<?php

use App\Http\Controllers\AparController;
use App\Http\Controllers\AlarmController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\HydrantController;
use App\Http\Controllers\ScanController;
use App\Models\Hydrant;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
//route login
Route::get('/login', [AuthController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::get('/register', [AuthController::class, 'register']);
Route::post('/register', [AuthController::class, 'process']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

// route dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth');
Route::get('/', [DashboardController::class, 'index'])->middleware('auth');

//route barang
Route::resource('/barang', BarangController::class)->middleware('auth');
Route::resource('/apar', AparController::class)->middleware('auth');
Route::resource('/alarm', AlarmController::class)->middleware('auth');
Route::resource('/hydrant', HydrantController::class)->middleware('auth');

Route::get('qrcode/apar/{id}', [AparController::class, 'generate'])->name('generate.apar');
Route::get('qrcode/alarm/{id}', [AlarmController::class, 'generate'])->name('generate.alarm');
Route::get('qrcode/hydrant/{id}', [HydrantController::class, 'generate'])->name('generate.hydrant');