<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->integer('acc_id')->nullable();
            $table->json('acc_details')->nullable();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('space_id')->constrained('spaces')->cascadeOnDelete();
            $table->foreignId('subspace_id')->constrained('subspaces')->cascadeOnDelete();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->string('gateway', 256);
            $table->text('note')->nullable();
            $table->enum('status', ['pending', 'success', 'failed', 'canceled'])->default('pending');
            $table->integer('api_cal_counter')->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['space_id', 'status']);
            $table->index(['subspace_id', 'status']);
            $table->index(['booking_id', 'status']);
            $table->index('gateway');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
