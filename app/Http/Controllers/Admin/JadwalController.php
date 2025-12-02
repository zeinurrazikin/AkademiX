<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\PeriodeAkademik;
use App\Models\MataKuliah;
use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Ruang;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $query = Jadwal::with(['periodeAkademik', 'mataKuliah', 'dosen', 'kelas', 'ruang']);
        
        if ($request->has('periode_id') && $request->periode_id) {
            $query->where('periode_akademik_id', $request->periode_id);
        }

        $jadwal = $query->paginate(10)->withQueryString();
        $periodeList = PeriodeAkademik::all();
        
        return view('admin.jadwal.index', compact('jadwal', 'periodeList'));
    }

    public function create()
    {
        $periodeList = PeriodeAkademik::all();
        $mataKuliahList = MataKuliah::all();
        $dosenList = Dosen::all();
        $kelasList = Kelas::all();
        $ruangList = Ruang::all();
        
        return view('admin.jadwal.create', compact('periodeList', 'mataKuliahList', 'dosenList', 'kelasList', 'ruangList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'periode_akademik_id' => 'required|exists:periode_akademik,id',
            'mata_kuliah_id' => 'required|exists:mata_kuliah,id',
            'dosen_id' => 'required|exists:dosen,id',
            'kelas_id' => 'required|exists:kelas,id',
            'ruang_id' => 'required|exists:ruang,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        $kodeJadwal = $request->mata_kuliah_id . '-' . $request->kelas_id . '-' . $request->hari;

        Jadwal::create([
            'periode_akademik_id' => $request->periode_akademik_id,
            'mata_kuliah_id' => $request->mata_kuliah_id,
            'dosen_id' => $request->dosen_id,
            'kelas_id' => $request->kelas_id,
            'ruang_id' => $request->ruang_id,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'kode_jadwal' => $kodeJadwal,
        ]);

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil ditambahkan');
    }

    public function edit(Jadwal $jadwal)
    {
        $periodeList = PeriodeAkademik::all();
        $mataKuliahList = MataKuliah::all();
        $dosenList = Dosen::all();
        $kelasList = Kelas::all();
        $ruangList = Ruang::all();
        
        return view('admin.jadwal.edit', compact('jadwal', 'periodeList', 'mataKuliahList', 'dosenList', 'kelasList', 'ruangList'));
    }

    public function update(Request $request, Jadwal $jadwal)
    {
        $request->validate([
            'periode_akademik_id' => 'required|exists:periode_akademik,id',
            'mata_kuliah_id' => 'required|exists:mata_kuliah,id',
            'dosen_id' => 'required|exists:dosen,id',
            'kelas_id' => 'required|exists:kelas,id',
            'ruang_id' => 'required|exists:ruang,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        $kodeJadwal = $request->mata_kuliah_id . '-' . $request->kelas_id . '-' . $request->hari;

        $jadwal->update([
            'periode_akademik_id' => $request->periode_akademik_id,
            'mata_kuliah_id' => $request->mata_kuliah_id,
            'dosen_id' => $request->dosen_id,
            'kelas_id' => $request->kelas_id,
            'ruang_id' => $request->ruang_id,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'kode_jadwal' => $kodeJadwal,
        ]);

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil diperbarui');
    }

    public function destroy(Jadwal $jadwal)
    {
        $jadwal->delete();
        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil dihapus');
    }
}