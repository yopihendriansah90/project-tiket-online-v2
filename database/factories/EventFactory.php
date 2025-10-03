<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph,
            'location' => $this->faker->city . ', ' . $this->faker->country,
            'is_online' => false,
            'has_numbered_seats' => false,
            'start_date' => $this->faker->dateTimeBetween('+1 month', '+3 months'),
            'end_date' => $this->faker->dateTimeBetween('+3 months', '+6 months'),
            'status' => 'published',
        ];
    }
}
