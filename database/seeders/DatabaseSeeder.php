<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'مسعود وحید',
            'type' => 'man',
            'email' => 'masood.vahid@gmail.com',
            'mobile' => '09138752587',
            'status' => 'active',
            'password' => Hash::make('12345')
        ]);

        User::factory()->create([
            'name' => 'راهکار دیجیتال شریف',
            'type' => 'company',
            'email'=> 'zanisdigital@gmail.com',
            'mobile' => '09900800773',
            'status' => 'active',
            'password'=> Hash::make('1020304050')
        ]);
    }
}
