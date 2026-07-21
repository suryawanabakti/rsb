<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'username' => 'di ',
            'password' => 'password', // Hash is handled by model attribute
            'phone' => '000000000000',
            'role' => 'admin',
        ]);
    }
}
