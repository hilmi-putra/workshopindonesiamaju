<?php

namespace App\Services\Admin;

use App\Models\Book;
use App\Models\Author;
use App\Models\Publisher;
use App\Models\OrderItem;
use App\Models\RoyaltyPayment;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RoyaltyService
{
    const DEFAULT_ROYALTY_PERCENTAGE = 10.0;

    /**
     * Get overall royalty summary statistics.
     */
    public function getRoyaltySummary(array $filters): array
    {
        $dateRange = ReportService::parseDateRange($filters);

        // Fetch all order items from paid orders
        $itemsQuery = OrderItem::whereHas('order', function ($q) use ($dateRange) {
            ReportService::isOrderPaidQuery($q);
            ReportService::applyDateRange($q, $dateRange, 'orders.created_at');
        })->with('book');

        $items = $itemsQuery->get();

        $totalSales = 0.0;
        $totalEarned = 0.0;
        $activeAuthors = [];

        foreach ($items as $item) {
            $subtotal = (float) $item->subtotal;
            $rate = (float) ($item->book?->royalty_percentage ?? self::DEFAULT_ROYALTY_PERCENTAGE);
            $totalSales += $subtotal;
            $totalEarned += ($subtotal * ($rate / 100));

            if ($item->book?->author_id) {
                $activeAuthors[$item->book->author_id] = true;
            }
        }

        // Payouts within date range
        $payoutQuery = RoyaltyPayment::where('status', 'paid');
        ReportService::applyDateRange($payoutQuery, $dateRange, 'paid_at');
        $totalPaidInPeriod = (float) $payoutQuery->sum('amount');

        // All-time total paid vs all-time total earned for real outstanding calculation
        $allTimePaid = (float) RoyaltyPayment::where('status', 'paid')->sum('amount');
        
        $allTimeItems = OrderItem::whereHas('order', function ($q) {
            ReportService::isOrderPaidQuery($q);
        })->with('book')->get();

        $allTimeEarned = 0.0;
        foreach ($allTimeItems as $it) {
            $rate = (float) ($it->book?->royalty_percentage ?? self::DEFAULT_ROYALTY_PERCENTAGE);
            $allTimeEarned += ((float) $it->subtotal * ($rate / 100));
        }

        $outstandingRoyalty = max(0, $allTimeEarned - $allTimePaid);

        return [
            'total_sales' => $totalSales,
            'total_royalty_earned' => round($totalEarned, 2),
            'total_royalty_paid' => round($totalPaidInPeriod, 2),
            'total_royalty_pending' => round($outstandingRoyalty, 2),
            'active_authors_count' => count($activeAuthors),
            'default_royalty_percentage' => self::DEFAULT_ROYALTY_PERCENTAGE,
        ];
    }

    /**
     * Get aggregated royalties grouped by Author and Publisher.
     */
    public function getAggregatedRoyalties(array $filters): array
    {
        $dateRange = ReportService::parseDateRange($filters);
        $search = strtolower(trim($filters['search'] ?? ''));

        // Query items from paid orders
        $itemsQuery = OrderItem::whereHas('order', function ($q) use ($dateRange) {
            ReportService::isOrderPaidQuery($q);
            ReportService::applyDateRange($q, $dateRange, 'orders.created_at');
        })->with(['book.author', 'book.publisher']);

        if (!empty($filters['author_id'])) {
            $itemsQuery->whereHas('book', fn($b) => $b->where('author_id', $filters['author_id']));
        }
        if (!empty($filters['publisher_id'])) {
            $itemsQuery->whereHas('book', fn($b) => $b->where('publisher_id', $filters['publisher_id']));
        }

        $items = $itemsQuery->get();

        $authorsMap = [];
        $publishersMap = [];

        foreach ($items as $item) {
            $book = $item->book;
            if (!$book) continue;

            $subtotal = (float) $item->subtotal;
            $quantity = (int) $item->quantity;
            $royaltyRate = (float) ($book->royalty_percentage ?? self::DEFAULT_ROYALTY_PERCENTAGE);
            $earned = $subtotal * ($royaltyRate / 100);

            // Group by Author
            if ($book->author_id && $book->author) {
                $aId = $book->author_id;
                if (!isset($authorsMap[$aId])) {
                    $authorsMap[$aId] = [
                        'entity_type' => 'Author',
                        'entity_id' => $aId,
                        'entity_name' => $book->author->name,
                        'email' => $book->author->email ?? '-',
                        'books_map' => [],
                        'units_sold' => 0,
                        'total_sales' => 0.0,
                        'total_earned' => 0.0,
                        'total_paid' => 0.0,
                        'total_pending' => 0.0,
                    ];
                }
                $authorsMap[$aId]['books_map'][$book->id] = true;
                $authorsMap[$aId]['units_sold'] += $quantity;
                $authorsMap[$aId]['total_sales'] += $subtotal;
                $authorsMap[$aId]['total_earned'] += $earned;
            }

            // Group by Publisher
            if ($book->publisher_id && $book->publisher) {
                $pId = $book->publisher_id;
                if (!isset($publishersMap[$pId])) {
                    $publishersMap[$pId] = [
                        'entity_type' => 'Publisher',
                        'entity_id' => $pId,
                        'entity_name' => $book->publisher->name,
                        'email' => '-',
                        'books_map' => [],
                        'units_sold' => 0,
                        'total_sales' => 0.0,
                        'total_earned' => 0.0,
                        'total_paid' => 0.0,
                        'total_pending' => 0.0,
                    ];
                }
                $publishersMap[$pId]['books_map'][$book->id] = true;
                $publishersMap[$pId]['units_sold'] += $quantity;
                $publishersMap[$pId]['total_sales'] += $subtotal;
                $publishersMap[$pId]['total_earned'] += $earned;
            }
        }

        // Calculate paid amounts for all mapped entities
        foreach ($authorsMap as $aId => &$aData) {
            $paid = (float) RoyaltyPayment::where('author_id', $aId)->where('status', 'paid')->sum('amount');
            $aData['total_paid'] = $paid;
            $aData['total_pending'] = max(0, $aData['total_earned'] - $paid);
            $aData['books_count'] = count($aData['books_map']);
            $aData['avg_percentage'] = $aData['total_sales'] > 0 
                ? round(($aData['total_earned'] / $aData['total_sales']) * 100, 1) 
                : self::DEFAULT_ROYALTY_PERCENTAGE;
            unset($aData['books_map']);
        }

        foreach ($publishersMap as $pId => &$pData) {
            $paid = (float) RoyaltyPayment::where('publisher_id', $pId)->where('status', 'paid')->sum('amount');
            $pData['total_paid'] = $paid;
            $pData['total_pending'] = max(0, $pData['total_earned'] - $paid);
            $pData['books_count'] = count($pData['books_map']);
            $pData['avg_percentage'] = $pData['total_sales'] > 0 
                ? round(($pData['total_earned'] / $pData['total_sales']) * 100, 1) 
                : self::DEFAULT_ROYALTY_PERCENTAGE;
            unset($pData['books_map']);
        }

        $authorList = array_values($authorsMap);
        $publisherList = array_values($publishersMap);

        // Apply search filter if provided
        if ($search !== '') {
            $authorList = array_values(array_filter($authorList, fn($a) => str_contains(strtolower($a['entity_name']), $search)));
            $publisherList = array_values(array_filter($publisherList, fn($p) => str_contains(strtolower($p['entity_name']), $search)));
        }

        return [
            'summary' => $this->getRoyaltySummary($filters),
            'authors' => $authorList,
            'publishers' => $publisherList,
            'all' => array_merge($authorList, $publisherList),
        ];
    }

    /**
     * Get detailed breakdown of an entity's books and orders for transparency.
     */
    public function getEntityDetails(string $entityType, int $entityId, array $filters): array
    {
        $dateRange = ReportService::parseDateRange($filters);

        $itemsQuery = OrderItem::whereHas('order', function ($q) use ($dateRange) {
            ReportService::isOrderPaidQuery($q);
            ReportService::applyDateRange($q, $dateRange, 'orders.created_at');
        })
        ->whereHas('book', function ($b) use ($entityType, $entityId) {
            if ($entityType === 'Author') {
                $b->where('author_id', $entityId);
            } else {
                $b->where('publisher_id', $entityId);
            }
        })
        ->with(['order.user', 'order.payment', 'book']);

        $items = $itemsQuery->latest('created_at')->get();

        // Books breakdown
        $booksMap = [];
        $transactions = [];

        foreach ($items as $item) {
            $book = $item->book;
            if (!$book) continue;

            $bId = $book->id;
            $rate = (float) ($book->royalty_percentage ?? self::DEFAULT_ROYALTY_PERCENTAGE);
            $subtotal = (float) $item->subtotal;
            $royalty = $subtotal * ($rate / 100);

            if (!isset($booksMap[$bId])) {
                $booksMap[$bId] = [
                    'book_id' => $bId,
                    'title' => $book->title,
                    'price' => (float) $book->price,
                    'royalty_percentage' => $rate,
                    'units_sold' => 0,
                    'total_revenue' => 0.0,
                    'total_royalty' => 0.0,
                ];
            }

            $booksMap[$bId]['units_sold'] += $item->quantity;
            $booksMap[$bId]['total_revenue'] += $subtotal;
            $booksMap[$bId]['total_royalty'] += $royalty;

            $transactions[] = [
                'order_id' => $item->order_id,
                'invoice_number' => $item->order?->invoice_number,
                'customer_name' => $item->order?->user?->name ?? 'Pelanggan',
                'book_title' => $book->title,
                'quantity' => $item->quantity,
                'price' => (float) $item->price,
                'subtotal' => $subtotal,
                'royalty_percentage' => $rate,
                'royalty_amount' => round($royalty, 2),
                'order_date' => $item->order?->created_at->format('Y-m-d H:i'),
                'payment_method' => strtoupper($item->order?->payment_method ?? '-'),
            ];
        }

        // Entity name
        $name = 'Unknown';
        if ($entityType === 'Author') {
            $name = Author::find($entityId)?->name ?? 'Unknown Author';
        } else {
            $name = Publisher::find($entityId)?->name ?? 'Unknown Publisher';
        }

        return [
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'entity_name' => $name,
            'books' => array_values($booksMap),
            'transactions' => $transactions,
        ];
    }

    /**
     * Update the royalty percentage of a book.
     */
    public function updateBookRoyalty(int $bookId, float $percentage): Book
    {
        $book = Book::findOrFail($bookId);
        $book->royalty_percentage = $percentage;
        $book->save();
        return $book;
    }
}
