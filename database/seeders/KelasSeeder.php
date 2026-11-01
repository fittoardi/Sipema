<?php

namespace Database\Seeders;

use App\Enums\Hari;
use App\Enums\ModePertemuan;
use App\Enums\TipeKelas;
use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\KelasMahasiswa;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Pertemuan;
use App\Models\Ruangan;
use App\Models\TahunAkademik;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        $tahunAktif = TahunAkademik::where('is_active', true)->first() ?? TahunAkademik::first();
        $dosens = Dosen::all();
        $courses = MataKuliah::all();
        $rooms = Ruangan::all();
        $andi = Mahasiswa::where('nim', '23110001')->first();
        $allStudents = Mahasiswa::all();

        $budi = Dosen::where('nidn', '0012345678')->first() ?? $dosens->first();
        $lab1 = Ruangan::where('kode', 'LAB-KOMP-01')->first() ?? $rooms->first();
        $rt301 = Ruangan::where('kode', 'RT-301')->first() ?? $rooms->first();

        $sampleClasses = [
            [
                'mata_kuliah_id' => $courses->where('kode', 'TI-201')->first()?->id ?? $courses->first()->id,
                'dosen_id' => $budi->id,
                'tahun_akademik_id' => $tahunAktif->id,
                'ruangan_id' => $lab1?->id,
                'nama_kelas' => 'TI-201-A',
                'tipe_kelas' => TipeKelas::Offline->value,
                'hari' => Hari::Senin->value,
                'jam_mulai' => '08:00:00',
                'jam_selesai' => '10:30:00',
                'kapasitas' => 40,
            ],
            [
                'mata_kuliah_id' => $courses->where('kode', 'TI-202')->first()?->id ?? $courses->skip(1)->first()?->id ?? $courses->first()->id,
                'dosen_id' => $budi->id,
                'tahun_akademik_id' => $tahunAktif->id,
                'ruangan_id' => $lab1?->id,
                'nama_kelas' => 'TI-202-A',
                'tipe_kelas' => TipeKelas::Hybrid->value,
                'hari' => Hari::Selasa->value,
                'jam_mulai' => '10:30:00',
                'jam_selesai' => '13:00:00',
                'kapasitas' => 40,
            ],
            [
                'mata_kuliah_id' => $courses->where('kode', 'TI-203')->first()?->id ?? $courses->skip(2)->first()?->id ?? $courses->first()->id,
                'dosen_id' => $dosens->skip(1)->first()?->id ?? $budi->id,
                'tahun_akademik_id' => $tahunAktif->id,
                'ruangan_id' => $rt301?->id,
                'nama_kelas' => 'TI-203-A',
                'tipe_kelas' => TipeKelas::Offline->value,
                'hari' => Hari::Rabu->value,
                'jam_mulai' => '08:00:00',
                'jam_selesai' => '10:30:00',
                'kapasitas' => 40,
            ],
            [
                'mata_kuliah_id' => $courses->where('kode', 'TI-204')->first()?->id ?? $courses->skip(3)->first()?->id ?? $courses->first()->id,
                'dosen_id' => $dosens->skip(2)->first()?->id ?? $budi->id,
                'tahun_akademik_id' => $tahunAktif->id,
                'ruangan_id' => $lab1?->id,
                'nama_kelas' => 'TI-204-A',
                'tipe_kelas' => TipeKelas::Offline->value,
                'hari' => Hari::Kamis->value,
                'jam_mulai' => '13:00:00',
                'jam_selesai' => '15:30:00',
                'kapasitas' => 35,
            ],
        ];

        foreach ($sampleClasses as $classData) {
            $kelas = Kelas::updateOrCreate(
                [
                    'mata_kuliah_id' => $classData['mata_kuliah_id'],
                    'tahun_akademik_id' => $classData['tahun_akademik_id'],
                    'nama_kelas' => $classData['nama_kelas'],
                ],
                $classData,
            );

            // Generate 16 sessions if not present
            if ($kelas->pertemuan()->count() === 0) {
                $baseDate = now()->subWeeks(4)->startOfWeek();
                for ($p = 1; $p <= 16; $p++) {
                    $sessionDate = (clone $baseDate)->addWeeks($p - 1);
                    Pertemuan::create([
                        'kelas_id' => $kelas->id,
                        'pertemuan_ke' => $p,
                        'judul' => "Pertemuan ke-{$p}: Topik Bahasan Pembelajaran {$p}",
                        'mode' => $p % 4 === 0 ? ModePertemuan::Online->value : ModePertemuan::Offline->value,
                        'ruangan_id' => $kelas->ruangan_id,
                        'tautan_meeting' => $p % 4 === 0 ? 'https://meet.google.com/abc-defg-hij' : null,
                        'tanggal' => $sessionDate->toDateString(),
                        'berita_acara' => $p <= 4 ? "Telah terlaksana perkuliahan sesi {$p} materi pembelajaran berjalan lancar." : null,
                        'is_published' => true,
                        'is_presensi_open' => $p === 5,
                        'wajib_selfie' => true,
                        'wajib_gps' => true,
                        'kode_presensi' => $p === 5 ? 'SIELMA' : null,
                    ]);
                }
            }

            // Enroll Andi Pratama and a few random students
            if ($andi) {
                KelasMahasiswa::firstOrCreate([
                    'kelas_id' => $kelas->id,
                    'mahasiswa_id' => $andi->id,
                ]);
            }

            $peers = $allStudents->where('id', '!=', $andi?->id)->random(min(10, $allStudents->count() - 1));
            foreach ($peers as $peer) {
                KelasMahasiswa::firstOrCreate([
                    'kelas_id' => $kelas->id,
                    'mahasiswa_id' => $peer->id,
                ]);
            }
        }
    }
}
