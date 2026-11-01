<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\KelasRequest;
use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\MataKuliah;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class KelasController extends Controller
{
    public function index(): View
    {
        $kelas = Kelas::query()->with(['mataKuliah', 'dosen'])->latest()->get();

        return view('admin.kelas.index', compact('kelas'));
    }

    public function create(): View
    {
        $mataKuliahs = MataKuliah::all();
        $dosens = Dosen::all();

        return view('admin.kelas.create', compact('mataKuliahs', 'dosens'));
    }

    public function store(KelasRequest $request): RedirectResponse
    {
        Kelas::create($request->validated());

        return to_route('admin.kelas.index')->with('success', 'Kelas berhasil ditambah.');
    }

    public function edit(Kelas $kelas): View
    {
        $mataKuliahs = MataKuliah::all();
        $dosens = Dosen::all();

        return view('admin.kelas.edit', compact('kelas', 'mataKuliahs', 'dosens'));
    }

    public function update(KelasRequest $request, Kelas $kelas): RedirectResponse
    {
        $kelas->update($request->validated());

        return to_route('admin.kelas.index')->with('success', 'Kelas berhasil diupdate.');
    }

    public function destroy(Kelas $kelas): RedirectResponse
    {
        $kelas->delete();

        return to_route('admin.kelas.index')->with('success', 'Kelas berhasil dihapus.');
    }
}
