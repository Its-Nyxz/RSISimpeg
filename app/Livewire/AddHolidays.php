<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Holidays;

class AddHolidays extends Component
{

    public $id;
    public $tipe;
    public $holidays;
    public $holiday;
    public $description;
    public $date;

    public $errorMessage = '';

    public function mount()
    {
        $this->holidays = Holidays::all();

        if ($this->tipe) {
            $holiday = Holidays::find($this->tipe);
            $this->date = Carbon::parse($holiday->date)->format('Y-m-d'); // Format ke format yang kompatibel dengan input date HTML\
            $this->description = $holiday->description;
        }
    }

    public function removeHoliday()
    {
        Holidays::destroy($this->tipe);
        return redirect()->route('liburnasional.index')->with('success', 'Hari Libur Nasional berhasil dihapus.');
    }
    public function saveHoliday()
    {
        
        $this->validate([
            'date' => 'required|date',
            'description' => 'required|string|max:255',
        ]);

        // Cek apakah tanggal sudah ada (kecuali untuk edit data yang sama)
        $existingHoliday = Holidays::where('date', $this->date)
            ->when($this->tipe, function ($query) {
                return $query->where('id', '!=', $this->tipe);
            })
            ->first();

        if ($existingHoliday) {
            // Set error message untuk ditampilkan di SweetAlert
            $this->errorMessage = 'Tanggal ' . Carbon::parse($this->date)->format('d F Y') . ' sudah ada hari libur: ' . $existingHoliday->description;
            
            // Dispatch event untuk menampilkan SweetAlert
            $this->dispatch('show-error-alert', message: $this->errorMessage);
            return;
        }
        
        $holiday = Holidays::updateOrCreate(
            ['id' => $this->tipe ?? 0], // Unique field to check for existing record
            [
                'date' => $this->date,
                'description' => $this->description,
            ]
        );

        if ($holiday->wasRecentlyCreated && $this->holiday) {
            return redirect()->route('liburnasional.index')->with('success', 'Berhasil Menambah Hari Libur Nasional');
        } else {
            return redirect()->route('liburnasional.index')->with('success', 'Berhasil Mengubah Hari Libur Nasional');
        }
    }
    public function render()
    {
        return view('livewire.add-holidays');
    }
}
