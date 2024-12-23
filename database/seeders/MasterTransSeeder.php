<?php

namespace Database\Seeders;

use App\Models\MasterTrans;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MasterTransSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'nama' => 'Tetap',
                'nom_makan' => 200000,
                'nom_transport' => 500000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($data as $row) {
            MasterTrans::create($row);
        }
    }
}
