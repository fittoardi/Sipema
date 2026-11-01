<?php

namespace App\Services;

use App\Enums\MetodePresensi;
use App\Enums\ModePertemuan;
use App\Enums\StatusPresensi;
use App\Models\Mahasiswa;
use App\Models\Pertemuan;
use App\Models\Presensi;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;

class PresensiService
{
    public function __construct(
        protected GeoDistanceCalculator $geoCalculator,
    ) {}

    /**
     * Process student self attendance.
     *
     * @param  array{
     *     latitude?: float|null,
     *     longitude?: float|null,
     *     akurasi_meter?: float|null,
     *     kode_presensi?: string|null,
     *     foto_selfie?: UploadedFile|string|null,
     *     ip_address?: string|null,
     *     user_agent?: string|null
     * }  $data
     * @return array{success: bool, message: string, presensi?: Presensi}
     */
    public function submitMandiri(Pertemuan $pertemuan, Mahasiswa $mahasiswa, array $data): array
    {
        // 1. Check if attendance is open
        if (! $pertemuan->is_presensi_open) {
            return [
                'success' => false,
                'message' => 'Presensi untuk pertemuan ini sedang ditutup oleh dosen pengampu.',
            ];
        }

        // 2. Check if student already attended
        $existing = Presensi::where('pertemuan_id', $pertemuan->id)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->first();

        if ($existing) {
            return [
                'success' => false,
                'message' => 'Anda sudah melakukan presensi pada pertemuan ini.',
                'presensi' => $existing,
            ];
        }

        // 3. Verify Passcode if required
        if (! empty($pertemuan->kode_presensi)) {
            $inputCode = strtoupper(trim((string) ($data['kode_presensi'] ?? '')));
            if ($inputCode !== strtoupper(trim($pertemuan->kode_presensi))) {
                return [
                    'success' => false,
                    'message' => 'Kode presensi tidak sesuai dengan yang diumumkan dosen.',
                ];
            }
        }

        // 4. Geofencing check for Offline sessions
        $isDalamRadius = true;
        $jarakMeter = 0.0;
        $lat = isset($data['latitude']) ? (float) $data['latitude'] : null;
        $lon = isset($data['longitude']) ? (float) $data['longitude'] : null;

        if ($pertemuan->mode === ModePertemuan::Offline || $pertemuan->wajib_gps) {
            $ruangan = $pertemuan->ruangan ?? $pertemuan->kelas?->ruangan;

            if ($ruangan && $ruangan->latitude && $ruangan->longitude) {
                if ($lat === null || $lon === null) {
                    return [
                        'success' => false,
                        'message' => 'Pertemuan ini mewajibkan GPS. Mohon izinkan akses lokasi di peramban Anda.',
                    ];
                }

                $jarakMeter = $this->geoCalculator->calculate(
                    $lat,
                    $lon,
                    (float) $ruangan->latitude,
                    (float) $ruangan->longitude
                );

                $allowedRadius = (float) $ruangan->radius_meter;
                // Allow GPS accuracy tolerance up to 25 meters
                $isDalamRadius = ($jarakMeter <= ($allowedRadius + 25.0));

                if (! $isDalamRadius) {
                    return [
                        'success' => false,
                        'message' => "Posisi Anda berada di luar radius ruangan {$ruangan->nama} (Jarak Anda: {$jarakMeter}m, Maksimal: {$allowedRadius}m).",
                    ];
                }
            }
        }

        // 5. Selfie Photo Storage
        $fotoPath = null;
        if ($pertemuan->wajib_selfie) {
            if (empty($data['foto_selfie'])) {
                return [
                    'success' => false,
                    'message' => 'Foto bukti selfie wajah wajib diambil melalui kamera langsung.',
                ];
            }

            $fotoPath = $this->storeSelfiePhoto($data['foto_selfie'], $pertemuan->id, $mahasiswa->id);
        }

        // 6. Create Presensi Record
        $presensi = Presensi::create([
            'pertemuan_id' => $pertemuan->id,
            'mahasiswa_id' => $mahasiswa->id,
            'status' => StatusPresensi::Hadir,
            'foto_selfie_path' => $fotoPath,
            'latitude' => $lat,
            'longitude' => $lon,
            'akurasi_meter' => isset($data['akurasi_meter']) ? (float) $data['akurasi_meter'] : null,
            'jarak_meter' => $jarakMeter,
            'is_dalam_radius' => $isDalamRadius,
            'metode' => MetodePresensi::Mandiri,
            'ip_address' => $data['ip_address'] ?? null,
            'user_agent' => $data['user_agent'] ?? null,
            'keterangan' => 'Presensi mandiri via SIELMA',
            'dicatat_pada' => now(),
        ]);

        return [
            'success' => true,
            'message' => 'Presensi berhasil dicatat. Terima kasih!',
            'presensi' => $presensi,
        ];
    }

    /**
     * Dosen manual update of student attendance.
     */
    public function updateManualDosen(
        Pertemuan $pertemuan,
        Mahasiswa $mahasiswa,
        StatusPresensi $status,
        ?string $keterangan = null
    ): Presensi {
        return Presensi::updateOrCreate(
            [
                'pertemuan_id' => $pertemuan->id,
                'mahasiswa_id' => $mahasiswa->id,
            ],
            [
                'status' => $status,
                'metode' => MetodePresensi::ManualDosen,
                'keterangan' => $keterangan ?? 'Diverifikasi/diubah oleh dosen',
                'dicatat_pada' => now(),
            ]
        );
    }

    /**
     * Store selfie photo to private storage (base64 data URI or UploadedFile).
     */
    protected function storeSelfiePhoto(UploadedFile|string $photo, int $pertemuanId, int $mahasiswaId): string
    {
        $filename = "selfie_{$pertemuanId}_{$mahasiswaId}_".time().'.jpg';
        $relativeDir = 'presensi';
        $fullPath = "{$relativeDir}/{$filename}";

        if ($photo instanceof UploadedFile) {
            Storage::disk('private')->putFileAs($relativeDir, $photo, $filename);
        } elseif (is_string($photo)) {
            // Check if base64 data URI
            if (str_starts_with($photo, 'data:image')) {
                $parts = explode(',', $photo, 2);
                $imageData = base64_decode($parts[1] ?? '', true);
            } else {
                $imageData = base64_decode($photo, true);
            }

            if ($imageData === false) {
                throw new InvalidArgumentException('Format foto base64 tidak valid.');
            }

            Storage::disk('private')->put($fullPath, $imageData);
        }

        return $fullPath;
    }
}

