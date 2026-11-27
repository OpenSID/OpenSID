<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('analisis')->group(function () {
    Route::get('master', [\App\Http\Controllers\Api\Internal\AnalisisController::class, 'master'])->name('api.analisis.master');
    Route::match(['GET', 'POST'], 'indikator', [\App\Http\Controllers\Api\Internal\AnalisisController::class, 'indikator'])->name('api.analisis.indikator');
    Route::match(['GET', 'POST'], 'jawaban', [\App\Http\Controllers\Api\Internal\AnalisisController::class, 'jawaban'])->name('api.analisis.jawaban');
});
