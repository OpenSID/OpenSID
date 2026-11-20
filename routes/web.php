<?php

use Illuminate\Support\Facades\Route;

// Captcha
Route::get('captcha', function () {
    return (new App\Libraries\Captcha())->make();
})->name('captcha');
