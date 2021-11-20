<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController as APIAuthController;
use App\Http\Controllers\API\JasaController as APIJasaController;
use App\Http\Controllers\API\BankAccountController as APIBankAccountController;
use App\Http\Controllers\API\BookingController as APIBookingController;
use App\Http\Controllers\API\JadwalController as APIJadwalController;
use App\Http\Controllers\API\PostController as APIPostController;

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
    Route::get('/profile', [APIAuthController::class, 'profile']);
    Route::post('/logout', [APIAuthController::class, 'logout']);
    Route::group(['prefix'=>'booking','as'=>'booking.'], function(){
        Route::get('/list', [APIBookingController::class, 'daftar_booking'])->name('list');
        Route::post('/pesan', [APIBookingController::class, 'make_booking'])->name('pesan');
    });
    Route::group(['prefix'=>'jadwal','as'=>'jadwal.'], function(){
        Route::get('/list', [APIJadwalController::class, 'daftar_jadwal'])->name('list');
    });
});
Route::resource('vendor', APIJasaController::class);
Route::resource('bank', APIBankAccountController::class);
Route::resource('katalog', APIPostController::class);
