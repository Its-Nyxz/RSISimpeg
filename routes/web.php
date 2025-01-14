<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\OpsiAbsenController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MasterUmumController;
use App\Http\Controllers\MasterGapokController;
use App\Http\Controllers\MasterTransController;
use App\Http\Controllers\MasterJabatanController;
use App\Http\Controllers\MasterGolonganController;
use App\Http\Controllers\MasterTunjanganController;
use App\Http\Controllers\MasterPendidikanController;
use App\Http\Controllers\KenaikanBerkalaGolController;
use App\Http\Controllers\PeranFungsionalController;
use App\Http\Controllers\PerizinanJabatanController;

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
    Route::resource('trans', MasterUmumController::class);
    Route::resource('tunjangan', MasterTunjanganController::class);
    Route::resource('gapok', MasterGapokController::class);
    Route::resource('golongan', MasterGolonganController::class);
    Route::resource('pendidikan', MasterPendidikanController::class);
    Route::resource('kenaikangol', KenaikanBerkalaGolController::class);
    Route::resource('peranfungsional', PeranFungsionalController::class);
    Route::resource('jabatanperizinan', PerizinanJabatanController::class);
});

require __DIR__ . '/auth.php';
