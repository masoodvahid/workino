<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('type', ['space', 'subspace', 'content', 'comment']);
            $table->unsignedBigInteger('parent_id');
            $table->text('content')->nullable();
            $table->unsignedTinyInteger('rating')->default(5);
            $table->foreignId('reply_to')->nullable()->constrained('comments')->nullOnDelete();
            $table->enum('status', ['pending', 'approve', 'reject', 'spam'])->default('pending');
            $table->timestamps();

            $table->index(['type', 'parent_id']);
            $table->index(['user_id', 'status']);
            $table->index('reply_to');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
