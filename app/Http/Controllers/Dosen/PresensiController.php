<?php

namespace App\Http\Controllers\Dosen;

use App\Enums\StatusPresensi;
use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\Pertemuan;
use App\Models\Presensi;
use App\Services\PresensiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PresensiController extends Controller
{
    public function __construct(
        protected PresensiService $presensiService,
    ) {}

    protected function authorizeDosenForKelas(Request $request, Kelas $kelas): Dosen
    {
        $dosen = Dosen::where('user_id', $request->user()->id)->firstOrFail();
        if ($kelas->dosen_id !== $dosen->id) {
            abort(403, 'Anda bukan dosen pengampu untuk kelas ini.');
        }

        return $dosen;
    }

    public function index(Request $request, Kelas $kela, Pertemuan $pertemuan): View
    {
        $this->authorizeDosenForKelas($request, $kela);

        if ($pertemuan->kelas_id !== $kela->id) {
            abort(404);
        }

        $kela->load(['mataKuliah', 'ruangan']);
        $enrolledStudents = $kela->mahasiswas()->orderBy('nim')->get();

        $presensis = Presensi::where('pertemuan_id', $pertemuan->id)
            ->with('mahasiswa')
            ->get()
            ->keyBy('mahasiswa_id');

        $hadirCount = $presensis->where('status', StatusPresensi::Hadir)->count();
        $izinCount = $presensis->where('status', StatusPresensi::Izin)->count();
        $sakitCount = $presensis->where('status', StatusPresensi::Sakit)->count();
        $alphaCount = $enrolledStudents->count() - $hadirCount - $izinCount - $sakitCount;

        return view('dosen.presensi.index', [
            'kelas' => $kela,
            'pertemuan' => $pertemuan,
            'enrolledStudents' => $enrolledStudents,
            'presensis' => $presensis,
            'hadirCount' => $hadirCount,
            'izinCount' => $izinCount,
            'sakitCount' => $sakitCount,
            'alphaCount' => max(0, $alphaCount),
        ]);
    }

    public function update(Request $request, Kelas $kela, Pertemuan $pertemuan, Mahasiswa $mahasiswa): RedirectResponse
    {
        $this->authorizeDosenForKelas($request, $kela);

        $validated = $request->validate([
            'status' => 'required|in:hadir,izin,sakit,alpha',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $statusEnum = StatusPresensi::from($validated['status']);
        $this->presensiService->updateManualDosen(
            $pertemuan,
            $mahasiswa,
            $statusEnum,
            $validated['keterangan'] ?? null
        );

        return back()->with('success', "Status presensi {$mahasiswa->nama} berhasil diubah menjadi {$statusEnum->label()}.");
    }

    public function exportCsv(Request $request, Kelas $kela, Pertemuan $pertemuan): StreamedResponse
    {
        $this->authorizeDosenForKelas($request, $kela);

        $enrolled = $kela->mahasiswas()->orderBy('nim')->get();
        $presensis = Presensi::where('pertemuan_id', $pertemuan->id)->get()->keyBy('mahasiswa_id');

        $filename = "Presensi_{$kela->nama_kelas}_Pertemuan_{$pertemuan->pertemuan_ke}_".now()->format('Ymd_His').'.csv';

        return response()->streamDownload(function () use ($enrolled, $presensis): void {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['No', 'NIM', 'Nama Mahasiswa', 'Status Presensi', 'Waktu Dicatat', 'Jarak (meter)', 'Dalam Radius', 'Metode', 'Keterangan']);

            foreach ($enrolled as $idx => $mhs) {
                $p = $presensis->get($mhs->id);
                fputcsv($handle, [
                    $idx + 1,
                    $mhs->nim,
                    $mhs->nama,
                    $p ? $p->status->label() : 'Alpha / Belum Hadir',
                    $p?->dicatat_pada?->format('Y-m-d H:i:s') ?? '-',
                    $p?->jarak_meter ?? '-',
                    $p ? ($p->is_dalam_radius ? 'Ya' : 'Tidak') : '-',
                    $p?->metode->value ?? '-',
                    $p?->keterangan ?? '-',
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
        ]);
    }
}
