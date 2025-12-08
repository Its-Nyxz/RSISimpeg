<?php

namespace App\Exports;

use Livewire\Livewire;
use App\Livewire\AktivitasAbsensi;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use App\Models\UnitKerja;


class AbsensiUserSheet implements FromArray, WithTitle
{
    protected $user;
    protected $month;
    protected $year;

    public function __construct($user, $month, $year)
    {
        $this->user = $user;
        $this->month = $month;
        $this->year = $year;
    }

    public function array(): array
    {
        $component = new AktivitasAbsensi();
        $component->selectedUserId = $this->user->id;
        $component->month = $this->month;
        $component->year = $this->year;
        $component->loadData();

        return $component->items;
    }

    public function title(): string
    {
        return $this->user->name;
    }
}