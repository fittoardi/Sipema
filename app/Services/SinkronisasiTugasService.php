<?php

namespace App\Services;

use App\Models\KelasMahasiswa;
use App\Models\Nilai;
use App\Models\PengumpulanTugas;
use App\Models\Tugas;

class SinkronisasiTugasService
{
    /**
     * Synchronize all assignment scores for a specific student in a class.
     */
    public function syncMahasiswa(KelasMahasiswa $kelasMahasiswa): ?Nilai
    {
        $kelasId = $kelasMahasiswa->kelas_id;
        $mahasiswaId = $kelasMahasiswa->mahasiswa_id;

        // Get all assignment IDs belonging to this class (via pertemuan)
        $tugasIds = Tugas::whereHas('pertemuan', fn ($q) => $q->where('kelas_id', $kelasId))->pluck('id');

        if ($tugasIds->isEmpty()) {
            return null;
        }

        // Calculate average of graded submissions
        $submissions = PengumpulanTugas::whereIn('tugas_id', $tugasIds)
            ->where('mahasiswa_id', $mahasiswaId)
            ->whereNotNull('nilai')
            ->get();

        if ($submissions->isEmpty()) {
            return null;
        }

        $averageScore = round($submissions->avg('nilai'), 2);

        $nilai = Nilai::firstOrNew([
            'kelas_mahasiswa_id' => $kelasMahasiswa->id,
        ]);

        $nilai->tugas = $averageScore;
        $nilai->recalculate();
        $nilai->save();

        return $nilai;
    }

    /**
     * Synchronize all students enrolled in a class after grading assignments.
     */
    public function syncKelas(int $kelasId): int
    {
        $enrolledStudents = KelasMahasiswa::where('kelas_id', $kelasId)->get();
        $count = 0;

        foreach ($enrolledStudents as $km) {
            if ($this->syncMahasiswa($km)) {
                $count++;
            }
        }

        return $count;
    }
}
