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
        Schema::create('tugas', function (Blueprint $table){
            $table -> id('id_tugas');
            $table -> unsignedBigInteger('id_matakuliah');
            $table -> foreign('id_matakuliah')->references('id_matakuliah')->on('mata_kuliah')->onDelete('cascade');
            $table -> string('judul_tugas');
            $table -> string('deskripsi');
            $table -> string('lampiran_tugas')->nullable();
            $table -> timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugas');
    }
};
