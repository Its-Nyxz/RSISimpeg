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
    public function run(): void
    {
        // Buat Roles jika belum ada
        $roles = [
            'Super Admin',
            'Kepegawaian',
            'Keuangan',
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

        // Data Kepala Instalasi
        $kepalaInstalasi = [
            ['unit_name' => 'IMP', 'name' => 'Agus Widayat'],
            ['unit_name' => 'INST RANAP', 'name' => 'Tatun Parjiati'],
        ];

        foreach ($kepalaInstalasi as $data) {
            $unit = UnitKerja::where('nama', $data['unit_name'])->first();

            if ($unit) {
                $user = User::firstOrCreate(
                    ['email' => strtolower(str_replace(' ', '.', $data['name'])) . '@gmail.com'],
                    [
                        'name' => $data['name'],
                        'password' => Hash::make('123'),
                        'unit_id' => $unit->id,
                    ]
                );

                $user->assignRole('Kepala Instalasi');
            }
        }

        // Data Kepala Unit
        $kepalaUnit = [
            ['unit_name' => 'IPCN', 'name' => 'Wingit Bayu H'],
        ];

        foreach ($kepalaUnit as $data) {
            $unit = UnitKerja::where('nama', $data['unit_name'])->first();

            if ($unit) {
                $user = User::firstOrCreate(
                    ['email' => strtolower(str_replace(' ', '.', $data['name'])) . '@gmail.com'],
                    [
                        'name' => $data['name'],
                        'password' => Hash::make('123'),
                        'unit_id' => $unit->id,
                    ]
                );

                $user->assignRole('Kepala Unit');
            }
        }

        // Data untuk unit IBS (penambahan data anggota IBS)
        $ibsMembers = [
            ['name' => 'Sulis Setiyanto', 'role' => 'Kepala Unit'],
            ['name' => 'Singgih Triyantoro'],
            ['name' => 'Adam Rachman Sukmana.,A.Md Kep'],
            ['name' => 'Atik Wakiah'],
            ['name' => 'Supriyadi.,A.Md Kep'],
            ['name' => 'Reni Ekawati'],
            ['name' => 'Mohammad Amrulloh'],
            ['name' => 'Riyan Nuryana'],
            ['name' => 'Anifah'],
            ['name' => 'Dwi Agung Nugroho'],
            ['name' => 'Titin Astuti.,S.Kep Ns'],
            ['name' => 'Mat Suwignyo'],
            ['name' => 'Yutiwi .,A.Md Kep'],
            ['name' => 'Umi Sakdiyah'],
            ['name' => 'Restika Dyah Utami'],
            ['name' => 'Arif Yulianto'],
            ['name' => 'Nanang Cahyono'],
            ['name' => 'Sofyanul Affan Hidayat'],
            ['name' => 'Muhammad Iqbal Ramdadhan'],
            ['name' => 'Daryanto'],
            ['name' => 'Ilham Tri Nugroho'],
        ];

        // Cari unit IBS
        $ibsUnit = UnitKerja::where('nama', 'IBS')->first();

        if (!$ibsUnit) {
            $this->command->error('Unit IBS tidak ditemukan!');
            return;
        }

        foreach ($ibsMembers as $member) {
            $user = User::firstOrCreate(
                ['email' => strtolower(str_replace([' ', ',', '.'], '.', $member['name'])) . '@gmail.com'],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'),
                    'unit_id' => $ibsUnit->id,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user IBS berhasil dijalankan .');

        // Data untuk unit IGD
        $igdMembers = [
            ['name' => 'Suyatno', 'role' => 'Kepala Unit'],
            ['name' => 'Dika Ari Utomo'],
            ['name' => 'Brian Sanada'],
            ['name' => 'Soleh Ahmad R'],
            ['name' => 'Mahful.,S.Kep Ns'],
            ['name' => 'Majid Prasetya.,S.Kep Ns'],
            ['name' => 'Gilang Yoga S'],
            ['name' => 'Yuli Ratnasari, S.Kep.Ns'],
            ['name' => 'Ariadi Harko'],
            ['name' => 'Galuh Rimba N'],
            ['name' => 'Novian Hardiyono.,A.Md Kep'],
            ['name' => 'Sabar Tiono.,A.Md Kep'],
            ['name' => 'Aris Cahyono'],
            ['name' => 'Yustika Dwi A'],
            ['name' => 'Dinar Kurniadi.,S.Kep Ns'],
            ['name' => 'Adib Rofiudin Izza'],
            ['name' => 'Indarto'],
            ['name' => 'Albetias Pangestuti'],
            ['name' => 'Swasti Jamalina'],
            ['name' => 'Ajeng Bara Saputri.,A.Md Kep'],
            ['name' => 'Syaeful Fadlan A.,S.Kep Ns'],
            ['name' => 'Hanung Maulana.,A.Md Kep'],
            ['name' => 'Tony Adam'],
            ['name' => 'Aris Aji Pangestu'],
        ];

        // Cari unit IGD
        $igdUnit = UnitKerja::where('nama', 'IGD')->first();

        if (!$igdUnit) {
            $this->command->error('Unit IGD tidak ditemukan!');
            return;
        }

        foreach ($igdMembers as $member) {
            $user = User::firstOrCreate(
                ['email' => strtolower(str_replace([' ', ',', '.'], '.', $member['name'])) . '@gmail.com'],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $igdUnit->id,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user IGD berhasil dijalankan.');

        // Data untuk unit ICU
        $icuMembers = [
            ['name' => 'Tri Nurhidayah', 'role' => 'Kepala Unit'],
            ['name' => 'Jefri Oktavian.,S.Kep Ns'],
            ['name' => 'Ahmad Sulatif'],
            ['name' => 'Eka Dewi Wahyuni'],
            ['name' => 'Budiarto'],
            ['name' => 'Widyandika Yudha'],
            ['name' => 'Reni Yuniarti'],
            ['name' => 'Septi Hartanti'],
            ['name' => 'Sukoyo'],
            ['name' => 'Nia Puspita Utami.,S.Kep Ns'],
            ['name' => 'Aqmarinda Laila'],
            ['name' => 'Dwi Pangestuti.,S.Kep Ns'],
            ['name' => 'Eka Sri Rahayu'],
            ['name' => 'Wahyu Nur Hidayat'],
            ['name' => 'Nur Fitriyadi'],
        ];

        // Cari unit ICU
        $icuUnit = UnitKerja::where('nama', 'ICU')->first();

        if (!$icuUnit) {
            $this->command->error('Unit ICU tidak ditemukan!');
            return;
        }

        foreach ($icuMembers as $member) {
            $user = User::firstOrCreate(
                ['email' => strtolower(str_replace([' ', ',', '.'], '.', $member['name'])) . '@gmail.com'],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $icuUnit->id,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user ICU berhasil dijalankan.');

        // Data untuk unit Instalasi Dialisis
        $dialisisMembers = [
            ['name' => 'Puji Yuliati', 'role' => 'Kepala Unit'],
            ['name' => 'Ari Dwi Astuti'],
            ['name' => 'Sahid Menru H'],
            ['name' => 'Helman Riyadi'],
            ['name' => 'Darmuji'],
            ['name' => "Nur Wakhidah Lulu'ul Jannah"],
        ];

        // Cari unit Instalasi Dialisis
        $dialisisUnit = UnitKerja::where('nama', 'INST DIALISIS')->first();

        if (!$dialisisUnit) {
            $this->command->error('Unit Instalasi Dialisis tidak ditemukan!');
            return;
        }

        foreach ($dialisisMembers as $member) {
            $user = User::firstOrCreate(
                ['email' => strtolower(str_replace([' ', ',', '.'], '.', $member['name'])) . '@gmail.com'],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $dialisisUnit->id,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user Instalasi Dialisis berhasil dijalankan.');

        // Data untuk unit IRJ
        $irjMembers = [
            ['name' => 'Desi Norma', 'role' => 'Kepala Unit'],
            ['name' => 'Yuni Susanti'],
            ['name' => 'Ayu Putri'],
            ['name' => 'Feni Kustiani'],
            ['name' => 'Erviani Ratna P'],
            ['name' => 'Dwi Sulistyo P'],
            ['name' => 'Sutiyah'],
            ['name' => 'Abdul Halim'],
            ['name' => 'Anugrah Noviani'],
            ['name' => 'Roni Wandoyo'],
            ['name' => 'Arni'],
            ['name' => 'Bety Tinaria'],
            ['name' => 'Budi Hastuti'],
            ['name' => 'Ratnaningrum'],
            ['name' => 'Diah Tika Ariani'],
            ['name' => 'Charomatul Amanah'],
            ['name' => 'Nur Alviah'],
            ['name' => 'Sri Wahyuni'],
            ['name' => 'Ais Oktavina'],
            ['name' => 'Miftahul Falah'],
            ['name' => 'Harinto'],
            ['name' => 'Suparman'],
            ['name' => 'Indra Gunawan'],
            ['name' => 'Raihanah Al Mahdiyyah'],
        ];

        // Cari unit IRJ
        $irjUnit = UnitKerja::where('nama', 'IRJ')->first();

        if (!$irjUnit) {
            $this->command->error('Unit IRJ tidak ditemukan!');
            return;
        }

        foreach ($irjMembers as $member) {
            $user = User::firstOrCreate(
                ['email' => strtolower(str_replace([' ', ',', '.'], '.', $member['name'])) . '@gmail.com'],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $irjUnit->id,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user IRJ berhasil dijalankan.');

        // Data untuk unit Perinatologi
        $perinatologiMembers = [
            ['name' => 'Ariyanti Retno A', 'role' => 'Kepala Ruang'],
            ['name' => 'Murni Nurdiyanti'],
            ['name' => 'Laila Oktavia'],
            ['name' => 'Lina Ernawati'],
            ['name' => 'Ani Kurniati'],
            ['name' => 'Meli Roshidatul Fajriyah'],
            ['name' => 'Siti Nurhidayah'],
            ['name' => 'Yurika Fian K'],
            ['name' => 'Yusefi Verawati, Amd.Kep'],
            ['name' => 'Resha Oktaviani.,S.Kep Ns'],
            ['name' => 'Tarwiyah'],
        ];

        // Cari unit Perinatologi
        $perinatologiUnit = UnitKerja::where('nama', 'PERINATOLOGI')->first();

        if (!$perinatologiUnit) {
            $this->command->error('Unit Perinatologi tidak ditemukan!');
            return;
        }

        foreach ($perinatologiMembers as $member) {
            $user = User::firstOrCreate(
                ['email' => strtolower(str_replace([' ', ',', '.'], '.', $member['name'])) . '@gmail.com'],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $perinatologiUnit->id,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user Perinatologi berhasil dijalankan.');

        // Data untuk unit VK
        $vkMembers = [
            ['name' => 'Widanti Kusuma', 'role' => 'Kepala Ruang'],
            ['name' => 'Uswatun Khasanah'],
            ['name' => 'Elga Wulandari'],
            ['name' => 'Angelia Resthy Ratnawati'],
            ['name' => 'Nur Anissa'],
            ['name' => 'Endah Suryani'],
            ['name' => 'Umu Khoiriyah'],
            ['name' => 'Lestari Anggit S'],
            ['name' => 'Dian Septiana'],
            ['name' => 'Hana Putri'],
            ['name' => 'Reni Windi Astuti'],
            ['name' => 'Dwi Apriliyani'],
            ['name' => 'Andini Kurniasih'],
            ['name' => 'Rokhaniyah .,A.Md Keb'],
            ['name' => 'Cici Oviani Agustina.,A.Md Keb'],
            ['name' => 'Eti Yuliana'],
            ['name' => 'Vega Rizkawati'],
            ['name' => 'Melia Dwi S'],
            ['name' => 'Umdatul Ilmi'],
        ];

        // Cari unit VK
        $vkUnit = UnitKerja::where('nama', 'VK')->first();

        if (!$vkUnit) {
            $this->command->error('Unit VK tidak ditemukan!');
            return;
        }

        foreach ($vkMembers as $member) {
            $user = User::firstOrCreate(
                ['email' => strtolower(str_replace([' ', ',', '.'], '.', $member['name'])) . '@gmail.com'],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $vkUnit->id,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user VK berhasil dijalankan.');

        // Data untuk unit ALZAITUN
        $alzaitunMembers = [
            ['name' => 'Wahyu Puspitasari', 'role' => 'Kepala Ruang'],
            ['name' => 'Lili Alimah'],
            ['name' => 'Rinta Ermawati'],
            ['name' => 'Tri Herlina'],
            ['name' => 'Preti Desiana'],
            ['name' => 'Reni Desi Safitri'],
            ['name' => 'Meilia Suharya N'],
            ['name' => 'Liana Andriani'],
            ['name' => 'Fitroh Aulia Dina.,A.Md Keb'],
            ['name' => 'Khusnul Khotimah'],
            ['name' => 'Siti Solehah / Gunung Giana'],
            ['name' => 'Lie Ivani'],
            ['name' => 'Musrifah Afriyanti.,A.Md Keb'],
        ];

        // Cari unit ALZAITUN
        $alzaitunUnit = UnitKerja::where('nama', 'ALZAITUN')->first();

        if (!$alzaitunUnit) {
            $this->command->error('Unit ALZAITUN tidak ditemukan!');
            return;
        }

        foreach ($alzaitunMembers as $member) {
            $user = User::firstOrCreate(
                ['email' => strtolower(str_replace([' ', ',', '.', '/'], '.', $member['name'])) . '@gmail.com'],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $alzaitunUnit->id,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user ALZAITUN berhasil dijalankan.');

        // Data untuk unit AT TAQWA
        $atTaqwaMembers = [
            ['name' => 'Susilo Rudatin', 'role' => 'Kepala Ruang'],
            ['name' => 'Imam Waizun'],
            ['name' => 'Yuyun Purwanti'],
            ['name' => 'Ahmad Sukro'],
            ['name' => 'Andika Susetyo Kaisar.,S.Kep Ns'],
            ['name' => 'Badi Nur Waluyo, S.Kep.Ns.'],
            ['name' => 'Erdika Retno Wulandari.,A.Md Kep'],
            ['name' => 'Slamet Uji K.,S.Kep Ns'],
            ['name' => 'Putri Puja P.,A.Md Kep'],
            ['name' => 'Jodi Fajar Wiguna.,A.Md Kep'],
            ['name' => 'Tri Mutmainah.,A.Md Kep'],
            ['name' => 'Wahyu Nur F.,S.Kep Ns'],
            ['name' => 'Slamet Nikmat'],
            ['name' => 'Wahidatun Toyibah'],
            ['name' => 'Prana Sakti Ibnu Oetomo'],
            ['name' => 'Yuda Valentino.,S.Kep Ns'],
            ['name' => 'Rozaul Muta\'ali'],
        ];

        // Cari unit AT TAQWA
        $atTaqwaUnit = UnitKerja::where('nama', 'AT TAQWA')->first();

        if (!$atTaqwaUnit) {
            $this->command->error('Unit AT TAQWA tidak ditemukan!');
            return;
        }

        foreach ($atTaqwaMembers as $member) {
            $user = User::firstOrCreate(
                ['email' => strtolower(str_replace([' ', ',', '.', '\''], '.', $member['name'])) . '@gmail.com'],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $atTaqwaUnit->id,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user AT TAQWA berhasil dijalankan.');

        // Data untuk unit ASSALAM
        $assalamMembers = [
            ['name' => 'Indriyana Keliek F', 'role' => 'Kepala Ruang'],
            ['name' => 'Purwadi'],
            ['name' => 'Rini Wijayanti'],
            ['name' => 'Dwi Sukur W'],
            ['name' => 'Diyah Enggar Tri'],
            ['name' => 'Yunut Jenianto'],
            ['name' => 'Irma Damayanti'],
            ['name' => 'Slamet Prihatin'],
            ['name' => 'Atsari Robihah'],
            ['name' => 'Diding Panca P.,A.Md Kep'],
            ['name' => 'Riana Dwi Agustina.,S.Kep Ns'],
            ['name' => 'Ikhsan Saifudin.,S.Kep Ns'],
            ['name' => 'Dwi Yulianti.,S.Kep Ns'],
            ['name' => 'Khomsa Fadillah R'],
        ];

        // Cari unit ASSALAM
        $assalamUnit = UnitKerja::where('nama', 'ASSALAM')->first();

        if (!$assalamUnit) {
            $this->command->error('Unit ASSALAM tidak ditemukan!');
            return;
        }

        foreach ($assalamMembers as $member) {
            $user = User::firstOrCreate(
                ['email' => strtolower(str_replace([' ', ',', '.', '\''], '.', $member['name'])) . '@gmail.com'],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $assalamUnit->id,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user ASSALAM berhasil dijalankan.');

        // Data untuk unit AL AMIN
        $alAminMembers = [
            ['name' => 'Siti Markhamah', 'role' => 'Kepala Ruang'],
            ['name' => 'Deni Amrulloh'],
            ['name' => 'Buyung Pambudi'],
            ['name' => 'Ina Karunia'],
            ['name' => 'Rian Diah Utami'],
            ['name' => 'Nigeffe Pasalaila'],
            ['name' => 'Gayuh Dwi Laksono.,S.Kep Ns'],
            ['name' => 'Tri Ningsih Nawang Sasi.,S.Kep Ns'],
            ['name' => 'Tekad Setiawan'],
            ['name' => 'Wahidun'],
            ['name' => 'Haris Naufal Faizi.,S.Kep Ns'],
        ];

        // Cari unit AL AMIN
        $alAminUnit = UnitKerja::where('nama', 'AL AMIN')->first();

        if (!$alAminUnit) {
            $this->command->error('Unit AL AMIN tidak ditemukan!');
            return;
        }

        foreach ($alAminMembers as $member) {
            $user = User::firstOrCreate(
                ['email' => strtolower(str_replace([' ', ',', '.', '\''], '.', $member['name'])) . '@gmail.com'],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $alAminUnit->id,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user AL AMIN berhasil dijalankan.');

        // Data untuk unit FIRDAUS
        $firdausMembers = [
            ['name' => 'Ika Sari Sholehati', 'role' => 'Kepala Ruang'],
            ['name' => 'Syafira Diaz Maisyaroh'],
            ['name' => 'Sinta Puspita'],
            ['name' => 'Nony Marlina'],
            ['name' => 'Septi Indriwati'],
            ['name' => 'Rini Utami'],
            ['name' => 'Walyanti.,S.Kep Ns'],
            ['name' => 'Rizki Dwi A.,S.Kep Ns'],
            ['name' => 'Rica Karomah, Amd.Kep.'],
            ['name' => 'Laili Dwi Artati'],
            ['name' => 'Sudarmi'],
        ];

        // Cari unit FIRDAUS
        $firdausUnit = UnitKerja::where('nama', 'FIRDAUS')->first();

        if (!$firdausUnit) {
            $this->command->error('Unit FIRDAUS tidak ditemukan!');
            return;
        }

        foreach ($firdausMembers as $member) {
            $user = User::firstOrCreate(
                ['email' => strtolower(str_replace([' ', ',', '.', '\''], '.', $member['name'])) . '@gmail.com'],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $firdausUnit->id,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user FIRDAUS berhasil dijalankan.');

        // Data untuk unit HAJI
        $hajiMembers = [
            ['name' => 'Umu Hani', 'role' => 'Kepala Ruang'],
            ['name' => 'Windu Kusuma W'],
            ['name' => 'Etha Setyana'],
            ['name' => 'Neneng Susmas Netty'],
            ['name' => 'Akhmad Sulukhi'],
            ['name' => 'Purnama Setya Cahyadi.,S.Kep Ns'],
            ['name' => 'Sisanto'],
            ['name' => 'Adistia Yunita Nurfaega.,A.Md Kep'],
            ['name' => 'Tri Susanto.,A.Md Kep'],
            ['name' => 'Abto Deswar Diansyah.,A.Md Kep'],
            ['name' => 'I Wayan Arianto'],
            ['name' => 'Tri Handoko'],
            ['name' => 'Choerul Elma Subarkah'],
            ['name' => 'Ratna Sulistiyani.,A.Md Kep'],
            ['name' => 'Siti Azizah.,A.Md Kep'],
            ['name' => 'Feli Tri Yuliana.,S.Kep Ns'],
            ['name' => 'Ridwan Saputra'],
            ['name' => 'Gugun Galuh Rahmanmaulana'],
        ];

        // Cari unit HAJI
        $hajiUnit = UnitKerja::where('nama', 'HAJI')->first();

        if (!$hajiUnit) {
            $this->command->error('Unit HAJI tidak ditemukan!');
            return;
        }

        foreach ($hajiMembers as $member) {
            $user = User::firstOrCreate(
                ['email' => strtolower(str_replace([' ', ',', '.', '\''], '.', $member['name'])) . '@gmail.com'],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $hajiUnit->id,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user HAJI berhasil dijalankan.');

        // Data untuk unit ASSYFA
        $assyfaMembers = [
            ['name' => 'Ifa Fitria', 'role' => 'Kepala Ruang'],
            ['name' => 'Fuandraeni F'],
            ['name' => 'Titi Yulia A'],
            ['name' => 'Ferawati Trianasari'],
            ['name' => 'Sasmita Ilmi'],
            ['name' => 'Aprilia Atita Bela Adila, Amd.Kep.'],
            ['name' => 'Febtriyanto, S.Kep.Ns.'],
            ['name' => 'Deni Ambang.,A.Md Kep'],
            ['name' => 'Dendi Wahyu S'],
            ['name' => 'Anggoro Panggih'],
            ['name' => 'Cahya Indra Lukmana, S.Kep.Ns.'],
            ['name' => 'Drajat Wahyu I.,A.Md Kep'],
            ['name' => 'Vendrha Zani Zegal'],
            ['name' => 'Wulan Tri Mulyani.,S.Kep Ns'],
            ['name' => 'Tiara Arindha Wibowo.,S.Kep Ns'],
            ['name' => 'Didik Prapto Sasongko,S.Kep Ns'],
            ['name' => 'Vicky Indra Wibowo'],
        ];

        // Cari unit ASSYFA
        $assyfaUnit = UnitKerja::where('nama', 'ASSYFA')->first();

        if (!$assyfaUnit) {
            $this->command->error('Unit ASSYFA tidak ditemukan!');
            return;
        }

        foreach ($assyfaMembers as $member) {
            $user = User::firstOrCreate(
                ['email' => strtolower(str_replace([' ', ',', '.', '\''], '.', $member['name'])) . '@gmail.com'],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $assyfaUnit->id,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user ASSYFA berhasil dijalankan.');

        // Data untuk unit AZIZIAH
        $aziziahMembers = [
            ['name' => 'Slamet Supratomo', 'role' => 'Kepala Ruang'],
            ['name' => 'Ediy Santosa'],
            ['name' => 'Agus Suprihanto'],
            ['name' => 'Yanuar Puthut Wijonarko'],
            ['name' => 'Suryo Aji'],
            ['name' => 'Wahyu Sri Sadono'],
            ['name' => 'Feri Susanto'],
            ['name' => 'Dian Ratnasari'],
            ['name' => 'Maita Indah P'],
            ['name' => 'Nuripan'],
            ['name' => 'Fajar Tri Pambudi'],
            ['name' => 'Dika Adi S'],
        ];

        // Cari unit AZIZIAH
        $aziziahUnit = UnitKerja::where('nama', 'AZIZIAH')->first();

        if (!$aziziahUnit) {
            $this->command->error('Unit AZIZIAH tidak ditemukan!');
            return;
        }

        foreach ($aziziahMembers as $member) {
            $user = User::firstOrCreate(
                ['email' => strtolower(str_replace([' ', ',', '.', '\''], '.', $member['name'])) . '@gmail.com'],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $aziziahUnit->id,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user AZIZIAH berhasil dijalankan.');

        // Data untuk unit ALMUNAWAROH
        $almunawarohMembers = [
            ['name' => 'Latifah', 'role' => 'Kepala Ruang'],
            ['name' => 'Eka Nur Fitri'],
            ['name' => 'Catur Noviantiko'],
            ['name' => 'Zuhri Nikmatuloh Zulfikar'],
            ['name' => 'Moch. Sukur'],
            ['name' => 'Wiwi Kusniati'],
            ['name' => 'Emi Dwi Listia'],
            ['name' => 'Faizal Tamim Al- Mundziri, Amd.Kep.'],
            ['name' => 'Eling Tiyasari.,A.Md Kep'],
            ['name' => 'Samudin'],
            ['name' => 'Khasiful Fuad'],
        ];

        // Cari unit ALMUNAWAROH
        $almunawarohUnit = UnitKerja::where('nama', 'ALMUNAWAROH')->first();

        if (!$almunawarohUnit) {
            $this->command->error('Unit ALMUNAWAROH tidak ditemukan!');
            return;
        }

        foreach ($almunawarohMembers as $member) {
            $user = User::firstOrCreate(
                ['email' => strtolower(str_replace([' ', ',', '.', '\'', '-'], '.', $member['name'])) . '@gmail.com'],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $almunawarohUnit->id,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user ALMUNAWAROH berhasil dijalankan.');

        // Data untuk unit INST REHAB MEDIK
        $rehabMedikMembers = [
            ['name' => 'Slamet Budi Santosa', 'role' => 'Kepala Ruang'],
            ['name' => "Lu'lu u Al Hikmah.,A.Md Fis"],
            ['name' => 'Dhanti Wahyundari.,Ftr'],
            ['name' => 'Ucik Auliya.,A.Md Kes'],
        ];

        // Cari unit INST REHAB MEDIK
        $rehabMedikUnit = UnitKerja::where('nama', 'INST REHAB MEDIK')->first();

        if (!$rehabMedikUnit) {
            $this->command->error('Unit INST REHAB MEDIK tidak ditemukan!');
            return;
        }

        foreach ($rehabMedikMembers as $member) {
            $user = User::firstOrCreate(
                ['email' => strtolower(str_replace([' ', ',', '.', '\'', '-'], '.', $member['name'])) . '@gmail.com'],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $rehabMedikUnit->id,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user INST REHAB MEDIK berhasil dijalankan.');

        // Data untuk unit CASE MANAGER
        $caseManagerMembers = [
            ['name' => 'Ari Fitria', 'role' => 'Kepala Unit'],
            ['name' => 'Didik Setiawan'],
            ['name' => 'Tzalis Ubaidillah'],
            ['name' => 'Ari Yogo P'],
            ['name' => 'Ismi Ngaisatun'],
            ['name' => 'Andang Pramana'],
        ];

        // Cari unit CASE MANAGER
        $caseManagerUnit = UnitKerja::where('nama', 'CASE MANAGER')->first();

        if (!$caseManagerUnit) {
            $this->command->error('Unit CASE MANAGER tidak ditemukan!');
            return;
        }

        foreach ($caseManagerMembers as $member) {
            $user = User::firstOrCreate(
                ['email' => strtolower(str_replace([' ', ',', '.', '\'', '-'], '.', $member['name'])) . '@gmail.com'],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $caseManagerUnit->id,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user CASE MANAGER berhasil dijalankan.');

        // Data untuk unit INST REKAM MEDIK
        $rekamMedikMembers = [
            ['name' => 'Wigati', 'role' => 'Kepala Instalasi'],
            ['name' => 'Deka Prasetiyanti'],
            ['name' => 'Lina Sandyasari'],
            ['name' => 'Lina Afiyanti'],
            ['name' => 'Meiga Kencana Putri.,A.Md'],
            ['name' => 'Kavi Nurul Firdaus'],
            ['name' => 'Andi Gunawan'],
            ['name' => 'Gayuh Prasetyo'],
            ['name' => 'Eric Setiawan'],
            ['name' => 'Agus Waluyo'],
            ['name' => 'Suci Rahmawati'],
            ['name' => 'Rifka Winda Listanti'],
            ['name' => 'Novi Purbasari'],
            ['name' => 'Angga Putra Pratama'],
            ['name' => 'Wagianto'],
            ['name' => 'Nurul Fatiah'],
            ['name' => 'Zulfa Nurmutaqin'],
            ['name' => 'Hamid Badawi Hasan'],
            ['name' => 'Dzaky Arhiska Daffa'],
            ['name' => 'Puji Lestari'],
        ];

        // Cari unit INST REKAM MEDIK
        $rekamMedikUnit = UnitKerja::where('nama', 'INST REKAM MEDIK')->first();

        if (!$rekamMedikUnit) {
            $this->command->error('Unit INST REKAM MEDIK tidak ditemukan!');
            return;
        }

        foreach ($rekamMedikMembers as $member) {
            $user = User::firstOrCreate(
                ['email' => strtolower(str_replace([' ', ',', '.', '\''], '.', $member['name'])) . '@gmail.com'],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $rekamMedikUnit->id,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user INST REKAM MEDIK berhasil dijalankan.');

        $farmasiMembers = [
            ['name' => 'Uniek Setyawardani', 'role' => 'Kepala Instalasi'],
            ['name' => 'Rizqi Ayu Amalina., S.Farm Apt'],
            ['name' => 'Tri Wahyu Yuni Kosiah'],
            ['name' => 'Yunika Wulansari'],
            ['name' => 'Edy Purwanto'],
            ['name' => 'Susi Susanti'],
            ['name' => 'Sabaniah Dwi H'],
            ['name' => 'Octamiarso Eko R'],
            ['name' => 'Wiwin Nur Supriyanti'],
            ['name' => 'Alifah Zovia Mordan'],
            ['name' => 'Saguh Pambudi'],
            ['name' => 'Widaryati'],
            ['name' => 'Siti Solekhah / Andang'],
            ['name' => 'Nuzul Ayu Pangestika'],
            ['name' => 'Febri Zaeni Ikhsan'],
            ['name' => 'Tri Hidayati'],
            ['name' => 'Desiana Nur Handayani'],
            ['name' => 'Afriliana Nurahimah'],
            ['name' => 'Dika Destiani.,S.Farm Apt'],
            ['name' => 'Faizatul Istiqomah .,S.Farm Apt'],
            ['name' => 'Irma Okida Anggraeni'],
            ['name' => 'Titin Lestari'],
            ['name' => 'Damar Dwi Sasongko'],
            ['name' => 'Purwaningsih.,A.Md Farm'],
        ];

        // Cari unit INST FARMASI
        $farmasiUnit = UnitKerja::where('nama', 'INST FARMASI')->first();

        if (!$farmasiUnit) {
            $this->command->error('Unit INST FARMASI tidak ditemukan!');
            return;
        }

        foreach ($farmasiMembers as $member) {
            $user = User::firstOrCreate(
                ['email' => strtolower(str_replace([' ', ',', '.', '/', '\'', '-'], '.', $member['name'])) . '@gmail.com'],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $farmasiUnit->id,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user INST FARMASI berhasil dijalankan.');

        // Data untuk unit INST RADIOLOGI
        $radiologiMembers = [
            ['name' => 'Wisnu Kuncahyo', 'role' => 'Kepala Ruang'],
            ['name' => 'Fatkhur Rokhman'],
            ['name' => 'Lulu Khoirunita l'],
            ['name' => 'Diah Larasati W'],
            ['name' => 'Nur Yamin.,A.Md Rad'],
            ['name' => 'Yuniasih Kurniawati'],
        ];

        // Cari unit INST RADIOLOGI
        $radiologiUnit = UnitKerja::where('nama', 'INST RADIOLOGI')->first();

        if (!$radiologiUnit) {
            $this->command->error('Unit INST RADIOLOGI tidak ditemukan!');
            return;
        }

        foreach ($radiologiMembers as $member) {
            $user = User::firstOrCreate(
                ['email' => strtolower(str_replace([' ', ',', '.', '/', '\'', '-'], '.', $member['name'])) . '@gmail.com'],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $radiologiUnit->id,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user INST RADIOLOGI berhasil dijalankan.');

        // Data untuk unit INST LABORATORIUM
        $laboratoriumMembers = [
            ['name' => 'Joko Sugiharto', 'role' => 'Kepala Instalasi'],
            ['name' => 'Eka Prihartiningsih.,A.Md AK'],
            ['name' => 'Wahyu Setiyo W.,A.Md AK'],
            ['name' => 'Vita Dwi Mulatsih'],
            ['name' => 'Laelly Yuni Sugesty'],
            ['name' => 'Maya Irenne Ratu'],
            ['name' => 'Nia Musaadah'],
            ['name' => 'Devi Novita Triana'],
            ['name' => 'Alfina Reinada Hapsari.,A.Md AK'],
            ['name' => 'Nurhayati'],
            ['name' => 'Ismanto'],
            ['name' => 'Yuniara Fika Tri P'],
            ['name' => 'Zaenal Arifin'],
            ['name' => 'Rosna Erviana'],
            ['name' => 'Diaz Cagar Biru Langit'],
            ['name' => 'Argandari.,A.Md AK'],
            ['name' => 'Tyas Asalwa Nabila'],
        ];

        // Cari unit INST LABORATORIUM
        $laboratoriumUnit = UnitKerja::where('nama', 'INST LABORATORIUM')->first();

        if (!$laboratoriumUnit) {
            $this->command->error('Unit INST LABORATORIUM tidak ditemukan!');
            return;
        }

        foreach ($laboratoriumMembers as $member) {
            $user = User::firstOrCreate(
                ['email' => strtolower(str_replace([' ', ',', '.', '/', '\'', '-'], '.', $member['name'])) . '@gmail.com'],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $laboratoriumUnit->id,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user INST LABORATORIUM berhasil dijalankan.');

        // Data untuk unit INST SANITASI
        $sanitasiMembers = [
            ['name' => 'A Imam Mutaqin', 'role' => 'Kepala Instalasi'],
            ['name' => 'Ardi Febriyanto'],
            ['name' => 'Adiyono'],
            ['name' => 'Aji Widianto'],
            ['name' => 'Gesit Purnama Ghyan'],
            ['name' => 'Muhamad Saefuloh'],
            ['name' => 'Agus Sutomo'],
            ['name' => 'Bambang Hermanto'],
            ['name' => 'Khayatno Setiawan'],
            ['name' => 'Setya Budi'],
            ['name' => 'Sekar Antik Larasati'],
            ['name' => 'Kamil Zulfikar'],
        ];

        // Cari unit INST SANITASI
        $sanitasiUnit = UnitKerja::where('nama', 'INST SANITASI')->first();

        if (!$sanitasiUnit) {
            $this->command->error('Unit INST SANITASI tidak ditemukan!');
            return;
        }

        foreach ($sanitasiMembers as $member) {
            $user = User::firstOrCreate(
                ['email' => strtolower(str_replace([' ', ',', '.', '/', '\'', '-'], '.', $member['name'])) . '@gmail.com'],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $sanitasiUnit->id,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user INST SANITASI berhasil dijalankan.');

        // Data untuk unit INST CSSD
        $cssdMembers = [
            ['name' => 'Khamidah', 'role' => 'Kepala Instalasi'],
            ['name' => 'M. Agung Prastowo'],
            ['name' => 'Ipung Prayogi'],
            ['name' => 'Fajar Suryo Purnomo'],
            ['name' => 'Edi Priyanto'],
        ];

        // Cari unit INST CSSD
        $cssdUnit = UnitKerja::where('nama', 'INST CSSD')->first();

        if (!$cssdUnit) {
            $this->command->error('Unit INST CSSD tidak ditemukan!');
            return;
        }

        foreach ($cssdMembers as $member) {
            $user = User::firstOrCreate(
                ['email' => strtolower(str_replace([' ', ',', '.', '/', '\'', '-'], '.', $member['name'])) . '@gmail.com'],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $cssdUnit->id,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user INST CSSD berhasil dijalankan.');

        // Data untuk unit INST PEML SARPRAS
        $sarprasMembers = [
            ['name' => 'Widodo Pindah Riyanto', 'role' => 'Kepala Instalasi'],
            ['name' => 'Agus Riyanto'],
            ['name' => 'Agus Rahmat S'],
            ['name' => 'Nur Fauzi Achmad'],
            ['name' => 'M.Soleman'],
            ['name' => 'Febriyanto'],
            ['name' => 'Rizal Muntadlo'],
            ['name' => 'Achmad Anton Triyono'],
        ];

        // Cari unit INST PEML SARPRAS
        $sarprasUnit = UnitKerja::where('nama', 'INST PEML SARPRAS')->first();

        if (!$sarprasUnit) {
            $this->command->error('Unit INST PEML SARPRAS tidak ditemukan!');
            return;
        }

        foreach ($sarprasMembers as $member) {
            $user = User::firstOrCreate(
                ['email' => strtolower(str_replace([' ', ',', '.', '/', '\'', '-'], '.', $member['name'])) . '@gmail.com'],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $sarprasUnit->id,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user INST PEML SARPRAS berhasil dijalankan.');

        // Data untuk unit INST GAS MEDIK & ALKES
        $gasMedikAlkesMembers = [
            ['name' => 'Adityana Juni Saputra.,A.Md'],
            ['name' => 'Puji Triono'],
        ];

        // Cari unit INST GAS MEDIK & ALKES
        $gasMedikAlkesUnit = UnitKerja::where('nama', 'INST GAS MEDIK & ALKES')->first();

        if (!$gasMedikAlkesUnit) {
            $this->command->error('Unit INST GAS MEDIK & ALKES tidak ditemukan!');
            return;
        }

        foreach ($gasMedikAlkesMembers as $member) {
            $user = User::firstOrCreate(
                ['email' => strtolower(str_replace([' ', ',', '.', '/', '\'', '-'], '.', $member['name'])) . '@gmail.com'],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $gasMedikAlkesUnit->id,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user INST GAS MEDIK & ALKES berhasil dijalankan.');



        // Data untuk unit UNIT MCU & POSKES
        $mcuPoskesMembers = [
            ['name' => 'Ruslan', 'role' => 'Kepala Unit'],
            ['name' => 'Mamat Setiawan'],
            ['name' => 'Robby Ilmiawan'],
        ];

        // Cari unit UNIT MCU & POSKES
        $mcuPoskesUnit = UnitKerja::where('nama', 'MCU & POSKES')->first();

        if (!$mcuPoskesUnit) {
            $this->command->error('Unit MCU & POSKES tidak ditemukan!');
            return;
        }

        foreach ($mcuPoskesMembers as $member) {
            $user = User::firstOrCreate(
                ['email' => strtolower(str_replace([' ', ',', '.', '/', '\'', '-'], '.', $member['name'])) . '@gmail.com'],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $mcuPoskesUnit->id,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user UNIT MCU & POSKES berhasil dijalankan.');

        // Data untuk unit UNIT TRANSPORTASI
        $transportasiMembers = [
            ['name' => 'Durul Farid', 'role' => 'Kepala Unit'],
            ['name' => 'Arif Suhendra', 'role' => 'Kepala Unit'],
            ['name' => 'Afria Sofan'],
            ['name' => 'Suwarno'],
            ['name' => 'Rakhmat Mubasyier'],
            ['name' => 'M.Rofik'],
            ['name' => 'Margo Nursuwono'],
            ['name' => 'M.Ari Arif H'],
            ['name' => 'Sarwidi'],
        ];

        // Cari unit UNIT TRANSPORTASI
        $transportasiUnit = UnitKerja::where('nama', 'TRANSPORTASI')->first();

        if (!$transportasiUnit) {
            $this->command->error('Unit TRANSPORTASI tidak ditemukan!');
            return;
        }

        foreach ($transportasiMembers as $member) {
            $user = User::firstOrCreate(
                ['email' => strtolower(str_replace([' ', ',', '.', '/', '\'', '-'], '.', $member['name'])) . '@gmail.com'],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $transportasiUnit->id,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user UNIT TRANSPORTASI berhasil dijalankan.');

        // Data untuk unit INST GIZI
        $giziMembers = [
            ['name' => 'Pujiningsih', 'role' => 'Kepala Instalasi'],
            ['name' => 'Ulfahul Hani'],
            ['name' => 'Tri Rahayu'],
            ['name' => 'Musringah'],
            ['name' => 'Asih Setyowati'],
            ['name' => 'Ari Rahmawati'],
            ['name' => 'Retno Winarni'],
            ['name' => 'Asri Widyaningrum'],
            ['name' => 'Sri Yanti'],
            ['name' => 'Eka Yuhriana'],
            ['name' => 'Nur Aeni Istiqomah'],
            ['name' => 'Lutfia Mega'],
            ['name' => 'Veri Aryanti'],
            ['name' => 'Vieky Amalia'],
            ['name' => 'Hastasari Ayuningtyas'],
            ['name' => 'Nur Rokhmah'],
            ['name' => 'Dwi Anggorowati'],
            ['name' => 'Irsyad Khusni'],
            ['name' => 'Umar Hasanudin'],
        ];

        // Cari unit INST GIZI
        $giziUnit = UnitKerja::where('nama', 'INST GIZI')->first();

        if (!$giziUnit) {
            $this->command->error('Unit INST GIZI tidak ditemukan!');
            return;
        }

        foreach ($giziMembers as $member) {
            $user = User::firstOrCreate(
                ['email' => strtolower(str_replace([' ', ',', '.', '/', '\'', '-'], '.', $member['name'])) . '@gmail.com'],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $giziUnit->id,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user INST GIZI berhasil dijalankan.');

        // Data untuk unit UNIT PJBR
        $pjbrMembers = [
            ['name' => 'Toha', 'role' => 'Kepala Unit'],
            ['name' => 'Gunadi'],
        ];

        // Cari unit UNIT PJBR
        $pjbrUnit = UnitKerja::where('nama', 'PJBR')->first();

        if (!$pjbrUnit) {
            $this->command->error('Unit PJBR tidak ditemukan!');
            return;
        }

        foreach ($pjbrMembers as $member) {
            $user = User::firstOrCreate(
                ['email' => strtolower(str_replace([' ', ',', '.', '/', '\'', '-'], '.', $member['name'])) . '@gmail.com'],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $pjbrUnit->id,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user UNIT PJBR berhasil dijalankan.');

        // Data untuk unit UNIT PENGELOLAAN LINEN
        $linenMembers = [
            ['name' => 'Budiono', 'role' => 'Kepala Unit'],
            ['name' => 'Hari Nugroho'],
            ['name' => 'Bariroh Ahmad'],
            ['name' => 'Mustangin'],
            ['name' => 'Rifin Supratman'],
            ['name' => 'Agus Junaedi'],
        ];

        // Cari unit UNIT PENGELOLAAN LINEN
        $linenUnit = UnitKerja::where('nama', 'PENGELOLAAN LINEN')->first();

        if (!$linenUnit) {
            $this->command->error('Unit PENGELOLAAN LINEN tidak ditemukan!');
            return;
        }

        foreach ($linenMembers as $member) {
            $user = User::firstOrCreate(
                ['email' => strtolower(str_replace([' ', ',', '.', '/', '\'', '-'], '.', $member['name'])) . '@gmail.com'],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $linenUnit->id,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user UNIT PENGELOLAAN LINEN berhasil dijalankan.');

        // Data untuk unit HUMAS & PROG RS
        $humasMembers = [
            ['name' => 'Ali Muakhor', 'role' => 'Kepala Seksi'],
            ['name' => 'Lusy Rustiyani.,S.Kep'],
            ['name' => 'Nur Ardi Firdosti'],
            ['name' => 'Irana Eka Wardana'],
            ['name' => 'Shinta Yunita Sari'],
            ['name' => 'Lina Narulita'],
            ['name' => 'Supriyanto'],
            ['name' => 'Wiwit Setia Bekti', 'role' => 'Kepala Seksi'],
            ['name' => 'Irana Eka Wardana'],
            ['name' => 'Asri Dian Premitasari', 'role' => 'Kepala Seksi'],
        ];

        // Cari unit HUMAS & PROG RS
        $humasUnit = UnitKerja::where('nama', 'HUMAS & PROG RS')->first();

        if (!$humasUnit) {
            $this->command->error('Unit HUMAS & PROG RS tidak ditemukan!');
            return;
        }

        foreach ($humasMembers as $member) {
            $user = User::firstOrCreate(
                ['email' => strtolower(str_replace([' ', ',', '.', '/', '\'', '-'], '.', $member['name'])) . '@gmail.com'],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $humasUnit->id,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user HUMAS & PROG RS berhasil dijalankan.');

        // Data untuk unit SDM
        $sdmMembers = [
            ['name' => 'Mister', 'role' => 'Kepala Seksi'],
            ['name' => 'Riris Afianto', 'role' => 'Kepala Seksi'],
            ['name' => 'Diana Melisawati'],
            ['name' => 'Dyah Novita Sari'],
            ['name' => 'Barkah Setiyani'],
            ['name' => 'Silih Prasetya'],
            ['name' => 'Eko Santoso'],
        ];

        // Cari unit SDM
        $sdmUnit = UnitKerja::where('nama', 'SDM')->first();

        if (!$sdmUnit) {
            $this->command->error('Unit SDM tidak ditemukan!');
            return;
        }

        foreach ($sdmMembers as $member) {
            $user = User::firstOrCreate(
                ['email' => strtolower(str_replace([' ', ',', '.', '/', '\'', '-'], '.', $member['name'])) . '@gmail.com'],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $sdmUnit->id,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user SDM berhasil dijalankan.');

        // Data untuk unit AKUNTANSI
        $akuntansiMembers = [
            ['name' => 'Nur Aini Oktaviani', 'role' => 'Kepala Seksi'],
            ['name' => 'Anissa Vista Tiara Wardhani'],
            ['name' => 'Entoek Puri W'],
        ];

        // Cari unit AKUNTANSI
        $akuntansiUnit = UnitKerja::where('nama', 'AKUNTANSI')->first();

        if (!$akuntansiUnit) {
            $this->command->error('Unit AKUNTANSI tidak ditemukan!');
            return;
        }

        foreach ($akuntansiMembers as $member) {
            $user = User::firstOrCreate(
                ['email' => strtolower(str_replace([' ', ',', '.', '/', '\'', '-'], '.', $member['name'])) . '@gmail.com'],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $akuntansiUnit->id,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user AKUNTANSI berhasil dijalankan.');

        // Data untuk unit KEUANGAN
        $keuanganMembers = [
            ['name' => 'Siti Maulidah', 'role' => 'Kepala Seksi'],
            ['name' => 'Adinda Lionita Hidayah.,SE'],
            ['name' => 'Eka Lestari'],
            ['name' => 'Dini Inti'],
        ];

        // Cari unit KEUANGAN
        $keuanganUnit = UnitKerja::where('nama', 'KEUANGAN')->first();

        if (!$keuanganUnit) {
            $this->command->error('Unit KEUANGAN tidak ditemukan!');
            return;
        }

        foreach ($keuanganMembers as $member) {
            $user = User::firstOrCreate(
                ['email' => strtolower(str_replace([' ', ',', '.', '/', '\'', '-'], '.', $member['name'])) . '@gmail.com'],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $keuanganUnit->id,
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
                ['email' => strtolower(str_replace([' ', ',', '.', '/', '\'', '-'], '.', $member['name'])) . '@gmail.com'],
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
            ['name' => 'Erlita Puspitasari', 'role' => 'Kepala Unit'],
            ['name' => 'Dian Olivia Oktafiyanti'],
            ['name' => 'Edwin Sulistyo'],
            ['name' => 'Nila Suprobo'],
            ['name' => 'Winda Riyana'],
            ['name' => 'Rahmalina Mentari Putri'],
        ];

        // Cari unit ASURANSI
        $asuransiUnit = UnitKerja::where('nama', 'ASURANSI')->first();

        if (!$asuransiUnit) {
            $this->command->error('Unit ASURANSI tidak ditemukan!');
            return;
        }

        foreach ($asuransiMembers as $member) {
            $user = User::firstOrCreate(
                ['email' => strtolower(str_replace([' ', ',', '.', '/', '\'', '-'], '.', $member['name'])) . '@gmail.com'],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $asuransiUnit->id,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user ASURANSI berhasil dijalankan.');

        // Data untuk unit ASET & LOGISTIK
        $asetLogistikMembers = [
            ['name' => 'Ratih Titis P', 'role' => 'Kepala Unit'],
            ['name' => 'Nur Ardi Firdosta'],
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
            $user = User::firstOrCreate(
                ['email' => strtolower(str_replace([' ', ',', '.', '/', '\'', '-'], '.', $member['name'])) . '@gmail.com'],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $asetLogistikUnit->id,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user ASET & LOGISTIK berhasil dijalankan.');
        // Data untuk unit ASET & LOGISTIK
        $asetLogistikMembers = [
            ['name' => 'Ratih Titis P', 'role' => 'Kepala Unit'],
            ['name' => 'Nur Ardi Firdosta'],
            // ['name' => 'Fathul Bari'],
            // ['name' => 'Oryzae Sativa Linies'],
        ];

        // Cari unit ASET & LOGISTIK
        $asetLogistikUnit = UnitKerja::where('nama', 'ASET & LOGISTIK')->first();

        if (!$asetLogistikUnit) {
            $this->command->error('Unit ASET & LOGISTIK tidak ditemukan!');
            return;
        }

        // Data untuk unit GUDANG
        $Gudang = [
            ['name' => 'Ratih Titis Pamungkas', 'role' => 'Kepala Unit'],
            ['name' => 'Fathul Bari'],
            ['name' => 'Oryzae Sativa Linies'],
        ];

        // Cari unit GUDANG
        $asetLogistikUnit = UnitKerja::where('nama', 'GUDANG')->first();

        if (!$asetLogistikUnit) {
            $this->command->error('Unit GUDANG tidak ditemukan!');
            return;
        }

        foreach ($Gudang as $member) {
            $user = User::firstOrCreate(
                ['email' => strtolower(str_replace([' ', ',', '.', '/', '\'', '-'], '.', $member['name'])) . '@gmail.com'],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $asetLogistikUnit->id,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user GUDANG berhasil dijalankan.');

        // Data untuk unit PELAYANAN MEDIK
        $pelayananMedikMembers = [
            ['name' => 'Eko Setiono', 'role' => 'Kepala Unit'],
            ['name' => 'Lia Eris Fitriani'],
        ];

        // Cari unit PELAYANAN MEDIK
        $pelayananMedikUnit = UnitKerja::where('nama', 'PELAYANAN MEDIK')->first();

        if (!$pelayananMedikUnit) {
            $this->command->error('Unit PELAYANAN MEDIK tidak ditemukan!');
            return;
        }

        foreach ($pelayananMedikMembers as $member) {
            $user = User::firstOrCreate(
                ['email' => strtolower(str_replace([' ', ',', '.', '/', '\'', '-'], '.', $member['name'])) . '@gmail.com'],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $pelayananMedikUnit->id,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user PELAYANAN MEDIK berhasil dijalankan.');

        // Data untuk unit PENUNJANG
        $penunjangMembers = [
            ['name' => 'Mutia Kanza Salama', 'role' => 'Kepala Unit'],
            ['name' => 'Puspita Chandra Alviana'],
        ];

        // Cari unit PENUNJANG
        $penunjangUnit = UnitKerja::where('nama', 'PENUNJANG')->first();

        if (!$penunjangUnit) {
            $this->command->error('Unit PENUNJANG tidak ditemukan!');
            return;
        }

        foreach ($penunjangMembers as $member) {
            $user = User::firstOrCreate(
                ['email' => strtolower(str_replace([' ', ',', '.', '/', '\'', '-'], '.', $member['name'])) . '@gmail.com'],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $penunjangUnit->id,
                ]
            );

            $role = $member['role'] ?? 'Staf';
            $user->assignRole($role);
        }

        $this->command->info('Seeder user PENUNJANG berhasil dijalankan.');

        // Data untuk unit KEPERAWATAN
        $keperawatanMembers = [
            ['name' => 'Rifki Nafisani', 'role' => 'Kepala Unit'],
            ['name' => 'Muhadi'],
        ];

        // Cari unit KEPERAWATAN
        $keperawatanUnit = UnitKerja::where('nama', 'KEPERAWATAN')->first();

        if (!$keperawatanUnit) {
            $this->command->error('Unit KEPERAWATAN tidak ditemukan!');
            return;
        }

        foreach ($keperawatanMembers as $member) {
            $user = User::firstOrCreate(
                ['email' => strtolower(str_replace([' ', ',', '.', '/', '\'', '-'], '.', $member['name'])) . '@gmail.com'],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('123'), // Password default
                    'unit_id' => $keperawatanUnit->id,
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
                ['email' => strtolower(str_replace([' ', ',', '.', '/', '\'', '-'], '.', $member['name'])) . '@gmail.com'],
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
                ['email' => strtolower(str_replace([' ', ',', '.', '/', '\'', '-'], '.', $member['name'])) . '@gmail.com'],
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
            ['name' => 'Basuki Imam Sampurna', 'role' => 'Kepala Unit', 'jabatan' => 'Ka. Instalasi Teknologi Informasi'],
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
                ['email' => strtolower(str_replace([' ', ',', '.', '/', '\'', '-'], '.', $member['name'])) . '@gmail.com'],
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
                ['email' => strtolower(str_replace([' ', ',', '.', '/', '\'', '-'], '.', $member['name'])) . '@gmail.com'],
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
                ['email' => strtolower(str_replace([' ', ',', '.', '/', '\'', '-'], '.', $member['name'])) . '@gmail.com'],
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
                ['email' => strtolower(str_replace([' ', ',', '.', '/', '\'', '-'], '.', $member['name'])) . '@gmail.com'],
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
                ['email' => strtolower(str_replace([' ', ',', '.', '/', '\'', '-'], '.', $member['name'])) . '@gmail.com'],
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
                ['email' => strtolower(str_replace([' ', ',', '.', '/', '\'', '-'], '.', $member['name'])) . '@gmail.com'],
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
