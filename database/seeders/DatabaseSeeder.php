<?php

namespace Database\Seeders;

use App\Enums\UserRoleKey;
use App\Models\Role;
use App\Models\Space;
use App\Models\SubSpace;
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
        $this->seedRoles();
        $this->seedUsers();
        $this->seedSpaces();
    }

    private function seedRoles(): void
    {
        Role::query()->updateOrCreate(
            ['key' => UserRoleKey::Admin->value],
            [
                'title' => UserRoleKey::Admin->getLabel(),
                'permissions' => [],
            ],
        );

        Role::query()->updateOrCreate(
            ['key' => UserRoleKey::SpaceUser->value],
            [
                'title' => UserRoleKey::SpaceUser->getLabel(),
                'permissions' => [
                    'dashboard.view',
                    'spaces.view_any',
                    'spaces.view',
                    'spaces.update',
                ],
            ],
        );

        Role::query()->updateOrCreate(
            ['key' => UserRoleKey::User->value],
            [
                'title' => UserRoleKey::User->getLabel(),
                'permissions' => [],
            ],
        );
    }

    private function seedUsers(): void
    {
        $roles = Role::query()
            ->get()
            ->keyBy(fn (Role $role) => $role->key->value);

        $user1 = User::factory()->create([
            'name' => 'مسعود وحید',
            'type' => 'man',
            'role_id' => $roles[UserRoleKey::Admin->value]?->id,
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
            'role_id' => $roles[UserRoleKey::SpaceUser->value]?->id,
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
                'role_id' => $roles[UserRoleKey::User->value]?->id,
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

    private function seedSpaces(): void
    {
        Space::factory(10)->create()->each(function (Space $space) {
            $space->setMetaValues([
                'logo' => fake()->imageUrl(200, 200, 'business', true, 'Logo'),
                'featured_image' => fake()->imageUrl(800, 600, 'office', true, 'Featured'),
                'images' => [
                    fake()->imageUrl(800, 600, 'office', true, 'Image 1'),
                    fake()->imageUrl(800, 600, 'office', true, 'Image 2'),
                    fake()->imageUrl(800, 600, 'office', true, 'Image 3'),
                ],
                'abstract' => fake('fa_IR')->realText(200),
                'content' => collect(fake('fa_IR')->paragraphs(3))
                    ->map(fn ($p) => "<p>$p</p>")
                    ->implode(''),
            ]);

            $this->seedSubSpacesForSpace($space);
        });
    }

    private function seedSubSpacesForSpace(Space $space): void
    {
        $types = ['seat', 'room', 'meeting_room', 'conference_room', 'coffeeshop'];
        $count = random_int(2, 6);

        foreach (range(1, $count) as $index) {
            $type = fake()->randomElement($types);
            $title = fake('fa_IR')->words(2, true).' '.$index;

            $subSpace = SubSpace::query()->create([
                'space_id' => $space->id,
                'title' => $title,
                'slug' => Str::slug($title).'-'.$index,
                'type' => $type,
                'capacity' => $type === 'seat' ? random_int(1, 4) : random_int(4, 30),
                'status' => fake()->randomElement(['active', 'pending', 'deactive']),
            ]);

            $subSpace->setMetaValues([
                'feature_image' => fake()->imageUrl(900, 650, 'office', true, 'Subspace Feature'),
                'images' => [
                    fake()->imageUrl(900, 650, 'office', true, 'Subspace Image 1'),
                    fake()->imageUrl(900, 650, 'office', true, 'Subspace Image 2'),
                ],
                'working_time' => $this->fakeWorkingTime(),
                'abstract' => fake('fa_IR')->realText(180),
                'content' => collect(fake('fa_IR')->paragraphs(2))
                    ->map(fn ($paragraph) => "<p>{$paragraph}</p>")
                    ->implode(''),
            ]);
        }
    }

    private function fakeWorkingTime(): array
    {
        return [
            'saturday' => ['enabled' => true, 'start' => '08:00', 'end' => '18:00'],
            'sunday' => ['enabled' => true, 'start' => '08:00', 'end' => '18:00'],
            'monday' => ['enabled' => true, 'start' => '08:00', 'end' => '18:00'],
            'tuesday' => ['enabled' => true, 'start' => '08:00', 'end' => '18:00'],
            'wednesday' => ['enabled' => true, 'start' => '08:00', 'end' => '18:00'],
            'thursday' => ['enabled' => true, 'start' => '08:00', 'end' => '16:00'],
            'friday' => ['enabled' => fake()->boolean(35), 'start' => '09:00', 'end' => '13:00'],
        ];
    }
}
