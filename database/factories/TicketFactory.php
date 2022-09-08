<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Contract;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $contract = Contract::all()->random();

        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'arrendador_id' => $contract->user->id,
            'abogado_id' => User::role('abogado')->get()->random()->id,
            'contract_id' => $contract->id,
        ];
    }
}
