<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan_nilai', function (Blueprint $table) {
            $table->id();
            $table->enum('komponen', ['kuis', 'tugas', 'uts', 'uas'])->unique();
            $table->decimal('bobot', 5, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan_nilai');
    }
};
