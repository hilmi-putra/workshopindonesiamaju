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
            $table->string('awb_number')->nullable()->after('shipping_method');
            $table->text('notes')->nullable()->after('shipping_address_id');
            // the status column is already there, but we can't easily change default to 'pending_payment' if there's no doctrine/dbal installed. Let's just keep 'status' as is and insert 'pending_payment' from code.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['awb_number', 'notes']);
        });
    }
};
