<?php

namespace App\Services;

use App\Models\Nilai;
use App\Models\Transkrip;
use App\Models\Mahasiswa;
use App\Models\PeriodeAkademik;
use Illuminate\Support\Facades\DB;

class KhsService
{
    /**
     * Ambil data KHS berdasarkan mahasiswa dan periode
     */
    public function getKhsByMahasiswaAndPeriode($mahasiswaId, $periodeId)
    {
        return Nilai::where('mahasiswa_id', $mahasiswaId)
            ->where('periode_akademik_id', $periodeId)
            ->with(['mataKuliah', 'dosen', 'periodeAkademik'])
            ->get();
    }

    /**
     * Hitung Indeks Prestasi (IP) berdasarkan nilai-nilai
     */
    public function calculateIP($nilaiCollection)
    {
        if ($nilaiCollection->isEmpty()) {
            return 0;
        }

        $totalBobot = 0;
        $totalSks = 0;

        foreach ($nilaiCollection as $nilai) {
            $bobot = $nilai->nilai_mutu * $nilai->mataKuliah->total_sks;
            $totalBobot += $bobot;
            $totalSks += $nilai->mataKuliah->total_sks;
        }

        return $totalSks > 0 ? round($totalBobot / $totalSks, 2) : 0;
    }

    /**
     * Hitung Indeks Prestasi Kumulatif (IPK)
     */
    public function calculateIPK($mahasiswaId, $periodeId = null)
    {
        $query = Transkrip::where('mahasiswa_id', $mahasiswaId);
        
        if ($periodeId) {
            $query->where('periode_akademik_id', '<=', $periodeId);
        }

        $transkrip = $query->get();

        if ($transkrip->isEmpty()) {
            return 0;
        }

        $totalBobot = 0;
        $totalSks = 0;

        foreach ($transkrip as $item) {
            $bobot = $item->nilai_mutu * $item->sks;
            $totalBobot += $bobot;
            $totalSks += $item->sks;
        }

        return $totalSks > 0 ? round($totalBobot / $totalSks, 2) : 0;
    }

    /**
     * Generate transkrip dari nilai
     */
    public function generateTranskripFromNilai($mahasiswaId, $periodeId)
    {
        DB::beginTransaction();
        
        try {
            $nilai = Nilai::where('mahasiswa_id', $mahasiswaId)
                ->where('periode_akademik_id', $periodeId)
                ->get();

            foreach ($nilai as $n) {
                // Hanya simpan ke transkrip jika nilai lulus
                if ($n->nilai_mutu > 0) {
                    Transkrip::updateOrCreate(
                        [
                            'mahasiswa_id' => $mahasiswaId,
                            'mata_kuliah_id' => $n->mata_kuliah_id,
                            'periode_akademik_id' => $periodeId,
                        ],
                        [
                            'nilai_angka' => $n->nilai_angka,
                            'nilai_huruf' => $n->nilai_huruf,
                            'nilai_mutu' => $n->nilai_mutu,
                            'sks' => $n->mataKuliah->total_sks,
                        ]
                    );
                }
            }

            // Update IPK dan total SKS mahasiswa
            $this->updateMahasiswaIpkSks($mahasiswaId);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Update IPK dan total SKS mahasiswa
     */
    private function updateMahasiswaIpkSks($mahasiswaId)
    {
        $ipk = $this->calculateIPK($mahasiswaId);
        
        $totalSks = Transkrip::where('mahasiswa_id', $mahasiswaId)
            ->where('nilai_mutu', '>', 0)
            ->sum('sks');

        Mahasiswa::where('id', $mahasiswaId)->update([
            'ipk' => $ipk,
            'total_sks' => $totalSks,
        ]);
    }

    /**
     * Ambil semua KHS untuk mahasiswa
     */
    public function getAllKhsByMahasiswa($mahasiswaId)
    {
        $periode = PeriodeAkademik::orderBy('tanggal_mulai', 'desc')->get();
        
        $khsData = [];
        foreach ($periode as $p) {
            $nilai = $this->getKhsByMahasiswaAndPeriode($mahasiswaId, $p->id);
            if ($nilai->count() > 0) {
                $ip = $this->calculateIP($nilai);
                $khsData[] = [
                    'periode' => $p,
                    'nilai' => $nilai,
                    'ip' => $ip,
                ];
            }
        }

        return $khsData;
    }
}