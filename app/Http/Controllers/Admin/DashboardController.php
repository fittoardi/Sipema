<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Nilai;
use App\Models\Prodi;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalMahasiswa = Mahasiswa::count();
        $totalDosen = Dosen::count();
        $prodis = Prodi::withCount('mahasiswas')->get();

        $lastSemester = Nilai::selectRaw('YEAR(created_at) as tahun, MIN(semester) as semester')
            ->groupBy('tahun')
            ->orderByRaw('MIN(created_at) DESC')
            ->limit(6)
            ->get();

        $distribusiGrade = Nilai::selectRaw(
            'grade, COUNT(*) as jumlah, ROUND(AVG(nilai_akhir), 2) as rata_rata'
        )
            ->groupBy('grade')
            ->orderByRaw("FIELD(grade, 'A', 'B', 'C', 'D', 'E', 'F', 'N')")
            ->get();

        $grafikNorm = Nilai::selectRaw(
            'YEAR(created_at) as tahun, semester, COUNT(*) as jumlah'
        )
            ->groupBy('tahun', 'semester')
            ->orderBy('tahun')
            ->orderBy('semester')
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
