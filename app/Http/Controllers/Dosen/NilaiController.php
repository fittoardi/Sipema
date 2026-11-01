<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dosen\StoreNilaiRequest;
use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\KelasMahasiswa;
use App\Models\Nilai;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $dosen = Dosen::where('user_id', $user?->id)->firstOrFail();

        $selectedKelas = $request->integer('kelas_id');

        $allKelas = Kelas::query()
            ->where('dosen_id', $dosen->id)
            ->with(['mataKuliah', 'mahasiswas.user', 'nilais'])
            ->get();

        $kelas = $selectedKelas > 0
            ? $allKelas->where('id', $selectedKelas)
            : $allKelas;

        return view('dashboard.dosen.nilai', [
            'dosen' => $dosen,
            'allKelas' => $allKelas,
            'kelas' => $kelas,
            'selectedKelas' => $selectedKelas,
        ]);
    }

    public function store(StoreNilaiRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $user = $request->user();
        $dosen = Dosen::where('user_id', $user?->id)->firstOrFail();

        $kelas = Kelas::whereKey($validated['kelas_id'])
            ->where('dosen_id', $dosen->id)
            ->firstOrFail();

        abort_unless($kelas->mahasiswas()->whereKey($validated['mahasiswa_id'])->exists(), 403);

        $km = KelasMahasiswa::firstOrCreate([
            'kelas_id' => $kelas->id,
            'mahasiswa_id' => $validated['mahasiswa_id'],
        ]);

        $nilai = Nilai::firstOrNew([
            'kelas_mahasiswa_id' => $km->id,
        ]);
        $nilai->fill($validated)->recalculate()->save();

        return back()->with('status', 'Nilai berhasil disimpan dan dihitung otomatis.');
    }
}
