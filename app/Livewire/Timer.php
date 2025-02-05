<?php

namespace App\Livewire;

use Livewire\Component;

class Timer extends Component
{
    public $time = 0;
    public $isRunning = false;
    public $isPaused = false;

    public function startTimer()
    {
        $this->isRunning = true;
        $this->isPaused = false;
    }

    public function pauseTimer()
    {
        $this->isPaused = true;
    }

    public function resumeTimer()
    {
        $this->isPaused = false;
        $this->isRunning = true;
        // \Log::info('Timer resumed');
    }

    public function stopTimer()
    {
        $this->isRunning = false;
        $this->isPaused = false;
        $this->time = 0;
    }

    public function updateTimer()
    {
        if ($this->isRunning && !$this->isPaused) {
            $this->time++;
        }
    }

    public function render()
    {
        return view('livewire.timer');
    }
}