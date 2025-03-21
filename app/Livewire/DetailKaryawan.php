<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\DB;


class DetailKaryawan extends Component
{
    public $user;
    public $user_id;
    public $alasanResign;
    public $statusKaryawan;
    public $roles; 

    public function mount($user)
    {
        // Mendapatkan data user
        $this->user = $user;
        $this->user_id = $user->id;
        $this->statusKaryawan = $user->status_karyawan;
        $this->alasanResign = $user->alasan_resign;
        $this->roles = DB::table('roles')
            ->join('model_has_roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('model_has_roles.model_id', $this->user_id)
            ->pluck('roles.name')
            ->toArray();
    }

    public function resignKerja()
    {
        $this->validate(['alasanResign' => 'required|string|max:255']);
        $user = User::findOrFail($this->user_id);
        $user->update([
            'status_karyawan' => 0,
            'alasan_resign' => $this->alasanResign
        ]);
        return redirect()->route('detailkaryawan.show', $this->user_id)->with('success', 'Karyawan berhasil dinonaktifkan.');
    }

    public function kembali()
    {
        $user = User::findOrFail($this->user_id);
        $user->update([
            'status_karyawan' => 1,
            'alasan_resign' => $this->alasanResign
        ]);
        return redirect()->route('detailkaryawan.show', $this->user_id)->with('success', 'Karyawan berhasil diaktifkan.');
    }

    public function render()
    {
        return view('livewire.detail-karyawan', [
            'roles' => $this->roles // Kirim ke view
        ]);
    }
}
