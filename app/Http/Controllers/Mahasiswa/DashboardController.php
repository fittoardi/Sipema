<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Nilai;

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

        ]);
    }
}
