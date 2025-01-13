<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UnitKerja;
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
        $roles = [
            'Super Admin',
            'Kepegawaian',
            'Keuangan',
            'Kepala Unit',
        ];

        foreach ($roles as $roleName) {
            Role::findOrCreate($roleName);
        }

        // Super Admin
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('123'), // Password default
            'unit_id' => null, // Tidak terkait dengan unit
        ]);

        $superAdmin->assignRole('Super Admin');

        // Data Kepala Unit
        $users = [
            ['unit_name' => 'IBS', 'name' => 'Sulis Setiyanto'],
            ['unit_name' => 'IGD', 'name' => 'Suyatno'],
            ['unit_name' => 'ICU', 'name' => 'Tri Nurhidayah'],
            ['unit_name' => 'INST DIALISIS', 'name' => 'Puji Yuliati'],
            ['unit_name' => 'IRJ', 'name' => 'Desi Norma'],
            ['unit_name' => 'Ka. IMP', 'name' => 'Tatun Parjiati'],
            ['unit_name' => 'PERINATOLOGI', 'name' => 'Ariyanti Retno A'],
            ['unit_name' => 'VK', 'name' => 'Widanti Kusuma'],
            ['unit_name' => 'ALZAITUN', 'name' => 'Wahyu Puspitasari'],
            ['unit_name' => 'Ka. Instalasi Ranap', 'name' => 'Agus Widayat'],
            ['unit_name' => 'AT TAQWA', 'name' => 'Susilo Rudatin'],
            ['unit_name' => 'ASSALAM', 'name' => 'Indriyana Keliek F'],
            ['unit_name' => 'AL AMIN', 'name' => 'Siti Markhamah'],
            ['unit_name' => 'FIRDAUS', 'name' => 'Ika Sari Sholehati'],
            ['unit_name' => 'HAJI', 'name' => 'Umu Hani'],
            ['unit_name' => 'ASSYFA', 'name' => 'Ifa Fitria'],
            ['unit_name' => 'AZIZIAH', 'name' => 'Slamet Supratomo'],
            ['unit_name' => 'ALMUNAWAROH', 'name' => 'Latifah'],
            ['unit_name' => 'INST REHAB MEDIK', 'name' => 'Slamet Budi Santosa'],
            ['unit_name' => 'CASE MANAGER', 'name' => 'Ari Fitria'],
            ['unit_name' => 'INST REKAM MEDIK', 'name' => 'Wigati'],
            ['unit_name' => 'INST FARMASI', 'name' => 'Uniek Setyawardani'],
            ['unit_name' => 'INST RADIOLOGI', 'name' => 'Wisnu Kuncahyo'],
            ['unit_name' => 'INST LABORATORIUM', 'name' => 'Joko Sugiharto'],
            ['unit_name' => 'INST SANITASI', 'name' => 'A Imam Mutaqin'],
            ['unit_name' => 'INST CSSD', 'name' => 'Khamidah'],
            ['unit_name' => 'INST PEML SARPRAS', 'name' => 'Widodo Pindah Riyanto'],
            ['unit_name' => 'INST GAS MEDIK & ALKES', 'name' => 'Adityana Juni Saputra'],
            ['unit_name' => 'UNIT MCU & POSKES', 'name' => 'Ruslan'],
            ['unit_name' => 'UNIT TRANSPORTASI', 'name' => 'Durul Farid'],
            ['unit_name' => 'INST GIZI', 'name' => 'Pujiningsih'],
            ['unit_name' => 'UNIT PJBR', 'name' => 'Toha'],
            ['unit_name' => 'UNIT PENGELOLAAN LINEN', 'name' => 'Budiono'],
            ['unit_name' => 'HUMAS & PROG RS', 'name' => 'Ali Muakhor'],
            ['unit_name' => 'SDM', 'name' => 'Diana Melisawati'],
            ['unit_name' => 'AKUNTANSI', 'name' => 'Nur Aini Oktaviani'],
            ['unit_name' => 'KEUANGAN', 'name' => 'Siti Maulidah'],
            ['unit_name' => 'KASIR', 'name' => 'Khodijah'],
            ['unit_name' => 'ASURANSI', 'name' => 'Erlita Puspitasari'],
            ['unit_name' => 'ASET & LOGISTIK', 'name' => 'Ratih Titis P'],
            ['unit_name' => 'PELAYANAN MEDIK', 'name' => 'Eko Setiono'],
            ['unit_name' => 'KEPERAWATAN', 'name' => 'Rifki Nafisani'],
            ['unit_name' => 'PENUNJANG', 'name' => 'Mutia Kanza Salama'],
            ['unit_name' => 'PENGAMANAN', 'name' => 'Eko Pranoto'],
            ['unit_name' => 'UNIT PEMASARAN', 'name' => 'Adi Setiadi'],
            ['unit_name' => 'IT', 'name' => 'Basuki Imam Sampurna'],
            ['unit_name' => 'IPCN', 'name' => 'Wingit Bayu H'],
            ['unit_name' => 'KOMITE', 'name' => 'Fajarianto'],
        ];

        // Buat User untuk setiap unit
        foreach ($users as $userData) {
            // Cari unit berdasarkan nama
            $unit = UnitKerja::where('nama', $userData['unit_name'])->first();

            if ($unit) {
                // Buat user
                $user = User::create([
                    'name' => $userData['name'],
                    'email' => strtolower(str_replace(' ', '.', $userData['name'])) . '@gmail.com',
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $unit->id, // Hubungkan ke unit kerja
                ]);

                // Assign role "Kepala Unit" atau "Kepegawaian" (contoh logika, sesuaikan sesuai kebutuhan)
                $role = $userData['name'] === $unit->nama ? 'Kepala Unit' : 'Kepegawaian';
                $user->assignRole($role);
            }
        }
    }
}
