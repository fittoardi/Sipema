<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Nilai Mahasiswa</title>
    <style>
        body { font-family: sans-serif; font-size: 10pt; margin: 20px; }
        h1 { text-align: center; margin-bottom: 20px; }
        .header { margin-bottom: 15px; }
        .header-info { display: flex; justify-content: space-between; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .footer { margin-top: 20px; text-align: center; font-size: 8pt; color: #666; }
        .total { font-weight: bold; }
    </style>
</head>
<body>
    <h1>Laporan Nilai Mahasiswa</h1>
    
    <div class="header">
        <div class="header-info">
            <div>
                <strong>Tanggal:</strong> {{ $tanggal }}
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIM</th>
                <th>Nama Mahasiswa</th>
                <th>Prodi</th>
                <th>Mata Kuliah</th>
                <th>Kelas</th>
                <th>Nilai Akhir</th>
                <th>Grade</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($nilais as $nilai)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $nilai->mahasiswa?->nim ?? '-' }}</td>
                <td>{{ $nilai->mahasiswa?->user?->name ?? '-' }}</td>
                <td>{{ $nilai->mahasiswa?->prodi?->nama_prodi ?? '-' }}</td>
                <td>{{ $nilai->kelas?->mataKuliah?->nama ?? '-' }}</td>
                <td>{{ $nilai->kelas?->nama ?? '-' }}</td>
                <td>{{ number_format($nilai->nilai_akhir, 2) }}</td>
                <td>{{ $nilai->grade }}</td>
                <td>{{ $nilai->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d F Y H:i:s') }}</p>
    </div>
</body>
</html>
