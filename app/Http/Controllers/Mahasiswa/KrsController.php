<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Krs;
use App\Models\KrsDetail;
use App\Models\Jadwal;
use App\Models\PeriodeAkademik;
use App\Services\KrsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KrsController extends Controller
{
    protected $krsService;

    public function __construct(KrsService $krsService)
    {
        $this->krsService = $krsService;
    }

    public function index()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $krs = $mahasiswa->krs()->with(['periodeAkademik', 'krsDetail.jadwal.mataKuliah'])->paginate(10);
        return view('mahasiswa.krs.index', compact('krs'));
    }

    public function create()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $periodeAktif = PeriodeAkademik::aktif()->first();
        
        if (!$periodeAktif) {
            return redirect()->back()->with('error', 'Tidak ada periode akademik aktif');
        }

        $jadwal = Jadwal::where('periode_akademik_id', $periodeAktif->id)
            ->with(['mataKuliah', 'dosen', 'kelas', 'ruang'])
            ->get();

        return view('mahasiswa.krs.create', compact('jadwal', 'periodeAktif'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|array',
            'jadwal_id.*' => 'required|exists:jadwal,id',
        ]);

        $mahasiswa = Auth::user()->mahasiswa;
        $periodeAktif = PeriodeAkademik::aktif()->first();

        if (!$periodeAktif) {
            return redirect()->back()->with('error', 'Tidak ada periode akademik aktif');
        }

        // Validasi KRS
        $validation = $this->krsService->validateKrs($mahasiswa->id, $request->jadwal_id, $periodeAktif->id);
        
        if (!$validation['valid']) {
            return redirect()->back()->withErrors($validation['errors'])->withInput();
        }

        // Buat KRS
        $krs = Krs::create([
            'mahasiswa_id' => $mahasiswa->id,
            'periode_akademik_id' => $periodeAktif->id,
            'nomor_krs' => 'KRS-' . date('Y') . '-' . str_pad($mahasiswa->id, 6, '0', STR_PAD_LEFT),
            'status' => 'diajukan',
            'total_sks' => $validation['total_sks'],
        ]);

        // Tambahkan detail KRS
        foreach ($request->jadwal_id as $jadwalId) {
            KrsDetail::create([
                'krs_id' => $krs->id,
                'jadwal_id' => $jadwalId,
            ]);
        }

        return redirect()->route('mahasiswa.krs.index')->with('success', 'KRS berhasil diajukan');
    }

    public function show($krsId)
    {
        $krs = Krs::with(['mahasiswa', 'periodeAkademik', 'krsDetail.jadwal.mataKuliah', 'krsDetail.jadwal.dosen', 'krsDetail.jadwal.kelas', 'krsDetail.jadwal.ruang'])->findOrFail($krsId);
        
        // Pastikan KRS milik mahasiswa yang login
        if ($krs->mahasiswa_id !== Auth::user()->mahasiswa->id) {
            abort(403, 'Akses ditolak');
        }

        return view('mahasiswa.krs.show', compact('krs'));
    }

    public function destroy($krsId)
    {
        $krs = Krs::findOrFail($krsId);
        
        // Pastikan KRS milik mahasiswa yang login dan statusnya draft
        if ($krs->mahasiswa_id !== Auth::user()->mahasiswa->id || $krs->status !== 'draft') {
            abort(403, 'Akses ditolak');
        }

        $krs->delete();
        return redirect()->route('mahasiswa.krs.index')->with('success', 'KRS berhasil dihapus');
    }
}