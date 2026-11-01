<?php

namespace App\Http\Controllers\Dosen;

use App\Concerns\StreamsCsv;
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

        $kelas = $dosen->kelas()->withCount('mahasiswas')->get();

        $totalMahasiswa = (int) $kelas->sum('mahasiswas_count');
        $totalMataKuliah = $kelas->unique('mata_kuliah_id')->count();
        $totalJadwal = $kelas->count();

        $ringkasanNilai = Nilai::whereIn('kelas_id', $kelas->pluck('id'))
            ->avg('nilai_akhir');

        return view('dashboard.dosen.index', [
            'kelas' => $kelas,
            'totalMahasiswa' => $totalMahasiswa,
            'totalMataKuliah' => $totalMataKuliah,
            'totalJadwal' => $totalJadwal,
            'ringkasanNilai' => number_format((float) ($ringkasanNilai ?? 0), 2),
        ]);
    }

    public function downloadNilai(Request $request): StreamedResponse
    {
        $user = $request->user();
        $dosen = Dosen::where('user_id', $user?->id)->firstOrFail();

        $kelasIds = Kelas::where('dosen_id', $dosen->id)->pluck('id');

        $nilais = Nilai::with(['kelas.mataKuliah', 'mahasiswa.user'])
            ->whereIn('kelas_id', $kelasIds)
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
            $nilai->kuis,
            $nilai->tugas,
            $nilai->uts,
            $nilai->uas,
            $nilai->nilai_akhir,
            $nilai->grade,
            $nilai->status,
        ]);
    }
}
