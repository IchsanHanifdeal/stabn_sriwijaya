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
        Schema::create('nilai', function (Blueprint $table) {
            $table->id('id_nilai');
            $table->unsignedBigInteger('id_tugas');
            $table->foreign('id_tugas')->references('id_tugas')->on('tugas')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('id_matakuliah');
            $table->foreign('id_matakuliah')->references('id_matakuliah')->on('mata_kuliah')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('id_mahasiswa');
            $table->foreign('id_mahasiswa')->references('id_mahasiswa')->on('mahasiswa')->onDelete('cascade')->onUpdate('cascade');
            $table->date('tanggal_pengumpulan');
            $table->string('file');
            $table->string('komentar')->nullable();
            $table->enum('keterangan', ['dinilai', 'belum dinilai'])->default('belum dinilai');
            $table->integer('nilai')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai');
    }
};
