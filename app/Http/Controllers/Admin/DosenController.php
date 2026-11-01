<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DosenRequest;
use App\Models\Dosen;
use App\Models\Prodi;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class DosenController extends Controller
{
    public function index(): View
    {
        $dosens = Dosen::query()->with('prodi')->latest()->get();

        return view('admin.dosen.index', compact('dosens'));
    }

    public function create(): View
    {
        $prodis = Prodi::all();

        return view('admin.dosen.create', compact('prodis'));
    }

    public function store(DosenRequest $request): RedirectResponse
    {
        Dosen::create($request->validated());

        return to_route('admin.dosen.index')->with('success', 'Dosen berhasil ditambah.');
    }

    public function edit(Dosen $dosen): View
    {
        $prodis = Prodi::all();

        return view('admin.dosen.edit', compact('dosen', 'prodis'));
    }

    public function update(DosenRequest $request, Dosen $dosen): RedirectResponse
    {
        $dosen->update($request->validated());

        return to_route('admin.dosen.index')->with('success', 'Dosen berhasil diupdate.');
    }

    public function destroy(Dosen $dosen): RedirectResponse
    {
        $dosen->delete();

        return to_route('admin.dosen.index')->with('success', 'Dosen berhasil dihapus.');
    }
}
