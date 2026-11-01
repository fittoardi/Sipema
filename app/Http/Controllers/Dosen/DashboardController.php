<?php

namespace App\Http\Controllers\Dosen;

use App\Concerns\StreamsCsv;
use App\Enums\Hari;
use App\Enums\StatusNilai;
use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Nilai;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
    use StreamsCsv;

    public function index(Request $request): View
    {
        $user = $request->user();
        $dosen = Dosen::where('user_id', $user?->id)->firstOrFail();

        $kelas = $dosen->kelas()
            ->with(['mataKuliah.programStudi', 'ruangan'])
            ->withCount('kelasMahasiswa')
            ->get();

        $totalMahasiswa = (int) $kelas->sum('kelas_mahasiswa_count');
        $totalMataKuliah = $kelas->unique('mata_kuliah_id')->count();
        $totalJadwal = $kelas->count();

        $ringkasanNilai = Nilai::whereHas('kelasMahasiswa', fn ($q) => $q->whereIn('kelas_id', $kelas->pluck('id')))
            ->avg('nilai_akhir');

        return view('dashboard.dosen.index', [
            'dosen' => $dosen,
            'kelas' => $kelas,
            'totalMahasiswa' => $totalMahasiswa,
            'totalMataKuliah' => $totalMataKuliah,
            'totalJadwal' => $totalJadwal,
            'ringkasanNilai' => number_format((float) ($ringkasanNilai ?? 0), 2),
        ]);
    }

    public function mataKuliah(Request $request): View
    {
        $user = $request->user();
        $dosen = Dosen::where('user_id', $user?->id)->firstOrFail();

        $kelas = $dosen->kelas()
            ->with(['mataKuliah.programStudi', 'ruangan'])
            ->withCount('kelasMahasiswa')
            ->get();

        return view('dashboard.dosen.matakuliah', [
            'dosen' => $dosen,
            'kelas' => $kelas,
        ]);
    }

    public function jadwal(Request $request): View
    {
        $user = $request->user();
        $dosen = Dosen::where('user_id', $user?->id)->firstOrFail();

        $dayOrder = [
            'Senin' => 1,
            'Selasa' => 2,
            'Rabu' => 3,
            'Kamis' => 4,
            'Jumat' => 5,
            'Sabtu' => 6,
            'Minggu' => 7,
        ];

        $jadwals = $dosen->kelas()
            ->with(['mataKuliah', 'ruangan'])
            ->get()
            ->map(function ($k) {
                return (object) [
                    'id' => $k->id,
                    'kelas_id' => $k->id,
                    'kelas' => $k,
                    'mataKuliah' => $k->mataKuliah,
                    'hari' => $k->hari instanceof Hari ? $k->hari->value : (string) ($k->hari ?? 'Senin'),
                    'jam_mulai' => (string) ($k->jam_mulai ?? '08:00:00'),
                    'jam_selesai' => (string) ($k->jam_selesai ?? '10:00:00'),
                    'ruangan' => $k->ruangan?->nama ?? 'Ruang Kelas',
                ];
            })
            ->sortBy(fn ($j) => sprintf('%02d-%s', $dayOrder[$j->hari] ?? 99, $j->jam_mulai))
            ->values();

        return view('dashboard.dosen.jadwal', [
            'dosen' => $dosen,
            'jadwals' => $jadwals,
        ]);
    }

    public function downloadNilai(Request $request): StreamedResponse
    {
        $user = $request->user();
        $dosen = Dosen::where('user_id', $user?->id)->firstOrFail();

        $kelasIds = Kelas::where('dosen_id', $dosen->id)->pluck('id');

        $nilais = Nilai::with(['kelasMahasiswa.kelas.mataKuliah', 'kelasMahasiswa.mahasiswa.user'])
            ->whereHas('kelasMahasiswa', fn ($q) => $q->whereIn('kelas_id', $kelasIds))
            ->get();

        $filename = 'nilai_dosen_'.$dosen->nidn.'.csv';

        $headers = [
            'NIM',
            'Nama Mahasiswa',
            'Mata Kuliah',
            'Kuis',
            'Tugas',
            'UTS',
            'UAS',
            'Nilai Akhir',
            'Grade',
            'Status',
        ];

        return $this->streamCsv($filename, $headers, $nilais, fn (Nilai $nilai): array => [
            $nilai->mahasiswa?->nim ?? '-',
            $nilai->mahasiswa?->user?->name ?? $nilai->mahasiswa?->nama ?? '-',
            $nilai->kelas?->mataKuliah?->nama ?? '-',
            number_format((float) $nilai->kuis, 2, '.', ''),
            number_format((float) $nilai->tugas, 2, '.', ''),
            number_format((float) $nilai->uts, 2, '.', ''),
            number_format((float) $nilai->uas, 2, '.', ''),
            number_format((float) $nilai->nilai_akhir, 2, '.', ''),
            $nilai->grade,
            $nilai->status instanceof StatusNilai ? $nilai->status->label() : (string) ($nilai->status ?? '-'),
        ]);
    }
}
