<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\GajiNetto;
use App\Models\UnitKerja;
use Livewire\WithPagination;
use App\Models\JenisKaryawan;
use Livewire\WithFileUploads;
use App\Imports\PotonganImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\JadwalTemplateExport;
use App\Exports\PotonganTemplateExport;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class EditUrutan extends Component
{

    public $jenisId;
    public $namaJenis;

    public function mount($jenisId)
    {
        $this->jenisId = $jenisId;
        $this->loadData();

        $dataJenis = JenisKaryawan::find($this->jenisId);
        $this->namaJenis = $dataJenis ? $dataJenis->nama : 'Karyawan';
    }

    public function updateUrutan($items)
    {
        DB::transaction(function () use ($items) {
            foreach ($items as $item) {
                // Gunakan updateOrInsert dengan kondisi yang lebih spesifik
                DB::table('urutan_keuangan_user')->updateOrInsert(
                    [
                        'user_id' => $item['value'],
                    ],
                    [
                        'urutan' => $item['order']
                    ]
                );
            }
        });

        $this->dispatch('notify', 'Urutan berhasil diperbarui!');
    }

    public function loadData()
    {
        return User::with(['kategorijabatan', 'unitKerja', 'roles', 'urutanKeuangan'])
            ->where('id', '>', 1)
            ->where('status_karyawan', '1')
            ->where('jenis_id', $this->jenisId)

            // Mengambil kolom 'urutan' dari relasi urutanKeuangan secara otomatis
            ->withAggregate('urutanKeuangan', 'urutan')

            // Sekarang Anda punya kolom virtual 'urutan_keuangan_urutan'
            ->orderByRaw('urutan_keuangan_urutan IS NULL ASC') // Taruh yang belum punya urutan di bawah
            ->orderBy('urutan_keuangan_urutan', 'asc')
            ->orderBy('name', 'asc')
            ->get();
    }   


    public function render()
    {
        $users = $this->loadData();
        return view('livewire.edit-urutan', [
            'users' => $users
        ]);
    }
}
