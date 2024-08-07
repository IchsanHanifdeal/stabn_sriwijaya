<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;
    protected $table = 'nilai';
    protected $primaryKey = 'id_nilai';
    protected $fillable = ['id_tugas', 'id_matakuliah', 'id_mahasiswa', 'file', 'keterangan', 'tanggal_pengumpulan', 'nilai', 'komentar'];
    
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa');
    }
}

