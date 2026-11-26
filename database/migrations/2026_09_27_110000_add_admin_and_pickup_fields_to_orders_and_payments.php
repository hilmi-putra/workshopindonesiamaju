<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'confirmed_by')) {
                $table->foreignId('confirmed_by')->nullable()->constrained('users')->nullOnDelete();
            }
        });

        Schema::table('order_timelines', function (Blueprint $table) {
            if (!Schema::hasColumn('order_timelines', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('order_timelines', 'actor_name')) {
                $table->string('actor_name')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('order_timelines', 'action')) {
                $table->string('action')->nullable()->after('actor_name');
            }
            if (!Schema::hasColumn('order_timelines', 'old_value')) {
                $table->string('old_value')->nullable()->after('action');
            }
            if (!Schema::hasColumn('order_timelines', 'new_value')) {
                $table->string('new_value')->nullable()->after('old_value');
            }
        });

        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'pickup_code')) {
                $table->string('pickup_code')->nullable()->after('notes');
            }
            if (!Schema::hasColumn('orders', 'picked_up_at')) {
                $table->timestamp('picked_up_at')->nullable()->after('pickup_code');
            }
            if (!Schema::hasColumn('orders', 'completed_at')) {
                $table->timestamp('completed_at')->nullable()->after('picked_up_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['confirmed_by']);
            $table->dropColumn('confirmed_by');
        });

        Schema::table('order_timelines', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'actor_name', 'action', 'old_value', 'new_value']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['pickup_code', 'picked_up_at', 'completed_at']);
        });
    }
};
