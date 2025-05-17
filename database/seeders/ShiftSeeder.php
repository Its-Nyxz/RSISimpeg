<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Shift;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Semua shift (untuk unit kerja 24 jam)
        $fullShifts = [
            // Pagi
            ['nama_shift' => 'P', 'jam_masuk' => '05:30', 'jam_keluar' => '12:30', 'keterangan' => 'Pagi'],
            ['nama_shift' => 'P', 'jam_masuk' => '05:30', 'jam_keluar' => '13:00', 'keterangan' => 'Pagi'],
            ['nama_shift' => 'P', 'jam_masuk' => '06:00', 'jam_keluar' => '12:00', 'keterangan' => 'Pagi'],
            ['nama_shift' => 'P', 'jam_masuk' => '06:00', 'jam_keluar' => '13:00', 'keterangan' => 'Pagi'],
            ['nama_shift' => 'P', 'jam_masuk' => '07:00', 'jam_keluar' => '12:30', 'keterangan' => 'Pagi'],
            ['nama_shift' => 'P', 'jam_masuk' => '07:30', 'jam_keluar' => '13:00', 'keterangan' => 'Pagi'],
            ['nama_shift' => 'P', 'jam_masuk' => '07:30', 'jam_keluar' => '13:30', 'keterangan' => 'Pagi'],
            ['nama_shift' => 'P', 'jam_masuk' => '07:30', 'jam_keluar' => '14:00', 'keterangan' => 'Pagi'],
            ['nama_shift' => 'P', 'jam_masuk' => '07:30', 'jam_keluar' => '14:30', 'keterangan' => 'Pagi'],

            // Tanggung
            ['nama_shift' => 'T', 'jam_masuk' => '08:30', 'jam_keluar' => '15:30', 'keterangan' => 'Tanggung'],
            ['nama_shift' => 'T', 'jam_masuk' => '09:00', 'jam_keluar' => '16:00', 'keterangan' => 'Tanggung'],
            ['nama_shift' => 'T', 'jam_masuk' => '09:30', 'jam_keluar' => '16:30', 'keterangan' => 'Tanggung'],
            ['nama_shift' => 'T', 'jam_masuk' => '09:00', 'jam_keluar' => '15:30', 'keterangan' => 'Tanggung'],
            ['nama_shift' => 'T', 'jam_masuk' => '09:30', 'jam_keluar' => '15:30', 'keterangan' => 'Tanggung'],
            ['nama_shift' => 'T', 'jam_masuk' => '09:30', 'jam_keluar' => '16:00', 'keterangan' => 'Tanggung'],
            ['nama_shift' => 'T', 'jam_masuk' => '09:30', 'jam_keluar' => '16:30', 'keterangan' => 'Tanggung'],
            ['nama_shift' => 'T', 'jam_masuk' => '10:00', 'jam_keluar' => '15:30', 'keterangan' => 'Tanggung'],
            ['nama_shift' => 'T', 'jam_masuk' => '10:00', 'jam_keluar' => '16:00', 'keterangan' => 'Tanggung'],
            ['nama_shift' => 'T', 'jam_masuk' => '10:00', 'jam_keluar' => '17:00', 'keterangan' => 'Tanggung'],
            ['nama_shift' => 'T', 'jam_masuk' => '10:00', 'jam_keluar' => '18:00', 'keterangan' => 'Tanggung'],
            ['nama_shift' => 'T', 'jam_masuk' => '10:30', 'jam_keluar' => '16:30', 'keterangan' => 'Tanggung'],
            ['nama_shift' => 'T', 'jam_masuk' => '11:00', 'jam_keluar' => '17:30', 'keterangan' => 'Tanggung'],
            ['nama_shift' => 'T', 'jam_masuk' => '11:00', 'jam_keluar' => '18:00', 'keterangan' => 'Tanggung'],
            ['nama_shift' => 'T', 'jam_masuk' => '11:30', 'jam_keluar' => '18:30', 'keterangan' => 'Tanggung'],

            // Sore
            ['nama_shift' => 'S', 'jam_masuk' => '12:00', 'jam_keluar' => '19:00', 'keterangan' => 'Sore'],
            ['nama_shift' => 'S', 'jam_masuk' => '12:30', 'jam_keluar' => '19:30', 'keterangan' => 'Sore'],
            ['nama_shift' => 'S', 'jam_masuk' => '13:00', 'jam_keluar' => '18:00', 'keterangan' => 'Sore'],
            ['nama_shift' => 'S', 'jam_masuk' => '13:00', 'jam_keluar' => '19:00', 'keterangan' => 'Sore'],
            ['nama_shift' => 'S', 'jam_masuk' => '13:00', 'jam_keluar' => '19:30', 'keterangan' => 'Sore'],
            ['nama_shift' => 'S', 'jam_masuk' => '13:00', 'jam_keluar' => '20:00', 'keterangan' => 'Sore'],
            ['nama_shift' => 'S', 'jam_masuk' => '13:30', 'jam_keluar' => '19:30', 'keterangan' => 'Sore'],
            ['nama_shift' => 'S', 'jam_masuk' => '13:30', 'jam_keluar' => '20:00', 'keterangan' => 'Sore'],
            ['nama_shift' => 'S', 'jam_masuk' => '13:30', 'jam_keluar' => '20:30', 'keterangan' => 'Sore'],
            ['nama_shift' => 'S', 'jam_masuk' => '14:00', 'jam_keluar' => '21:00', 'keterangan' => 'Sore'],

            // Malam
            ['nama_shift' => 'M', 'jam_masuk' => '19:30', 'jam_keluar' => '07:30', 'keterangan' => 'Malam'],
            ['nama_shift' => 'M', 'jam_masuk' => '21:00', 'jam_keluar' => '07:30', 'keterangan' => 'Malam'],
        ];

        // Shift pagi khusus untuk unit non-24-jam
        $regularPagiShift = [
            ['nama_shift' => 'P', 'jam_masuk' => '05:30', 'jam_keluar' => '12:30', 'keterangan' => 'Pagi'],
            ['nama_shift' => 'P', 'jam_masuk' => '05:30', 'jam_keluar' => '13:00', 'keterangan' => 'Pagi'],
            ['nama_shift' => 'P', 'jam_masuk' => '06:00', 'jam_keluar' => '12:00', 'keterangan' => 'Pagi'],
            ['nama_shift' => 'P', 'jam_masuk' => '06:00', 'jam_keluar' => '13:00', 'keterangan' => 'Pagi'],
            ['nama_shift' => 'P', 'jam_masuk' => '07:00', 'jam_keluar' => '12:30', 'keterangan' => 'Pagi'],
            ['nama_shift' => 'P', 'jam_masuk' => '07:30', 'jam_keluar' => '13:00', 'keterangan' => 'Pagi'],
            ['nama_shift' => 'P', 'jam_masuk' => '07:30', 'jam_keluar' => '13:30', 'keterangan' => 'Pagi'],
            ['nama_shift' => 'P', 'jam_masuk' => '07:30', 'jam_keluar' => '14:00', 'keterangan' => 'Pagi'],
            ['nama_shift' => 'P', 'jam_masuk' => '07:30', 'jam_keluar' => '14:30', 'keterangan' => 'Pagi'],
        ];

        // Unit 24 jam (dapat semua shift)
        $unitIdsWithFullShifts = [
            1,  // IMP
            2,  // PERINATOLOGI (sub)
            3,  // VK (sub)
            5,  // INST RANAP
            6,  // AT TAQWA
            7,  // ASSALAM
            8,  // AL AMIN
            9,  // FIRDAUS
            10, // HAJI
            11, // ASSYFA
            12, // AZIZIAH
            13, // ALMUNAWAROH
            14, // IBS
            15, // IGD
            16, // ICU
            17, // INST DIALISIS
            18, // IRJ
            22, // INST RADIOLOGI
            23, // INST LABORATORIUM
            24, // INST SANITASI
            25, // INST CSSD
            26, // INST PEML SARPRAS
            28, // TRANSPORTASI
            29, // INST GIZI
            30, // PJBR
            31, // PENGELOLAAN LINEN
            33, // KEPERAWATAN
            45, // SUPERVISOR
            57  // GUDANG
        ];

        // Semua unit kerja
        $allUnitIds = range(1, 60);

        // Sisanya hanya dapat shift pagi
        $unitIdsWithRegularPagi = array_diff($allUnitIds, $unitIdsWithFullShifts);

        // Insert untuk unit dengan full shift
        // Untuk unit dengan full shift
        foreach ($unitIdsWithFullShifts as $unit_id) {
            foreach ($fullShifts as $shift) {
                Shift::updateOrCreate(
                    [
                        'unit_id' => $unit_id,
                        'nama_shift' => $shift['nama_shift'],
                        'jam_masuk' => Carbon::createFromFormat('H:i', $shift['jam_masuk'], 'Asia/Jakarta')->format('H:i'),
                        'jam_keluar' => Carbon::createFromFormat('H:i', $shift['jam_keluar'], 'Asia/Jakarta')->format('H:i'),
                    ],
                    [
                        'keterangan' => $shift['keterangan'],
                    ]
                );
            }
        }

        // Untuk unit dengan shift pagi reguler
        foreach ($unitIdsWithRegularPagi as $unit_id) {
            foreach ($regularPagiShift as $shift) {
                Shift::updateOrCreate(
                    [
                        'unit_id' => $unit_id,
                        'nama_shift' => $shift['nama_shift'],
                        'jam_masuk' => Carbon::createFromFormat('H:i', $shift['jam_masuk'], 'Asia/Jakarta')->format('H:i'),
                        'jam_keluar' => Carbon::createFromFormat('H:i', $shift['jam_keluar'], 'Asia/Jakarta')->format('H:i'),
                    ],
                    [
                        'keterangan' => $shift['keterangan'],
                    ]
                );
            }
        }


        // $unitIds = range(1, 59);

        // foreach ($unitIds as $unit_id) {
        //     foreach ($shifts as $shift) {
        //         Shift::create([
        //             'unit_id' => $unit_id,
        //             'nama_shift' => $shift['nama_shift'],
        //             'jam_masuk' => Carbon::createFromFormat('H:i', $shift['jam_masuk'], 'Asia/Jakarta')->format('H:i'),
        //             'jam_keluar' => Carbon::createFromFormat('H:i', $shift['jam_keluar'], 'Asia/Jakarta')->format('H:i'),
        //             'keterangan' => $shift['keterangan'],
        //         ]);
        //     }
        // }
    }
}
