<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat Roles jika belum ada
        $superAdminRole = Role::findOrCreate('Super Admin');
        $kepegawaianRole = Role::findOrCreate('Kepegawaian');
        $keuanganRole = Role::findOrCreate('Keuangan');
        $staffRole = Role::findOrCreate('Staff');

        // Daftar Users
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@example.com',
                'password' => Hash::make('password'), // Ganti dengan password yang aman
                'jabatan_id' => null,
                'fungsi_id' => null,
                'trans_id' => null,
                'khusus_id' => null,
                'gol_id' => null,
                'nip' => null,
                'no_hp' => null,
                'tmt' => null,
                'jk' => null,
                'pensiun' => null,
                'tanggal_lahir' => null,
                'alamat' => null,
                'pend_awal' => null,
                'pend_penyesuaian' => null,
                'pendidikan' => null,
                'tgl_penyesuaian' => null,
                'masa_kerja' => null,
                'status' => null,
                'role' => $superAdminRole,
            ],
            [
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'password' => Hash::make('password'), // Ganti dengan password yang aman
                'jabatan_id' => 1,
                'fungsi_id' => 1,
                'trans_id' => 1,
                'khusus_id' => 1,
                'gol_id' => 8,
                'nip' => '123456789',
                'no_hp' => '081234567890',
                'tmt' => '2020-01-01',
                'jk' => 'L',
                'pensiun' => null,
                'tanggal_lahir' => '1985-05-15',
                'alamat' => 'Jl. Contoh Alamat No. 1',
                'pend_awal' => 7,
                'pend_penyesuaian' => null,
                'pendidikan' => 7,
                'tgl_penyesuaian' => null,
                'masa_kerja' => '4',
                'status' => 1,
                'role' => $kepegawaianRole,
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane.smith@example.com',
                'password' => Hash::make('password'), // Ganti dengan password yang aman
                'jabatan_id' => 2,
                'fungsi_id' => 2,
                'trans_id' => 1,
                'khusus_id' => 2,
                'gol_id' => 10,
                'nip' => '987654321',
                'no_hp' => '089876543210',
                'tmt' => '2023-01-01',
                'jk' => 'P',
                'pensiun' => null,
                'tanggal_lahir' => '1990-10-20',
                'alamat' => 'Jl. Contoh Alamat No. 2',
                'pend_awal' => 11,
                'pend_penyesuaian' => null,
                'pendidikan' => 11,
                'tgl_penyesuaian' => null,
                'masa_kerja' => '1',
                'status' => 1,
                'role' => $keuanganRole,
            ],
            [
                'name' => 'Michael Johnson',
                'email' => 'michael.johnson@example.com',
                'password' => Hash::make('password'), // Ganti dengan password yang aman
                'jabatan_id' => 3,
                'fungsi_id' => 3,
                'trans_id' => 1,
                'khusus_id' => 3,
                'gol_id' => 7,
                'nip' => '456789123',
                'no_hp' => '081234512345',
                'tmt' => '2024-01-01',
                'jk' => 'L',
                'pensiun' => null,
                'tanggal_lahir' => '1988-08-08',
                'alamat' => 'Jl. Contoh Alamat No. 3',
                'pend_awal' => 8,
                'pend_penyesuaian' => null,
                'pendidikan' => 8,
                'tgl_penyesuaian' => null,
                'masa_kerja' => '0',
                'status' => 1,
                'role' => $staffRole,
            ],
        ];

        // Buat User dan Assign Roles
        foreach ($users as $userData) {
            $role = $userData['role'];
            unset($userData['role']); // Hapus role sebelum memasukkan ke database

            $user = User::create($userData); // Simpan user ke database
            $user->assignRole($role); // Assign role ke user
        }
    }
}
