<?php

namespace Database\Seeders;

use App\Models\PeriodeAkademik;
use Illuminate\Database\Seeder;

class PeriodeAkademikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create active semester
        PeriodeAkademik::create([
            'kode_periode' => '2025-Ganjil',
            'tahun_akademik' => '2025/2026',
            'semester' => 'Ganjil',
            'tanggal_mulai' => '2025-08-01',
            'tanggal_selesai' => '2025-12-31',
            'status' => 'aktif',
        ]);

        // Create previous semesters
        PeriodeAkademik::create([
            'kode_periode' => '2024-Genap',
            'tahun_akademik' => '2024/2025',
            'semester' => 'Genap',
            'tanggal_mulai' => '2025-01-01',
            'tanggal_selesai' => '2025-06-30',
            'status' => 'tutup',
        ]);

        PeriodeAkademik::create([
            'kode_periode' => '2024-Ganjil',
            'tahun_akademik' => '2024/2025',
            'semester' => 'Ganjil',
            'tanggal_mulai' => '2024-08-01',
            'tanggal_selesai' => '2024-12-31',
            'status' => 'tutup',
        ]);
    }
}