<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Absen;
use App\Models\StatusAbsen;
use App\Models\JadwalAbsensi;
use Illuminate\Console\Command;

class GenerateAlphaAbsen extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:absen-alpha {--date=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tanggal = $this->option('date') ?? now()->subDay()->toDateString();
        $this->info("\n[Mulai] Cek absen tanggal: $tanggal\n");

        $statusAlpha = StatusAbsen::where('nama', 'Tidak Absen')->first();
        $statusLibur = StatusAbsen::where('nama', 'Libur')->first();

        if (!$statusAlpha || !$statusLibur) {
            $this->error("❌ Status absen 'Tidak Absen' atau 'Libur' tidak ditemukan. Periksa tabel status_absen.");
            return;
        }

        $users = User::with('unitKerja')->get();
        $totalAlpha = 0;
        $totalLibur = 0;

        foreach ($users as $user) {
            $jadwals = JadwalAbsensi::where('user_id', $user->id)
                ->whereDate('tanggal_jadwal', $tanggal)
                ->with('shift')
                ->get();

            if ($jadwals->isEmpty()) continue;

            $this->line("👤 {$user->name} ({$user->id})");

            foreach ($jadwals as $jadwal) {
                $shift = $jadwal->shift;

                if (!$shift) {
                    $this->warn("  → Tidak ada shift untuk Jadwal ID {$jadwal->id}");
                    continue;
                }

                $startShift = Carbon::parse($shift->jam_masuk, 'Asia/Jakarta');
                $endShift = Carbon::parse($shift->jam_keluar, 'Asia/Jakarta');

                if ($endShift->lessThan($startShift)) {
                    $endShift->addDay();  // ✅ Shift melewati tengah malam
                }
                $shiftDuration = $startShift->diffInSeconds($endShift);

                $shiftSelesai = Carbon::parse($jadwal->tanggal_jadwal)
                    ->addDays($isMalam ? 1 : 0)
                    ->setTimeFrom($jamKeluar);
                // ->addMinutes(30); // beri toleransi

                if (now()->lessThan($shiftSelesai)) {
                    $this->line("  → Shift {$shift->nama_shift} belum berakhir ({$shiftSelesai})");
                    continue;
                }

                $sudahAbsen = Absen::where('jadwal_id', $jadwal->id)
                    ->where('user_id', $user->id)
                    ->exists();

                if ($sudahAbsen) {
                    $this->line("  → Sudah absen sebelumnya.");
                    continue;
                }

                $dataAbsen = [
                    'jadwal_id' => $jadwal->id,
                    'user_id' => $user->id,
                    'time_in' => Carbon::createFromTimestamp($this->timeIn, 'Asia/Jakarta')->timestamp,
                    'time_out' => Carbon::createFromTimestamp($this->timeOut, 'Asia/Jakarta')->timestamp,
                ];

                if ($shift->nama_shift === 'L') {
                    Absen::create(array_merge($dataAbsen, [
                        'absent' => 0,
                        'present' => 1,
                        'deskripsi_out' => 'Libur',
                        'status_absen_id' => $statusLibur->id,
                        'keterangan' => $statusLibur->keterangan ?? 'Hari Libur',
                    ]));
                    $this->info("  ✅ Absen LIBUR dibuat.");
                    $totalLibur++;
                } else {
                    Absen::create(array_merge($dataAbsen, [
                        'absent' => 1,
                        'present' => 0,
                        'deskripsi_out' => 'Alpha',
                        'status_absen_id' => $statusAlpha->id,
                        'keterangan' => $statusAlpha->keterangan ?? 'Tidak melakukan absensi sama sekali',
                    ]));
                    $this->info("  ❌ Absen ALPHA dibuat.");
                    $totalAlpha++;
                }
            }
        }

        $this->info("\n[Selesai] Total Alpha: $totalAlpha | Total Libur: $totalLibur\n");
    }
}
