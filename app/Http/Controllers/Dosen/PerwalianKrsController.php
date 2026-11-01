<?php

namespace App\Http\Controllers\Dosen;

use App\Enums\StatusKrs;
use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Krs;
use App\Models\Mahasiswa;
use App\Models\TahunAkademik;
use App\Services\KrsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PerwalianKrsController extends Controller
{
    public function __construct(
        protected KrsService $krsService,
    ) {}

    public function index(Request $request): View
    {
        $dosen = Dosen::where('user_id', $request->user()->id)->firstOrFail();
        $tahunAktif = TahunAkademik::where('is_active', true)->first()
            ?? TahunAkademik::latest('id')->firstOrFail();

        $statusFilter = $request->query('status');

        $query = Mahasiswa::query()
            ->with(['programStudi'])
            ->with(['krs' => function ($q) use ($tahunAktif) {
                $q->where('tahun_akademik_id', $tahunAktif->id)
                    ->with(['details.kelas.mataKuliah', 'details.kelas.dosen', 'details.kelas.ruangan']);
            }])
            ->where('dosen_wali_id', $dosen->id);

        if ($statusFilter) {
            $query->whereHas('krs', function ($q) use ($tahunAktif, $statusFilter) {
                $q->where('tahun_akademik_id', $tahunAktif->id)
                    ->where('status', $statusFilter);
            });
        }

        $advisees = $query->paginate(15)->withQueryString();

        // Statistics
        $totalAdvisees = Mahasiswa::where('dosen_wali_id', $dosen->id)->count();
        $pendingCount = Krs::where('tahun_akademik_id', $tahunAktif->id)
            ->where('status', StatusKrs::Diajukan)
            ->whereHas('mahasiswa', fn ($q) => $q->where('dosen_wali_id', $dosen->id))
            ->count();
        $approvedCount = Krs::where('tahun_akademik_id', $tahunAktif->id)
            ->where('status', StatusKrs::Disetujui)
            ->whereHas('mahasiswa', fn ($q) => $q->where('dosen_wali_id', $dosen->id))
            ->count();

        return view('dosen.perwalian.index', [
            'dosen' => $dosen,
            'tahunAktif' => $tahunAktif,
            'advisees' => $advisees,
            'totalAdvisees' => $totalAdvisees,
            'pendingCount' => $pendingCount,
            'approvedCount' => $approvedCount,
            'currentFilter' => $statusFilter,
        ]);
    }

    public function show(Request $request, Mahasiswa $mahasiswa): View
    {
        $dosen = Dosen::where('user_id', $request->user()->id)->firstOrFail();

        if ($mahasiswa->dosen_wali_id !== $dosen->id) {
            abort(403, 'Anda bukan dosen pembimbing akademik dari mahasiswa ini.');
        }

        $tahunAktif = TahunAkademik::where('is_active', true)->first()
            ?? TahunAkademik::latest('id')->firstOrFail();

        $krs = Krs::where('mahasiswa_id', $mahasiswa->id)
            ->where('tahun_akademik_id', $tahunAktif->id)
            ->with(['details.kelas.mataKuliah', 'details.kelas.dosen', 'details.kelas.ruangan', 'disetujuiOleh'])
            ->first();

        return view('dosen.perwalian.show', [
            'dosen' => $dosen,
            'mahasiswa' => $mahasiswa,
            'tahunAktif' => $tahunAktif,
            'krs' => $krs,
        ]);
    }

    public function approve(Request $request, Krs $krs): RedirectResponse
    {
        $dosen = Dosen::where('user_id', $request->user()->id)->firstOrFail();

        if ($krs->mahasiswa->dosen_wali_id !== $dosen->id) {
            abort(403, 'Anda tidak berwenang menyetujui KRS mahasiswa ini.');
        }

        $this->krsService->approveKrs($krs, $request->user()->id);

        return back()->with('success', "KRS untuk mahasiswa {$krs->mahasiswa->nama} ({$krs->mahasiswa->nim}) berhasil disetujui.");
    }

    public function revisi(Request $request, Krs $krs): RedirectResponse
    {
        $validated = $request->validate([
            'catatan' => 'required|string|max:1000',
        ]);

        $dosen = Dosen::where('user_id', $request->user()->id)->firstOrFail();

        if ($krs->mahasiswa->dosen_wali_id !== $dosen->id) {
            abort(403, 'Anda tidak berwenang meminta revisi KRS mahasiswa ini.');
        }

        $this->krsService->reviseKrs($krs, $validated['catatan']);

        return back()->with('success', "Status KRS mahasiswa {$krs->mahasiswa->nama} diubah menjadi 'Perlu Revisi'. Catatan telah dikirimkan.");
    }
}

