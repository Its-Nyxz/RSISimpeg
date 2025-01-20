<?php

namespace Database\Seeders;

use App\Models\PosisiPoint;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PosisiPointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $posisi = [
            ['nama' => 'Ketua/ Wakil/ Sekretaris 1-2 Tim Produktif/Aktif', 'point' => 1],
            ['nama' => 'Ketua/ Wakil/Sekretaris 3-4 Tim Produktif/Aktif', 'point' => 2],
            ['nama' => 'Ketua/ Wakil/Sekretaris 5-6 Tim Produktif/Aktif', 'point' => 3],
        ];

        foreach ($posisi as $data) {
            PosisiPoint::create($data);
        }
    }
}
