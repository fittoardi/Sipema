<?php

namespace App\Services;

use App\Enums\StatusKrs;
use App\Models\Kelas;
use App\Models\KelasMahasiswa;
use App\Models\Krs;
use App\Models\KrsDetail;
use App\Models\Mahasiswa;
use App\Models\TahunAkademik;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class KrsService
{
    public function __construct(
        protected JadwalConflictDetector $conflictDetector,
    ) {}

    /**
     * Get existing KRS for the student and academic year, or create a draft.
     */
    public function getOrCreateDraft(Mahasiswa $mahasiswa, TahunAkademik $tahunAkademik): Krs
    {
        return Krs::firstOrCreate(
            [
                'mahasiswa_id' => $mahasiswa->id,
                'tahun_akademik_id' => $tahunAkademik->id,
            ],
            [
                'total_sks' => 0,
                'status' => StatusKrs::Draft,
            ]
        );
    }

    /**
     * Add a class to student's KRS draft with SKS limit, quota lock, and collision checks.
     *
     * @return array{success: bool, message: string}
     */
    public function addClass(Krs $krs, int $kelasId): array
    {
        if ($krs->status !== StatusKrs::Draft && $krs->status !== StatusKrs::Revisi) {
            return [
                'success' => false,
                'message' => 'KRS sudah diajukan atau disetujui sehingga tidak dapat diubah.',
            ];
        }

        return DB::transaction(function () use ($krs, $kelasId): array {
            // Check if already added
            $alreadyExists = KrsDetail::where('krs_id', $krs->id)
                ->where('kelas_id', $kelasId)
                ->exists();

            if ($alreadyExists) {
                return [
                    'success' => false,
                    'message' => 'Mata kuliah / kelas ini sudah ada dalam rencana studi Anda.',
                ];
            }

            // Lock class for quota verification
            $kelas = Kelas::query()
                ->with('mataKuliah')
                ->lockForUpdate()
                ->find($kelasId);

            if (! $kelas) {
                return [
                    'success' => false,
                    'message' => 'Kelas tidak ditemukan.',
                ];
            }

            // Check if same course is already taken in another class
            $existingSameCourse = KrsDetail::query()
                ->where('krs_id', $krs->id)
                ->whereHas('kelas', fn ($q) => $q->where('mata_kuliah_id', $kelas->mata_kuliah_id))
                ->exists();

            if ($existingSameCourse) {
                return [
                    'success' => false,
                    'message' => 'Mata kuliah '.$kelas->mataKuliah->nama.' sudah diambil di kelas lain.',
                ];
            }

            // Check schedule collision
            $conflict = $this->conflictDetector->checkKrsConflict($krs->id, $kelas);
            if ($conflict) {
                return [
                    'success' => false,
                    'message' => "Jadwal bentrok dengan {$conflict->nama_lengkap} pada hari {$conflict->hari} jam {$conflict->jam_rentang}.",
                ];
            }

            // Check SKS limit
            $maxSks = $krs->tahunAkademik->maks_sks_default ?? 24;
            $newTotalSks = $krs->total_sks + ($kelas->sks ?? 3);

            if ($newTotalSks > $maxSks) {
                return [
                    'success' => false,
                    'message' => "Batas maksimal SKS ({$maxSks} SKS) terlampaui. Total rencana: {$newTotalSks} SKS.",
                ];
            }

            // Check quota
            $enrolledCount = KelasMahasiswa::where('kelas_id', $kelasId)->count();
            if ($enrolledCount >= $kelas->kapasitas) {
                return [
                    'success' => false,
                    'message' => "Kapasitas kelas ({$kelas->kapasitas} kursi) sudah penuh.",
                ];
            }

            // Insert detail and recalculate SKS
            KrsDetail::create([
                'krs_id' => $krs->id,
                'kelas_id' => $kelas->id,
            ]);

            $this->recalculateTotalSks($krs);

            return [
                'success' => true,
                'message' => "Kelas {$kelas->nama_lengkap} berhasil ditambahkan ke KRS.",
            ];
        });
    }

    /**
     * Remove a class from student's KRS draft.
     */
    public function removeClass(Krs $krs, int $kelasId): void
    {
        if ($krs->status !== StatusKrs::Draft && $krs->status !== StatusKrs::Revisi) {
            throw new InvalidArgumentException('KRS tidak dapat diubah pada status saat ini.');
        }

        KrsDetail::where('krs_id', $krs->id)
            ->where('kelas_id', $kelasId)
            ->delete();

        $this->recalculateTotalSks($krs);
    }

    /**
     * Submit KRS for advisor approval.
     *
     * @return array{success: bool, message: string}
     */
    public function submitKrs(Krs $krs): array
    {
        if ($krs->details()->count() === 0) {
            return [
                'success' => false,
                'message' => 'KRS masih kosong. Silakan pilih minimal 1 mata kuliah.',
            ];
        }

        $krs->status = StatusKrs::Diajukan;
        $krs->save();

        return [
            'success' => true,
            'message' => 'KRS berhasil diajukan kepada Dosen Pembimbing Akademik.',
        ];
    }

    /**
     * Dosen approves KRS. Creates active enrolments in kelas_mahasiswa.
     */
    public function approveKrs(Krs $krs, int $approverUserId): void
    {
        DB::transaction(function () use ($krs, $approverUserId): void {
            $krs->status = StatusKrs::Disetujui;
            $krs->disetujui_oleh = $approverUserId;
            $krs->disetujui_pada = now();
            $krs->catatan_wali = null;
            $krs->save();

            // Sync to kelas_mahasiswa
            $classIds = $krs->details()->pluck('kelas_id');
            foreach ($classIds as $classId) {
                KelasMahasiswa::firstOrCreate(
                    [
                        'kelas_id' => $classId,
                        'mahasiswa_id' => $krs->mahasiswa_id,
                    ],
                    [
                        'krs_id' => $krs->id,
                    ]
                );
            }
        });
    }

    /**
     * Dosen requests revision for KRS.
     */
    public function reviseKrs(Krs $krs, string $catatan): void
    {
        $krs->status = StatusKrs::Revisi;
        $krs->catatan_wali = $catatan;
        $krs->save();
    }

    /**
     * Recalculate total SKS for a KRS.
     */
    public function recalculateTotalSks(Krs $krs): int
    {
        $krs->load('details.kelas.mataKuliah');

        $total = $krs->details->sum(function ($detail) {
            return $detail->kelas?->sks ?? 0;
        });

        $krs->total_sks = $total;
        $krs->saveQuietly();

        return $total;
    }
}

