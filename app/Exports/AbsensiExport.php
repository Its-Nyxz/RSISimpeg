<?php

namespace App\Exports;

use App\Models\Absen;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;

class AbsensiExport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public $items;
    public $user;
    public $title;

    public function __construct($items, $user, $title)
    {
        $this->items = $items;
        $this->user = $user;
        $this->title = $title;
    }

    public function view(): View
    {
        return view('exports.absensi', [
            'items' => $this->items,
            'user' => $this->user,
            'title' => $this->title,
        ]);
    }
}
