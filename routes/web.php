<?php

use App\Http\Controllers\MasterFungsiController;
use App\Http\Controllers\JadwalAbsensiController;
use App\Http\Controllers\StatusAbsenController;
use App\Models\MasterFungsi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\OpsiAbsenController;
use App\Http\Controllers\TunjanganKinerjaController;
use App\Http\Controllers\MasaKerjaController;
use App\Http\Controllers\LevelUnitController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MasterUmumController;
use App\Http\Controllers\MasterGapokController;
use App\Http\Controllers\MasterTransController;
use App\Http\Controllers\MasterJabatanController;
use App\Http\Controllers\MasterGolonganController;
use App\Http\Controllers\MasterTunjanganController;
use App\Http\Controllers\MasterPendidikanController;
use App\Http\Controllers\MasterPotonganController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\KenaikanBerkalaGolController;
use App\Http\Controllers\PeranFungsionalController;
use App\Http\Controllers\PerizinanJabatanController;

use App\Http\Controllers\MasterKhususController;
use App\Http\Controllers\MasterAbsensiController;
use App\Http\Controllers\KenaikanGolonganController;
use App\Http\Controllers\PenilaianPekerjaController;
use App\Http\Controllers\DetailJabatanController;

use App\Http\Controllers\DataKaryawanController;
use App\Http\Controllers\DetailKaryawanController;
use App\Http\Controllers\DetailKeuanganController;

use App\Http\Controllers\UnitKerjaController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\ProposionalitasPointController;
use App\Http\Controllers\PointPeranController;
use App\Http\Controllers\TukinJabatanController;
use App\Http\Controllers\TimerController;

use App\Http\Controllers\CutiController;
use App\Http\Controllers\ImportGajiController;

use App\Http\Controllers\AktivitasAbsensiController;
use App\Http\Controllers\HolidaysController;
use App\Http\Controllers\KategoriJabatanController;
use App\Http\Controllers\PengajuanController;
use App\Livewire\UserProfile;

Route::get('/', function () {
    return redirect()->to('/login');
});

Route::get('/logout', function () {
    Auth::guard('web')->logout();

    Session::invalidate();
    Session::regenerateToken();

    return redirect()->to('/login');
});

Route::get('dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('users', UsersController::class);
    Route::get('users/create', [UsersController::class, 'create'])->name('users.create');
    Route::get('users/edit/{id}', [UsersController::class, 'edit'])->name('users.edit');
    Route::put('users/resetPassword/{id}', [UserProfile::class, 'resetPassword'])->name('users.resetPassword');
    Route::delete('users/destroy/{id}', [UserProfile::class, 'destroy'])->name('users.destroy');
    Route::resource('absensi', AbsensiController::class);
    Route::resource('shift', ShiftController::class);
    Route::resource('opsi', OpsiAbsenController::class);
    Route::resource('jabatan', MasterJabatanController::class);
    Route::resource('umum', MasterUmumController::class);
    Route::resource('trans', MasterTransController::class);
    Route::resource('tunjangan', MasterTunjanganController::class);
    Route::resource('fungsional', MasterFungsiController::class);
    Route::resource('khusus', MasterKhususController::class);
    Route::resource('gapok', MasterGapokController::class);
    Route::resource('golongan', MasterGolonganController::class);
    Route::resource('pendidikan', MasterPendidikanController::class);
    Route::resource('potongan', MasterPotonganController::class);

    Route::resource('kenaikangol', KenaikanBerkalaGolController::class);
    Route::resource('peranfungsional', PeranFungsionalController::class);
    Route::resource('jabatanperizinan', PerizinanJabatanController::class);

    Route::resource('absensi', MasterAbsensiController::class);
    Route::get('/jadwal/template', [JadwalAbsensiController::class, 'export'])->name('jadwal.template');
    Route::get('jadwal/{tipe}/{id}', [JadwalAbsensiController::class, 'create']);
    Route::resource('jadwal', JadwalAbsensiController::class);
    Route::resource('status', StatusAbsenController::class);
    Route::resource('kenaikan', KenaikanGolonganController::class);
    Route::resource('penilaian', PenilaianPekerjaController::class);
    Route::resource('jabatanperizinan', PerizinanJabatanController::class);
    Route::resource('detail', DetailJabatanController::class);

    Route::resource('datakaryawan', DataKaryawanController::class);
    Route::resource('detailkaryawan', DetailKaryawanController::class);
    Route::get('editKaryawan/{id}', [DataKaryawanController::class, 'edit'])->name('editKaryawan.edit');
    Route::resource('detailkeuangan', DetailKeuanganController::class);

    Route::resource('tukin', TunjanganKinerjaController::class);
    Route::resource('masakerja', MasaKerjaController::class);
    Route::resource('levelunit', LevelUnitController::class);


    Route::resource('unitkerja', UnitKerjaController::class);
    // Route::resource('userprofile', UserProfileController::class);
    // Route::get('/userprofile', [UserProfileController::class, 'index'])->name('userprofile');
    Route::get('/userprofile/editnomor', [UserProfileController::class, 'editNomor'])->name('userprofile.editnomor');
    Route::get('/userprofile/editemail', [UserProfileController::class, 'editEmail'])->name('userprofile.editemail');
    Route::get('/userprofile/editpassword', [UserProfileController::class, 'editPassword'])->name('userprofile.editpassword');
    Route::get('/userprofile/editprofile', [UserProfileController::class, 'editProfile'])->name('userprofile.editprofile');
    Route::resource('keuangan', KeuanganController::class);
    Route::resource('userprofile', UserProfileController::class);
    Route::resource('proposionalitas', ProposionalitasPointController::class);
    Route::resource('poinperan', PointPeranController::class);
    Route::resource('tukinjabatan', TukinJabatanController::class);
    Route::resource('timer', TimerController::class);
    Route::resource('cuti', CutiController::class);
    Route::resource('importgaji', ImportGajiController::class);
    Route::resource('approvalcuti', CutiController::class);
    // Hapus route 'create' bawaan dari resource
    Route::resource('aktivitasabsensi', AktivitasAbsensiController::class)
        ->except(['create']);

    // Buat route custom untuk create dengan parameter user_id
    Route::get('/aktivitasabsensi/create/{user_id?}', [AktivitasAbsensiController::class, 'create'])
        ->name('aktivitasabsensi.create');

    Route::resource('katjab', KategoriJabatanController::class);
    Route::get('liburnasional/{tipe}/{holiday}', [HolidaysController::class, 'create']);
    Route::resource('liburnasional', HolidaysController::class);
    Route::get('pengajuan/{tipe}', [PengajuanController::class, 'create']);
    Route::get('pengajuan', [PengajuanController::class, 'index']);
});

require __DIR__ . '/auth.php';
