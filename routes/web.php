<?php

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

Route::get('/', function () {

});
Route::any('/telegram1',[\App\Http\Controllers\TelegramController::class,'index'])->name('telegram');
Route::any('callback',[\App\Http\Controllers\GateWayController::class,'verify'])->name('gateway.callback');
Route::get('PayMent',[\App\Http\Controllers\GateWayController::class,'init'])->name('gateway.init');
