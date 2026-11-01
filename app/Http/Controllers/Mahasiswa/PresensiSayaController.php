<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\KelasMahasiswa;
use App\Models\Mahasiswa;
use App\Models\Pertemuan;
use App\Models\Presensi;
use App\Services\PresensiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PresensiSayaController extends Controller
{
    public function __construct(
        protected PresensiService $presensiService,
    ) {}

    public function show(Request $request, Pertemuan $pertemuan): View|RedirectResponse
    {
        $user = $request->user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();

        // Verify enrollment
        $isEnrolled = KelasMahasiswa::where('kelas_id', $pertemuan->kelas_id)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->exists();

        if (! $isEnrolled) {
            abort(403, 'Anda tidak terdaftar dalam kelas ini.');
        }

        $pertemuan->load(['kelas.mataKuliah', 'kelas.dosen', 'ruangan']);

        // Check if student already attended
        $presensi = Presensi::where('pertemuan_id', $pertemuan->id)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->first();

        return view('mahasiswa.presensi.show', [
            'mahasiswa' => $mahasiswa,
            'pertemuan' => $pertemuan,
            'presensi' => $presensi,
            'kelas' => $pertemuan->kelas,
        ]);
    }

    public function store(Request $request, Pertemuan $pertemuan): RedirectResponse
    {
        $user = $request->user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();

        // Verify enrollment
        $isEnrolled = KelasMahasiswa::where('kelas_id', $pertemuan->kelas_id)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->exists();

        if (! $isEnrolled) {
            abort(403, 'Anda tidak terdaftar dalam kelas ini.');
        }

        $validated = $request->validate([
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'akurasi_meter' => 'nullable|numeric',
            'kode_presensi' => 'nullable|string|max:20',
            'foto_selfie' => 'nullable|string', // Base64 data URI from canvas
        ]);

        $data = [
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'akurasi_meter' => $validated['akurasi_meter'] ?? null,
            'kode_presensi' => $validated['kode_presensi'] ?? null,
            'foto_selfie' => $validated['foto_selfie'] ?? null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ];

        $result = $this->presensiService->submitMandiri($pertemuan, $mahasiswa, $data);

        if (! $result['success']) {
            return back()->with('error', $result['message']);
        }

        return back()->with('success', $result['message']);
    }
}

