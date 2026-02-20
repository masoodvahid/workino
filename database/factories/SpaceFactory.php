<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Space>
 */
class SpaceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake('fa_IR')->unique()->company();

        return [
            'title' => $title,
            'slug' => str()->slug($title),
            'order' => fake()->numberBetween(1, 99),
            'note' => fake('fa_IR')->paragraph(),
            'status' => fake()->randomElement(['active', 'pending', 'deactive', 'ban']),
        ];
    }
}
