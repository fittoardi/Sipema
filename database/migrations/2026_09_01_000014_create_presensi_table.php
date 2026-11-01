<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pertemuan_id')->constrained('pertemuan')->cascadeOnDelete();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->restrictOnDelete();
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpha'])->index();
            $table->string('foto_selfie_path', 255)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->decimal('akurasi_meter', 8, 2)->nullable();
            $table->decimal('jarak_meter', 8, 2)->nullable();
            $table->boolean('is_dalam_radius')->default(false);
            $table->enum('metode', ['mandiri', 'manual_dosen'])->default('mandiri');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamp('dicatat_pada');
            $table->timestamps();

            $table->unique(['pertemuan_id', 'mahasiswa_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presensi');
    }
};

