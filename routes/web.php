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
    Route::resource('jadwal', JadwalAbsensiController::class);
    Route::resource('status', StatusAbsenController::class);
    Route::resource('kenaikan', KenaikanGolonganController::class);
    Route::resource('penilaian', PenilaianPekerjaController::class);
    Route::resource('jabatanperizinan', PerizinanJabatanController::class);
    Route::resource('detail', DetailJabatanController::class);

    Route::resource('datakaryawan', DataKaryawanController::class);
    Route::resource('detailkaryawan', DetailKaryawanController::class);
    Route::resource('detailkeuangan', DetailKeuanganController::class);

    Route::resource('tukin', TunjanganKinerjaController::class);
    Route::resource('masakerja', MasaKerjaController::class);
    Route::resource('levelunit', LevelUnitController::class);

    Route::resource('unitkerja', UnitKerjaController::class);
    // Route::resource('userprofile', UserProfileController::class);
    Route::get('/userprofile', [UserProfileController::class, 'index'])->name('userprofile');
    Route::resource('keuangan', KeuanganController::class);
    Route::resource('userprofile', UserProfileController::class);
});

require __DIR__ . '/auth.php';
