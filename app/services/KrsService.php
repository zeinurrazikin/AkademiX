<?php

namespace App\Services;

use App\Models\Jadwal;
use App\Models\Krs;
use App\Models\KrsDetail;
use App\Models\MataKuliah;
use App\Models\PeriodeAkademik;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\DB;

class KrsService
{
    /**
     * Validasi KRS sebelum disimpan
     */
    public function validateKrs($mahasiswaId, $jadwalIds, $periodeId)
    {
        $errors = [];
        $totalSks = 0;
        $jadwalList = Jadwal::whereIn('id', $jadwalIds)->with(['mataKuliah'])->get();
        
        // Cek apakah mahasiswa sudah memiliki KRS aktif untuk periode ini
        $existingKrs = Krs::where('mahasiswa_id', $mahasiswaId)
            ->where('periode_akademik_id', $periodeId)
            ->where('status', '!=', 'draft')
            ->first();
        
        if ($existingKrs) {
            $errors[] = 'Mahasiswa sudah memiliki KRS aktif untuk periode ini';
        }

        // Cek bentrok jadwal
        $conflicts = $this->checkJadwalConflict($jadwalList);
        if ($conflicts) {
            $errors[] = 'Terdapat bentrok jadwal: ' . implode(', ', $conflicts);
        }

        // Hitung total SKS dan cek batas maksimal
        foreach ($jadwalList as $jadwal) {
            $totalSks += $jadwal->mataKuliah->total_sks;
        }

        $mahasiswa = Mahasiswa::find($mahasiswaId);
        $batasSks = $this->getBatasSks($mahasiswa->ipk);
        
        if ($totalSks > $batasSks) {
            $errors[] = "Total SKS melebihi batas maksimal ({$totalSks} > {$batasSks})";
        }

        // Cek prasyarat mata kuliah
        $prasyaratErrors = $this->checkPrasyarat($mahasiswaId, $jadwalIds);
        if ($prasyaratErrors) {
            $errors = array_merge($errors, $prasyaratErrors);
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'total_sks' => $totalSks,
        ];
    }

    /**
     * Cek bentrok jadwal
     */
    private function checkJadwalConflict($jadwalList)
    {
        $conflicts = [];
        $jadwalArray = $jadwalList->toArray();

        for ($i = 0; $i < count($jadwalArray); $i++) {
            for ($j = $i + 1; $j < count($jadwalArray); $j++) {
                if ($jadwalArray[$i]['hari'] === $jadwalArray[$j]['hari']) {
                    $jamMulai1 = \Carbon\Carbon::parse($jadwalArray[$i]['jam_mulai']);
                    $jamSelesai1 = \Carbon\Carbon::parse($jadwalArray[$i]['jam_selesai']);
                    $jamMulai2 = \Carbon\Carbon::parse($jadwalArray[$j]['jam_mulai']);
                    $jamSelesai2 = \Carbon\Carbon::parse($jadwalArray[$j]['jam_selesai']);

                    if ($jamMulai2 < $jamSelesai1 && $jamSelesai2 > $jamMulai1) {
                        $conflicts[] = $jadwalArray[$i]['mataKuliah']['kode_mk'] . 
                                     ' dan ' . $jadwalArray[$j]['mataKuliah']['kode_mk'];
                    }
                }
            }
        }

        return $conflicts;
    }

    /**
     * Cek prasyarat mata kuliah
     */
    private function checkPrasyarat($mahasiswaId, $jadwalIds)
    {
        $errors = [];
        $jadwalList = Jadwal::whereIn('id', $jadwalIds)->with(['mataKuliah'])->get();
        
        foreach ($jadwalList as $jadwal) {
            $mataKuliah = $jadwal->mataKuliah;
            
            if ($mataKuliah->prasyarat) {
                $prasyaratMk = MataKuliah::where('kode_mk', $mataKuliah->prasyarat)->first();
                
                if ($prasyaratMk) {
                    // Cek apakah mahasiswa sudah lulus prasyarat
                    $transkrip = \App\Models\Transkrip::where('mahasiswa_id', $mahasiswaId)
                        ->where('mata_kuliah_id', $prasyaratMk->id)
                        ->where('nilai_mutu', '>', 0)
                        ->first();
                    
                    if (!$transkrip) {
                        $errors[] = "Mata kuliah {$mataKuliah->kode_mk} memerlukan prasyarat {$mataKuliah->prasyarat} yang belum lulus";
                    }
                }
            }
        }

        return $errors;
    }

    /**
     * Dapatkan batas SKS berdasarkan IPK
     */
    private function getBatasSks($ipk)
    {
        if ($ipk >= 3.5) {
            return 24;
        } elseif ($ipk >= 3.0) {
            return 22;
        } elseif ($ipk >= 2.5) {
            return 20;
        } elseif ($ipk >= 2.0) {
            return 18;
        } else {
            return 16;
        }
    }

    /**
     * Simpan KRS
     */
    public function storeKrs($mahasiswaId, $periodeId, $jadwalIds)
    {
        DB::beginTransaction();
        
        try {
            // Validasi KRS
            $validation = $this->validateKrs($mahasiswaId, $jadwalIds, $periodeId);
            
            if (!$validation['valid']) {
                throw new \Exception(implode(', ', $validation['errors']));
            }

            // Buat KRS
            $krs = Krs::create([
                'mahasiswa_id' => $mahasiswaId,
                'periode_akademik_id' => $periodeId,
                'nomor_krs' => 'KRS-' . date('Y') . '-' . str_pad($mahasiswaId, 6, '0', STR_PAD_LEFT),
                'status' => 'diajukan',
                'total_sks' => $validation['total_sks'],
            ]);

            // Tambahkan detail KRS
            foreach ($jadwalIds as $jadwalId) {
                KrsDetail::create([
                    'krs_id' => $krs->id,
                    'jadwal_id' => $jadwalId,
                ]);
            }

            DB::commit();
            return $krs;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Update status KRS
     */
    public function updateStatus($krsId, $status)
    {
        $krs = Krs::findOrFail($krsId);
        $krs->update(['status' => $status]);
        return $krs;
    }
}