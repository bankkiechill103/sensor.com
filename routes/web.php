<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\HomeController;
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

Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('get-data-g', [ResultController::class, 'getDataG'])->name('getdatag');
Route::get('get-data-t', [ResultController::class, 'getDataT'])->name('getdatat');
Route::get('exports/{id}/{type}/{start_date}/{end_date}', [ResultController::class, 'export'])->name('export');
