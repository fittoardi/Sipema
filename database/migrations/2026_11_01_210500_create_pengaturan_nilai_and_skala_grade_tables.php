<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('pengaturan_nilais')) {
            Schema::create('pengaturan_nilais', function (Blueprint $table): void {
                $table->id();
                $table->string('komponen')->unique();
                $table->decimal('bobot', 5, 2);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('skala_grades')) {
            Schema::create('skala_grades', function (Blueprint $table): void {
                $table->id();
                $table->string('grade', 5)->unique();
                $table->decimal('nilai_min', 5, 2);
                $table->boolean('is_lulus')->default(true);
                $table->unsignedInteger('urutan')->default(1);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('tahun_akademiks')) {
            Schema::create('tahun_akademiks', function (Blueprint $table): void {
                $table->id();
                $table->string('tahun', 20);
                $table->string('semester', 20);
                $table->boolean('is_active')->default(false);
                $table->timestamps();

                $table->unique(['tahun', 'semester']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tahun_akademiks');
        Schema::dropIfExists('skala_grades');
        Schema::dropIfExists('pengaturan_nilais');
    }
};
