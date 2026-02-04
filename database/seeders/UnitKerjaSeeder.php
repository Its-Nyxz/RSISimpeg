<?php

namespace Database\Seeders;

use App\Models\UnitKerja;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UnitKerjaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Unit dengan sub-unit
        $unitsWithSubUnits = [
            [
                'name' => 'IMP',
                'kode' => 'IMP',
                'keterangan' => 'Instalasi Maternal Perinatal',
                'sub_units' => [
                    ['name' => 'PERINATOLOGI', 'kode' => 'PER', 'keterangan' => 'Perinatologi'],
                    ['name' => 'VK', 'kode' => 'VK', 'keterangan' => 'Ruang VK'],
                    ['name' => 'ALZAITUN', 'kode' => 'AZ', 'keterangan' => 'Ruang ALZAITUN'],
                ],
            ],
            [
                'name' => 'INST RANAP',
                'kode' => 'RANAP',
                'keterangan' => 'Instalasi Rawat Inap',
                'sub_units' => [
                    ['name' => 'AT TAQWA', 'kode' => 'TQ', 'keterangan' => 'Ruang AT TAQWA'],
                    ['name' => 'ASSALAM', 'kode' => 'AS', 'keterangan' => 'Ruang ASSALAM'],
                    ['name' => 'AL AMIN', 'kode' => 'AA', 'keterangan' => 'Ruang AL AMIN'],
                    ['name' => 'FIRDAUS', 'kode' => 'FR', 'keterangan' => 'Ruang FIRDAUS'],
                    ['name' => 'HAJI', 'kode' => 'HJ', 'keterangan' => 'Ruang HAJI'],
                    ['name' => 'ASSYFA', 'kode' => 'SY', 'keterangan' => 'Ruang ASSYFA'],
                    ['name' => 'AZIZIAH', 'kode' => 'AZZ', 'keterangan' => 'Ruang AZIZIAH'],
                    ['name' => 'ALMUNAWAROH', 'kode' => 'ALM', 'keterangan' => 'Ruang ALMUNAWAROH'],
                ],
            ],
        ];

        // Unit tanpa sub-unit
        $unitsWithoutSubUnits = [
            ['name' => 'IBS', 'kode' => 'IBS', 'keterangan' => 'Instalasi Bedah Sentral'],
            ['name' => 'IGD', 'kode' => 'IGD', 'keterangan' => 'Instalasi Gawat Darurat'],
            ['name' => 'ICU', 'kode' => 'ICU', 'keterangan' => 'Intensive Care Unit'],
            ['name' => 'INST DIALISIS', 'kode' => 'DIAL', 'keterangan' => 'Instalasi Dialisis'],
            ['name' => 'IRJ', 'kode' => 'IRJ', 'keterangan' => 'Instalasi Rawat Jalan'],
            ['name' => 'INST REHAB MEDIK', 'kode' => 'REHAB', 'keterangan' => 'Instalasi Rehabilitasi Medik'],
            ['name' => 'CASE MANAGER', 'kode' => 'CM', 'keterangan' => 'Case Manager'],
            ['name' => 'INST REKAM MEDIK', 'kode' => 'REKMED', 'keterangan' => 'Instalasi Rekam Medis'],
            ['name' => 'INST FARMASI', 'kode' => 'FARM', 'keterangan' => 'Instalasi Farmasi'],
            ['name' => 'INST RADIOLOGI', 'kode' => 'RAD', 'keterangan' => 'Instalasi Radiologi'],
            ['name' => 'INST LABORATORIUM', 'kode' => 'LAB', 'keterangan' => 'Instalasi Laboratorium'],
            ['name' => 'INST SANITASI', 'kode' => 'SANIT', 'keterangan' => 'Instalasi Sanitasi'],
            ['name' => 'INST CSSD', 'kode' => 'CSSD', 'keterangan' => 'Instalasi CSSD'],
            ['name' => 'INST PEML SARPRAS', 'kode' => 'SARPRAS', 'keterangan' => 'Pemeliharaan Sarpras'],
            ['name' => 'INST GAS MEDIK & ALKES', 'kode' => 'GASMED', 'keterangan' => 'Gas Medik dan Alkes'],
            ['name' => 'MCU & POSKES', 'kode' => 'MCU', 'keterangan' => 'MCU dan Poskes'],
            ['name' => 'TRANSPORTASI', 'kode' => 'TRANSP', 'keterangan' => 'Unit Ambulance dan Transportasi'],
            ['name' => 'INST GIZI', 'kode' => 'GIZI', 'keterangan' => 'Instalasi Gizi'],
            ['name' => 'PJBR', 'kode' => 'PJBR', 'keterangan' => 'Unit Pemulasaran Jenazah dan Bina Rohani'],
            ['name' => 'PENGELOLAAN LINEN', 'kode' => 'LINEN', 'keterangan' => 'Pengelolaan Linen'],
            ['name' => 'HUMAS & PROG RS', 'kode' => 'HUMAS', 'keterangan' => 'Humas dan Program RS'],
            ['name' => 'SDM', 'kode' => 'SDM', 'keterangan' => 'Sumber Daya Manusia'],
            ['name' => 'KEPEGAWAIAN', 'kode' => 'PGW', 'keterangan' => 'Kepegawaian'],
            ['name' => 'AKUNTANSI', 'kode' => 'AKUN', 'keterangan' => 'Akuntansi'],
            ['name' => 'KEUANGAN', 'kode' => 'KEU', 'keterangan' => 'Keuangan'],
            ['name' => 'KASIR', 'kode' => 'KASIR', 'keterangan' => 'Kasir'],
            ['name' => 'ASURANSI', 'kode' => 'ASUR', 'keterangan' => 'Asuransi'],
            ['name' => 'ASET & LOGISTIK', 'kode' => 'ASET', 'keterangan' => 'Aset dan Logistik'],
            ['name' => 'PELAYANAN MEDIK', 'kode' => 'MEDIK', 'keterangan' => 'Pelayanan Medik'],
            ['name' => 'KEPERAWATAN', 'kode' => 'KEP', 'keterangan' => 'Keperawatan'],
            ['name' => 'PENUNJANG', 'kode' => 'PENUNJ', 'keterangan' => 'Penunjang'],
            ['name' => 'PENGAMANAN', 'kode' => 'PENGAM', 'keterangan' => 'Pengamanan dan Peduli Lingkungan'],
            ['name' => 'PEMASARAN', 'kode' => 'PEMASAR', 'keterangan' => 'Unit Pemasaran'],
            ['name' => 'ITI', 'kode' => 'ITI', 'keterangan' => 'Instalasi Teknologi Informasi'],
            ['name' => 'IPCN', 'kode' => 'IPCN', 'keterangan' => 'Infection Prevention Control Nurse'],
            ['name' => 'KOMITE KEPERAWATAN', 'kode' => 'KP', 'keterangan' => 'Komite Keperawatan'],
            ['name' => 'KOMITE MUTU', 'kode' => 'KM', 'keterangan' => 'Komite Mutu'],
            ['name' => 'SUPERVISOR', 'kode' => 'SPV', 'keterangan' => 'Supervisor'],
            ['name' => 'DOKTER SPESIALIS PART TIME', 'kode' => 'DSP', 'keterangan' => 'Dokter Spesialis Part Time'],
            ['name' => 'PPA LAIN PART TIME', 'kode' => 'PPA', 'keterangan' => 'PPA LAIN Part Time'],
            ['name' => 'DOKTER UMUM FULL TIME', 'kode' => 'DUF', 'keterangan' => 'Dokter Umum Full Time'],
            ['name' => 'IAPI', 'kode' => 'IAPI', 'keterangan' => 'Ikatan Ahli Pengadaan Indonesia'],
            ['name' => 'SPI', 'kode' => 'SPI', 'keterangan' => ' Satuan Pengawas Internal '],
            ['name' => 'GUDANG', 'kode' => 'GUDANG', 'keterangan' => ' Unit Gudang '],
            ['name' => 'DEWAN PENGAWAS', 'kode' => 'DEWAS', 'keterangan' => ' Unit Dewan Pengawas '],
            ['name' => 'DIREKTUR', 'kode' => 'DIREKTUR', 'keterangan' => ' Direktur '],
            ['name' => 'KOMITE K3RS', 'kode' => 'K3RS', 'keterangan' => ' Komite K3RS '],
            ['name' => 'ADM KEPEGAWAIAN', 'kode' => 'AK', 'keterangan' => ' Adm kepeg '],
        ];

        // Menambahkan unit dengan sub-unit
        foreach ($unitsWithSubUnits as $unitData) {
            // Tambahkan unit utama
            $unit = UnitKerja::create([
                'nama' => $unitData['name'],
                'kode' => $unitData['kode'],
                'keterangan' => $unitData['keterangan'],
                'parent_id' => null, // Unit utama tidak memiliki parent
            ]);

            // Tambahkan sub-unit
            if (!empty($unitData['sub_units'])) {
                foreach ($unitData['sub_units'] as $subUnitData) {
                    UnitKerja::create([
                        'nama' => $subUnitData['name'],
                        'kode' => $subUnitData['kode'],
                        'keterangan' => $subUnitData['keterangan'],
                        'parent_id' => $unit->id, // Sub-unit terhubung dengan unit utama
                    ]);
                }
            }
        }

        // Menambahkan unit tanpa sub-unit
        foreach ($unitsWithoutSubUnits as $unitData) {
            UnitKerja::create([
                'nama' => $unitData['name'],
                'kode' => $unitData['kode'],
                'keterangan' => $unitData['keterangan'],
                'parent_id' => null, // Tidak memiliki parent
            ]);
        }
    }
}
