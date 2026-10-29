<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prodi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProdiController extends Controller
{
    public function index(): View
    {
        $prodis = Prodi::latest()->get();

        return view('admin.prodi.index', compact('prodis'));
    }

    public function create(): View
    {
        return view('admin.prodi.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kode_prodi' => ['required', 'string', 'max:10', 'unique:prodis'],
            'nama_prodi' => ['required', 'string', 'max:100'],
        ]);

        Prodi::create($validated);

        return redirect()->route('admin.prodi.index')
            ->with('success', 'Program studi berhasil ditambah.');
    }

    public function edit(string $id): View
    {
        $prodi = Prodi::findOrFail($id);

        return view('admin.prodi.edit', compact('prodi'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $prodi = Prodi::findOrFail($id);

        $validated = $request->validate([
            'kode_prodi' => ['required', 'string', 'max:10', 'unique:prodis,kode_prodi,'.$prodi->id],
            'nama_prodi' => ['required', 'string', 'max:100'],
        ]);

        $prodi->update($validated);

        return redirect()->route('admin.prodi.index')
            ->with('success', 'Program studi berhasil diupdate.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $prodi = Prodi::findOrFail($id);

        $prodi->delete();

        return redirect()->route('admin.prodi.index')
            ->with('success', 'Program studi berhasil dihapus.');
    }
}
