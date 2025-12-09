<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use App\Livewire\AktivitasAbsensi;

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
        $component = new AktivitasAbsensi();
        $component->selectedUserId = $this->user->id;
        $component->month = $this->month;
        $component->year  = $this->year;
        $component->loadData();

        return view('exports.absensi', [
            'items' => $component->items,
            'user'  => $this->user,
            'title' => $this->month . ' ' . $this->year,
        ]);
    }

    public function title(): string
    {
        return $this->user->name ?: 'User';
    }
}
