<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Silabus extends Model
{
    use HasFactory;
    protected $table = 'silabus';
    protected $primaryKey = 'id_silabus';
    protected $fillable = ['pertemuan', 'file_silabus', 'nama_silabus', 'deskripsi', 'tipe_file'];
}
