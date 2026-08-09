<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\ServeFileController;
use Illuminate\Support\Facades\Route;

// Captcha
Route::get('captcha', function () {
    return (new App\Libraries\Captcha())->make();
})->name('captcha');

// Asset tema & modul (fallback default divalidasi ketat di controller)
Route::get('theme_asset/{theme}', [AssetController::class, 'serveTheme']);
Route::get('module_asset/{module}', [AssetController::class, 'serveModule']);

// File upload desa (signed URL)
Route::get('storage-desa', [ServeFileController::class, 'index'])
    ->name('storage.desa')
    ->middleware('signed');
