<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('promo_voucher_id')->nullable()->constrained('vouchers')->nullOnDelete()->after('grand_total');
            $table->foreignId('shipping_voucher_id')->nullable()->constrained('vouchers')->nullOnDelete()->after('promo_voucher_id');
            $table->foreignId('cashback_voucher_id')->nullable()->constrained('vouchers')->nullOnDelete()->after('shipping_voucher_id');
            $table->decimal('promo_discount_amount', 12, 2)->default(0)->after('cashback_voucher_id');
            $table->decimal('shipping_discount_amount', 12, 2)->default(0)->after('promo_discount_amount');
            $table->decimal('cashback_amount', 12, 2)->default(0)->after('shipping_discount_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['promo_voucher_id']);
            $table->dropForeign(['shipping_voucher_id']);
            $table->dropForeign(['cashback_voucher_id']);
            $table->dropColumn([
                'promo_voucher_id', 'shipping_voucher_id', 'cashback_voucher_id',
                'promo_discount_amount', 'shipping_discount_amount', 'cashback_amount'
            ]);
        });
    }
};
