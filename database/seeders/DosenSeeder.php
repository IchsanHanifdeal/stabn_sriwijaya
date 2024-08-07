<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Dosen;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Dosen::create([
            'id_dosen' => '1',
            'id_user' => '1',
            'nip' => '1234567890',
            'name' => 'Dosen 1',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2000-01-01',
            'alamat' => 'Jakarta',
            'no_hp' => '081234567890',
            'username' => 'dosen1',
            'email' => 'dosen1@gmail.com',
        ]);
    }
}
