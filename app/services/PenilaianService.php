<?php

namespace App\Services;

use App\Models\Nilai;
use App\Models\Jadwal;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use Illuminate\Support\Facades\DB;

class PenilaianService
{
    /**
     * Konversi nilai angka ke huruf
     */
    public function konversiNilaiAngkaKeHuruf($nilaiAngka)
    {
        if ($nilaiAngka === null) {
            return '-';
        }
        
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

    /**
     * Konversi nilai angka ke mutu
     */
    public function konversiNilaiAngkaKeMutu($nilaiAngka)
    {
        if ($nilaiAngka === null) {
            return 0.00;
        }
        
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

    /**
     * Simpan nilai mahasiswa
     */
    public function simpanNilai($jadwalId, $nilaiData)
    {
        DB::beginTransaction();
        
        try {
            $jadwal = Jadwal::findOrFail($jadwalId);
            
            foreach ($nilaiData as $mahasiswaId => $nilaiAngka) {
                $nilaiHuruf = $this->konversiNilaiAngkaKeHuruf($nilaiAngka);
                $nilaiMutu = $this->konversiNilaiAngkaKeMutu($nilaiAngka);

                Nilai::updateOrCreate(
                    [
                        'mahasiswa_id' => $mahasiswaId,
                        'jadwal_id' => $jadwalId,
                        'periode_akademik_id' => $jadwal->periode_akademik_id,
                        'mata_kuliah_id' => $jadwal->mata_kuliah_id,
                        'dosen_id' => $jadwal->dosen_id,
                    ],
                    [
                        'nilai_angka' => $nilaiAngka,
                        'nilai_huruf' => $nilaiHuruf,
                        'nilai_mutu' => $nilaiMutu,
                    ]
                );
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Ambil nilai mahasiswa untuk jadwal tertentu
     */
    public function getNilaiByJadwal($jadwalId)
    {
        return Nilai::where('jadwal_id', $jadwalId)
            ->with(['mahasiswa', 'dosen', 'mataKuliah'])
            ->get();
    }

    /**
     * Ambil nilai mahasiswa untuk semester tertentu
     */
    public function getNilaiByMahasiswaAndPeriode($mahasiswaId, $periodeId)
    {
        return Nilai::where('mahasiswa_id', $mahasiswaId)
            ->where('periode_akademik_id', $periodeId)
            ->with(['mataKuliah', 'dosen', 'periodeAkademik'])
            ->get();
    }

    /**
     * Cek apakah nilai sudah diinput
     */
    public function isNilaiInput($jadwalId)
    {
        $jumlahMahasiswa = \App\Models\KrsDetail::where('jadwal_id', $jadwalId)
            ->with(['krs.mahasiswa'])
            ->get()
            ->pluck('krs.mahasiswa')
            ->unique()
            ->count();

        $jumlahNilai = Nilai::where('jadwal_id', $jadwalId)->count();

        return $jumlahNilai >= $jumlahMahasiswa && $jumlahMahasiswa > 0;
    }

    /**
     * Generate rekap nilai
     */
    public function generateRekapNilai($jadwalId)
    {
        $jadwal = Jadwal::with(['mataKuliah', 'dosen', 'periodeAkademik'])->findOrFail($jadwalId);
        
        $nilai = $this->getNilaiByJadwal($jadwalId);
        
        $rekap = [
            'jadwal' => $jadwal,
            'nilai' => $nilai,
            'jumlah_mahasiswa' => $nilai->count(),
            'jumlah_lulus' => $nilai->filter(function($item) {
                return $item->nilai_mutu > 0;
            })->count(),
            'jumlah_tidak_lulus' => $nilai->filter(function($item) {
                return $item->nilai_mutu == 0;
            })->count(),
        ];

        return $rekap;
    }
}