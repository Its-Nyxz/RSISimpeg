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
            'absensi',
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
        }
    }
}