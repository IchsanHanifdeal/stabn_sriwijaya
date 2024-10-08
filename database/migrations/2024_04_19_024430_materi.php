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
        Schema::create('materi', function (Blueprint $table) {
            $table->id('id_materi');
            $table->enum('tipe_materi', ['gambar', 'dokumen', 'video']);
            $table->string('file_materi')->nullable();
            $table->string('pertemuan');
            $table->string('judul_materi');
            $table->string('deskripsi');
            $table -> unsignedBigInteger('id_matakuliah');
            $table -> foreign('id_matakuliah')->references('id_matakuliah')->on('mata_kuliah')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materi');
    }
};
