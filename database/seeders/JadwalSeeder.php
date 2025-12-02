<?php

namespace Database\Seeders;

use App\Models\Jadwal;
use App\Models\PeriodeAkademik;
use App\Models\MataKuliah;
use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Ruang;
use Illuminate\Database\Seeder;

class JadwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $periodeAktif = PeriodeAkademik::where('status', 'aktif')->first();
        $dosen = Dosen::all();
        $mataKuliah = MataKuliah::all();
        $kelas = Kelas::all();
        $ruang = Ruang::all();

        $jadwal = [
            [
                'periode_akademik_id' => $periodeAktif->id,
                'mata_kuliah_id' => $mataKuliah[0]->id,
                'dosen_id' => $dosen[0]->id,
                'kelas_id' => $kelas[0]->id,
                'ruang_id' => $ruang[0]->id,
                'hari' => 'Senin',
                'jam_mulai' => '08:00',
                'jam_selesai' => '10:00',
                'kode_jadwal' => 'KOM101-TI22A-Senin',
            ],
            [
                'periode_akademik_id' => $periodeAktif->id,
                'mata_kuliah_id' => $mataKuliah[1]->id,
                'dosen_id' => $dosen[1]->id,
                'kelas_id' => $kelas[0]->id,
                'ruang_id' => $ruang[1]->id,
                'hari' => 'Selasa',
                'jam_mulai' => '10:00',
                'jam_selesai' => '12:00',
                'kode_jadwal' => 'KOM102-TI22A-Selasa',
            ],
            [
                'periode_akademik_id' => $periodeAktif->id,
                'mata_kuliah_id' => $mataKuliah[2]->id,
                'dosen_id' => $dosen[0]->id,
                'kelas_id' => $kelas[1]->id,
                'ruang_id' => $ruang[2]->id,
                'hari' => 'Rabu',
                'jam_mulai' => '13:00',
                'jam_selesai' => '15:00',
                'kode_jadwal' => 'KOM103-TI22B-Rabu',
            ],
            [
                'periode_akademik_id' => $periodeAktif->id,
                'mata_kuliah_id' => $mataKuliah[3]->id,
                'dosen_id' => $dosen[1]->id,
                'kelas_id' => $kelas[1]->id,
                'ruang_id' => $ruang[3]->id,
                'hari' => 'Kamis',
                'jam_mulai' => '09:00',
                'jam_selesai' => '11:00',
                'kode_jadwal' => 'KOM104-TI22B-Kamis',
            ],
            [
                'periode_akademik_id' => $periodeAktif->id,
                'mata_kuliah_id' => $mataKuliah[4]->id,
                'dosen_id' => $dosen[0]->id,
                'kelas_id' => $kelas[0]->id,
                'ruang_id' => $ruang[4]->id,
                'hari' => 'Jumat',
                'jam_mulai' => '14:00',
                'jam_selesai' => '16:00',
                'kode_jadwal' => 'KOM105-TI22A-Jumat',
            ],
        ];

        foreach ($jadwal as $j) {
            Jadwal::create($j);
        }
    }
}