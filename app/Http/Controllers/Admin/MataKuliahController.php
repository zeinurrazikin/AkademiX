<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataKuliah;
use Illuminate\Http\Request;

class MataKuliahController extends Controller
{
    public function index(Request $request)
    {
        $query = MataKuliah::query();
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_mk', 'like', "%{$search}%")
                  ->orWhere('kode_mk', 'like', "%{$search}%");
            });
        }

        $mataKuliah = $query->paginate(10)->withQueryString();
        
        return view('admin.mata_kuliah.index', compact('mataKuliah'));
    }

    public function create()
    {
        return view('admin.mata_kuliah.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_mk' => 'required|string|unique:mata_kuliah,kode_mk',
            'nama_mk' => 'required|string|max:255',
            'sks_teori' => 'required|integer|min:0',
            'sks_praktikum' => 'required|integer|min:0',
            'sks_praktek_lapangan' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'prasyarat' => 'nullable|string|max:255',
        ]);

        $totalSks = $request->sks_teori + $request->sks_praktikum + $request->sks_praktek_lapangan;

        MataKuliah::create([
            'kode_mk' => $request->kode_mk,
            'nama_mk' => $request->nama_mk,
            'sks_teori' => $request->sks_teori,
            'sks_praktikum' => $request->sks_praktikum,
            'sks_praktek_lapangan' => $request->sks_praktek_lapangan,
            'total_sks' => $totalSks,
            'deskripsi' => $request->deskripsi,
            'prasyarat' => $request->prasyarat,
        ]);

        return redirect()->route('admin.mata_kuliah.index')->with('success', 'Mata kuliah berhasil ditambahkan');
    }

    public function edit(MataKuliah $mataKuliah)
    {
        return view('admin.mata_kuliah.edit', compact('mataKuliah'));
    }

    public function update(Request $request, MataKuliah $mataKuliah)
    {
        $request->validate([
            'kode_mk' => 'required|string|unique:mata_kuliah,kode_mk,' . $mataKuliah->id,
            'nama_mk' => 'required|string|max:255',
            'sks_teori' => 'required|integer|min:0',
            'sks_praktikum' => 'required|integer|min:0',
            'sks_praktek_lapangan' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'prasyarat' => 'nullable|string|max:255',
        ]);

        $totalSks = $request->sks_teori + $request->sks_praktikum + $request->sks_praktek_lapangan;

        $mataKuliah->update([
            'kode_mk' => $request->kode_mk,
            'nama_mk' => $request->nama_mk,
            'sks_teori' => $request->sks_teori,
            'sks_praktikum' => $request->sks_praktikum,
            'sks_praktek_lapangan' => $request->sks_praktek_lapangan,
            'total_sks' => $totalSks,
            'deskripsi' => $request->deskripsi,
            'prasyarat' => $request->prasyarat,
        ]);

        return redirect()->route('admin.mata_kuliah.index')->with('success', 'Mata kuliah berhasil diperbarui');
    }

    public function destroy(MataKuliah $mataKuliah)
    {
        $mataKuliah->delete();
        return redirect()->route('admin.mata_kuliah.index')->with('success', 'Mata kuliah berhasil dihapus');
    }
}