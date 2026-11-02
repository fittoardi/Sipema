<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\KelasMahasiswa;
use App\Models\Mahasiswa;
use App\Models\Materi;
use App\Models\ProgresMateri;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class KelasController extends Controller
{
    protected function getMahasiswa(Request $request): Mahasiswa
    {
        return Mahasiswa::with(['prodi.fakultas', 'user'])
            ->where('user_id', $request->user()?->id)
            ->firstOrFail();
    }

    public function index(Request $request): View
    {
        $mahasiswa = $this->getMahasiswa($request);

        $kelasIds = KelasMahasiswa::where('mahasiswa_id', $mahasiswa->id)
            ->pluck('kelas_id')
            ->merge(
                $mahasiswa->krsAktif?->krsDetail()->pluck('kelas_id') ?? collect()
            )
            ->unique()
            ->values();

        $kelasList = Kelas::with([
            'mataKuliah.programStudi',
            'ruangan',
            'dosen.user',
            'pertemuan.materis',
            'pertemuan.tugas',
            'pertemuan.presensis' => fn ($q) => $q->where('mahasiswa_id', $mahasiswa->id),
        ])
            ->whereIn('id', $kelasIds)
            ->get();

        $kelasList = $kelasList->map(function (Kelas $k) use ($mahasiswa) {
            $totalPertemuan = $k->pertemuan->count();
            $hadirCount = 0;
            $materiSelesaiCount = 0;
            $totalMateri = 0;

            foreach ($k->pertemuan as $p) {
                $presensi = $p->presensis->first();
                if ($presensi && $presensi->status?->value === 'hadir') {
                    $hadirCount++;
                }

                $totalMateri += $p->materis->count();
                $materiIds = $p->materis->pluck('id');
                if ($materiIds->isNotEmpty()) {
                    $materiSelesaiCount += ProgresMateri::whereIn('materi_id', $materiIds)
                        ->where('mahasiswa_id', $mahasiswa->id)
                        ->count();
                }
            }

            $persenHadir = $totalPertemuan > 0 ? round(($hadirCount / $totalPertemuan) * 100) : 0;
            $persenMateri = $totalMateri > 0 ? round(($materiSelesaiCount / $totalMateri) * 100) : 0;

            $k->total_pertemuan = $totalPertemuan;
            $k->hadir_count = $hadirCount;
            $k->persen_hadir = $persenHadir;
            $k->total_materi = $totalMateri;
            $k->materi_selesai_count = $materiSelesaiCount;
            $k->persen_materi = $persenMateri;

            return $k;
        });

        return view('mahasiswa.kelas.index', [
            'mahasiswa' => $mahasiswa,
            'kelasList' => $kelasList,
        ]);
    }

    public function show(Request $request, Kelas $kela): View
    {
        $mahasiswa = $this->getMahasiswa($request);

        $isEnrolled = KelasMahasiswa::where('kelas_id', $kela->id)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->exists();

        if (! $isEnrolled && $kela->id !== $mahasiswa->krsAktif?->krsDetail()->firstWhere('kelas_id', $kela->id)?->kelas_id) {
            abort(403, 'Anda tidak terdaftar dalam kelas perkuliahan ini.');
        }

        $kela->load(['mataKuliah.programStudi', 'dosen.user', 'ruangan', 'tahunAkademik']);

        $pertemuans = $kela->pertemuan()
            ->with([
                'materis.progresMateri' => fn ($q) => $q->where('mahasiswa_id', $mahasiswa->id),
                'tugas.pengumpulans' => fn ($q) => $q->where('mahasiswa_id', $mahasiswa->id),
                'presensis' => fn ($q) => $q->where('mahasiswa_id', $mahasiswa->id),
                'ruangan',
            ])
            ->orderBy('pertemuan_ke')
            ->get();

        $totalHadir = 0;
        $totalSesi = $pertemuans->count();

        foreach ($pertemuans as $p) {
            $presensi = $p->presensis->first();
            if ($presensi && $presensi->status?->value === 'hadir') {
                $totalHadir++;
            }
        }

        $persenKehadiran = $totalSesi > 0 ? round(($totalHadir / $totalSesi) * 100) : 0;

        return view('mahasiswa.kelas.show', [
            'mahasiswa' => $mahasiswa,
            'kelas' => $kela,
            'pertemuans' => $pertemuans,
            'totalHadir' => $totalHadir,
            'totalSesi' => $totalSesi,
            'persenKehadiran' => $persenKehadiran,
        ]);
    }

    public function toggleMateri(Request $request, Materi $materi): RedirectResponse|JsonResponse
    {
        $mahasiswa = $this->getMahasiswa($request);
        $kelasId = $materi->pertemuan?->kelas_id;

        $isEnrolled = KelasMahasiswa::where('kelas_id', $kelasId)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->exists();

        if (! $isEnrolled) {
            abort(403, 'Akses tidak sah.');
        }

        $progres = ProgresMateri::where('materi_id', $materi->id)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->first();

        if ($progres) {
            $progres->delete();
            $message = "Status materi '{$materi->judul}' direset ke belum selesai.";
            $isSelesai = false;
        } else {
            ProgresMateri::create([
                'materi_id' => $materi->id,
                'mahasiswa_id' => $mahasiswa->id,
                'selesai_pada' => now(),
            ]);
            $message = "Selamat! Materi '{$materi->judul}' berhasil ditandai selesai belajar.";
            $isSelesai = true;
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_selesai' => $isSelesai,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }

    public function downloadMateri(Request $request, Materi $materi): StreamedResponse|RedirectResponse
    {
        $mahasiswa = $this->getMahasiswa($request);
        $kelasId = $materi->pertemuan?->kelas_id;

        $isEnrolled = KelasMahasiswa::where('kelas_id', $kelasId)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->exists();

        if (! $isEnrolled) {
            abort(403, 'Akses file tidak diizinkan.');
        }

        if ($materi->file_path && Storage::disk('private')->exists($materi->file_path)) {
            return Storage::disk('private')->download($materi->file_path, basename($materi->file_path));
        }

        if (! empty($materi->konten_atau_url)) {
            return redirect()->away($materi->konten_atau_url);
        }

        return back()->with('error', 'File lampiran tidak ditemukan.');
    }
}
