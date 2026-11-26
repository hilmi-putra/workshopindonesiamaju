<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'payment_provider')) {
                $table->string('payment_provider')->nullable()->default('store')->after('payment_method');
            }
            if (!Schema::hasColumn('payments', 'transaction_id')) {
                $table->string('transaction_id')->nullable()->after('transaction_number');
            }
            if (!Schema::hasColumn('payments', 'payment_status')) {
                $table->string('payment_status')->nullable()->default('pending')->after('status');
            }
            if (!Schema::hasColumn('payments', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('payment_date');
            }
            if (!Schema::hasColumn('payments', 'expired_at')) {
                $table->timestamp('expired_at')->nullable()->after('paid_at');
            }
            if (!Schema::hasColumn('payments', 'payment_reference')) {
                $table->string('payment_reference')->nullable()->after('expired_at');
            }
            if (!Schema::hasColumn('payments', 'payment_metadata')) {
                $table->json('payment_metadata')->nullable()->after('response_payload');
            }
        });

        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('shipping_method');
            }
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'payment_provider',
                'transaction_id',
                'payment_status',
                'paid_at',
                'expired_at',
                'payment_reference',
                'payment_metadata'
            ]);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('payment_method');
        });
    }
};
