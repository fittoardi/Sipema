<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Nilai;
use App\Models\Prodi;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalMahasiswa = Mahasiswa::query()->count();
        $totalDosen = Dosen::query()->count();
        $prodis = Prodi::query()->withCount('mahasiswas')->get();

        $lastSemester = Nilai::query()
            ->join('kelas_mahasiswa', 'nilai.kelas_mahasiswa_id', '=', 'kelas_mahasiswa.id')
            ->join('kelas', 'kelas_mahasiswa.kelas_id', '=', 'kelas.id')
            ->join('tahun_akademik', 'kelas.tahun_akademik_id', '=', 'tahun_akademik.id')
            ->selectRaw('tahun_akademik.tahun as tahun, tahun_akademik.semester as semester')
            ->distinct()
            ->orderByDesc('tahun_akademik.tahun')
            ->limit(6)
            ->get();

        $order = ['A' => 1, 'B' => 2, 'C' => 3, 'D' => 4, 'E' => 5];
        $distribusiGrade = Nilai::query()
            ->selectRaw('grade, COUNT(*) as jumlah, ROUND(AVG(nilai_akhir), 2) as rata_rata')
            ->whereNotNull('grade')
            ->groupBy('grade')
            ->get()
            ->sortBy(fn ($item): int => $order[$item->grade] ?? 99)
            ->values();

        $grafikNorm = Nilai::query()
            ->join('kelas_mahasiswa', 'nilai.kelas_mahasiswa_id', '=', 'kelas_mahasiswa.id')
            ->join('kelas', 'kelas_mahasiswa.kelas_id', '=', 'kelas.id')
            ->join('tahun_akademik', 'kelas.tahun_akademik_id', '=', 'tahun_akademik.id')
            ->selectRaw('tahun_akademik.tahun as tahun, tahun_akademik.semester as semester, COUNT(nilai.id) as jumlah')
            ->groupBy('tahun_akademik.tahun', 'tahun_akademik.semester')
            ->orderBy('tahun_akademik.tahun')
            ->orderBy('tahun_akademik.semester')
            ->get();

        return view('admin.dashboard.index', compact(
            'totalMahasiswa',
            'totalDosen',
            'prodis',
            'distribusiGrade',
            'grafikNorm',
            'lastSemester'
        ));
    }
}
