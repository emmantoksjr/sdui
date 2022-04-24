<?php

use App\Http\Controllers\NewsController;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->name('news')->prefix('news')->group(function () {
    Route::get('/', [NewsController::class, 'index'])->name('index');
    Route::post('/', [NewsController::class, 'create'])->name('create');
    Route::get('/{news}', [NewsController::class, 'show'])->name('show');
    Route::put('/{news}', [NewsController::class, 'update'])->name('update');
    Route::delete('/{news}', [NewsController::class, 'destroy'])->name('delete');
});
