<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class PimpinanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Avoid duplicate username pimpinan
        if (! User::where('username', 'pimpinan')->exists()) {
            User::create([
                'name' => 'Bapak Pimpinan',
                'username' => 'pimpinan',
                'password' => 'password123',
                'phone' => '081300000000',
                'role' => 'pimpinan',
            ]);
        }
    }
}
