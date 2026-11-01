<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\KelasMahasiswa;
use App\Models\PengumpulanTugas;
use App\Models\Pertemuan;
use App\Models\Tugas;
use App\Services\SinkronisasiTugasService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TugasController extends Controller
{
    protected function authorizeDosenForKelas(Request $request, Kelas $kelas): Dosen
    {
        $dosen = Dosen::where('user_id', $request->user()->id)->firstOrFail();
        if ($kelas->dosen_id !== $dosen->id) {
            abort(403, 'Anda bukan dosen pengampu untuk kelas ini.');
        }

        return $dosen;
    }

    public function show(Request $request, Kelas $kela, Pertemuan $pertemuan, Tugas $tugas): View
    {
        $this->authorizeDosenForKelas($request, $kela);

        if ($pertemuan->kelas_id !== $kela->id || $tugas->pertemuan_id !== $pertemuan->id) {
            abort(404);
        }

        $enrolledStudents = $kela->kelasMahasiswa()
            ->with(['mahasiswa.user'])
            ->get();

        $submissions = PengumpulanTugas::where('tugas_id', $tugas->id)
            ->get()
            ->keyBy('mahasiswa_id');

        return view('dosen.tugas.show', [
            'kelas' => $kela,
            'pertemuan' => $pertemuan,
            'tugas' => $tugas,
            'enrolledStudents' => $enrolledStudents,
            'submissions' => $submissions,
        ]);
    }

    public function grade(
        Request $request,
        Kelas $kela,
        Pertemuan $pertemuan,
        Tugas $tugas,
        PengumpulanTugas $pengumpulan,
        SinkronisasiTugasService $syncService
    ): RedirectResponse {
        $this->authorizeDosenForKelas($request, $kela);

        if ($pengumpulan->tugas_id !== $tugas->id) {
            abort(404);
        }

        $validated = $request->validate([
            'nilai' => 'required|numeric|min:0|max:100',
            'umpan_balik' => 'nullable|string|max:1000',
        ]);

        $pengumpulan->update([
            'nilai' => $validated['nilai'],
            'umpan_balik' => $validated['umpan_balik'] ?? null,
        ]);

        $km = KelasMahasiswa::where('kelas_id', $kela->id)
            ->where('mahasiswa_id', $pengumpulan->mahasiswa_id)
            ->first();

        if ($km) {
            $syncService->syncMahasiswa($km);
        }

        return back()->with('success', 'Nilai tugas dan feedback berhasil disimpan, serta disinkronkan ke rekap nilai.');
    }

    public function downloadSubmission(
        Request $request,
        Kelas $kela,
        Pertemuan $pertemuan,
        Tugas $tugas,
        PengumpulanTugas $pengumpulan
    ): StreamedResponse {
        $this->authorizeDosenForKelas($request, $kela);

        if ($pengumpulan->tugas_id !== $tugas->id) {
            abort(404);
        }

        if (! $pengumpulan->file_path || ! Storage::disk('private')->exists($pengumpulan->file_path)) {
            abort(404, 'File tugas tidak ditemukan.');
        }

        return Storage::disk('private')->download($pengumpulan->file_path);
    }

    public function syncAll(Request $request, Kelas $kela, SinkronisasiTugasService $syncService): RedirectResponse
    {
        $this->authorizeDosenForKelas($request, $kela);

        $count = $syncService->syncKelas($kela->id);

        return back()->with('success', "Sinkronisasi nilai tugas selesai. {$count} data nilai mahasiswa diperbarui.");
    }
}
