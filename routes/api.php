<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController as APIAuthController;
use App\Http\Controllers\API\JasaController as APIJasaController;
use App\Http\Controllers\API\BankAccountController as APIBankAccountController;
use App\Http\Controllers\API\BookingController as APIBookingController;
use App\Http\Controllers\API\JadwalController as APIJadwalController;
use App\Http\Controllers\API\PostController as APIPostController;
use App\Http\Controllers\API\PaymentController as APIPaymentController;
use App\Http\Controllers\API\KategoriController as APIKategoriController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
//API route for register new user
Route::post('/register', [APIAuthController::class, 'register']);
//API route for login user
Route::post('/login', [APIAuthController::class, 'login']);

//Protecting Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/review/add', [APIJasaController::class, 'add_review'])->name('review.add');
    Route::get('/profile', [APIAuthController::class, 'profile']);
    Route::get('/notifications', [APIAuthController::class, 'get_notifications']);
    Route::put('/update/profile', [APIAuthController::class, 'update']);
    Route::put('/change/password', [APIAuthController::class, 'change_password']);

    Route::post('/logout', [APIAuthController::class, 'logout']);
    Route::group(['prefix'=>'booking','as'=>'booking.'], function(){
        Route::get('/list', [APIBookingController::class, 'daftar_booking'])->name('list');
        Route::get('/detail/{id}', [APIBookingController::class, 'detail_booking'])->name('detail');
        Route::post('/pesan', [APIBookingController::class, 'make_booking'])->name('pesan');
        Route::put('/upload-bukti/{id}', [APIPaymentController::class, 'upload_bukti_pembayaran'])->name('upload_bukti_pembayaran');
        Route::put('/cancel/{id}', [APIBookingController::class, 'cancel_booking']);
    });
    Route::group(['prefix'=>'jadwal','as'=>'jadwal.'], function(){
        Route::get('/list', [APIJadwalController::class, 'daftar_jadwal'])->name('list');
    });
    Route::get('pencarian/rekomendasi', [APIJasaController::class, 'getRecommendations']);
    Route::get('thumbnails/paket-lengkap', [APIJasaController::class, 'getPaketLengkap']);
    Route::get('thumbnails/paket-lengkap', [APIJasaController::class, 'getPaketLengkap']);
    Route::resource('vendor', APIJasaController::class);

});
Route::get('pencarian/vendor', [APIJasaController::class, 'cari_vendor']);
Route::resource('bank', APIBankAccountController::class);
Route::resource('katalog', APIPostController::class);
Route::get('thumbnails/vendor', [APIJasaController::class, 'getThumbnails']);
Route::get('pencarian/terpopuler', [APIJasaController::class, 'getPencarianTerpopuler']);
Route::get('categories/tema', [APIKategoriController::class, 'indexTema']);
Route::get('categories/paket', [APIKategoriController::class, 'indexKategori']);
Route::get('global/vendor/all', [APIJasaController::class, 'index_global']);
Route::get('global/vendor/detail/{id}', [APIJasaController::class, 'show_global']);
Route::get('global/vendor/lengkap', [APIJasaController::class, 'getPaketLengkap_global']);
