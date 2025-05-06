<?php

namespace Database\Seeders;

use App\Models\MasterUmum;
use App\Models\PointPeran;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call([
            MasterGolonganSeeder::class, // Tambahkan ini
            MasterPendidikanSeeder::class,
            KategoriJabatanSeeder::class,
            MasterGapokSeeder::class,
            MasterJabatanSeeder::class,
            MasterFungsiSeeder::class,
            MasterUmumSeeder::class,
            MasterKhususSeeder::class,
            MasterPenyesuaianSeeder::class,
            // MasterPotonganSeeder::class,
            MasterTransSeeder::class,
            OpsiAbsenSeeder::class,
            JenisFileSeeder::class,
            KategoripphSeeder::class,
            NilaipphSeeder::class,
            JenisKaryawanSeeder::class,
            StatusAbsenSeeder::class,
            UnitKerjaSeeder::class,
            LevelPointSeeder::class,
            LevelUnitSeeder::class,
            TerlibatPointSeeder::class,
            PosisiPointSeeder::class,
            // ShiftSeeder::class,
            ShiftPointSeeder::class,
            PkPointSeeder::class,
            PointPelatihanSeeder::class,
            PointPeranSeeder::class,
            MasaKerjaSeeder::class,
            PointJabatanSeeder::class,
            ProposionalitasPointSeeder::class,
            PointKinerjaSeeder::class,
            PointJamKerjaSeeder::class,
            // UserSeeder::class, // Tambahkan ini
            RolePermissionSeeder::class,
            HolidaysSeeder::class,
            JenisCutiSeeder::class,
            StatusCutiSeeder::class,
            JenisIzinSeeder::class,
        ]);
    }
}
