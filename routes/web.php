<?php

use App\Http\Controllers\CitaController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () { return view('welcome'); });
Route::post('/citas/horas-del-dia', [CitaController::class, 'getHoursDay'])->name('citas.getHoursDay');
Route::post('/citas/mi-reserva', [CitaController::class, 'getMyBooking'])->name('citas.getMyBooking');
Route::post('/citas/horas-disponibles', [CitaController::class, 'getAvailableHours'])->name('citas.getAvailableHours');
Route::resource('citas', CitaController::class);