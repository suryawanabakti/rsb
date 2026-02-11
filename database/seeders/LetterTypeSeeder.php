<?php

namespace Database\Seeders;

use App\Models\LetterType;
use Illuminate\Database\Seeder;

class LetterTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'Surat Keterangan Sakit',
                'description' => 'Surat keterangan istirahat karena kondisi kesehatan yang tidak memungkinkan untuk bekerja atau sekolah.',
                'is_active' => true,
            ],
            [
                'name' => 'Surat Keterangan Sehat',
                'description' => 'Surat keterangan yang menyatakan kondisi kesehatan pasien dalam keadaan baik/sehat.',
                'is_active' => true,
            ],
            [
                'name' => 'Surat Rujukan',
                'description' => 'Surat pengantar untuk pemeriksaan lebih lanjut ke fasilitas kesehatan tingkat lanjut.',
                'is_active' => true,
            ],
        ];

        foreach ($types as $type) {
            LetterType::create($type);
        }
    }
}
