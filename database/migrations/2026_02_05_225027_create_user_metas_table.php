<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_meta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('uid')->constrained('users')->cascadeOnDelete();
            $table->string('key');
            $table->text('value')->nullable();           
            $table->boolean('is_encrypted')->default(false);
            $table->boolean('status')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['uid', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_meta');
    }
};
