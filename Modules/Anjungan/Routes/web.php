<?php

// Anjungan > Daftar Anjungan
Route::group('anjungan', ['namespace' => 'Anjungan/Admin'], static function (): void {
    Route::get('/', 'Anjungan@index')->name('admin.anjungan.index');
    Route::get('/datatables', 'Anjungan@datatables')->name('admin.anjungan.datatables');
    Route::get('/form/{id?}', 'Anjungan@form')->name('admin.anjungan.form');
    Route::post('/insert', 'Anjungan@insert')->name('admin.anjungan.insert');
    Route::post('/update/{id?}', 'Anjungan@update')->name('admin.anjungan.update');
    Route::get('/delete/{id?}', 'Anjungan@delete')->name('admin.anjungan.delete');
    Route::post('/delete', 'Anjungan@delete')->name('admin.anjungan.delete-all');
    Route::get('/kunci/{id?}/{val?}', 'Anjungan@kunci')->name('admin.anjungan.kunci');
});

// Anjungan > Menu
Route::group('anjungan_menu', ['namespace' => 'Anjungan/Admin'], static function (): void {
    Route::get('/', 'Anjungan_menu@index')->name('anjungan_menu.index');
    Route::get('/datatables', 'Anjungan_menu@datatables')->name('anjungan_menu.datatables');
    Route::get('/form/{id?}', 'Anjungan_menu@form')->name('anjungan_menu.form');
    Route::post('/insert', 'Anjungan_menu@insert')->name('anjungan_menu.insert');
    Route::post('/update/{id?}', 'Anjungan_menu@update')->name('anjungan_menu.update');
    Route::get('/delete/{id?}', 'Anjungan_menu@delete')->name('anjungan_menu.delete');
    Route::post('/delete', 'Anjungan_menu@delete')->name('anjungan_menu.delete-all');
    Route::get('/lock/{id?}', 'Anjungan_menu@lock')->name('anjungan_menu.lock');
    Route::post('/tukar', 'Anjungan_menu@tukar')->name('anjungan_menu.tukar');
});

// Anjungan > Pengaturan
Route::group('anjungan_pengaturan', ['namespace' => 'Anjungan/Admin'], static function (): void {
    Route::get('/', 'Anjungan_pengaturan@index')->name('anjungan_pengaturan.index');
    Route::post('/update', 'Anjungan_pengaturan@update')->name('anjungan_pengaturan.update');
});

Route::group('anjungan-mandiri', ['namespace' => 'Anjungan'], static function (): void {
    Route::get('/', 'Anjungan@index')->name('anjungan.index');
    Route::get('/beranda', 'AnjunganBeranda@index')->name('anjungan.beranda.index');
    Route::get('/surat/{id?}', 'AnjunganSurat@buat')->name('anjungan.surat');
    Route::get('/surat/form/{id?}', 'AnjunganSurat@form')->name('anjungan.surat.form');
    Route::post('/surat/kirim', 'AnjunganSurat@kirim')->name('anjungan.surat.kirim');
    Route::get('/permohonan', 'AnjunganSurat@permohonan')->name('anjungan.permohonan');
});