<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\MasterUmum;
use App\Models\PointPeran;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

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
        // Buat Roles jika belum ada
        $roles = [
            'Super Admin',
        ];

        // Buat role jika belum ada
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // Daftar permission
        $permissions = [
            // 'add-user',
            'timer',
            'list-history',
            'list-history-user',
            'list-history-create',
            'list-history-edit',
            'select-user',
            'master-data',
            'tunjangan',
            'golongan',
            'gaji-pokok',
            'pendidikan',
            'absen',
            'template-jadwal',
            'import-jadwal',
            'tambah-jadwal',
            'edit-jadwal',
            'unit-kerja',
            'potongan',
            'tunjangan-kinerja',
            'kategori-jabatan',
            'kategori-pph',
            'create-data-karyawan',
            'detail-data-karyawan',
            'edit-data-karyawan',
            'tambah-history',
            'tambah-sp',
            'view-kenaikan',
            'approval-cuti',
            'approval-izin',
            'approval-tukar-jadwal',
            'view-import-gaji',
            'view-poin-peran',
            'view-poin-penilaian',
            'view-keuangan',
            'view-kepegawaian',
            'hak-akses',
            'resign-kerja',
            'jatah-cuti',
            'override-lokasi',
            'penyesuaian',
            'approve-izin',
            'approve-cuti'
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Assign semua permission ke Super Admin
        $superAdminRole = Role::where('name', 'Super Admin')->first();
        $superAdminRole->syncPermissions(Permission::all());

        // Buat user super admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@gmail.com'],
            [
                'name' => 'Super Admin',
                'username' => 'superadmin',
                'password' => Hash::make('123'), // password default
                'unit_id' => null,
            ]
        );

        // // Assign role ke user
        // $superAdmin->assignRole('Super Admin');

        $this->call([
            // MasterGolonganSeeder::class,
            // MasterPendidikanSeeder::class,
            // KategoriJabatanSeeder::class,
            // MasterGapokSeeder::class,
            // MasterJabatanSeeder::class,
            // MasterFungsiSeeder::class,
            // MasterUmumSeeder::class,
            // MasterKhususSeeder::class,
            // MasterPenyesuaianSeeder::class,
            // MasterPotonganSeeder::class,
            // MasterTransSeeder::class,
            // OpsiAbsenSeeder::class,
            JenisFileSeeder::class,
            // KategoripphSeeder::class,
            // NilaipphSeeder::class,
            JenisKaryawanSeeder::class,
            StatusAbsenSeeder::class,
            // UnitKerjaSeeder::class,
            // LevelPointSeeder::class,
            // LevelUnitSeeder::class,
            // TerlibatPointSeeder::class,
            // PosisiPointSeeder::class,
            // ShiftSeeder::class,
            // ShiftPointSeeder::class,
            // PkPointSeeder::class,
            // PointPelatihanSeeder::class,
            // PointPeranSeeder::class,
            // MasaKerjaSeeder::class,
            // PointJabatanSeeder::class,
            // ProposionalitasPointSeeder::class,
            // PointKinerjaSeeder::class,
            // PointJamKerjaSeeder::class,
            // UserSeeder::class, // Tambahkan ini
            // RolePermissionSeeder::class,
            // HolidaysSeeder::class,
            // JenisCutiSeeder::class,
            // StatusCutiSeeder::class,
            // JenisIzinSeeder::class,
            // RiwayatJabatanSeeder::class,
            // TaxBracketsSeeder::class,
            // GapokKontrakSeeder::class,
        ]);
    }
}
