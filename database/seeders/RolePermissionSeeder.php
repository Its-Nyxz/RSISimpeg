<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Daftar role
        $roles = [
            'Super Admin',
            'Kepegawaian',
            'Keuangan',
            'Manager',
            'Kepala Unit',
            'Kepala Sub Unit',
            'Kepala Instalasi',
            'Kepala Ruang',
            'Kepala Seksi',
            'Penanggung Jawab',
            'Koordinator',
            'Administrator',
            'Staf',
        ];

        // Daftar permission
        $permissions = [
            'add-user',
            'timer',
            'list-history',
            'select-user',
            'tunjangan',
            'golongan',
            'gaji-pokok',
            'pendidikan',
            'absen',
            'unit-kerja',
            'potongan',
            'tunjangan-kinerja',
            'kategori-jabatan',
            'create',
            'detail',
            'view-kenaikan',
            'notification',
            'view-gaji',
            'view-peran',
            'view-pekerja',
            'view-keuangan',
            'hak-akses',
        ];

        // Tambah permission ke database
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Tambah role & assign permission
        foreach ($roles as $role) {
            $roleModel = Role::firstOrCreate(['name' => $role]);

            // Kalau role-nya Super Admin, kasih semua permission
            if ($role == 'Super Admin') {
                $roleModel->givePermissionTo(Permission::all());
            }
            // Kalau role-nya Administrator, kasih semua permission
            if ($role == 'Administrator') {
                $roleModel->givePermissionTo(Permission::all());
            }

            // Kalau role-nya Kepegawaian, kasih semua permission KECUALI 'view-keuangan'
            if ($role === 'Kepegawaian') {
                $allowedPermissions = Permission::where('name', '!=', 'view-keuangan')->get();
                $roleModel->givePermissionTo($allowedPermissions);
            }
            // Kalau role-nya Staf, kasih hanya permission 'timer' dan 'list-history'
            if ($role === 'Staf') {
                $roleModel->givePermissionTo(['timer', 'list-history']);
            }
        }
    }
}
