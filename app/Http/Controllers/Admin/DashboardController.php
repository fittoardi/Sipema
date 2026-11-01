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
            ->join('kelas', 'nilais.kelas_id', '=', 'kelas.id')
            ->selectRaw('kelas.tahun_akademik as tahun, kelas.semester')
            ->distinct()
            ->orderByDesc('kelas.tahun_akademik')
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
            ->join('kelas', 'nilais.kelas_id', '=', 'kelas.id')
            ->selectRaw('kelas.tahun_akademik as tahun, kelas.semester, COUNT(nilais.id) as jumlah')
            ->groupBy('kelas.tahun_akademik', 'kelas.semester')
            ->orderBy('kelas.tahun_akademik')
            ->orderBy('kelas.semester')
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
