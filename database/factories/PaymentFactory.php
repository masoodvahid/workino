<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        $booking = Booking::query()
            ->with(['subSpace.space'])
            ->inRandomOrder()
            ->first() ?? Booking::factory()->create();

        return [
            'acc_id' => fake()->optional()->numberBetween(1000, 999999),
            'acc_details' => fake()->boolean(60) ? [
                'ref_id' => fake()->numerify('##########'),
                'card_mask' => fake()->numerify('6037**** **** ####'),
            ] : null,
            'user_id' => $booking->user_id,
            'space_id' => $booking->subSpace?->space_id,
            'subspace_id' => $booking->subspace_id,
            'booking_id' => $booking->id,
            'gateway' => fake()->randomElement(['zarinpal', 'idpay', 'manual']),
            'note' => fake('fa_IR')->optional()->sentence(),
            'status' => fake()->randomElement(['pending', 'success', 'failed', 'canceled']),
            'api_cal_counter' => fake()->numberBetween(0, 5),
        ];
    }
}
