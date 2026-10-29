<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataKuliah;
use App\Models\Prodi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MataKuliahController extends Controller
{
    public function index(): View
    {
        $matakuliahs = MataKuliah::with('prodi')->latest()->get();

        return view('admin.matakuliah.index', compact('matakuliahs'));
    }

    public function create(): View
    {
        $prodis = Prodi::all();

        return view('admin.matakuliah.create', compact('prodis'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kode' => ['required', 'string', 'max:20', 'unique:mata_kuliahs,kode'],
            'nama' => ['required', 'string', 'max:255'],
            'sks' => ['required', 'integer', 'min:1'],
            'prodi_id' => ['required', 'exists:prodis,id'],
        ]);
        MataKuliah::create($validated);

        return redirect()->route('admin.matakuliah.index')->with('success', 'Mata Kuliah berhasil ditambah.');
    }

    public function edit(string $id): View
    {
        $matakuliah = MataKuliah::findOrFail($id);
        $prodis = Prodi::all();

        return view('admin.matakuliah.edit', compact('matakuliah', 'prodis'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $matakuliah = MataKuliah::findOrFail($id);
        $validated = $request->validate([
            'kode' => ['required', 'string', 'max:20', 'unique:mata_kuliahs,kode,'.$matakuliah->id],
            'nama' => ['required', 'string', 'max:255'],
            'sks' => ['required', 'integer', 'min:1'],
            'prodi_id' => ['required', 'exists:prodis,id'],
        ]);
        $matakuliah->update($validated);

        return redirect()->route('admin.matakuliah.index')->with('success', 'Mata Kuliah berhasil diupdate.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $matakuliah = MataKuliah::findOrFail($id);
        $matakuliah->delete();

        return redirect()->route('admin.matakuliah.index')->with('success', 'Mata Kuliah berhasil dihapus.');
    }
}
