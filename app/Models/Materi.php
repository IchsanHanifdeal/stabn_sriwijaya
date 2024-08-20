<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    use HasFactory;
    protected $table = 'materi';
    protected $primaryKey = 'id_materi';
    protected $fillable = ['tipe_materi', 'file_materi', 'judul_materi', 'deskripsi', 'pertemuan', 'id_matakuliah'];

    public function matakuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'id_matakuliah');
    }
}
