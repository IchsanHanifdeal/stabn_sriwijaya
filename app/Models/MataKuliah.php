<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    use HasFactory;
    use HasFactory;
    protected $table = 'mata_kuliah';
    protected $primaryKey = 'id_matakuliah';
    protected $fillable = [
        'id_matakuliah',
        'mata_kuliah',
    ];
}
