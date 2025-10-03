<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Seat>
 */
class SeatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'event_id' => Event::factory(),
            'ticket_id' => Ticket::factory(),
            'area' => $this->faker->randomElement(['VIP', 'Regular', 'Premium', 'Economy']),
            'row' => $this->faker->randomElement(['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H']),
            'number' => $this->faker->numberBetween(1, 50),
            'is_available' => true,
            'order_item_id' => null,
            'reserved_until' => null,
        ];
    }

    /**
     * Indicate that the seat is reserved.
     */
    public function reserved()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_available' => false,
                'reserved_until' => now()->addMinutes(15),
            ];
        });
    }

    /**
     * Indicate that the seat is sold.
     */
    public function sold()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_available' => false,
                'order_item_id' => \App\Models\OrderItem::factory(),
                'reserved_until' => null,
            ];
        });
    }
}