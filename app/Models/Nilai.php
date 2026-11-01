<?php

namespace App\Models;

use App\Enums\StatusNilai;
use Illuminate\Database\Eloquent\Attributes\UseEloquentBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[UseEloquentBuilder(Builder::class)]
class Nilai extends Model
{
    use HasFactory;

    protected $table = 'nilai';

    protected $tempKelasId;
    protected $tempMahasiswaId;

    protected $fillable = [
        'kelas_mahasiswa_id',
        'kelas_id',
        'mahasiswa_id',
        'kuis',
        'tugas',
        'uts',
        'uas',
        'nilai_akhir',
        'grade',
        'status',
        'updated_by',
    ];

    public function setStatusAttribute($value): void
    {
        if (is_string($value)) {
            $lower = strtolower($value);
            if ($lower === 'lulus') {
                $value = StatusNilai::Lulus;
            } elseif ($lower === 'tidak lulus' || $lower === 'tidak_lulus') {
                $value = StatusNilai::TidakLulus;
            } elseif ($lower === 'belum lengkap' || $lower === 'belum_lengkap') {
                $value = StatusNilai::BelumLengkap;
            }
        }

        $this->attributes['status'] = $value instanceof StatusNilai ? $value->value : $value;
    }

    public function setKelasIdAttribute($value): void
    {
        $this->tempKelasId = $value;
        $this->resolveKelasMahasiswa();
    }

    public function setMahasiswaIdAttribute($value): void
    {
        $this->tempMahasiswaId = $value;
        $this->resolveKelasMahasiswa();
    }

    protected function resolveKelasMahasiswa(): void
    {
        if (! empty($this->tempKelasId) && ! empty($this->tempMahasiswaId)) {
            $km = KelasMahasiswa::firstOrCreate([
                'kelas_id' => $this->tempKelasId,
                'mahasiswa_id' => $this->tempMahasiswaId,
            ]);
            $this->attributes['kelas_mahasiswa_id'] = $km->id;
        }
    }

    protected function casts(): array
    {
        return [
            'kuis' => 'float',
            'tugas' => 'float',
            'uts' => 'float',
            'uas' => 'float',
            'nilai_akhir' => 'float',
            'status' => StatusNilai::class,
        ];
    }

    public function kelasMahasiswa(): BelongsTo
    {
        return $this->belongsTo(KelasMahasiswa::class, 'kelas_mahasiswa_id');
    }

    public function getKelasAttribute()
    {
        return $this->kelasMahasiswa?->kelas;
    }

    public function getMahasiswaAttribute()
    {
        return $this->kelasMahasiswa?->mahasiswa;
    }

    public function getMahasiswaIdAttribute()
    {
        return $this->kelasMahasiswa?->mahasiswa_id;
    }

    public function getKelasIdAttribute()
    {
        return $this->kelasMahasiswa?->kelas_id;
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function recalculate(): static
    {
        if ($this->kuis === null || $this->tugas === null || $this->uts === null || $this->uas === null) {
            $this->nilai_akhir = null;
            $this->grade = null;
            $this->status = StatusNilai::BelumLengkap;

            return $this;
        }

        $bobotKuis = PengaturanNilai::getBobot('kuis', 10);
        $bobotTugas = PengaturanNilai::getBobot('tugas', 20);
        $bobotUts = PengaturanNilai::getBobot('uts', 30);
        $bobotUas = PengaturanNilai::getBobot('uas', 40);

        $totalBobot = $bobotKuis + $bobotTugas + $bobotUts + $bobotUas;
        if ($totalBobot <= 0) {
            $totalBobot = 100;
        }

        $rawScore = (
            ($this->kuis * $bobotKuis) +
            ($this->tugas * $bobotTugas) +
            ($this->uts * $bobotUts) +
            ($this->uas * $bobotUas)
        ) / $totalBobot;

        $this->nilai_akhir = round($rawScore, 2);

        $gradeRecord = SkalaGrade::determineGrade($this->nilai_akhir);
        if ($gradeRecord) {
            $this->grade = $gradeRecord->grade;
            $this->status = $gradeRecord->is_lulus ? StatusNilai::Lulus : StatusNilai::TidakLulus;
        } else {
            $this->grade = 'E';
            $this->status = StatusNilai::TidakLulus;
        }

        return $this;
    }
}
