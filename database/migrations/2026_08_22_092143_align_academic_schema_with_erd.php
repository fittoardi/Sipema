<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('dosens') && ! Schema::hasColumn('dosens', 'nama')) {
            Schema::table('dosens', fn (Blueprint $table) => $table->string('nama')->nullable()->after('user_id'));
        }

        if (Schema::hasTable('mahasiswas') && ! Schema::hasColumn('mahasiswas', 'nama')) {
            Schema::table('mahasiswas', fn (Blueprint $table) => $table->string('nama')->nullable()->after('user_id'));
        }

        if (Schema::hasTable('mata_kuliahs')) {
            if (Schema::hasColumn('mata_kuliahs', 'kode_mk') && ! Schema::hasColumn('mata_kuliahs', 'kode')) {
                Schema::table('mata_kuliahs', fn (Blueprint $table) => $table->renameColumn('kode_mk', 'kode'));
            }

            if (Schema::hasColumn('mata_kuliahs', 'nama_mk') && ! Schema::hasColumn('mata_kuliahs', 'nama')) {
                Schema::table('mata_kuliahs', fn (Blueprint $table) => $table->renameColumn('nama_mk', 'nama'));
            }
        }

        if (Schema::hasTable('kelas')) {
            Schema::table('kelas', function (Blueprint $table): void {
                if (! Schema::hasColumn('kelas', 'mata_kuliah_id')) {
                    $table->foreignId('mata_kuliah_id')->nullable()->after('id')->constrained('mata_kuliahs')->nullOnDelete();
                }

                if (! Schema::hasColumn('kelas', 'dosen_id')) {
                    $table->foreignId('dosen_id')->nullable()->after('mata_kuliah_id')->constrained('dosens')->nullOnDelete();
                }

                if (! Schema::hasColumn('kelas', 'nama')) {
                    $table->string('nama')->nullable()->after('dosen_id');
                }

                if (! Schema::hasColumn('kelas', 'tahun_akademik')) {
                    $table->string('tahun_akademik')->nullable();
                }

                if (! Schema::hasColumn('kelas', 'semester')) {
                    $table->enum('semester', ['Ganjil', 'Genap'])->nullable();
                }
            });
        }

        if (Schema::hasTable('nilais')) {
            Schema::table('nilais', function (Blueprint $table): void {
                if (! Schema::hasColumn('nilais', 'kelas_id')) {
                    $table->foreignId('kelas_id')->nullable()->after('id')->constrained('kelas')->nullOnDelete();
                }

                if (! Schema::hasColumn('nilais', 'kuis')) {
                    $table->decimal('kuis', 5, 2)->default(0);
                }

                if (! Schema::hasColumn('nilais', 'status')) {
                    $table->string('status')->nullable();
                }
            });
        }

        if (! Schema::hasTable('kelas_mahasiswa')) {
            Schema::create('kelas_mahasiswa', function (Blueprint $table): void {
                $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
                $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->cascadeOnDelete();
                $table->timestamps();
                $table->primary(['kelas_id', 'mahasiswa_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Existing production data must not be destructively rolled back.
    }
};
