<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use App\Livewire\AktivitasAbsensi;
use Carbon\Carbon;

class AbsensiUserSheet implements FromView, WithTitle
{
    protected $user;
    protected $month;
    protected $year;

    public function __construct($user, $month, $year)
    {
        $this->user  = $user;
        $this->month = $month;
        $this->year  = $year;
    }

    public function view(): View
    {
        // Set locale Indonesia agar nama bulan dalam bahasa Indonesia
        Carbon::setLocale('id');

        // Buat format bulan → nama bulan (contoh: "Desember 2025")
        $title = Carbon::createFromDate($this->year, $this->month, 1)
                       ->translatedFormat('F Y');

        $component = new AktivitasAbsensi();
        $component->selectedUserId = $this->user->id;
        $component->month = $this->month;
        $component->year  = $this->year;
        $component->loadData();

        return view('exports.absensi', [
            'items' => $component->items,
            'user'  => $this->user,
            'title' => $title,
        ]);
    }

    public function title(): string
    {
        return $this->user->name ?: 'User';
    }
}
