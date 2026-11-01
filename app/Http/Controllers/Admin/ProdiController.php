<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProdiRequest;
use App\Models\Prodi;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ProdiController extends Controller
{
    public function index(): View
    {
        $prodis = Prodi::query()->latest()->get();

        return view('admin.prodi.index', compact('prodis'));
    }

    public function create(): View
    {
        return view('admin.prodi.create');
    }

    public function store(ProdiRequest $request): RedirectResponse
    {
        Prodi::create($request->validated());

        return to_route('admin.prodi.index')
            ->with('success', 'Program studi berhasil ditambah.');
    }

    public function edit(Prodi $prodi): View
    {
        return view('admin.prodi.edit', compact('prodi'));
    }

    public function update(ProdiRequest $request, Prodi $prodi): RedirectResponse
    {
        $prodi->update($request->validated());

        return to_route('admin.prodi.index')
            ->with('success', 'Program studi berhasil diupdate.');
    }

    public function destroy(Prodi $prodi): RedirectResponse
    {
        $prodi->delete();

        return to_route('admin.prodi.index')
            ->with('success', 'Program studi berhasil dihapus.');
    }
}
