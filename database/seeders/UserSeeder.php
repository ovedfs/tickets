<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Adela Torres',
            'email' => 'adela@gmail.com',
            'password' => Hash::make('password'),
        ])->assignRole('arrendador');

        User::create([
            'name' => 'Pedro Luna',
            'email' => 'pedro@gmail.com',
            'password' => Hash::make('password'),
        ])->assignRole('arrendador');

        User::factory()
            ->count(5)
            ->create()
            ->each(function($user, $index){
                $user->assignRole('abogado');
            });
    }
}
