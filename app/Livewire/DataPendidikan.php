<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\MasterPendidikan;

class DataPendidikan extends Component
{

    public $search = ''; // Properti untuk menyimpan nilai input pencarian
    public $pendidikans = [];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData($searchGolonganName = null)
    {
        $this->pendidikans = MasterPendidikan::with(['minimGolongan', 'maximGolongan']) // Load relasi ke golongan
            ->when($this->search, function ($query) {
                $query->where('nama', 'like', '%' . $this->search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $this->search . '%');
            })
            // ->when($searchGolonganName, function ($query) use ($searchGolonganName) {
            //     $query->whereHas('minimGolongan', function ($subQuery) use ($searchGolonganName) {
            //         $subQuery->where('nama', 'like', '%' . $searchGolonganName . '%');
            //     })
            //         ->orWhereHas('maximGolongan', function ($subQuery) use ($searchGolonganName) {
            //             $subQuery->where('nama', 'like', '%' . $searchGolonganName . '%');
            //         });
            // })
            ->get();
    }

    public function updateSearch($value)
    {
        $this->search = $value;
        $this->loadData();
    }

    public function destroy($id)
    {
        $pendidikan = MasterPendidikan::find($id);
        if (!$pendidikan) {
            return redirect()->route('pendidikan.index')->with('error', 'Pendidikan Tidak Ditemukan');
        }

        // Cek apakah ada user yang memakai pendidikan ini
        $userCount = User::where('kategori_pendidikan', $id)->count();

        if ($userCount > 0) {
            return redirect()->route('pendidikan.index')->with('error', 'Tidak dapat menghapus. Pendidikan ini sedang digunakan oleh pengguna.');
        }

        try {
            $pendidikan->delete();

            return redirect()->route('pendidikan.index')->with('success', 'Pendidikan berhasil Dihapus');
        } catch (\Exception $e) {
            return redirect()->route('pendidikan.index')->with('error', 'Terjadi kesalahan saat Pendidikan dihapus');
        }
    }

    public function render()
    {
        return view('livewire.data-pendidikan');
    }
}
