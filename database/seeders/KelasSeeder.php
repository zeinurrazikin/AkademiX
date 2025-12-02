<?php

namespace Database\Seeders;

use App\Models\Kelas;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kelas = [
            ['kode_kelas' => 'TI22A', 'nama_kelas' => 'Teknik Informatika 2022 A', 'kuota' => 30],
            ['kode_kelas' => 'TI22B', 'nama_kelas' => 'Teknik Informatika 2022 B', 'kuota' => 30],
            ['kode_kelas' => 'TI23A', 'nama_kelas' => 'Teknik Informatika 2023 A', 'kuota' => 30],
            ['kode_kelas' => 'TI23B', 'nama_kelas' => 'Teknik Informatika 2023 B', 'kuota' => 30],
        ];

        foreach ($kelas as $k) {
            Kelas::create($k);
        }
    }
}