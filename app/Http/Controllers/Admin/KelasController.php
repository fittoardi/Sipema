<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\MataKuliah;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KelasController extends Controller
{
    public function index(): View
    {
        $kelas = Kelas::with(['mataKuliah', 'dosen'])->latest()->get();

        return view('admin.kelas.index', compact('kelas'));
    }

    public function create(): View
    {
        $mataKuliahs = MataKuliah::all();
        $dosens = Dosen::all();

        return view('admin.kelas.create', compact('mataKuliahs', 'dosens'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'mata_kuliah_id' => ['required', 'exists:mata_kuliahs,id'],
            'dosen_id' => ['required', 'exists:dosens,id'],
            'nama' => ['required', 'string', 'max:255'],
            'tahun_akademik' => ['required', 'string', 'max:20'],
            'semester' => ['required', 'in:Ganjil,Genap'],
        ]);

        Kelas::create($validated);

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil ditambah.');
    }

    public function edit(string $id): View
    {
        $kelas = Kelas::findOrFail($id);
        $mataKuliahs = MataKuliah::all();
        $dosens = Dosen::all();

        return view('admin.kelas.edit', compact('kelas', 'mataKuliahs', 'dosens'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $kelas = Kelas::findOrFail($id);
        $validated = $request->validate([
            'mata_kuliah_id' => ['required', 'exists:mata_kuliahs,id'],
            'dosen_id' => ['required', 'exists:dosens,id'],
            'nama' => ['required', 'string', 'max:255'],
            'tahun_akademik' => ['required', 'string', 'max:20'],
            'semester' => ['required', 'in:Ganjil,Genap'],
        ]);

        $kelas->update($validated);

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil diupdate.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil dihapus.');
    }
}
