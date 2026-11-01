<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MahasiswaRequest;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class MahasiswaController extends Controller
{
    public function index(): View
    {
        $mahasiswas = Mahasiswa::query()->with('prodi')->latest()->get();

        return view('admin.mahasiswa.index', compact('mahasiswas'));
    }

    public function create(): View
    {
        $prodis = Prodi::all();
        $users = User::query()->where('role', 'mahasiswa')->get();

        return view('admin.mahasiswa.create', compact('prodis', 'users'));
    }

    public function store(MahasiswaRequest $request): RedirectResponse
    {
        Mahasiswa::create($request->validated());

        return to_route('admin.mahasiswa.index')->with('success', 'Mahasiswa berhasil ditambah.');
    }

    public function edit(Mahasiswa $mahasiswa): View
    {
        $prodis = Prodi::all();
        $users = User::query()->where('role', 'mahasiswa')->get();

        return view('admin.mahasiswa.edit', compact('mahasiswa', 'prodis', 'users'));
    }

    public function update(MahasiswaRequest $request, Mahasiswa $mahasiswa): RedirectResponse
    {
        $mahasiswa->update($request->validated());

        return to_route('admin.mahasiswa.index')->with('success', 'Mahasiswa berhasil diupdate.');
    }

    public function destroy(Mahasiswa $mahasiswa): RedirectResponse
    {
        $mahasiswa->delete();

        return to_route('admin.mahasiswa.index')->with('success', 'Mahasiswa berhasil dihapus.');
    }
}
