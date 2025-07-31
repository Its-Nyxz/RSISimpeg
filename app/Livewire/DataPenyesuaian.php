<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\Penyesuaian;
use Livewire\WithPagination;
use App\Models\MasterPenyesuaian;

class DataPenyesuaian extends Component
{
    use WithPagination;
    public $search = '';

    // Method to load data with pagination
    public function loadData()
    {
        return MasterPenyesuaian::with(['pendidikanAwal', 'pendidikanPenyesuaian'])
            ->when($this->search, function ($query) {
                $query->whereHas('pendidikanAwal', function ($q) {
                    $q->where('nama', 'like', '%' . $this->search . '%');
                })->orWhereHas('pendidikanPenyesuaian', function ($q) {
                    $q->where('nama', 'like', '%' . $this->search . '%');
                })->orWhere('masa_kerja', 'like', '%' . $this->search . '%');
            })
            ->paginate(15);
    }

    public function updateSearch($value)
    {
        $this->search = $value;
        $this->resetPage(); // Reset to page 1 whenever the search term is updated
    }

    public function destroy($id)
    {
        $penyesuaian = MasterPenyesuaian::find($id);
        if (!$penyesuaian) {
            return redirect()->route('penyesuaian.index')->with('error', 'Penyesuaian Tidak Ditemukan');
        }

        // Cek apakah ada user yang memakai Penyesuaian ini
        $userCount = Penyesuaian::where('penyesuaian_id', $id)->count();

        if ($userCount > 0) {
            return redirect()->route('penyesuaian.index')->with('error', 'Tidak dapat menghapus. Penyesuaian ini sedang digunakan oleh pengguna.');
        }

        try {
            $penyesuaian->delete();

            return redirect()->route('penyesuaian.index')->with('success', 'Penyesuaian berhasil Dihapus');
        } catch (\Exception $e) {
            return redirect()->route('penyesuaian.index')->with('error', 'Terjadi kesalahan saat Penyesuaian dihapus');
        }
    }


    public function render()
    {
        return view('livewire.data-penyesuaian', [
            'penyesuaians' => $this->loadData(), // Pass the paginated data to the view
        ]);
    }
}
