<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Materi;
use App\Models\Pertemuan;
use App\Models\Ruangan;
use App\Models\Tugas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PertemuanController extends Controller
{
    protected function authorizeDosenForKelas(Request $request, Kelas $kelas): Dosen
    {
        $dosen = Dosen::where('user_id', $request->user()->id)->firstOrFail();
        if ($kelas->dosen_id !== $dosen->id) {
            abort(403, 'Anda bukan dosen pengampu untuk kelas ini.');
        }

        return $dosen;
    }

    public function index(Request $request, Kelas $kela): View
    {
        $this->authorizeDosenForKelas($request, $kela);

        $kela->load(['mataKuliah', 'ruangan', 'tahunAkademik']);
        $pertemuans = $kela->pertemuan()
            ->withCount(['materis', 'tugas', 'presensis'])
            ->orderBy('pertemuan_ke')
            ->get();

        return view('dosen.pertemuan.index', [
            'kelas' => $kela,
            'pertemuans' => $pertemuans,
            'ruangans' => Ruangan::all(),
        ]);
    }

    public function show(Request $request, Kelas $kela, Pertemuan $pertemuan): View
    {
        $this->authorizeDosenForKelas($request, $kela);

        if ($pertemuan->kelas_id !== $kela->id) {
            abort(404);
        }

        $pertemuan->load(['materis', 'tugas.pengumpulans', 'presensis.mahasiswa', 'ruangan']);
        $totalStudents = $kela->kelasMahasiswa()->count();

        return view('dosen.pertemuan.show', [
            'kelas' => $kela,
            'pertemuan' => $pertemuan,
            'totalStudents' => $totalStudents,
            'ruangans' => Ruangan::all(),
        ]);
    }

    public function update(Request $request, Kelas $kela, Pertemuan $pertemuan): RedirectResponse
    {
        $this->authorizeDosenForKelas($request, $kela);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'mode' => 'required|in:offline,online',
            'tanggal' => 'required|date',
            'ruangan_id' => 'nullable|exists:ruangan,id',
            'tautan_meeting' => 'nullable|url|max:255',
            'berita_acara' => 'nullable|string',
            'wajib_selfie' => 'nullable|boolean',
            'wajib_gps' => 'nullable|boolean',
        ]);

        $pertemuan->update([
            'judul' => $validated['judul'],
            'mode' => $validated['mode'],
            'tanggal' => $validated['tanggal'],
            'ruangan_id' => $validated['mode'] === 'offline' ? ($validated['ruangan_id'] ?? null) : null,
            'tautan_meeting' => $validated['mode'] === 'online' ? ($validated['tautan_meeting'] ?? null) : null,
            'berita_acara' => $validated['berita_acara'] ?? null,
            'wajib_selfie' => (bool) ($request->has('wajib_selfie')),
            'wajib_gps' => (bool) ($request->has('wajib_gps')),
        ]);

        return back()->with('success', "Informasi sesi pertemuan ke-{$pertemuan->pertemuan_ke} berhasil diperbarui.");
    }

    public function togglePresensi(Request $request, Kelas $kela, Pertemuan $pertemuan): RedirectResponse
    {
        $this->authorizeDosenForKelas($request, $kela);

        $isCurrentlyOpen = (bool) $pertemuan->is_presensi_open;
        $willOpen = ! $isCurrentlyOpen;

        $kode = $pertemuan->kode_presensi;
        if ($willOpen && empty($kode)) {
            $kode = strtoupper(Str::random(6));
        }

        $pertemuan->update([
            'is_presensi_open' => $willOpen,
            'kode_presensi' => $willOpen ? $kode : $pertemuan->kode_presensi,
        ]);

        $statusMsg = $willOpen ? "Presensi SESI {$pertemuan->pertemuan_ke} DIBUKA (Kode PIN: {$pertemuan->kode_presensi})." : "Presensi SESI {$pertemuan->pertemuan_ke} DITUTUP.";

        return back()->with('success', $statusMsg);
    }

    public function storeMateri(Request $request, Kelas $kela, Pertemuan $pertemuan): RedirectResponse
    {
        $this->authorizeDosenForKelas($request, $kela);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tipe' => 'required|in:file,url,teks',
            'file' => 'nullable|file|max:20480', // max 20MB
            'url' => 'nullable|url|max:255',
            'konten' => 'nullable|string',
        ]);

        $filePath = null;
        if ($validated['tipe'] === 'file' && $request->hasFile('file')) {
            $file = $request->file('file');
            $ext = $file->getClientOriginalExtension();
            $filename = "materi_{$pertemuan->id}_".time()."_{$file->getClientOriginalName()}";
            $filePath = $file->storeAs('materi', $filename, 'private');
        }

        Materi::create([
            'pertemuan_id' => $pertemuan->id,
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'tipe' => $validated['tipe'],
            'file_path' => $filePath,
            'url' => $validated['tipe'] === 'url' ? ($validated['url'] ?? null) : null,
            'konten' => $validated['tipe'] === 'teks' ? ($validated['konten'] ?? null) : null,
        ]);

        return back()->with('success', 'Materi pembelajaran berhasil ditambahkan.');
    }

    public function destroyMateri(Request $request, Kelas $kela, Pertemuan $pertemuan, Materi $materi): RedirectResponse
    {
        $this->authorizeDosenForKelas($request, $kela);

        if ($materi->file_path && Storage::disk('private')->exists($materi->file_path)) {
            Storage::disk('private')->delete($materi->file_path);
        }

        $materi->delete();

        return back()->with('success', 'Materi berhasil dihapus.');
    }

    public function storeTugas(Request $request, Kelas $kela, Pertemuan $pertemuan): RedirectResponse
    {
        $this->authorizeDosenForKelas($request, $kela);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'instruksi' => 'required|string',
            'batas_waktu' => 'required|date',
            'allow_late' => 'nullable|boolean',
            'lampiran' => 'nullable|file|max:10240',
        ]);

        $lampiranPath = null;
        if ($request->hasFile('lampiran')) {
            $file = $request->file('lampiran');
            $filename = "tugas_lampiran_{$pertemuan->id}_".time()."_{$file->getClientOriginalName()}";
            $lampiranPath = $file->storeAs('tugas', $filename, 'private');
        }

        Tugas::create([
            'pertemuan_id' => $pertemuan->id,
            'judul' => $validated['judul'],
            'instruksi' => $validated['instruksi'],
            'lampiran_path' => $lampiranPath,
            'batas_waktu' => $validated['batas_waktu'],
            'allow_late' => (bool) ($request->has('allow_late')),
        ]);

        return back()->with('success', 'Penugasan baru berhasil dibuat.');
    }
}
