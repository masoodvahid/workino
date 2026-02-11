<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user1 = User::factory()->create([
            'name' => 'مسعود وحید',
            'type' => 'man',
            'email' => 'masood.vahid@gmail.com',
            'mobile' => '09138752587',
            'status' => 'active',
            'password' => Hash::make('12345'),
        ]);

        $user1->setMetaValues([
            'city' => 'isfahan',
            'address' => 'اصفهان، خیابان آمادگاه',
            'postal_code' => '8146512345',
            'national_id' => '1270000000',
            'birth_day' => '1990-01-01',
            'education' => 'master',
            'major' => 'مهندسی نرم‌افزار',
            'university' => 'دانشگاه اصفهان',
        ]);

        $user2 = User::factory()->create([
            'name' => 'راهکار دیجیتال شریف',
            'type' => 'company',
            'email' => 'zanisdigital@gmail.com',
            'mobile' => '09900800773',
            'status' => 'active',
            'password' => Hash::make('1020304050'),
        ]);

        $user2->setMetaValues([
            'city' => 'tehran',
            'address' => 'تهران، خیابان آزادی، دانشگاه صنعتی شریف',
            'postal_code' => '1136511111',
            'national_id' => '14000000000',
            'reg_number' => '123456',
        ]);

        $educationOptions = ['diploma', 'associate', 'bachelor', 'master', 'phd', 'other'];
        $types = ['man', 'woman', 'company'];

        foreach (range(1, 10) as $index) {
            $type = fake()->randomElement($types);

            $user = User::factory()->create([
                'name' => $type === 'company' ? fake()->company() : fake()->name(),
                'type' => $type,
                'email' => fake()->unique()->safeEmail(),
                'status' => fake()->randomElement(['active', 'pending', 'deactive', 'ban']),
                'password' => Hash::make(Str::random(10)),
            ]);

            $meta = [
                'city' => fake()->randomElement(['isfahan', 'tehran']),
                'address' => fake()->address(),
                'postal_code' => fake()->numerify('##########'),
            ];

            if ($type === 'company') {
                $meta['national_id'] = fake()->numerify('##########');
                $meta['reg_number'] = fake()->numerify('########');
            }

            if (in_array($type, ['man', 'woman'], true)) {
                $meta['national_id'] = fake()->numerify('##########');
                $meta['birth_day'] = fake()->date('Y-m-d');
                $meta['education'] = fake()->randomElement($educationOptions);
                $meta['major'] = fake()->jobTitle();
                $meta['university'] = fake()->company();
            }

            $user->setMetaValues($meta);
        }
    }
}
