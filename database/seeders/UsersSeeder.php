<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create new user
        \App\Models\User::factory()->count(100)->create();

        \App\Models\User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@karrycar.com',
            'password' => bcrypt('password'),
        ]);
    }
}
