<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
            });
        }

        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->change();
            $table->string('invoice_number')->nullable()->change();
            $table->integer('subtotal')->nullable()->change();
            $table->integer('grand_total')->nullable()->change();

            if (!Schema::hasColumn('orders', 'code')) {
                $table->string('code', 32)->nullable()->unique()->after('id');
            }
            if (!Schema::hasColumn('orders', 'customer_name')) {
                $table->string('customer_name')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('orders', 'customer_phone')) {
                $table->string('customer_phone', 30)->nullable()->after('customer_name');
            }
            if (!Schema::hasColumn('orders', 'customer_address')) {
                $table->text('customer_address')->nullable()->after('customer_phone');
            }
            if (!Schema::hasColumn('orders', 'total')) {
                $table->decimal('total', 15, 2)->default(0)->after('status');
            }
            if (!Schema::hasColumn('orders', 'confirmed_at')) {
                $table->timestamp('confirmed_at')->nullable()->after('notes');
            }

            $table->index('status');
            $table->index('created_at');
        });

        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('orders', function (Blueprint $table) {
                $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['created_at']);
            $table->dropColumn([
                'code',
                'customer_name',
                'customer_phone',
                'customer_address',
                'total',
                'confirmed_at',
            ]);
        });
    }
};
