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
    Schema::create('nilais', function (Blueprint $table) {

        $table->id();

        $table->foreignId('mahasiswa_id')
            ->constrained()
            ->cascadeOnUpdate()
            ->cascadeOnDelete();

        $table->foreignId('mata_kuliah_id')
            ->constrained()
            ->cascadeOnUpdate()
            ->restrictOnDelete();

        $table->double('tugas')->default(0);

        $table->double('uts')->default(0);

        $table->double('uas')->default(0);

        $table->double('nilai_akhir')->nullable();

        $table->string('grade')->nullable();

        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilais');
    }
};
