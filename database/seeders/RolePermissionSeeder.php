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
            'Kepala Seksi Kepegawaian', //
            'Kepala Seksi Keuangan',
            'Manager',
            'Kepala Unit',
            'Kepala Sub Unit',
            'Kepala Instalasi',
            'Kepala Ruang',
            'Kepala Seksi',
            'Penanggung Jawab',
            'Koordinator',
            'Administrator', //
            'Staf',
            'Staf Kepegawaian',
            'Staf Keuangan',
        ];

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
            'view-kepegawaian',
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

            // Role Kepala Seksi Kepegawaian
            if ($role === 'Kepala Seksi Kepegawaian') {
                // Hanya blokir 'view-keuangan' untuk Kepala Seksi Kepegawaian
                $allowedPermissions = Permission::where('name', '!=', 'view-keuangan')->get();
                $roleModel->givePermissionTo($allowedPermissions);
            }

            // Role Staf Kepegawaian
            if ($role === 'Staf Kepegawaian') {
                // Blokir akses ke 'view-keuangan', 'masterdata', dan 'hak-akses' untuk Staf Kepegawaian
                $restrictedPermissions = ['view-keuangan', 'master-data', 'hak-akses', 'absen', 'list-history-user', 'list-history-create', 'list-history-edit'];

                // Ambil permission yang **tidak termasuk dalam restrictedPermissions**
                $allowedPermissions = Permission::whereNotIn('name', $restrictedPermissions)->get();

                $roleModel->givePermissionTo($allowedPermissions);
            }

            // Role Keuangan
            if (in_array($role, ['Kepala Seksi Keuangan', 'Staf Keuangan'])) {
                $allowedPermissions = Permission::where('name', '!=', 'view-kepegawaian')->get();
                $roleModel->givePermissionTo($allowedPermissions);
            }

            // Kalau role-nya Staf, kasih hanya permission 'timer' dan 'list-history'
            if ($role === 'Staf') {
                $roleModel->givePermissionTo(['timer', 'list-history']);
            }

            // Kalau role-nya Kepala Unit
            if ($role === 'Kepala Unit') {
                $allowedPermissions = ['timer', 'list-history', 'absen', 'view-kepegawaian', 'detail', 'create'];
                $roleModel->givePermissionTo($allowedPermissions);
            }

            // Kalau role-nya Kepala Ruang
            if ($role === 'Kepala Ruang') {
                $allowedPermissions = ['timer', 'list-history', 'absen', 'view-kepegawaian', 'detail', 'create'];
                $roleModel->givePermissionTo($allowedPermissions);
            }
        }
    }
}
