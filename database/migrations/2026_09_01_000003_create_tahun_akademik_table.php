<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tahun_akademik', function (Blueprint $table) {
            $table->id();
            $table->string('tahun', 20)->index();
            $table->enum('semester', ['Ganjil', 'Genap'])->index();
            $table->boolean('is_active')->default(false)->index();
            $table->boolean('is_krs_open')->default(false);
            $table->date('krs_mulai')->nullable();
            $table->date('krs_selesai')->nullable();
            $table->unsignedSmallInteger('maks_sks_default')->default(24);
            $table->timestamps();

            $table->unique(['tahun', 'semester']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tahun_akademik');
    }
};

