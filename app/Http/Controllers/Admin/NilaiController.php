<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\NilaiRequest;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\Nilai;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class NilaiController extends Controller
{
    public function index(): View
    {
        $nilais = Nilai::query()->with(['kelas.mataKuliah', 'kelas.dosen', 'mahasiswa'])->latest()->get();

        return view('admin.nilai.index', compact('nilais'));
    }

    public function create(): View
    {
        $kelas = Kelas::with(['mataKuliah', 'dosen'])->get();
        $mahasiswas = Mahasiswa::all();

        return view('admin.nilai.create', compact('kelas', 'mahasiswas'));
    }

    public function store(NilaiRequest $request): RedirectResponse
    {
        $nilai = new Nilai($request->validated());
        $nilai->recalculate()->save();

        return to_route('admin.nilai.index')->with('success', 'Nilai berhasil ditambah.');
    }

    public function edit(Nilai $nilai): View
    {
        $nilai->load(['kelas.mataKuliah', 'kelas.dosen', 'mahasiswa']);
        $kelas = Kelas::with(['mataKuliah', 'dosen'])->get();
        $mahasiswas = Mahasiswa::all();

        return view('admin.nilai.edit', compact('nilai', 'kelas', 'mahasiswas'));
    }

    public function update(NilaiRequest $request, Nilai $nilai): RedirectResponse
    {
        $nilai->fill($request->validated())->recalculate()->save();

        return to_route('admin.nilai.index')->with('success', 'Nilai berhasil diupdate.');
    }

    public function destroy(Nilai $nilai): RedirectResponse
    {
        $nilai->delete();

        return to_route('admin.nilai.index')->with('success', 'Nilai berhasil dihapus.');
    }
}
