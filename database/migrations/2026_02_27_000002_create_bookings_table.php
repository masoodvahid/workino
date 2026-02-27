<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('subspace_id')->constrained('subspaces')->cascadeOnDelete();
            $table->foreignId('price_id')->constrained('prices')->cascadeOnDelete();
            $table->integer('quantity')->default(1);
            $table->integer('unit_price');
            $table->integer('total_price');
            $table->date('start');
            $table->date('end');
            $table->enum('status', ['pending', 'approve', 'reject'])->default('pending');
            $table->text('note')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['subspace_id', 'status']);
            $table->index(['price_id', 'status']);
            $table->index(['start', 'end']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
