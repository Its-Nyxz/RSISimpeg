<?php

namespace Database\Seeders;

use App\Models\StatusCuti;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StatusCutiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $status = ['Disetujui', 'Ditolak', 'Menunggu'];

        foreach ($status as $item) {
            StatusCuti::create(['nama_status' => $item]);
        }
    }
}
