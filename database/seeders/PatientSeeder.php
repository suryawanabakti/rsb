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
            [
                'user' => [
                    'name' => 'PETRA CK TUMENGKOL,S.I.K.,M.Si',
                    'username' => '86091922',
                    'password' => 'password123',
                    'phone' => '08123456789',
                    'role' => 'pasien',
                    'nrp' => '86091922',
                ],
                'patient' => [
                    'nik' => '8609192200000001',
                    'address' => 'ROYAL SPRING CLUSTER GOLDEN SPRING B3 NO.03',
                    'birth_date' => '1986-09-19',
                    'gender' => 'P',
                    'pangkat' => 'AKBP',
                    'nrp_nip' => '86091922',
                    'pendidikan_terakhir' => 'S2',
                    'jabatan_kesatuan' => 'GADIK KEPOLISIAN MUDA TK.I SPN POLDA SULSEL',
                ],
            ],
        ];

        foreach ($patients as $data) {
            $user = User::updateOrCreate(
                ['username' => $data['user']['username']],
                $data['user']
            );

            $patientData = $data['patient'];
            $patientData['user_id'] = $user->id;

            Patient::updateOrCreate(
                ['user_id' => $user->id],
                $patientData
            );
        }
    }
}
