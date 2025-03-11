<?php

namespace App\Livewire;

use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Absen;
use App\Models\JadwalAbsensi;
use App\Models\StatusAbsen;

class Timer extends Component
{
    public $time = 0;
    public $isRunning = false;
    public $isPaused = false;
    public $startTime;
    public $showWorkPlanModal = false;
    public $showWorkReportModal = false;
    public $workPlan = '';
    public $workReport = '';
    public $items = [];

    public function mount()
    {
        if (Session::has('timer_data')) {
            $sessionData = Session::get('timer_data');
    
            $this->startTime = Carbon::parse($sessionData['startTime']);
            $this->isRunning = $sessionData['isRunning'];
            $this->isPaused = $sessionData['isPaused'];
            $this->time = $sessionData['time'] ?? 0;
    
            // Hanya load data jika masih berjalan
            if ($this->isRunning) {
                $this->loadData();
            } else {
                $this->items = []; // Reset jika timer sudah selesai
            }
        } else {
            $this->items = []; // Reset jika tidak ada session
        }
    }
    

    public function loadData()
    {
        $user = Auth::user();

        $this->items = Absen::where('jadwal_id', function ($query) use ($user) {
                $query->select('id')
                    ->from('jadwal_absensis')
                    ->where('user_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($absen) {
                return [
                    'rencana_kerja' => $absen->keterangan_mulai ?? '-',
                ];
            })
            ->toArray(); // Pastikan hasilnya berupa array
    }

    public function openWorkPlanModal()
    {
        $this->showWorkPlanModal = true;
    }

    public function startTimerWithPlan()
    {
        if ($this->isRunning) return;

        $this->isRunning = true;
        $this->isPaused = false;
        $this->startTime = Carbon::now('Asia/Jakarta');

        // Tambahkan inputan terbaru ke daftar rencana kerja langsung
        $this->items = array_merge($this->items, [['rencana_kerja' => $this->workPlan]]);

        // Simpan di session
        Session::put('timer_data', [
            'startTime' => $this->startTime->toDateTimeString(),
            'isRunning' => true,
            'isPaused' => false,
            'time' => 0,
            'workPlan' => $this->workPlan
        ]);

        $this->showWorkPlanModal = false;
        $this->workPlan = '';
    }

    public function pauseTimer()
    {
        if (!$this->isRunning || $this->isPaused) return;
        $this->isPaused = true;
        Session::put('timer_data.isPaused', true);
    }

    public function resumeTimer()
    {
        if (!$this->isPaused) return;
        $this->isPaused = false;
        Session::put('timer_data.isPaused', false);
    }

    public function openWorkReportModal()
    {
        $this->showWorkReportModal = true;
    }

    public function submitWorkReport()
    {
        if (!$this->isRunning) return;
    
        $user = Auth::user();
        $jadwal = JadwalAbsensi::where('user_id', $user->id)->first();
        $statusAbsen = StatusAbsen::first();
        $workPlan = Session::get('timer_data')['workPlan'] ?? '';
        $workReport = $this->workReport;
    
        if ($jadwal && $statusAbsen) {
            Absen::create([
                'jadwal_id' => $jadwal->id,
                'status_absen_id' => $statusAbsen->id,
                'time_in' => Carbon::parse(Session::get('timer_data')['startTime'])->setTimezone('Asia/Jakarta'),
                'time_out' => Carbon::now('Asia/Jakarta'),
                'total_seconds' => $this->time,
                'keterangan_mulai' => $workPlan,
                'keterangan_selesai' => $workReport,
            ]);
        }
    
        // Reset state
        $this->isRunning = false;
        $this->isPaused = false;
        $this->time = 0;
        $this->workReport = '';
    
        // Hapus session sebelum reload halaman
        Session::forget('timer_data');  
        $this->items = []; // Pastikan reset daftar rencana kerja
    
        $this->showWorkReportModal = false;
    }
    


    public function updateTimer()
    {
        if ($this->isRunning && !$this->isPaused) {
            $startTime = Session::get('timer_data')['startTime'] ?? 0;

            if (!is_numeric($startTime)) {
                $startTime = Carbon::parse($startTime)->setTimezone('Asia/Jakarta')->timestamp;
            }

            $this->time = now('Asia/Jakarta')->timestamp - $startTime;
            Session::put('timer_data.time', $this->time);
        }
    }

    public function render()
    {
        return view('livewire.timer');
    }
}