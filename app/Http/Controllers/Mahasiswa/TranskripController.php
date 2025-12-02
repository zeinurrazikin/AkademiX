<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Transkrip;
use App\Services\KhsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TranskripController extends Controller
{
    protected $khsService;

    public function __construct(KhsService $khsService)
    {
        $this->khsService = $khsService;
    }

    public function index()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $transkrip = Transkrip::where('mahasiswa_id', $mahasiswa->id)
            ->with(['mataKuliah', 'periodeAkademik'])
            ->orderBy('periode_akademik_id')
            ->get();

        $ipk = $this->khsService->calculateIPK($mahasiswa->id);

        return view('mahasiswa.transkrip.index', compact('transkrip', 'ipk'));
    }

    public function generatePdf()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $transkrip = Transkrip::where('mahasiswa_id', $mahasiswa->id)
            ->with(['mataKuliah', 'periodeAkademik'])
            ->orderBy('periode_akademik_id')
            ->get();

        $ipk = $this->khsService->calculateIPK($mahasiswa->id);

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('mahasiswa.transkrip.pdf', compact('mahasiswa', 'transkrip', 'ipk'));
        
        return $pdf->download('transkrip_nilai_' . $mahasiswa->nim . '.pdf');
    }
}