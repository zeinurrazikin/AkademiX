<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Krs;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KrsController extends Controller
{
    public function index()
    {
        $dosen = Auth::user()->dosen;
        
        $krsDiajukan = Krs::whereHas('krsDetail.jadwal', function($q) use ($dosen) {
            $q->where('dosen_id', $dosen->id);
        })->where('status', 'diajukan')
        ->with(['mahasiswa', 'periodeAkademik', 'krsDetail.jadwal.mataKuliah'])
        ->paginate(10);

        return view('dosen.krs.index', compact('krsDiajukan'));
    }

    public function show($krsId)
    {
        $krs = Krs::with(['mahasiswa', 'periodeAkademik', 'krsDetail.jadwal.mataKuliah', 'krsDetail.jadwal.dosen', 'krsDetail.jadwal.kelas', 'krsDetail.jadwal.ruang'])->findOrFail($krsId);
        
        // Pastikan KRS milik mahasiswa yang dosen ini ajarkan
        $dosen = Auth::user()->dosen;
        $hasAccess = $krs->krsDetail->contains(function($detail) use ($dosen) {
            return $detail->jadwal->dosen_id === $dosen->id;
        });

        if (!$hasAccess) {
            abort(403, 'Akses ditolak');
        }

        return view('dosen.krs.show', compact('krs'));
    }

    public function approve($krsId)
    {
        $krs = Krs::findOrFail($krsId);
        
        // Pastikan KRS milik mahasiswa yang dosen ini ajarkan
        $dosen = Auth::user()->dosen;
        $hasAccess = $krs->krsDetail->contains(function($detail) use ($dosen) {
            return $detail->jadwal->dosen_id === $dosen->id;
        });

        if (!$hasAccess) {
            abort(403, 'Akses ditolak');
        }

        $krs->update(['status' => 'disetujui']);
        return redirect()->route('dosen.krs.index')->with('success', 'KRS berhasil disetujui');
    }

    public function reject($krsId)
    {
        $krs = Krs::findOrFail($krsId);
        
        // Pastikan KRS milik mahasiswa yang dosen ini ajarkan
        $dosen = Auth::user()->dosen;
        $hasAccess = $krs->krsDetail->contains(function($detail) use ($dosen) {
            return $detail->jadwal->dosen_id === $dosen->id;
        });

        if (!$hasAccess) {
            abort(403, 'Akses ditolak');
        }

        $krs->update(['status' => 'ditolak']);
        return redirect()->route('dosen.krs.index')->with('success', 'KRS berhasil ditolak');
    }
}