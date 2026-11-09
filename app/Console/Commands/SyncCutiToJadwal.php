<?php

namespace App\Console\Commands;

use App\Models\Absen;
use App\Models\CutiKaryawan;
use App\Models\JadwalAbsensi;
use App\Models\Shift;
use App\Models\StatusAbsen;
use App\Models\UnitKerja;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SyncCutiToJadwal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cuti:sync-jadwal {--unit= : Nama unit kerja atau kode (contoh: IRJ)} {--all : Sinkronkan status 1 dan status 4}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sinkronisasi pengajuan cuti yang disetujui ke jadwal absensi (shift C)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $unitFilter = $this->option('unit');
        $includeStatus4 = $this->option('all');

        $statusIds = $includeStatus4 ? [1, 4] : [1];

        $query = CutiKaryawan::with(['user.unitKerja', 'jenisCuti', 'statusCuti'])
            ->whereIn('status_cuti_id', $statusIds);

        if ($unitFilter) {
            $query->whereHas('user.unitKerja', function ($q) use ($unitFilter) {
                $q->where('nama', 'like', "%{$unitFilter}%")
                  ->orWhere('kode', 'like', "%{$unitFilter}%")
                  ->orWhere('keterangan', 'like', "%{$unitFilter}%");
            });
        }

        $cutis = $query->get();

        $this->info("Ditemukan {$cutis->count()} data cuti untuk disinkronkan ke jadwal.");

        $cutiStatusId = StatusAbsen::where('nama', 'Tepat Waktu')->value('id');
        $totalJadwalCreated = 0;

        foreach ($cutis as $cuti) {
            $user = $cuti->user;
            if (!$user) {
                continue;
            }

            // Pastikan shift C untuk unit ini ada
            $shift = Shift::firstOrCreate(
                [
                    'unit_id' => $user->unit_id,
                    'nama_shift' => 'C',
                ],
                [
                    'jam_masuk' => null,
                    'jam_keluar' => null,
                    'keterangan' => 'Cuti',
                ]
            );

            $start = Carbon::parse($cuti->tanggal_mulai);
            $end = Carbon::parse($cuti->tanggal_selesai);

            $this->line("Memproses cuti: {$user->name} ({$cuti->tanggal_mulai} s/d {$cuti->tanggal_selesai})...");

            for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                $tglStr = $date->toDateString();

                $jadwal = JadwalAbsensi::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'tanggal_jadwal' => $tglStr,
                    ],
                    [
                        'shift_id' => $shift->id,
                    ]
                );

                $namaJenisCuti = $cuti->jenisCuti->nama_cuti ?? 'Cuti';

                Absen::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'jadwal_id' => $jadwal->id,
                    ],
                    [
                        'status_absen_id' => $cutiStatusId,
                        'present' => 1,
                        'absent' => 0,
                        'late' => 0,
                        'time_in' => null,
                        'time_out' => null,
                        'keterangan' => 'Cuti disetujui',
                        'deskripsi_in' => $namaJenisCuti,
                        'deskripsi_out' => $namaJenisCuti,
                        'is_dinas' => false,
                        'is_lembur' => false,
                        'approved_lembur' => false,
                    ]
                );

                $totalJadwalCreated++;
            }
        }

        $this->info("Sinkronisasi selesai! Total {$totalJadwalCreated} jadwal absensi shift C diperbarui.");

        return Command::SUCCESS;
    }
}

