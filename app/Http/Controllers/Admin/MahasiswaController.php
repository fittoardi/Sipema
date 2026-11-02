<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MahasiswaRequest;
use App\Models\Fakultas;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index(Request $request): View
    {
        $fakultasId = $request->filled('fakultas_id') ? (int) $request->input('fakultas_id') : null;
        $prodiId = $request->filled('prodi_id') ? (int) $request->input('prodi_id') : null;
        $search = $request->input('search');

        $mahasiswas = Mahasiswa::query()
            ->with(['prodi.fakultas', 'user', 'dosenWali'])
            ->filterByFakultas($fakultasId)
            ->filterByProdi($prodiId)
            ->search($search)
            ->latest()
            ->get();

        $fakultasList = Fakultas::with(['programStudi' => fn ($q) => $q->orderBy('nama')])
            ->orderBy('nama')
            ->get();

        $prodisList = Prodi::with('fakultas')
            ->when($fakultasId, fn ($q) => $q->where('fakultas_id', $fakultasId))
            ->orderBy('nama')
            ->get();

        $totalMahasiswa = Mahasiswa::count();

        return view('admin.mahasiswa.index', compact(
            'mahasiswas',
            'fakultasList',
            'prodisList',
            'fakultasId',
            'prodiId',
            'search',
            'totalMahasiswa'
        ));
    }

    public function create(): View
    {
        $fakultas = Fakultas::with(['programStudi' => fn ($q) => $q->orderBy('nama')])->orderBy('nama')->get();
        $prodis = Prodi::with('fakultas')->orderBy('nama')->get();
        $users = User::query()->where('role', 'mahasiswa')->orderBy('name')->get();

        return view('admin.mahasiswa.create', compact('fakultas', 'prodis', 'users'));
    }

    public function store(MahasiswaRequest $request): RedirectResponse
    {
        Mahasiswa::create($request->validated());

        return to_route('admin.mahasiswa.index')->with('success', 'Mahasiswa berhasil ditambah.');
    }

    public function edit(Mahasiswa $mahasiswa): View
    {
        $mahasiswa->loadMissing(['prodi.fakultas']);
        $fakultas = Fakultas::with(['programStudi' => fn ($q) => $q->orderBy('nama')])->orderBy('nama')->get();
        $prodis = Prodi::with('fakultas')->orderBy('nama')->get();
        $users = User::query()->where('role', 'mahasiswa')->orderBy('name')->get();

        return view('admin.mahasiswa.edit', compact('mahasiswa', 'fakultas', 'prodis', 'users'));
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
