<?php

namespace App\Services;

use App\Models\Kelas;
use App\Models\KrsDetail;

class JadwalConflictDetector
{
    /**
     * Check if two time intervals on the same day overlap.
     * Intervals: [startA, endA) and [startB, endB)
     * Overlap condition: startA < endB && endA > startB
     */
    public function hasTimeOverlap(string $startA, string $endA, string $startB, string $endB): bool
    {
        $sA = substr($startA, 0, 5);
        $eA = substr($endA, 0, 5);
        $sB = substr($startB, 0, 5);
        $eB = substr($endB, 0, 5);

        return ($sA < $eB) && ($eA > $sB);
    }

    /**
     * Check if a candidate class conflicts with existing classes chosen in student's KRS.
     */
    public function checkKrsConflict(int $krsId, Kelas $candidateClass): ?Kelas
    {
        $existingClasses = Kelas::query()
            ->whereIn('id', KrsDetail::where('krs_id', $krsId)->pluck('kelas_id'))
            ->where('hari', $candidateClass->hari)
            ->get();

        foreach ($existingClasses as $existing) {
            if ($existing->id === $candidateClass->id) {
                continue;
            }

            if ($this->hasTimeOverlap(
                $candidateClass->jam_mulai,
                $candidateClass->jam_selesai,
                $existing->jam_mulai,
                $existing->jam_selesai
            )) {
                return $existing;
            }
        }

        return null;
    }

    /**
     * Check if a room is already occupied during the requested day and time slot.
     */
    public function checkRoomConflict(
        int $ruanganId,
        string $hari,
        string $jamMulai,
        string $jamSelesai,
        int $tahunAkademikId,
        ?int $ignoreKelasId = null
    ): ?Kelas {
        $query = Kelas::query()
            ->where('ruangan_id', $ruanganId)
            ->where('tahun_akademik_id', $tahunAkademikId)
            ->where('hari', $hari);

        if ($ignoreKelasId) {
            $query->where('id', '!=', $ignoreKelasId);
        }

        $candidates = $query->get();

        foreach ($candidates as $candidate) {
            if ($this->hasTimeOverlap(
                $jamMulai,
                $jamSelesai,
                $candidate->jam_mulai,
                $candidate->jam_selesai
            )) {
                return $candidate;
            }
        }

        return null;
    }
}
