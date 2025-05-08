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

    private function usernameFormat($name)
    {
        // Hanya biarkan huruf dan spasi
        $name = strtolower(preg_replace('/[^a-zA-Z ]/', '', $name));

        // Ubah ke lowercase dan hapus spasi
        return strtolower(str_replace(' ', '', $name));
    }

    public function run(): void
    {
        // Buat Roles jika belum ada
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

        foreach ($roles as $roleName) {
            Role::findOrCreate($roleName);
        }

        // Super Admin
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'username' => 'superadmin',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('123'), // Password default
            'unit_id' => null, // Tidak terkait dengan unit
        ]);

        $superAdmin->assignRole('Super Admin');

        // Data untuk Direktur
        $direktur = [
            ['name' => 'Dr. H, Arif Fadlullah Chonar', 'unit_name' => 'DIREKTUR', 'nip' => '01030167', 'jabatan' => 'Direktur'],
        ];

        foreach ($direktur as $member) {
            $unit = UnitKerja::where('nama', $member['unit_name'])->first();
            $jabatan = strtolower(trim($member['jabatan']));

            $KategoriJabatan = KategoriJabatan::whereRaw('LOWER(nama) = ?', [$jabatan])->value('id');
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'nip' => $member['nip'],
                    'username' => $this->usernameFormat($member['name']),
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $unit->id,
                    'jabatan_id' => $KategoriJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user DIREKTUR berhasil dijalankan.');

        // Data Kepala Instalasi
        $kepalaInstalasi = [
            ['unit_name' => 'IRJ', 'name' => 'drg Amalia Rahmaniar', 'nip' => '01200559', 'jabatan' => 'Ka. Instalasi (Dokter)', 'fungsi' => 'Dokter Gigi'], //102
            ['unit_name' => 'IAPI', 'name' => 'Dr. Anantya Hari W.,Sp.An', 'jabatan' => 'Ka. Instalasi (Dokter)', 'fungsi' => 'Dokter Spesialis'],
            ['unit_name' => 'IMP', 'name' => 'Tatun Parjiati', 'nip' => '02990132', 'jabatan' => 'Bidan'],
            ['unit_name' => 'INST RANAP', 'name' => 'Agus Widayat', 'nip' => '02050192', 'jabatan' => 'Perawat Diploma'], //part time
        ];

        foreach ($kepalaInstalasi as $data) {
            $unit = UnitKerja::where('nama', $data['unit_name'])->first();
            $jabatan = strtolower(trim($data['jabatan']));

            $fungsi = isset($data['fungsi']) ? strtolower(trim($data['fungsi'])) : null;

            $KategoriJabatan = KategoriJabatan::whereRaw('LOWER(nama) = ?', [$jabatan])->value('id');
            $FungsiJabatan = $fungsi ? KategoriJabatan::whereRaw('LOWER(nama) = ?', [$fungsi])->value('id') : null;

            if ($unit) {
                $user = User::firstOrCreate(
                    ['email' => $this->emailFormat($data['name'])],
                    [
                        'nip' => $data['nip'] ?? null,
                        'username' => $this->usernameFormat($data['name']),
                        'name' => $data['name'],
                        'password' => Hash::make('123'),
                        'unit_id' => $unit->id,
                        'jabatan_id' => $KategoriJabatan,
                        'fungsi_id' => $FungsiJabatan,
                    ]
                );

                $user->assignRole('Kepala Instalasi');
            }
        }


        // // Data Kepala Unit
        // $kepalaUnit = [
        //     ['unit_name' => 'IPCN', 'name' => 'Wingit Bayu H', 'jabatan' => 'IPCN'],
        // ];

        // foreach ($kepalaUnit as $data) {
        //     $unit = UnitKerja::where('nama', $data['unit_name'])->first();
        //     $KategoriJabatan = KategoriJabatan::where('nama', $data['jabatan'])->value('id');
        //     if ($unit) {
        //         $user = User::firstOrCreate(
        //             ['email' => $this->emailFormat($data['name'])],
        //             [
        //                 'name' => $data['name'],
        //                 'password' => Hash::make('123'),
        //                 'unit_id' => $unit->id,
        //                 'jabatan_id' => $KategoriJabatan,
        //             ]
        //         );

        //         $user->assignRole('Kepala Unit');
        //     }
        // }

        // Data untuk unit Dokter Umum Full Time
        $dokterUmumFullTime = [
            ['name' => 'Dr. H. Agung Widiharto', 'nip' => '01080241', 'jabatan' => 'Dokter Umum'],
            ['name' => 'Dr. Alfiyah Rakhmatul Azizah', 'nip' => '01240677', 'jabatan' => 'Dokter Umum'],
            ['name' => 'Dr. Fitratul Aliyah', 'nip' => '01240678', 'jabatan' => 'Dokter Umum'],
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
                    'nip' => $member['nip'] ?? null,
                    'username' => $this->usernameFormat($member['name']),
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
            ['name' => 'Sulis Setianto', 'nip' => '02090266', 'role' => 'Kepala Unit', 'jabatan' => 'Ners'],
            ['name' => 'Daryanto', 'nip' => '02129314', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Mat Suwignyo', 'nip' => '02100290', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Anifah', 'nip' => '02220594', 'jabatan' => 'Ners'],
            ['name' => 'Mohammad Amrulloh', 'nip' => '02150409', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Singgih Triyantoro', 'nip' => '02140391', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Riyan Nuryana', 'nip' => '02180478', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Umi Sakdiyah', 'nip' => '02160433', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Dwi Agung Nugroho', 'jabatan' => 'Ners'],
            ['name' => 'Atik Wakiah', 'jabatan' => 'Ners'],
            ['name' => 'Supriyadi.,A.Md Kep', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Yutiwi .,A.Md Kep', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Reni Ekawati', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Titin Astuti', 'jabatan' => 'Ners'],
            ['name' => 'Adam Rachman Sukmana.,A.Md Kep', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Ilham Tri Nugroho', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Muhammad Iqbal Ramdadhan', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Sofyanul Affan Hidayat', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Nanang Cahyono', 'jabatan' => 'Pekarya Kesehatan'],
            ['name' => 'Arif Yulianto', 'jabatan' => 'Pekarya Kesehatan'],
            ['name' => 'Restika Dyah Utami', 'role' => 'Administrator', 'jabatan' => 'Staf Administrasi IBS'],
            ['name' => 'Joni Krismanto', 'jabatan' => 'Ners'],
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
                    'nip' => $member['nip'] ?? null,
                    'username' => $this->usernameFormat($member['name']),
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
            ['name' => 'Suyatno', 'role' => 'Kepala Ruang', 'nip' => '02090001', 'jabatan' => 'Ners'],
            ['name' => 'Gilang Yoga Sulistyo Utomo', 'nip' => '02090002', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Majid Prasetya', 'nip' => '02090003', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Yuli Ratnasari, S.Kep.Ns', 'nip' => '02090004', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Dika Ari Utomo', 'nip' => '02090005', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Aryadi Harko', 'nip' => '02090006', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Galuh Rimba N', 'nip' => '02090007', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Aris Cahyono', 'nip' => '02090008', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Novian Hardiyono.,A.Md Kep', 'nip' => '02090009', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Sabar Tiono.,A.Md Kep', 'nip' => '02090010', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Soleh Ahmad R', 'nip' => '02090011', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Mahful', 'nip' => '02090012', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Yustika Dwi A', 'nip' => '02090013', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Brian Sanada', 'nip' => '02090014', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Dinar Kurniadi', 'nip' => '02090015', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Adib Rofiudin Izza', 'nip' => '02090016', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Indarto', 'nip' => '02090017', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Albetias Pangestuti', 'nip' => '02090018', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Swasti Jamalina', 'nip' => '02090019', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Ajeng Bara Saputri.,A.Md Kep', 'nip' => '02090020', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Syaeful Fadlan A', 'nip' => '02090021', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Hanung Maulana.,A.Md Kep', 'nip' => '02090022', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Tony Adam', 'nip' => '02090023', 'jabatan' => 'Pekarya Kesehatan'],
            ['name' => 'Aris Aji Pangestu', 'nip' => '02090024', 'jabatan' => 'Pekarya Kesehatan'],
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
                    'nip' => $member['nip'] ?? null,
                    'username' => $this->usernameFormat($member['name']),
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
            ['name' => 'Tri Nurhidayah', 'role' => 'Kepala Unit', 'jabatan' => 'Ners'],
            ['name' => 'Budiarto', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Septi Hartanti', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Widyandika Yudha', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Eka Dewi Wahyuni', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Reni Yuniarti', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Ahmad Sulatif', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Eka Sri Rahayu', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Nia Puspita Utami', 'jabatan' => 'Ners'],
            ['name' => 'Aqmarinda Laila', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Dwi Pangestuti', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Sukoyo', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Jefri Oktavian', 'jabatan' => 'Ners'],
            ['name' => 'Nur Fitriyadi', 'jabatan' => 'Pekarya Kesehatan'],
            ['name' => 'Wahyu Nur Hidayat', 'jabatan' => 'Pekarya Kesehatan'],
            ['name' => 'Titi Yuli Anggraeni', 'jabatan' => 'Ners'],
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

            ['name' => 'Puji Yuliati', 'role' => 'Kepala Unit', 'jabatan' => 'Ners'],
            ['name' => 'Ari Dwi Astuti', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Sahid Menru Hidayatulloh', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Helman Riyadi', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Darmuji', 'jabatan' => 'Perawat Diploma'],
            ['name' => "Nur Wakhidah Lulu'ul Jannah", 'role' => 'Administrator', 'jabatan' => 'Staf Administrasi Inst Laboratorium'],
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
            // ['name' => 'drg Amalia Rahmanniar', 'role' => 'Kepala Instalasi', 'jabatan' => 'Ka. Instalasi (Dokter)', 'fungsi' => 'Dokter Gigi'],
            // ['name' => 'Desi Norma W', 'role' => 'Kepala Ruang', 'jabatan' => 'Karu Instalasi Rawat Jalan'],
            ['name' => 'Desi Norma W', 'role' => 'Kepala Unit', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Ais Oktavina', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Yunie Sushanti', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Erviani Ratna P', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Dwi Sulistyo P', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Anugrah Noviani', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Roni Wandoyo', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Arni', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Bety Tinaria', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Budi Hastuti', 'jabatan' => 'Ners'],
            ['name' => 'Ratnaningrum', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Diyah Tika Ariani', 'jabatan' => 'Bidan'],
            ['name' => 'Ayu Putri Purnaningsih', 'jabatan' => 'Bidan'],
            ['name' => 'Raihanah Al Mahdiyyah', 'jabatan' => 'Perawat Diploma'], //Kontrak
            ['name' => 'Suparman', 'jabatan' => 'Staf Administrasi IRJ'],
            ['name' => 'Charomatul Amanah', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Miftahul Falah', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Sri Wahyuni', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Nur Alviah', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Harinto', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Abdul Halim', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Feni Kustiani', 'jabatan' => 'Bidan'],
            ['name' => 'Sutiyah', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Indra Gunawan', 'jabatan' => 'Staf Administrasi IRJ'],
        ];

        // Cari unit IRJ
        $irjUnit = UnitKerja::where('nama', 'IRJ')->first();

        if (!$irjUnit) {
            $this->command->error('Unit IRJ tidak ditemukan!');
            return;
        }

        foreach ($irjMembers as $member) {
            $fungsi = isset($member['fungsi']) ? strtolower(trim($member['fungsi'])) : null;
            $jabatan = strtolower(trim($member['jabatan']));

            $KategoriJabatan = KategoriJabatan::whereRaw('LOWER(nama) = ?', [$jabatan])->value('id');
            $FungsiJabatan = $fungsi ? KategoriJabatan::whereRaw('LOWER(nama) = ?', [$fungsi])->value('id') : null;
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $irjUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                    'fungsi_id' => $FungsiJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user IRJ berhasil dijalankan.');

        // Data untuk unit Perinatologi
        $perinatologiMembers = [
            ['name' => 'Ariyanti Retno A', 'role' => 'Kepala Ruang', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Laila Oktavia', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Resha Oktaviani', 'jabatan' => 'Ners'],
            ['name' => 'Murni Nurdiyanti', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Meli Roshidatul Fajriyah', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Lina Ernawati', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Siti Nurhidayah', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Yusefi Verawati, Amd.Kep', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Yurika Fian K', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Ani Kurniati', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Tarwiyah', 'jabatan' => 'Pekarya Kesehatan'], //Pekarya Doble
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
            ['name' => 'Widanti Kusuma', 'role' => 'Kepala Ruang', 'jabatan' => 'Bidan'],
            ['name' => 'Dian Septiana', 'jabatan' => 'Bidan'],
            ['name' => 'Angelia Resthy Ratnawati', 'jabatan' => 'Bidan'],
            ['name' => 'Nur Anisa Apriliani', 'jabatan' => 'Bidan'],
            ['name' => 'Hana Putri', 'jabatan' => 'Bidan'],
            ['name' => 'Vega Rizkawati', 'jabatan' => 'Bidan'],
            ['name' => 'Elga Wulandari', 'jabatan' => 'Bidan'],
            ['name' => 'Andini Kurniasih', 'jabatan' => 'Bidan'],
            ['name' => 'Uswatun Khasanah', 'jabatan' => 'Bidan'],
            ['name' => 'Dwi Apriliyani', 'jabatan' => 'Bidan'],
            ['name' => 'Reni Windi Astuti', 'jabatan' => 'Bidan'],
            ['name' => 'Melia Dwi Setiawati', 'jabatan' => 'Bidan'],
            ['name' => 'Endah Suryani', 'jabatan' => 'Bidan'],
            ['name' => 'Umu Khoiriyah', 'jabatan' => 'Bidan'],
            ['name' => 'Lestari Anggit Setyowati', 'jabatan' => 'Bidan'],
            ['name' => 'Rokhaniyah', 'jabatan' => 'Bidan'],
            ['name' => 'Cici Oviani Agustina', 'jabatan' => 'Bidan'],
            ['name' => 'Eti Yuliana', 'jabatan' => 'Bidan'],
            ['name' => 'Umdatul Ilmi', 'jabatan' => 'Pekarya Kesehatan'], //pekarya double
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
            ['name' => 'Wahyu Puspitasari', 'role' => 'Kepala Ruang', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Rinta Ermawati', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Liana Andriani', 'jabatan' => 'Bidan'],
            ['name' => 'Tri Herlina', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Preti Desiana', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Reni Desi Safitri', 'jabatan' => 'Bidan'],
            ['name' => 'Musrifah Afriyanti', 'jabatan' => 'Bidan'], //kontrak
            ['name' => 'Meilia Suharya N', 'jabatan' => 'Bidan'],
            ['name' => 'Lili Alimah', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Fitroh Aulia Dina', 'jabatan' => 'Bidan'],
            ['name' => 'Khusnul Khotimah', 'jabatan' => 'Bidan'],
            ['name' => 'Siti Solehah / Gunung Giana', 'jabatan' => 'Pekarya Kesehatan'],
            ['name' => 'Lie Ivani', 'jabatan' => 'Pekarya Kesehatan'],
            ['name' => 'Wahidatun Toyibah', 'jabatan' => 'Pekarya Kesehatan'], //Pekarya Double
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
            ['name' => 'Susilo Rudatin', 'role' => 'Kepala Ruang', 'nip' => '02092001', 'jabatan' => 'Ners'],
            ['name' => 'Imam Waizun', 'nip' => '02092002', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Wahyu Nur Fitriyani', 'nip' => '02092003', 'jabatan' => 'Ners'],
            ['name' => 'Andika Susetyo Kaisar Putra', 'nip' => '02092004', 'jabatan' => 'Ners'],
            ['name' => 'Erdika Retno Wulandari.,A.Md Kep', 'nip' => '02092005', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Badi Nur Waluyo', 'nip' => '02092006', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Yuyun Purwanti', 'nip' => '02092007', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Slamet Uji Kurniawan', 'nip' => '02092008', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Putri Puja Pangesti.,A.Md Kep', 'nip' => '02092009', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Jodi Fajar Wiguna.,A.Md Kep', 'nip' => '02092010', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Ahmad Sukro Hidayat', 'nip' => '02092011', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Tri Mutmainah.,A.Md Kep', 'nip' => '02092012', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Yuda Valentino', 'nip' => '02092013', 'jabatan' => 'Perawat Diploma'], //kontrk
            ['name' => 'Rozaul Muta\'ali', 'nip' => '02092014', 'jabatan' => 'Perawat Diploma'],  //kontrak
            // ['name' => 'Prana Sakti Ibnu Oetomo', 'nip' => '02092015', 'jabatan' => 'Pekarya Kesehatan'], //kontrak
            ['name' => 'Slamet Nikmat', 'nip' => '02092016', 'jabatan' => 'Pekarya Kesehatan'],
            ['name' => 'Dika Adi Saputra', 'jabatan' => 'Pekarya Kesehatan'],
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
                    'nip' => $member['nip'] ?? null,
                    'username' => $this->usernameFormat($member['name']),
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
            ['name' => 'Indriyana Keliek F', 'role' => 'Kepala Ruang', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Purwadi', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Rini Wijayanti', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Diding Panca P', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Diyah Enggar Tri', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Riana Dwi Agustina', 'jabatan' => 'Ners'], //kontrak
            ['name' => 'Yunut Jenianto', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Ikhsan Saifudin', 'jabatan' => 'Perawat Diploma'], //kontrak
            ['name' => 'Dwi Yulianti', 'jabatan' => 'Perawat Diploma'], //kontrak
            ['name' => 'Khomsa Fadillah R', 'jabatan' => 'Perawat Diploma'], //kontrak
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
            ['name' => 'Siti Markhamah', 'role' => 'Kepala Ruang', 'jabatan' => 'Ners'],
            ['name' => 'Deni Amrulloh', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Buyung Pambudi', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Tri Ningsih NawangSasi', 'jabatan' => 'Ners'],
            ['name' => 'Ina Karunia', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Gayuh Dwi Laksono', 'jabatan' => 'Ners'],
            ['name' => 'Rian Diah Utami', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Nigeffe Pasalaila', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Haris Naufal Faizi', 'jabatan' => 'Perawat Diploma'], //kontrak
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
            ['name' => 'Ika Sari Sholehati', 'role' => 'Kepala Ruang', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Syafira Diaz Maisyaroh', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Sinta Puspita', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Rini Utami', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Rizki Dwi A', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Nony Marlina', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Septi Indriwati', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Rica Karomah, Amd.Kep.', 'jabatan' => 'Perawat Diploma'],
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
            ['name' => 'Umu Hani', 'role' => 'Kepala Ruang', 'jabatan' => 'Ners'],
            ['name' => 'Neneng Susmas Netty', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Purnama Setya Cahyadi', 'jabatan' => 'Ners'],
            ['name' => 'Walyanti', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Tri Susanto', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Siti Azizah', 'jabatan' => 'Perawat Diploma'], //kontrak
            ['name' => 'Windu Kusuma W', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Etha Setyana', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Dwi Sukur W', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Ratna Sulistiyani', 'jabatan' => 'Perawat Diploma'], //kontrak
            ['name' => 'Akhmad Sulukhi', 'jabatan' => 'Ners'],
            ['name' => 'Abto Deswar Diansyah', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Adistia Yunita Nurfaega', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Feli Tri Yuliana', 'jabatan' => 'Perawat Diploma'], //kontrak
            ['name' => 'Ridwan Saputra', 'jabatan' => 'Perawat Diploma'], //kontrak
            ['name' => 'Gugun Galuh Rahmanmaulana', 'jabatan' => 'Perawat Diploma'], //kontrak
            ['name' => 'I Wayan Arianto', 'jabatan' => 'Pekarya Kesehatan'],
            ['name' => 'Tri Handoko', 'jabatan' => 'Pekarya Kesehatan'],
            ['name' => 'Choerul Elma Subarkah', 'jabatan' => 'Pekarya Kesehatan'], //pakarya double
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
            ['name' => 'Ifa Fitria', 'role' => 'Kepala Ruang', 'jabatan' => 'Ners'],
            ['name' => 'Fuandraeni Faslihatun', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Cahya Indra Lukmana', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Ferawati Trianasari', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Febtriyanto', 'jabatan' => 'Ners'],
            ['name' => 'Sasmita Ilmi F', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Drajat Wahyu I', 'jabatan' => 'Perawat Diploma'], //PHK
            ['name' => 'Deni Ambang', 'jabatan' => 'Perawat Diploma'], //PHK
            ['name' => 'Sisanto', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Aprilia Atita Bella Adila, Amd.Kep.', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Vendrha Zani Zegal', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Didik Prapto Sasongko', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Tiara Arindha Wibowo.', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Wulan Tri Mulyani.', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Vicky Indra Wibowo', 'jabatan' => 'Perawat Diploma'],
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
            ['name' => 'Slamet Supratomo', 'role' => 'Kepala Ruang', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Agus Suprihanto', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Yanuar Puthut Wijonarko', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Suryo Aji', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Ediy Santosa', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Wahyu Sri Sadono', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Feri Susanto', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Dian Ratnasari', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Maita Indah P', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Nuripan', 'jabatan' => 'Pekarya Kesehatan'],
            ['name' => 'Fajar Tri Pambudi', 'jabatan' => 'Pekarya Kesehatan'],
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
            ['name' => 'Latifah', 'role' => 'Kepala Ruang', 'jabatan' => 'Ners'],
            ['name' => 'Wiwi Kusniati', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Muhammad Sukur', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Emi Dwi Listia', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Eka Nur Fitri Apriliyani', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Catur Noviantiko', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Zuhri Nikmatuloh Zulfikar', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Faizal Tamim Al- Mundziri, Amd.Kep.', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Eling Tiyasari.,A.Md Kep', 'jabatan' => 'Perawat Diploma'],
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
            // ['name' => 'Dr. Tegar Harputra Raya.,Sp KFR', 'role' => 'Kepala Instalasi', 'jabatan' => 'Dokter Spesialis Rehabilitasi Medik + Ka. Instalasi Rehabilitasi Medik'], //part time
            ['name' => 'Slamet Budi Santoso', 'role' => 'Kepala Ruang', 'jabatan' => 'Fisioterapis'],
            ['name' => "Lu'lu u Al Hikmah", 'jabatan' => 'Fisioterapis'],
            ['name' => 'Dhanti Wahyundari', 'jabatan' => 'Fisioterapis'],
            // ['name' => 'Ucik Auliya', 'jabatan' => 'Okupasi Terapi'],
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
            ['name' => 'Ari Fitria', 'role' => 'Koordinator', 'jabatan' => 'Ners'],
            ['name' => 'Didik Setiawan', 'jabatan' => 'Ners'],
            ['name' => 'Tzalis Ubaidillah', 'jabatan' => 'Ners'],
            ['name' => 'Ari Yogo P', 'jabatan' => 'Ners'],
            ['name' => 'Ismi Ngaisatun', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Andang Pramana', 'jabatan' => 'Perawat Diploma'],
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
            ['name' => 'Wigati', 'role' => 'Kepala Instalasi', 'nip' => '02091001', 'jabatan' => 'Perekam Medik'],
            ['name' => 'Deka Prasetiyanti', 'nip' => '02091002', 'jabatan' => 'Perekam Medik'],
            ['name' => 'Lina Sandyasari', 'nip' => '02091003', 'jabatan' => 'Perekam Medik'],
            ['name' => 'Lina Afiyanti', 'nip' => '02091004', 'jabatan' => 'Perekam Medik'],
            ['name' => 'Meiga Kencana Putri.,A.Md', 'nip' => '02091005', 'jabatan' => 'Perekam Medik'],
            // ['name' => 'Eric Setiawan', 'nip' => '02091007', 'jabatan' => 'Informasi + Filling'],
            ['name' => 'Kavi Nurul Firdaus', 'nip' => '02091008', 'jabatan' => 'Staf Instalasi Rekam Medik'],
            ['name' => 'Gayuh Prasetyo', 'nip' => '02091009', 'jabatan' => 'Staf Instalasi Rekam Medik'],
            ['name' => 'Agus Waluyo', 'nip' => '02091010', 'jabatan' => 'Staf Instalasi Rekam Medik'],
            ['name' => 'Suci Rahmawati', 'nip' => '02091011', 'jabatan' => 'Staf Instalasi Rekam Medik'],
            ['name' => 'Rifka Winda Listanti', 'nip' => '02091012', 'jabatan' => 'Staf Instalasi Rekam Medik'],
            ['name' => 'Novi Purbasari', 'nip' => '02091013', 'jabatan' => 'Staf Instalasi Rekam Medik'],
            ['name' => 'Angga Putra Pratama', 'nip' => '02091014', 'jabatan' => 'Staf Instalasi Rekam Medik'],
            ['name' => 'Wagianto', 'nip' => '02091015', 'jabatan' => 'Staf Instalasi Rekam Medik'],
            ['name' => 'Nurul Fatiah', 'nip' => '02091016', 'jabatan' => 'Staf Instalasi Rekam Medik'],
            ['name' => 'Zulfa Nurmutaqin', 'nip' => '02091017', 'jabatan' => 'Staf Instalasi Rekam Medik'],
            ['name' => 'Hamid Badawi Hasan', 'nip' => '02091018', 'jabatan' => 'Staf Instalasi Rekam Medik'],
            ['name' => 'Dzaky Arhiska Daffa', 'nip' => '02091019', 'jabatan' => 'Staf Instalasi Rekam Medik'],
            ['name' => 'Puji Lestari', 'nip' => '02091020', 'jabatan' => 'Staf Instalasi Rekam Medik'],
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
                    'nip' => $member['nip'] ?? null,
                    'username' => $this->usernameFormat($member['name']),
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
            ['name' => 'Uniek Setya Wardani', 'role' => 'Kepala Instalasi', 'jabatan' => 'Apoteker'],
            ['name' => 'Rizqi Ayu Amalina., S.Farm Apt', 'jabatan' => 'Apoteker'],
            ['name' => 'Tri Wahyu Yuni Kosiah', 'jabatan' => 'Apoteker'],
            ['name' => 'Nuzul Ayu Pangestika', 'jabatan' => 'Apoteker'],
            ['name' => 'Desiana Nur Handayani', 'jabatan' => 'Apoteker'],
            ['name' => 'Afriliana Nurahimah', 'jabatan' => 'Apoteker'],
            ['name' => 'Dika Destiani', 'jabatan' => 'Apoteker'],
            ['name' => 'Faizatul Istiqomah', 'jabatan' => 'Apoteker'],
            ['name' => 'Yunika Wulansari', 'jabatan' => 'Tenaga Teknis Kefarmasian (TTK)'],
            ['name' => 'Edy Purwanto', 'jabatan' => 'Tenaga Teknis Kefarmasian (TTK)'],
            ['name' => 'Susi Susanti', 'jabatan' => 'Tenaga Teknis Kefarmasian (TTK)'],
            ['name' => 'Sabaniah Dwi H', 'jabatan' => 'Tenaga Teknis Kefarmasian (TTK)'],
            ['name' => 'Wiwin Nur Supriyanti', 'jabatan' => 'Tenaga Teknis Kefarmasian (TTK)'],
            ['name' => 'Febri Zaeni Ikhsan', 'jabatan' => 'Tenaga Teknis Kefarmasian (TTK)'],
            ['name' => 'Tri Hidayati', 'jabatan' => 'Tenaga Teknis Kefarmasian (TTK)'],
            ['name' => 'Octamiarso Eko R', 'jabatan' => 'Tenaga Teknis Kefarmasian (TTK)'],
            ['name' => 'Saguh Pambudi', 'jabatan' => 'Tenaga Teknis Kefarmasian (TTK)'],
            ['name' => 'Alifah Zovia Mordan', 'jabatan' => 'Tenaga Teknis Kefarmasian (TTK)'],
            ['name' => 'Widaryati', 'jabatan' => 'Tenaga Teknis Kefarmasian (TTK)'],
            ['name' => 'Siti Solekhah / Andang', 'jabatan' => 'Tenaga Teknis Kefarmasian (TTK)'],
            ['name' => 'Irma Okida Anggraeni', 'jabatan' => 'Tenaga Teknis Kefarmasian (TTK)'],
            ['name' => 'Titin Lestari', 'jabatan' => 'Tenaga Teknis Kefarmasian (TTK)'],
            // ['name' => 'Purwaningsih.,A.Md Farm', 'jabatan' => 'Tenaga Teknis Kefarmasian'],
            // ['name' => 'Damar Dwi Sasongko', 'jabatan' => 'Staf Instalasi Farmasi'], //KONTRAK
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
            ['name' => 'Dr. Febi Pramono., Sp. Rad', 'role' => 'Kepala Instalasi', 'jabatan' => 'Ka. Instalasi (Dokter)', 'fungsi' => 'Dokter Spesialis'], //part time
            ['name' => 'Wisnu Kuncahyo', 'role' => 'Kepala Ruang', 'jabatan' => 'Radiografer'],
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
            $fungsi = isset($member['fungsi']) ? strtolower(trim($member['fungsi'])) : null;
            $jabatan = strtolower(trim($member['jabatan']));
            $KategoriJabatan = KategoriJabatan::whereRaw('LOWER(nama) = ?', [$jabatan])->value('id');
            $FungsiJabatan = $fungsi ? KategoriJabatan::whereRaw('LOWER(nama) = ?', [$fungsi])->value('id') : null;
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $radiologiUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                    'fungsi_id' => $FungsiJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user INST RADIOLOGI berhasil dijalankan.');

        // Data untuk unit INST LABORATORIUM
        $laboratoriumMembers = [
            ['name' => 'Dr. Trinovia Andayaningsih.,Sp PK', 'role' => 'Kepala Instalasi', 'jabatan' => 'Ka. Instalasi (Dokter)', 'fungsi' => 'Dokter Spesialis'], //part time
            ['name' => 'Joko Sugiharto', 'role' => 'Kepala Ruang', 'jabatan' => 'Analis Kesehatan'],
            ['name' => 'Eka Prihartiningsih', 'jabatan' => 'Analis Kesehatan'],
            ['name' => 'Wahyu Setiyo W', 'jabatan' => 'Analis Kesehatan'],
            ['name' => 'Vita Dwi Mulatsih', 'jabatan' => 'Analis Kesehatan'],
            ['name' => 'Laelly Yuni Sugesty', 'jabatan' => 'Analis Kesehatan'],
            ['name' => 'Maya Irenne Ratu', 'jabatan' => 'Analis Kesehatan'],
            ['name' => 'Nia Musaadah', 'jabatan' => 'Analis Kesehatan'],
            ['name' => 'Devi Novita Triana', 'jabatan' => 'Analis Kesehatan'],
            ['name' => 'Nur Hayati', 'jabatan' => 'Analis Kesehatan'],
            ['name' => 'Ismanto', 'jabatan' => 'Analis Kesehatan'],
            ['name' => 'Yuniara Fika Tri P', 'jabatan' => 'Analis Kesehatan'],
            ['name' => 'Zaenal Arifin', 'jabatan' => 'Analis Kesehatan'],
            ['name' => 'Argandari', 'jabatan' => 'Analis Kesehatan'],
            ['name' => 'Tyas Asalwa Nabila', 'jabatan' => 'Analis Kesehatan'],
            ['name' => 'Rosna Erviana', 'role' => 'Administrator', 'jabatan' => 'Staf Administrasi Inst Laboratorium'],
            ['name' => 'Diaz Cagar Biru Langit', 'role' => 'Administrator', 'jabatan' => 'Staf Administrasi Inst Laboratorium'],
            ['name' => 'Alfina Reinada Hapsari', 'jabatan' => 'Analis Kesehatan'],
        ];

        // Cari unit INST LABORATORIUM
        $laboratoriumUnit = UnitKerja::where('nama', 'INST LABORATORIUM')->first();

        if (!$laboratoriumUnit) {
            $this->command->error('Unit INST LABORATORIUM tidak ditemukan!');
            return;
        }

        foreach ($laboratoriumMembers as $member) {
            $fungsi = isset($member['fungsi']) ? strtolower(trim($member['fungsi'])) : null;
            $jabatan = strtolower(trim($member['jabatan']));
            $KategoriJabatan = KategoriJabatan::whereRaw('LOWER(nama) = ?', [$jabatan])->value('id');
            $FungsiJabatan = $fungsi ? KategoriJabatan::whereRaw('LOWER(nama) = ?', [$fungsi])->value('id') : null;
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $laboratoriumUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                    'fungsi_id' => $FungsiJabatan,
                ]
            );


            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user INST LABORATORIUM berhasil dijalankan.');

        // Data untuk unit INST SANITASI
        $sanitasiMembers = [
            ['name' => 'A Imam Mutaqin', 'role' => 'Kepala Instalasi', 'jabatan' => 'Kesehatan Lingkungan / Sanitarian'],
            ['name' => 'Ardi Febriyanto', 'jabatan' => 'Pelaksana IPAL'],
            // ['name' => 'Sekar Antik Larasati', 'jabatan' => 'Sanitarian'],
            ['name' => 'Adiyono', 'jabatan' => 'Cleaning Service'],
            ['name' => 'Aji Widianto', 'jabatan' => 'Cleaning Service'],
            ['name' => 'Gesit Purnama Ghyan', 'jabatan' => 'Cleaning Service'],
            ['name' => 'Muhamad Saefuloh', 'jabatan' => 'Cleaning Service'],
            ['name' => 'Agus Sutomo', 'jabatan' => 'Cleaning Service'],
            ['name' => 'Setya Budi', 'jabatan' => 'Cleaning Service'],
            ['name' => 'Kamil Zulfikar', 'jabatan' => 'Cleaning Service'],
            ['name' => 'Bambang Hermanto', 'jabatan' => 'Cleaning Service'],
            ['name' => 'Khayatno Setiawan', 'jabatan' => 'Cleaning Service'],
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
            ['name' => 'Khamidah', 'role' => 'Kepala Instalasi', 'jabatan' => 'Ners'],
            ['name' => 'M. Agung Prastowo', 'jabatan' => 'Staf Instalasi CSSD'],
            ['name' => 'Ipung Prayogi', 'jabatan' => 'Staf Instalasi CSSD'],
            ['name' => 'Fajar Suryo Purnomo', 'jabatan' => 'Staf Instalasi CSSD'],
            ['name' => 'Edi Priyanto', 'jabatan' => 'Staf Instalasi CSSD'],
            ['name' => 'Andi Gunawan', 'nip' => '02091006', 'jabatan' => 'Staf Instalasi CSSD'],
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
            ['name' => 'Widodo Pindah Riyanto', 'role' => 'Kepala Instalasi', 'jabatan' => 'Ka. Instalasi Pemeliharaan  Sarpras'],
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
            ['name' => 'Adityana Juni Saputra', 'jabatan' => 'Elektromedik'],
            ['name' => 'Puji Triono', 'jabatan' => 'Staf Instalasi Gas Medik'],
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
            ['name' => 'Ruslan', 'role' => 'Kepala Unit', 'jabatan' => 'Ners'],
            ['name' => 'Mamat Setiawan', 'jabatan' => 'Perawat Diploma'],
            ['name' => 'Robby Ilmiawan', 'jabatan' => 'Staf Unit MCU dan Poskes'],
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
            ['name' => 'Arif Suhendra', 'role' => 'Kepala Unit', 'jabatan' => 'Ka. Unit Ambulance'],
            ['name' => 'Durul Farid', 'jabatan' => 'Staf Unit Ambulance'],
            ['name' => 'Afria Sofan', 'jabatan' => 'Staf Unit Ambulance'],
            ['name' => 'Suwarno', 'jabatan' => 'Staf Unit Ambulance'],
            ['name' => 'Rakhmat Mubasyier', 'jabatan' => 'Staf Unit Ambulance'],
            ['name' => 'M.Rofik', 'jabatan' => 'Staf Unit Ambulance'],
            ['name' => 'Margo Nursuwono', 'jabatan' => 'Staf Unit Ambulance'],
            ['name' => 'M.Ari Arif H', 'jabatan' => 'Staf Unit Ambulance'],
            ['name' => 'Umar Hasanudin', 'jabatan' => 'Staf Unit Ambulance'],
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
            ['name' => 'Pujiningsih', 'role' => 'Kepala Instalasi', 'jabatan' => 'Ahli Gizi / Dietisien'],
            ['name' => 'Ulfahul Hani', 'jabatan' => 'Ahli Gizi / Dietisien'],
            ['name' => 'Tri Rahayu', 'jabatan' => 'Staf Instalasi Gizi'],
            ['name' => 'Musringah', 'jabatan' => 'Staf Instalasi Gizi'],
            ['name' => 'Asih Setyowati', 'jabatan' => 'Staf Instalasi Gizi'],
            ['name' => 'Retno Winarni', 'jabatan' => 'Staf Instalasi Gizi'],
            ['name' => 'Asri Widyaningrum', 'jabatan' => 'Staf Instalasi Gizi'],
            ['name' => 'Sri Yanti', 'jabatan' => 'Staf Instalasi Gizi'],
            ['name' => 'Eka Yuhriana', 'jabatan' => 'Staf Instalasi Gizi'],
            ['name' => 'Nur Aeni Istiqomah', 'jabatan' => 'Staf Instalasi Gizi'],
            ['name' => 'Lutfia Mega', 'jabatan' => 'Staf Instalasi Gizi'],
            ['name' => 'Ari Rahmawati', 'jabatan' => 'Staf Instalasi Gizi'],
            ['name' => 'Veri Aryanti', 'jabatan' => 'Staf Instalasi Gizi'],
            ['name' => 'Vieky Amalia', 'jabatan' => 'Staf Instalasi Gizi'],
            ['name' => 'Hastasari Ayuning tiyas', 'jabatan' => 'Staf Instalasi Gizi'],
            ['name' => 'Nur Rokhmah', 'jabatan' => 'Staf Instalasi Gizi'],
            ['name' => 'Dwi Anggorowati', 'jabatan' => 'Staf Instalasi Gizi'],
            ['name' => 'Irsyad Khusni', 'jabatan' => 'Staf Instalasi Gizi'],
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
            ['name' => 'Toha', 'role' => 'Kepala Unit', 'jabatan' => 'Ka. Unit PJBR'],
            ['name' => 'Gunadi', 'jabatan' => 'Staf Unit PJBR'],
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
            ['name' => 'Sucahyo H.F', 'role' => 'Manager', 'jabatan' => 'Manajer'],
            ['name' => 'Dr. Syarif Hadi ', 'role' => 'Kepala Seksi', 'jabatan' => 'Ka. Seksi', 'fungsi' => 'Dokter Umum'],

            ['name' => 'Ali Muakhor', 'jabatan' => 'Staf Humas dan Program RS'],
            ['name' => 'Lusy Rustiyani.,S.Kep', 'jabatan' => 'Staf Humas dan Program RS'],
            ['name' => 'Nur Ardi Firdosti', 'jabatan' => 'Staf Humas dan Program RS'],

            ['name' => 'Wiwit Setia Bekti', 'role' => 'Kepala Seksi', 'jabatan' => 'Ka. Seksi'],
            ['name' => 'Irana Eka Wardana', 'jabatan' => 'Staf Humas dan Program RS'],

            ['name' => 'Asri Dian Premitasari', 'role' => 'Kepala Seksi', 'jabatan' => 'Ka. Seksi'],
            ['name' => 'Shinta Yunita Sari', 'jabatan' => 'Staf Humas dan Program RS'],
            ['name' => 'Lina Narulita', 'jabatan' => 'Staf Humas dan Program RS'],
            ['name' => 'Supriyanto', 'jabatan' => 'Staf Humas dan Program RS'],
        ];

        // Cari unit HUMAS & PROG RS
        $humasUnit = UnitKerja::where('nama', 'HUMAS & PROG RS')->first();

        if (!$humasUnit) {
            $this->command->error('Unit HUMAS & PROG RS tidak ditemukan!');
            return;
        }

        foreach ($humasMembers as $member) {
            $fungsi = isset($member['fungsi']) ? strtolower(trim($member['fungsi'])) : null;
            $jabatan = strtolower(trim($member['jabatan']));

            $KategoriJabatan = KategoriJabatan::whereRaw('LOWER(nama) = ?', [$jabatan])->value('id');
            $FungsiJabatan = $fungsi ? KategoriJabatan::whereRaw('LOWER(nama) = ?', [$fungsi])->value('id') : null;
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $humasUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                    'fungsi_id' => $FungsiJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user HUMAS & PROG RS berhasil dijalankan.');

        // Data untuk unit SDM
        $sdmMembers = [
            ['name' => 'Dr. ardhi Oemar Agustjik', 'nip' => '01160436',  'role' => 'Manager', 'jabatan' => 'Manajer', 'funsgi' => 'Dokter Umum'],
            ['name' => 'Silih Prasetya', 'nip' => '03230610', 'jabatan' => 'Staf SDM'],
            ['name' => 'Riris Afianto', 'role' => 'Kepala Seksi', 'jabatan' => 'Ka. Seksi'],
            ['name' => 'Barkah Setiyani', 'nip' => '03200574', 'jabatan' => 'Staf SDM'],
            ['name' => 'Eko Santoso', 'nip' => '08212183', 'jabatan' => 'Staf SDM'],

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
            // Regex untuk menghapus gelar di awal (Dr., Prof., Ir., H., drg., dll) dan di akhir (Sp.An, S.Ked, M.T, Gaji, dll)
            $cleanName = preg_replace('/^(Dr\.|Prof\.|Ir\.|H\.|Gaji|S\.|A\.|drg\.)\s+|\s*,?\s*(Sp\.\w+|S\.\w+|M\.\w+|Gaji|A\.\w+|H\.\w+|Ir\.\w+|Dr\.\w+|Prof\.\w+|drg\.\w+)$/', '', $member['name']);

            // Hilangkan double space yang mungkin tersisa
            $cleanName = preg_replace('/\s+/', ' ', $cleanName);

            // Hapus spasi dan ubah ke lowercase untuk username
            $username = isset($member['nip']) ? strtolower(str_replace(' ', '', $cleanName)) : null;

            $fungsi = isset($member['fungsi']) ? strtolower(trim($member['fungsi'])) : null;
            $jabatan = strtolower(trim($member['jabatan']));

            $KategoriJabatan = KategoriJabatan::whereRaw('LOWER(nama) = ?', [$jabatan])->value('id');
            $FungsiJabatan = $fungsi ? KategoriJabatan::whereRaw('LOWER(nama) = ?', [$fungsi])->value('id') : null;
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'nip' => $member['nip'] ?? null,
                    'username' => $username,
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $sdmUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                    'fungsi_id' => $FungsiJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user SDM berhasil dijalankan.');

        // Data untuk unit Kepegawaian
        $kepegawaianMembers = [
            ['name' => 'Mister', 'nip' => '02090274', 'role' => 'Kepala Seksi Kepegawaian', 'jabatan' => 'Ka. Seksi',],
            ['name' => 'Diana Melisawati', 'nip' => '03160444', 'role' => 'Staf Kepegawaian', 'jabatan' => 'Staf SDM',],
            ['name' => 'Dyah Novita Sari', 'nip' => '03170456', 'role' => 'Staf Kepegawaian', 'jabatan' => 'Staf SDM',],
        ];

        // Cari unit SDM
        $kepegawaianUnit = UnitKerja::where('nama', 'KEPEGAWAIAN')->first();

        if (!$kepegawaianUnit) {
            $this->command->error('Unit KEPEGAWAIAN tidak ditemukan!');
            return;
        }

        foreach ($kepegawaianMembers as $member) {
            $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'nip' => $member['nip'] ?? null,
                    'username' => $this->usernameFormat($member['name']),
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $kepegawaianUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user KEPEGAWAIAN berhasil dijalankan.');


        // Data untuk unit AKUNTANSI
        $akuntansiMembers = [
            ['name' => 'Endah Lestari D', 'role' => 'Manager', 'jabatan' => 'Manajer'],
            ['name' => 'Nur Aini Oktaviani', 'role' => 'Staf Keuangan', 'jabatan' => 'Staf Akuntansi'],
            ['name' => 'Anissa Vista Tiara Wardhani', 'role' => 'Staf Keuangan', 'jabatan' => 'Staf Akuntansi'],
            ['name' => 'Entoek Puri W', 'role' => 'Staf Keuangan', 'jabatan' => 'Staf Akuntansi'],
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
            ['name' => 'Dr. Kartiko Sumartoyo.,Sp U', 'role' => 'Staf Keuangan', 'jabatan' => 'Wadir', 'fungsi' => 'Dokter Spesialis'],
            ['name' => 'Nur Chalifah', 'role' => 'Kepala Seksi Keuangan', 'jabatan' => 'Ka. Seksi'],
            ['name' => 'Siti Maulidah', 'role' => 'Staf Keuangan', 'jabatan' => 'Staf Keuangan'],
            ['name' => 'Adinda Lionita Hidayah.,SE', 'role' => 'Staf Keuangan', 'jabatan' => 'Staf Keuangan'],
            ['name' => 'Eka Lestari', 'role' => 'Staf Keuangan', 'jabatan' => 'Staf Keuangan'],
            ['name' => 'Dini Inti Wahyuni', 'role' => 'Staf Keuangan', 'jabatan' => 'Staf Keuangan'],
        ];

        // Cari unit KEUANGAN
        $keuanganUnit = UnitKerja::where('nama', 'KEUANGAN')->first();

        if (!$keuanganUnit) {
            $this->command->error('Unit KEUANGAN tidak ditemukan!');
            return;
        }

        foreach ($keuanganMembers as $member) {
            $fungsi = isset($member['fungsi']) ? strtolower(trim($member['fungsi'])) : null;
            $jabatan = strtolower(trim($member['jabatan']));

            $KategoriJabatan = KategoriJabatan::whereRaw('LOWER(nama) = ?', [$jabatan])->value('id');
            $FungsiJabatan = $fungsi ? KategoriJabatan::whereRaw('LOWER(nama) = ?', [$fungsi])->value('id') : null;
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $keuanganUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                    'fungsi_id' => $FungsiJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user KEUANGAN berhasil dijalankan.');

        // Data untuk unit KASIR
        $kasirMembers = [
            ['name' => 'Khodijah', 'role' => 'Staf Keuangan', 'jabatan' => 'Staf Keuangan'],
            ['name' => 'Sri Afti Cahyani', 'role' => 'Staf Keuangan', 'jabatan' => 'Staf Keuangan'],
            ['name' => 'Suci Prihatiyani', 'role' => 'Staf Keuangan', 'jabatan' => 'Staf Keuangan'],
            ['name' => 'Surya Eka Wardani', 'role' => 'Staf Keuangan', 'jabatan' => 'Staf Keuangan'],
            ['name' => 'Liana Yulianti', 'role' => 'Staf Keuangan', 'jabatan' => 'Staf Keuangan'],
            ['name' => 'Candra Bisma Abdul', 'role' => 'Staf Keuangan', 'jabatan' => 'Staf Keuangan'],
            ['name' => 'Dika Muzaqi Eka P', 'role' => 'Staf Keuangan', 'jabatan' => 'Staf Keuangan'],
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

        $this->command->info('Seeder user KASIR berhasil dijalankan.');

        // Data untuk unit ASURANSI
        $asuransiMembers = [
            ['name' => 'Erlita Puspitasari', 'role' => 'Kepala Seksi', 'jabatan' => 'Penanggung Jawab Klaim Asuransi'],
            ['name' => 'Dian Olivia Oktafiyanti', 'jabatan' => 'Staf Asuransi'],
            ['name' => 'Edwin Sulistyo', 'jabatan' => 'Staf Asuransi'],
            ['name' => 'Nila Suprobo', 'jabatan' => 'Staf Asuransi'],
            ['name' => 'Winda Riyana', 'jabatan' => 'Staf Asuransi'],
            ['name' => 'Rahmalina Mentari Putri', 'jabatan' => 'Staf Asuransi'],
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
            ['name' => 'Bani Akbar Dhira Y', 'role' => 'Kepala Seksi', 'jabatan' => 'Ka. Seksi'],
            ['name' => 'Nur Ardi Firdosta', 'jabatan' => 'Staf Aset dan Logistik'],
            ['name' => 'Oryzae Sativa Linies', 'jabatan' => 'Staf Aset dan Logistik'],
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
            // ['name' => 'Dr. Aditya Chandra Putra.,Sp PD', 'role' => 'Manager', 'jabatan' => 'Wadir Pelayanan'],
            ['name' => 'Dr. Dedy Pujo Purnomo.,Sp An', 'role' => 'Manager', 'jabatan' => 'Wadir', 'fungsi' => 'Dokter Spesialis'],
            ['name' => 'Dr. Muhammad Taufiq Hidayat', 'role' => 'Manager', 'jabatan' => 'Manajer', 'fungsi' => 'Dokter Umum'],
            ['name' => 'Dr. Indri Setiani ', 'role' => 'Kepala Seksi', 'jabatan' => 'Ka. Seksi', 'fungsi' => 'Dokter Umum'],
            ['name' => 'Eko Setiono', 'jabatan' => 'Staf Pelayanan Medik'],
            ['name' => 'Lia Eris Fitriani', 'jabatan' => 'Staf Pelayanan Medik'],
        ];

        // Cari unit PELAYANAN MEDIK
        $pelayananMedikUnit = UnitKerja::where('nama', 'PELAYANAN MEDIK')->first();

        if (!$pelayananMedikUnit) {
            $this->command->error('Unit PELAYANAN MEDIK tidak ditemukan!');
            return;
        }

        foreach ($pelayananMedikMembers as $member) {
            $fungsi = isset($member['fungsi']) ? strtolower(trim($member['fungsi'])) : null;
            $jabatan = strtolower(trim($member['jabatan']));

            $KategoriJabatan = KategoriJabatan::whereRaw('LOWER(nama) = ?', [$jabatan])->value('id');
            $FungsiJabatan = $fungsi ? KategoriJabatan::whereRaw('LOWER(nama) = ?', [$fungsi])->value('id') : null;
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $pelayananMedikUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                    'fungsi_id' => $FungsiJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user PELAYANAN MEDIK berhasil dijalankan.');

        // Data untuk unit PENUNJANG
        $penunjangMembers = [
            ['name' => 'Purbo Santosa', 'role' => 'Manager', 'jabatan' => 'Manajer'],
            ['name' => 'Umu Trisniati', 'role' => 'Kepala Seksi', 'jabatan' => 'Ka. Seksi', 'fungsi' => 'Apoteker'],
            ['name' => 'Ahmad Nur Banjari', 'role' => 'Kepala Seksi', 'jabatan' => 'Ka. Seksi'],
            ['name' => 'Mutia Kanza Salama', 'jabatan' => 'Staf Penunjang'],
            ['name' => 'Puspita Chandra Alviana', 'jabatan' => 'Staf Penunjang'],
        ];

        // Cari unit PENUNJANG
        $penunjangUnit = UnitKerja::where('nama', 'PENUNJANG')->first();

        if (!$penunjangUnit) {
            $this->command->error('Unit PENUNJANG tidak ditemukan!');
            return;
        }

        foreach ($penunjangMembers as $member) {
            $fungsi = isset($member['fungsi']) ? strtolower(trim($member['fungsi'])) : null;
            $jabatan = strtolower(trim($member['jabatan']));

            $KategoriJabatan = KategoriJabatan::whereRaw('LOWER(nama) = ?', [$jabatan])->value('id');
            $FungsiJabatan = $fungsi ? KategoriJabatan::whereRaw('LOWER(nama) = ?', [$fungsi])->value('id') : null;
            $user = User::firstOrCreate(
                ['email' => $this->emailFormat($member['name'])],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $penunjangUnit->id,
                    'jabatan_id' => $KategoriJabatan,
                    'fungsi_id' => $FungsiJabatan,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user PENUNJANG berhasil dijalankan.');

        // Data untuk unit KEPERAWATAN
        $keperawatanMembers = [
            ['name' => 'Siti Zaenab', 'role' => 'Manager', 'jabatan' => 'Manajer'],
            ['name' => 'Nurul Ulfah Kh', 'role' => 'Kepala Seksi', 'jabatan' => 'Ka. Seksi'],
            ['name' => 'Rudiati', 'role' => 'Kepala Seksi', 'jabatan' => 'Ka. Seksi'],
            ['name' => 'Rifki Nafisani', 'jabatan' => 'Staf Keperawatan'],
            ['name' => 'Muhadi', 'jabatan' => 'Staf Keperawatan'],
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
            ['name' => 'Eko Pranoto', 'role' => 'Kepala Unit', 'jabatan' => 'Ka. Unit Pengamanan'],
            ['name' => 'Sahro Susilo', 'jabatan' => 'Staf Unit Pengamanan'],
            ['name' => 'Senja Kristiawan', 'jabatan' => 'Staf Unit Pengamanan'],
            ['name' => 'Margianto', 'jabatan' => 'Staf Unit Pengamanan'],
            ['name' => 'Khadmono', 'jabatan' => 'Staf Unit Pengamanan'],
            ['name' => 'Ivan Yunanto', 'jabatan' => 'Staf Unit Pengamanan'],
            ['name' => 'Redyan Chamdan Nofebrianto', 'jabatan' => 'Staf Unit Pengamanan'],
            ['name' => 'Misrun', 'jabatan' => 'Staf Unit Pengamanan'],
            ['name' => 'Hidayat Setiawan', 'jabatan' => 'Staf Unit Pengamanan'],
            ['name' => 'Kharwani', 'jabatan' => 'Staf Unit Pengamanan'],
            ['name' => 'Alfiyan Hidayanto', 'jabatan' => 'Staf Unit Pengamanan'],
            ['name' => 'Yudi Saputra', 'jabatan' => 'Staf Unit Pengamanan'],
            ['name' => 'Sodik Kholidun', 'jabatan' => 'Staf Unit Pengamanan'],
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
            ['name' => 'Adi Setiadi', 'role' => 'Kepala Unit', 'jabatan' => 'Ka. Unit Pemasaran'],
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
            ['name' => 'Wingit Bayu H', 'jabatan' => 'Ners'],
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
            ['name' => 'Fajarianto', 'jabatan' => 'Komite Full Time'],
            ['name' => 'Sarwidi', 'jabatan' => 'Komite Full Time'],
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
        $komiteMembers = [
            ['name' => 'Sarwidi', 'jabatan' => 'Komite Full Time'],
        ];

        // Cari unit KOMITE
        $komiteUnit = UnitKerja::where('nama', 'KOMITE K3RS')->first();

        if (!$komiteUnit) {
            $this->command->error('Unit KOMITE K3RS tidak ditemukan!');
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

        $this->command->info('Seeder user KOMITE K3RS berhasil dijalankan.');

        // Data untuk unit KOMITE
        // $komiteperawatMembers = [
        //     ['name' => 'Joni Krismanto', 'role' => 'Kepala Unit', 'jabatan' => 'Ketua Komite Keperawatan'],
        // ];

        // // Cari unit KOMITE
        // $komiteUnitPerawat = UnitKerja::where('nama', 'KOMITE KEPERAWATAN')->first();

        // if (!$komiteUnitPerawat) {
        //     $this->command->error('Unit KOMITE Keperawatan tidak ditemukan!');
        //     return;
        // }

        // foreach ($komiteperawatMembers as $member) {
        //     $KategoriJabatan = KategoriJabatan::where('nama', $member['jabatan'])->value('id');
        //     $user = User::firstOrCreate(
        //         ['email' => $this->emailFormat($member['name'])],
        //         [
        //             'name' => $member['name'],
        //             'password' => Hash::make('123'), // Password default
        //             'unit_id' => $komiteUnitPerawat->id,
        //             'jabatan_id' => $KategoriJabatan,
        //         ]
        //     );

        //     $role = $member['role'] ?? 'Staf';
        //     $user->assignRole($role);
        // }

        // $this->command->info('Seeder user KOMITE Keperawatan berhasil dijalankan.');

        // Data untuk unit SPI
        $SPI = [
            ['name' => 'Gonggo Iswahyudi', 'role' => 'Kepala Unit', 'jabatan' => 'Ketua SPI'],
            ['name' => 'Lutfian Prisnandika', 'jabatan' => 'Staf Anggota SPI'],
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
            ['name' => 'Usiana Tri R', 'role' => 'Kepala Unit', 'jabatan' => 'Staf SDM'],
            ['name' => 'Dr. Nono Sukarman', 'jabatan' => 'Dokter Umum'],
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
