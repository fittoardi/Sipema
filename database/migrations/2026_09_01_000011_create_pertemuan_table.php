<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pertemuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->unsignedSmallInteger('pertemuan_ke');
            $table->string('judul', 255);
            $table->enum('mode', ['offline', 'online'])->default('offline');
            $table->foreignId('ruangan_id')->nullable()->constrained('ruangan')->restrictOnDelete();
            $table->string('tautan_meeting', 255)->nullable();
            $table->date('tanggal')->index();
            $table->text('berita_acara')->nullable();
            $table->boolean('is_published')->default(true);
            $table->boolean('is_presensi_open')->default(false);
            $table->boolean('wajib_selfie')->default(true);
            $table->boolean('wajib_gps')->default(true);
            $table->string('kode_presensi', 10)->nullable();
            $table->timestamps();

            $table->unique(['kelas_id', 'pertemuan_ke']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pertemuan');
    }
};

