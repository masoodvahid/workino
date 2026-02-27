<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Price;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        $price = Price::query()->inRandomOrder()->first() ?? Price::factory()->create();
        $unitPrice = $price->special_price ?: $price->base_price;
        $quantity = fake()->numberBetween(1, 3);
        $start = fake()->dateTimeBetween('-14 days', '+14 days');
        $end = (clone $start)->modify('+' . fake()->numberBetween(1, 3) . ' days');

        return [
            'user_id' => User::query()->inRandomOrder()->value('id') ?? User::factory()->create()->id,
            'subspace_id' => $price->subspace_id,
            'price_id' => $price->id,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'total_price' => $quantity * $unitPrice,
            'start' => $start->format('Y-m-d'),
            'end' => $end->format('Y-m-d'),
            'status' => fake()->randomElement(['pending', 'approve', 'reject']),
            'note' => fake('fa_IR')->optional()->sentence(),
        ];
    }
}
