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
        Schema::create('absensi', function (Blueprint $table) {
            $table->id('id_absensi');
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
            $table->date('tanggal');
            $table->time('waktu');
            $table->string('lokasi');
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('foto');
            $table->string('status');
            $table->string('pertemuan');
            $table->longText('keterangan')->nullable();
            $table->timestamps();
            $table->unique(['id_user', 'pertemuan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensi', function (Blueprint $table) {
            $table->dropUnique(['id_user', 'pertemuan']);
        });

        Schema::dropIfExists('absensi');
    }
};
