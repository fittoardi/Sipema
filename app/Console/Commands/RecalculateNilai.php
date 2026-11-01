<?php

namespace App\Console\Commands;

use App\Models\Nilai;
use Illuminate\Console\Command;

class RecalculateNilai extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nilai:recalculate {--kelas= : Filter berdasarkan ID kelas} {--dry-run : Simulasi tanpa menyimpan perubahan ke database}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menghitung ulang seluruh nilai akhir, grade, dan status kelulusan mahasiswa';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $kelasId = $this->option('kelas');
        $isDryRun = (bool) $this->option('dry-run');

        $query = Nilai::query();

        if ($kelasId) {
            $query->where('kelas_id', $kelasId);
        }

        $total = $query->count();
        $this->info("Ditemukan {$total} data nilai untuk dihitung ulang.");

        if ($isDryRun) {
            $this->warn('Mode DRY RUN aktif: data tidak akan disimpan ke database.');
        }

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $processed = 0;
        $query->chunk(100, function ($nilais) use ($isDryRun, $bar, &$processed): void {
            foreach ($nilais as $nilai) {
                $nilai->recalculate();

                if (! $isDryRun) {
                    $nilai->save();
                }

                $processed++;
                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine(2);

        $this->info("Berhasil memproses {$processed} data nilai.");

        return Command::SUCCESS;
    }
}
