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
            Schema::table('order_items', function (Blueprint $table) {
                $table->dropForeign(['book_id']);
            });
        }

        Schema::table('order_items', function (Blueprint $table) {
            $table->unsignedBigInteger('book_id')->nullable()->change();
            $table->integer('quantity')->nullable()->change();
            $table->integer('price')->nullable()->change();
            $table->integer('subtotal')->nullable()->change();

            if (!Schema::hasColumn('order_items', 'title_snapshot')) {
                $table->string('title_snapshot')->nullable()->after('book_id');
            }
            if (!Schema::hasColumn('order_items', 'price_snapshot')) {
                $table->decimal('price_snapshot', 15, 2)->nullable()->after('title_snapshot');
            }
            if (!Schema::hasColumn('order_items', 'qty')) {
                $table->unsignedInteger('qty')->default(1)->after('price_snapshot');
            }
        });

        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('order_items', function (Blueprint $table) {
                $table->foreign('book_id')->references('id')->on('books')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['title_snapshot', 'price_snapshot', 'qty']);
        });
    }
};
