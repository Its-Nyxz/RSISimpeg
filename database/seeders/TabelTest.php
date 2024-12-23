<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Umum;
use App\Models\Gapok;
use App\Models\Penyesuaian;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TabelTest extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data untuk tabel t_umum
        $t_umum = [
            [
                'user_id' => 2,
                'umum_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 4,
                'umum_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 3,
                'umum_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        // Gunakan insert untuk menyisipkan banyak baris
        Umum::insert($t_umum);

        // Data untuk tabel t_gapok
        $t_gapok = [
            [
                'user_id' => 2,
                'gol_id' => 8,
                'gapok_id' => 222,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 3,
                'gol_id' => 7,
                'gapok_id' => 287,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 4,
                'gol_id' => 10,
                'gapok_id' => 183,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        // Gunakan insert untuk menyisipkan banyak baris
        Gapok::insert($t_gapok);

        // Data untuk tabel t_penyesuaian
        $t_penyesuaian = [
            [
                'user_id' => 2,
                'penyesuaian_id' => 25,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 3,
                'penyesuaian_id' => 29,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        // Gunakan insert untuk menyisipkan banyak baris
        Penyesuaian::insert($t_penyesuaian);
    }
}
