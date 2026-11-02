<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Enums\Hari;
use App\Enums\TipeKelas;
use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\KelasMahasiswa;
use App\Models\Mahasiswa;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        /** @var Mahasiswa $mahasiswa */
        $mahasiswa = Mahasiswa::query()
            ->with(['prodi.fakultas', 'user'])
            ->where('user_id', $user?->id)
            ->firstOrFail();

        // Retrieve all classes the student is enrolled in
        $kelasIds = KelasMahasiswa::query()
            ->where('mahasiswa_id', $mahasiswa->id)
            ->pluck('kelas_id')
            ->merge(
                $mahasiswa->krsAktif?->krsDetail()->pluck('kelas_id') ?? collect()
            )
            ->unique()
            ->values();

        $dayOrder = [
            'Senin' => 1,
            'Selasa' => 2,
            'Rabu' => 3,
            'Kamis' => 4,
            'Jumat' => 5,
            'Sabtu' => 6,
            'Minggu' => 7,
        ];

        $carbonDayToHari = [
            0 => 'Minggu',
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
        ];

        $now = Carbon::now('Asia/Jakarta');
        $todayHari = $carbonDayToHari[$now->dayOfWeek] ?? 'Senin';
        $todayDate = $now->toDateString();

        $kelasList = Kelas::query()
            ->with([
                'mataKuliah.programStudi',
                'ruangan',
                'dosen.user',
                'pertemuan' => fn ($q) => $q->orderBy('pertemuan_ke'),
            ])
            ->whereIn('id', $kelasIds)
            ->get();

        $jadwals = $kelasList->map(function (Kelas $k) use ($todayDate) {
            $hariVal = $k->hari instanceof Hari ? $k->hari->value : (string) ($k->hari ?? 'Senin');
            $tipeVal = $k->tipe_kelas instanceof TipeKelas ? $k->tipe_kelas->value : (string) ($k->tipe_kelas ?? 'offline');

            $pertemuanBuka = $k->pertemuan->firstWhere('is_presensi_open', true);
            $pertemuanHariIni = $k->pertemuan->first(fn ($p) => $p->tanggal?->toDateString() === $todayDate);

            return (object) [
                'id' => $k->id,
                'kelas_id' => $k->id,
                'kelas' => $k,
                'nama_kelas' => $k->nama_kelas,
                'mataKuliah' => $k->mataKuliah,
                'sks' => (int) ($k->mataKuliah?->sks ?? 3),
                'hari' => $hariVal,
                'jam_mulai' => (string) ($k->jam_mulai ?? '08:00:00'),
                'jam_selesai' => (string) ($k->jam_selesai ?? '10:00:00'),
                'jam_rentang' => substr((string) ($k->jam_mulai ?? '08:00'), 0, 5).' - '.substr((string) ($k->jam_selesai ?? '10:00'), 0, 5).' WIB',
                'ruangan' => $k->ruangan?->nama ?? ($tipeVal === 'online' ? 'Online / Daring' : 'Ruang Kelas'),
                'gedung' => $k->ruangan?->gedung ?? 'Kampus Utama',
                'tipe_kelas' => $tipeVal,
                'dosen_nama' => $k->dosen?->nama ?? ($k->dosen?->user?->name ?? 'Dosen Pengampu'),
                'dosen_nidn' => $k->dosen?->nidn ?? '-',
                'pertemuan_buka' => $pertemuanBuka,
                'pertemuan_hari_ini' => $pertemuanHariIni,
            ];
        })
            ->sortBy(fn ($j) => sprintf('%02d-%s', $dayOrder[$j->hari] ?? 99, $j->jam_mulai))
            ->values();

        $jadwalHariIni = $jadwals->where('hari', $todayHari)->values();
        $totalSks = $jadwals->sum('sks');

        // Calendar recurring events
        $calendarEvents = [];
        foreach ($jadwals as $j) {
            $calendarEvents[] = [
                'type' => 'recurring',
                'day' => $j->hari,
                'kelas_id' => $j->kelas_id,
                'mata_kuliah' => $j->mataKuliah?->nama,
                'kode' => $j->mataKuliah?->kode,
                'nama_kelas' => $j->nama_kelas,
                'jam' => $j->jam_rentang,
                'ruangan' => $j->ruangan,
                'tipe' => $j->tipe_kelas,
                'dosen' => $j->dosen_nama,
                'is_presensi_open' => (bool) $j->pertemuan_buka,
                'presensi_url' => $j->pertemuan_buka ? route('mahasiswa.presensi.show', $j->pertemuan_buka->id) : null,
            ];
        }

        // Dated pertemuan events
        $pertemuanEvents = [];
        foreach ($kelasList as $k) {
            foreach ($k->pertemuan as $p) {
                if ($p->tanggal) {
                    $pertemuanEvents[] = [
                        'date' => $p->tanggal->toDateString(),
                        'judul' => $p->judul ?? ('Pertemuan ke-'.$p->pertemuan_ke),
                        'pertemuan_ke' => $p->pertemuan_ke,
                        'mata_kuliah' => $k->mataKuliah?->nama,
                        'kelas_nama' => $k->nama_kelas,
                        'mode' => $p->mode?->value ?? 'offline',
                        'is_presensi_open' => (bool) $p->is_presensi_open,
                        'presensi_url' => $p->is_presensi_open ? route('mahasiswa.presensi.show', $p->id) : null,
                        'tautan_meeting' => $p->tautan_meeting,
                    ];
                }
            }
        }

        return view('dashboard.mahasiswa.jadwal', [
            'mahasiswa' => $mahasiswa,
            'jadwals' => $jadwals,
            'jadwalHariIni' => $jadwalHariIni,
            'todayHari' => $todayHari,
            'todayDate' => $todayDate,
            'totalSks' => $totalSks,
            'calendarEventsJson' => json_encode($calendarEvents),
            'pertemuanEventsJson' => json_encode($pertemuanEvents),
        ]);
    }
}

