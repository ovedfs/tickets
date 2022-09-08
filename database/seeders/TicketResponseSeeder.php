<?php

namespace Database\Seeders;

use App\Models\TicketResponse;
use Illuminate\Database\Seeder;

class TicketResponseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TicketResponse::factory()
            ->count(100)
            ->create();
    }
}
