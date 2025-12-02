<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use App\Models\PeriodeAkademik;
use App\Services\KhsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KhsController extends Controller
{
    protected $khsService;

    public function __construct(KhsService $khsService)
    {
        $this->khsService = $khsService;
    }

    public function index()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $periode = PeriodeAkademik::orderBy('tanggal_mulai', 'desc')->get();
        
        $khsData = [];
        foreach ($periode as $p) {
            $nilai = $this->khsService->getKhsByMahasiswaAndPeriode($mahasiswa->id, $p->id);
            if ($nilai->count() > 0) {
                $ip = $this->khsService->calculateIP($nilai);
                $khsData[] = [
                    'periode' => $p,
                    'nilai' => $nilai,
                    'ip' => $ip,
                ];
            }
        }

        return view('mahasiswa.khs.index', compact('khsData'));
    }

    public function show($periodeId)
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $periode = PeriodeAkademik::findOrFail($periodeId);
        
        $nilai = $this->khsService->getKhsByMahasiswaAndPeriode($mahasiswa->id, $periodeId);
        $ip = $this->khsService->calculateIP($nilai);
        $ipk = $this->khsService->calculateIPK($mahasiswa->id, $periodeId);

        return view('mahasiswa.khs.show', compact('nilai', 'periode', 'ip', 'ipk'));
    }
}