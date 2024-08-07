<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    use HasFactory;
    
    protected $table = 'jurusan';
    protected $primaryKey = 'id_jurusan';
    protected $fillable = ['nama_jurusan', 'kode_jurusan'];

    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class, 'id_jurusan');
    }
}
