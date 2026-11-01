<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengumpulan_tugas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_id')->constrained('tugas')->cascadeOnDelete();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->restrictOnDelete();
            $table->string('file_path', 255);
            $table->text('catatan_mahasiswa')->nullable();
            $table->dateTime('dikumpulkan_pada');
            $table->boolean('is_terlambat')->default(false);
            $table->decimal('nilai', 5, 2)->nullable();
            $table->text('umpan_balik')->nullable();
            $table->timestamps();

            $table->unique(['tugas_id', 'mahasiswa_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengumpulan_tugas');
    }
};
