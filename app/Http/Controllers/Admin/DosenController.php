<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Prodi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DosenController extends Controller
{
    public function index(): View
    {
        $dosens = Dosen::with('prodi')->latest()->get();

        return view('admin.dosen.index', compact('dosens'));
    }

    public function create(): View
    {
        $prodis = Prodi::all();

        return view('admin.dosen.create', compact('prodis'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'nama' => ['required', 'string', 'max:255'],
            'nidn' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:255'],
            'nip' => ['required', 'string', 'max:30'],
            'prodi_id' => ['required', 'exists:prodis,id'],
        ]);
        Dosen::create($validated);

        return redirect()->route('admin.dosen.index')->with('success', 'Dosen berhasil ditambah.');
    }

    public function edit(string $id): View
    {
        $dosen = Dosen::findOrFail($id);
        $prodis = Prodi::all();

        return view('admin.dosen.edit', compact('dosen', 'prodis'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $dosen = Dosen::findOrFail($id);
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'nama' => ['required', 'string', 'max:255'],
            'nidn' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:255'],
            'nip' => ['required', 'string', 'max:30'],
            'prodi_id' => ['required', 'exists:prodis,id'],
        ]);
        $dosen->update($validated);

        return redirect()->route('admin.dosen.index')->with('success', 'Dosen berhasil diupdate.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $dosen = Dosen::findOrFail($id);
        $dosen->delete();

        return redirect()->route('admin.dosen.index')->with('success', 'Dosen berhasil dihapus.');
    }
}
