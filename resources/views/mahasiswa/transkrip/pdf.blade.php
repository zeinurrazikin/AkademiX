<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Transkrip Nilai - {{ $mahasiswa->nama_lengkap_with_nim }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 18px;
            margin: 0;
        }
        .header h2 {
            font-size: 16px;
            margin: 5px 0;
        }
        .student-info {
            margin: 10px 0;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            font-size: 11px;
        }
        th {
            background-color: #f0f0f0;
        }
        .summary {
            margin: 20px 0;
            font-size: 12px;
        }
        .footer {
            margin-top: 40px;
            text-align: right;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>UNIVERSITAS AKADEMIK</h1>
        <h2>Transkrip Nilai</h2>
    </div>
    
    <div class="student-info">
        <p><strong>Nama:</strong> {{ $mahasiswa->nama_lengkap }}</p>
        <p><strong>NIM:</strong> {{ $mahasiswa->nim }}</p>
        <p><strong>Program Studi:</strong> Teknik Informatika</p>
        <p><strong>Tahun Masuk:</strong> {{ $mahasiswa->tahun_masuk }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode MK</th>
                <th>Nama Mata Kuliah</th>
                <th>SKS</th>
                <th>Periode</th>
                <th>Nilai Angka</th>
                <th>Nilai Huruf</th>
                <th>Nilai Mutu</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transkrip as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->mataKuliah->kode_mk }}</td>
                    <td>{{ $item->mataKuliah->nama_mk }}</td>
                    <td>{{ $item->sks }}</td>
                    <td>{{ $item->periodeAkademik->tahun_akademik }} - {{ $item->periodeAkademik->semester }}</td>
                    <td>{{ $item->nilai_angka }}</td>
                    <td>{{ $item->nilai_huruf }}</td>
                    <td>{{ $item->nilai_mutu }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="summary">
        <p><strong>Total SKS:</strong> {{ $transkrip->sum('sks') }}</p>
        <p><strong>IPK:</strong> {{ number_format($ipk, 2) }}</p>
    </div>
    
    <div class="footer">
        <p>{{ date('d F Y') }}</p>
        <p>Ka. Prodi Teknik Informatika</p>
        <br><br>
        <p>(________________)</p>
    </div>
</body>
</html>