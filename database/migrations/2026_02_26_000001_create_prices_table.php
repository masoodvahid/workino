<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subspace_id')->constrained('subspaces')->cascadeOnDelete();
            $table->string('title', 512);
            $table->text('description')->nullable();
            $table->enum('unit', ['hour', 'day', 'week', 'month', 'year']);
            $table->json('unit_rules')->nullable();
            $table->integer('base_price');
            $table->integer('special_price')->nullable();
            $table->date('start')->nullable();
            $table->date('end')->nullable();
            $table->integer('priority')->nullable();
            $table->enum('status', ['active', 'deactive', 'pending']);
            $table->softDeletes();
            $table->timestamps();

            $table->index(['subspace_id', 'status']);
            $table->index(['start', 'end']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prices');
    }
};
