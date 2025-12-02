<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\MataKuliah;
use App\Models\PeriodeAkademik;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalMahasiswa = Mahasiswa::count();
        $totalDosen = Dosen::count();
        $totalMataKuliah = MataKuliah::count();
        $periodeAktif = PeriodeAkademik::aktif()->first();
        
        $stats = [
            'total_mahasiswa' => $totalMahasiswa,
            'total_dosen' => $totalDosen,
            'total_mata_kuliah' => $totalMataKuliah,
            'periode_aktif' => $periodeAktif ? $periodeAktif->nama_periode_lengkap : 'Tidak Ada',
        ];

        return view('admin.dashboard', compact('stats'));
    }
}