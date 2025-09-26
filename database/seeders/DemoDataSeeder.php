<?php

namespace Database\Seeders;

use App\Models\Attendee;
use App\Models\Event;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Seat;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // // 1. Create Users (Admin & Customers)
            // $adminUser = User::factory()->create([
            //     'name' => 'Admin User',
            //     'email' => 'admin@example.com',
            //     'password' => Hash::make('password'),
            // ]);
            // Assign 'admin' role if your RolesAndPermissionsSeeder creates it
            // $adminUser->assignRole('admin');

            $customers = User::factory(20)->create();

            // 2. Create Events
            $concert = Event::factory()->create([
                'user_id' => 1,
                'title' => 'Konser Musik Spektakuler',
                'description' => 'Sebuah malam penuh bintang dengan musisi ternama.',
                'location' => 'Stadium Megah',
                'is_online' => false,
                'has_numbered_seats' => true,
                'start_date' => now()->addMonths(2),
                'end_date' => now()->addMonths(2)->addHours(4),
            ]);

            $seminar = Event::factory()->create([
                'user_id' => 1,
                'title' => 'Seminar Digital Marketing 2025',
                'description' => 'Pelajari strategi terbaru dari para ahli.',
                'location' => 'Ballroom Hotel Bintang Lima',
                'is_online' => false,
                'has_numbered_seats' => false,
                'start_date' => now()->addMonths(1),
                'end_date' => now()->addMonths(1)->addHours(8),
            ]);

            // 3. Create Tickets for each event
            $concertVipTicket = Ticket::factory()->create([
                'event_id' => $concert->id,
                'name' => 'VIP',
                'price' => 1500000,
                'quantity' => 100,
            ]);

            $concertRegularTicket = Ticket::factory()->create([
                'event_id' => $concert->id,
                'name' => 'Regular',
                'price' => 750000,
                'quantity' => 400,
            ]);

            $seminarTicket = Ticket::factory()->create([
                'event_id' => $seminar->id,
                'name' => 'Peserta',
                'price' => 500000,
                'quantity' => 200,
            ]);

            // 4. Create Seats for the Concert
            $seats = [];
            for ($i = 1; $i <= 10; $i++) {
                for ($j = 1; $j <= 10; $j++) {
                    $seats[] = [
                        'event_id' => $concert->id,
                        'ticket_id' => $concertVipTicket->id,
                        'area' => 'VIP',
                        'row' => chr(64 + $i), // A, B, C, ...
                        'number' => $j,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
            Seat::insert($seats);

            // 5. Simulate Orders
            $customers->each(function (User $customer) use ($concert, $seminar, $concertVipTicket, $concertRegularTicket, $seminarTicket) {
                // Simulate buying 1 to 3 tickets for the concert
                $orderForConcert = Order::factory()->create([
                    'user_id' => $customer->id,
                    'status' => fake()->randomElement(['paid', 'pending', 'failed']),
                ]);

                $quantity = rand(1, 3);
                $chosenTicket = fake()->randomElement([$concertVipTicket, $concertRegularTicket]);

                $orderItem = OrderItem::factory()->create([
                    'order_id' => $orderForConcert->id,
                    'ticket_id' => $chosenTicket->id,
                    'ticket_name' => $chosenTicket->name,
                    'price' => $chosenTicket->price,
                    'quantity' => $quantity,
                    'subtotal' => $chosenTicket->price * $quantity,
                ]);

                // Find available seats and create attendees
                $availableSeats = Seat::where('event_id', $concert->id)
                    ->where('ticket_id', $chosenTicket->id)
                    ->where('is_available', true)
                    ->take($quantity)
                    ->get();

                if ($availableSeats->count() === $quantity) {
                    foreach ($availableSeats as $seat) {
                        Attendee::factory()->create([
                            'order_item_id' => $orderItem->id,
                            'seat_id' => $seat->id,
                            'name' => fake()->name(),
                            'email' => fake()->safeEmail(),
                        ]);
                        $seat->update(['is_available' => false, 'order_item_id' => $orderItem->id]);
                    }
                }

                $orderForConcert->update(['total_price' => $orderItem->subtotal]);
            });
        });
    }
}
