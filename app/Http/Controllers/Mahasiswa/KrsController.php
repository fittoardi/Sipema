<?php

namespace App\Services;

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\TahunAkademik;
use App\Services\KrsService;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class KrsController extends Controller
{
    public function __construct(
        protected KrsService $krsService,
    ) {}

    public function index(Request $request): View
    {
        $user = $request->user();
        $mahasiswa = Mahasiswa::with(['programStudi', 'dosenWali'])
            ->where('user_id', $user->id)
            ->firstOrFail();

        $tahunAktif = TahunAkademik::where('is_active', true)->first()
            ?? TahunAkademik::latest('id')->firstOrFail();

        $krs = $this->krsService->getOrCreateDraft($mahasiswa, $tahunAktif);
        $krs->load(['details.kelas.mataKuliah', 'details.kelas.dosen', 'details.kelas.ruangan', 'disetujuiOleh']);

        $selectedClassIds = $krs->details->pluck('kelas_id')->all();

        // Available classes for student's prodi in the active academic year
        $availableClasses = Kelas::query()
            ->with(['mataKuliah', 'dosen', 'ruangan'])
            ->withCount('kelasMahasiswas')
            ->where('tahun_akademik_id', $tahunAktif->id)
            ->whereHas('mataKuliah', function ($q) use ($mahasiswa) {
                $q->where('program_studi_id', $mahasiswa->program_studi_id);
            })
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->get();

        return view('mahasiswa.krs.index', [
            'mahasiswa' => $mahasiswa,
            'tahunAktif' => $tahunAktif,
            'krs' => $krs,
            'selectedClassIds' => $selectedClassIds,
            'availableClasses' => $availableClasses,
            'isKrsOpen' => $tahunAktif->is_krs_open,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        $user = $request->user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();
        $tahunAktif = TahunAkademik::where('is_active', true)->firstOrFail();

        if (! $tahunAktif->is_krs_open) {
            return back()->with('error', 'Masa pengisian KRS online telah ditutup.');
        }

        $krs = $this->krsService->getOrCreateDraft($mahasiswa, $tahunAktif);

        $result = $this->krsService->addClass($krs, (int) $validated['kelas_id']);

        if (! $result['success']) {
            return back()->with('error', $result['message']);
        }

        return back()->with('success', $result['message']);
    }

    public function destroy(Request $request, Kelas $kela): RedirectResponse
    {
        $user = $request->user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();
        $tahunAktif = TahunAkademik::where('is_active', true)->firstOrFail();

        $krs = $this->krsService->getOrCreateDraft($mahasiswa, $tahunAktif);

        try {
            $this->krsService->removeClass($krs, $kela->id);

            return back()->with('success', "Mata kuliah {$kela->nama_lengkap} berhasil dihapus dari KRS.");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function submit(Request $request): RedirectResponse
    {
        $user = $request->user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();
        $tahunAktif = TahunAkademik::where('is_active', true)->firstOrFail();

        $krs = $this->krsService->getOrCreateDraft($mahasiswa, $tahunAktif);

        $result = $this->krsService->submitKrs($krs);

        if (! $result['success']) {
            return back()->with('error', $result['message']);
        }

        return back()->with('success', $result['message']);
    }

    public function cetakPdf(Request $request): Response
    {
        $user = $request->user();
        $mahasiswa = Mahasiswa::with(['programStudi', 'dosenWali'])
            ->where('user_id', $user->id)
            ->firstOrFail();

        $tahunAktif = TahunAkademik::where('is_active', true)->firstOrFail();

        $krs = $this->krsService->getOrCreateDraft($mahasiswa, $tahunAktif);
        $krs->load(['details.kelas.mataKuliah', 'details.kelas.dosen', 'details.kelas.ruangan', 'disetujuiOleh']);

        $options = new Options;
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $html = view('mahasiswa.krs.cetak_pdf', [
            'mahasiswa' => $mahasiswa,
            'tahunAktif' => $tahunAktif,
            'krs' => $krs,
        ])->render();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = "KRS_{$mahasiswa->nim}_{$tahunAktif->tahun_formatted}.pdf";

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$filename.'"',
        ]);
    }
}
