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
        LetterType::query()->delete();
        $types = [
            [
                'name' => 'SURAT KETERANGAN BERBADAN SEHAT / JASMANI',
                'slug' => 'skbj',
                'description' => 'Surat keterangan yang menyatakan kondisi kesehatan jasmani pasien dalam keadaan baik/sehat.',
                'is_active' => true,
            ],
            [
                'name' => 'SURAT KETERANGAN BEBAS NARKOBA',
                'slug' => 'skbn',
                'description' => 'Surat keterangan yang menyatakan bahwa pasien bebas dari penyalahgunaan narkoba berdasarkan hasil pemeriksaan urine.',
                'is_active' => true,
            ],
        ];

        foreach ($types as $type) {
            LetterType::updateOrCreate(
                ['name' => $type['name']],
                $type
            );
        }
    }
}
