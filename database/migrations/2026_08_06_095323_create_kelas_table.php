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
        Schema::create('kelas', function (Blueprint $table) {

            $table->id();

            $table->foreignId('mata_kuliah_id')
                ->constrained('mata_kuliahs')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('dosen_id')
                ->constrained('dosens')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('nama');

            $table->string('tahun_akademik');

            $table->enum('semester', ['Ganjil', 'Genap']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
