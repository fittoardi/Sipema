<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Nilai;
use App\Models\Prodi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanController extends Controller
{
    public function index(Request $request): View
    {
        $query = Nilai::with(['kelas.mataKuliah', 'mahasiswa.prodi', 'kelas.dosen']);

        if ($request->filled('prodi_id')) {
            $query->whereHas('mahasiswa', function ($q) use ($request) {
                $q->where('prodi_id', $request->prodi_id);
            });
        }

        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        $nilais = $query->paginate(15)->withQueryString();
        $prodis = Prodi::all();
        $kelas = Kelas::all();

        return view('admin.laporan.index', compact('nilais', 'prodis', 'kelas'));
    }

    public function export(Request $request): StreamedResponse
    {
        $query = Nilai::with(['kelas.mataKuliah', 'mahasiswa.prodi', 'kelas.dosen']);

        if ($request->filled('prodi_id')) {
            $query->whereHas('mahasiswa', function ($q) use ($request) {
                $q->where('prodi_id', $request->prodi_id);
            });
        }

        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        $nilais = $query->get();

        $filename = 'laporan-nilai-'.date('Y-m-d').'.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $callback = function () use ($nilais) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['NIM', 'Nama Mahasiswa', 'Prodi', 'Mata Kuliah', 'Kelas', 'Nilai Akhir', 'Grade', 'Status']);

            foreach ($nilais as $nilai) {
                fputcsv($handle, [
                    $nilai->mahasiswa?->nim ?? '-',
                    $nilai->mahasiswa?->user?->name ?? '-',
                    $nilai->mahasiswa?->prodi?->nama_prodi ?? '-',
                    $nilai->kelas?->mataKuliah?->nama ?? '-',
                    $nilai->kelas?->nama ?? '-',
                    $nilai->nilai_akhir,
                    $nilai->grade,
                    $nilai->status,
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportExcel(Request $request)
    {
        $query = Nilai::with(['kelas.mataKuliah', 'mahasiswa.prodi', 'kelas.dosen']);

        if ($request->filled('prodi_id')) {
            $query->whereHas('mahasiswa', function ($q) use ($request) {
                $q->where('prodi_id', $request->prodi_id);
            });
        }

        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        $nilais = $query->get();

        return response()->streamDownload(function () use ($nilais) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['NIM', 'Nama Mahasiswa', 'Prodi', 'Mata Kuliah', 'Kelas', 'Kuis', 'Tugas', 'UTS', 'UAS', 'Nilai Akhir', 'Grade', 'Status']);

            foreach ($nilais as $nilai) {
                fputcsv($handle, [
                    $nilai->mahasiswa?->nim ?? '-',
                    $nilai->mahasiswa?->user?->name ?? '-',
                    $nilai->mahasiswa?->prodi?->nama_prodi ?? '-',
                    $nilai->kelas?->mataKuliah?->nama ?? '-',
                    $nilai->kelas?->nama ?? '-',
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
        }, 'laporan-nilai-'.date('Y-m-d').'.xls', [
            'Content-Type' => 'application/vnd.ms-excel',
        ]);
    }

    public function exportPdf(Request $request)
    {
        $query = Nilai::with(['kelas.mataKuliah', 'mahasiswa.prodi', 'kelas.dosen']);

        if ($request->filled('prodi_id')) {
            $query->whereHas('mahasiswa', function ($q) use ($request) {
                $q->where('prodi_id', $request->prodi_id);
            });
        }

        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        $nilais = $query->get();

        $prodis = Prodi::all();
        $kelas = Kelas::all();
        $tanggal = now()->format('d F Y');

        $pdf = Pdf::loadView('admin.laporan.pdf', compact('nilais', 'prodis', 'kelas', 'tanggal'));

        return $pdf->download('laporan-nilai-'.date('Y-m-d').'.pdf');
    }
}
