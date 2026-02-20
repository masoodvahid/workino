<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->after('type')->constrained('roles')->nullOnDelete();
        });

        $userRoleId = DB::table('roles')->where('key', 'user')->value('id');
        $adminRoleId = DB::table('roles')->where('key', 'admin')->value('id');

        if ($userRoleId) {
            DB::table('users')->update(['role_id' => $userRoleId]);
        }

        $firstUserId = DB::table('users')->min('id');

        if ($firstUserId && $adminRoleId) {
            DB::table('users')
                ->where('id', $firstUserId)
                ->update(['role_id' => $adminRoleId]);
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });
    }
};
