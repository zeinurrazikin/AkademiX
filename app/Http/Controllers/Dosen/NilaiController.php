<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Nilai;
use App\Models\KrsDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NilaiController extends Controller
{
    public function index()
    {
        $dosen = Auth::user()->dosen;
        $jadwal = $dosen->jadwal()->with(['periodeAkademik', 'mataKuliah', 'kelas'])->get();
        return view('dosen.nilai.index', compact('jadwal'));
    }

    public function showInputNilai($jadwalId)
    {
        $jadwal = Jadwal::with(['periodeAkademik', 'mataKuliah', 'kelas', 'ruang'])->findOrFail($jadwalId);
        
        // Pastikan jadwal milik dosen yang login
        if ($jadwal->dosen_id !== Auth::user()->dosen->id) {
            abort(403, 'Akses ditolak');
        }

        $mahasiswa = $jadwal->krsDetail()
            ->with(['krs.mahasiswa', 'krs.periodeAkademik'])
            ->get()
            ->pluck('krs.mahasiswa')
            ->unique();

        $nilai = Nilai::whereIn('mahasiswa_id', $mahasiswa->pluck('id'))
            ->where('jadwal_id', $jadwalId)
            ->pluck('nilai_angka', 'mahasiswa_id')
            ->toArray();

        return view('dosen.nilai.input', compact('jadwal', 'mahasiswa', 'nilai'));
    }

    public function storeNilai(Request $request, $jadwalId)
    {
        $request->validate([
            'nilai.*' => 'required|numeric|min:0|max:100',
        ]);

        $jadwal = Jadwal::findOrFail($jadwalId);
        
        // Pastikan jadwal milik dosen yang login
        if ($jadwal->dosen_id !== Auth::user()->dosen->id) {
            abort(403, 'Akses ditolak');
        }

        foreach ($request->nilai as $mahasiswaId => $nilaiAngka) {
            $nilai = Nilai::updateOrCreate(
                [
                    'mahasiswa_id' => $mahasiswaId,
                    'jadwal_id' => $jadwalId,
                    'periode_akademik_id' => $jadwal->periode_akademik_id,
                    'mata_kuliah_id' => $jadwal->mata_kuliah_id,
                    'dosen_id' => $jadwal->dosen_id,
                ],
                [
                    'nilai_angka' => $nilaiAngka,
                ]
            );
        }

        return redirect()->route('dosen.nilai.input', $jadwalId)->with('success', 'Nilai berhasil disimpan');
    }
}