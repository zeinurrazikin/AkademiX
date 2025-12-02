<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>KHS - {{ $mahasiswa->nama_lengkap_with_nim }} - {{ $periode->tahun_akademik }} {{ $periode->semester }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 0;
            padding: 20px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 16px;
            margin: 0;
            font-weight: bold;
        }
        .header h2 {
            font-size: 14px;
            margin: 5px 0;
            font-weight: normal;
        }
        .header h3 {
            font-size: 12px;
            margin: 5px 0;
            font-weight: normal;
        }
        .student-info {
            margin: 15px 0;
            font-size: 12px;
        }
        .student-info table {
            width: 100%;
            border-collapse: collapse;
        }
        .student-info td {
            padding: 2px 0;
            vertical-align: top;
        }
        .student-info .label {
            width: 150px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 11px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .summary {
            margin: 20px 0;
            font-size: 12px;
        }
        .summary table {
            width: 100%;
            border-collapse: collapse;
        }
        .summary td {
            border: 1px solid #000;
            padding: 6px;
        }
        .footer {
            margin-top: 40px;
            text-align: right;
            font-size: 12px;
        }
        .footer .date {
            margin-bottom: 30px;
        }
        .footer .signature {
            display: inline-block;
            width: 200px;
            text-align: center;
        }
        .footer .signature-line {
            margin-top: 60px;
            border-top: 1px solid #000;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</h1>
        <h2>UNIVERSITAS AKADEMIK</h2>
        <h3>FAKULTAS TEKNOLOGI INFORMASI</h3>
    </div>
    
    <div class="student-info">
        <table>
            <tr>
                <td class="label">Nama Mahasiswa</td>
                <td>: {{ $mahasiswa->nama_lengkap }}</td>
            </tr>
            <tr>
                <td class="label">NIM</td>
                <td>: {{ $mahasiswa->nim }}</td>
            </tr>
            <tr>
                <td class="label">Program Studi</td>
                <td>: Teknik Informatika</td>
            </tr>
            <tr>
                <td class="label">Fakultas</td>
                <td>: Teknologi Informasi</td>
            </tr>
            <tr>
                <td class="label">Tahun Akademik</td>
                <td>: {{ $periode->tahun_akademik }} - {{ $periode->semester }}</td>
            </tr>
        </table>
    </div>
    
    <table>
        <thead>
            <tr>
                <th width="5%" style="text-align: center;">No</th>
                <th width="10%">Kode MK</th>
                <th width="30%">Nama Mata Kuliah</th>
                <th width="5%" style="text-align: center;">SKS</th>
                <th width="10%" style="text-align: center;">Nilai Angka</th>
                <th width="10%" style="text-align: center;">Nilai Huruf</th>
                <th width="10%" style="text-align: center;">Nilai Mutu</th>
                <th width="15%" style="text-align: center;">Bobot</th>
            </tr>
        </thead>
        <tbody>
            @foreach($nilai as $index => $item)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $item->mataKuliah->kode_mk }}</td>
                    <td>{{ $item->mataKuliah->nama_mk }}</td>
                    <td style="text-align: center;">{{ $item->mataKuliah->total_sks }}</td>
                    <td style="text-align: center;">{{ $item->nilai_angka }}</td>
                    <td style="text-align: center;">{{ $item->nilai_huruf }}</td>
                    <td style="text-align: center;">{{ $item->nilai_mutu }}</td>
                    <td style="text-align: center;">{{ number_format($item->nilai_mutu * $item->mataKuliah->total_sks, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="summary">
        <table>
            <tr>
                <td width="70%">
                    <strong>Jumlah Mata Kuliah: {{ $nilai->count() }}</strong>
                </td>
                <td width="30%">
                    <strong>Total SKS: {{ $nilai->sum(function($item) { return $item->mataKuliah->total_sks; }) }}</strong>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <strong>Indeks Prestasi (IP): {{ number_format($ip, 2) }}</strong>
                </td>
            </tr>
        </table>
    </div>
    
    <div class="footer">
        <div class="date">Jakarta, {{ \Carbon\Carbon::now()->format('d F Y') }}</div>
        <div class="signature">
            <div>Ka. Prodi Teknik Informatika</div>
            <div style="margin-top: 60px; font-weight: bold;">Dr. Ir. John Doe, M.T.</div>
            <div>NIP. 19750101 200501 1 001</div>
        </div>
    </div>
</body>
</html>