<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Jurusan;

class JurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Jurusan::create([
            'nama_jurusan' => 'Teknologi Informasi',
            'kode_jurusan' => 'TI',
        ]);

        Jurusan::create([
            'nama_jurusan' => 'Manajemen Informatika',
            'kode_jurusan' => 'MI',
        ]);

        Jurusan::create([
            'nama_jurusan' => 'Sistem Informasi',
            'kode_jurusan' => 'SI',
        ]);

        Jurusan::create([
            'nama_jurusan' => 'Jaringan',
            'kode_jurusan' => 'JA',
        ]);

        Jurusan::create([
            'nama_jurusan' => 'Riset',
            'kode_jurusan' => 'RIS',
        ]);
    }
}
