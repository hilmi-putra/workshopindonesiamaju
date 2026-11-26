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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('voucher_type')->default('product_discount'); // product_discount, shipping_discount, cashback
            $table->string('discount_type')->default('fixed'); // fixed, percentage
            $table->decimal('value', 12, 2);
            $table->decimal('min_purchase', 12, 2)->default(0);
            $table->decimal('max_discount', 12, 2)->nullable();
            $table->integer('quota')->nullable();
            $table->integer('used_count')->default(0);
            $table->integer('max_uses_per_user')->default(1);
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->string('status')->default('active'); // draft, active, paused, expired, archived
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
