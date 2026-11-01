<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dosen\StoreNilaiRequest;
use App\Models\Dosen;
use App\Models\Kelas;
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
        $kelas = Kelas::query()
            ->where('dosen_id', $dosen->id)
            ->with(['mataKuliah', 'mahasiswas', 'nilais'])
            ->get();

        return view('dosen.nilai.index', [
            'kelas' => $kelas,
            'selectedKelas' => $request->integer('kelas_id'),
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

        $nilai = Nilai::firstOrNew([
            'kelas_id' => $kelas->id,
            'mahasiswa_id' => $validated['mahasiswa_id'],
        ]);
        $nilai->fill($validated)->recalculate()->save();

        return back()->with('status', 'Nilai berhasil disimpan dan dihitung.');
    }
}
