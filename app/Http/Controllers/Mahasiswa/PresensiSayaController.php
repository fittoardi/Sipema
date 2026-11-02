<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
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

        $kelasList = Kelas::with([
            'mataKuliah.programStudi',
            'dosen.user',
            'pertemuan.presensis' => fn ($q) => $q->where('mahasiswa_id', $mahasiswa->id),
        ])
            ->whereIn('id', $kelasIds)
            ->get();

        $totalHadir = 0;
        $totalIzin = 0;
        $totalSakit = 0;
        $totalAlpha = 0;
        $totalSesiBerjalan = 0;

        $rekapPerKelas = $kelasList->map(function ($k) use (&$totalHadir, &$totalIzin, &$totalSakit, &$totalAlpha, &$totalSesiBerjalan) {
            $hadir = 0;
            $izin = 0;
            $sakit = 0;
            $alpha = 0;
            $totalPertemuan = $k->pertemuan->count();

            foreach ($k->pertemuan as $p) {
                $pRecord = $p->presensis->first();
                if ($pRecord) {
                    $status = strtolower($pRecord->status?->value ?? (string) $pRecord->status);
                    if ($status === 'hadir') {
                        $hadir++;
                        $totalHadir++;
                    } elseif ($status === 'izin') {
                        $izin++;
                        $totalIzin++;
                    } elseif ($status === 'sakit') {
                        $sakit++;
                        $totalSakit++;
                    } else {
                        $alpha++;
                        $totalAlpha++;
                    }
                }
            }

            $sesiSelesai = $hadir + $izin + $sakit + $alpha;
            $totalSesiBerjalan += $sesiSelesai;
            $persentase = $totalPertemuan > 0 ? round(($hadir / $totalPertemuan) * 100) : 0;
            $isEligibleUas = $persentase >= 75;

            return (object) [
                'kelas' => $k,
                'mataKuliah' => $k->mataKuliah,
                'dosen' => $k->dosen,
                'total_pertemuan' => $totalPertemuan,
                'hadir' => $hadir,
                'izin' => $izin,
                'sakit' => $sakit,
                'alpha' => $alpha,
                'sesi_selesai' => $sesiSelesai,
                'persentase' => $persentase,
                'is_eligible_uas' => $isEligibleUas,
            ];
        });

        $totalRecorded = $totalHadir + $totalIzin + $totalSakit + $totalAlpha;
        $overallPercentage = $totalRecorded > 0 ? round(($totalHadir / $totalRecorded) * 100) : 0;

        // Riwayat presensi terbaru
        $riwayatPresensi = Presensi::with(['pertemuan.kelas.mataKuliah', 'pertemuan.ruangan'])
            ->where('mahasiswa_id', $mahasiswa->id)
            ->latest('dicatat_pada')
            ->take(15)
            ->get();

        return view('mahasiswa.presensi.index', [
            'mahasiswa' => $mahasiswa,
            'rekapPerKelas' => $rekapPerKelas,
            'totalHadir' => $totalHadir,
            'totalIzin' => $totalIzin,
            'totalSakit' => $totalSakit,
            'totalAlpha' => $totalAlpha,
            'overallPercentage' => $overallPercentage,
            'riwayatPresensi' => $riwayatPresensi,
        ]);
    }

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
