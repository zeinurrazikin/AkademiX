<?php

namespace App\Services;

use App\Models\Mahasiswa;
use App\Models\Transkrip;
use App\Models\Nilai;
use App\Models\PeriodeAkademik;
use Illuminate\Support\Facades\View;

class PdfGeneratorService
{
    /**
     * Generate transkrip PDF
     */
    public function generateTranskripPdf($mahasiswaId)
    {
        $mahasiswa = Mahasiswa::findOrFail($mahasiswaId);
        $transkrip = Transkrip::where('mahasiswa_id', $mahasiswaId)
            ->with(['mataKuliah', 'periodeAkademik'])
            ->orderBy('periode_akademik_id')
            ->get();

        $ipk = $this->calculateIPK($mahasiswaId);

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pdf.transkrip', compact('mahasiswa', 'transkrip', 'ipk'));
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf;
    }

    /**
     * Generate KHS PDF
     */
    public function generateKhsPdf($mahasiswaId, $periodeId)
    {
        $mahasiswa = Mahasiswa::findOrFail($mahasiswaId);
        $periode = PeriodeAkademik::findOrFail($periodeId);
        
        $nilai = Nilai::where('mahasiswa_id', $mahasiswaId)
            ->where('periode_akademik_id', $periodeId)
            ->with(['mataKuliah', 'dosen', 'periodeAkademik'])
            ->get();

        $ip = $this->calculateIP($nilai);

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pdf.khs', compact('mahasiswa', 'periode', 'nilai', 'ip'));
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf;
    }

    /**
     * Calculate IPK
     */
    private function calculateIPK($mahasiswaId)
    {
        $transkrip = Transkrip::where('mahasiswa_id', $mahasiswaId)
            ->where('nilai_mutu', '>', 0)
            ->get();

        if ($transkrip->isEmpty()) {
            return 0;
        }

        $totalBobot = 0;
        $totalSks = 0;

        foreach ($transkrip as $item) {
            $bobot = $item->nilai_mutu * $item->sks;
            $totalBobot += $bobot;
            $totalSks += $item->sks;
        }

        return $totalSks > 0 ? round($totalBobot / $totalSks, 2) : 0;
    }

    /**
     * Calculate IP
     */
    private function calculateIP($nilaiCollection)
    {
        if ($nilaiCollection->isEmpty()) {
            return 0;
        }

        $totalBobot = 0;
        $totalSks = 0;

        foreach ($nilaiCollection as $nilai) {
            $bobot = $nilai->nilai_mutu * $nilai->mataKuliah->total_sks;
            $totalBobot += $bobot;
            $totalSks += $nilai->mataKuliah->total_sks;
        }

        return $totalSks > 0 ? round($totalBobot / $totalSks, 2) : 0;
    }
}