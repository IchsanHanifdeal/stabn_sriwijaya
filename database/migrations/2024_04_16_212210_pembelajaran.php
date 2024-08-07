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
        Schema::create('silabus', function (Blueprint $table) {
            $table->id('id_silabus');
            $table->string('file_silabus');
            $table->enum('tipe_file', ['gambar', 'dokumen', 'video']);
            $table->string('nama_silabus');
            $table->string('deskripsi');
            $table->string('pertemuan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('silabus');
    }
};
