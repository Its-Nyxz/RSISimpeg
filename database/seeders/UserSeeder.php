<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UnitKerja;
use App\Models\KategoriJabatan;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function emailFormat($nameUser)
    {
        $emailUsername = strtolower(str_replace([' ', ',', '.', '/', '\'', '-'], '.', $nameUser));

        // Hapus titik ganda yang berulang menggunakan regex
        $emailUsername = preg_replace('/\.+/', '.', $emailUsername);

        // Hapus titik di awal dan akhir agar tidak invalid
        $emailUsername = trim($emailUsername, '.');

        return $emailUsername . '@gmail.com';
    }

    public function run(): void
    {
        // Buat Roles jika belum ada
        $roles = [
            'Super Admin',
            'Kepegawaian', //
            'Keuangan',
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

        // Data untuk Direktur
        $direktur = [
            ['name' => 'Dr. H, Arif Fadlullah Chonar', 'jabatan' => 'Direktur'],
        ];

        foreach ($direktur as $member) {
            $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'jabatan_id' => $KategoriJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user DIREKTUR berhasil dijalankan.');

        // Data Kepala Instalasi
        $kepalaInstalasi = [
            ['unit_name' => 'IRJ', 'name' => 'drg Amalia Rahmaniar', 'jabatan' => 'Kepala Instalasi Rawat Jalan'], //102
            ['unit_name' => 'IAPI', 'name' => 'Dr. Anantya Hari W.,Sp.An', 'jabatan' => 'Dokter Spesialis Anastesi + Ka. IAPI'], //part time
            ['unit_name' => 'IMP', 'name' => 'Tatun Parjiati', 'jabatan' => 'Ka. IMP'],
            ['unit_name' => 'INST RANAP', 'name' => 'Agus Widayat', 'jabatan' => 'Ka. Instalasi Rawat Inap'],
        ];

        foreach ($kepalaInstalasi as $data) {
            $unit = UnitKerja::where('nama', $data['unit_name'])->first();

            $KategoriJabatan = KategoriJabatan::where('nama', $data['jabatan'])->value('id');
            if ($unit) {
                $user = User::firstOrCreate(
                    ['email' => $this->emailFormat($data['name'])],
                    [
                        'name' => $data['name'],
                        'password' => Hash::make('123'),
                        'unit_id' => $unit->id,
                        'jabatan_id' => $KategoriJabatan,
                    ]
                );

                $user->assignRole('Kepala Instalasi');
            }
        }

        // Data Kepala Unit
        $kepalaUnit = [
            ['unit_name' => 'IPCN', 'name' => 'Wingit Bayu H', 'jabatan' => 'IPCN'],
        ];

        foreach ($kepalaUnit as $data) {
            $unit = UnitKerja::where('nama', $data['unit_name'])->first();
            $KategoriJabatan = KategoriJabatan::where('nama', $data['jabatan'])->value('id');
            if ($unit) {
                $user = User::firstOrCreate(
                    ['email' => $this->emailFormat($data['name'])],
                    [
                        'name' => $data['name'],
                        'password' => Hash::make('123'),
                        'unit_id' => $unit->id,
                        'jabatan_id' => $KategoriJabatan,
                    ]
                );

                $user->assignRole('Kepala Unit');
            }
        }

        // Data untuk unit Dokter Umum Full Time
        $dokterUmumFullTime = [
            ['name' => 'Dr. H. Agung Widiharto', 'jabatan' => 'Dokter Umum Fungsional'],
            ['name' => 'Dr. Alfiyah Rakhmatul Azizah', 'jabatan' => 'Dokter Umum Fungsional + PJ Klaim'],
            ['name' => 'Dr. Fitratul Aliyah', 'jabatan' => 'Dokter Umum Fungsional'],
        ];

        // Cari unit Dokter Umum Full Time
        $dokterUmumFullTimeunit = UnitKerja::where('nama', 'DOKTER UMUM FULL TIME')->first();

        if (!$dokterUmumFullTimeunit) {
            $this->command->error('Unit DOKTER UMUM FULL TIME tidak ditemukan!');
            return;
        }

        foreach ($dokterUmumFullTime as $member) {
            $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $dokterUmumFullTimeunit->id,
                    'jabatan_id' => $KategoriJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user DOKTER UMUM FULL TIME berhasil dijalankan.');

        // Data untuk unit IBS (penambahan data anggota IBS)
        $ibsMembers = [
            // ['name' => 'Dr. M Yudha S.U., Sp.B', 'role' => 'Kepala Instalasi', 'jabatan' => 'Dokter Spesialis Bedah + Ka. Instalasi Bedah Sentral'],
            ['name' => 'Sulis Setiyanto', 'role' => 'Kepala Unit', 'jabatan' => 'Karu IBS'],
            ['name' => 'Daryanto', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Mat Suwignyo', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Anifah', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Mohammad Amrulloh', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Singgih Triyantoro', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Riyan Nuryana', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Umi Sakdiyah', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Dwi Agung Nugroho', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Atik Wakiah', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Supriyadi.,A.Md Kep', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Yutiwi .,A.Md Kep', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Reni Ekawati', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Titin Astuti.,S.Kep Ns', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Adam Rachman Sukmana.,A.Md Kep', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Ilham Tri Nugroho', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Muhammad Iqbal Ramdadhan', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Sofyanul Affan Hidayat', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Nanang Cahyono', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Arif Yulianto', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Restika Dyah Utami', 'role' => 'Administrator', 'jabatan' => 'Staf Administrasi IBS'],
        ];

        // Cari unit IBS
        $ibsUnit = UnitKerja::where('nama', 'IBS')->first();

        if (!$ibsUnit) {
            $this->command->error('Unit IBS tidak ditemukan!');
            return;
        }

        foreach ($ibsMembers as $member) {
            $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'),
                    'unit_id' => $ibsUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user IBS berhasil dijalankan .');

        // Data untuk unit IGD
        $igdMembers = [
            ['name' => 'Suyatno', 'role' => 'Kepala Unit', 'jabatan' => 'Karu IGD'],
            ['name' => 'Gilang Yoga Sulistyo Utomo', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Majid Prasetya.,S.Kep Ns', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Yuli Ratnasari, S.Kep.Ns', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Dika Ari Utomo', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Aryadi Harko', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Galuh Rimba N', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Aris Cahyono', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Novian Hardiyono.,A.Md Kep', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Sabar Tiono.,A.Md Kep', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Soleh Ahmad R', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Mahful.,S.Kep Ns', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Yustika Dwi A', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Brian Sanada', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Dinar Kurniadi.,S.Kep Ns', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Adib Rofiudin Izza', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Indarto', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Albetias Pangestuti', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Swasti Jamalina', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Ajeng Bara Saputri.,A.Md Kep', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Syaeful Fadlan A.,S.Kep Ns', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Hanung Maulana.,A.Md Kep', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Tony Adam', 'jabatan' => 'Pekarya Kesehatan'],
            ['name' => 'Aris Aji Pangestu', 'jabatan' => 'Pekarya Kesehatan'],
        ];

        // Cari unit IGD
        $igdUnit = UnitKerja::where('nama', 'IGD')->first();

        if (!$igdUnit) {
            $this->command->error('Unit IGD tidak ditemukan!');
            return;
        }

        foreach ($igdMembers as $member) {
            $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $igdUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user IGD berhasil dijalankan.');

        // Data untuk unit ICU
        $icuMembers = [
            // ['name' => 'Dr. Anantya Hari W.,Sp.An', 'role' => 'Kepala Unit' , 'jabatan' => 'Dokter Spesialis Anastesi + Ka. IAPI'], //part time
            ['name' => 'Tri Nurhidayah', 'role' => 'Kepala Unit', 'jabatan' => 'Karu ICU'],
            ['name' => 'Budiarto', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Septi Hartanti', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Widyandika Yudha', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Eka Dewi Wahyuni', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Reni Yuniarti', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Ahmad Sulatif', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Eka Sri Rahayu', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Nia Puspita Utami.,S.Kep Ns', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Aqmarinda Laila', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Dwi Pangestuti.,S.Kep Ns', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Sukoyo', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Jefri Oktavian.,S.Kep Ns', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Nur Fitriyadi', 'jabatan' => 'Pekarya Kesehatan'],
            ['name' => 'Wahyu Nur Hidayat', 'jabatan' => 'Pekarya Kesehatan'],
        ];

        // Cari unit ICU
        $icuUnit = UnitKerja::where('nama', 'ICU')->first();

        if (!$icuUnit) {
            $this->command->error('Unit ICU tidak ditemukan!');
            return;
        }

        foreach ($icuMembers as $member) {
            $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $icuUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user ICU berhasil dijalankan.');

        // Data untuk unit Instalasi Dialisis
        $dialisisMembers = [
            // ['name' => 'Dr. Yunanto Dwi Nugroho, Sp.PD', 'role' => 'Kepala Unit', 'jabatan' => 'Supervisor Instalasi Dialisis'], //Part Time
            // ['name' => 'Dr. Lucy Mirafra Ganjar Wijaya', 'jabatan' => 'Dokter Pelaksana Instalasi Dialisis'], //Part Time

            ['name' => 'Puji Yuliati', 'role' => 'Kepala Unit', 'jabatan' => 'Karu Instalasi Dialisis'],
            ['name' => 'Ari Dwi Astuti', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Sahid Menru Hidayatulloh', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Helman Riyadi', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Darmuji', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => "Nur Wakhidah Lulu'ul Jannah", 'role' => 'Administrator', 'jabatan' => 'Administrasi Instalasi Dialisis'],
        ];

        // Cari unit Instalasi Dialisis
        $dialisisUnit = UnitKerja::where('nama', 'INST DIALISIS')->first();

        if (!$dialisisUnit) {
            $this->command->error('Unit Instalasi Dialisis tidak ditemukan!');
            return;
        }

        foreach ($dialisisMembers as $member) {
            $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $dialisisUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user Instalasi Dialisis berhasil dijalankan.');

        // Data untuk unit IRJ
        $irjMembers = [
            // ['name' => 'drg Amalia Rahmanniar', 'role' => 'Kepala Instalasi', 'jabatan' => 'Kepala Instalasi Rawat Jalan'],
            // ['name' => 'Desi Norma W', 'role' => 'Kepala Ruang', 'jabatan' => 'Karu Instalasi Rawat Jalan'],
            ['name' => 'Desi Norma W', 'role' => 'Kepala Unit', 'jabatan' => 'Karu Instalasi Rawat Jalan'],
            ['name' => 'Ais Oktavina', 'jabatan' => 'Perawat Gigi Pelaksana'],
            ['name' => 'Yunie Sushanti', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Erviani Ratna P', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Dwi Sulistyo P', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Anugrah Noviani', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Roni Wandoyo', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Arni', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Bety Tinaria', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Budi Hastuti', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Ratnaningrum', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Diyah Tika Ariani', 'jabatan' => 'Bidan Pelaksana'],
            ['name' => 'Ayu Putri Purnaningsih', 'jabatan' => 'Bidan Pelaksana'],
            ['name' => 'Raihanah Al Mahdiyyah', 'jabatan' => 'Perawat Gigi Pelaksana'], //Kontrak
            ['name' => 'Suparman', 'jabatan' => 'Terapis Komplementer'],
            ['name' => 'Charomatul Amanah', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Miftahul Falah', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Sri Wahyuni', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Nur Alviah', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Harinto', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Abdul Halim', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Feni Kustiani', 'jabatan' => 'Bidan Pelaksana'],
            ['name' => 'Sutiyah', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Indra Gunawan', 'jabatan' => 'Staf Instalasi Rawat Jalan'],
        ];

        // Cari unit IRJ
        $irjUnit = UnitKerja::where('nama', 'IRJ')->first();

        if (!$irjUnit) {
            $this->command->error('Unit IRJ tidak ditemukan!');
            return;
        }

        foreach ($irjMembers as $member) {
            $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $irjUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user IRJ berhasil dijalankan.');

        // Data untuk unit Perinatologi
        $perinatologiMembers = [
            ['name' => 'Ariyanti Retno A', 'role' => 'Kepala Ruang', 'jabatan' => 'Karu Perinatologi'],
            ['name' => 'Laila Oktavia', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Resha Oktaviani.,S.Kep Ns', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Murni Nurdiyanti', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Meli Roshidatul Fajriyah', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Lina Ernawati', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Siti Nurhidayah', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Yusefi Verawati, Amd.Kep', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Yurika Fian K', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Ani Kurniati', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Tarwiyah', 'jabatan' => 'Pekarya Kesehatan Ruang VK dan Perinatologi'], //Pekarya Doble
        ];

        // Cari unit Perinatologi
        $perinatologiUnit = UnitKerja::where('nama', 'PERINATOLOGI')->first();

        if (!$perinatologiUnit) {
            $this->command->error('Unit Perinatologi tidak ditemukan!');
            return;
        }

        foreach ($perinatologiMembers as $member) {
            $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $perinatologiUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user Perinatologi berhasil dijalankan.');

        // Data untuk unit VK
        $vkMembers = [
            ['name' => 'Widanti Kusuma', 'role' => 'Kepala Ruang', 'jabatan' => 'Karu VK'],
            ['name' => 'Dian Septiana', 'jabatan' => 'Bidan Pelaksana'],
            ['name' => 'Angelia Resthy Ratnawati', 'jabatan' => 'Bidan Pelaksana'],
            ['name' => 'Nur Anisa Apriliani', 'jabatan' => 'Bidan Pelaksana'],
            ['name' => 'Hana Putri', 'jabatan' => 'Bidan Pelaksana'],
            ['name' => 'Vega Rizkawati', 'jabatan' => 'Bidan Pelaksana'],
            ['name' => 'Elga Wulandari', 'jabatan' => 'Bidan Pelaksana'],
            ['name' => 'Andini Kurniasih', 'jabatan' => 'Bidan Pelaksana'],
            ['name' => 'Uswatun Khasanah', 'jabatan' => 'Bidan Pelaksana'],
            ['name' => 'Dwi Apriliyani', 'jabatan' => 'Bidan Pelaksana'],
            ['name' => 'Reni Windi Astuti', 'jabatan' => 'Bidan Pelaksana'],
            ['name' => 'Melia Dwi Setiawati', 'jabatan' => 'Bidan Pelaksana'],
            ['name' => 'Endah Suryani', 'jabatan' => 'Bidan Pelaksana'],
            ['name' => 'Umu Khoiriyah', 'jabatan' => 'Bidan Pelaksana'],
            ['name' => 'Lestari Anggit Setyowati', 'jabatan' => 'Bidan Pelaksana'],
            ['name' => 'Rokhaniyah .,A.Md Keb', 'jabatan' => 'Bidan Pelaksana'],
            ['name' => 'Cici Oviani Agustina.,A.Md Keb', 'jabatan' => 'Bidan Pelaksana'],
            ['name' => 'Eti Yuliana', 'jabatan' => 'Bidan Pelaksana'],
            ['name' => 'Umdatul Ilmi', 'jabatan' => 'Pekarya Kesehatan Ruang VK dan Perinatologi'], //pekarya double
        ];

        // Cari unit VK
        $vkUnit = UnitKerja::where('nama', 'VK')->first();

        if (!$vkUnit) {
            $this->command->error('Unit VK tidak ditemukan!');
            return;
        }

        foreach ($vkMembers as $member) {
            $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $vkUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user VK berhasil dijalankan.');

        // Data untuk unit ALZAITUN
        $alzaitunMembers = [
            ['name' => 'Wahyu Puspitasari', 'role' => 'Kepala Ruang', 'jabatan' => 'Karu Al Zaitun'],
            ['name' => 'Rinta Ermawati', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Liana Andriani', 'jabatan' => 'Bidan Pelaksana'],
            ['name' => 'Tri Herlina', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Preti Desiana', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Reni Desi Safitri', 'jabatan' => 'Bidan Pelaksana'],
            ['name' => 'Musrifah Afriyanti.,A.Md Keb', 'jabatan' => 'Bidan Pelaksana'], //kontrak
            ['name' => 'Meilia Suharya N', 'jabatan' => 'Bidan Pelaksana'],
            ['name' => 'Lili Alimah', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Fitroh Aulia Dina.,A.Md Keb', 'jabatan' => 'Bidan Pelaksana'],
            ['name' => 'Khusnul Khotimah', 'jabatan' => 'Bidan Pelaksana'],
            ['name' => 'Siti Solehah / Gunung Giana', 'jabatan' => 'Pekarya Kesehatan'],
            ['name' => 'Lie Ivani', 'jabatan' => 'Pekarya Kesehatan'],
            ['name' => 'Wahidatun Toyibah', 'jabatan' => 'Pekarya Kesehatan ALZAITUN DAN AT TAQWA'], //Pekarya Double
        ];

        // Cari unit ALZAITUN
        $alzaitunUnit = UnitKerja::where('nama', 'ALZAITUN')->first();

        if (!$alzaitunUnit) {
            $this->command->error('Unit ALZAITUN tidak ditemukan!');
            return;
        }

        foreach ($alzaitunMembers as $member) {
            $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $alzaitunUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user ALZAITUN berhasil dijalankan.');

        // Data untuk unit AT TAQWA
        $atTaqwaMembers = [
            ['name' => 'Susilo Rudatin', 'role' => 'Kepala Ruang', 'jabatan' => 'Karu At Taqwa'],
            ['name' => 'Imam Waizun', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Wahyu Nur Fitriyani.,S.Kep Ns', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Andika Susetyo Kaisar Putra.,S.Kep Ns', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Erdika Retno Wulandari.,A.Md Kep', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Badi Nur Waluyo, S.Kep.Ns.', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Yuyun Purwanti', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Slamet Uji Kurniawan.,S.Kep Ns', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Putri Puja Pangesti.,A.Md Kep', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Jodi Fajar Wiguna.,A.Md Kep', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Ahmad Sukro Hidayat', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Tri Mutmainah.,A.Md Kep', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Yuda Valentino.,S.Kep Ns', 'jabatan' => 'Perawat Pelaksana'], //kontrak
            ['name' => 'Rozaul Muta\'ali', 'jabatan' => 'Perawat Pelaksana'], //kontrak
            ['name' => 'Slamet Nikmat', 'jabatan' => 'Pekarya Kesehatan'],
            ['name' => 'Prana Sakti Ibnu Oetomo', 'jabatan' => 'Pekarya Kesehatan'], //kontrak
            // ['name' => 'Wahidatun Toyibah'],
        ];

        // Cari unit AT TAQWA
        $atTaqwaUnit = UnitKerja::where('nama', 'AT TAQWA')->first();

        if (!$atTaqwaUnit) {
            $this->command->error('Unit AT TAQWA tidak ditemukan!');
            return;
        }

        foreach ($atTaqwaMembers as $member) {
            $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $atTaqwaUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user AT TAQWA berhasil dijalankan.');

        // Data untuk unit ASSALAM
        $assalamMembers = [
            ['name' => 'Indriyana Keliek F', 'role' => 'Kepala Ruang', 'jabatan' => 'Karu Assalam'],
            ['name' => 'Purwadi', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Rini Wijayanti', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Diding Panca P.,A.Md Kep', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Diyah Enggar Tri', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Riana Dwi Agustina.,S.Kep Ns', 'jabatan' => 'Perawat Pelaksana'], //kontrak
            ['name' => 'Yunut Jenianto', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Ikhsan Saifudin.,S.Kep Ns', 'jabatan' => 'Perawat Pelaksana'], //kontrak
            ['name' => 'Dwi Yulianti.,S.Kep Ns', 'jabatan' => 'Perawat Pelaksana'], //kontrak
            ['name' => 'Khomsa Fadillah R', 'jabatan' => 'Perawat Pelaksana'], //kontrak
            ['name' => 'Irma Damayanti', 'jabatan' => 'Pekarya Kesehatan'],
            ['name' => 'Slamet Prihatin', 'jabatan' => 'Pekarya Kesehatan'],
            ['name' => 'Atsari Robihah', 'jabatan' => 'Pekarya Kesehatan'],
        ];

        // Cari unit ASSALAM
        $assalamUnit = UnitKerja::where('nama', 'ASSALAM')->first();

        if (!$assalamUnit) {
            $this->command->error('Unit ASSALAM tidak ditemukan!');
            return;
        }

        foreach ($assalamMembers as $member) {
            $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $assalamUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user ASSALAM berhasil dijalankan.');

        // Data untuk unit AL AMIN
        $alAminMembers = [
            ['name' => 'Siti Markhamah', 'role' => 'Kepala Ruang', 'jabatan' => 'Karu Al Amin'],
            ['name' => 'Deni Amrulloh', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Buyung Pambudi', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Tri Ningsih NawangSasi.,S.Kep Ns', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Ina Karunia', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Gayuh Dwi Laksono.,S.Kep Ns', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Rian Diah Utami', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Nigeffe Pasalaila', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Haris Naufal Faizi.,S.Kep Ns', 'jabatan' => 'Perawat Pelaksana'], //kontrak
            ['name' => 'Wahidun', 'jabatan' => 'Pekarya Kesehatan'],
            ['name' => 'Tekad Setiawan', 'jabatan' => 'Pekarya Kesehatan'],
        ];

        // Cari unit AL AMIN
        $alAminUnit = UnitKerja::where('nama', 'AL AMIN')->first();

        if (!$alAminUnit) {
            $this->command->error('Unit AL AMIN tidak ditemukan!');
            return;
        }

        foreach ($alAminMembers as $member) {
            $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $alAminUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user AL AMIN berhasil dijalankan.');

        // Data untuk unit FIRDAUS
        $firdausMembers = [
            ['name' => 'Ika Sari Sholehati', 'role' => 'Kepala Ruang', 'jabatan' => 'Karu Firdaus'],
            ['name' => 'Syafira Diaz Maisyaroh', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Sinta Puspita', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Rini Utami', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Walyanti.,S.Kep Ns', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Rizki Dwi A.,S.Kep Ns', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Nony Marlina', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Septi Indriwati', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Rica Karomah, Amd.Kep.', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Laili Dwi Artati', 'jabatan' => 'Pekarya Kesehatan'],
            ['name' => 'Sudarmi', 'jabatan' => 'Pekarya Kesehatan'],
        ];

        // Cari unit FIRDAUS
        $firdausUnit = UnitKerja::where('nama', 'FIRDAUS')->first();

        if (!$firdausUnit) {
            $this->command->error('Unit FIRDAUS tidak ditemukan!');
            return;
        }

        foreach ($firdausMembers as $member) {
            $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $firdausUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user FIRDAUS berhasil dijalankan.');

        // Data untuk unit HAJI
        $hajiMembers = [
            ['name' => 'Umu Hani', 'role' => 'Kepala Ruang', 'jabatan' => 'Karu Haji'],
            ['name' => 'Neneng Susmas Netty', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Purnama Setya Cahyadi.,S.Kep Ns', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Tri Susanto.,A.Md Kep', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Siti Azizah.,A.Md Kep', 'jabatan' => 'Perawat Pelaksana'], //kontrak
            ['name' => 'Windu Kusuma W', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Etha Setyana', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Dwi Sukur W', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Ratna Sulistiyani.,A.Md Kep', 'jabatan' => 'Perawat Pelaksana'], //kontrak
            ['name' => 'Akhmad Sulukhi', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Abto Deswar Diansyah.,A.Md Kep', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Adistia Yunita Nurfaega.,A.Md Kep', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Feli Tri Yuliana.,S.Kep Ns', 'jabatan' => 'Perawat Pelaksana'], //kontrak
            ['name' => 'Ridwan Saputra', 'jabatan' => 'Perawat Pelaksana'], //kontrak
            ['name' => 'Gugun Galuh Rahmanmaulana', 'jabatan' => 'Perawat Pelaksana'], //kontrak
            ['name' => 'I Wayan Arianto', 'jabatan' => 'Pekarya Kesehatan'],
            ['name' => 'Tri Handoko', 'jabatan' => 'Pekarya Kesehatan'],
            ['name' => 'Choerul Elma Subarkah', 'jabatan' => 'Pekarya Kesehatan HAJI DAN AL AMIN'], //pakarya double
        ];

        // Cari unit HAJI
        $hajiUnit = UnitKerja::where('nama', 'HAJI')->first();

        if (!$hajiUnit) {
            $this->command->error('Unit HAJI tidak ditemukan!');
            return;
        }

        foreach ($hajiMembers as $member) {
            $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $hajiUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user HAJI berhasil dijalankan.');

        // Data untuk unit ASSYFA
        $assyfaMembers = [
            ['name' => 'Ifa Fitria', 'role' => 'Kepala Ruang', 'jabatan' => 'Karu Assyfa'],
            ['name' => 'Fuandraeni Faslihatun', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Titi Yuli Anggraeni', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Cahya Indra Lukmana, S.Kep.Ns.', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Ferawati Trianasari', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Febtriyanto, S.Kep.Ns.', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Sasmita Ilmi F', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Drajat Wahyu I.,A.Md Kep', 'jabatan' => 'Perawat Pelaksana'], //PHK
            ['name' => 'Deni Ambang.,A.Md Kep', 'jabatan' => 'Perawat Pelaksana'], //PHK
            ['name' => 'Sisanto', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Aprilia Atita Bella Adila, Amd.Kep.', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Vendrha Zani Zegal', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Didik Prapto Sasongko,S.Kep Ns', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Tiara Arindha Wibowo.,S.Kep Ns', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Wulan Tri Mulyani.,S.Kep Ns', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Vicky Indra Wibowo', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Dendi Wahyu S', 'jabatan' => 'Pekarya Kesehatan'],
            ['name' => 'Anggoro Panggih', 'jabatan' => 'Pekarya Kesehatan'],
        ];

        // Cari unit ASSYFA
        $assyfaUnit = UnitKerja::where('nama', 'ASSYFA')->first();

        if (!$assyfaUnit) {
            $this->command->error('Unit ASSYFA tidak ditemukan!');
            return;
        }

        foreach ($assyfaMembers as $member) {
            $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $assyfaUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user ASSYFA berhasil dijalankan.');

        // Data untuk unit AZIZIAH
        $aziziahMembers = [
            ['name' => 'Slamet Supratomo', 'role' => 'Kepala Ruang', 'jabatan' => 'Karu Aziziah'],
            ['name' => 'Agus Suprihanto', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Yanuar Puthut Wijonarko', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Suryo Aji', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Ediy Santosa', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Wahyu Sri Sadono', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Feri Susanto', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Dian Ratnasari', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Maita Indah P', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Nuripan', 'jabatan' => 'Pekarya Kesehatan'],
            ['name' => 'Dika Adi Saputra', 'jabatan' => 'Pekarya Kesehatan'],
            ['name' => 'Fajar Tri Pambudi', 'jabatan' => 'Pekarya Kesehatan Ruang Assyfa dan Aziziah'],
        ];

        // Cari unit AZIZIAH
        $aziziahUnit = UnitKerja::where('nama', 'AZIZIAH')->first();

        if (!$aziziahUnit) {
            $this->command->error('Unit AZIZIAH tidak ditemukan!');
            return;
        }

        foreach ($aziziahMembers as $member) {
            $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $aziziahUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user AZIZIAH berhasil dijalankan.');

        // Data untuk unit ALMUNAWAROH
        $almunawarohMembers = [
            ['name' => 'Latifah', 'role' => 'Kepala Ruang', 'jabatan' => 'Karu Al Munawaroh'],
            ['name' => 'Wiwi Kusniati', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Muhammad Sukur', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Emi Dwi Listia', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Eka Nur Fitri Apriliyani', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Catur Noviantiko', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Zuhri Nikmatuloh Zulfikar', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Faizal Tamim Al- Mundziri, Amd.Kep.', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Eling Tiyasari.,A.Md Kep', 'jabatan' => 'Perawat Pelaksana'],
            ['name' => 'Khasiful Fuad', 'jabatan' => 'Pekarya Kesehatan'],
            ['name' => 'Samudin', 'jabatan' => 'Pekarya Kesehatan'],
        ];

        // Cari unit ALMUNAWAROH
        $almunawarohUnit = UnitKerja::where('nama', 'ALMUNAWAROH')->first();

        if (!$almunawarohUnit) {
            $this->command->error('Unit ALMUNAWAROH tidak ditemukan!');
            return;
        }

        foreach ($almunawarohMembers as $member) {
            $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $almunawarohUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user ALMUNAWAROH berhasil dijalankan.');

        // Data untuk unit INST REHAB MEDIK
        $rehabMedikMembers = [
            ['name' => 'Dr. Tegar Harputra Raya.,Sp KFR7', 'role' => 'Kepala Instalasi', 'jabatan' => 'Dokter Spesialis Rehabilitasi Medik + Ka. Instalasi Rehabilitasi Medik'], //part time
            ['name' => 'Slamet Budi Santosa', 'role' => 'Kepala Ruang', 'jabatan' => 'Karu Instalasi Rehabilitasi Medik'],
            ['name' => "Lu'lu u Al Hikmah.,A.Md Fis", 'jabatan' => 'Fisioterapis'],
            ['name' => 'Dhanti Wahyundari.,Ftr', 'jabatan' => 'Fisioterapis'],
            ['name' => 'Ucik Auliya.,A.Md Kes', 'jabatan' => 'Okupasi Terapi'],
        ];

        // Cari unit INST REHAB MEDIK
        $rehabMedikUnit = UnitKerja::where('nama', 'INST REHAB MEDIK')->first();

        if (!$rehabMedikUnit) {
            $this->command->error('Unit INST REHAB MEDIK tidak ditemukan!');
            return;
        }

        foreach ($rehabMedikMembers as $member) {
            $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $rehabMedikUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user INST REHAB MEDIK berhasil dijalankan.');

        // Data untuk unit CASE MANAGER
        $caseManagerMembers = [
            ['name' => 'Ari Fitria', 'role' => 'Koordinator', 'jabatan' => 'Koordinator MPP'],
            ['name' => 'Didik Setiawan', 'jabatan' => 'MPP'],
            ['name' => 'Tzalis Ubaidillah', 'jabatan' => 'MPP'],
            ['name' => 'Ari Yogo P', 'jabatan' => 'MPP'],
            ['name' => 'Ismi Ngaisatun', 'jabatan' => 'MPP'],
            ['name' => 'Andang Pramana', 'jabatan' => 'MPP'],
        ];

        // Cari unit CASE MANAGER
        $caseManagerUnit = UnitKerja::where('nama', 'CASE MANAGER')->first();

        if (!$caseManagerUnit) {
            $this->command->error('Unit CASE MANAGER tidak ditemukan!');
            return;
        }

        foreach ($caseManagerMembers as $member) {
            $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $caseManagerUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user CASE MANAGER berhasil dijalankan.');

        // Data untuk unit INST REKAM MEDIK
        $rekamMedikMembers = [
            ['name' => 'Wigati', 'role' => 'Kepala Instalasi', 'jabatan' => 'Ka. Instalasi Rekam Medik'],
            ['name' => 'Deka Prasetiyanti', 'jabatan' => 'Staf Instalasi Rekam Medik'],
            ['name' => 'Lina Sandyasari', 'jabatan' => 'Asembling'],
            ['name' => 'Lina Afiyanti', 'jabatan' => 'Koding'],
            ['name' => 'Meiga Kencana Putri.,A.Md', 'jabatan' => 'Koding'],
            ['name' => 'Andi Gunawan', 'jabatan' => 'Informasi + Filling'],
            ['name' => 'Eric Setiawan', 'jabatan' => 'Informasi + Filling'],
            ['name' => 'Kavi Nurul Firdaus', 'jabatan' => 'Pendaftaran'],
            ['name' => 'Gayuh Prasetyo', 'jabatan' => 'Pendaftaran'],
            ['name' => 'Agus Waluyo', 'jabatan' => 'Pendaftaran'],
            ['name' => 'Suci Rahmawati', 'jabatan' => 'Pendaftaran'],
            ['name' => 'Rifka Winda Listanti', 'jabatan' => 'Pendaftaran'],
            ['name' => 'Novi Purbasari', 'jabatan' => 'Pendaftaran'],
            ['name' => 'Angga Putra Pratama', 'jabatan' => 'Pendaftaran'],
            ['name' => 'Wagianto', 'jabatan' => 'Pendaftaran'],
            ['name' => 'Nurul Fatiah', 'jabatan' => 'Pendaftaran'],
            ['name' => 'Zulfa Nurmutaqin', 'jabatan' => 'Pendaftaran'],
            ['name' => 'Hamid Badawi Hasan', 'jabatan' => 'Pendaftaran'],
            ['name' => 'Dzaky Arhiska Daffa', 'jabatan' => 'Pendaftaran'],
            ['name' => 'Puji Lestari', 'jabatan' => 'Petugas Entry SKU'],
        ];

        // Cari unit INST REKAM MEDIK
        $rekamMedikUnit = UnitKerja::where('nama', 'INST REKAM MEDIK')->first();

        if (!$rekamMedikUnit) {
            $this->command->error('Unit INST REKAM MEDIK tidak ditemukan!');
            return;
        }

        foreach ($rekamMedikMembers as $member) {
            $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $rekamMedikUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user INST REKAM MEDIK berhasil dijalankan.');

        // Data untuk unit FARMASI
        $farmasiMembers = [
            ['name' => 'Uniek Setyawardani', 'role' => 'Kepala Instalasi', 'jabatan' => 'Ka. Instalasi Farmasi'],
            ['name' => 'Rizqi Ayu Amalina., S.Farm Apt', 'jabatan' => 'Apoteker'],
            ['name' => 'Tri Wahyu Yuni Kosiah', 'jabatan' => 'Apoteker'],
            ['name' => 'Nuzul Ayu Pangestika', 'jabatan' => 'Apoteker'],
            ['name' => 'Desiana Nur Handayani', 'jabatan' => 'Apoteker'],
            ['name' => 'Afriliana Nurahimah', 'jabatan' => 'Apoteker'],
            ['name' => 'Dika Destiani.,S.Farm Apt', 'jabatan' => 'Apoteker'],
            ['name' => 'Faizatul Istiqomah .,S.Farm Apt', 'jabatan' => 'Apoteker'],
            ['name' => 'Yunika Wulansari', 'jabatan' => 'Pelaksana Teknis Kefarmasian'],
            ['name' => 'Edy Purwanto', 'jabatan' => 'Pelaksana Teknis Kefarmasian'],
            ['name' => 'Susi Susanti', 'jabatan' => 'Pelaksana Teknis Kefarmasian'],
            ['name' => 'Sabaniah Dwi H', 'jabatan' => 'Pelaksana Teknis Kefarmasian'],
            ['name' => 'Wiwin Nur Supriyanti', 'jabatan' => 'Pelaksana Teknis Kefarmasian'],
            ['name' => 'Febri Zaeni Ikhsan', 'jabatan' => 'Pelaksana Teknis Kefarmasian'],
            ['name' => 'Tri Hidayati', 'jabatan' => 'Pelaksana Teknis Kefarmasian'],
            ['name' => 'Octamiarso Eko R', 'jabatan' => 'Pelaksana Teknis Kefarmasian'],
            ['name' => 'Saguh Pambudi', 'jabatan' => 'Pelaksana Teknis Kefarmasian'],
            ['name' => 'Alifah Zovia Mordan', 'jabatan' => 'Pelaksana Teknis Kefarmasian'],
            ['name' => 'Widaryati', 'jabatan' => 'Pelaksana Teknis Non Kefarmasian'],
            ['name' => 'Siti Solekhah / Andang', 'jabatan' => 'Pelaksana Teknis Non Kefarmasian'],
            ['name' => 'Irma Okida Anggraeni', 'jabatan' => 'Pelaksana Teknis Non Kefarmasian'],
            ['name' => 'Titin Lestari', 'jabatan' => 'Staf Instalasi Farmasi'],
            ['name' => 'Purwaningsih.,A.Md Farm', 'jabatan' => 'Tenaga Teknis Kefarmasian'],
            ['name' => 'Damar Dwi Sasongko', 'jabatan' => 'Staf Instalasi Farmasi'], //KONTRAK
        ];

        // Cari unit INST FARMASI
        $farmasiUnit = UnitKerja::where('nama', 'INST FARMASI')->first();

        if (!$farmasiUnit) {
            $this->command->error('Unit INST FARMASI tidak ditemukan!');
            return;
        }

        foreach ($farmasiMembers as $member) {
            $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $farmasiUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user INST FARMASI berhasil dijalankan.');

        // Data untuk unit INST RADIOLOGI
        $radiologiMembers = [
            ['name' => 'Dr. Febi Pramono., Sp. Rad', 'role' => 'Kepala Instalasi', 'jabatan' => 'Ka. Instalasi Radiologi'], //part time
            ['name' => 'Wisnu Kuncahyo', 'role' => 'Kepala Ruang', 'jabatan' => 'Karu Instalasi Radiologi'],
            ['name' => 'Fatkhur Rokhman', 'jabatan' => 'Radiografer'],
            ['name' => 'Lulu Khoirunita l', 'jabatan' => 'Radiografer'],
            ['name' => 'Diah Larasati W', 'jabatan' => 'Radiografer'],
            ['name' => 'Nur Yamin.,A.Md Rad', 'jabatan' => 'Radiografer'],
            ['name' => 'Yuniasih Kurniawati', 'jabatan' => 'Radiografer'],
        ];

        // Cari unit INST RADIOLOGI
        $radiologiUnit = UnitKerja::where('nama', 'INST RADIOLOGI')->first();

        if (!$radiologiUnit) {
            $this->command->error('Unit INST RADIOLOGI tidak ditemukan!');
            return;
        }

        foreach ($radiologiMembers as $member) {
            $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $radiologiUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user INST RADIOLOGI berhasil dijalankan.');

        // Data untuk unit INST LABORATORIUM
        $laboratoriumMembers = [
            ['name' => 'Dr. Trinovia Andayaningsih.,Sp PK', 'role' => 'Kepala Instalasi', 'jabatan' => 'Dokter Spesialis Patologi Klinik+ Ka. Instalasi Labroratorium'], //part time
            ['name' => 'Joko Sugiharto', 'role' => 'Kepala Ruang', 'jabatan' => 'Karu Instalasi Labroatorium'],
            ['name' => 'Eka Prihartiningsih.,A.Md AK', 'jabatan' => 'Analis Kesehatan'],
            ['name' => 'Wahyu Setiyo W.,A.Md AK', 'jabatan' => 'Analis Kesehatan'],
            ['name' => 'Vita Dwi Mulatsih', 'jabatan' => 'Analis Kesehatan'],
            ['name' => 'Laelly Yuni Sugesty', 'jabatan' => 'Analis Kesehatan'],
            ['name' => 'Maya Irenne Ratu', 'jabatan' => 'Analis Kesehatan'],
            ['name' => 'Nia Musaadah', 'jabatan' => 'Analis Kesehatan'],
            ['name' => 'Devi Novita Triana', 'jabatan' => 'Analis Kesehatan'],
            ['name' => 'Nur Hayati', 'jabatan' => 'Analis Kesehatan'],
            ['name' => 'Ismanto', 'jabatan' => 'Analis Kesehatan'],
            ['name' => 'Yuniara Fika Tri P', 'jabatan' => 'Analis Kesehatan'],
            ['name' => 'Zaenal Arifin', 'jabatan' => 'Analis Kesehatan'],
            ['name' => 'Argandari.,A.Md AK', 'jabatan' => 'Analis Kesehatan'],
            ['name' => 'Tyas Asalwa Nabila', 'jabatan' => 'Analis Kesehatan'],
            ['name' => 'Rosna Erviana', 'role' => 'Administrator', 'jabatan' => 'Staf Administrasi'],
            ['name' => 'Diaz Cagar Biru Langit', 'role' => 'Administrator', 'jabatan' => 'Staf Administrasi'],
            ['name' => 'Alfina Reinada Hapsari.,A.Md AK', 'jabatan' => 'Teknisi Bank Darah'],
        ];

        // Cari unit INST LABORATORIUM
        $laboratoriumUnit = UnitKerja::where('nama', 'INST LABORATORIUM')->first();

        if (!$laboratoriumUnit) {
            $this->command->error('Unit INST LABORATORIUM tidak ditemukan!');
            return;
        }

        foreach ($laboratoriumMembers as $member) {
            $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $laboratoriumUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user INST LABORATORIUM berhasil dijalankan.');

        // Data untuk unit INST SANITASI
        $sanitasiMembers = [
            ['name' => 'A Imam Mutaqin', 'role' => 'Kepala Instalasi', 'jabatan' => 'Ka. Instalasi Sanitasi'],
            ['name' => 'Ardi Febriyanto', 'jabatan' => 'IPAL'],
            ['name' => 'Sekar Antik Larasati', 'jabatan' => 'Sanitarian'],
            ['name' => 'Adiyono', 'jabatan' => 'Cleaning Service'],
            ['name' => 'Aji Widianto', 'jabatan' => 'Cleaning Service'],
            ['name' => 'Gesit Purnama Ghyan', 'jabatan' => 'Cleaning Service'],
            ['name' => 'Muhamad Saefuloh', 'jabatan' => 'Cleaning Service'],
            ['name' => 'Agus Sutomo', 'jabatan' => 'Cleaning Service'],
            ['name' => 'Setya Budi', 'jabatan' => 'Cleaning Service'],
            ['name' => 'Kamil Zulfikar', 'jabatan' => 'Cleaning Service'],
            ['name' => 'Bambang Hermanto', 'jabatan' => 'Staf Instalasi Sanitasi'],
            ['name' => 'Khayatno Setiawan', 'jabatan' => 'Staf Instalasi Sanitasi'],
        ];

        // Cari unit INST SANITASI
        $sanitasiUnit = UnitKerja::where('nama', 'INST SANITASI')->first();

        if (!$sanitasiUnit) {
            $this->command->error('Unit INST SANITASI tidak ditemukan!');
            return;
        }

        foreach ($sanitasiMembers as $member) {
            $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $sanitasiUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user INST SANITASI berhasil dijalankan.');

        // Data untuk unit INST CSSD
        $cssdMembers = [
            ['name' => 'Khamidah', 'role' => 'Kepala Instalasi', 'jabatan' => 'Ka. Instalasi CSSD'],
            ['name' => 'M. Agung Prastowo', 'jabatan' => 'Staf CSSD'],
            ['name' => 'Ipung Prayogi', 'jabatan' => 'Staf CSSD'],
            ['name' => 'Fajar Suryo Purnomo', 'jabatan' => 'Staf CSSD'],
            ['name' => 'Edi Priyanto', 'jabatan' => 'Staf CSSD'],
        ];

        // Cari unit INST CSSD
        $cssdUnit = UnitKerja::where('nama', 'INST CSSD')->first();

        if (!$cssdUnit) {
            $this->command->error('Unit INST CSSD tidak ditemukan!');
            return;
        }

        foreach ($cssdMembers as $member) {
            $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $cssdUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user INST CSSD berhasil dijalankan.');

        // Data untuk unit INST PEML SARPRAS
        $sarprasMembers = [
            ['name' => 'Widodo Pindah Riyanto', 'role' => 'Kepala Instalasi', 'jabatan' => 'Ka. Instalasi Peml. Sarpras'],
            ['name' => 'Agus Riyanto', 'jabatan' => 'Staf Instalasi Peml. Sarpras'],
            ['name' => 'Agus Rahmat S', 'jabatan' => 'Staf Instalasi Peml. Sarpras'],
            ['name' => 'Nur Fauzi Achmad', 'jabatan' => 'Staf Instalasi Peml. Sarpras'],
            ['name' => 'M.Soleman', 'jabatan' => 'Staf Instalasi Peml. Sarpras'],
            ['name' => 'Febriyanto', 'jabatan' => 'Staf Instalasi Peml. Sarpras'],
            ['name' => 'Rizal Muntadlo', 'jabatan' => 'Staf Instalasi Peml. Sarpras'],
            ['name' => 'Achmad Anton Triyono', 'jabatan' => 'Staf Instalasi Peml. Sarpras'],
        ];

        // Cari unit INST PEML SARPRAS
        $sarprasUnit = UnitKerja::where('nama', 'INST PEML SARPRAS')->first();

        if (!$sarprasUnit) {
            $this->command->error('Unit INST PEML SARPRAS tidak ditemukan!');
            return;
        }

        foreach ($sarprasMembers as $member) {
            $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $sarprasUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user INST PEML SARPRAS berhasil dijalankan.');

        // Data untuk unit INST GAS MEDIK & ALKES
        $gasMedikAlkesMembers = [
            ['name' => 'Adityana Juni Saputra.,A.Md', 'jabatan' => 'Elektromedis'],
            ['name' => 'Puji Triono', 'jabatan' => 'Staf Instalasi Gas Medik dan Alkes'],
        ];

        // Cari unit INST GAS MEDIK & ALKES
        $gasMedikAlkesUnit = UnitKerja::where('nama', 'INST GAS MEDIK & ALKES')->first();

        if (!$gasMedikAlkesUnit) {
            $this->command->error('Unit INST GAS MEDIK & ALKES tidak ditemukan!');
            return;
        }

        foreach ($gasMedikAlkesMembers as $member) {
            $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $gasMedikAlkesUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user INST GAS MEDIK & ALKES berhasil dijalankan.');



        // Data untuk unit UNIT MCU & POSKES
        $mcuPoskesMembers = [
            ['name' => 'Ruslan', 'role' => 'Kepala Unit', 'jabatan' => 'Ka. Unit MCU dan Poskes'],
            ['name' => 'Mamat Setiawan', 'jabatan' => 'Perawat Pelaksana Poskes dr. Nono'],
            ['name' => 'Robby Ilmiawan', 'role' => 'Administrator', 'jabatan' => 'Staf Administrasi Unit MCU dan Poskes'],
        ];

        // Cari unit UNIT MCU & POSKES
        $mcuPoskesUnit = UnitKerja::where('nama', 'MCU & POSKES')->first();

        if (!$mcuPoskesUnit) {
            $this->command->error('Unit MCU & POSKES tidak ditemukan!');
            return;
        }

        foreach ($mcuPoskesMembers as $member) {
            $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $mcuPoskesUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user UNIT MCU & POSKES berhasil dijalankan.');

        // Data untuk unit UNIT TRANSPORTASI
        $transportasiMembers = [
            ['name' => 'Durul Farid', 'role' => 'Kepala Unit', 'jabatan' => 'Ka. Unit Transportasi'],
            ['name' => 'Arif Suhendra', 'role' => 'Kepala Unit', 'jabatan' => 'Ka. Unit Ambulance'],
            ['name' => 'Afria Sofan', 'jabatan' => 'Staf Unit Transportasi dan Ambulance'],
            ['name' => 'Suwarno', 'jabatan' => 'Staf Unit Transportasi dan Ambulance'],
            ['name' => 'Rakhmat Mubasyier', 'jabatan' => 'Staf Unit Transportasi dan Ambulance'],
            ['name' => 'M.Rofik', 'jabatan' => 'Staf Unit Transportasi dan Ambulance'],
            ['name' => 'Margo Nursuwono', 'jabatan' => 'Staf Unit Transportasi dan Ambulance'],
            ['name' => 'M.Ari Arif H', 'jabatan' => 'Staf Unit Transportasi dan Ambulance'],
            ['name' => 'Sarwidi', 'jabatan' => 'Staf Unit Transportasi dan Ambulance + Anggota Komite K3RS'],
        ];

        // Cari unit UNIT TRANSPORTASI
        $transportasiUnit = UnitKerja::where('nama', 'TRANSPORTASI')->first();

        if (!$transportasiUnit) {
            $this->command->error('Unit TRANSPORTASI tidak ditemukan!');
            return;
        }

        foreach ($transportasiMembers as $member) {
            $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $transportasiUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user UNIT TRANSPORTASI berhasil dijalankan.');

        // Data untuk unit INST GIZI
        $giziMembers = [
            ['name' => 'Pujiningsih', 'role' => 'Kepala Instalasi', 'jabatan' => 'Ka. Instalasi Gizi'],
            ['name' => 'Ulfahul Hani', 'jabatan' => 'Ahli Gizi'],
            ['name' => 'Tri Rahayu', 'jabatan' => 'Koordinator Gudang'],
            ['name' => 'Musringah', 'jabatan' => 'Koordinator Distribusi'],
            ['name' => 'Asih Setyowati', 'jabatan' => 'Pelaksana Masak'],
            ['name' => 'Retno Winarni', 'jabatan' => 'Pramusaji'],
            ['name' => 'Asri Widyaningrum', 'jabatan' => 'Pramusaji'],
            ['name' => 'Sri Yanti', 'jabatan' => 'Pramusaji'],
            ['name' => 'Eka Yuhriana', 'jabatan' => 'Pramusaji'],
            ['name' => 'Nur Aeni Istiqomah', 'jabatan' => 'Pramusaji'],
            ['name' => 'Lutfia Mega', 'jabatan' => 'Pramusaji'],
            ['name' => 'Ari Rahmawati', 'jabatan' => 'Pramusaji'],
            ['name' => 'Veri Aryanti', 'jabatan' => 'Pramusaji'],
            ['name' => 'Vieky Amalia', 'jabatan' => 'Pramusaji'],
            ['name' => 'Hastasari Ayuning tiyas', 'jabatan' => 'Pramusaji'],
            ['name' => 'Nur Rokhmah', 'jabatan' => 'Pramusaji'],
            ['name' => 'Dwi Anggorowati', 'jabatan' => 'Pramusaji'],
            ['name' => 'Irsyad Khusni', 'jabatan' => 'Pramusaji'],
            ['name' => 'Umar Hasanudin', 'jabatan' => 'Pramusaji'],
        ];

        // Cari unit INST GIZI
        $giziUnit = UnitKerja::where('nama', 'INST GIZI')->first();

        if (!$giziUnit) {
            $this->command->error('Unit INST GIZI tidak ditemukan!');
            return;
        }

        foreach ($giziMembers as $member) {
            $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $giziUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user INST GIZI berhasil dijalankan.');

        // Data untuk unit UNIT PJBR
        $pjbrMembers = [
            ['name' => 'Toha', 'role' => 'Kepala Unit', 'jabatan' => 'Ka. Unit Pemulasaran Jenazah dan Binroh'],
            ['name' => 'Gunadi', 'jabatan' => 'Staf Unit Pemulasaran Jenazah dan Binroh'],
        ];

        // Cari unit UNIT PJBR
        $pjbrUnit = UnitKerja::where('nama', 'PJBR')->first();

        if (!$pjbrUnit) {
            $this->command->error('Unit PJBR tidak ditemukan!');
            return;
        }

        foreach ($pjbrMembers as $member) {
            $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $pjbrUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user UNIT PJBR berhasil dijalankan.');

        // Data untuk unit UNIT PENGELOLAAN LINEN
        $linenMembers = [
            ['name' => 'Dr. Kartiko Sumartoyo.,Sp U', 'role' => 'Manager', 'jabatan' => 'Wadir Umum dan Keuangan'],
            ['name' => 'Budiono', 'role' => 'Kepala Unit', 'jabatan' => 'Ka. Unit Pengelolaan Linen'],
            ['name' => 'Hari Nugroho', 'jabatan' => 'Staf Unit Pengelolaan Linen'],
            ['name' => 'Bariroh Ahmad', 'jabatan' => 'Staf Unit Pengelolaan Linen'],
            ['name' => 'Mustangin', 'jabatan' => 'Staf Unit Pengelolaan Linen'],
            ['name' => 'Rifin Supratman', 'jabatan' => 'Staf Unit Pengelolaan Linen'],
            ['name' => 'Agus Junaedi', 'jabatan' => 'Staf Unit Pengelolaan Linen'],
        ];

        // Cari unit UNIT PENGELOLAAN LINEN
        $linenUnit = UnitKerja::where('nama', 'PENGELOLAAN LINEN')->first();

        if (!$linenUnit) {
            $this->command->error('Unit PENGELOLAAN LINEN tidak ditemukan!');
            return;
        }

        foreach ($linenMembers as $member) {
            $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $linenUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user UNIT PENGELOLAAN LINEN berhasil dijalankan.');

        // Data untuk unit HUMAS & PROG RS
        $humasMembers = [
            ['name' => 'Sucahyo H.F', 'role' => 'Manager', 'jabatan' => 'Manajer Humas dan Program RS'],
            ['name' => 'Dr. Syarif Hadi ', 'role' => 'Kepala Seksi', 'jabatan' => 'Ka. Seksi Perencanaan dan Pengembangan'],

            ['name' => 'Ali Muakhor', 'jabatan' => 'Staf Seksi Perencanaan dan Pengembangan'],
            ['name' => 'Lusy Rustiyani.,S.Kep', 'jabatan' => 'Staf Seksi Perencanaan dan Pengembangan'],
            ['name' => 'Nur Ardi Firdosti', 'jabatan' => 'Staf Seksi Perencanaan dan Pengembangan'],

            ['name' => 'Wiwit Setia Bekti', 'role' => 'Kepala Seksi', 'jabatan' => 'Ka. Seksi Hukum dan Kerjasama'],
            ['name' => 'Irana Eka Wardana', 'jabatan' => 'Staf Seksi Hukum dan Kerjasama'],

            ['name' => 'Asri Dian Premitasari', 'role' => 'Kepala Seksi', 'jabatan' => 'Ka. Seksi Manajemen Informasi dan Pelaporan'],
            ['name' => 'Shinta Yunita Sari', 'jabatan' => 'Staf Seksi Manajemen Informasi dan Pelaporan'],
            ['name' => 'Lina Narulita', 'jabatan' => 'Staf Seksi Manajemen Informasi dan Pelaporan'],
            ['name' => 'Supriyanto', 'jabatan' => 'Staf Seksi Manajemen Informasi dan Pelaporan'],
        ];

        // Cari unit HUMAS & PROG RS
        $humasUnit = UnitKerja::where('nama', 'HUMAS & PROG RS')->first();

        if (!$humasUnit) {
            $this->command->error('Unit HUMAS & PROG RS tidak ditemukan!');
            return;
        }

        foreach ($humasMembers as $member) {
            $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $humasUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user HUMAS & PROG RS berhasil dijalankan.');

        // Data untuk unit SDM
        $sdmMembers = [
            ['name' => 'Dr. ardhi Oemar Agustjik', 'nip' => '000111',  'role' => 'Manager', 'jabatan' => 'Manajer SDM'],
            ['name' => 'Silih Prasetya', 'jabatan' => 'Staf Manajer SDM'],
            ['name' => 'Riris Afianto', 'role' => 'Kepala Seksi', 'jabatan' => 'Ka. Seksi Pendidikan, Pelatihan dan Pengembangan SDM'],
            ['name' => 'Mister', 'nip' => '111222', 'role' => 'Kepegawaian', 'jabatan' => 'Ka. Seksi Kepegawaian'],
            ['name' => 'Diana Melisawati', 'nip' => '222333', 'role' => 'Kepegawaian', 'jabatan' => 'Staf Seksi Kepegawaian'],
            ['name' => 'Dyah Novita Sari', 'nip' => '333444', 'role' => 'Kepegawaian', 'jabatan' => 'Staf Seksi Kepegawaian'],
            ['name' => 'Barkah Setiyani', 'nip' => '444555', 'role' => 'Kepegawaian', 'jabatan' => 'Staf Seksi Kepegawaian'],
            ['name' => 'Eko Santoso', 'nip' => '555666', 'role' => 'Kepegawaian', 'jabatan' => 'Staf Seksi Kepegawaian'],

            // ['name' => 'Barkah Setiyani', 'jabatan' => 'Staf Seksi Pendidikan, Pelatihan dan Pengembangan SDM'],      // data seeder asli
            // ['name' => 'Eko Santoso', 'jabatan' => 'Staf Seksi Kajian dan Budaya Islam'],

        ];

        // Cari unit SDM
        $sdmUnit = UnitKerja::where('nama', 'SDM')->first();

        if (!$sdmUnit) {
            $this->command->error('Unit SDM tidak ditemukan!');
            return;
        }

        foreach ($sdmMembers as $member) {
            $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');

            // Regex untuk menghapus gelar di awal (Dr., Prof., Ir., H., drg., dll) dan di akhir (Sp.An, S.Ked, M.T, Gaji, dll)
            $cleanName = preg_replace('/^(Dr\.|Prof\.|Ir\.|H\.|Gaji|S\.|A\.|drg\.)\s+|\s*,?\s*(Sp\.\w+|S\.\w+|M\.\w+|Gaji|A\.\w+|H\.\w+|Ir\.\w+|Dr\.\w+|Prof\.\w+|drg\.\w+)$/', '', $member['name']);

            // Hilangkan double space yang mungkin tersisa
            $cleanName = preg_replace('/\s+/', ' ', $cleanName);

            // Hapus spasi dan ubah ke lowercase untuk username
            $username = isset($member['nip']) ? strtolower(str_replace(' ', '', $cleanName)) : null;

            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'nip' => $member['nip'] ?? null,
                    'username' => $username,
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $sdmUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user SDM berhasil dijalankan.');


        // Data untuk unit AKUNTANSI
        $akuntansiMembers = [
            ['name' => 'Endah Lestari D', 'role' => 'Kepala Seksi', 'jabatan' => 'Manajer Keuangan+ Plt. Ka. Seksi Akuntansi'],
            ['name' => 'Nur Aini Oktaviani', 'jabatan' => 'Staf Seksi Akuntansi'],
            ['name' => 'Anissa Vista Tiara Wardhani', 'jabatan' => 'Staf Seksi Akuntansi'],
            ['name' => 'Entoek Puri W', 'jabatan' => 'Staf Seksi Akuntansi'],
        ];

        // Cari unit AKUNTANSI
        $akuntansiUnit = UnitKerja::where('nama', 'AKUNTANSI')->first();

        if (!$akuntansiUnit) {
            $this->command->error('Unit AKUNTANSI tidak ditemukan!');
            return;
        }

        foreach ($akuntansiMembers as $member) {
            $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $akuntansiUnit->id,
                    'jabatan_id' => $KategoriJabatan,

                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user AKUNTANSI berhasil dijalankan.');

        // Data untuk unit KEUANGAN
        $keuanganMembers = [
            ['name' => 'Nur Chalifah', 'role' => 'Kepala Seksi', 'jabatan' => 'Ka. Seksi Keuangan'],
            ['name' => 'Siti Maulidah', 'jabatan' => 'Staf Seksi Keuangan'],
            ['name' => 'Adinda Lionita Hidayah.,SE', 'jabatan' => 'Staf Seksi Keuangan'],
            ['name' => 'Eka Lestari', 'jabatan' => 'Staf Seksi Keuangan'],
            ['name' => 'Dini Inti Wahyuni', 'jabatan' => 'Staf Seksi Keuangan'],
        ];

        // Cari unit KEUANGAN
        $keuanganUnit = UnitKerja::where('nama', 'KEUANGAN')->first();

        if (!$keuanganUnit) {
            $this->command->error('Unit KEUANGAN tidak ditemukan!');
            return;
        }

        foreach ($keuanganMembers as $member) {
            $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $keuanganUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user AKUNTANSI berhasil dijalankan.');

        // Data untuk unit KEUANGAN
        $kasirMembers = [
            ['name' => 'Khodijah', 'jabatan' => 'Staf Seksi Keuangan (Kasir)'],
            ['name' => 'Sri Afti Cahyani', 'jabatan' => 'Staf Seksi Keuangan (Kasir)'],
            ['name' => 'Suci Prihatiyani', 'jabatan' => 'Staf Seksi Keuangan (Kasir)'],
            ['name' => 'Surya Eka Wardani', 'jabatan' => 'Staf Seksi Keuangan (Kasir)'],
            ['name' => 'Liana Yulianti', 'jabatan' => 'Staf Seksi Keuangan (Kasir)'],
            ['name' => 'Candra Bisma Abdul', 'jabatan' => 'Staf Seksi Keuangan (Kasir)'],
            ['name' => 'Dika Muzaqi Eka P', 'jabatan' => 'Staf Seksi Keuangan (Kasir)'],
        ];

        // Cari unit KASIR
        $kasirUnit = UnitKerja::where('nama', 'KASIR')->first();

        if (!$kasirUnit) {
            $this->command->error('Unit KASIR tidak ditemukan!');
            return;
        }

        foreach ($kasirMembers as $member) {
            $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $kasirUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user KEUANGAN berhasil dijalankan.');

        // Data untuk unit KASIR
        $kasirMembers = [
            ['name' => 'Khodijah'],
            ['name' => 'Sri Afti Cahyani'],
            ['name' => 'Suci Prihatiyani'],
            ['name' => 'Surya Eka Wardani'],
            ['name' => 'Liana Yulianti'],
            ['name' => 'Candra Bisma Abdul'],
            ['name' => 'Dika Muzaqi Eka P'],
        ];

        // Cari unit KASIR
        $kasirUnit = UnitKerja::where('nama', 'KASIR')->first();

        if (!$kasirUnit) {
            $this->command->error('Unit KASIR tidak ditemukan!');
            return;
        }

        foreach ($kasirMembers as $member) {
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $kasirUnit->id,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user KASIR berhasil dijalankan.');

        // Data untuk unit ASURANSI
        $asuransiMembers = [
            ['name' => 'Erlita Puspitasari', 'role' => 'Kepala Seksi', 'jabatan' => 'Ka. Seksi Asuransi'],
            ['name' => 'Dian Olivia Oktafiyanti', 'jabatan' => 'Staf Seksi Asuransi'],
            ['name' => 'Edwin Sulistyo', 'jabatan' => 'Staf Seksi Asuransi'],
            ['name' => 'Nila Suprobo', 'jabatan' => 'Staf Seksi Asuransi'],
            ['name' => 'Winda Riyana', 'jabatan' => 'Staf Seksi Asuransi'],
            ['name' => 'Rahmalina Mentari Putri', 'jabatan' => 'Staf Seksi Asuransi'],
        ];

        // Cari unit ASURANSI
        $asuransiUnit = UnitKerja::where('nama', 'ASURANSI')->first();

        if (!$asuransiUnit) {
            $this->command->error('Unit ASURANSI tidak ditemukan!');
            return;
        }

        foreach ($asuransiMembers as $member) {
            $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $asuransiUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user ASURANSI berhasil dijalankan.');

        // Data untuk unit ASET & LOGISTIK
        $asetLogistikMembers = [
            ['name' => 'Bani Akbar Dhira Y', 'role' => 'Kepala Seksi', 'jabatan' => 'Ka. Seksi Aset dan Logistik'],
            ['name' => 'Nur Ardi Firdosta', 'jabatan' => 'Staf Seksi Aset dan Logistik'],
            // ['name' => 'Fathul Bari'],
            // ['name' => 'Oryzae Sativa Linies'],
        ];

        // Cari unit ASET & LOGISTIK
        $asetLogistikUnit = UnitKerja::where('nama', 'ASET & LOGISTIK')->first();

        if (!$asetLogistikUnit) {
            $this->command->error('Unit ASET & LOGISTIK tidak ditemukan!');
            return;
        }

        foreach ($asetLogistikMembers as $member) {
            $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $asetLogistikUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user ASET & LOGISTIK berhasil dijalankan.');

        // Data untuk unit GUDANG
        $Gudang = [
            ['name' => 'Ratih Titis Pamungkas', 'role' => 'Kepala Unit', 'jabatan' => 'Ka. Unit Gudang'],
            ['name' => 'Fathul Bari', 'jabatan' => 'Staf Unit Gudang'],
            ['name' => 'Oryzae Sativa Linies', 'jabatan' => 'Staf Unit Gudang'],
        ];

        // Cari unit GUDANG
        $asetLogistikUnit = UnitKerja::where('nama', 'GUDANG')->first();

        if (!$asetLogistikUnit) {
            $this->command->error('Unit GUDANG tidak ditemukan!');
            return;
        }

        foreach ($Gudang as $member) {
            $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $asetLogistikUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user GUDANG berhasil dijalankan.');

        // Data untuk unit PELAYANAN MEDIK
        $pelayananMedikMembers = [
            ['name' => 'Dr. Aditya Chandra Putra.,Sp PD', 'role' => 'Manager', 'jabatan' => 'Wadir Pelayanan'],
            ['name' => 'Dr. Dedy Pujo Purnomo.,Sp An', 'role' => 'Manager', 'jabatan' => 'Manajer Pelayanan Medik'],
            ['name' => 'Eko Setiono', 'jabatan' => 'Staf Manajer Pelayanan Medik'],
            ['name' => 'Lia Eris Fitriani', 'jabatan' => 'Staf Manajer Pelayanan Medik'],
            ['name' => 'Dr. Indri Setiani ', 'role' => 'Kepala Seksi', 'jabatan' => 'Ka. Seksi Pel Medik Rajal, Gadar Ranap'],
            ['name' => 'Dr. Indri Setiani ', 'role' => 'Kepala Seksi', 'jabatan' => 'Ka. Seksi Pel. Medik Bedah, Intensif, HD, MP, Rehab Medik'],
        ];

        // Cari unit PELAYANAN MEDIK
        $pelayananMedikUnit = UnitKerja::where('nama', 'PELAYANAN MEDIK')->first();

        if (!$pelayananMedikUnit) {
            $this->command->error('Unit PELAYANAN MEDIK tidak ditemukan!');
            return;
        }

        foreach ($pelayananMedikMembers as $member) {
            $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $pelayananMedikUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user PELAYANAN MEDIK berhasil dijalankan.');

        // Data untuk unit PENUNJANG
        $penunjangMembers = [
            ['name' => 'Purbo Santosa', 'role' => 'Manager', 'jabatan' => 'Manajer Penunjang'],
            ['name' => 'Umu Trisniati', 'role' => 'Kepala Seksi', 'jabatan' => 'Ka. Seksi Penunjang Medik+ Plt. Instalasi Gas Medik dan Alkes'],
            ['name' => 'Ahmad Nur Banjari', 'role' => 'Kepala Seksi', 'jabatan' => 'Ka. Seksi Penunjang Non Medik'],
            ['name' => 'Mutia Kanza Salama', 'jabatan' => 'Staf Manajer Penunjang'],
            ['name' => 'Puspita Chandra Alviana', 'jabatan' => 'Staf Manajer Penunjang'],
        ];

        // Cari unit PENUNJANG
        $penunjangUnit = UnitKerja::where('nama', 'PENUNJANG')->first();

        if (!$penunjangUnit) {
            $this->command->error('Unit PENUNJANG tidak ditemukan!');
            return;
        }

        foreach ($penunjangMembers as $member) {
            $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $penunjangUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user PENUNJANG berhasil dijalankan.');

        // Data untuk unit KEPERAWATAN
        $keperawatanMembers = [
            ['name' => 'Siti Zaenab', 'role' => 'Manager', 'jabatan' => 'Manajer Keperawatan'],
            ['name' => 'Nurul Ulfah Kh', 'role' => 'Kepala Seksi', 'jabatan' => 'Ka. Seksi Keperawatan Rajal, Ranap, Gadar'],
            ['name' => 'Rudiati', 'role' => 'Kepala Seksi', 'jabatan' => 'Ka. Seksi Keperawatan Bedah, Intensif, HD, MP, Rehabilitasi Medik'],
            ['name' => 'Rifki Nafisani', 'jabatan' => 'Staf Manajer Keperawatan'],
            ['name' => 'Muhadi', 'jabatan' => 'Staf Manajer Keperawatan'],
        ];

        // Cari unit KEPERAWATAN
        $keperawatanUnit = UnitKerja::where('nama', 'KEPERAWATAN')->first();

        if (!$keperawatanUnit) {
            $this->command->error('Unit KEPERAWATAN tidak ditemukan!');
            return;
        }

        foreach ($keperawatanMembers as $member) {
            $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $keperawatanUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user KEPERAWATAN berhasil dijalankan.');


        // Data untuk unit PENGAMANAN
        $pengamananMembers = [
            ['name' => 'Eko Pranoto', 'role' => 'Kepala Unit', 'jabatan' => 'Ka. Unit Pengamanan dan Peduli Lingkungan'],
            ['name' => 'Sahro Susilo', 'jabatan' => 'Staf Unit Pengamanan dan Peduli Lingkungan'],
            ['name' => 'Senja Kristiawan', 'jabatan' => 'Staf Unit Pengamanan dan Peduli Lingkungan'],
            ['name' => 'Margianto', 'jabatan' => 'Staf Unit Pengamanan dan Peduli Lingkungan'],
            ['name' => 'Khadmono', 'jabatan' => 'Staf Unit Pengamanan dan Peduli Lingkungan'],
            ['name' => 'Ivan Yunanto', 'jabatan' => 'Staf Unit Pengamanan dan Peduli Lingkungan'],
            ['name' => 'Redyan Chamdan Nofebrianto', 'jabatan' => 'Staf Unit Pengamanan dan Peduli Lingkungan'],
            ['name' => 'Misrun', 'jabatan' => 'Staf Unit Pengamanan dan Peduli Lingkungan'],
            ['name' => 'Hidayat Setiawan', 'jabatan' => 'Staf Unit Pengamanan dan Peduli Lingkungan'],
            ['name' => 'Kharwani', 'jabatan' => 'Staf Unit Pengamanan dan Peduli Lingkungan'],
            ['name' => 'Alfiyan Hidayanto', 'jabatan' => 'Staf Unit Pengamanan dan Peduli Lingkungan'],
            ['name' => 'Yudi Saputra', 'jabatan' => 'Staf Unit Pengamanan dan Peduli Lingkungan'],
            ['name' => 'Sodik Kholidun', 'jabatan' => 'Staf Unit Pengamanan dan Peduli Lingkungan'],
        ];

        // Cari unit PENGAMANAN
        $pengamananUnit = UnitKerja::where('nama', 'PENGAMANAN')->first();

        if (!$pengamananUnit) {
            $this->command->error('Unit PENGAMANAN tidak ditemukan!');
            return;
        }

        foreach ($pengamananMembers as $member) {
            $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $pengamananUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user PENGAMANAN berhasil dijalankan.');

        // Data untuk unit UNIT PEMASARAN
        $pemasaranMembers = [
            ['name' => 'Adi Setiadi', 'role' => 'Kepala Unit', 'jabatan' => 'Plt. Ka. Unit Pemasaran'],
            ['name' => 'Purwanto', 'jabatan' => 'Staf Unit Pemasaran'],
            ['name' => 'Rahma Desta K.,S.KM', 'jabatan' => 'Staf Unit Pemasaran'],
            ['name' => 'Desi Yulianti.,S.Sos', 'jabatan' => 'Staf Unit Pemasaran'],
        ];

        // Cari unit UNIT PEMASARAN
        $pemasaranUnit = UnitKerja::where('nama', 'PEMASARAN')->first();

        if (!$pemasaranUnit) {
            $this->command->error('Unit UNIT PEMASARAN tidak ditemukan!');
            return;
        }

        foreach ($pemasaranMembers as $member) {
            $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $pemasaranUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user UNIT PEMASARAN berhasil dijalankan.');

        // Data untuk unit IT
        $itMembers = [
            ['name' => 'Basuki Imam Sampurna', 'role' => 'Kepala Instalasi', 'jabatan' => 'Ka. Instalasi Teknologi Informasi'],
            ['name' => 'Harry Satrio Aji', 'jabatan' => 'Staf Instalasi Teknologi Informasi'],
            ['name' => 'Athaya Muhammad Shidqi Rahmat', 'jabatan' => 'Staf Instalasi Teknologi Informasi'],
            ['name' => 'Mistomo', 'jabatan' => 'Staf Instalasi Teknologi Informasi'],
        ];

        // Cari unit IT
        $itUnit = UnitKerja::where('nama', 'ITI')->first();

        if (!$itUnit) {
            $this->command->error('Unit ITI tidak ditemukan!');
            return;
        }

        foreach ($itMembers as $member) {
            $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $itUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user IT berhasil dijalankan.');

        // Data untuk unit KOMITE
        $komiteMembers = [
            ['name' => 'Fajarianto', 'role' => 'Kepala Unit', 'jabatan' => 'Sekretaris Komite Mutu'],
        ];

        // Cari unit KOMITE
        $komiteUnit = UnitKerja::where('nama', 'KOMITE MUTU')->first();

        if (!$komiteUnit) {
            $this->command->error('Unit KOMITE MUTU tidak ditemukan!');
            return;
        }

        foreach ($komiteMembers as $member) {
            $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $komiteUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user KOMITE MUTU berhasil dijalankan.');

        // Data untuk unit KOMITE
        $komiteperawatMembers = [
            ['name' => 'Joni Krismanto', 'role' => 'Kepala Unit', 'jabatan' => 'Ketua Komite Keperawatan'],
        ];

        // Cari unit KOMITE
        $komiteUnitPerawat = UnitKerja::where('nama', 'KOMITE KEPERAWATAN')->first();

        if (!$komiteUnitPerawat) {
            $this->command->error('Unit KOMITE Keperawatan tidak ditemukan!');
            return;
        }

        foreach ($komiteperawatMembers as $member) {
            $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $komiteUnitPerawat->id,
                    'jabatan_id' => $KategoriJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user KOMITE Keperawatan berhasil dijalankan.');

        // Data untuk unit SPI
        $SPI = [
            ['name' => 'Gonggo Iswahyudi', 'role' => 'Kepala Unit', 'jabatan' => 'Ketua SPI'],
            ['name' => 'Lutfian Prisnandika', 'jabatan' => 'Anggota SPI'],
            // ['name' => 'Rudi Abri W'],
            // ['name' => 'M Azhar Nur Cholid'],
        ];

        // Cari unit SPI
        $SPIunit = UnitKerja::where('nama', 'SPI')->first();

        if (!$SPIunit) {
            $this->command->error('Unit SPI tidak ditemukan!');
            return;
        }

        foreach ($SPI as $member) {
            $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $SPIunit->id,
                    'jabatan_id' => $KategoriJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user SPI berhasil dijalankan.');

        // Data untuk unit SUPERVISOR
        $SUPERVISOR = [
            ['name' => 'Rudi Abri W', 'role' => 'Kepala Unit', 'jabatan' => 'Supervisor'],
            ['name' => 'M Azhar Nur Cholid', 'jabatan' => 'Supervisor'],
        ];

        // Cari unit SUPERVISOR
        $SUPERVISORunit = UnitKerja::where('nama', 'SUPERVISOR')->first();

        if (!$SUPERVISORunit) {
            $this->command->error('Unit SUPERVISOR tidak ditemukan!');
            return;
        }

        foreach ($SUPERVISOR as $member) {
            $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $SUPERVISORunit->id,
                    'jabatan_id' => $KategoriJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user SUPERVISOR berhasil dijalankan.');

        // Data untuk unit dewas
        $dewas = [
            ['name' => 'Usiana Tri R', 'role' => 'Kepala Unit', 'jabatan' => 'Dewan Pengawas'],
            ['name' => 'Dr. Nono Sukarman', 'jabatan' => 'Dewan Pengawas'],
        ];

        // Cari unit dewas
        $dewasunit = UnitKerja::where('nama', 'DEWAN PENGAWAS')->first();

        if (!$dewasunit) {
            $this->command->error('Unit DEWAS tidak ditemukan!');
            return;
        }

        foreach ($dewas as $member) {
            $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $dewasunit->id,
                    'jabatan_id' => $KategoriJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user DEWAS berhasil dijalankan.');
    }
}
