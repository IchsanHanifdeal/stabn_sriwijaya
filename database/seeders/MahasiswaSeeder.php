<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mahasiswa;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Mahasiswa::create([
            'id_user' => '2',
            'id_jurusan' => '1',
            'nim' => '1234567890',
            'name' => 'Mahasiswa 1',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2000-01-01',
            'alamat' => 'Jl. Raya Transyogi, Jakarta',
            'no_hp' => '081234567890',
            'username' => 'mahasiswa1',
            'email' => 'mahasiswa1@gmail.com',
        ]);
    }
}
