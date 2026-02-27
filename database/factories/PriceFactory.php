<?php

namespace Database\Factories;

use App\Enums\BookingStatus;
use App\Enums\BookingUnit;
use App\Models\Price;
use App\Models\Space;
use App\Models\SubSpace;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Price>
 */
class PriceFactory extends Factory
{
    protected $model = Price::class;

    public function definition(): array
    {
        $subSpace = SubSpace::query()
            ->where('type', 'seat')
            ->inRandomOrder()
            ->first();

        if (! $subSpace) {
            $space = Space::factory()->create();
            $subSpace = SubSpace::query()->create([
                'space_id' => $space->id,
                'title' => 'Seat ' . fake('fa_IR')->words(2, true),
                'slug' => Str::slug(fake()->unique()->words(3, true)),
                'type' => 'seat',
                'capacity' => random_int(1, 4),
                'status' => 'active',
            ]);
        }

        $unit = fake()->randomElement([BookingUnit::Hour, BookingUnit::Day]);
        $basePrice = match ($unit) {
            BookingUnit::Hour => fake()->numberBetween(100_000, 180_000),
            BookingUnit::Day => fake()->numberBetween(220_000, 300_000),
            default => 100_000,
        };
        $specialPrice = fake()->boolean(35)
            ? fake()->numberBetween((int) floor($basePrice * 0.75), max($basePrice - 10_000, 10_000))
            : null;

        return [
            'subspace_id' => $subSpace->id,
            'title' => $unit === BookingUnit::Hour ? 'رزرو ساعتی صندلی' : 'رزرو روزانه صندلی',
            'description' => fake('fa_IR')->optional()->sentence(),
            'unit' => $unit,
            'unit_rules' => null,
            'base_price' => $basePrice,
            'special_price' => $specialPrice,
            'start' => fake()->boolean(40) ? fake()->dateTimeBetween('-10 days', '+10 days')->format('Y-m-d') : null,
            'end' => fake()->boolean(25) ? fake()->dateTimeBetween('+11 days', '+45 days')->format('Y-m-d') : null,
            'priority' => fake()->numberBetween(1, 10),
            'status' => fake()->randomElement([
                BookingStatus::Active,
                BookingStatus::Active,
                BookingStatus::Pending,
            ]),
        ];
    }
}
