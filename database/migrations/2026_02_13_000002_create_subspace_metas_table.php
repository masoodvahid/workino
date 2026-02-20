<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subspace_meta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subspace_id')->constrained('subspaces')->cascadeOnDelete();
            $table->string('key');
            $table->json('value')->nullable();
            $table->string('group', 128)->nullable();
            $table->unsignedSmallInteger('order')->nullable();
            $table->enum('status', ['active', 'deactive', 'pending', 'ban'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subspace_meta');
    }
};
