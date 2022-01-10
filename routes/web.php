<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JasaController;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use \App\Http\Controllers\KategoriPaketController;
use \App\Http\Controllers\TemaController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
//    return Inertia::render('Welcome', [
//        'canLogin' => Route::has('login'),
//        'canRegister' => Route::has('register'),
//        'laravelVersion' => Application::VERSION,
//        'phpVersion' => PHP_VERSION,
//    ]);
    return Redirect::to('login');
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::group(['prefix' => 'jadwal', 'as' => 'jadwal.'], function () {
        Route::get('/', [JadwalController::class, 'index'])->name('index');
        Route::post('/tambah/off', [JadwalController::class, 'add_tanggal_off'])->name('tambah-off');
    });
    Route::resource('jasa', JasaController::class);
    Route::resource('akun-bank', BankAccountController::class);
    Route::resource('post', PostController::class);
    Route::resource('booking', BookingController::class);
    Route::resource('kategori-paket', KategoriPaketController::class);
    Route::resource('kategori-tema', TemaController::class);
    Route::group(['prefix' => 'pembayaran', 'as' => 'pembayaran.'], function () {
        Route::get('/', [PaymentController::class, 'index'])->name('index');
        Route::put('/{id}', [PaymentController::class, 'verifikasi'])->name('verifikasi');
    });
});
