<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Kartu Rencana Studi (KRS) - {{ $mahasiswa->nim }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #222;
            line-height: 1.4;
            margin: 15px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #2d3748;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .header h1 {
            font-size: 14px;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header h2 {
            font-size: 13px;
            margin: 3px 0 0 0;
            font-weight: bold;
        }
        .header p {
            margin: 2px 0 0 0;
            font-size: 10px;
            color: #4a5568;
        }
        .info-table {
            width: 100%;
            margin-bottom: 15px;
        }
        .info-table td {
            padding: 2px 4px;
            font-size: 11px;
        }
        .info-table .label {
            font-weight: bold;
            width: 18%;
        }
        .info-table .separator {
            width: 2%;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .data-table th, .data-table td {
            border: 1px solid #718096;
            padding: 5px 6px;
            font-size: 10px;
        }
        .data-table th {
            background-color: #edf2f7;
            text-transform: uppercase;
            font-weight: bold;
            text-align: left;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .signature-table {
            width: 100%;
            margin-top: 25px;
        }
        .signature-table td {
            text-align: center;
            font-size: 10px;
            vertical-align: top;
            width: 50%;
        }
        .signature-space {
            height: 60px;
        }
        .footer-note {
            margin-top: 20px;
            font-size: 9px;
            color: #718096;
            border-top: 1px dashed #cbd5e0;
            padding-top: 6px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Sistem Informasi Akademik & E-Learning Hybrid (SIELMA)</h1>
        <h2>KARTU RENCANA STUDI (KRS)</h2>
        <p>Tahun Akademik: {{ $tahunAktif->tahun }} &bull; Semester: {{ $tahunAktif->semester->value }}</p>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">NIM</td>
            <td class="separator">:</td>
            <td><strong>{{ $mahasiswa->nim }}</strong></td>
            <td class="label">Program Studi</td>
            <td class="separator">:</td>
            <td>{{ $mahasiswa->programStudi->nama ?? $mahasiswa->programStudi->nama_prodi }}</td>
        </tr>
        <tr>
            <td class="label">Nama Mahasiswa</td>
            <td class="separator">:</td>
            <td><strong>{{ $mahasiswa->nama }}</strong></td>
            <td class="label">Dosen Pembimbing</td>
            <td class="separator">:</td>
            <td>{{ $mahasiswa->dosenWali->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Angkatan / Sem</td>
            <td class="separator">:</td>
            <td>{{ $mahasiswa->angkatan }} / Semester {{ $mahasiswa->semester }}</td>
            <td class="label">Status KRS</td>
            <td class="separator">:</td>
            <td><strong>{{ strtoupper($krs->status->value) }}</strong> (Total: {{ $krs->total_sks }} SKS)</td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 5%;">No</th>
                <th style="width: 15%;">Kode MK</th>
                <th style="width: 30%;">Mata Kuliah</th>
                <th class="text-center" style="width: 8%;">SKS</th>
                <th style="width: 10%;">Kelas</th>
                <th style="width: 17%;">Jadwal & Ruang</th>
                <th style="width: 15%;">Dosen Pengampu</th>
            </tr>
        </thead>
        <tbody>
            @forelse($krs->details as $i => $detail)
                @php $kls = $detail->kelas; @endphp
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $kls->mataKuliah->kode }}</td>
                    <td><strong>{{ $kls->mataKuliah->nama }}</strong></td>
                    <td class="text-center">{{ $kls->sks }}</td>
                    <td>{{ $kls->nama_kelas }}</td>
                    <td>{{ $kls->hari->value ?? $kls->hari }}, {{ $kls->jam_rentang }}<br><small>{{ $kls->ruangan->nama ?? 'Online' }}</small></td>
                    <td>{{ $kls->dosen->nama ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Belum ada mata kuliah yang diambil.</td>
                </tr>
            @endforelse
            <tr style="background-color: #f7fafc; font-weight: bold;">
                <td colspan="3" class="text-right">TOTAL SKS DIAMBIL :</td>
                <td class="text-center">{{ $krs->total_sks }}</td>
                <td colspan="3">SKS</td>
            </tr>
        </tbody>
    </table>

    <table class="signature-table">
        <tr>
            <td>
                Menyetujui,<br>
                Dosen Pembimbing Akademik
                <div class="signature-space"></div>
                <strong>{{ $mahasiswa->dosenWali->nama ?? '............................................' }}</strong><br>
                NIDN: {{ $mahasiswa->dosenWali->nidn ?? '..................................' }}
            </td>
            <td>
                Dicetak pada: {{ now()->translatedFormat('d F Y') }}<br>
                Mahasiswa Yang Bersangkutan
                <div class="signature-space"></div>
                <strong>{{ $mahasiswa->nama }}</strong><br>
                NIM: {{ $mahasiswa->nim }}
            </td>
        </tr>
    </table>

    <div class="footer-note">
        Dokumen ini diterbitkan secara sah melalui SIELMA. Status KRS: <strong>{{ strtoupper($krs->status->value) }}</strong>. Dicetak otomatis pada {{ now()->format('Y-m-d H:i:s') }}.
    </div>

</body>
</html>

