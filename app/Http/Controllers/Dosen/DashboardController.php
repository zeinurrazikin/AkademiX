<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Nilai;
use App\Models\Krs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $dosen = Auth::user()->dosen;
        
        $totalJadwal = $dosen->jadwal()->count();
        $totalNilai = Nilai::where('dosen_id', $dosen->id)->count();
        $totalKrsDiajukan = Krs::whereHas('krsDetail.jadwal', function($q) use ($dosen) {
            $q->where('dosen_id', $dosen->id);
        })->where('status', 'diajukan')->count();
        
        $stats = [
            'total_jadwal' => $totalJadwal,
            'total_nilai' => $totalNilai,
            'total_krs_diajukan' => $totalKrsDiajukan,
        ];

        return view('dosen.dashboard', compact('stats'));
    }
}