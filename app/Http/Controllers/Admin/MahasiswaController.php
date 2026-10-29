<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MahasiswaController extends Controller
{
    public function index(): View
    {
        $mahasiswas = Mahasiswa::with('prodi')->latest()->get();

        return view('admin.mahasiswa.index', compact('mahasiswas'));
    }

    public function create(): View
    {
        $prodis = Prodi::all();

        return view('admin.mahasiswa.create', compact('prodis'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'nama' => ['required', 'string', 'max:255'],
            'nim' => ['required', 'string', 'max:20'],
            'prodi_id' => ['required', 'exists:prodis,id'],
            'angkatan' => ['required', 'integer'],
        ]);
        Mahasiswa::create($validated);

        return redirect()->route('admin.mahasiswa.index')->with('success', 'Mahasiswa berhasil ditambah.');
    }

    public function edit(string $id): View
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $prodis = Prodi::all();

        return view('admin.mahasiswa.edit', compact('mahasiswa', 'prodis'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'nama' => ['required', 'string', 'max:255'],
            'nim' => ['required', 'string', 'max:20'],
            'prodi_id' => ['required', 'exists:prodis,id'],
            'angkatan' => ['required', 'integer'],
        ]);
        $mahasiswa->update($validated);

        return redirect()->route('admin.mahasiswa.index')->with('success', 'Mahasiswa berhasil diupdate.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $mahasiswa->delete();

        return redirect()->route('admin.mahasiswa.index')->with('success', 'Mahasiswa berhasil dihapus.');
    }
}
