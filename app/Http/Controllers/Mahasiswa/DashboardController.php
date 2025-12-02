<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Krs;
use App\Models\Nilai;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        
        $totalKrs = $mahasiswa->krs()->count();
        $totalNilai = Nilai::where('mahasiswa_id', $mahasiswa->id)->count();
        $totalKrsDisetujui = $mahasiswa->krs()->where('status', 'disetujui')->count();
        
        $stats = [
            'total_krs' => $totalKrs,
            'total_nilai' => $totalNilai,
            'total_krs_disetujui' => $totalKrsDisetujui,
        ];

        return view('mahasiswa.dashboard', compact('stats'));
    }
}