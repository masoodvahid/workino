<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subspaces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('space_id')->constrained('spaces')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug');
            $table->enum('type', ['seat', 'room', 'meeting_room', 'conference_room', 'coffeeshop'])->default('seat');
            $table->unsignedInteger('capacity')->nullable();
            $table->enum('status', ['active', 'deactive', 'pending', 'ban'])->default('pending');
            $table->softDeletes();
            $table->timestamps();
            $table->unique(['space_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subspaces');
    }
};
