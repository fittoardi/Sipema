<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mata_kuliah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_studi_id')->constrained('program_studi')->restrictOnDelete();
            $table->string('kode', 50)->unique();
            $table->string('nama', 255);
            $table->unsignedSmallInteger('sks')->default(3);
            $table->unsignedSmallInteger('semester_paket')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mata_kuliah');
    }
};

