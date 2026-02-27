<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('space_id')->constrained('spaces')->cascadeOnDelete();
            $table->string('code', 256);
            $table->string('title', 512);
            $table->text('description')->nullable();
            $table->enum('type', ['percent', 'static']);
            $table->date('start')->nullable();
            $table->date('end')->nullable();
            $table->integer('limits')->nullable();
            $table->json('applied_to')->nullable();
            $table->integer('priority')->nullable();
            $table->enum('status', ['active', 'deactive', 'pending']);
            $table->softDeletes();
            $table->timestamps();

            $table->index(['space_id', 'status']);
            $table->index(['start', 'end']);
            $table->index('code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
