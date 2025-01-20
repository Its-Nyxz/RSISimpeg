<?php

namespace Database\Seeders;

use App\Models\PkPoint;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PkPointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pk = [
            ['nama' => 'PK/BK Dasar', 'point' => 1],
            ['nama' => 'PK/ BK I', 'point' => 2],
            ['nama' => 'PK/ BK II', 'point' => 3],
            ['nama' => 'PK/ BK III', 'point' => 4],
            ['nama' => 'PK/BK IV', 'point' => 5],
        ];

        foreach ($pk as $data) {
            PkPoint::create($data);
        }
    }
}
