<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->index('title');
        });

        Schema::table('blog_posts', function (Blueprint $table) {
            $table->index(['status', 'publish_date']);
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropIndex(['title']);
        });

        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropIndex(['status', 'publish_date']);
        });
    }
};
