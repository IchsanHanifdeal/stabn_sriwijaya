<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'id_user' => '1',
            'name' => 'Dosen 1',
            'username' => 'dosen1',
            'email' => 'dosen1@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'dosen',
        ]);

        User::create([
            'id_user' => '2',
            'name' => 'Mahasiswa 1',
            'username' => 'mahasiswa1',
            'email' => 'mahasiswa1@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
        ]);
    }
}
