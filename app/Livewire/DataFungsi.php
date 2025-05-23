<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\MasterFungsi;

class DataFungsi extends Component
{
    public $search = '';
    public $fungsionals = [];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        // Query data dengan relasi ke KategoriJabatan
        $this->fungsionals = MasterFungsi::with('kategorijabatan')
            ->when($this->search, function ($query) {
                $query->whereHas('kategorijabatan', function ($q) {
                    $q->where('nama', 'like', '%' . $this->search . '%');
                })
                    ->orWhere('kualifikasi', 'like', '%' . $this->search . '%')
                    ->orWhere('nominal', 'like', '%' . $this->search . '%');
            })
            ->get();
    }

    public function updateSearch($value)
    {
        $this->search = $value;
        $this->loadData();
    }

    public function destroy($id)
    {
        $fungsional = MasterFungsi::with('kategorijabatan')->find($id);

        if (!$fungsional) {
            return redirect()->route('fungsional.index')->with('error', 'Fungsional Tidak Ditemukan');
        }

        // Cek apakah ada user yang memakai fungsional ini
        $userCount = User::where('jabatan_id', $id)->count();

        if ($userCount > 0) {
            return redirect()->route('fungsional.index')->with('error', 'Tidak dapat menghapus. Fungsional ini sedang digunakan oleh pengguna.');
        }

        try {
            $fungsional->delete();

            return redirect()->route('fungsional.index')->with('success', 'Fungsional berhasil Dihapus');
        } catch (\Exception $e) {
            return redirect()->route('fungsional.index')->with('error', 'Terjadi kesalahan saat Fungsional dihapus');
        }
    }

    public function render()
    {
        return view('livewire.data-fungsi');
    }
}
