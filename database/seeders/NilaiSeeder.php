<?php

namespace Database\Seeders;

use App\Models\Nilai;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Dosen;
use App\Models\PeriodeAkademik;
use App\Models\Jadwal;
use Illuminate\Database\Seeder;

class NilaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $periodeAktif = PeriodeAkademik::where('status', 'aktif')->first();
        $mahasiswa = Mahasiswa::all();
        $mataKuliah = MataKuliah::all();
        $dosen = Dosen::all();
        $jadwal = Jadwal::all();

        foreach ($mahasiswa as $mhs) {
            foreach ($mataKuliah as $mk) {
                // Get jadwal for this mata kuliah in current semester
                $jadwalMk = $jadwal->firstWhere('mata_kuliah_id', $mk->id);
                
                if ($jadwalMk) {
                    $nilaiAngka = fake()->numberBetween(60, 100);
                    
                    Nilai::create([
                        'mahasiswa_id' => $mhs->id,
                        'mata_kuliah_id' => $mk->id,
                        'dosen_id' => $jadwalMk->dosen_id,
                        'periode_akademik_id' => $periodeAktif->id,
                        'jadwal_id' => $jadwalMk->id,
                        'nilai_angka' => $nilaiAngka,
                        'nilai_huruf' => $this->getNilaiHuruf($nilaiAngka),
                        'nilai_mutu' => $this->getNilaiMutu($nilaiAngka),
                    ]);
                }
            }
        }
    }

    private function getNilaiHuruf($nilaiAngka)
    {
        if ($nilaiAngka >= 85) return 'A';
        if ($nilaiAngka >= 80) return 'A-';
        if ($nilaiAngka >= 75) return 'B+';
        if ($nilaiAngka >= 70) return 'B';
        if ($nilaiAngka >= 65) return 'B-';
        if ($nilaiAngka >= 60) return 'C+';
        if ($nilaiAngka >= 55) return 'C';
        if ($nilaiAngka >= 40) return 'D';
        return 'E';
    }

    private function getNilaiMutu($nilaiAngka)
    {
        if ($nilaiAngka >= 85) return 4.00;
        if ($nilaiAngka >= 80) return 3.70;
        if ($nilaiAngka >= 75) return 3.30;
        if ($nilaiAngka >= 70) return 3.00;
        if ($nilaiAngka >= 65) return 2.70;
        if ($nilaiAngka >= 60) return 2.30;
        if ($nilaiAngka >= 55) return 2.00;
        if ($nilaiAngka >= 40) return 1.00;
        return 0.00;
    }
}