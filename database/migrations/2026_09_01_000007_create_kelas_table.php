<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliah')->restrictOnDelete();
            $table->foreignId('dosen_id')->constrained('dosen')->restrictOnDelete();
            $table->foreignId('tahun_akademik_id')->constrained('tahun_akademik')->restrictOnDelete();
            $table->foreignId('ruangan_id')->nullable()->constrained('ruangan')->restrictOnDelete();
            $table->string('nama_kelas', 50);
            $table->enum('tipe_kelas', ['offline', 'online', 'hybrid'])->default('offline');
            $table->enum('hari', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'])->index();
            $table->time('jam_mulai')->index();
            $table->time('jam_selesai')->index();
            $table->unsignedSmallInteger('kapasitas')->default(40);
            $table->timestamps();

            $table->unique(['mata_kuliah_id', 'tahun_akademik_id', 'nama_kelas']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};

