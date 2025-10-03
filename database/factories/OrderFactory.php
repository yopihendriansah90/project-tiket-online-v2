<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
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
            'invoice_number' => 'INV-' . now()->timestamp . $this->faker->unique()->randomNumber(4),
            'total_price' => 0, // Will be updated after items are added
            'status' => 'pending',
            'payment_method' => 'credit_card',
            'paid_at' => null,
        ];
    }
}
