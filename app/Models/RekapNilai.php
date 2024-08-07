<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekapNilai extends Model
{
    use HasFactory;
    protected $table = 'rekap_nilai';
    protected $primaryKey = 'id_rekap_nilai';
    protected $fillable = ['id_mahasiswa', 'nilai_uts', 'nilai_uas'];
    
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa');
    }
}
