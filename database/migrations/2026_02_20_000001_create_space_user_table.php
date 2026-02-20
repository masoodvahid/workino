<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('space_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('space_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['active', 'deactive', 'pending', 'ban'])->default('pending');
            $table->timestamps();

            $table->unique(['user_id', 'space_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('space_user');
    }
};
