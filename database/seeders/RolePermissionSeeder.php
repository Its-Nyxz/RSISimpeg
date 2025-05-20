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
            'template-jadwal',
            'import-jadwal',
            'tambah-jadwal',
            'edit-jadwal',
            'unit-kerja',
            'potongan',
            'tunjangan-kinerja',
            'kategori-jabatan',
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
            'penyesuaian',
            'approve-izin',
            'approve-cuti'

        ];

        // Tambahkan permission ke database
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Aturan permission berdasarkan role
        $rolePermissions = [
            'Super Admin' => Permission::all(),
            'Administrator' => Permission::all(),
            'Kepala Seksi Kepegawaian' => Permission::where('name', '!=', 'view-keuangan')->get(),
            'Staf Kepegawaian' => Permission::whereNotIn('name', [
                'view-keuangan',
                'master-data',
                'hak-akses',
                'absen',
                'list-history-user',
                'list-history-create',
                'list-history-edit',
                'template-jadwal',
                'import-jadwal',
                'tambah-jadwal',
                'edit-jadwal',
                'approval-cuti',
                'approval-tukar-jadwal',
                'view-import-gaji',
                'view-poin-peran',
                'view-poin-penilaian',
                'approve-izin'
            ])->get(),
            'Kepala Seksi Keuangan' => Permission::whereNotIn('name', [
                'view-kenaikan',
                'hak-akses',
                'notification-cuti',
                'create-data-karyawan',
                'tambah-history',
                'tambah-sp',
                'view-poin-peran',
                'view-poin-penilaian',
            ])->get(),
            'Staf Keuangan' => Permission::whereNotIn('name', [
                'view-kepegawaian',
                'master-data',
                'hak-akses',
                'absen',
                'list-history-user',
                'list-history-create',
                'list-history-edit',
                'template-jadwal',
                'import-jadwal',
                'tambah-jadwal',
                'tambah-history',
                'tambah-sp',
                'edit-jadwal',
                'resign-kerja',
                'jatah-cuti',
                'penyesuaian'
            ])->get(),
            'Staf' => Permission::whereIn('name', ['timer', 'list-history'])->get(),
            'Kepala Instalasi' => Permission::whereNotIn('name', ['view-keuangan', 'hak-akses', 'master-data', 'create-data-karyawan', 'resign-kerja'])->get(),
            'Kepala Ruang' => Permission::whereNotIn('name', ['view-keuangan', 'hak-akses', 'master-data', 'create-data-karyawan', 'resign-kerja'])->get(),
            'Kepala Unit' => Permission::whereNotIn('name', ['view-keuangan', 'hak-akses', 'master-data', 'create-data-karyawan', 'resign-kerja'])->get(),
            'Kepala Seksi' => Permission::whereNotIn('name', ['view-keuangan', 'hak-akses', 'master-data', 'create-data-karyawan', 'resign-kerja'])->get(),
            'Manager' => Permission::whereIn('name', ['timer', 'list-history', 'detail-data-karyawan'])->get(),
        ];

        // Tambahkan role ke database dan atur permission
        foreach ($roles as $role) {
            $roleModel = Role::firstOrCreate(['name' => $role]);


            // Beri permission jika ada dalam daftar rolePermissions
            if (isset($rolePermissions[$role])) {
                $roleModel->givePermissionTo($rolePermissions[$role]);
            }
        }
    }
}
