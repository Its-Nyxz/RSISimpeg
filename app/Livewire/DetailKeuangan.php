<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\Potongan;
use App\Models\GajiBruto;
use App\Models\MasterPotongan;

class DetailKeuangan extends Component
{
    public $user;
    public $gajiBruto;
    public $isKaryawanTetap;
    public $bulan, $tahun;
    public $dynamicPotongans = [];

    public function mount(User $user)
    {
        $this->user = $user;
        $this->bulan = now()->month;
        $this->tahun = now()->year;
        $this->loadData();
    }

    public function updatedBulan()
    {
        $this->loadData();
    }
    public function updatedTahun()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->gajiBruto = GajiBruto::with('potongan')
            ->where('user_id', $this->user->id)
            ->where('tahun_penggajian', $this->tahun)
            ->where('bulan_penggajian', $this->bulan)
            ->first();

        $masterPotongans = MasterPotongan::orderBy('id')->get();
        $potonganList = collect();

        if ($this->gajiBruto) {
            $potonganList = Potongan::with('masterPotongan')
                ->where('bruto_id', $this->gajiBruto->id)
                ->get()
                ->keyBy('master_potongan_id');
        }

        // Buat urutan tetap sesuai master
        // 1. Definisikan urutan manual berdasarkan SLUG (atau field pengidentifikasi unik lainnya)
        // $urutanManual = [
        //     'simpanan-wajib',
        //     'simpanan-pokok',
        //     'ibi',
        //     'idi',
        //     'ppni',
        //     'pinjaman-koperasi',
        //     'obat',
        //     'angsuran-bank',
        //     'angsuran-perum',
        //     'dansos-karyawan',
        //     'dplk',
        //     'bpjs-tenaga-kerja',
        //     'bpjs-kesehatan',
        //     'rekonsiliasi-bpjs-kesehatan',
        //     'bpjs-kesehatan-ortutambahan',
        //     'pph21',
        //     'kurangan-pph-21-tahun-2024',
        //     'amaliah-romadhon',
        //     'rawat-inap',
        //     'potongan-selisih',
        //     'iuran-pekarsi',
        //     'lain-lain'
        // ];

        // 2. Ambil master potongan dan urutkan terlebih dahulu
        // $masterPotongans = MasterPotongan::all()->sortBy(function ($mp) use ($urutanManual) {
        //     $posisi = array_search($mp->slug, $urutanManual); // Pastikan ada kolom 'slug' di table master_potongans
        //     return ($posisi === false) ? 999 : $posisi;
        // });
        $masterPotongans = MasterPotongan::aktifTerurut()->get();

        // 3. Mapping ke dynamicPotongans (urutan akan terjaga dari $masterPotongans)
        $this->dynamicPotongans = $masterPotongans->mapWithKeys(function ($mp) use ($potonganList) {
            return [$mp->nama => $potonganList[$mp->id]->nominal ?? 0];
        })->toArray();


        $this->isKaryawanTetap = strtolower($this->user->jenis?->nama ?? '') === 'tetap';
    }

    public function getTotalPotonganProperty()
    {
        return collect($this->dynamicPotongans)->sum();
    }

    public function render()
    {
        return view('livewire.detail-keuangan', [
            'jenisKaryawan' => strtolower($this->user->jenis?->nama ?? ''),
            'isKaryawanTetap' => $this->isKaryawanTetap,
        ]);
    }
}
