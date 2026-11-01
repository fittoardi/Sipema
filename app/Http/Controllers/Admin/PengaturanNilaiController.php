<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use App\Models\PengaturanNilai;
use App\Models\SkalaGrade;
use App\Services\NilaiCalculator;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PengaturanNilaiController extends Controller
{
    public function index(): View
    {
        $calculator = app(NilaiCalculator::class);
        $weights = $calculator->getWeights();
        $grades = $calculator->getGrades();

        return view('admin.pengaturan-nilai.index', compact('weights', 'grades'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'weights.kuis' => ['required', 'numeric', 'min:0', 'max:100'],
            'weights.tugas' => ['required', 'numeric', 'min:0', 'max:100'],
            'weights.uts' => ['required', 'numeric', 'min:0', 'max:100'],
            'weights.uas' => ['required', 'numeric', 'min:0', 'max:100'],
            'grades' => ['required', 'array'],
            'grades.*.grade' => ['required', 'string', 'max:5'],
            'grades.*.min' => ['required', 'numeric', 'min:0', 'max:100'],
            'grades.*.is_lulus' => ['nullable'],
        ]);

        $totalBobot = array_sum($validated['weights']);
        if (round($totalBobot, 2) !== 100.0) {
            return back()->withInput()->with('error', "Total bobot komponen harus tepat 100%. Saat ini: {$totalBobot}%.");
        }

        // Save weights
        foreach ($validated['weights'] as $komponen => $bobot) {
            PengaturanNilai::updateOrCreate(
                ['komponen' => $komponen],
                ['bobot' => $bobot]
            );
        }

        // Save grades
        $urutan = 1;
        foreach ($validated['grades'] as $item) {
            SkalaGrade::updateOrCreate(
                ['grade' => $item['grade']],
                [
                    'nilai_min' => $item['min'],
                    'is_lulus' => ! empty($item['is_lulus']),
                    'urutan' => $urutan++,
                ]
            );
        }

        // Clear cache
        app(NilaiCalculator::class)->clearCache();

        // Optional recalculate
        if ($request->boolean('recalculate_now')) {
            Nilai::chunk(100, function ($nilais): void {
                foreach ($nilais as $nilai) {
                    $nilai->recalculate()->save();
                }
            });

            return to_route('admin.pengaturan-nilai.index')
                ->with('success', 'Pengaturan nilai berhasil disimpan dan seluruh nilai mahasiswa telah dihitung ulang.');
        }

        return to_route('admin.pengaturan-nilai.index')
            ->with('success', 'Pengaturan bobot dan skala grade berhasil diperbarui.');
    }
}
