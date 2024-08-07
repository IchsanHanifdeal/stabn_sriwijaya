<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;
    protected $table = 'absensi';
    protected $primaryKey = 'id_absensi';
    protected $fillable = [
        'id_user',
        'tanggal',
        'waktu',
        'lokasi',
        'latitude',
        'longitude',
        'foto',
        'status',
        'keterangan',
        'pertemuan',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
