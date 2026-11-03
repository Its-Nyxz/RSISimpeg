<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class SinkronCutiPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Bersihkan cache permission
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // Nama permission baru untuk sinkronisasi cuti ke jadwal
        $permissionName = 'sinkron-cuti';

        // Buat permission jika belum tersedia
        $permission = Permission::firstOrCreate([
            'name' => $permissionName,
            'guard_name' => 'web',
        ]);

        // Default role penerima (Super Admin & Administrator agar bisa mengelola)
        $defaultRoles = [
            'Super Admin',
            'Administrator',
        ];

        foreach ($defaultRoles as $roleName) {
            $role = Role::where('name', $roleName)
                ->where('guard_name', 'web')
                ->first();

            if ($role && !$role->hasPermissionTo($permission)) {
                $role->givePermissionTo($permission);
            }
        }

        // Bersihkan cache setelah penambahan
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
