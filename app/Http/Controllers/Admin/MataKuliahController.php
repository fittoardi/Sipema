<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MataKuliahRequest;
use App\Models\MataKuliah;
use App\Models\Prodi;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class MataKuliahController extends Controller
{
    public function index(): View
    {
        $matakuliahs = MataKuliah::query()->with('prodi')->latest()->get();

        return view('admin.matakuliah.index', compact('matakuliahs'));
    }

    public function create(): View
    {
        $prodis = Prodi::all();

        return view('admin.matakuliah.create', compact('prodis'));
    }

    public function store(MataKuliahRequest $request): RedirectResponse
    {
        MataKuliah::create($request->validated());

        return to_route('admin.matakuliah.index')->with('success', 'Mata Kuliah berhasil ditambah.');
    }

    public function edit(MataKuliah $matakuliah): View
    {
        $prodis = Prodi::all();

        return view('admin.matakuliah.edit', compact('matakuliah', 'prodis'));
    }

    public function update(MataKuliahRequest $request, MataKuliah $matakuliah): RedirectResponse
    {
        $matakuliah->update($request->validated());

        return to_route('admin.matakuliah.index')->with('success', 'Mata Kuliah berhasil diupdate.');
    }

    public function destroy(MataKuliah $matakuliah): RedirectResponse
    {
        $matakuliah->delete();

        return to_route('admin.matakuliah.index')->with('success', 'Mata Kuliah berhasil dihapus.');
    }
}
