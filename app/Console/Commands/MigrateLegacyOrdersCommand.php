<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateLegacyOrdersCommand extends Command
{
    protected $signature = 'megapress:migrate-legacy-data';

    protected $description = 'Backfill legacy orders and order_items into the WhatsApp flow schema idempotently.';

    public function handle(): int
    {
        DB::transaction(function () {
            $this->migrateOrders();
            $this->migrateOrderItems();
        });

        $this->info('Legacy data migration completed successfully.');

        return self::SUCCESS;
    }

    protected function migrateOrders(): void
    {
        Order::with('user')
            ->whereNull('code')
            ->chunkById(100, function ($orders) {
                foreach ($orders as $order) {
                    $code = $order->invoice_number ?: ('MP-LEGACY-' . $order->id);
                    if (Order::where('code', $code)->where('id', '!=', $order->id)->exists()) {
                        $code = 'MP-LEGACY-' . $order->id;
                    }

                    $newStatus = match (strtolower((string) $order->status)) {
                        'pending', 'pending_payment' => Order::STATUS_PENDING,
                        'processing', 'ready to pack', 'ready_to_ship', 'ready_for_pickup' => Order::STATUS_CONFIRMED,
                        'paid', 'shipped', 'picked_up' => Order::STATUS_PAID,
                        'completed' => Order::STATUS_COMPLETED,
                        'cancelled', 'refunded', 'expired', 'failed' => Order::STATUS_CANCELLED,
                        default => Order::STATUS_PENDING,
                    };

                    $order->update([
                        'code' => $code,
                        'customer_name' => $order->customer_name ?: ($order->user?->name ?? 'Pelanggan Lama'),
                        'customer_phone' => $order->customer_phone ?: ($order->user?->phone ?? '-'),
                        'customer_address' => $order->customer_address ?: ($order->user?->address ?? 'Alamat tidak tercatat'),
                        'total' => $order->total > 0 ? $order->total : ($order->grand_total ?? 0),
                        'status' => $newStatus,
                        'confirmed_at' => $order->confirmed_at ?? (in_array($newStatus, [Order::STATUS_CONFIRMED, Order::STATUS_PAID, Order::STATUS_COMPLETED], true) ? ($order->updated_at ?? $order->created_at) : null),
                        'completed_at' => $order->completed_at ?? ($newStatus === Order::STATUS_COMPLETED ? $order->updated_at : null),
                    ]);
                }
            });
    }

    protected function migrateOrderItems(): void
    {
        OrderItem::with('book')
            ->whereNull('title_snapshot')
            ->chunkById(200, function ($items) {
                foreach ($items as $item) {
                    $item->update([
                        'title_snapshot' => $item->book?->title ?? ('Buku (Arsip #' . $item->book_id . ')'),
                        'price_snapshot' => $item->price_snapshot ?? ($item->price ?? 0),
                        'qty' => $item->qty ?: ($item->quantity ?? 1),
                    ]);
                }
            });
    }
}
