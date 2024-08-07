<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class komentar_materi extends Model
{
    use HasFactory;
    protected $table = 'komentar_materi';
    protected $primaryKey = 'id_komentar';
    protected $fillable = ['id_materi', 'id_user', 'komentar'];
}
