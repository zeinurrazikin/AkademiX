<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\KrsDetail;
use App\Models\PeriodeAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalController extends Controller
{
    public function index()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $periodeAktif = PeriodeAkademik::aktif()->first();
        
        if (!$periodeAktif) {
            return redirect()->back()->with('error', 'Tidak ada periode akademik aktif');
        }

        $jadwal = KrsDetail::whereHas('krs', function($q) use ($mahasiswa, $periodeAktif) {
            $q->where('mahasiswa_id', $mahasiswa->id)
              ->where('periode_akademik_id', $periodeAktif->id);
        })
        ->with(['jadwal.mataKuliah', 'jadwal.dosen', 'jadwal.kelas', 'jadwal.ruang'])
        ->get()
        ->pluck('jadwal');

        return view('mahasiswa.jadwal.index', compact('jadwal', 'periodeAktif'));
    }

    public function all()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $periodeAktif = PeriodeAkademik::aktif()->first();
        
        if (!$periodeAktif) {
            return redirect()->back()->with('error', 'Tidak ada periode akademik aktif');
        }

        $jadwal = Jadwal::where('periode_akademik_id', $periodeAktif->id)
            ->with(['mataKuliah', 'dosen', 'kelas', 'ruang'])
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->get();

        return view('mahasiswa.jadwal.all', compact('jadwal', 'periodeAktif'));
    }
}