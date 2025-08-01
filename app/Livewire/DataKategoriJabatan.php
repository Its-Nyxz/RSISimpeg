<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\KategoriJabatan;

class DataKategoriJabatan extends Component
{
    use WithPagination;

    public $search = '';

    // Method to load data with pagination
    public function loadData()
    {
        return KategoriJabatan::when($this->search, function ($query) {
            $query->where('nama', 'like', '%' . $this->search . '%')
                ->orWhere('tunjangan', 'like', '%' . $this->search . '%')
                ->orWhere('keterangan', 'like', '%' . $this->search . '%');
        })
            ->paginate(15); // You can adjust the number of items per page as needed
    }

    // Method to update search value and reload data
    public function updateSearch($value)
    {
        $this->search = $value;
        $this->resetPage(); // Reset to page 1 whenever the search term is updated
    }

    public function destroy($id)
    {
        $jabatan = KategoriJabatan::find($id);
        if (!$jabatan) {
            return redirect()->route('katjab.index')->with('error', 'Jabatan Tidak Ditemukan');
        }

        // Cek apakah ada user yang memakai jabatan ini
        $userCount = User::where('jabatan_id', $id)->count();

        if ($userCount > 0) {
            return redirect()->route('katjab.index')->with('error', 'Tidak dapat menghapus. Jabatan ini sedang digunakan oleh pengguna.');
        }

        try {
            $jabatan->delete();

            // Refresh data setelah penghapusan
            $this->loadData();

            return redirect()->route('katjab.index')->with('success', 'Jabatan berhasil Dihapus');
        } catch (\Exception $e) {
            return redirect()->route('katjab.index')->with('error', 'Terjadi kesalahan saat Jabatan dihapus');
        }
    }

    public function render()
    {
        return view('livewire.data-kategori-jabatan', [
            'katjab' => $this->loadData(), // Pass the paginated data to the view
        ]);
    }
}
