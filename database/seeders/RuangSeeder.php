<?php

namespace Database\Seeders;

use App\Models\Ruang;
use Illuminate\Database\Seeder;

class RuangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ruang = [
            ['kode_ruang' => 'R101', 'nama_ruang' => 'Ruang 101', 'lokasi' => 'Gedung A', 'kapasitas' => 40],
            ['kode_ruang' => 'R102', 'nama_ruang' => 'Ruang 102', 'lokasi' => 'Gedung A', 'kapasitas' => 40],
            ['kode_ruang' => 'R201', 'nama_ruang' => 'Ruang 201', 'lokasi' => 'Gedung B', 'kapasitas' => 60],
            ['kode_ruang' => 'R202', 'nama_ruang' => 'Ruang 202', 'lokasi' => 'Gedung B', 'kapasitas' => 60],
            ['kode_ruang' => 'L101', 'nama_ruang' => 'Lab Komputer 1', 'lokasi' => 'Gedung C', 'kapasitas' => 30],
            ['kode_ruang' => 'L102', 'nama_ruang' => 'Lab Komputer 2', 'lokasi' => 'Gedung C', 'kapasitas' => 30],
        ];

        foreach ($ruang as $r) {
            Ruang::create($r);
        }
    }
}