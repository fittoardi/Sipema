<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skala_grade', function (Blueprint $table) {
            $table->id();
            $table->string('grade', 5)->unique();
            $table->decimal('nilai_min', 5, 2);
            $table->boolean('is_lulus')->default(false);
            $table->unsignedSmallInteger('urutan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skala_grade');
    }
};

