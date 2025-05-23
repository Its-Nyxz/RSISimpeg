<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\MasterUmum;

class DataUmum extends Component
{
    public $search = '';
    public $umums = [];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        // Query data dengan relasi ke KategoriJabatan
        $this->umums = MasterUmum::with('kategorijabatan')
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
        $jabatan = MasterUmum::with('kategorijabatan')->find($id);

        if (!$jabatan) {
            return redirect()->route('umum.index')->with('error', 'Jabatan Tidak Ditemukan');
        }

        // Cek apakah ada user yang memakai jabatan ini
        $userCount = User::where('jabatan_id', $id)->count();

        if ($userCount > 0) {
            return redirect()->route('umum.index')->with('error', 'Tidak dapat menghapus. Jabatan ini sedang digunakan oleh pengguna.');
        }

        try {
            $jabatan->delete();

            return redirect()->route('umum.index')->with('success', 'Jabatan berhasil Dihapus');
        } catch (\Exception $e) {
            return redirect()->route('umum.index')->with('error', 'Terjadi kesalahan saat Jabatan dihapus');
        }
    }
    public function render()
    {
        return view('livewire.data-umum');
    }
}
