<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Krs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalController extends Controller
{
    public function index()
    {
        $dosen = Auth::user()->dosen;
        $jadwal = $dosen->jadwal()->with(['periodeAkademik', 'mataKuliah', 'kelas', 'ruang'])->get();
        return view('dosen.jadwal.index', compact('jadwal'));
    }

    public function showJadwalKelas($jadwalId)
    {
        $jadwal = Jadwal::with(['periodeAkademik', 'mataKuliah', 'kelas', 'ruang'])->findOrFail($jadwalId);
        
        // Pastikan jadwal milik dosen yang login
        if ($jadwal->dosen_id !== Auth::user()->dosen->id) {
            abort(403, 'Akses ditolak');
        }

        $mahasiswa = $jadwal->krsDetail()
            ->with(['krs.mahasiswa'])
            ->get()
            ->pluck('krs.mahasiswa')
            ->unique();

        return view('dosen.jadwal.show_kelas', compact('jadwal', 'mahasiswa'));
    }
}