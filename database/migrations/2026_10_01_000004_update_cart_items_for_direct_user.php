<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->unsignedBigInteger('cart_id')->nullable()->change();
            $table->integer('quantity')->nullable()->change();

            if (!Schema::hasColumn('cart_items', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->cascadeOnDelete();
            }
            if (!Schema::hasColumn('cart_items', 'qty')) {
                $table->unsignedInteger('qty')->default(1)->after('book_id');
            }

            $table->unique(['user_id', 'book_id']);
        });
    }

    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'book_id']);
            if (Schema::hasColumn('cart_items', 'user_id')) {
                $table->dropConstrainedForeignId('user_id');
            }
            if (Schema::hasColumn('cart_items', 'qty')) {
                $table->dropColumn('qty');
            }
        });
    }
};
