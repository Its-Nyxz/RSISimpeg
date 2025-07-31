<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\WithPagination;

class UserProfile extends Component
{
    use WithPagination;

    public $userprofile;
    public $search = '';
    public $showNip = false;
    public function mount()
    {
        // Ambil data user yang sedang login
        $this->userprofile = User::with('kategorijabatan', 'pendidikanUser')
            ->where('id', Auth::id()) // Filter berdasarkan ID user yang login
            ->first();
        $this->loadData();
    }

    public function toggleNip()
    {
        $this->showNip = !$this->showNip;
    }

    public function updateSearch($value)
    {
        $this->search = $value;
        $this->resetPage();
    }

    public function loadData()
    {
        return User::with(['kategorijabatan', 'unitKerja', 'roles'])->where('id', '>', 1)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('id', '>', 1) //memastikan ketika dicari berdasarkan jabatan SuperAdmin tidak ikut tampil
                        ->where(function ($q) {
                            $q->where('name', 'like', '%' . $this->search . '%')
                                ->orWhere('username', 'like', '%' . $this->search . '%')
                                ->orWhereHas('kategorijabatan', function ($query) {
                                    $query->where('nama', 'like', '%' . $this->search . '%');
                                });
                        });
                });
            })->paginate(15);
    }

    public function resetPassword($id)
    {
        User::findOrFail($id)->update([
            'password' => Hash::make(123)
        ]);
        return redirect()->route('userprofile.index')->with('success', 'Password User berhasil diubah 123!');
    }

    public function destroy($id)
    {
        try {
            User::findOrFail($id)->delete();
            return redirect()->route('userprofile.index')->with('success', 'User berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('userprofile.index')->with('gagal', 'Terjadi kesalahan saat menghapus user!');
        }
    }

    public function render()
    {
        $users = $this->loadData();
        return view('livewire.user-profile', [
            'userprofile' => $this->userprofile,
            'users' => $users,
        ]);
    }
}