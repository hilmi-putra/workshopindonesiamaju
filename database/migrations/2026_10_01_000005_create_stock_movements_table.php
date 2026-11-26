<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained('books')->cascadeOnDelete();
            $table->integer('change');
            $table->string('reason', 100);
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->integer('stock_after');
            $table->timestamps();

            $table->index(['book_id', 'created_at']);
            $table->index(['order_id', 'reason']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
