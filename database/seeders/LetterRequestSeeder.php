<?php

namespace Database\Seeders;

use App\Models\LetterRequest;
use App\Models\LetterType;
use App\Models\Patient;
use App\Models\RequestFile;
use Illuminate\Database\Seeder;

class LetterRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $budi = Patient::whereHas('user', fn($q) => $q->where('username', 'budi80'))->first();
        $siti = Patient::whereHas('user', fn($q) => $q->where('username', 'siti95'))->first();

        $skbj = LetterType::where('slug', 'skbj')->first();
        $skbn = LetterType::where('slug', 'skbn')->first();

        if ($budi && $skbj) {
            $request1 = LetterRequest::create([
                'patient_id' => $budi->id,
                'letter_type_id' => $skbj->id,
                'submission_date' => now()->subDays(2),
                'status' => 'completed',
                'admin_notes' => 'Sudah diproses, silakan ambil di loket.',
                'processed_at' => now()->subDay(),
            ]);

            RequestFile::create([
                'letter_request_id' => $request1->id,
                'file_name' => 'KTP_Budi.pdf',
                'file_path' => 'letter_requests/' . $request1->id . '/ktp.pdf',
                'mime_type' => 'application/pdf',
                'file_size' => 102400,
            ]);
        }

        if ($siti && $skbn) {
            $request2 = LetterRequest::create([
                'patient_id' => $siti->id,
                'letter_type_id' => $skbn->id,
                'submission_date' => now(),
                'status' => 'submitted',
            ]);

            RequestFile::create([
                'letter_request_id' => $request2->id,
                'file_name' => 'Foto_Siti.jpg',
                'file_path' => 'letter_requests/' . $request2->id . '/foto.jpg',
                'mime_type' => 'image/jpeg',
                'file_size' => 204800,
            ]);
        }
    }
}
