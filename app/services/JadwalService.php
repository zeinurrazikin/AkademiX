<?php

namespace App\Services;

use App\Models\Jadwal;
use App\Models\Ruang;
use App\Models\Dosen;
use App\Models\PeriodeAkademik;
use Illuminate\Support\Facades\DB;

class JadwalService
{
    /**
     * Cek apakah jadwal bentrok dengan jadwal lain
     */
    public function cekBentrokJadwal($hari, $jamMulai, $jamSelesai, $ruangId = null, $dosenId = null, $id = null)
    {
        $query = Jadwal::where('hari', $hari);
        
        // Cek bentrok waktu
        $query->where(function($q) use ($jamMulai, $jamSelesai) {
            $q->where(function($sub) use ($jamMulai, $jamSelesai) {
                $sub->where('jam_mulai', '<', $jamSelesai)
                    ->where('jam_selesai', '>', $jamMulai);
            });
        });

        // Filter berdasarkan ruang
        if ($ruangId) {
            $query->where('ruang_id', $ruangId);
        }

        // Filter berdasarkan dosen
        if ($dosenId) {
            $query->where('dosen_id', $dosenId);
        }

        // Exclude current jadwal if updating
        if ($id) {
            $query->where('id', '!=', $id);
        }

        return $query->exists();
    }

    /**
     * Validasi jadwal sebelum disimpan
     */
    public function validateJadwal($data, $id = null)
    {
        $errors = [];

        // Cek bentrok ruang
        if ($this->cekBentrokJadwal($data['hari'], $data['jam_mulai'], $data['jam_selesai'], $data['ruang_id'], null, $id)) {
            $errors[] = 'Jadwal bentrok dengan jadwal lain di ruang yang sama';
        }

        // Cek bentrok dosen
        if ($this->cekBentrokJadwal($data['hari'], $data['jam_mulai'], $data['jam_selesai'], null, $data['dosen_id'], $id)) {
            $errors[] = 'Jadwal bentrok dengan jadwal lain yang diampu oleh dosen yang sama';
        }

        // Cek kapasitas ruang
        $ruang = Ruang::find($data['ruang_id']);
        $kelas = \App\Models\Kelas::find($data['kelas_id']);
        
        if ($ruang && $kelas && $ruang->kapasitas < $kelas->kuota) {
            $errors[] = 'Kapasitas ruang tidak mencukupi untuk jumlah mahasiswa di kelas ini';
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
        ];
    }

    /**
     * Simpan jadwal
     */
    public function storeJadwal($data)
    {
        $validation = $this->validateJadwal($data);
        
        if (!$validation['valid']) {
            throw new \Exception(implode(', ', $validation['errors']));
        }

        $kodeJadwal = $data['mata_kuliah_id'] . '-' . $data['kelas_id'] . '-' . $data['hari'];

        return Jadwal::create([
            'periode_akademik_id' => $data['periode_akademik_id'],
            'mata_kuliah_id' => $data['mata_kuliah_id'],
            'dosen_id' => $data['dosen_id'],
            'kelas_id' => $data['kelas_id'],
            'ruang_id' => $data['ruang_id'],
            'hari' => $data['hari'],
            'jam_mulai' => $data['jam_mulai'],
            'jam_selesai' => $data['jam_selesai'],
            'kode_jadwal' => $kodeJadwal,
        ]);
    }

    /**
     * Update jadwal
     */
    public function updateJadwal($id, $data)
    {
        $validation = $this->validateJadwal($data, $id);
        
        if (!$validation['valid']) {
            throw new \Exception(implode(', ', $validation['errors']));
        }

        $kodeJadwal = $data['mata_kuliah_id'] . '-' . $data['kelas_id'] . '-' . $data['hari'];

        $jadwal = Jadwal::findOrFail($id);
        $jadwal->update([
            'periode_akademik_id' => $data['periode_akademik_id'],
            'mata_kuliah_id' => $data['mata_kuliah_id'],
            'dosen_id' => $data['dosen_id'],
            'kelas_id' => $data['kelas_id'],
            'ruang_id' => $data['ruang_id'],
            'hari' => $data['hari'],
            'jam_mulai' => $data['jam_mulai'],
            'jam_selesai' => $data['jam_selesai'],
            'kode_jadwal' => $kodeJadwal,
        ]);

        return $jadwal;
    }

    /**
     * Ambil jadwal berdasarkan periode dan dosen
     */
    public function getJadwalByDosenAndPeriode($dosenId, $periodeId)
    {
        return Jadwal::where('dosen_id', $dosenId)
            ->where('periode_akademik_id', $periodeId)
            ->with(['mataKuliah', 'kelas', 'ruang', 'periodeAkademik'])
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->get();
    }

    /**
     * Ambil jadwal berdasarkan periode dan mahasiswa
     */
    public function getJadwalByMahasiswaAndPeriode($mahasiswaId, $periodeId)
    {
        return \App\Models\KrsDetail::whereHas('krs', function($q) use ($mahasiswaId, $periodeId) {
            $q->where('mahasiswa_id', $mahasiswaId)
              ->where('periode_akademik_id', $periodeId);
        })
        ->with(['jadwal.mataKuliah', 'jadwal.dosen', 'jadwal.kelas', 'jadwal.ruang'])
        ->get()
        ->pluck('jadwal');
    }

    /**
     * Hitung total jam mengajar dosen dalam satu periode
     */
    public function getTotalJamMengajar($dosenId, $periodeId)
    {
        $jadwal = Jadwal::where('dosen_id', $dosenId)
            ->where('periode_akademik_id', $periodeId)
            ->get();

        $totalJam = 0;
        foreach ($jadwal as $j) {
            $mulai = \Carbon\Carbon::parse($j->jam_mulai);
            $selesai = \Carbon\Carbon::parse($j->jam_selesai);
            $durasi = $selesai->diffInMinutes($mulai) / 60;
            $totalJam += $durasi;
        }

        return $totalJam;
    }

    /**
     * Ambil jadwal berdasarkan hari
     */
    public function getJadwalByHari($periodeId, $hari)
    {
        return Jadwal::where('periode_akademik_id', $periodeId)
            ->where('hari', $hari)
            ->with(['mataKuliah', 'dosen', 'kelas', 'ruang'])
            ->orderBy('jam_mulai')
            ->get();
    }
}