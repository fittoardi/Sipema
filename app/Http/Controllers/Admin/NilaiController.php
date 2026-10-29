<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\Nilai;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NilaiController extends Controller
{
    public function index(): View
    {
        $nilais = Nilai::with(['kelas.mataKuliah', 'kelas.dosen', 'mahasiswa'])->latest()->get();

        return view('admin.nilai.index', compact('nilais'));
    }

    public function create(): View
    {
        $kelas = Kelas::with(['mataKuliah', 'dosen'])->get();
        $mahasiswas = Mahasiswa::all();

        return view('admin.nilai.create', compact('kelas', 'mahasiswas'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kelas_id' => ['required', 'exists:kelas,id'],
            'mahasiswa_id' => ['required', 'exists:mahasiswas,id'],
            'kuis' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'tugas' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'uts' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'uas' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        $nilai = Nilai::create($validated);
        $nilai->recalculate();
        $nilai->save();

        return redirect()->route('admin.nilai.index')->with('success', 'Nilai berhasil ditambah.');
    }

    public function edit(string $id): View
    {
        $nilai = Nilai::with(['kelas.mataKuliah', 'kelas.dosen', 'mahasiswa'])->findOrFail($id);
        $kelas = Kelas::with(['mataKuliah', 'dosen'])->get();
        $mahasiswas = Mahasiswa::all();

        return view('admin.nilai.edit', compact('nilai', 'kelas', 'mahasiswas'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $nilai = Nilai::findOrFail($id);
        $validated = $request->validate([
            'kelas_id' => ['required', 'exists:kelas,id'],
            'mahasiswa_id' => ['required', 'exists:mahasiswas,id'],
            'kuis' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'tugas' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'uts' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'uas' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        $nilai->update($validated);
        $nilai->recalculate();
        $nilai->save();

        return redirect()->route('admin.nilai.index')->with('success', 'Nilai berhasil diupdate.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $nilai = Nilai::findOrFail($id);
        $nilai->delete();

        return redirect()->route('admin.nilai.index')->with('success', 'Nilai berhasil dihapus.');
    }
}
