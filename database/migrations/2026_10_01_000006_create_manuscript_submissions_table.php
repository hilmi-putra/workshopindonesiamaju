<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manuscript_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->string('author_name');
            $table->string('email');
            $table->string('phone', 30);
            $table->string('genre')->nullable();
            $table->text('synopsis');
            $table->string('file_path');
            $table->string('original_filename')->nullable();
            $table->string('status', 30)->default('pending')->index();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manuscript_submissions');
    }
};
