<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Seed 2 default users (admin and customer).
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@babapos.test'],
            [
                'name' => 'Admin BabaPOS',
                'username' => 'admin',
                'phone' => '081111111111',
                'role' => 'admin',
                'email_verified_at' => now(),
                'password' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'customer@babapos.test'],
            [
                'name' => 'Customer BabaPOS',
                'username' => 'customer',
                'phone' => '082222222222',
                'role' => 'customer',
                'email_verified_at' => now(),
                'password' => 'customer',
            ]
        );
    }
}
