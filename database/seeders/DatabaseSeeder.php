<?php

namespace Database\Seeders;

use App\Models\MasterUmum;
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
            MasterFungsiSeeder::class,
            MasterGapokSeeder::class,
            MasterJabatanSeeder::class,
            MasterKhususSeeder::class,
            MasterPenyesuaianSeeder::class,
            MasterPotonganSeeder::class,
            MasterTransSeeder::class,
            MasterUmumSeeder::class,
            UserSeeder::class, // Tambahkan ini
            TabelTest::class,
        ]);
    }
}
