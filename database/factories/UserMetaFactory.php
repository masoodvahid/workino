<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserMeta>
 */
class UserMetaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uid' => User::factory(),
            'key' => fake()->word(),
            'value' => fake()->sentence(),
            'title' => fake()->optional()->words(2, true),
            'is_encrypted' => fake()->boolean(),
            'status' => fake()->optional()->boolean(),
        ];
    }
}
