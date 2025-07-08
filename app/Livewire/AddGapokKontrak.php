<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\GapokKontrak;
use App\Models\KategoriJabatan;
use App\Models\MasterPendidikan;

class AddGapokKontrak extends Component
{
    public $id;
    public $tipe;
    public $kontraks;
    public $kontrak;
    public $min_masa_kerja;
    public $max_masa_kerja;
    public $nominal;
    public $jabatan;
    public $pendidikan;
    public $showPenyesuaianModal = false;
    public $penyesuaian_nominal;
    public $penyesuaian_tanggal;
    public $penyesuaianList = [];
    public $penyesuaian_keterangan;

    public $suggestions = [
        'jabatan' => [],
        'pendidikan' => [],
    ];

    public function fetchSuggestions($field, $value)
    {
        $this->suggestions[$field] = [];

        // if ($value) {
        if ($field === 'jabatan') {
            $categories = KategoriJabatan::where('nama', 'like', "%$value%")
                ->get()
                ->groupBy('tunjangan');

            foreach ($categories as $tunjangan => $katjabList) {
                $this->suggestions[$field][$tunjangan] = $katjabList->pluck('nama')->toArray();
            }
        } elseif ($field === 'pendidikan') {
            $this->suggestions[$field] = MasterPendidikan::where('nama', 'like', "%$value%")
                ->pluck('nama')
                ->toArray();
        }
    }

    public function selectSuggestion($field, $value)
    {
        if ($field === 'jabatan') {
            $this->jabatan = $value;
        } elseif ($field === 'pendidikan') {
            $this->pendidikan = $value;
        }
        $this->suggestions[$field] = [];
    }


    public function hideSuggestions($field)
    {
        $this->suggestions[$field] = [];
    }


    public function mount()
    {
        $this->kontraks = GapokKontrak::all();

        if ($this->tipe) {
            $kontrak = GapokKontrak::find($this->tipe);

            if ($kontrak) {
                $this->jabatan = $kontrak->kategoriJabatan->nama ?? null;
                $this->pendidikan = $kontrak->pendidikan->nama ?? null;
                $this->min_masa_kerja = $kontrak->min_masa_kerja;
                $this->max_masa_kerja = $kontrak->max_masa_kerja;
                // Ambil penyesuaian yang sudah aktif (tanggal_berlaku <= hari ini)
                $penyesuaianAktif = $kontrak->penyesuaian()
                    ->where('tanggal_berlaku', '<=', now())
                    ->orderByDesc('tanggal_berlaku')
                    ->first();

                if ($penyesuaianAktif) {
                    $this->nominal = $penyesuaianAktif->nominal_baru;
                } else {
                    $this->nominal = $kontrak->nominal;
                }

                // Riwayat penyesuaian ditampilkan di tabel
                $this->penyesuaianList = $kontrak->penyesuaian()
                    ->orderByDesc('tanggal_berlaku')
                    ->get()
                    ->toArray();
            }
        }
    }

    public function removeKontrak()
    {
        GapokKontrak::destroy($this->tipe);
        return redirect()->route('gapokkontrak.index')->with('success', 'Gaji Pokok Kontrak berhasil dihapus.');
    }

    public function saveKontrak()
    {
        $this->validate([
            'jabatan' => 'nullable',
            'pendidikan' => 'nullable|exists:master_pendidikan,id',
            'min_masa_kerja' => 'required|numeric|min:0',
            'max_masa_kerja' => 'required|numeric|min:0',
            'nominal' => 'required|numeric|min:0',
        ]);
        $kategoriJabatan = KategoriJabatan::where('nama', $this->jabatan)->first();

        $kontrak = GapokKontrak::updateOrCreate(
            ['id' => $this->tipe ?? 0],
            [
                'kategori_jabatan_id' => $kategoriJabatan->id ?? null,
                'pendidikan_id' => $this->pendidikan ?? null,
                'min_masa_kerja' => $this->min_masa_kerja,
                'max_masa_kerja' => $this->max_masa_kerja,
                'nominal' => $this->nominal,
            ]
        );

        if ($kontrak->wasRecentlyCreated) {
            return redirect()->route('gapokkontrak.index')->with('success', 'Berhasil Menambah Gaji Pokok Kontrak.');
        } else {
            return redirect()->route('gapokkontrak.index')->with('success', 'Berhasil Mengubah Gaji Pokok Kontrak.');
        }
    }

    public function openPenyesuaianModal()
    {
        $this->penyesuaian_nominal = $this->nominal;
        $this->penyesuaian_tanggal = now()->toDateString();
        $this->showPenyesuaianModal = true;
    }

    public function closePenyesuaianModal()
    {
        $this->showPenyesuaianModal = false;
    }

    public function savePenyesuaian()
    {
        $this->validate([
            'penyesuaian_nominal' => 'required|numeric|min:0',
            'penyesuaian_tanggal' => 'required|date',
            'penyesuaian_keterangan' => 'nullable|string|max:255',
        ]);

        $kontrak = GapokKontrak::find($this->tipe);
        if (!$kontrak) return;

        // Jika nominal tidak berubah dari sebelumnya
        if ($kontrak->nominal == $this->penyesuaian_nominal) {
            $this->showPenyesuaianModal = false;
            session()->flash('info', 'Tidak ada perubahan nominal gaji. Data tetap.');
            return;
        }

        // Cek apakah sudah ada penyesuaian sebelumnya
        $hasExistingPenyesuaian = $kontrak->penyesuaian()->exists();

        if (!$hasExistingPenyesuaian) {
            $kontrak->penyesuaian()->create([
                'tanggal_berlaku' => $kontrak->created_at->toDateString(),
                'nominal_baru' => $kontrak->nominal,
                'keterangan' => 'Nilai awal sebelum penyesuaian',
            ]);
        }

        // Simpan penyesuaian baru
        $kontrak->penyesuaian()->updateOrCreate(
            ['tanggal_berlaku' => $this->penyesuaian_tanggal],
            [
                'nominal_baru' => $this->penyesuaian_nominal,
                'keterangan' => $this->penyesuaian_keterangan ?: 'Penyesuaian UMK',
            ]
        );

        // Update nilai aktif
        $kontrak->update(['nominal' => $this->penyesuaian_nominal]);

        $this->showPenyesuaianModal = false;
        $this->penyesuaianList = $kontrak->penyesuaian()->orderByDesc('tanggal_berlaku')->get()->toArray();

        session()->flash('success', 'Penyesuaian gaji berhasil disimpan dan nilai aktif diperbarui.');
    }



    public function render()
    {
        return view('livewire.add-gapok-kontrak');
    }
}
