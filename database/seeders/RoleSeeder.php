<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\DoctorSchedule;
use App\Models\LabResult;
use App\Models\Patient;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Seed sample users for petugas_lab and dokter roles,
     * plus sample doctor schedules and lab results.
     */
    public function run(): void
    {
        // Create Petugas Lab user
        $petugasLab = User::create([
            'name' => 'Andi Petugas Lab',
            'username' => 'petugaslab',
            'password' => 'password123',
            'phone' => '081300000001',
            'role' => 'petugas_lab',
        ]);

        // Create Dokter user
        $dokter = User::create([
            'name' => 'Dr. Siti Nurbaya',
            'username' => 'doktersiti',
            'password' => 'password123',
            'phone' => '081300000002',
            'role' => 'dokter',
        ]);

        // Create Doctor Schedules
        $schedules = [
            ['day_of_week' => 'senin',  'start_time' => '08:00', 'end_time' => '12:00', 'room' => 'Poli Umum 1'],
            ['day_of_week' => 'selasa', 'start_time' => '08:00', 'end_time' => '12:00', 'room' => 'Poli Umum 1'],
            ['day_of_week' => 'rabu',   'start_time' => '13:00', 'end_time' => '17:00', 'room' => 'Poli Umum 2'],
            ['day_of_week' => 'kamis',  'start_time' => '08:00', 'end_time' => '12:00', 'room' => 'Poli Umum 1'],
            ['day_of_week' => 'jumat',  'start_time' => '08:00', 'end_time' => '11:00', 'room' => 'Poli Umum 1'],
        ];

        foreach ($schedules as $schedule) {
            DoctorSchedule::create(array_merge($schedule, [
                'doctor_id' => $dokter->id,
                'is_active' => true,
            ]));
        }

        // Create sample lab results for existing patients
        $patients = Patient::take(2)->get();

        if ($patients->count() > 0) {
            // Darah Lengkap for first patient
            LabResult::create([
                'patient_id' => $patients[0]->id,
                'test_name' => 'Darah Lengkap (CBC)',
                'test_date' => now()->subDays(3)->toDateString(),
                'result_data' => [
                    ['name' => 'Hemoglobin', 'value' => '14.5', 'unit' => 'g/dL', 'normal_range' => '13.0-17.0'],
                    ['name' => 'Leukosit', 'value' => '7200', 'unit' => '/µL', 'normal_range' => '4000-10000'],
                    ['name' => 'Trombosit', 'value' => '250000', 'unit' => '/µL', 'normal_range' => '150000-400000'],
                    ['name' => 'Hematokrit', 'value' => '42', 'unit' => '%', 'normal_range' => '40-54'],
                    ['name' => 'Eritrosit', 'value' => '4.8', 'unit' => 'juta/µL', 'normal_range' => '4.5-5.5'],
                ],
                'notes' => 'Hasil dalam batas normal',
                'status' => 'validated',
                'inputted_by' => $petugasLab->id,
                'validated_by' => $dokter->id,
                'validated_at' => now()->subDays(2),
            ]);

            // Urine for first patient (pending)
            LabResult::create([
                'patient_id' => $patients[0]->id,
                'test_name' => 'Urinalisis',
                'test_date' => now()->subDay()->toDateString(),
                'result_data' => [
                    ['name' => 'Warna', 'value' => 'Kuning', 'unit' => '', 'normal_range' => 'Kuning'],
                    ['name' => 'pH', 'value' => '6.0', 'unit' => '', 'normal_range' => '4.5-8.0'],
                    ['name' => 'Protein', 'value' => 'Negatif', 'unit' => '', 'normal_range' => 'Negatif'],
                    ['name' => 'Glukosa', 'value' => 'Negatif', 'unit' => '', 'normal_range' => 'Negatif'],
                ],
                'notes' => null,
                'status' => 'pending',
                'inputted_by' => $petugasLab->id,
            ]);

            if ($patients->count() > 1) {
                // Gula Darah for second patient
                LabResult::create([
                    'patient_id' => $patients[1]->id,
                    'test_name' => 'Gula Darah Puasa',
                    'test_date' => now()->subDays(2)->toDateString(),
                    'result_data' => [
                        ['name' => 'Glukosa Puasa', 'value' => '95', 'unit' => 'mg/dL', 'normal_range' => '70-100'],
                    ],
                    'notes' => 'Dalam batas normal',
                    'status' => 'pending',
                    'inputted_by' => $petugasLab->id,
                ]);
            }
        }
    }
}
