<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class MasterCutiIzinPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Bersihkan cache permission
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // Permission baru yang akan ditambahkan
        $permissionsBaru = [
            'master-cuti',
            'master-izin',
        ];

        // Role tertentu yang mendapatkan permission baru
        $rolesPenerima = [
            'Super Admin',
            'Administrator',
            'Kepala Seksi Kepegawaian',
            'Kepala Seksi Keuangan',
            'Manager',
            'Kepala Unit',
            'Kepala Sub Unit',
            'Kepala Instalasi',
            'Kepala Ruang',
            'Kepala Seksi',
            'Penanggung Jawab',
            'Koordinator',
        ];

        // Tambahkan hanya permission baru
        foreach ($permissionsBaru as $permissionName) {

            // Gunakan permission yang sudah ada,
            // atau buat jika belum tersedia
            $permission = Permission::firstOrCreate([
                'name' => $permissionName,
                'guard_name' => 'web',
            ]);

            foreach ($rolesPenerima as $roleName) {

                // Cari role lama tanpa membuat role baru
                $role = Role::where('name', $roleName)
                    ->where('guard_name', 'web')
                    ->first();

                // Lewati jika role tidak ditemukan
                if (!$role) {
                    continue;
                }

                // Tambahkan hanya jika belum dimiliki
                if (!$role->hasDirectPermission($permission)) {
                    $role->givePermissionTo($permission);
                }
            }
        }

        // Bersihkan cache setelah penambahan
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
