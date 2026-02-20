<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->enum('key', ['admin', 'space_user', 'user'])->unique();
            $table->string('title');
            $table->json('permissions')->nullable();
            $table->timestamps();
        });

        $now = now();

        DB::table('roles')->insert([
            [
                'key' => 'admin',
                'title' => 'مدیر کل',
                'permissions' => json_encode([], JSON_THROW_ON_ERROR),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'space_user',
                'title' => 'کاربر فضای کاری',
                'permissions' => json_encode([
                    'dashboard.view',
                    'spaces.view_any',
                    'spaces.view',
                    'spaces.update',
                ], JSON_THROW_ON_ERROR),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'user',
                'title' => 'کاربر مشتری',
                'permissions' => json_encode([], JSON_THROW_ON_ERROR),
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
