<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Concerns\StreamsCsv;
use App\Http\Controllers\Controller;
use App\Models\Jadwal;
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

        $mahasiswa = Mahasiswa::with('prodi')
            ->where('user_id', $user?->id)
            ->firstOrFail();

        $nilais = Nilai::with('kelas.mataKuliah')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->get();

        $jumlahNilai = $nilais->count();
        $rataNilai = $jumlahNilai > 0 ? (float) $nilais->avg('nilai_akhir') : null;

        $jumlahMataKuliah = MataKuliah::where('prodi_id', $mahasiswa->prodi_id)->count();
        $jumlahJadwal = Jadwal::count();

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

        $nilais = Nilai::with('kelas.mataKuliah')
            ->where('mahasiswa_id', $mahasiswa->id)
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
