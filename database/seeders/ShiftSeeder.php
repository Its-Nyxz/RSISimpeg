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
        ];

        // Unit 24 jam (dapat semua shift)
        $unitIdsWithFullShifts = [
            1,
            2,
            3,
            5,
            14,
            15,
            16,
            17,
            18,
            22,
            23,
            24,
            25,
            26,
            28,
            29,
            30,
            31,
            33,
            45,
            57
        ];

        // Semua unit kerja
        $allUnitIds = range(1, 60);

        // Sisanya hanya dapat shift pagi
        $unitIdsWithRegularPagi = array_diff($allUnitIds, $unitIdsWithFullShifts);

        // Insert untuk unit dengan full shift
        foreach ($unitIdsWithFullShifts as $unit_id) {
            foreach ($fullShifts as $shift) {
                Shift::create([
                    'unit_id' => $unit_id,
                    'nama_shift' => $shift['nama_shift'],
                    'jam_masuk' => Carbon::createFromFormat('H:i', $shift['jam_masuk'], 'Asia/Jakarta')->format('H:i'),
                    'jam_keluar' => Carbon::createFromFormat('H:i', $shift['jam_keluar'], 'Asia/Jakarta')->format('H:i'),
                    'keterangan' => $shift['keterangan'],
                ]);
            }
        }

        // Insert untuk unit reguler (hanya shift pagi)
        foreach ($unitIdsWithRegularPagi as $unit_id) {
            foreach ($regularPagiShift as $shift) {
                Shift::create([
                    'unit_id' => $unit_id,
                    'nama_shift' => $shift['nama_shift'],
                    'jam_masuk' => Carbon::createFromFormat('H:i', $shift['jam_masuk'], 'Asia/Jakarta')->format('H:i'),
                    'jam_keluar' => Carbon::createFromFormat('H:i', $shift['jam_keluar'], 'Asia/Jakarta')->format('H:i'),
                    'keterangan' => $shift['keterangan'],
                ]);
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
