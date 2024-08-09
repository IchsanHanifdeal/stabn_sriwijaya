<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekapNilai extends Model
{
    use HasFactory;
    protected $table = 'rekap_nilai';
    protected $primaryKey = 'id_rekap_nilai';
    protected $fillable = ['id_mahasiswa', 'id_matakuliah', 'nilai_kuis', 'nilai_tugas', 'nilai_uts', 'nilai_uas'];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa');
    }
    
    public function matakuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'id_matakuliah');
    }
}
