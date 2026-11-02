<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\KelasMahasiswa;
use App\Models\Mahasiswa;
use App\Models\PengumpulanTugas;
use App\Models\Tugas;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TugasController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $mahasiswa = Mahasiswa::with(['prodi.fakultas', 'user'])
            ->where('user_id', $user->id)
            ->firstOrFail();

        $kelasIds = KelasMahasiswa::where('mahasiswa_id', $mahasiswa->id)
            ->pluck('kelas_id')
            ->merge(
                $mahasiswa->krsAktif?->krsDetail()->pluck('kelas_id') ?? collect()
            )
            ->unique()
            ->values();

        $filterStatus = $request->input('status', 'semua');

        $tugasList = Tugas::query()
            ->whereHas('pertemuan', fn ($q) => $q->whereIn('kelas_id', $kelasIds))
            ->with([
                'pertemuan.kelas.mataKuliah',
                'pertemuan.kelas.dosen.user',
                'pengumpulans' => fn ($q) => $q->where('mahasiswa_id', $mahasiswa->id),
            ])
            ->orderBy('batas_waktu', 'asc')
            ->get();

        $now = Carbon::now();

        $tugasList = $tugasList->map(function (Tugas $t) use ($now) {
            $submission = $t->pengumpulans->first();
            $isSubmitted = $submission !== null;
            $isGraded = $submission && $submission->nilai !== null;
            $isOverdue = $now->greaterThan($t->batas_waktu);

            if ($isGraded) {
                $statusKey = 'dinilai';
                $statusLabel = 'Dinilai: '.number_format($submission->nilai, 1);
                $statusBadge = 'tag-pill-green';
            } elseif ($isSubmitted) {
                $statusKey = 'dikumpulkan';
                $statusLabel = 'Menunggu Penilaian';
                $statusBadge = 'tag-pill-blue';
            } elseif ($isOverdue) {
                $statusKey = 'terlambat';
                $statusLabel = $t->allow_late ? 'Terlambat (Bisa Kumpul)' : 'Tenggat Berakhir';
                $statusBadge = 'tag-pill-orange';
            } else {
                $statusKey = 'pending';
                $statusLabel = 'Perlu Dikerjakan';
                $statusBadge = 'tag-pill-amber';
            }

            $diffHours = $now->diffInHours($t->batas_waktu, false);
            if ($diffHours > 24) {
                $sisaHari = ceil($diffHours / 24);
                $countdownText = "Sisa {$sisaHari} hari";
            } elseif ($diffHours > 0) {
                $countdownText = "Sisa {$diffHours} jam";
            } else {
                $countdownText = 'Tenggat lewat';
            }

            $t->submission = $submission;
            $t->is_submitted = $isSubmitted;
            $t->is_graded = $isGraded;
            $t->is_overdue = $isOverdue;
            $t->status_key = $statusKey;
            $t->status_label = $statusLabel;
            $t->status_badge = $statusBadge;
            $t->countdown_text = $countdownText;

            return $t;
        });

        // Compute counts
        $totalAll = $tugasList->count();
        $totalPending = $tugasList->whereIn('status_key', ['pending', 'terlambat'])->where('is_submitted', false)->count();
        $totalSubmitted = $tugasList->where('status_key', 'dikumpulkan')->count();
        $totalGraded = $tugasList->where('status_key', 'dinilai')->count();

        // Apply filter
        $filteredTugas = match ($filterStatus) {
            'pending' => $tugasList->whereIn('status_key', ['pending', 'terlambat'])->where('is_submitted', false)->values(),
            'dikumpulkan' => $tugasList->where('status_key', 'dikumpulkan')->values(),
            'dinilai' => $tugasList->where('status_key', 'dinilai')->values(),
            default => $tugasList->values(),
        };

        return view('mahasiswa.tugas.index', [
            'mahasiswa' => $mahasiswa,
            'tugasList' => $filteredTugas,
            'filterStatus' => $filterStatus,
            'totalAll' => $totalAll,
            'totalPending' => $totalPending,
            'totalSubmitted' => $totalSubmitted,
            'totalGraded' => $totalGraded,
        ]);
    }

    protected function authorizeMahasiswaEnrollment(Request $request, Tugas $tugas): array
    {
        $mahasiswa = Mahasiswa::where('user_id', $request->user()->id)->firstOrFail();
        $kelasId = $tugas->pertemuan->kelas_id;

        $isEnrolled = KelasMahasiswa::where('kelas_id', $kelasId)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->exists();

        if (! $isEnrolled) {
            abort(403, 'Anda tidak terdaftar dalam kelas penugasan ini.');
        }

        return [$mahasiswa, $kelasId];
    }

    public function show(Request $request, Tugas $tugas): View
    {
        [$mahasiswa] = $this->authorizeMahasiswaEnrollment($request, $tugas);

        $tugas->load(['pertemuan.kelas.mataKuliah', 'pertemuan.kelas.dosen.user']);

        $submission = PengumpulanTugas::where('tugas_id', $tugas->id)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->first();

        $isOverdue = Carbon::now()->greaterThan($tugas->batas_waktu);

        return view('mahasiswa.tugas.show', [
            'tugas' => $tugas,
            'pertemuan' => $tugas->pertemuan,
            'kelas' => $tugas->pertemuan->kelas,
            'submission' => $submission,
            'isOverdue' => $isOverdue,
            'canSubmit' => ! $isOverdue || $tugas->allow_late,
        ]);
    }

    public function submit(Request $request, Tugas $tugas): RedirectResponse
    {
        [$mahasiswa] = $this->authorizeMahasiswaEnrollment($request, $tugas);

        $isOverdue = Carbon::now()->greaterThan($tugas->batas_waktu);
        if ($isOverdue && ! $tugas->allow_late) {
            return back()->withErrors(['file' => 'Batas waktu pengumpulan tugas telah berakhir dan pengumpulan terlambat tidak diizinkan.']);
        }

        $request->validate([
            'file' => 'required|file|max:20480', // max 20MB
            'catatan_mahasiswa' => 'nullable|string|max:1000',
        ]);

        $file = $request->file('file');
        $filename = "tugas_{$tugas->id}_{$mahasiswa->nim}_".time().'.'.$file->getClientOriginalExtension();
        $filePath = $file->storeAs('tugas_submissions', $filename, 'private');

        $submission = PengumpulanTugas::firstOrNew([
            'tugas_id' => $tugas->id,
            'mahasiswa_id' => $mahasiswa->id,
        ]);

        // If replacing previous file, delete old file
        if ($submission->exists && $submission->file_path && Storage::disk('private')->exists($submission->file_path)) {
            Storage::disk('private')->delete($submission->file_path);
        }

        $submission->file_path = $filePath;
        $submission->catatan_mahasiswa = $request->input('catatan_mahasiswa');
        $submission->dikumpulkan_pada = Carbon::now();
        $submission->is_terlambat = $isOverdue;
        $submission->save();

        return back()->with('success', 'Tugas berhasil dikumpulkan'.($isOverdue ? ' (Terlambat).' : '.'));
    }

    public function downloadLampiran(Request $request, Tugas $tugas): StreamedResponse
    {
        $this->authorizeMahasiswaEnrollment($request, $tugas);

        if (! $tugas->lampiran_path || ! Storage::disk('private')->exists($tugas->lampiran_path)) {
            abort(404, 'Lampiran tugas tidak ditemukan.');
        }

        return Storage::disk('private')->download($tugas->lampiran_path);
    }

    public function downloadJawaban(Request $request, Tugas $tugas): StreamedResponse
    {
        [$mahasiswa] = $this->authorizeMahasiswaEnrollment($request, $tugas);

        $submission = PengumpulanTugas::where('tugas_id', $tugas->id)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->firstOrFail();

        if (! $submission->file_path || ! Storage::disk('private')->exists($submission->file_path)) {
            abort(404, 'File tugas tidak ditemukan.');
        }

        return Storage::disk('private')->download($submission->file_path);
    }
}
