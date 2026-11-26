<?php

namespace App\Services\Admin;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Book;
use App\Models\PageView;
use App\Models\BookEvent;
use App\Models\SearchHistory;
use App\Models\RefundRequest;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportService
{
    /**
     * Parse date range from filters.
     * Supports: 'today', '7_days', '30_days', 'this_month', 'this_year', or custom 'start_date' & 'end_date'.
     */
    public static function parseDateRange(array $filters): array
    {
        $now = Carbon::now();
        $period = $filters['period'] ?? null;

        if ($period === 'today') {
            $startDate = $now->copy()->startOfDay();
            $endDate = $now->copy()->endOfDay();
        } elseif ($period === '7_days' || $period === '7days') {
            $startDate = $now->copy()->subDays(6)->startOfDay();
            $endDate = $now->copy()->endOfDay();
        } elseif ($period === '30_days' || $period === '30days') {
            $startDate = $now->copy()->subDays(29)->startOfDay();
            $endDate = $now->copy()->endOfDay();
        } elseif ($period === 'this_month' || $period === 'month') {
            $startDate = $now->copy()->startOfMonth();
            $endDate = $now->copy()->endOfMonth();
        } elseif ($period === 'this_year' || $period === 'year') {
            $startDate = $now->copy()->startOfYear();
            $endDate = $now->copy()->endOfYear();
        } elseif (!empty($filters['start_date']) || !empty($filters['end_date'])) {
            $startDate = !empty($filters['start_date']) ? Carbon::parse($filters['start_date'])->startOfDay() : null;
            $endDate = !empty($filters['end_date']) ? Carbon::parse($filters['end_date'])->endOfDay() : null;
        } else {
            // Default: All-time or last 30 days if preferred. Let's make default last 30 days
            $startDate = $now->copy()->subDays(29)->startOfDay();
            $endDate = $now->copy()->endOfDay();
        }

        return [$startDate, $endDate];
    }

    /**
     * Apply date range constraint to a query.
     */
    public static function applyDateRange($query, array $dateRange, string $column = 'created_at')
    {
        [$startDate, $endDate] = $dateRange;
        if ($startDate) {
            $query->where($column, '>=', $startDate);
        }
        if ($endDate) {
            $query->where($column, '<=', $endDate);
        }
        return $query;
    }

    /**
     * Constraint query to strictly PAID / VALID orders (QRISly paid, COD paid confirmed, not cancelled).
     */
    public static function isOrderPaidQuery($query)
    {
        return $query->where('orders.status', '!=', 'Cancelled')
            ->whereHas('payment', function ($q) {
                $q->where(function ($sub) {
                    $sub->whereIn(DB::raw('LOWER(payment_status)'), ['paid', 'settlement', 'lunas'])
                        ->orWhereIn(DB::raw('LOWER(status)'), ['paid', 'success', 'settlement', 'lunas']);
                });
            });
    }

    // ==========================================
    // 1. LAPORAN PENJUALAN (SALES REPORT)
    // ==========================================

    public function getSalesQuery(array $filters)
    {
        $dateRange = self::parseDateRange($filters);

        $query = Order::with([
            'items.book.publisher', 
            'items.book.author', 
            'items.book.categories', 
            'user', 
            'payment', 
            'shipment', 
            'refundRequest'
        ]);

        self::applyDateRange($query, $dateRange, 'orders.created_at');

        // Search by invoice or customer name/email
        if (!empty($filters['search'])) {
            $search = trim($filters['search']);
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($qu) use ($search) {
                      $qu->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Order Status filter
        if (!empty($filters['status']) && $filters['status'] !== 'all') {
            $query->where('status', $filters['status']);
        }

        // Payment Method filter
        if (!empty($filters['payment_method']) && $filters['payment_method'] !== 'all') {
            $method = strtolower($filters['payment_method']);
            $query->where('payment_method', $method);
        }

        // Payment Status filter (paid / unpaid)
        if (!empty($filters['payment_status']) && $filters['payment_status'] !== 'all') {
            $pStatus = strtolower($filters['payment_status']);
            $query->whereHas('payment', function ($q) use ($pStatus) {
                if ($pStatus === 'paid') {
                    $q->whereIn(DB::raw('LOWER(payment_status)'), ['paid', 'settlement', 'lunas'])
                      ->orWhereIn(DB::raw('LOWER(status)'), ['paid', 'success', 'settlement', 'lunas']);
                } else {
                    $q->whereNotIn(DB::raw('LOWER(payment_status)'), ['paid', 'settlement', 'lunas'])
                      ->whereNotIn(DB::raw('LOWER(status)'), ['paid', 'success', 'settlement', 'lunas']);
                }
            });
        }

        // Shipping Method filter (pickup vs courier)
        if (!empty($filters['shipping_method']) && $filters['shipping_method'] !== 'all') {
            if ($filters['shipping_method'] === 'pickup') {
                $query->where('shipping_method', 'like', '%pickup%');
            } else {
                $query->where('shipping_method', 'not like', '%pickup%');
            }
        }

        // Publisher filter
        if (!empty($filters['publisher_id'])) {
            $query->whereHas('items.book', function ($q) use ($filters) {
                $q->where('publisher_id', $filters['publisher_id']);
            });
        }

        // Category filter
        if (!empty($filters['category_id'])) {
            $query->whereHas('items.book.categories', function ($q) use ($filters) {
                $q->where('categories.id', $filters['category_id']);
            });
        }

        // Specific Book filter
        if (!empty($filters['book_id'])) {
            $query->whereHas('items', function ($q) use ($filters) {
                $q->where('book_id', $filters['book_id']);
            });
        }

        return $query->latest('created_at');
    }

    /**
     * Compute comprehensive sales summary cards according to filters.
     */
    public function getSalesSummary(array $filters): array
    {
        $baseQuery = $this->getSalesQuery($filters);

        // Clones for aggregate metrics
        $allOrders = (clone $baseQuery)->get();

        $totalOrders = $allOrders->count();
        $subtotal = $allOrders->sum('subtotal');
        $discount = $allOrders->sum('discount') + $allOrders->sum('promo_discount_amount');
        $shippingFee = $allOrders->sum('shipping_fee');
        $grandTotal = $allOrders->sum('grand_total');

        // Total products sold
        $totalItemsSold = 0;
        foreach ($allOrders as $ord) {
            $totalItemsSold += $ord->items->sum('quantity');
        }

        // Status counts
        $successfulOrders = 0;
        $pendingOrders = 0;
        $cancelledOrders = 0;
        $refundedOrders = 0;

        foreach ($allOrders as $ord) {
            $isPaid = false;
            if ($ord->payment) {
                $pStatus = strtolower($ord->payment->payment_status ?? ($ord->payment->status ?? ''));
                $isPaid = in_array($pStatus, ['paid', 'settlement', 'lunas']);
            }

            if ($ord->status === 'Cancelled') {
                $cancelledOrders++;
            } elseif ($ord->refundRequest && $ord->refundRequest->status === 'Approved') {
                $refundedOrders++;
            } elseif ($isPaid) {
                $successfulOrders++;
            } else {
                $pendingOrders++;
            }
        }

        return [
            'total_orders' => $totalOrders,
            'total_items_sold' => $totalItemsSold,
            'subtotal' => (float) $subtotal,
            'discount' => (float) $discount,
            'shipping_fee' => (float) $shippingFee,
            'grand_total' => (float) $grandTotal,
            'successful_orders' => $successfulOrders,
            'pending_orders' => $pendingOrders,
            'cancelled_orders' => $cancelledOrders,
            'refunded_orders' => $refundedOrders,
        ];
    }

    // ==========================================
    // 2. REVENUE (ACTUAL RECEIVED REVENUE)
    // ==========================================

    public function getRevenueStats(array $filters): array
    {
        $dateRange = self::parseDateRange($filters);

        // Strictly PAID orders
        $query = Order::with(['payment', 'items', 'refundRequest']);
        self::isOrderPaidQuery($query);
        self::applyDateRange($query, $dateRange, 'orders.created_at');

        $paidOrders = (clone $query)->get();

        $successfulTransactions = $paidOrders->count();
        $grossSales = (float) $paidOrders->sum('subtotal');
        $totalDiscount = (float) ($paidOrders->sum('discount') + $paidOrders->sum('promo_discount_amount'));
        $shippingRevenue = (float) $paidOrders->sum('shipping_fee');
        $totalCollected = (float) $paidOrders->sum('grand_total');

        // Calculate refunds from approved refund requests in the date range
        $refundQuery = RefundRequest::where('status', 'Approved');
        self::applyDateRange($refundQuery, $dateRange, 'created_at');
        $approvedRefunds = $refundQuery->with('order')->get();
        $totalRefunds = (float) $approvedRefunds->sum(function ($r) {
            return $r->order ? $r->order->grand_total : 0;
        });

        // Net revenue = Total revenue received minus refunds
        $netRevenue = max(0, $totalCollected - $totalRefunds);

        // Average Order Value
        $averageOrderValue = $successfulTransactions > 0 ? round($totalCollected / $successfulTransactions) : 0;

        // Daily trend data for chart
        $dailyData = [];
        [$startDate, $endDate] = $dateRange;
        
        // Group paid orders by date
        $groupedByDate = $paidOrders->groupBy(function ($o) {
            return $o->created_at->format('Y-m-d');
        });

        foreach ($groupedByDate as $date => $ordersOnDate) {
            $dateGross = $ordersOnDate->sum('subtotal');
            $dateDiscount = $ordersOnDate->sum('discount') + $ordersOnDate->sum('promo_discount_amount');
            $dateShipping = $ordersOnDate->sum('shipping_fee');
            $dateTotal = $ordersOnDate->sum('grand_total');
            $dateOrders = $ordersOnDate->count();

            $dailyData[$date] = [
                'date' => $date,
                'orders_count' => $dateOrders,
                'gross_sales' => (float) $dateGross,
                'discount' => (float) $dateDiscount,
                'shipping_fee' => (float) $dateShipping,
                'total_revenue' => (float) $dateTotal,
                'refunds' => 0.0,
                'net_revenue' => (float) $dateTotal,
            ];
        }

        // Incorporate refunds into daily data
        foreach ($approvedRefunds as $ref) {
            $refDate = $ref->created_at->format('Y-m-d');
            $refAmount = $ref->order ? $ref->order->grand_total : 0;
            if (isset($dailyData[$refDate])) {
                $dailyData[$refDate]['refunds'] += $refAmount;
                $dailyData[$refDate]['net_revenue'] = max(0, $dailyData[$refDate]['net_revenue'] - $refAmount);
            }
        }

        ksort($dailyData);
        $chartData = array_values($dailyData);

        // Payment method breakdown (QRISly vs COD)
        $qrisOrders = $paidOrders->filter(fn($o) => strtolower($o->payment_method ?? ($o->payment?->payment_method ?? '')) === 'qris');
        $codOrders = $paidOrders->filter(fn($o) => strtolower($o->payment_method ?? ($o->payment?->payment_method ?? '')) === 'cod');

        $paymentBreakdown = [
            'qris' => [
                'name' => 'QRIS (QRISly)',
                'count' => $qrisOrders->count(),
                'total' => (float) $qrisOrders->sum('grand_total'),
                'percentage' => $totalCollected > 0 ? round(($qrisOrders->sum('grand_total') / $totalCollected) * 100, 1) : 0,
            ],
            'cod' => [
                'name' => 'COD / Bayar di Toko',
                'count' => $codOrders->count(),
                'total' => (float) $codOrders->sum('grand_total'),
                'percentage' => $totalCollected > 0 ? round(($codOrders->sum('grand_total') / $totalCollected) * 100, 1) : 0,
            ],
        ];

        return [
            'gross_sales' => $grossSales,
            'total_discount' => $totalDiscount,
            'shipping_revenue' => $shippingRevenue,
            'total_revenue' => $totalCollected,
            'total_refunds' => $totalRefunds,
            'net_revenue' => $netRevenue,
            'successful_transactions' => $successfulTransactions,
            'average_order_value' => $averageOrderValue,
            'chart_data' => $chartData,
            'payment_breakdown' => $paymentBreakdown,
            'daily_breakdown' => $chartData,
        ];
    }

    // ==========================================
    // 3. PRODUK TERLARIS (TOP PRODUCTS)
    // ==========================================

    public function getBestSellersQuery(array $filters)
    {
        $dateRange = self::parseDateRange($filters);

        $query = OrderItem::select(
            'order_items.book_id',
            DB::raw('SUM(order_items.quantity) as total_sold'),
            DB::raw('SUM(order_items.subtotal) as total_revenue'),
            DB::raw('COUNT(DISTINCT order_items.order_id) as total_buyers'),
            DB::raw('ROUND(SUM(order_items.subtotal) / SUM(order_items.quantity), 0) as average_price')
        )
        ->whereHas('order', function ($q) use ($dateRange) {
            ReportService::isOrderPaidQuery($q);
            ReportService::applyDateRange($q, $dateRange, 'orders.created_at');
        })
        ->with(['book.author', 'book.publisher', 'book.categories'])
        ->groupBy('order_items.book_id');

        if (!empty($filters['category_id'])) {
            $query->whereHas('book.categories', function ($q) use ($filters) {
                $q->where('categories.id', $filters['category_id']);
            });
        }

        if (!empty($filters['publisher_id'])) {
            $query->whereHas('book', function ($q) use ($filters) {
                $q->where('publisher_id', $filters['publisher_id']);
            });
        }

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);
            $query->whereHas('book', function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            });
        }

        return $query->orderByDesc('total_sold')->orderByDesc('total_revenue');
    }

    /**
     * Get transaction orders for a specific best seller product.
     */
    public function getBestSellerOrderDetails(int $bookId, array $filters)
    {
        $dateRange = self::parseDateRange($filters);

        $query = OrderItem::where('book_id', $bookId)
            ->whereHas('order', function ($q) use ($dateRange) {
                ReportService::isOrderPaidQuery($q);
                ReportService::applyDateRange($q, $dateRange, 'orders.created_at');
            })
            ->with(['order.user', 'order.payment'])
            ->latest('created_at');

        return $query->paginate(10);
    }

    // ==========================================
    // 4. ANALYTICS TRAFIK (TRAFFIC STATS)
    // ==========================================

    public function getTrafficStats(array $filters): array
    {
        $dateRange = self::parseDateRange($filters);

        $pageViewsQuery = PageView::query();
        $bookEventsQuery = BookEvent::query();
        $searchQuery = SearchHistory::query();

        self::applyDateRange($pageViewsQuery, $dateRange, 'created_at');
        self::applyDateRange($bookEventsQuery, $dateRange, 'created_at');
        self::applyDateRange($searchQuery, $dateRange, 'created_at');

        $totalVisitors = (clone $pageViewsQuery)->distinct('ip_address')->count('ip_address');
        $totalPagesViewed = (clone $pageViewsQuery)->count();
        $totalSearches = (clone $searchQuery)->count();
        $totalInteractions = (clone $bookEventsQuery)->count();

        // Count paid orders in period for conversion rate calculation
        $orderQuery = Order::query();
        self::isOrderPaidQuery($orderQuery);
        self::applyDateRange($orderQuery, $dateRange, 'created_at');
        $paidOrdersCount = $orderQuery->count();

        $conversionRate = $totalVisitors > 0 ? round(($paidOrdersCount / $totalVisitors) * 100, 2) : 0;

        // Most viewed pages
        $mostViewedPages = (clone $pageViewsQuery)
            ->select('path', DB::raw('COUNT(*) as views'), DB::raw('COUNT(DISTINCT ip_address) as unique_visitors'))
            ->groupBy('path')
            ->orderByDesc('views')
            ->take(8)
            ->get();

        // Most searched keywords
        $mostSearchedKeywords = (clone $searchQuery)
            ->select('keyword', DB::raw('COUNT(*) as count'))
            ->groupBy('keyword')
            ->orderByDesc('count')
            ->take(8)
            ->get();

        // Most viewed books
        $mostViewedBooks = (clone $bookEventsQuery)
            ->where('event_type', 'view')
            ->select('book_id', DB::raw('COUNT(*) as views'))
            ->with('book.author')
            ->groupBy('book_id')
            ->orderByDesc('views')
            ->take(8)
            ->get();

        // Most wishlisted books
        $mostWishlistedBooks = (clone $bookEventsQuery)
            ->where('event_type', 'wishlist')
            ->select('book_id', DB::raw('COUNT(*) as count'))
            ->with('book.author')
            ->groupBy('book_id')
            ->orderByDesc('count')
            ->take(8)
            ->get();

        // Daily chart data
        $chartData = (clone $pageViewsQuery)
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(DISTINCT ip_address) as visitors'),
                DB::raw('COUNT(*) as pageviews')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Device breakdown estimation from user_agent
        $agents = (clone $pageViewsQuery)->select('user_agent')->get();
        $desktopCount = 0;
        $mobileCount = 0;
        $tabletCount = 0;

        foreach ($agents as $ag) {
            $ua = strtolower($ag->user_agent ?? '');
            if (str_contains($ua, 'ipad') || str_contains($ua, 'tablet')) {
                $tabletCount++;
            } elseif (str_contains($ua, 'mobile') || str_contains($ua, 'android') || str_contains($ua, 'iphone')) {
                $mobileCount++;
            } else {
                $desktopCount++;
            }
        }

        $totalDevices = max(1, $desktopCount + $mobileCount + $tabletCount);
        $deviceBreakdown = [
            'desktop' => [
                'count' => $desktopCount,
                'percentage' => round(($desktopCount / $totalDevices) * 100, 1),
            ],
            'mobile' => [
                'count' => $mobileCount,
                'percentage' => round(($mobileCount / $totalDevices) * 100, 1),
            ],
            'tablet' => [
                'count' => $tabletCount,
                'percentage' => round(($tabletCount / $totalDevices) * 100, 1),
            ],
        ];

        return [
            'total_visitors' => $totalVisitors,
            'total_page_views' => $totalPagesViewed,
            'total_searches' => $totalSearches,
            'total_interactions' => $totalInteractions,
            'paid_orders_count' => $paidOrdersCount,
            'conversion_rate' => $conversionRate,
            'most_viewed_pages' => $mostViewedPages,
            'most_searched_keywords' => $mostSearchedKeywords,
            'most_viewed_books' => $mostViewedBooks,
            'most_wishlisted_books' => $mostWishlistedBooks,
            'device_breakdown' => $deviceBreakdown,
            'chart_data' => $chartData,
            'has_data' => $totalPagesViewed > 0,
        ];
    }
}
