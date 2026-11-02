<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FakultasRequest;
use App\Models\Fakultas;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class FakultasController extends Controller
{
    public function index(): View
    {
        $fakultas = Fakultas::query()
            ->withCount('programStudi')
            ->latest()
            ->get();

        return view('admin.fakultas.index', compact('fakultas'));
    }

    public function create(): View
    {
        return view('admin.fakultas.create');
    }

    public function store(FakultasRequest $request): RedirectResponse
    {
        Fakultas::create($request->validated());

        return to_route('admin.fakultas.index')
            ->with('success', 'Fakultas berhasil ditambahkan.');
    }

    public function edit(Fakultas $fakultas): View
    {
        return view('admin.fakultas.edit', compact('fakultas'));
    }

    public function update(FakultasRequest $request, Fakultas $fakultas): RedirectResponse
    {
        $fakultas->update($request->validated());

        return to_route('admin.fakultas.index')
            ->with('success', 'Fakultas berhasil diperbarui.');
    }

    public function destroy(Fakultas $fakultas): RedirectResponse
    {
        $fakultas->delete();

        return to_route('admin.fakultas.index')
            ->with('success', 'Fakultas berhasil dihapus.');
    }
}

