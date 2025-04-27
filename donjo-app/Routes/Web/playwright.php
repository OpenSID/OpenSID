<?php

defined('BASEPATH') || exit('No direct script access allowed');

Route::group('playwright', static function (): void {
    Route::post('artisan', 'PlaywrightController@artisan')->name('playwright.artisan');
    Route::post('user', 'PlaywrightController@user')->name('playwright.user');
    Route::post('query', 'PlaywrightController@query')->name('playwright.query');
    Route::post('select', 'PlaywrightController@select')->name('playwright.select');
});