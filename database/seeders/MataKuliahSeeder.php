<?php

namespace Database\Seeders;

use App\Models\MataKuliah;
use Illuminate\Database\Seeder;

class MataKuliahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mataKuliah = [
            [
                'kode_mk' => 'KOM101',
                'nama_mk' => 'Pemrograman Web',
                'sks_teori' => 2,
                'sks_praktikum' => 1,
                'sks_praktek_lapangan' => 0,
                'deskripsi' => 'Mata kuliah tentang dasar-dasar pemrograman web menggunakan HTML, CSS, dan JavaScript.',
                'prasyarat' => '',
            ],
            [
                'kode_mk' => 'KOM102',
                'nama_mk' => 'Basis Data',
                'sks_teori' => 2,
                'sks_praktikum' => 1,
                'sks_praktek_lapangan' => 0,
                'deskripsi' => 'Mata kuliah tentang konsep dan implementasi basis data menggunakan SQL.',
                'prasyarat' => 'KOM101',
            ],
            [
                'kode_mk' => 'KOM103',
                'nama_mk' => 'Algoritma dan Struktur Data',
                'sks_teori' => 3,
                'sks_praktikum' => 0,
                'sks_praktek_lapangan' => 0,
                'deskripsi' => 'Mata kuliah tentang konsep algoritma dan struktur data.',
                'prasyarat' => '',
            ],
            [
                'kode_mk' => 'KOM104',
                'nama_mk' => 'Jaringan Komputer',
                'sks_teori' => 2,
                'sks_praktikum' => 1,
                'sks_praktek_lapangan' => 0,
                'deskripsi' => 'Mata kuliah tentang konsep dan implementasi jaringan komputer.',
                'prasyarat' => 'KOM101',
            ],
            [
                'kode_mk' => 'KOM105',
                'nama_mk' => 'Sistem Operasi',
                'sks_teori' => 3,
                'sks_praktikum' => 0,
                'sks_praktek_lapangan' => 0,
                'deskripsi' => 'Mata kuliah tentang konsep dan implementasi sistem operasi.',
                'prasyarat' => '',
            ],
        ];

        foreach ($mataKuliah as $mk) {
            $mk['total_sks'] = $mk['sks_teori'] + $mk['sks_praktikum'] + $mk['sks_praktek_lapangan'];
            MataKuliah::create($mk);
        }
    }
}