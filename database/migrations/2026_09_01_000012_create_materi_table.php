<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pertemuan_id')->constrained('pertemuan')->cascadeOnDelete();
            $table->string('judul', 255);
            $table->enum('tipe', ['file', 'link', 'video', 'teks', 'url'])->default('file');
            $table->text('konten_atau_url')->nullable();
            $table->string('file_path', 255)->nullable();
            $table->unsignedSmallInteger('urutan')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materi');
    }
};
