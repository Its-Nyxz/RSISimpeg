<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\MasterJabatan;
use App\Models\MasterPendidikan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{
    public function index()
    {
        return view('userprofile.index');
    }

    public function editNomor()
    {
        return view('userprofile.editnomor');
    }

    public function updateWhatsApp(Request $request)
    {
        // Validasi input
        $request->validate([
            'no_hp' => 'required|numeric|digits_between:10,15',
        ]);

        // Update nomor WhatsApp
        $user = Auth::user();
        $user->no_hp = $request->no_hp;
        $user->save();

        return redirect()->back()->with('success', 'Nomor WhatsApp berhasil diperbarui melalui Controller!');
    }

    public function editEmail()
    {
        return view('userprofile.editemail');
    }

    public function updateEmail(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email|unique:users,email,' . Auth::id(),
        ]);

        // Update email
        $user = Auth::user();
        $user->email = $request->email;
        $user->save();

        return redirect()->back()->with('success', 'Email berhasil diperbarui melalui Controller!');
    }

    public function editPassword()
    {
        return view('userprofile.editpassword');
    }

    public function updatePassword(Request $request)
    {
        // Validasi input
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|confirmed', // Password baru dan konfirmasi password harus sama
        ]);

        // Validasi password lama
        $user = Auth::user();

        // Mengecek apakah password lama sesuai dengan yang ada di database
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai.']);
        }

        // Update password baru
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->back()->with('success', 'Password berhasil diperbarui.');
    }

    public function editProfile()
    {
        $user = Auth::user();
        $jabatans = MasterJabatan::all();
        $pendidikans = MasterPendidikan::all();

        return view('userprofile.editprofile', compact('user', 'jabatans', 'pendidikans'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'jabatan_id' => 'nullable|exists:master_jabatan,id', // Validasi jabatan harus ada di tabel master_jabatan
            'tempat' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'tanggal_tetap' => 'nullable|date',
            'pendidikan_id' => 'nullable|exists:master_pendidikan,id',
            'pendidikan_penyesuaian' => 'nullable|string|max:255',
            'tgl_penyesuaian' => 'nullable|date',
            'pensiun' => 'nullable|date',
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->jabatan_id = $request->jabatan_id; // Simpan jabatan_id ke dalam tabel users
        $user->tempat = $request->tempat;
        $user->tanggal_lahir = $request->tanggal_lahir;
        $user->tanggal_tetap = $request->tanggal_tetap;
        $user->pendidikan_id = $request->pendidikan_id;
        $user->pendidikan_penyesuaian = $request->pend_penyesuaian;
        $user->tgl_penyesuaian = $request->tgl_penyesuaian;
        $user->pensiun = $request->pensiun;

        $user->save();

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }
}
