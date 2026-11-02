<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Concerns\StreamsCsv;
use App\Enums\StatusNilai;
use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
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

        $mahasiswa = Mahasiswa::with(['prodi.fakultas', 'user'])
            ->where('user_id', $user?->id)
            ->firstOrFail();

        $nilais = Nilai::with(['kelasMahasiswa.kelas.mataKuliah'])
            ->whereHas('kelasMahasiswa', fn ($q) => $q->where('mahasiswa_id', $mahasiswa->id))
            ->get();

        $jumlahNilai = $nilais->count();
        $rataNilai = $jumlahNilai > 0 ? (float) $nilais->avg('nilai_akhir') : null;

        $jumlahMataKuliah = MataKuliah::where('program_studi_id', $mahasiswa->program_studi_id)->count();
        $jumlahJadwal = Kelas::count();

        return view('dashboard.mahasiswa.index', [
            'mahasiswa' => $mahasiswa,
            'jumlahMataKuliah' => $jumlahMataKuliah,
            'jumlahJadwal' => $jumlahJadwal,
            'jumlahNilai' => $jumlahNilai,
            'rataNilai' => $rataNilai,
            'nilais' => $nilais,
        ]);
    }

    public function downloadNilai(Request $request): StreamedResponse
    {
        $user = $request->user();

        $mahasiswa = Mahasiswa::where('user_id', $user?->id)->firstOrFail();

        $nilais = Nilai::with(['kelasMahasiswa.kelas.mataKuliah'])
            ->whereHas('kelasMahasiswa', fn ($q) => $q->where('mahasiswa_id', $mahasiswa->id))
            ->get();

        $filename = 'nilai_'.$mahasiswa->nim.'.csv';

        $headers = [
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
