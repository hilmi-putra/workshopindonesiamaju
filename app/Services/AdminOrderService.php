<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use RuntimeException;

class AdminOrderService
{
    public function transitionStatus(Order $order, string $newStatus, User $admin, ?string $note = null): Order
    {
        $allowed = array_keys(Order::statuses());
        if (!in_array($newStatus, $allowed, true)) {
            throw new InvalidArgumentException("Status tidak valid: {$newStatus}");
        }

        return DB::transaction(function () use ($order, $newStatus, $admin, $note) {
            $lockedOrder = Order::with('items')
                ->whereKey($order->id)
                ->lockForUpdate()
                ->firstOrFail();

            $oldStatus = $lockedOrder->status;
            if ($oldStatus === $newStatus) {
                return $lockedOrder;
            }

            // Net change from stock_movements for this order: negative means stock is currently deducted
            $sumChange = (int) StockMovement::where('order_id', $lockedOrder->id)->sum('change');
            $isCurrentlyDeducted = $sumChange < 0;

            $deductStates = [Order::STATUS_CONFIRMED, Order::STATUS_PAID, Order::STATUS_COMPLETED];

            // 1. Deduct stock once when moving into confirmed/paid/completed and not yet deducted
            if (in_array($newStatus, $deductStates, true) && !$isCurrentlyDeducted) {
                $bookIds = $lockedOrder->items->pluck('book_id')->filter()->unique()->sort()->values();
                $books = Book::whereIn('id', $bookIds)->orderBy('id')->lockForUpdate()->get()->keyBy('id');

                foreach ($lockedOrder->items as $item) {
                    if (!$item->book_id) {
                        continue;
                    }
                    $book = $books->get($item->book_id);
                    if (!$book) {
                        throw new RuntimeException("Buku \"{$item->display_title}\" tidak ditemukan di katalog.");
                    }

                    $qty = $item->display_qty;
                    if ($book->stock < $qty) {
                        throw new RuntimeException("Stok buku \"{$book->title}\" tidak mencukupi (tersedia: {$book->stock}, dibutuhkan: {$qty}).");
                    }
                }

                foreach ($lockedOrder->items as $item) {
                    if (!$item->book_id) {
                        continue;
                    }
                    $book = $books->get($item->book_id);
                    $qty = $item->display_qty;
                    $book->stock = (int) $book->stock - $qty;
                    $book->save();

                    StockMovement::create([
                        'book_id' => $book->id,
                        'change' => -$qty,
                        'reason' => 'order_' . $newStatus,
                        'order_id' => $lockedOrder->id,
                        'admin_id' => $admin->id,
                        'stock_after' => $book->stock,
                    ]);
                }
            }

            // 2. Restore stock once when moving to cancelled if stock was previously deducted
            if ($newStatus === Order::STATUS_CANCELLED && $isCurrentlyDeducted) {
                $bookIds = $lockedOrder->items->pluck('book_id')->filter()->unique()->sort()->values();
                $books = Book::whereIn('id', $bookIds)->orderBy('id')->lockForUpdate()->get()->keyBy('id');

                foreach ($lockedOrder->items as $item) {
                    if (!$item->book_id) {
                        continue;
                    }
                    $book = $books->get($item->book_id);
                    if (!$book) {
                        continue;
                    }

                    $qty = $item->display_qty;
                    $book->stock = (int) $book->stock + $qty;
                    $book->save();

                    StockMovement::create([
                        'book_id' => $book->id,
                        'change' => $qty,
                        'reason' => 'order_cancelled',
                        'order_id' => $lockedOrder->id,
                        'admin_id' => $admin->id,
                        'stock_after' => $book->stock,
                    ]);
                }
            }

            $updateData = ['status' => $newStatus];
            if (in_array($newStatus, $deductStates, true) && !$lockedOrder->confirmed_at) {
                $updateData['confirmed_at'] = now();
            }
            if ($newStatus === Order::STATUS_COMPLETED && !$lockedOrder->completed_at) {
                $updateData['completed_at'] = now();
            }

            $lockedOrder->update($updateData);

            $lockedOrder->timeline()->create([
                'status' => $newStatus,
                'description' => $note ?: "Status diubah dari {$oldStatus} menjadi {$newStatus} oleh {$admin->name}.",
                'user_id' => $admin->id,
                'actor_name' => $admin->name,
                'action' => 'status_change',
                'old_value' => $oldStatus,
                'new_value' => $newStatus,
            ]);

            return $lockedOrder->fresh(['items.book', 'timeline', 'stockMovements']);
        });
    }

    public function adjustStock(Book $book, int $change, string $reason, User $admin): StockMovement
    {
        if ($change === 0) {
            throw new InvalidArgumentException('Perubahan stok tidak boleh 0.');
        }

        return DB::transaction(function () use ($book, $change, $reason, $admin) {
            $lockedBook = Book::whereKey($book->id)->lockForUpdate()->firstOrFail();
            $newStock = (int) $lockedBook->stock + $change;

            if ($newStock < 0) {
                throw new RuntimeException("Stok tidak boleh kurang dari 0 (stok saat ini: {$lockedBook->stock}).");
            }

            $lockedBook->stock = $newStock;
            $lockedBook->save();

            return StockMovement::create([
                'book_id' => $lockedBook->id,
                'change' => $change,
                'reason' => $reason,
                'order_id' => null,
                'admin_id' => $admin->id,
                'stock_after' => $newStock,
            ]);
        });
    }

    public function createManualOrder(array $data, array $items, User $admin): Order
    {
        if (empty($items)) {
            throw new InvalidArgumentException('Pesanan harus memiliki minimal 1 buku.');
        }

        return DB::transaction(function () use ($data, $items, $admin) {
            $checkout = app(WhatsAppCheckoutService::class);
            $bookIds = collect($items)->pluck('book_id')->unique()->sort()->values();
            $books = Book::whereIn('id', $bookIds)->orderBy('id')->lockForUpdate()->get()->keyBy('id');

            $total = 0;
            $prepared = [];
            foreach ($items as $row) {
                $book = $books->get((int) $row['book_id']);
                $qty = max(1, (int) $row['qty']);
                if (!$book) {
                    throw new RuntimeException('Salah satu buku tidak ditemukan.');
                }

                $price = (float) $book->final_price;
                $lineTotal = round($price * $qty, 2);
                $total += $lineTotal;

                $prepared[] = [
                    'book' => $book,
                    'price' => $price,
                    'qty' => $qty,
                    'line_total' => $lineTotal,
                ];
            }

            $order = Order::create([
                'code' => $checkout->generateOrderCode(),
                'user_id' => $data['user_id'] ?? null,
                'customer_name' => $data['customer_name'],
                'customer_phone' => $data['customer_phone'],
                'customer_address' => $data['customer_address'],
                'notes' => $data['notes'] ?? 'Pesanan manual (admin).',
                'status' => Order::STATUS_PENDING,
                'total' => $total,
            ]);

            foreach ($prepared as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'book_id' => $item['book']->id,
                    'title_snapshot' => $item['book']->title,
                    'price_snapshot' => $item['price'],
                    'qty' => $item['qty'],
                    'quantity' => $item['qty'],
                    'price' => (int) round($item['price']),
                    'subtotal' => (int) round($item['line_total']),
                ]);
            }

            $order->timeline()->create([
                'status' => Order::STATUS_PENDING,
                'description' => "Pesanan manual dibuat oleh admin {$admin->name}.",
                'user_id' => $admin->id,
                'actor_name' => $admin->name,
                'action' => 'manual_created',
                'new_value' => Order::STATUS_PENDING,
            ]);

            $targetStatus = $data['status'] ?? Order::STATUS_CONFIRMED;
            if ($targetStatus !== Order::STATUS_PENDING) {
                return $this->transitionStatus($order, $targetStatus, $admin, 'Status awal pesanan manual.');
            }

            return $order->fresh(['items.book', 'timeline']);
        });
    }
}
