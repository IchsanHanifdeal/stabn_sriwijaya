<?php

namespace Database\Seeders;

use App\Models\MataKuliah;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class mata_kuliahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MataKuliah::create([
            'id_matakuliah' => '1',
            'mata_kuliah' => 'PBO'
        ]);
        
        MataKuliah::create([
            'id_matakuliah' => '2',
            'mata_kuliah' => 'ASD'
        ]);

        MataKuliah::create([
            'id_matakuliah' => '3',
            'mata_kuliah' => 'PWD'
        ]);

        MataKuliah::create([
            'id_matakuliah' => '4',
            'mata_kuliah' => 'PWL'
        ]);
    }
}
