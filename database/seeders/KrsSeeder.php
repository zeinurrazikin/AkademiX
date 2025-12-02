<?php

namespace Database\Seeders;

use App\Models\Krs;
use App\Models\KrsDetail;
use App\Models\Mahasiswa;
use App\Models\PeriodeAkademik;
use App\Models\Jadwal;
use Illuminate\Database\Seeder;

class KrsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $periodeAktif = PeriodeAkademik::where('status', 'aktif')->first();
        $mahasiswa = Mahasiswa::all();
        $jadwal = Jadwal::all();

        foreach ($mahasiswa as $index => $mhs) {
            // Create KRS
            $krs = Krs::create([
                'mahasiswa_id' => $mhs->id,
                'periode_akademik_id' => $periodeAktif->id,
                'nomor_krs' => 'KRS-' . date('Y') . '-' . str_pad($mhs->id, 6, '0', STR_PAD_LEFT),
                'status' => 'disetujui',
                'total_sks' => 12,
            ]);

            // Create KRS details (pick 3-4 jadwal per mahasiswa)
            $jadwalIds = $jadwal->pluck('id')->toArray();
            $selectedJadwal = array_slice($jadwalIds, $index * 3, 4);
            
            foreach ($selectedJadwal as $jadwalId) {
                KrsDetail::create([
                    'krs_id' => $krs->id,
                    'jadwal_id' => $jadwalId,
                ]);
            }
        }
    }
}