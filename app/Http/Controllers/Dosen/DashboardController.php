<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Nilai;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $dosen = Dosen::where('user_id', $user->id)->firstOrFail();

        $kelas = $dosen->kelas()->withCount('mahasiswas')->get();

        $totalMahasiswa = $kelas->sum('mahasiswas_count');
        $totalMataKuliah = $kelas->unique('mata_kuliah_id')->count();
        $totalJadwal = $kelas->count();

        $ringkasanNilai = Nilai::whereIn('kelas_id', $dosen->kelas->pluck('id'))
            ->avg('nilai_akhir');

        return view('dashboard.dosen.index', [
            'kelas' => $kelas,
            'totalMahasiswa' => $totalMahasiswa,
            'totalMataKuliah' => $totalMataKuliah,
            'totalJadwal' => $totalJadwal,
            'ringkasanNilai' => number_format($ringkasanNilai ?? 0, 2),
        ]);
    }

    public function downloadNilai(): StreamedResponse
    {
        $user = auth()->user();

        $dosen = Dosen::where('user_id', $user->id)->firstOrFail();

        $kelasIds = Kelas::where('dosen_id', $dosen->id)->pluck('id');

        $nilais = Nilai::with(['kelas.mataKuliah', 'mahasiswa'])
            ->whereIn('kelas_id', $kelasIds)
            ->get();

        $filename = 'nilai_dosen_'.$dosen->nidn.'.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $callback = function () use ($nilais) {
            $handle = fopen('php://output', 'w');

            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
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
            ]);

            foreach ($nilais as $nilai) {
                fputcsv($handle, [
                    $nilai->mahasiswa?->nim ?? '-',
                    $nilai->mahasiswa?->user?->name ?? '-',
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

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
