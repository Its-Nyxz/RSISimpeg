<?php

namespace App\Livewire;

use App\Models\Kategoripph;
use Livewire\Component;

class AddPph extends Component
{
    public $id;
    public $tipe; // id penyesuaian untuk edit
    public $pphs;
    public $nama;
    public $parent;
    public $keterangan;

    public function mount()
    {
        // hanya ambil parent (kategori utama)
        $this->pphs = Kategoripph::whereNull('parent_id')->get();

        if ($this->tipe) {
            $pph = Kategoripph::find($this->tipe);

            if ($pph) {
                $this->nama = $pph->nama;
                $this->parent = $pph->parent_id;
                $this->keterangan = $pph->keterangan;
            }
        }
    }

    public function removepph()
    {
        $kategori = Kategoripph::withCount('users')->findOrFail($this->tipe);

        if ($kategori->users_count > 0) {
            return redirect()->route('pph.index')
                ->with('error', 'Kategori tidak bisa dihapus karena masih digunakan oleh user.');
        }

        $kategori->delete();

        return redirect()->route('pph.index')
            ->with('success', 'Kategori PPh berhasil dihapus.');
    }


    public function savepph()
    {
        $this->validate([
            'nama' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        // Jika parent kosong, jadikan null
        $parentId = $this->parent ?: null;

        $pph = Kategoripph::updateOrCreate(
            ['id' => $this->tipe],
            [
                'nama' => $this->nama,
                'parent_id' => $parentId,
                'keterangan' => $this->keterangan,
            ]
        );

        $message = $pph->wasRecentlyCreated
            ? 'Berhasil Menambahkan Kategori PPh.'
            : 'Berhasil Memperbarui Kategori PPh.';

        return redirect()->route('pph.index')->with('success', $message);
    }

    public function render()
    {
        return view('livewire.add-pph', [
            'parentList' => $this->pphs
        ]);
    }
}
