<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Nilai;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $mahasiswa = Mahasiswa::with('prodi')
            ->where('user_id', $user->id)
            ->firstOrFail();

        return view('dashboard.mahasiswa.index', [

            'mahasiswa' => $mahasiswa,

            'jumlahMataKuliah' => MataKuliah::where(
                'prodi_id',
                $mahasiswa->prodi_id
            )->count(),

            'jumlahJadwal' => Jadwal::count(),

            'jumlahNilai' => Nilai::where(
                'mahasiswa_id',
                $mahasiswa->id
            )->count(),

            'rataNilai' => Nilai::where(
                'mahasiswa_id',
                $mahasiswa->id
            )->avg('nilai_akhir'),

            'nilais' => Nilai::with('kelas.mataKuliah')
                ->where('mahasiswa_id', $mahasiswa->id)
                ->get(),

        ]);
    }

    public function downloadNilai(): StreamedResponse
    {
        $user = auth()->user();

        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();

        $nilais = Nilai::with('kelas.mataKuliah')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->get();

        $filename = 'nilai_'.$mahasiswa->nim.'.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $callback = function () use ($nilais) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM for Excel compatibility
            fwrite($handle, "\xEF\xBB\xBF");

            // Header row
            fputcsv($handle, [
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
