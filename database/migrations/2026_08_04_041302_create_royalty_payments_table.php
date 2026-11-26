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
        Schema::create('royalty_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('publisher_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('amount', 12, 2);
            $table->string('reference_no')->nullable();
            $table->string('status')->default('pending'); // pending, paid
            $table->text('notes')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('royalty_payments');
    }
};
