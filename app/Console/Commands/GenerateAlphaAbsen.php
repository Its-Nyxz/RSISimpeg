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
            // Ambil semua jadwal untuk tanggal tersebut (termasuk shift malam dari hari sebelumnya)
            $jadwalsHariIni = JadwalAbsensi::where('user_id', $user->id)
                ->whereDate('tanggal_jadwal', $tanggal)
                ->with('shift')
                ->get();

            // Ambil juga jadwal shift malam dari hari sebelumnya yang berlanjut ke hari ini
            $jadwalsMalamKemarin = JadwalAbsensi::where('user_id', $user->id)
                ->whereDate('tanggal_jadwal', Carbon::parse($tanggal)->subDay()->toDateString())
                ->with('shift')
                ->get()
                ->filter(function ($jadwal) {
                    return $jadwal->shift && $jadwal->shift->nama_shift === 'L';
                });

            $allJadwals = $jadwalsHariIni->merge($jadwalsMalamKemarin);

            if ($allJadwals->isEmpty()) continue;

            $this->line("👤 {$user->name} ({$user->id})");

            // Array untuk melacak shift mana saja yang sudah diproses
            $shiftJadwalIds = [];
            $totalShiftHariIni = 0;
            $totalAbsenHariIni = 0;
            $ada_libur = false;

            foreach ($allJadwals as $jadwal) {
                $shift = $jadwal->shift;

                if (!$shift) {
                    $this->warn("  → Tidak ada shift untuk Jadwal ID {$jadwal->id}");
                    continue;
                }
                // Validasi: Jika shift null atau jam_keluar null, lewati
                if (!$shift->jam_keluar && !$shift->jam_masuk) {
                    continue;
                }

                // Parse waktu shift dengan benar
                $startShift = Carbon::parse($shift->jam_masuk, 'Asia/Jakarta');
                $endShift = Carbon::parse($shift->jam_keluar, 'Asia/Jakarta');
                $isMalam = $endShift->lessThan($startShift);

                if ($isMalam) {
                    $endShift->addDay();  // Shift melewati tengah malam
                }

                // Hitung waktu selesai shift di zona Jakarta
                $shiftSelesai = Carbon::parse($jadwal->tanggal_jadwal, 'Asia/Jakarta')
                    ->setTimeFromTimeString($shift->jam_keluar);
                
                if ($isMalam) {
                    $shiftSelesai->addDay();
                }

                // Cek apakah shift sudah berakhir
                if (now('Asia/Jakarta')->lessThan($shiftSelesai)) {
                    $this->line("  → Shift {$shift->nama_shift} belum berakhir ({$shiftSelesai})");
                    continue;
                }

                $shiftJadwalIds[] = $jadwal->id;
                $totalShiftHariIni++;

                // Cek apakah sudah ada absen untuk jadwal ini
                $sudahAbsen = Absen::where('jadwal_id', $jadwal->id)
                    ->where('user_id', $user->id)
                    ->exists();

                if ($sudahAbsen) {
                    $this->line("  ✓ Shift {$shift->nama_shift} sudah ada absen.");
                    $totalAbsenHariIni++;
                    continue;
                }

                // Tandai jika ada shift libur
                if ($shift->nama_shift === 'L') {
                    $ada_libur = true;
                }
            }

            // LOGIKA ALPHA: Hanya buat alpha jika SEMUA shift seharian tidak ada absen
            if ($totalShiftHariIni > 0 && $totalAbsenHariIni === 0) {
                // Hanya proses shift non-libur untuk alpha
                $shiftNonLibur = [];
                $shiftLibur = [];

                foreach ($allJadwals as $jadwal) {
                    $shift = $jadwal->shift;
                    if (!$shift || !in_array($jadwal->id, $shiftJadwalIds)) continue;

                    $sudahAbsen = Absen::where('jadwal_id', $jadwal->id)
                        ->where('user_id', $user->id)
                        ->exists();

                    if (!$sudahAbsen) {
                        if ($shift->nama_shift === 'L') {
                            $shiftLibur[] = $jadwal;
                        } else {
                            $shiftNonLibur[] = $jadwal;
                        }
                    }
                }

                // Buat alpha untuk semua shift yang tidak ada absen
                foreach ($shiftNonLibur as $jadwal) {
                    $shift = $jadwal->shift;

                    Absen::create([
                        'jadwal_id' => $jadwal->id,
                        'user_id' => $user->id,
                        'time_in' => null,
                        'time_out' => null,
                        'absent' => 1,
                        'present' => 0,
                        'deskripsi_out' => 'Alpha',
                        'status_absen_id' => $statusAlpha->id,
                        'keterangan' => $statusAlpha->keterangan ?? 'Tidak melakukan absensi sama sekali',
                    ]);
                    $this->info("  ❌ Absen ALPHA dibuat untuk shift {$shift->nama_shift}.");
                    $totalAlpha++;
                }

                // Buat libur untuk semua shift libur yang tidak ada absen
                foreach ($shiftLibur as $jadwal) {
                    $shift = $jadwal->shift;

                    Absen::create([
                        'jadwal_id' => $jadwal->id,
                        'user_id' => $user->id,
                        'time_in' => null,
                        'time_out' => null,
                        'absent' => 0,
                        'present' => 1,
                        'deskripsi_out' => 'Libur',
                        'status_absen_id' => $statusLibur->id,
                        'keterangan' => $statusLibur->keterangan ?? 'Hari Libur',
                    ]);
                    $this->info("  ✅ Absen LIBUR dibuat.");
                    $totalLibur++;
                }
            }
        }

        $this->info("\n[Selesai] Total Alpha: $totalAlpha | Total Libur: $totalLibur\n");
    }
}
