<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Nilai;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NilaiController extends Controller
{
    public function index(Request $request): View
    {
        $dosen = Dosen::where('user_id', $request->user()->id)->firstOrFail();
        $kelas = Kelas::query()
            ->where('dosen_id', $dosen->id)
            ->with(['mataKuliah', 'mahasiswas', 'nilais'])
            ->get();

        return view('dosen.nilai.index', [
            'kelas' => $kelas,
            'selectedKelas' => $request->integer('kelas_id'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kelas_id' => ['required', 'integer', 'exists:kelas,id'],
            'mahasiswa_id' => ['required', 'integer', 'exists:mahasiswas,id'],
            'kuis' => ['required', 'numeric', 'between:0,100'],
            'tugas' => ['required', 'numeric', 'between:0,100'],
            'uts' => ['required', 'numeric', 'between:0,100'],
            'uas' => ['required', 'numeric', 'between:0,100'],
        ]);

        $dosen = Dosen::where('user_id', $request->user()->id)->firstOrFail();
        $kelas = Kelas::whereKey($validated['kelas_id'])
            ->where('dosen_id', $dosen->id)
            ->firstOrFail();

        abort_unless($kelas->mahasiswas()->whereKey($validated['mahasiswa_id'])->exists(), 403);

        $nilai = Nilai::firstOrNew([
            'kelas_id' => $kelas->id,
            'mahasiswa_id' => $validated['mahasiswa_id'],
        ]);
        $nilai->fill($validated)->recalculate()->save();

        return back()->with('status', 'Nilai berhasil disimpan dan dihitung.');
    }
}
