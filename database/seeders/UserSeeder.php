<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Dosen;
use App\Models\Mahasiswa;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'id_user' => '1',
                'name' => 'Dosen 1',
                'username' => 'dosen1',
                'email' => 'dosen1@gmail.com',
                'password' => Hash::make('password'),
                'validasi' => 'diterima',
                'role' => 'dosen',
            ],
            [
                'id_user' => '2',
                'name' => 'Dosen 2',
                'username' => 'dosen2',
                'email' => 'dosen2@gmail.com',
                'password' => Hash::make('password'),
                'validasi' => 'menunggu validasi',
                'role' => 'dosen',
            ],
            [
                'id_user' => '3',
                'name' => 'Dosen 3',
                'username' => 'dosen3',
                'email' => 'dosen3@gmail.com',
                'password' => Hash::make('password'),
                'validasi' => 'ditolak',
                'role' => 'dosen',
            ],
            [
                'id_user' => '4',
                'name' => 'Mahasiswa 1',
                'username' => 'mahasiswa1',
                'email' => 'mahasiswa1@gmail.com',
                'password' => Hash::make('password'),
                'validasi' => 'diterima',
                'role' => 'mahasiswa',
            ],
            [
                'id_user' => '5',
                'name' => 'Mahasiswa 2',
                'username' => 'mahasiswa2',
                'email' => 'mahasiswa2@gmail.com',
                'password' => Hash::make('password'),
                'validasi' => 'menunggu validasi',
                'role' => 'mahasiswa',
            ],
            [
                'id_user' => '6',
                'name' => 'Mahasiswa 3',
                'username' => 'mahasiswa3',
                'email' => 'mahasiswa3@gmail.com',
                'password' => Hash::make('password'),
                'validasi' => 'ditolak',
                'role' => 'mahasiswa',
            ],
            [
                'id_user' => '7',
                'name' => 'Admin 1',
                'username' => 'admin1',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password'),
                'validasi' => 'diterima',
                'role' => 'admin',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }

        $dosens = [
            [
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
            ],
            [
                'id_dosen' => '2',
                'id_user' => '2',
                'nip' => '1234567891',
                'name' => 'Dosen 2',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '1990-02-02',
                'alamat' => 'Bandung',
                'no_hp' => '081234567891',
                'username' => 'dosen2',
                'email' => 'dosen2@gmail.com',
            ],
            [
                'id_dosen' => '3',
                'id_user' => '3',
                'nip' => '1234567892',
                'name' => 'Dosen 3',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Surabaya',
                'tanggal_lahir' => '1985-03-03',
                'alamat' => 'Surabaya',
                'no_hp' => '081234567892',
                'username' => 'dosen3',
                'email' => 'dosen3@gmail.com',
            ],
        ];

        foreach ($dosens as $dosen) {
            Dosen::create($dosen);
        }

        $mahasiswas = [
            [
                'id_user' => '4',
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
            ],
            [
                'id_user' => '5',
                'id_jurusan' => '2',
                'nim' => '1234567891',
                'name' => 'Mahasiswa 2',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Bogor',
                'tanggal_lahir' => '2001-02-02',
                'alamat' => 'Jl. Raya Bogor, Bogor',
                'no_hp' => '081234567891',
                'username' => 'mahasiswa2',
                'email' => 'mahasiswa2@gmail.com',
            ],
            [
                'id_user' => '6',
                'id_jurusan' => '2',
                'nim' => '1234567892',
                'name' => 'Mahasiswa 3',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Depok',
                'tanggal_lahir' => '2002-03-03',
                'alamat' => 'Jl. Raya Margonda, Depok',
                'no_hp' => '081234567892',
                'username' => 'mahasiswa3',
                'email' => 'mahasiswa3@gmail.com',
            ],
        ];

        foreach ($mahasiswas as $mahasiswa) {
            Mahasiswa::create($mahasiswa);
        }
    }
}