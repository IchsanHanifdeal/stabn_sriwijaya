<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    use HasFactory;
    protected $table = 'tugas';
    protected $primaryKey = 'id_tugas';
    protected $fillable = [
        'id_matakuliah',
        'judul_tugas',
        'deskripsi',
        'lampiran_tugas',
        'pertemuan',
    ];

    public function matakuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'id_matakuliah');
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'id_tugas', 'id_tugas');
    }
}
