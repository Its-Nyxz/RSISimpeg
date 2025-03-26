<?php

namespace Database\Seeders;

use App\Models\JenisCuti;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class JenisCutiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cuti = [
            ['nama_cuti' => 'Cuti Tahunan', 'durasi_default' => 12, 'dibayar' => true, 'hanya_untuk_karyawan_tetap' => false],
            ['nama_cuti' => 'Cuti Melahirkan', 'durasi_default' => 90, 'dibayar' => true, 'hanya_untuk_karyawan_tetap' => true],
            ['nama_cuti' => 'Cuti Keguguran', 'durasi_default' => 45, 'dibayar' => true, 'hanya_untuk_karyawan_tetap' => true],
            ['nama_cuti' => 'Cuti Ibadah Haji', 'durasi_default' => 14, 'dibayar' => true, 'hanya_untuk_karyawan_tetap' => true],
            ['nama_cuti' => 'Cuti Ibadah Umroh', 'durasi_default' => 4, 'dibayar' => true, 'hanya_untuk_karyawan_tetap' => true],
            ['nama_cuti' => 'Cuti Kematian', 'durasi_default' => 3, 'dibayar' => true, 'hanya_untuk_karyawan_tetap' => false],
            ['nama_cuti' => 'Cuti Pernikahan', 'durasi_default' => 3, 'dibayar' => true, 'hanya_untuk_karyawan_tetap' => false],
            ['nama_cuti' => 'Cuti Pernikahan', 'durasi_default' => 3, 'dibayar' => true, 'hanya_untuk_karyawan_tetap' => false],
        ];

        foreach ($cuti as $item) {
            JenisCuti::create($item);
        }
    }
}
