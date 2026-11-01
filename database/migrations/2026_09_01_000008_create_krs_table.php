<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('krs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->restrictOnDelete();
            $table->foreignId('tahun_akademik_id')->constrained('tahun_akademik')->restrictOnDelete();
            $table->unsignedSmallInteger('total_sks')->default(0);
            $table->enum('status', ['draft', 'diajukan', 'revisi', 'disetujui'])->default('draft')->index();
            $table->text('catatan_wali')->nullable();
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users')->restrictOnDelete();
            $table->timestamp('disetujui_pada')->nullable();
            $table->timestamps();

            $table->unique(['mahasiswa_id', 'tahun_akademik_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('krs');
    }
};
