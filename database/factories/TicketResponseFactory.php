<?php

namespace Database\Factories;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketResponseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $ticket = Ticket::all()->random();

        return [
            'response' => $this->faker->paragraph(),
            'ticket_id' => $ticket->id,
            'user_id' => $this->faker->randomElement([$ticket->arrendador->id, $ticket->abogado->id]),
        ];
    }
}
