<?php

namespace App\Http\Controllers\Admin;

use App\Concerns\StreamsCsv;
use App\Enums\StatusNilai;
use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Nilai;
use App\Models\Prodi;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanController extends Controller
{
    use StreamsCsv;

    public function index(Request $request): View
    {
        $nilais = $this->buildLaporanQuery($request)->paginate(15)->withQueryString();
        $prodis = Prodi::all();
        $kelas = Kelas::all();

        return view('admin.laporan.index', compact('nilais', 'prodis', 'kelas'));
    }

    public function export(Request $request): StreamedResponse
    {
        $nilais = $this->buildLaporanQuery($request)->get();
        $filename = 'laporan-nilai-'.date('Y-m-d').'.csv';

        $headers = ['NIM', 'Nama Mahasiswa', 'Prodi', 'Mata Kuliah', 'Kelas', 'Nilai Akhir', 'Grade', 'Status'];

        return $this->streamCsv($filename, $headers, $nilais, fn (Nilai $nilai): array => [
            $nilai->mahasiswa?->nim ?? '-',
            $nilai->mahasiswa?->nama ?? $nilai->mahasiswa?->user?->name ?? '-',
            $nilai->mahasiswa?->prodi?->nama_prodi ?? '-',
            $nilai->kelas?->mataKuliah?->nama ?? '-',
            $nilai->kelas?->nama ?? '-',
            $nilai->nilai_akhir,
            $nilai->grade,
            $nilai->status instanceof StatusNilai ? $nilai->status->label() : (string) ($nilai->status->value ?? $nilai->status ?? '-'),
        ]);
    }

    public function exportExcel(Request $request): StreamedResponse
    {
        $nilais = $this->buildLaporanQuery($request)->get();
        $filename = 'laporan-nilai-'.date('Y-m-d').'.xls';

        $headers = ['NIM', 'Nama Mahasiswa', 'Prodi', 'Mata Kuliah', 'Kelas', 'Kuis', 'Tugas', 'UTS', 'UAS', 'Nilai Akhir', 'Grade', 'Status'];

        return response()->streamDownload(function () use ($headers, $nilais): void {
            $handle = fopen('php://output', 'w');

            if ($handle === false) {
                return;
            }

            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, $headers);

            foreach ($nilais as $nilai) {
                fputcsv($handle, [
                    $nilai->mahasiswa?->nim ?? '-',
                    $nilai->mahasiswa?->nama ?? $nilai->mahasiswa?->user?->name ?? '-',
                    $nilai->mahasiswa?->prodi?->nama_prodi ?? '-',
                    $nilai->kelas?->mataKuliah?->nama ?? '-',
                    $nilai->kelas?->nama ?? '-',
                    $nilai->kuis,
                    $nilai->tugas,
                    $nilai->uts,
                    $nilai->uas,
                    $nilai->nilai_akhir,
                    $nilai->grade,
                    $nilai->status instanceof StatusNilai ? $nilai->status->label() : (string) ($nilai->status->value ?? $nilai->status ?? '-'),
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'application/vnd.ms-excel',
        ]);
    }

    public function exportPdf(Request $request): Response
    {
        $nilais = $this->buildLaporanQuery($request)->get();
        $prodis = Prodi::all();
        $kelas = Kelas::all();
        $tanggal = now()->format('d F Y');

        $options = new Options;
        $options->setIsRemoteEnabled(true);
        $dompdf = new Dompdf($options);
        $html = view('admin.laporan.pdf', compact('nilais', 'prodis', 'kelas', 'tanggal'))->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="laporan-nilai-'.date('Y-m-d').'.pdf"',
        ]);
    }

    /**
     * @return Builder<Nilai>
     */
    private function buildLaporanQuery(Request $request): Builder
    {
        return Nilai::query()
            ->with(['kelas.mataKuliah', 'mahasiswa.prodi', 'kelas.dosen'])
            ->when($request->filled('prodi_id'), function (Builder $query) use ($request): void {
                $query->whereHas('mahasiswa', function (Builder $q) use ($request): void {
                    $q->where('program_studi_id', $request->integer('prodi_id'));
                });
            })
            ->when($request->filled('kelas_id'), function (Builder $query) use ($request): void {
                $query->whereHas('kelas', function (Builder $q) use ($request): void {
                    $q->where('id', $request->integer('kelas_id'));
                });
            });
    }
}
