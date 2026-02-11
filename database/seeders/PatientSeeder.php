<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Patient;
use Illuminate\Database\Seeder;

class   PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $patients = [
            [
                'user' => [
                    'name' => 'Budi Santosos',
                    'username' => 'budi80',
                    'password' => 'password123',
                    'phone' => '081234567890',
                    'role' => 'pasien',
                ],
                'patient' => [
                    'nik' => '3201234567890001',
                    'address' => 'Jl. Merdeka No. 10, Jakarta Pusat',
                    'birth_date' => '1980-01-01',
                    'gender' => 'L',
                ],
            ],
            [
                'user' => [
                    'name' => 'Siti Aminah',
                    'username' => 'siti95',
                    'password' => 'password123',
                    'phone' => '085712345678',
                    'role' => 'pasien',
                ],
                'patient' => [
                    'nik' => '3201234567890002',
                    'address' => 'Jl. Mawar No. 5, Sleman, Yogyakarta',
                    'birth_date' => '1995-05-15',
                    'gender' => 'P',
                ],
            ],
        ];

        foreach ($patients as $data) {
            $user = User::create($data['user']);

            $patientData = $data['patient'];
            $patientData['user_id'] = $user->id;

            Patient::create($patientData);
        }
    }
}
