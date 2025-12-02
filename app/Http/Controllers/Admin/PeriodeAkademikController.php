<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PeriodeAkademik;
use Illuminate\Http\Request;

class PeriodeAkademikController extends Controller
{
    public function index()
    {
        $periode = PeriodeAkademik::orderBy('tanggal_mulai', 'desc')->paginate(10);
        return view('admin.periode.index', compact('periode'));
    }

    public function create()
    {
        return view('admin.periode.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun_akademik' => 'required|string|max:9',
            'semester' => 'required|in:Ganjil,Genap',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        ]);

        $kodePeriode = $request->tahun_akademik . '-' . $request->semester;

        PeriodeAkademik::create([
            'kode_periode' => $kodePeriode,
            'tahun_akademik' => $request->tahun_akademik,
            'semester' => $request->semester,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
        ]);

        return redirect()->route('admin.periode.index')->with('success', 'Periode akademik berhasil ditambahkan');
    }

    public function edit(PeriodeAkademik $periode)
    {
        return view('admin.periode.edit', compact('periode'));
    }

    public function update(Request $request, PeriodeAkademik $periode)
    {
        $request->validate([
            'tahun_akademik' => 'required|string|max:9',
            'semester' => 'required|in:Ganjil,Genap',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        ]);

        $kodePeriode = $request->tahun_akademik . '-' . $request->semester;

        $periode->update([
            'kode_periode' => $kodePeriode,
            'tahun_akademik' => $request->tahun_akademik,
            'semester' => $request->semester,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
        ]);

        return redirect()->route('admin.periode.index')->with('success', 'Periode akademik berhasil diperbarui');
    }

    public function destroy(PeriodeAkademik $periode)
    {
        $periode->delete();
        return redirect()->route('admin.periode.index')->with('success', 'Periode akademik berhasil dihapus');
    }

    public function setAktif(PeriodeAkademik $periode)
    {
        // Set semua periode menjadi tidak aktif
        PeriodeAkademik::where('status', 'aktif')->update(['status' => 'tutup']);
        
        // Set periode yang dipilih menjadi aktif
        $periode->update(['status' => 'aktif']);
        
        return redirect()->route('admin.periode.index')->with('success', 'Periode akademik berhasil diaktifkan');
    }
}