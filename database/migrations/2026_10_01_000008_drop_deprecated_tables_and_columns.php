<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop FKs and deprecated columns on orders safely (works on both MySQL and SQLite)
        if (Schema::hasTable('orders')) {
            if (DB::getDriverName() === 'sqlite') {
                // SQLite cannot DROP COLUMN on columns referenced by inline FKs unless FKs are stripped via dropForeign/table rebuild
                foreach (['shipping_address_id', 'promo_voucher_id', 'shipping_voucher_id', 'cashback_voucher_id'] as $fkCol) {
                    if (Schema::hasColumn('orders', $fkCol)) {
                        try {
                            Schema::table('orders', fn (Blueprint $table) => $table->dropConstrainedForeignId($fkCol));
                        } catch (\Throwable $e) {
                            try {
                                Schema::table('orders', fn (Blueprint $table) => $table->dropForeign([$fkCol]));
                            } catch (\Throwable $ignored) {
                            }
                        }
                    }
                }
            } else {
                foreach (['shipping_address_id', 'promo_voucher_id', 'shipping_voucher_id', 'cashback_voucher_id'] as $fkCol) {
                    if (Schema::hasColumn('orders', $fkCol)) {
                        try {
                            Schema::table('orders', fn (Blueprint $table) => $table->dropForeign([$fkCol]));
                        } catch (\Throwable $e) {
                        }
                    }
                }
            }

            $orderDropCols = array_filter([
                'shipping_address_id',
                'shipping_fee',
                'insurance_fee',
                'discount',
                'tax',
                'shipping_method',
                'payment_method',
                'awb_number',
                'pickup_code',
                'picked_up_at',
                'snap_token',
                'promo_voucher_id',
                'shipping_voucher_id',
                'cashback_voucher_id',
                'promo_discount_amount',
                'shipping_discount_amount',
                'cashback_amount',
            ], fn (string $col) => Schema::hasColumn('orders', $col));

            if (!empty($orderDropCols)) {
                foreach ($orderDropCols as $col) {
                    Schema::table('orders', fn (Blueprint $table) => $table->dropColumn($col));
                }
            }
        }

        // Drop FK and deprecated columns on cart_items safely
        if (Schema::hasTable('cart_items')) {
            if (Schema::hasColumn('cart_items', 'cart_id')) {
                try {
                    Schema::table('cart_items', fn (Blueprint $table) => $table->dropForeign(['cart_id']));
                } catch (\Throwable $e) {
                }
            }

            $cartDropCols = array_filter(['cart_id', 'quantity'], fn (string $col) => Schema::hasColumn('cart_items', $col));
            foreach ($cartDropCols as $col) {
                Schema::table('cart_items', fn (Blueprint $table) => $table->dropColumn($col));
            }
        }

        // Drop deprecated tables in dependency order
        Schema::dropIfExists('voucher_usages');
        Schema::dropIfExists('vouchers');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_shipments');
        Schema::dropIfExists('refund_requests');
        Schema::dropIfExists('shipping_zones');
        Schema::dropIfExists('user_addresses');
        Schema::dropIfExists('carts');
        Schema::dropIfExists('personal_access_tokens');
    }

    public function down(): void
    {
        // One-way cleanup migration after user confirmation.
    }
};
