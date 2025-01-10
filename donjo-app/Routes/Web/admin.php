<?php

/*
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

// SITEMAN
Route::group('siteman', static function (): void {
    Route::get('/', 'auth/AuthenticatedSessionController@create');
    Route::post('/auth', 'auth/AuthenticatedSessionController@store');
    Route::get('/logout', 'auth/AuthenticatedSessionController@destroy');
    Route::get('/lupa_sandi', 'auth/PasswordResetLinkController@create');
    Route::post('/kirim_lupa_sandi', 'auth/PasswordResetLinkController@store');
    Route::get('/reset_kata_sandi/{token?}', 'auth/NewPasswordController@create');
    Route::post('/verifikasi_sandi', 'auth/NewPasswordController@store');
    Route::post('/matikan-captcha', 'auth/AuthenticatedSessionController@matikanCaptcha')->name('siteman.matikan-captcha');
});

// MAIN
Route::get('main', 'Main@index');

// Notif
Route::group('notif', static function (): void {
    Route::get('/', 'Notif@index');
    Route::post('/update_pengumuman', 'Notif@update_pengumuman')->name('notif.update_pengumuman');
    Route::post('/update_setting', 'Notif@update_setting')->name('notif.update_setting');
});

Route::group('pengguna', static function (): void {
    Route::post('/update', 'Pengguna@update')->name('pengguna.update');
    Route::post('/update_password', 'Pengguna@update_password')->name('pengguna.update_password');
    Route::match(['GET', 'POST'], '/kirim_verifikasi', 'Pengguna@kirim_verifikasi')->name('pengguna.kirim_verifikasi');
    Route::match(['GET', 'POST'], '/kirim_otp_telegram', 'Pengguna@kirim_otp_telegram')->name('pengguna.kirim_otp_telegram');
    Route::match(['GET', 'POST'], '/verifikasi_telegram', 'Pengguna@verifikasi_telegram')->name('pengguna.verifikasi_telegram');
    Route::match(['GET', 'POST'], '/verifikasi/{hash}', 'Pengguna@verifikasi')->name('pengguna.verifikasi');
    Route::match(['GET', 'POST'], '/', 'Pengguna@index')->name('pengguna.index');
});

// MODULE
// Beranda
Route::get('beranda', 'Beranda@index');

Route::group('periksa', static function (): void {
    Route::get('/', 'Periksa@index')->name('periksa.index');
    Route::match(['GET', 'POST'], '/perbaiki', 'Periksa@perbaiki')->name('periksa.perbaiki');
    Route::match(['GET', 'POST'], '/perbaiki_sebagian/{masalah?}', 'Periksa@perbaiki_sebagian')->name('periksa.perbaiki_sebagian');
    Route::get('/login', 'Periksa@login')->name('periksa.login');
    Route::post('/auth', 'Periksa@auth')->name('periksa.auth');
    Route::post('/tanggallahir', 'Periksa@tanggallahir')->name('periksa.tanggallahir');
    Route::post('suplemen_terdata', 'Periksa@suplemenTerdata')->name('periksa.suplemen_terdata');
});
Route::group('periksaKlasifikasiSurat', static function (): void {
    Route::get('/hapus', 'PeriksaKlasifikasiSurat@hapus')->name('periksaKlasifikasiSurat.hapus');
});
Route::group('periksaLogKeluarga', static function (): void {
    Route::get('/', 'PeriksaLogKeluarga@index')->name('periksaLogKeluarga.index');
    Route::post('/hapusLog', 'PeriksaLogKeluarga@hapusLog')->name('periksaLogKeluarga.hapusLog');
});
Route::group('periksaLogPenduduk', static function (): void {
    Route::get('/', 'PeriksaLogPenduduk@index')->name('periksaLogPenduduk.index');
    Route::post('/hapusLog', 'PeriksaLogPenduduk@hapusLog')->name('periksaLogPenduduk.hapusLog');
    Route::post('/updateStatusDasar', 'PeriksaLogPenduduk@updateStatusDasar')->name('periksaLogPenduduk.updateStatusDasar');
});
Route::group('periksaKepalaKeluargaGanda', static function (): void {
    Route::get('/', 'PeriksaKepalaKeluargaGanda@index')->name('periksaKepalaKeluargaGanda.index');
    Route::post('/ubahShdk', 'PeriksaKepalaKeluargaGanda@ubahShdk')->name('periksaKepalaKeluargaGanda.ubahShdk');
});

// Info Desa > Identitas Desa
Route::group('identitas_desa', static function (): void {
    Route::get('/', 'Identitas_desa@index')->name('identitas_desa.index');
    Route::get('/kosongkan', 'Identitas_desa@kosongkan')->name('identitas_desa.kosongkan');
    Route::get('/form', 'Identitas_desa@form')->name('identitas_desa.form');
    Route::post('/insert', 'Identitas_desa@insert')->name('identitas_desa.insert');
    Route::post('/update', 'Identitas_desa@update')->name('identitas_desa.update');
    Route::get('/maps/{tipe}', 'Identitas_desa@maps')->name('identitas_desa.maps');
    Route::post('/update_maps/{tipe}', 'Identitas_desa@update_maps')->name('identitas_desa.update_maps');
    Route::get('/reset', 'Identitas_desa@reset')->name('identitas_desa.reset');
});

// Info Desa > Wilayah Administratif
Route::group('wilayah', static function (): void {
    Route::get('/clear', static function (): void {
        redirect('wilayah');
    });
    Route::get('/datatables', 'Wilayah@datatables')->name('wilayah.datatables');
    Route::post('/tukar', 'Wilayah@tukar')->name('wilayah.tukar');
    Route::match(['GET', 'POST'], '/form_dusun/{level?}/{parent?}/{id?}', 'Wilayah@form_dusun')->name('wilayah.form_dusun');
    Route::match(['GET', 'POST'], '/form_rw/{level?}/{parent?}/{id?}', 'Wilayah@form_rw')->name('wilayah.form_rw');
    Route::match(['GET', 'POST'], '/form_rt/{level?}/{parent?}/{id?}', 'Wilayah@form_rt')->name('wilayah.form_rt');
    Route::get('/apipendudukwilayah', 'Wilayah@apipendudukwilayah')->name('wilayah.apipendudukwilayah');
    Route::get('/dialog/{aksi?}', 'Wilayah@dialog')->name('wilayah.dialog');
    Route::post('/daftar/{aksi?}', 'Wilayah@daftar')->name('wilayah.daftar');
    Route::match(['GET', 'POST'], '/index/{parent?}/{level?}', 'Wilayah@index')->name('wilayah.index-page');
    Route::match(['GET', 'POST'], '/', 'Wilayah@index')->name('wilayah.index');
    Route::post('/insert/{level?}/{parent?}', 'Wilayah@insert')->name('wilayah.insert');
    Route::post('/update/{level?}/{id?}/{parent?}', 'Wilayah@update')->name('wilayah.update');
    Route::get('/delete/{level?}/{id?}/{parent?}', 'Wilayah@delete')->name('wilayah.delete');
    Route::get('/cetak_rw/{id?}', 'Wilayah@cetak_rw')->name('wilayah.cetak_rw');
    Route::get('/unduh_rw/{id?}', 'Wilayah@unduh_rw')->name('wilayah.unduh_rw');
    Route::get('/cetak_rt/{id?}', 'Wilayah@cetak_rt')->name('wilayah.cetak_rt');
    Route::get('/unduh_rt/{id?}', 'Wilayah@unduh_rt')->name('wilayah.unduh_rt');
    Route::get('/ajax_kantor_dusun_maps/{id?}', 'Wilayah@ajax_kantor_dusun_maps')->name('wilayah.ajax_kantor_dusun_maps');
    Route::get('/ajax_wilayah_dusun_maps/{id?}', 'Wilayah@ajax_wilayah_dusun_maps')->name('wilayah.ajax_wilayah_dusun_maps');
    Route::get('/ajax_kantor_rw_maps/{id?}/{dusun?}', 'Wilayah@ajax_kantor_rw_maps')->name('wilayah.ajax_kantor_rw_maps');
    Route::get('/ajax_wilayah_rw_maps/{id?}/{dusun?}', 'Wilayah@ajax_wilayah_rw_maps')->name('wilayah.ajax_wilayah_rw_maps');
    Route::get('/ajax_kantor_rt_maps/{id?}/{rw?}', 'Wilayah@ajax_kantor_rt_maps')->name('wilayah.ajax_kantor_rt_maps');
    Route::get('/ajax_wilayah_rt_maps/{id?}/{rw?}', 'Wilayah@ajax_wilayah_rt_maps')->name('wilayah.ajax_wilayah_rt_maps');
    Route::post('/update_kantor_map/{level?}/{id?}/{parent?}', 'Wilayah@update_kantor_map')->name('wilayah.update_kantor_map');
    Route::post('/update_wilayah_map/{level?}/{id?}/{parent?}', 'Wilayah@update_wilayah_map')->name('wilayah.update_wilayah_map');
    Route::get('/kosongkan/{id?}', 'Wilayah@kosongkan')->name('wilayah.kosongkan');
    Route::get('/list_rw/{dusun?}', 'Wilayah@list_rw')->name('wilayah.list_rw');
    Route::get('/list_rt/{dusun?}/{rw?}', 'Wilayah@list_rt')->name('wilayah.list_rt');
    Route::post('/ubah_lokasi_peta/{wilayah?}/{to?}/{msg?}', 'Wilayah@ubah_lokasi_peta')->name('wilayah.ubah_lokasi_peta');
    Route::get('/warga/{id?}', 'Wilayah@warga')->name('wilayah.warga');
    Route::get('/warga_kk/{id?}', 'Wilayah@warga_kk')->name('wilayah.warga_kk');
    Route::get('/warga_l/{id?}', 'Wilayah@warga_l')->name('wilayah.warga_l');
    Route::get('/warga_p/{id?}', 'Wilayah@warga_p')->name('wilayah.warga_p');
});

// Info Desa > Status Desa
Route::group('status_desa', static function (): void {
    Route::get('/', 'Status_desa@index')->name('status_desa.index');
    Route::post('/', 'Status_desa@index')->name('status_desa.index_post');
    Route::get('/perbarui_idm/{tahun}', 'Status_desa@perbarui_idm')->name('status_desa.perbarui_idm');
    Route::get('/simpan/{tahun}', 'Status_desa@simpan')->name('status_desa.simpan');
    Route::post('/perbarui_bps', 'Status_desa@perbarui_bps')->name('status_desa.perbarui_bps');
    Route::get('/perbarui_sdgs', 'Status_desa@perbarui_sdgs')->name('status_desa.perbarui_sdgs');
    Route::get('/navigasi/{navigasi}', 'Status_desa@navigasi')->name('status_desa.navigasi');
});

// Kependudukan > Penduduk
Route::group('penduduk', static function (): void {
    Route::get('', 'Penduduk@index');
    Route::get('clear', static function (): void {
        redirect('penduduk');
    });
    Route::get('datatables', 'Penduduk@datatables')->name('penduduk.datatables');
    Route::get('list_nik_ajax', 'Penduduk@list_nik_ajax')->name('penduduk.list_nik_ajax');
    Route::get('ambil_foto', 'Penduduk@ambil_foto')->name('penduduk.ambil_foto');
    Route::get('form_peristiwa/{periswita?}', 'Penduduk@form_peristiwa')->name('penduduk.form_peristiwa');
    Route::get('form/{id?}', 'Penduduk@form')->name('penduduk.form');
    Route::get('detail/{id}', 'Penduduk@detail')->name('penduduk.detail');
    Route::get('dokumen/{id?}', 'Penduduk@dokumen')->name('penduduk.dokumen');
    Route::get('dokumen_datatables', 'Penduduk@dokumen_datatables')->name('penduduk.dokumen_datatables');
    Route::get('dokumen_form/{id?}/{id_dokumen?}', 'Penduduk@dokumen_form')->name('penduduk.dokumen_form');
    Route::get('dokumen_list/{id?}', 'Penduduk@dokumen_list')->name('penduduk.dokumen_list');
    Route::post('dokumen_insert', 'Penduduk@dokumen_insert')->name('penduduk.dokumen_insert');
    Route::post('dokumen_update/{id?}', 'Penduduk@dokumen_update')->name('penduduk.dokumen_update');
    Route::match(['GET', 'POST'], '/delete_dokumen/{id_pend?}/{id?}', 'Penduduk@delete_dokumen')->name('penduduk.delete_dokumen');
    Route::get('cetak_biodata/{id?}', 'Penduduk@cetak_biodata')->name('penduduk.cetak_biodata');
    Route::post('insert/{peristiwa}', 'Penduduk@insert')->name('penduduk.insert');
    Route::post('update/{id?}', 'Penduduk@update')->name('penduduk.update');
    Route::get('delete/{id?}', 'Penduduk@delete')->name('penduduk.delete');
    Route::post('delete_all/{p?}/{o?}', 'Penduduk@delete_all')->name('penduduk.delete_all');
    Route::get('ajax_adv_search', 'Penduduk@ajax_adv_search')->name('penduduk.ajax_adv_search');
    Route::post('adv_search_proses', 'Penduduk@adv_search_proses')->name('penduduk.adv_search_proses');
    // Route::get('ajax_penduduk_pindah_rw/{dusun?}', 'Penduduk@ajax_penduduk_pindah_rw')->name('penduduk.ajax_penduduk_pindah_rw');
    // Route::get('ajax_penduduk_pindah_rt/{dusun?}/{rw?}', 'Penduduk@ajax_penduduk_pindah_rt')->name('penduduk.ajax_penduduk_pindah_rt');
    Route::get('ajax_penduduk_maps/{id?}/{edit?}', 'Penduduk@ajax_penduduk_maps')->name('penduduk.ajax_penduduk_maps');
    Route::post('update_maps/{id?}/{edit?}', 'Penduduk@update_maps')->name('penduduk.update_maps');
    Route::get('edit_status_dasar/{id?}', 'Penduduk@edit_status_dasar')->name('penduduk.edit_status_dasar');
    Route::post('update_status_dasar/{id?}', 'Penduduk@update_status_dasar')->name('penduduk.update_status_dasar');
    Route::get('kembalikan_status/{id?}', 'Penduduk@kembalikan_status')->name('penduduk.kembalikan_status');
    Route::post('cetak/{aksi?}/{privasi_nik?}', 'Penduduk@cetak')->name('penduduk.cetak');
    Route::get('statistik/{tipe?}/{nomor?}/{sex?}', 'Penduduk@statistik')->name('penduduk.statistik');
    Route::get('lap_statistik/{id_cluster?}/{tipe?}/{no?}', 'Penduduk@lap_statistik')->name('penduduk.lap_statistik');
    Route::get('search_kumpulan_nik', 'Penduduk@search_kumpulan_nik')->name('penduduk.search_kumpulan_nik');
    Route::get('ajax_cetak/{aksi?}', 'Penduduk@ajax_cetak')->name('penduduk.ajax_cetak');
    Route::get('program_bantuan', 'Penduduk@program_bantuan')->name('penduduk.program_bantuan');
    Route::post('program_bantuan_proses', 'Penduduk@program_bantuan_proses')->name('penduduk.program_bantuan_proses');
    Route::get('unduh_berkas/{id_dokumen?}/{tampil?}', 'Penduduk@unduh_berkas')->name('penduduk.unduh_berkas');
    Route::get('impor', 'Penduduk@impor')->name('penduduk.impor');
    Route::post('proses_impor', 'Penduduk@proses_impor')->name('penduduk.proses_impor');
    Route::get('impor_bip', 'Penduduk@impor_bip')->name('penduduk.impor_bip');
    Route::post('proses_impor_bip', 'Penduduk@proses_impor_bip')->name('penduduk.proses_impor_bip');
    Route::get('ekspor/{huruf?}', 'Penduduk@ekspor')->name('penduduk.ekspor');
    Route::get('foto_bawaan/{id}', 'Penduduk@foto_bawaan')->name('penduduk.foto_bawaan');
});

// Kependudukan > Penduduk > Log Penduduk
Route::group('penduduk_log', static function (): void {
    Route::get('clear', 'Penduduk_log@index')->name('penduduk_log.clear');
    Route::get('', 'Penduduk_log@index')->name('penduduk_log.index');
    Route::get('index', 'Penduduk_log@index');
    Route::get('datatables', 'Penduduk_log@datatables')->name('penduduk_log.datatables');
    Route::get('edit/{id}', 'Penduduk_log@edit')->name('penduduk_log.edit');
    Route::post('update/{id}', 'Penduduk_log@update')->name('penduduk_log.update');
    Route::get('kembalikan_status/{id}', 'Penduduk_log@kembalikan_status')->name('penduduk_log.kembalikan_status');
    Route::get('ajax_kembalikan_status_pergi/{id}', 'Penduduk_log@ajax_kembalikan_status_pergi')->name('penduduk_log.ajax_kembalikan_status_pergi');
    Route::post('kembalikan_status_pergi/{id}', 'Penduduk_log@kembalikan_status_pergi')->name('penduduk_log.kembalikan_status_pergi');
    Route::post('kembalikan_status_all', 'Penduduk_log@kembalikan_status_all')->name('penduduk_log.kembalikan_status_all');
    Route::post('cetak/{aksi}/{privasi_nik}', 'Penduduk_log@cetak')->name('penduduk_log.cetak');
    Route::get('ajax_cetak/{aksi}', 'Penduduk_log@ajax_cetak')->name('penduduk_log.ajax_cetak');
    Route::get('statistik/{tipe}/{nomor}/{sex}', 'Penduduk_log@statistik')->name('penduduk_log.statistik');
    Route::get('dokumen/{id}', 'Penduduk_log@dokumen')->name('penduduk_log.dokumen');
});

// Kependudukan > Keluarga
Route::group('keluarga', static function (): void {
    Route::get('', 'Keluarga@index');
    Route::get('/clear', static function (): void {
        redirect('keluarga');
    });
    Route::get('datatables', 'Keluarga@datatables')->name('keluarga.datatables');
    Route::get('list_kk_ajax', 'Keluarga@list_kk_ajax')->name('keluarga.list_kk_ajax');
    Route::post('cetak/{aksi?}/{privasi_kk?}', 'Keluarga@cetak')->name('keluarga.cetak');
    Route::get('form', 'Keluarga@form')->name('keluarga.form');
    Route::get('form_peristiwa/{peristiwa}/{id?}', 'AnggotaKeluarga@form')->name('keluarga.form_peristiwa');
    Route::get('edit_nokk/{id?}', 'Keluarga@edit_nokk')->name('keluarga.edit_nokk');
    Route::get('add_exist/{id?}', 'Keluarga@add_exist')->name('keluarga.add_exist');
    Route::get('pindah_kolektif', 'Keluarga@pindah_kolektif')->name('keluarga.pindah_kolektif');
    Route::match(['GET', 'POST'], '/proses_pindah', 'Keluarga@proses_pindah')->name('keluarga.proses_pindah');
    Route::post('insert/{id?}', 'Keluarga@insert')->name('keluarga.insert');
    Route::match(['GET', 'POST'], 'insert_anggota', 'AnggotaKeluarga@insert')->name('keluarga.insert_anggota');
    Route::match(['GET', 'POST'], '/insert_new', 'Keluarga@insert_new')->name('keluarga.insert_new');
    Route::post('update_nokk/{id?}', 'Keluarga@update_nokk')->name('keluarga.update_nokk');
    Route::match(['GET', 'POST'], 'delete/{id?}', 'Keluarga@delete')->name('keluarga.delete');
    Route::post('delete_all', 'Keluarga@delete_all')->name('keluarga.delete_all');
    Route::get('anggota/{id}', 'AnggotaKeluarga@index')->name('keluarga.anggota');
    Route::get('ajax_add_anggota/{id?}', 'AnggotaKeluarga@ajax_add_anggota')->name('keluarga.ajax_add_anggota');
    Route::get('edit_anggota/{id_kk?}/{id?}', 'AnggotaKeluarga@edit_anggota')->name('keluarga.edit_anggota');
    Route::get('kartu_keluarga/{id?}', 'Keluarga@kartu_keluarga')->name('keluarga.kartu_keluarga');
    Route::match(['GET', 'POST'], 'cetak_kk/{id?}', 'Keluarga@cetak_kk')->name('keluarga.cetak_kk');
    Route::match(['GET', 'POST'], 'doc_kk/{id?}', 'Keluarga@doc_kk')->name('keluarga.doc_kk');
    Route::post('add_anggota/{id?}', 'AnggotaKeluarga@add_anggota')->name('keluarga.add_anggota');
    Route::post('update_anggota/{id_kk?}/{id?}', 'AnggotaKeluarga@update_anggota')->name('keluarga.update_anggota');
    Route::get('delete_anggota/{kk?}/{id?}', 'AnggotaKeluarga@delete_anggota')->name('keluarga.delete_anggota');
    Route::get('keluarkan_anggota/{kk?}/{id?}', 'AnggotaKeluarga@keluarkan_anggota')->name('keluarga.keluarkan_anggota');
    Route::get('statistik/{tipe?}/{nomor?}/{sex?}', 'Keluarga@statistik')->name('keluarga.statistik');
    Route::get('search_kumpulan_kk', 'Keluarga@search_kumpulan_kk')->name('keluarga.search_kumpulan_kk');
    Route::get('ajax_cetak/{aksi?}', 'Keluarga@ajax_cetak')->name('keluarga.ajax_cetak');
    Route::get('program_bantuan', 'Keluarga@program_bantuan')->name('keluarga.program_bantuan');
    Route::post('program_bantuan_proses', 'Keluarga@program_bantuan_proses')->name('keluarga.program_bantuan_proses');
    Route::get('nokk_sementara', 'Keluarga@nokk_sementara')->name('keluarga.nokk_sementara');
    Route::get('form_pecah_semua/{id?}', 'Keluarga@form_pecah_semua')->name('keluarga.form_pecah_semua');
    Route::match(['GET', 'POST'], '/pecah_semua/{id?}', 'Keluarga@pecah_semua')->name('keluarga.pecah_semua');
});

// Kependudukan > Rumah Tangga
Route::group('rtm', static function (): void {
    Route::get('/clear', static function (): void {
        redirect('rtm');
    });
    Route::get('/', 'Rtm@index')->name('rtm.index');
    Route::get('index', 'Rtm@index')->name('rtm.index-default');
    Route::get('datatables', 'Rtm@datatables')->name('rtm.datatables');
    Route::post('insert', 'Rtm@insert')->name('rtm.insert');
    Route::post('update/{id}', 'Rtm@update')->name('rtm.update');
    Route::match(['GET', 'POST'], '/delete/{id?}', 'Rtm@delete')->name('rtm.delete');
    Route::get('apipendudukrtm', 'Rtm@apipendudukrtm')->name('rtm.apipendudukrtm');
    Route::get('form/{id?}', 'Rtm@form')->name('rtm.form');
    Route::get('ajax_cetak/{aksi?}', 'Rtm@ajax_cetak')->name('rtm.ajax_cetak');
    Route::post('cetak/{aksi?}/{privasi_nik?}', 'Rtm@cetak')->name('rtm.cetak');
    Route::get('edit_nokk/{id?}', 'Rtm@edit_nokk')->name('rtm.edit_nokk');
    Route::post('update_nokk/{id?}', 'Rtm@update_nokk')->name('rtm.update_nokk');
    Route::get('anggota/{id?}', 'Rtm@anggota')->name('rtm.anggota');
    Route::get('ajax_add_anggota/{id?}', 'Rtm@ajax_add_anggota')->name('rtm.ajax_add_anggota');
    Route::get('datables_anggota/{id?}', 'Rtm@datables_anggota')->name('rtm.datables_anggota');
    Route::get('edit_anggota/{id_rtm?}/{id?}', 'Rtm@edit_anggota')->name('rtm.edit_anggota');
    Route::get('kartu_rtm/{id?}', 'Rtm@kartu_rtm')->name('rtm.kartu_rtm');
    Route::get('cetak_kk/{id?}', 'Rtm@cetak_kk')->name('rtm.cetak_kk');
    Route::post('add_anggota/{id?}', 'Rtm@add_anggota')->name('rtm.add_anggota');
    Route::post('update_anggota/{id_rtm?}/{id?}', 'Rtm@update_anggota')->name('rtm.update_anggota');
    Route::get('delete_anggota/{kk?}/{id?}', 'Rtm@delete_anggota')->name('rtm.delete_anggota');
    Route::post('delete_all_anggota/{kk?}', 'Rtm@delete_all_anggota')->name('rtm.delete_all_anggota');
    Route::get('statistik/{tipe?}/{no?}/{sex?}', 'Rtm@statistik')->name('rtm.statistik');
    Route::post('impor', 'Rtm@impor')->name('rtm.impor');
});

// Identitas Desa > Lembaga atau Kependudukan > Kelompok
Route::match(['GET', 'POST'], 'kelompok_anggota/anggota/{id_penduduk?}', 'Kelompok_anggota@anggota')->name('kelompok_anggota.anggota');

foreach (['lembaga' => 'Lembaga', 'kelompok' => 'Kelompok'] as $key => $value) {
    Route::group($key, static function () use ($key, $value): void {
        Route::get('/apipendudukkelompok', "{$value}@apipendudukkelompok")->name("{$key}.apipendudukkelompok");
        Route::get('/to_master/{id?}', "{$value}@to_master")->name("{$key}.to_master");
        Route::get('/clear', "{$value}@clear")->name("{$key}.clear");
        Route::get('/form/{p?}/{o?}/{id?}', "{$value}@form")->name("{$key}.form");
        Route::get('/aksi/{aksi?}/{id?}', "{$value}@aksi")->name("{$key}.aksi");
        Route::get('/dialog/{aksi?}', "{$value}@dialog")->name("{$key}.dialog");
        Route::post('/daftar/{aksi?}', "{$value}@daftar")->name("{$key}.daftar");
        Route::post('/filter/{filter}', "{$value}@filter")->name("{$key}.filter");
        Route::post('/insert', "{$value}@insert")->name("{$key}.insert");
        Route::post('/update/{p?}/{o?}/{id?}', "{$value}@update")->name("{$key}.update");
        Route::get('/delete/{id?}', "{$value}@delete")->name("{$key}.delete");
        Route::post('/delete_all', "{$value}@delete_all")->name("{$key}.delete_all");
        Route::get('/statistik/{tipe?}/{nomor?}/{sex?}', "{$value}@statistik")->name("{$key}.statistik");
        Route::match(['GET', 'POST'], '/index', "{$value}@index");
        Route::match(['GET', 'POST'], '/index/{p?}/{o?}', "{$value}@index");
        Route::match(['GET', 'POST'], '/index/{p?}', "{$value}@index");
        Route::match(['GET', 'POST'], '/', "{$value}@index");
    });

    Route::group("{$key}_master", static function () use ($key, $value): void {
        Route::get('/', "{$value}_master@index")->name("{$key}_master.index");
        Route::get('/datatables', "{$value}_master@datatables")->name("{$key}_master.datatables");
        Route::get('/form/{id?}', "{$value}_master@form")->name("{$key}_master.form");
        Route::post('/insert', "{$value}_master@insert")->name("{$key}_master.insert");
        Route::post('/update/{id?}', "{$value}_master@update")->name("{$key}_master.update");
        Route::get('/delete/{id?}', "{$value}_master@delete")->name("{$key}_master.delete");
        Route::post('/delete_all/{id_kelompok?}', "{$value}_master@delete_all")->name("{$key}_master.delete_all");
    });

    Route::group("{$key}_anggota", static function () use ($key, $value): void {
        Route::get('/detail/{id?}', "{$value}_anggota@detail")->name("{$key}_anggota.detail");
        Route::get('/aksi/{aksi?}/{id?}', "{$value}_anggota@aksi")->name("{$key}_anggota.aksi");
        Route::get('/datatables', "{$value}_anggota@datatables")->name("{$key}_anggota.datatables");
        Route::get('/form/{id_kelompok?}/{id?}', "{$value}_anggota@form")->name("{$key}_anggota.form");
        Route::post('/insert/{id?}', "{$value}_anggota@insert")->name("{$key}_anggota.insert");
        Route::post('/update/{id_kelompok?}/{id?}', "{$value}_anggota@update")->name("{$key}_anggota.update");
        Route::get('/delete/{id_kelompok?}/{id?}', "{$value}_anggota@delete")->name("{$key}_anggota.delete");
        Route::get('/dialog/{aksi?}/{id?}', "{$value}_anggota@dialog")->name("{$key}_anggota.dialog");
        Route::post('/daftar/{aksi?}/{id?}', "{$value}_anggota@daftar")->name("{$key}_anggota.daftar");
        Route::post('/delete_all/{id_kelompok?}', "{$value}_anggota@delete_all")->name("{$key}_anggota.delete_all");
    });
}

// Kependudukan > Data Suplemen
Route::group('suplemen', static function (): void {
    Route::get('/clear', static function (): void {
        redirect('suplemen');
    });
    Route::get('/', 'Suplemen@index')->name('suplemen.index');
    Route::get('/datatables', 'Suplemen@datatables')->name('suplemen.datatables');
    Route::get('/form/{id?}', 'Suplemen@form')->name('suplemen.form');
    Route::post('/create', 'Suplemen@create')->name('suplemen.create');
    Route::post('/update/{id}', 'Suplemen@update')->name('suplemen.update');
    Route::get('/delete/{id}', 'Suplemen@delete')->name('suplemen.delete');
    Route::get('/rincian/{id}', 'Suplemen@rincian')->name('suplemen.rincian');
    Route::get('/datatables_terdata', 'Suplemen@datatables_terdata')->name('suplemen.datatables_terdata');
    Route::match(['GET', 'POST'], '/form_terdata/{suplemen}/{aksi}/{id?}', 'Suplemen@form_terdata')->name('suplemen.form_terdata');
    Route::post('/create_terdata/{aksi}', 'Suplemen@create_terdata')->name('suplemen.create_terdata');
    Route::post('/update_terdata/{id}', 'Suplemen@update_terdata')->name('suplemen.update_terdata');
    Route::get('/delete_terdata/{id}', 'Suplemen@delete_terdata')->name('suplemen.delete_terdata');
    Route::post('/delete_all_terdata', 'Suplemen@delete_all_terdata')->name('suplemen.delete_all_terdata');
    Route::get('/apipenduduksuplemen', 'Suplemen@apipenduduksuplemen')->name('suplemen.apipenduduksuplemen');
    Route::get('/dialog_daftar/{id}/{aksi}', 'Suplemen@dialog_daftar')->name('suplemen.dialog_daftar');
    Route::post('/daftar/{id}/{aksi}', 'Suplemen@daftar')->name('suplemen.daftar');
    Route::get('/impor_data/{id}', 'Suplemen@impor_data')->name('suplemen.impor_data');
    Route::post('/impor', 'Suplemen@impor')->name('suplemen.impor');
    Route::get('/ekspor/{id}', 'Suplemen@ekspor')->name('suplemen.ekspor');
});

// Kependudukan > Calon Pemilih
Route::group('dpt', static function (): void {
    Route::get('/clear', static function (): void {
        redirect('dpt');
    });
    Route::get('/', 'Dpt@index')->name('dpt.index');
    Route::get('/datatables', 'Dpt@datatables')->name('dpt.datatables');
    Route::get('/ajax_cetak/{aksi?}', 'Dpt@ajax_cetak')->name('dpt.ajax_cetak');
    Route::post('/cetak/{aksi?}/{privasi_nik?}', 'Dpt@cetak')->name('dpt.cetak');
});

// Pemilihan
Route::group('pemilihan', static function (): void {
    Route::get('/', 'Pemilihan@index')->name('pemilihan.index');
    Route::get('/datatables', 'Pemilihan@datatables')->name('pemilihan.datatables');
    Route::get('/form/{id?}', 'Pemilihan@form')->name('pemilihan.form');
    Route::post('/insert', 'Pemilihan@insert')->name('pemilihan.insert');
    Route::post('/update/{id}', 'Pemilihan@update')->name('pemilihan.update');
    Route::get('/status/{id?}', 'Pemilihan@status')->name('pemilihan.status');
    Route::get('/delete/{id}', 'Pemilihan@delete')->name('pemilihan.delete');
    Route::post('/delete_all', 'Pemilihan@delete_all')->name('pemilihan.delete_all');
});

// Statistik > Statistik Kependudukan
Route::group('statistik', static function (): void {

    Route::get('', 'Statistik@index')->name('statistik.index');

    Route::get('clear', static function (): void {
        redirect('statistik');
    });

    // Statistik Penduduk
    Route::group('penduduk/{id}', static function (): void {
        Route::get('', 'Statistik@index')->name('statistik.penduduk.index');
        Route::get('datatables', 'Statistik@datatables')->name('statistik.penduduk.datatables');
        Route::post('cetak/{aksi?}', 'Statistik@cetak')->name('statistik.penduduk.cetak');
        Route::get('dialog/{aksi?}', 'Statistik@dialog')->name('statistik.penduduk.dialog');
        Route::post('daftar/{aksi?}/{lap?}', 'Statistik@daftar')->name('statistik.penduduk.daftar');
    });

    // Statistik RTM
    Route::group('rtm/{id}', static function (): void {
        Route::post('cetak/{aksi?}', 'Statistik@cetak')->name('statistik.rtm.cetak_rtm');
        Route::get('dialog/{aksi?}', 'Statistik@dialog')->name('statistik.rtm.dialog_rtm');
    });

    // Statistik Keluarga
    Route::group('keluarga/{id}', static function (): void {
        Route::get('', 'Statistik@index')->name('statistik.keluarga.index');
        Route::get('datatables', 'Statistik@datatables')->name('statistik.keluarga.datatables');
        Route::post('cetak/{aksi?}', 'Statistik@cetak')->name('statistik.keluarga.cetak');
        Route::get('dialog/{aksi?}', 'Statistik@dialog')->name('statistik.keluarga.dialog');
        Route::post('daftar/{aksi?}/{lap?}', 'Statistik@daftar')->name('statistik.keluarga.daftar');
    });

    // Statistik Bantuan
    Route::group('bantuan/{id}', static function (): void {
        Route::get('', 'Statistik_bantuan@index')->name('statistik.bantuan.program.index');
        Route::get('datatables', 'Statistik_bantuan@datatables')->name('statistik.bantuan.datatables');
        Route::get('dialog/{tipe}/{aksi}', 'Statistik_bantuan@dialog')->name('statistik.bantuan.dialog');
        Route::post('cetak/{tipe}/{aksi}', 'Statistik_bantuan@cetak')->name('statistik.bantuan.cetak');
        Route::get('peserta_datatables', 'Statistik_bantuan@peserta_datatables')->name('statistik.bantuan.peserta_datatables');
    });

    // Rentang Umur
    Route::group('rentang_umur', static function (): void {
        Route::get('', 'Rentang_umur@index')->name('statistik.rentang_umur');
        Route::get('/datatables', 'Rentang_umur@datatables')->name('statistik.rentang_umur.datatables');
        Route::get('/form/{id?}', 'Rentang_umur@form')->name('statistik.rentang_umur.form');
        Route::post('/insert', 'Rentang_umur@insert')->name('statistik.rentang_umur.insert');
        Route::post('/update/{id?}', 'Rentang_umur@update')->name('statistik.rentang_umur.update');
        Route::get('/delete/{id}', 'Rentang_umur@delete')->name('statistik.rentang_umur.delete');
        Route::post('/delete_all', 'Rentang_umur@delete_all')->name('statistik.rentang_umur.delete_all');
    });
});

// Statistik > Laporan Bulanan
Route::group('laporan', static function (): void {
    Route::get('/', 'Laporan@index')->name('laporan.index');
    Route::get('/clear', 'Laporan@clear')->name('laporan.clear');
    Route::get('/dialog/{aksi}', 'Laporan@dialog')->name('laporan.dialog');
    Route::post('/cetak/{cetak}', 'Laporan@cetak')->name('laporan.cetak');
    Route::post('/bulan', 'Laporan@bulan')->name('laporan.bulan');
    Route::get('/detail_penduduk/{rincian}/{tipe}', 'Laporan@detail_penduduk')->name('laporan.detail_penduduk');
    Route::get('/detail_dialog/{aksi?}/{rincian?}/{tipe?}', 'Laporan@detail_dialog')->name('laporan.detail_dialog');
    Route::post('/detail_cetak/{aksi?}/{rincian?}/{tipe?}', 'Laporan@detail_cetak')->name('laporan.detail_cetak');
});

// Statistik > Laporan Kelompok Rentan
Route::group('laporan_rentan', static function (): void {
    Route::get('/', 'Laporan_rentan@index')->name('laporan_rentan.index');
    Route::get('/clear', 'Laporan_rentan@clear')->name('laporan_rentan.clear');
    Route::get('/cetak/{aksi}', 'Laporan_rentan@cetak')->name('laporan_rentan.cetak');
    Route::post('/dusun', 'Laporan_rentan@dusun')->name('laporan_rentan.dusun');
});

// Statistik > Laporan Penduduk
Route::group('laporan_penduduk', static function (): void {
    Route::get('/', 'Laporan_penduduk@index')->name('laporan_penduduk.index');
    Route::post('/datatables', 'Laporan_penduduk@datatables')->name('laporan_penduduk.datatables');
    Route::get('/form/{id?}', 'Laporan_penduduk@form')->name('laporan_penduduk.form');
    Route::post('/insert', 'Laporan_penduduk@insert')->name('laporan_penduduk.insert');
    Route::post('/update/{id}', 'Laporan_penduduk@update')->name('laporan_penduduk.update');
    Route::match(['GET', 'POST'], '/delete', 'Laporan_penduduk@delete')->name('laporan_penduduk.delete');
    Route::get('/unduh/{id?}', 'Laporan_penduduk@unduh')->name('laporan_penduduk.unduh');
    Route::post('/kirim', 'Laporan_penduduk@kirim')->name('laporan_penduduk.kirim');
});

// Kesehatan > Pendataan & Pemantauan Covid-19
Route::group('covid19', static function (): void {
    // Pendataan
    Route::get('/', 'Covid19@index')->name('covid19.index');
    Route::get('/data_pemudik/{page?}', 'Covid19@data_pemudik')->name('covid19.data_pemudik');
    Route::match(['GET', 'POST'], '/form_pemudik', 'Covid19@form_pemudik')->name('covid19.form_pemudik');
    Route::get('/apipendudukpemudik', 'Covid19@apipendudukpemudik')->name('covid19.apipendudukpemudik');
    Route::post('/insert_penduduk', 'Covid19@insert_penduduk')->name('covid19.insert_penduduk');
    Route::post('/add_pemudik', 'Covid19@add_pemudik')->name('covid19.add_pemudik');
    Route::get('/hapus_pemudik/{id}', 'Covid19@hapus_pemudik')->name('covid19.hapus_pemudik');
    Route::post('/edit_pemudik_form/{id}', 'Covid19@edit_pemudik_form')->name('covid19.edit_pemudik_form');
    Route::post('/edit_pemudik/{id}', 'Covid19@edit_pemudik')->name('covid19.edit_pemudik');
    Route::get('/detil_pemudik/{id}', 'Covid19@detil_pemudik')->name('covid19.detil_pemudik');
    Route::post('/update_penduduk/{id_pend}/{id_pemudik}', 'Covid19@update_penduduk')->name('covid19.update_penduduk');
    // Pemantauan
    Route::get('/pantau/{page?}/{tgl?}/{nik?}', 'Covid19@pantau')->name('covid19.pantau');
    Route::post('/add_pantau', 'Covid19@add_pantau')->name('covid19.add_pantau');
    Route::get('/hapus_pantau/{id?}/{page?}/{plus?}', 'Covid19@hapus_pantau')->name('covid19.hapus_pantau');
    Route::get('/daftar/{aksi?}/{tgl?}/{nik?}', 'Covid19@daftar')->name('covid19.daftar');
});

// Kesehatan > Vaksin
Route::group('vaksin_covid', static function (): void {
    Route::post('/filter/{filter?}/{return?}', 'Vaksin_covid@filter')->name('vaksin_covid.filter');
    Route::post('/search', 'Vaksin_covid@search')->name('vaksin_covid.search');
    Route::get('/clear/{return?}', 'Vaksin_covid@clear')->name('vaksin_covid.clear');
    Route::get('/form', 'Vaksin_covid@form')->name('vaksin_covid.form');
    Route::get('/apipendudukvaksin', 'Vaksin_covid@apipendudukvaksin')->name('vaksin_covid.apipendudukvaksin');
    Route::get('/tampil_sertifikat/{id_penduduk}', 'Vaksin_covid@tampil_sertifikat')->name('vaksin_covid.tampil_sertifikat');
    Route::get('/berkas_vaksin/{id_penduduk}/{vaksin}', 'Vaksin_covid@berkas_vaksin')->name('vaksin_covid.berkas_vaksin');
    Route::post('/update', 'Vaksin_covid@update')->name('vaksin_covid.update');
    Route::match(['GET', 'POST'], '/laporan_penduduk/{p?}', 'Vaksin_covid@laporan_penduduk')->name('vaksin_covid.laporan_penduduk');
    Route::post('/laporan_penduduk_cetak/{aksi}', 'Vaksin_covid@laporan_penduduk_cetak')->name('vaksin_covid.laporan_penduduk_cetak');
    Route::get('/laporan_rekap', 'Vaksin_covid@laporan_rekap')->name('vaksin_covid.laporan_rekap');
    Route::post('/laporan_rekap_cetak/{aksi}', 'Vaksin_covid@laporan_rekap_cetak')->name('vaksin_covid.laporan_rekap_cetak');
    Route::post('/rekap/{penduduk}', 'Vaksin_covid@rekap')->name('vaksin_covid.rekap');
    Route::post('/autocomplete', 'Vaksin_covid@autocomplete')->name('vaksin_covid.autocomplete');
    Route::post('/impor', 'Vaksin_covid@impor')->name('vaksin_covid.impor');
    Route::match(['GET', 'POST'], '/index', 'Vaksin_covid@index');
    Route::match(['GET', 'POST'], '/index/{p?}', 'Vaksin_covid@index');
    Route::match(['GET', 'POST'], '/', 'Vaksin_covid@index');
});

// Kesehatan > Stunting
Route::group('stunting', static function (): void {
    // Posyandu
    Route::get('/', 'Stunting@index')->name('stunting.index');
    Route::get('/datatablesPosyandu', 'Stunting@datatablesPosyandu')->name('stunting.datatablesPosyandu');
    Route::get('/formPosyandu/{id?}', 'Stunting@formPosyandu')->name('stunting.formPosyandu');
    Route::post('/insertPosyandu', 'Stunting@insertPosyandu')->name('stunting.insertPosyandu');
    Route::post('/updatePosyandu/{id?}', 'Stunting@updatePosyandu')->name('stunting.updatePosyandu');
    Route::get('/deletePosyandu/{id}', 'Stunting@deletePosyandu')->name('stunting.deletePosyandu');
    Route::post('/deleteAllPosyandu', 'Stunting@deleteAllPosyandu')->name('stunting.deleteAllPosyandu');
    // KIA
    Route::get('/kia', 'Stunting@kia')->name('stunting.kia');
    Route::get('/datatablesKia', 'Stunting@datatablesKia')->name('stunting.datatablesKia');
    Route::match(['GET', 'POST'], '/formKia/{id?}', 'Stunting@formKia')->name('stunting.formKia');
    Route::get('/getIbu', 'Stunting@getIbu')->name('stunting.getIbu');
    Route::get('/getAnak', 'Stunting@getAnak')->name('stunting.getAnak');
    Route::post('/insertKia', 'Stunting@insertKia')->name('stunting.insertKia');
    Route::post('/updateKia/{id?}', 'Stunting@updateKia')->name('stunting.updateKia');
    Route::get('/deleteKia/{id}', 'Stunting@deleteKia')->name('stunting.deleteKia');
    Route::post('/deleteAllKia', 'Stunting@deleteAllKia')->name('stunting.deleteAllKia');
    // Pemantauan Ibu Hamil
    Route::get('/pemantauan_ibu_hamil', 'Stunting@pemantauan_ibu_hamil')->name('stunting.pemantauan_ibu_hamil');
    Route::get('/datatablesIbuHamil', 'Stunting@datatablesIbuHamil')->name('stunting.datatablesIbuHamil');
    Route::match(['GET', 'POST'], '/formIbuHamil/{id?}', 'Stunting@formIbuHamil')->name('stunting.formIbuHamil');
    Route::post('/insertIbuHamil', 'Stunting@insertIbuHamil')->name('stunting.insertIbuHamil');
    Route::post('/updateIbuHamil/{id?}', 'Stunting@updateIbuHamil')->name('stunting.updateIbuHamil');
    Route::get('/deleteIbuHamil/{id}', 'Stunting@deleteIbuHamil')->name('stunting.deleteIbuHamil');
    Route::post('/deleteAllIbuHamil', 'Stunting@deleteAllIbuHamil')->name('stunting.deleteAllIbuHamil');
    Route::get('/eksporIbuHamil', 'Stunting@eksporIbuHamil')->name('stunting.eksporIbuHamil');
    // Pemantauan Ibu Anak
    Route::get('/pemantauan_anak', 'Stunting@pemantauan_anak')->name('stunting.pemantauan_anak');
    Route::get('/datatablesAnak', 'Stunting@datatablesAnak')->name('stunting.datatablesAnak');
    Route::match(['GET', 'POST'], '/formAnak/{id?}', 'Stunting@formAnak')->name('stunting.formAnak');
    Route::post('/insertAnak', 'Stunting@insertAnak')->name('stunting.insertAnak');
    Route::post('/updateAnak/{id?}', 'Stunting@updateAnak')->name('stunting.updateAnak');
    Route::get('/deleteAnak/{id}', 'Stunting@deleteAnak')->name('stunting.deleteAnak');
    Route::post('/deleteAllAnak', 'Stunting@deleteAllAnak')->name('stunting.deleteAllAnak');
    Route::get('/eksporAnak', 'Stunting@eksporAnak')->name('stunting.eksporAnak');
    // Pemantauan Paud
    Route::get('/pemantauan_paud', 'Stunting@pemantauan_paud')->name('stunting.pemantauan_paud');
    Route::get('/datatablesPaud', 'Stunting@datatablesPaud')->name('stunting.datatablesPaud');
    Route::match(['GET', 'POST'], '/formPaud/{id?}', 'Stunting@formPaud')->name('stunting.formPaud');
    Route::post('/insertPaud', 'Stunting@insertPaud')->name('stunting.insertPaud');
    Route::post('/updatePaud/{id?}', 'Stunting@updatePaud')->name('stunting.updatePaud');
    Route::get('/deletePaud/{id}', 'Stunting@deletePaud')->name('stunting.deletePaud');
    Route::post('/deleteAllPaud', 'Stunting@deleteAllPaud')->name('stunting.deleteAllPaud');
    Route::get('/eksporPaud', 'Stunting@eksporPaud')->name('stunting.eksporPaud');
    // Rekapitulasi
    Route::get('rekapitulasi_ibu_hamil/{kuartal?}/{tahun?}/{id?}', 'Stunting_rekapitulasi@ibu_hamil')->name('stunting.rekapitulasi_ibu_hamil');
    Route::get('rekapitulasi_bulanan_anak/{kuartal?}/{tahun?}/{id?}', 'Stunting_rekapitulasi@bulanan_anak')->name('stunting.rekapitulasi_bulanan_anak');
    Route::get('rekapitulasi_bulanan_balita/{kuartal?}/{tahun?}/{id?}', 'Stunting_rekapitulasi@bulanan_balita')->name('stunting.rekapitulasi_bulanan_balita');
    Route::get('/scorecard_konvergensi/{kuartal?}/{tahun?}/{id?}', 'Stunting@scorecard_konvergensi')->name('stunting.scorecard_konvergensi');
    Route::get('/dialog_sk/{aksi?}', 'Stunting@dialog_sk')->name('stunting.sk.dialog');
    Route::post('/aksi_sk/{aksi?}', 'Stunting@aksi_sk')->name('stunting.sk.aksi');
});

// Layanan Surat > Pengaturan Surat
Route::group('surat_master', static function (): void {
    Route::get('/', 'Surat_master@index')->name('surat_master.index');
    Route::get('/datatables', 'Surat_master@datatables')->name('surat_master.datatables');
    Route::get('/form/{id?}', 'Surat_master@form')->name('surat_master.form');
    Route::get('/apisurat', 'Surat_master@apisurat')->name('surat_master.apisurat');
    Route::get('/syaratSuratDatatables/{id?}', 'Surat_master@syaratSuratDatatables')->name('surat_master.syaratSuratDatatables');
    Route::post('/insert', 'Surat_master@insert')->name('surat_master.insert');
    Route::post('/simpan_sementara', 'Surat_master@simpan_sementara')->name('surat_master.simpan_sementara');
    Route::post('/update/{id?}', 'Surat_master@update')->name('surat_master.update');
    Route::post('/kodeIsian/{id?}', 'Surat_master@kodeIsian')->name('surat_master.kodeIsian');
    Route::match(['GET', 'POST'], '/kunci/{id?}', 'Surat_master@kunci')->name('surat_master.kunci');
    Route::match(['GET', 'POST'], '/favorit/{id?}', 'Surat_master@favorit')->name('surat_master.favorit');
    Route::match(['GET', 'POST'], 'delete/{id?}', 'Surat_master@delete')->name('surat_master.delete');
    Route::get('/pengaturan', 'Surat_master@pengaturan')->name('surat_master.pengaturan');
    Route::post('/edit_pengaturan', 'Surat_master@edit_pengaturan')->name('surat_master.edit_pengaturan');
    Route::post('/pengaturan_sementara', 'Surat_master@pengaturan_sementara')->name('surat_master.pengaturan_sementara');
    Route::match(['GET', 'POST'], '/kode_isian/{jenis?}/{id?}', 'Surat_master@kode_isian')->name('surat_master.kode_isian');
    Route::match(['GET', 'POST'], '/salin_template/{jenis?}', 'Surat_master@salin_template')->name('surat_master.salin_template');
    Route::get('salin/{id}', 'Surat_master@salin')->name('surat_master.salin');
    Route::post('/preview', 'Surat_master@preview')->name('surat_master.preview');
    Route::post('/ekspor', 'Surat_master@ekspor')->name('surat_master.ekspor');
    Route::get('/impor_filter/{data}', 'Surat_master@impor_filter')->name('surat_master.impor_filter');
    Route::post('/impor_store', 'Surat_master@impor_store')->name('surat_master.impor_store');
    Route::post('/impor', 'Surat_master@impor')->name('surat_master.impor');
    Route::get('/templateTinyMCE', 'Surat_master@templateTinyMCE')->name('surat_master.templateTinyMCE');
    Route::get('bawaan', 'Surat_master@bawaan')->name('surat_master.bawaan');
});

// Layanan Surat > Cetak Surat
Route::group('surat', static function (): void {
    Route::get('/', 'Surat@index')->name('surat.index');
    Route::get('/datatables', 'Surat@datatables')->name('surat.datatables');
    Route::get('/apidaftarsurat', 'Surat@apidaftarsurat')->name('surat.apidaftarsurat');
    Route::match(['GET', 'POST'], '/form/{url?}/{id?}', 'Surat@form')->name('surat.form');
    Route::post('/pratinjau/{url?}/{id?}', 'Surat@pratinjau')->name('surat.pratinjau');
    Route::post('/pdf/{preview?}', 'Surat@pdf')->name('surat.pdf');
    Route::post('/konsep', 'Surat@konsep')->name('surat.konsep');
    Route::match(['GET', 'POST'], '/cetak/{id}', 'Surat@cetak')->name('surat.cetak');
    Route::post('/nomor_surat_duplikat', 'Surat@nomor_surat_duplikat')->name('surat.nomor_surat_duplikat');
    Route::post('/search', 'Surat@search')->name('surat.search');
    Route::match(['GET', 'POST'], '/favorit/{id?}/{val?}', 'Surat@favorit')->name('surat.favorit');
    Route::post('/format_nomor_surat', 'Surat@format_nomor_surat')->name('surat.format_nomor_surat');
    Route::get('/list_penduduk_ajax', 'Surat@list_penduduk_ajax')->name('surat.list_penduduk_ajax');
    Route::get('/list_penduduk_bersurat_ajax', 'Surat@list_penduduk_bersurat_ajax')->name('surat.list_penduduk_bersurat_ajax');
    Route::get('/apipenduduksurat', 'Surat@apipenduduksurat')->name('surat.apipenduduksurat');
});

Route::group('datasuratpenduduk', static function (): void {
    Route::match(['GET', 'POST'], '/', 'DataSuratPenduduk@index');
    Route::match(['GET', 'POST'], '/index/{id_surat?}/{id_penduduk?}/{kategori?}', 'DataSuratPenduduk@index');
});

// Layanan Surat > Permohonan Surat
Route::group('permohonan_surat_admin', static function (): void {
    Route::get('/', 'Permohonan_surat_admin@index')->name('permohonan_surat_admin.index');
    Route::get('/datatables', 'Permohonan_surat_admin@datatables')->name('permohonan_surat_admin.datatables');
    Route::get('/periksa/{id?}', 'Permohonan_surat_admin@periksa')->name('permohonan_surat_admin.periksa');
    Route::get('/proses/{id?}/{status?}', 'Permohonan_surat_admin@proses')->name('permohonan_surat_admin.proses');
    Route::get('/konfirmasi/{id_permohonan?}/{tipe?}', 'Permohonan_surat_admin@konfirmasi')->name('permohonan_surat_admin.konfirmasi');
    Route::match(['GET', 'POST'], '/kirim_pesan/{id_permohonan?}/{tipe?}', 'Permohonan_surat_admin@kirim_pesan')->name('permohonan_surat_admin.kirim_pesan');
    Route::get('/delete/{id?}', 'Permohonan_surat_admin@delete')->name('permohonan_surat_admin.delete');
    Route::get('/tampilkan/{id_dokumen?}/{id_pend?}', 'Permohonan_surat_admin@tampilkan')->name('permohonan_surat_admin.tampilkan');
    Route::get('/unduh_berkas/{id_dokumen?}/{id_pend?}/{tampil?}', 'Permohonan_surat_admin@unduh_berkas')->name('permohonan_surat_admin.unduh_berkas');
    Route::get('/tampilkan_berkas/{id_dokumen?}/{id_pend?}', 'Permohonan_surat_admin@tampilkan_berkas')->name('permohonan_surat_admin.tampilkan_berkas');
});

// Layanan Surat > Arsip Layanan
Route::group('keluar', static function (): void {
    Route::get('/lock_surat/{id}', 'Keluar@lockSurat')->name('keluar.lock_surat');
    Route::get('/ajax_edit_surat/{id}', 'Keluar@ajaxEditSurat')->name('keluar.ajax_edit_surat');
    Route::post('/edit_surat/{id}', 'Keluar@editSurat')->name('keluar.edit_surat');
    Route::get('/', 'Keluar@index')->name('keluar.index');
    Route::get('/masuk', 'Keluar@masuk')->name('keluar.masuk');
    Route::get('/ditolak', 'Keluar@ditolak')->name('keluar.ditolak');
    Route::get('/datatables', 'Keluar@datatables')->name('keluar.datatables');
    Route::post('/verifikasi', 'Keluar@verifikasi')->name('keluar.verifikasi');
    Route::get('/tolak', 'Keluar@tolak')->name('keluar.tolak');
    Route::get('/tte', 'Keluar@tte')->name('keluar.tte');
    Route::get('/kembalikan', 'Keluar@kembalikan')->name('keluar.kembalikan');
    Route::get('/periksa/{id}', 'Keluar@periksa')->name('keluar.periksa');
    Route::get('/edit_keterangan/{id}', 'Keluar@edit_keterangan')->name('keluar.edit_keterangan');
    Route::post('/update_keterangan/{id}', 'Keluar@update_keterangan')->name('keluar.update_keterangan');
    Route::get('/delete/{id}', 'Keluar@delete')->name('keluar.delete');
    Route::get('/perorangan/{id?}', 'Keluar@perorangan')->name('keluar.perorangan');
    Route::get('/perorangan_datatables', 'Keluar@perorangan_datatables')->name('keluar.perorangan_datatables');
    Route::get('/graph', 'Keluar@graph')->name('keluar.graph');
    Route::get('/unduh/{tipe?}/{id?}/{preview?}', 'Keluar@unduh')->name('keluar.unduh');
    Route::get('/dialog_cetak/{aksi?}', 'Keluar@dialog_cetak')->name('keluar.dialog_cetak');
    Route::post('/cetak/{aksi?}', 'Keluar@cetak')->name('keluar.cetak');
    Route::get('/qrcode/{id?}', 'Keluar@qrcode')->name('keluar.qrcode');
    Route::get('/perbaiki', 'Keluar@perbaiki')->name('keluar.perbaiki');
    Route::get('/kecamatan', 'Keluar@kecamatan')->name('keluar.kecamatan');
    Route::get('/data_kecamatan', 'Keluar@data_kecamatan')->name('keluar.data_kecamatan');
    Route::get('/dataPenduduk/{id?}', 'Keluar@dataPenduduk')->name('keluar.dataPenduduk');
    Route::get('/bulanTahun/{tahun?}', 'Keluar@bulanTahun')->name('keluar.bulanTahun');
});

// Layanan Surat > Daftar Persyaratan
Route::group('surat_mohon', static function (): void {
    Route::get('/', 'Surat_mohon@index')->name('surat_mohon.index');
    Route::get('/datatables', 'Surat_mohon@datatables')->name('surat_mohon.datatables');
    Route::get('/form/{id?}', 'Surat_mohon@form')->name('surat_mohon.form');
    Route::post('/insert', 'Surat_mohon@insert')->name('surat_mohon.insert');
    Route::post('/update/{id?}', 'Surat_mohon@update')->name('surat_mohon.update');
    Route::get('/delete/{id?}', 'Surat_mohon@delete')->name('surat_mohon.delete');
    Route::post('/deleteAll', 'Surat_mohon@deleteAll')->name('surat_mohon.delete_all');
});

// Surat Dinas > Pengaturan Surat Dinas
Route::group('surat_dinas', static function (): void {
    Route::get('', 'Surat_dinas@index')->name('surat_dinas.index');
    Route::get('datatables', 'Surat_dinas@datatables')->name('surat_dinas.datatables');
    Route::get('form/{id?}', 'Surat_dinas@form')->name('surat_dinas.form');
    Route::get('apisurat', 'Surat_dinas@apisurat')->name('surat_dinas.apisurat');
    Route::get('syaratSuratDatatables/{id?}', 'Surat_dinas@syaratSuratDatatables')->name('surat_dinas.syaratSuratDatatables');
    Route::post('insert', 'Surat_dinas@insert')->name('surat_dinas.insert');
    Route::post('simpan_sementara', 'Surat_dinas@simpan_sementara')->name('surat_dinas.simpan_sementara');
    Route::post('update/{id?}', 'Surat_dinas@update')->name('surat_dinas.update');
    Route::post('kodeIsian/{id?}', 'Surat_dinas@kodeIsian')->name('surat_dinas.kodeIsian');
    Route::match(['GET', 'POST'], 'kunci/{id?}', 'Surat_dinas@kunci')->name('surat_dinas.kunci');
    Route::match(['GET', 'POST'], 'favorit/{id?}', 'Surat_dinas@favorit')->name('surat_dinas.favorit');
    Route::match(['GET', 'POST'], 'delete/{id?}', 'Surat_dinas@delete')->name('surat_dinas.delete');
    Route::get('pengaturan', 'Surat_dinas@pengaturan')->name('surat_dinas.pengaturan');
    Route::post('edit_pengaturan', 'Surat_dinas@edit_pengaturan')->name('surat_dinas.edit_pengaturan');
    Route::match(['GET', 'POST'], 'kode_isian/{jenis?}/{id?}', 'Surat_dinas@kode_isian')->name('surat_dinas.kode_isian');
    Route::match(['GET', 'POST'], 'salin_template/{jenis?}', 'Surat_dinas@salin_template')->name('surat_dinas.salin_template');
    Route::get('salin/{id}', 'Surat_dinas@salin')->name('surat_dinas.salin');
    Route::post('preview', 'Surat_dinas@preview')->name('surat_dinas.preview');
    Route::post('ekspor', 'Surat_dinas@ekspor')->name('surat_dinas.ekspor');
    Route::get('impor_filter/{data}', 'Surat_dinas@impor_filter')->name('surat_dinas.impor_filter');
    Route::post('impor_store', 'Surat_dinas@impor_store')->name('surat_dinas.impor_store');
    Route::post('impor', 'Surat_dinas@impor')->name('surat_dinas.impor');
    Route::get('templateTinyMCE', 'Surat_dinas@templateTinyMCE')->name('surat_dinas.templateTinyMCE');
    Route::get('bawaan', 'Surat_dinas@bawaan')->name('surat_dinas.bawaan');
});
// Surat Dinas > Cetak
Route::group('surat_dinas_cetak', static function (): void {
    Route::get('', 'Surat_dinas_cetak@index')->name('surat_dinas_cetak.index');
    Route::get('datatables', 'Surat_dinas_cetak@datatables')->name('surat_dinas_cetak.datatables');
    Route::get('apidaftarsurat', 'Surat_dinas_cetak@apidaftarsurat')->name('surat_dinas_cetak.apidaftarsurat');
    Route::match(['GET', 'POST'], 'form/{url?}/{id?}', 'Surat_dinas_cetak@form')->name('surat_dinas_cetak.form');
    Route::post('pratinjau/{url?}/{id?}', 'Surat_dinas_cetak@pratinjau')->name('surat_dinas_cetak.pratinjau');
    Route::post('pdf/{preview?}', 'Surat_dinas_cetak@pdf')->name('surat_dinas_cetak.pdf');
    Route::post('konsep', 'Surat_dinas_cetak@konsep')->name('surat_dinas_cetak.konsep');
    Route::match(['GET', 'POST'], 'cetak/{id}', 'Surat_dinas_cetak@cetak')->name('surat_dinas_cetak.cetak');
    Route::post('nomor_surat_duplikat', 'Surat_dinas_cetak@nomor_surat_duplikat')->name('surat_dinas_cetak.nomor_surat_duplikat');
    Route::post('search', 'Surat_dinas_cetak@search')->name('surat_dinas_cetak.search');
    Route::match(['GET', 'POST'], 'favorit/{id?}/{val?}', 'Surat_dinas_cetak@favorit')->name('surat_dinas_cetak.favorit');
    Route::post('format_nomor_surat', 'Surat_dinas_cetak@format_nomor_surat')->name('surat_dinas_cetak.format_nomor_surat');
    Route::get('apipenduduksurat', 'Surat_dinas_cetak@apipenduduksurat')->name('surat_dinas_cetak.apipenduduksurat');
});
// Surat Dinas > Arsip Layanan
Route::group('surat_dinas_arsip', static function (): void {
    Route::get('', 'Surat_dinas_arsip@index')->name('surat_dinas_arsip.index');
    Route::get('masuk', 'Surat_dinas_arsip@masuk')->name('surat_dinas_arsip.masuk');
    Route::get('ditolak', 'Surat_dinas_arsip@ditolak')->name('surat_dinas_arsip.ditolak');
    Route::get('datatables', 'Surat_dinas_arsip@datatables')->name('surat_dinas_arsip.datatables');
    Route::post('verifikasi', 'Surat_dinas_arsip@verifikasi')->name('surat_dinas_arsip.verifikasi');
    Route::post('tolak', 'Surat_dinas_arsip@tolak')->name('surat_dinas_arsip.tolak');
    Route::get('tte', 'Surat_dinas_arsip@tte')->name('surat_dinas_arsip.tte');
    Route::get('kembalikan', 'Surat_dinas_arsip@kembalikan')->name('surat_dinas_arsip.kembalikan');
    Route::get('periksa/{id}', 'Surat_dinas_arsip@periksa')->name('surat_dinas_arsip.periksa');
    Route::get('edit_keterangan/{id}', 'Surat_dinas_arsip@edit_keterangan')->name('surat_dinas_arsip.edit_keterangan');
    Route::post('update_keterangan/{id}', 'Surat_dinas_arsip@update_keterangan')->name('surat_dinas_arsip.update_keterangan');
    Route::get('delete/{id}', 'Surat_dinas_arsip@delete')->name('surat_dinas_arsip.delete');
    Route::get('perorangan/{id?}', 'Surat_dinas_arsip@perorangan')->name('surat_dinas_arsip.perorangan');
    Route::get('perorangan_datatables', 'Surat_dinas_arsip@perorangan_datatables')->name('surat_dinas_arsip.perorangan_datatables');
    Route::get('graph', 'Surat_dinas_arsip@graph')->name('surat_dinas_arsip.graph');
    Route::get('unduh/{tipe?}/{id?}/{preview?}', 'Surat_dinas_arsip@unduh')->name('surat_dinas_arsip.unduh');
    Route::get('dialog_cetak/{aksi?}', 'Surat_dinas_arsip@dialog_cetak')->name('surat_dinas_arsip.dialog_cetak');
    Route::post('cetak/{aksi?}', 'Surat_dinas_arsip@cetak')->name('surat_dinas_arsip.cetak');
    Route::get('qrcode/{id?}', 'Surat_dinas_arsip@qrcode')->name('surat_dinas_arsip.qrcode');
    Route::get('perbaiki', 'Surat_dinas_arsip@perbaiki')->name('surat_dinas_arsip.perbaiki');
    Route::get('kecamatan', 'Surat_dinas_arsip@kecamatan')->name('surat_dinas_arsip.kecamatan');
    Route::get('data_kecamatan', 'Surat_dinas_arsip@data_kecamatan')->name('surat_dinas_arsip.data_kecamatan');
    Route::get('dataPenduduk/{id?}', 'Surat_dinas_arsip@dataPenduduk')->name('surat_dinas_arsip.dataPenduduk');
    Route::get('bulanTahun/{tahun?}', 'Surat_dinas_arsip@bulanTahun')->name('surat_dinas_arsip.bulanTahun');
});

// Sekretariat > Informasi Publik
Route::group('dokumen', static function (): void {
    Route::get('clear', static function (): void {
        redirect('dokumen');
    });
    Route::get('', 'Dokumen@index');
    Route::get('datatables', 'Dokumen@datatables')->name('dokumen.datatables');
    Route::get('form/{id?}', 'Dokumen@form')->name('dokumen.form');
    Route::post('insert', 'Dokumen@insert')->name('dokumen.insert');
    Route::post('update/{id}', 'Dokumen@update')->name('dokumen.update');
    Route::match(['GET', 'POST'], 'delete/{id?}', 'Dokumen@delete')->name('dokumen.delete');
    Route::get('lock/{id}', 'Dokumen@lock')->name('dokumen.lock');
    Route::get('dialog_cetak/{aksi}', 'Dokumen@dialog_cetak')->name('dokumen.dialog_cetak');
    Route::post('cetak/{aksi}', 'Dokumen@cetak')->name('dokumen.cetak');
    Route::get('unduh_berkas/{id_dokumen?}', 'Dokumen@unduh_berkas')->name('dokumen.unduh_berkas');
    Route::get('tampilkan_berkas/{id_dokumen?}/{id_pend?}/{popup?}', 'Dokumen@tampilkan_berkas')->name('dokumen.tampilkan_berkas');
    Route::get('ekspor', 'Dokumen@ekspor')->name('dokumen.ekspor');
    Route::post('ekspor_csv', 'Dokumen@ekspor_csv')->name('dokumen.ekspor_csv');
});

Route::group('inventaris_gedung', static function (): void {
    Route::get('/', 'Inventaris_gedung@index')->name('inventaris_gedung.index');
    Route::get('/datatables', 'Inventaris_gedung@datatables')->name('inventaris_gedung.datatables');
    Route::get('/form/{id?}/{view?}', 'Inventaris_gedung@form')->name('inventaris_gedung.form');
    Route::get('/view/{id?}', 'Inventaris_gedung@view')->name('inventaris_gedung.view');
    Route::post('/create', 'Inventaris_gedung@create')->name('inventaris_gedung.create');
    Route::post('/update/{id}', 'Inventaris_gedung@update')->name('inventaris_gedung.update');
    Route::get('/delete/{id}', 'Inventaris_gedung@delete')->name('inventaris_gedung.delete');
    Route::get('/dialog/{aksi?}', 'Inventaris_gedung@dialog')->name('inventaris_gedung.dialog');
    Route::post('/cetak/{aksi?}', 'Inventaris_gedung@cetak')->name('inventaris_gedung.cetak');
});

Route::group('inventaris_gedung_mutasi', static function (): void {
    Route::get('/', 'Inventaris_gedung_mutasi@index')->name('inventaris_gedung_mutasi.index');
    Route::get('/datatables', 'Inventaris_gedung_mutasi@datatables')->name('inventaris_gedung_mutasi.datatables');
    Route::get('/form/{id?}/{action?}/{view?}', 'Inventaris_gedung_mutasi@form')->name('inventaris_gedung_mutasi.form');
    Route::post('/create/{id}', 'Inventaris_gedung_mutasi@create')->name('inventaris_gedung_mutasi.create');
    Route::post('/update/{id?}/{inventaris_id?}', 'Inventaris_gedung_mutasi@update')->name('inventaris_gedung_mutasi.update');
    Route::get('/delete/{id?}', 'Inventaris_gedung_mutasi@delete')->name('inventaris_gedung_mutasi.delete');
});

Route::group('inventaris_jalan', static function (): void {
    Route::get('/', 'Inventaris_jalan@index')->name('inventaris_jalan.index');
    Route::get('/datatables', 'Inventaris_jalan@datatables')->name('inventaris_jalan.datatables');
    Route::get('/form/{id?}/{view?}', 'Inventaris_jalan@form')->name('inventaris_jalan.form');
    Route::post('/create', 'Inventaris_jalan@create')->name('inventaris_jalan.create');
    Route::post('/update/{id}', 'Inventaris_jalan@update')->name('inventaris_jalan.update');
    Route::get('/delete/{id}', 'Inventaris_jalan@delete')->name('inventaris_jalan.delete');
    Route::get('/dialog/{aksi?}', 'Inventaris_jalan@dialog')->name('inventaris_jalan.dialog');
    Route::post('/cetak/{aksi?}', 'Inventaris_jalan@cetak')->name('inventaris_jalan.cetak');
});

Route::group('inventaris_jalan_mutasi', static function (): void {
    Route::get('/', 'Inventaris_jalan_mutasi@index')->name('inventaris_jalan_mutasi.index');
    Route::post('/create/{id}', 'Inventaris_jalan_mutasi@create')->name('inventaris_jalan_mutasi.create');
    Route::post('/update/{id?}/{inventaris_id?}', 'Inventaris_jalan_mutasi@update')->name('inventaris_jalan_mutasi.update');
    Route::get('/delete/{id?}', 'Inventaris_jalan_mutasi@delete')->name('inventaris_jalan_mutasi.delete');
    Route::get('/form/{id?}/{view?}', 'Inventaris_jalan_mutasi@form')->name('inventaris_jalan_mutasi.form');
    Route::get('/datatables', 'Inventaris_jalan_mutasi@datatables')->name('inventaris_jalan_mutasi.datatables');
});

Route::group('inventaris_asset', static function (): void {
    Route::get('/', 'Inventaris_asset@index')->name('inventaris_asset.index');
    Route::get('/datatables', 'Inventaris_asset@datatables')->name('inventaris_asset.datatables');
    Route::get('/form/{id?}/{view?}', 'Inventaris_asset@form')->name('inventaris_asset.form');
    Route::post('/create', 'Inventaris_asset@create')->name('inventaris_asset.create');
    Route::post('/update/{id}', 'Inventaris_asset@update')->name('inventaris_asset.update');
    Route::get('/delete/{id}', 'Inventaris_asset@delete')->name('inventaris_asset.delete');
    Route::get('/dialog/{aksi?}', 'Inventaris_asset@dialog')->name('inventaris_asset.dialog');
    Route::post('/cetak/{aksi?}', 'Inventaris_asset@cetak')->name('inventaris_asset.cetak');
});

Route::group('inventaris_asset_mutasi', static function (): void {
    Route::get('/', 'Inventaris_asset_mutasi@index')->name('inventaris_asset_mutasi.index');
    Route::post('/create/{id}', 'Inventaris_asset_mutasi@create')->name('inventaris_asset_mutasi.create');
    Route::post('/update/{id?}/{inventaris_id?}', 'Inventaris_asset_mutasi@update')->name('inventaris_asset_mutasi.update');
    Route::get('/delete/{id?}', 'Inventaris_asset_mutasi@delete')->name('inventaris_asset_mutasi.delete');
    Route::get('/form/{id?}/{view?}', 'Inventaris_asset_mutasi@form')->name('inventaris_asset_mutasi.form');
    Route::get('/datatables', 'Inventaris_asset_mutasi@datatables')->name('inventaris_asset_mutasi.datatables');
});

Route::group('inventaris_kontruksi', static function (): void {
    Route::get('/', 'Inventaris_kontruksi@index')->name('inventaris_kontruksi.index');
    Route::get('/datatables', 'Inventaris_kontruksi@datatables')->name('inventaris_kontruksi.datatables');
    Route::get('/form/{id?}/{view?}', 'Inventaris_kontruksi@form')->name('inventaris_kontruksi.form');
    Route::get('/view/{id?}', 'Inventaris_kontruksi@view')->name('inventaris_kontruksi.view');
    Route::post('/create', 'Inventaris_kontruksi@create')->name('inventaris_kontruksi.create');
    Route::post('/update/{id}', 'Inventaris_kontruksi@update')->name('inventaris_kontruksi.update');
    Route::get('/delete/{id}', 'Inventaris_kontruksi@delete')->name('inventaris_kontruksi.delete');
    Route::get('/dialog/{aksi?}', 'Inventaris_kontruksi@dialog')->name('inventaris_kontruksi.dialog');
    Route::post('/cetak/{aksi?}', 'Inventaris_kontruksi@cetak')->name('inventaris_kontruksi.cetak');
});

Route::group('inventaris_peralatan', static function (): void {
    Route::get('/', 'Inventaris_peralatan@index')->name('inventaris_peralatan.index');
    Route::get('/datatables', 'Inventaris_peralatan@datatables')->name('inventaris_peralatan.datatables');
    Route::get('/form/{id?}/{view?}', 'Inventaris_peralatan@form')->name('inventaris_peralatan.form');
    Route::get('/view/{id?}', 'Inventaris_peralatan@view')->name('inventaris_peralatan.view');
    Route::post('/create', 'Inventaris_peralatan@create')->name('inventaris_peralatan.create');
    Route::post('/update/{id}', 'Inventaris_peralatan@update')->name('inventaris_peralatan.update');
    Route::get('/delete/{id}', 'Inventaris_peralatan@delete')->name('inventaris_peralatan.delete');
    Route::get('/dialog/{aksi?}', 'Inventaris_peralatan@dialog')->name('inventaris_peralatan.dialog');
    Route::post('/cetak/{aksi?}', 'Inventaris_peralatan@cetak')->name('inventaris_peralatan.cetak');
});

Route::group('inventaris_peralatan_mutasi', static function (): void {
    Route::get('/', 'Inventaris_peralatan_mutasi@index')->name('inventaris_peralatan_mutasi.index');
    Route::get('/datatables', 'Inventaris_peralatan_mutasi@datatables')->name('inventaris_peralatan_mutasi.datatables');
    Route::get('/form/{id?}/{action?}/{view?}', 'Inventaris_peralatan_mutasi@form')->name('inventaris_peralatan_mutasi.form');
    Route::post('/create/{id}', 'Inventaris_peralatan_mutasi@create')->name('inventaris_peralatan_mutasi.create');
    Route::post('/update/{id?}/{inventaris_id?}', 'Inventaris_peralatan_mutasi@update')->name('inventaris_peralatan_mutasi.update');
    Route::get('/delete/{id?}', 'Inventaris_peralatan_mutasi@delete')->name('inventaris_peralatan_mutasi.delete');
});

Route::group('inventaris_tanah', static function (): void {
    Route::get('/', 'Inventaris_tanah@index')->name('inventaris_tanah.index');
    Route::get('/datatables', 'Inventaris_tanah@datatables')->name('inventaris_tanah.datatables');
    Route::get('/form/{id?}/{view?}', 'Inventaris_tanah@form')->name('inventaris_tanah.form');
    Route::get('/view/{id?}', 'Inventaris_tanah@view')->name('inventaris_tanah.view');
    Route::post('/create', 'Inventaris_tanah@create')->name('inventaris_tanah.create');
    Route::post('/update/{id}', 'Inventaris_tanah@update')->name('inventaris_tanah.update');
    Route::get('/delete/{id}', 'Inventaris_tanah@delete')->name('inventaris_tanah.delete');
    Route::get('/dialog/{aksi?}', 'Inventaris_tanah@dialog')->name('inventaris_tanah.dialog');
    Route::post('/cetak/{aksi?}', 'Inventaris_tanah@cetak')->name('inventaris_tanah.cetak');
});

Route::group('inventaris_tanah_mutasi', static function (): void {
    Route::get('/', 'Inventaris_tanah_mutasi@index')->name('inventaris_tanah_mutasi.index');
    Route::get('/datatables', 'Inventaris_tanah_mutasi@datatables')->name('inventaris_tanah_mutasi.datatables');
    Route::get('/form/{id?}/{action?}/{view?}', 'Inventaris_tanah_mutasi@form')->name('inventaris_tanah_mutasi.form');
    Route::post('/create/{id}', 'Inventaris_tanah_mutasi@create')->name('inventaris_tanah_mutasi.create');
    Route::post('/update/{id?}/{inventaris_id?}', 'Inventaris_tanah_mutasi@update')->name('inventaris_tanah_mutasi.update');
    Route::get('/delete/{id?}', 'Inventaris_tanah_mutasi@delete')->name('inventaris_tanah_mutasi.delete');
});

// Laporan inventaris
Route::group('laporan_inventaris', static function (): void {
    Route::get('/dialog/{aksi?}/{mutasi?}', 'Laporan_inventaris@dialog')->name('laporan_inventaris.dialog');
    Route::get('/datatables/{mutasi?}', 'Laporan_inventaris@datatables')->name('laporan_inventaris.datatables');
    Route::get('/', 'Laporan_inventaris@index')->name('laporan_inventaris.index');
    Route::post('/cetak/{aksi?}/{mutasi?}', 'Laporan_inventaris@cetak')->name('laporan_inventaris.cetak');
    Route::get('/mutasi', 'Laporan_inventaris@mutasi')->name('laporan_inventaris.mutasi');
    Route::get('/permendagri_47/{asset}', 'Laporan_inventaris@permendagri_47')->name('laporan_inventaris.permendagri_47');
    Route::get('/permendagri_47_dialog/{aksi}/{asset?}', 'Laporan_inventaris@permendagri_47_dialog')->name('laporan_inventaris.permendagri_47_dialog');
});

// Sekretariat > Klasifikasi Surat
Route::group('klasifikasi', static function (): void {
    Route::get('/clear', static function (): void {
        redirect('klasifikasi');
    });
    Route::get('/', 'Klasifikasi@index')->name('klasifikasi.index');
    Route::get('/datatables', 'Klasifikasi@datatables')->name('klasifikasi.datatables');
    Route::get('/form/{id?}', 'Klasifikasi@form')->name('klasifikasi.form');
    Route::post('/insert', 'Klasifikasi@insert')->name('klasifikasi.insert');
    Route::post('/update/{id?}', 'Klasifikasi@update')->name('klasifikasi.update');
    Route::get('/delete/{id?}', 'Klasifikasi@delete')->name('klasifikasi.delete');
    Route::post('/delete_all/{id?}', 'Klasifikasi@delete_all')->name('klasifikasi.delete_all');
    Route::get('/lock/{id?}', 'Klasifikasi@lock')->name('klasifikasi.lock');
    Route::get('/unlock/{id?}', 'Klasifikasi@unlock')->name('klasifikasi.unlock');
    Route::get('/ekspor', 'Klasifikasi@ekspor')->name('klasifikasi.ekspor');
    Route::get('/impor', 'Klasifikasi@impor')->name('klasifikasi.impor');
    Route::post('/proses_impor', 'Klasifikasi@proses_impor')->name('klasifikasi.proses_impor');
});

Route::group('', ['namespace' => 'buku_umum'], static function (): void {
    // Bumindes umum
    Route::get('bumindes_umum', static function (): void {
        redirect('dokumen_sekretariat/perdes/3');
    });

    // Dokumen Sekretariat
    Route::group('dokumen_sekretariat', static function (): void {
        Route::get('/perdes/{kat?}', 'Dokumen_sekretariat@perdes')->name('buku-umum.dokumen_sekretariat.perdes');
        Route::get('/tambah_perdes', 'Dokumen_sekretariat@tambah_perdes')->name('buku-umum.dokumen_sekretariat.tambah_perdes');
        Route::get('/ubah_perdes/{id}', 'Dokumen_sekretariat@ubah_perdes')->name('buku-umum.dokumen_sekretariat.ubah_perdes');
        Route::get('/peraturan_desa/{kat?}/{p?}/{o?}', 'Dokumen_sekretariat@peraturan_desa')->name('buku-umum.dokumen_sekretariat.peraturan_desa');
        Route::get('/datatables', 'Dokumen_sekretariat@datatables')->name('buku-umum.dokumen_sekretariat.datatables');
        Route::get('/lock/{kat?}/{id?}', 'Dokumen_sekretariat@lock')->name('buku-umum.dokumen_sekretariat.lock');
        Route::post('/daftar/{kat?}/{aksi?}', 'Dokumen_sekretariat@daftar')->name('buku-umum.dokumen_sekretariat.daftar');

        Route::get('/form/{kat?}/{id?}', 'Dokumen_sekretariat@form')->name('buku-umum.dokumen_sekretariat.form');

        Route::post('/insert', 'Dokumen_sekretariat@insert')->name('buku-umum.dokumen_sekretariat.insert');
        Route::post('/update/{kat?}/{id?}/{p?}/{o?}', 'Dokumen_sekretariat@update')->name('buku-umum.dokumen_sekretariat.update');
        Route::get('/delete/{kat?}/{id?}', 'Dokumen_sekretariat@delete')->name('buku-umum.dokumen_sekretariat.delete');
        Route::post('/delete_all/{kat?}', 'Dokumen_sekretariat@delete_all')->name('buku-umum.dokumen_sekretariat.delete_all');
        Route::get('/dialog_cetak/{kat?}/{aksi?}', 'Dokumen_sekretariat@dialog_cetak')->name('buku-umum.dokumen_sekretariat.dialog_cetak');
        Route::get('/dialog_excel/{kat?}', 'Dokumen_sekretariat@dialog_excel')->name('buku-umum.dokumen_sekretariat.dialog_excel');
        Route::get('/berkas/{id_dokumen?}/{kat?}/{tipe?}/{popup?}', 'Dokumen_sekretariat@berkas')->name('buku-umum.dokumen_sekretariat.berkas');
    });

    Route::group('ekspedisi', static function (): void {
        Route::get('/clear', static function (): void {
            redirect('ekspedisi');
        });
        Route::get('/datatables', 'Ekspedisi@datatables')->name('buku-umum.ekspedisi.datatables');
        Route::get('/form/{id}', 'Ekspedisi@form')->name('buku-umum.ekspedisi.form');
        Route::post('/update/{id}', 'Ekspedisi@update')->name('buku-umum.ekspedisi.update');
        Route::get('/dialog/{aksi?}', 'Ekspedisi@dialog')->name('buku-umum.ekspedisi.dialog');
        Route::get('/unduh_tanda_terima/{id}', 'Ekspedisi@unduh_tanda_terima')->name('buku-umum.ekspedisi.unduh_tanda_terima');
        Route::get('/bukan_ekspedisi/{id}', 'Ekspedisi@bukan_ekspedisi')->name('buku-umum.ekspedisi.bukan_ekspedisi');
        Route::match(['GET', 'POST'], '/index', 'Ekspedisi@index')->name('buku-umum.ekspedisi.index');
        Route::match(['GET', 'POST'], '', 'Ekspedisi@index')->name('buku-umum.ekspedisi.index-page');
        Route::get('/dialog_cetak/{aksi?}', 'Ekspedisi@dialog_cetak')->name('buku-umum.ekspedisi.dialog_cetak');
        Route::post('/daftar/{aksi?}', 'Ekspedisi@daftar')->name('buku-umum.ekspedisi.daftar');
    });

    // Lembaran Desa
    Route::group('lembaran_desa', static function (): void {
        Route::get('/', 'Lembaran_desa@index')->name('buku-umum.lembaran_desa.index');
        Route::get('/datatables', 'Lembaran_desa@datatables')->name('buku-umum.lembaran_desa.datatables');
        Route::get('/form/{id?}', 'Lembaran_desa@form')->name('buku-umum.lembaran_desa.form');
        Route::post('/update/{id}', 'Lembaran_desa@update')->name('buku-umum.lembaran_desa.update');
        Route::get('/lock/{id}', 'Lembaran_desa@lock')->name('buku-umum.lembaran_desa.lock');
        Route::get('/dialog/{aksi?}', 'Lembaran_desa@dialog')->name('buku-umum.lembaran_desa.dialog');
        Route::post('/cetak/{aksi?}', 'Lembaran_desa@cetak')->name('buku-umum.lembaran_desa.cetak');
        Route::get('/unduh_berkas/{id_dokumen?}', 'Lembaran_desa@unduh_berkas')->name('buku-umum.lembaran_desa.unduh_berkas');
    });

    Route::group('pengurus', static function (): void {
        Route::get('/dialog/{aksi?}', 'Pengurus@dialog')->name('buku-umum.pengurus.dialog');
        Route::get('/', 'Pengurus@index')->name('buku-umum.pengurus.index');
        Route::get('/datatables', 'Pengurus@datatables')->name('buku-umum.pengurus.datatables');
        Route::match(['GET', 'POST'], '/form/{id?}', 'Pengurus@form')->name('buku-umum.pengurus.form');
        Route::post('/insert', 'Pengurus@insert')->name('buku-umum.pengurus.insert');
        Route::post('/update/{id?}', 'Pengurus@update')->name('buku-umum.pengurus.update');
        Route::match(['GET', 'POST'], '/delete/{id?}', 'Pengurus@delete')->name('buku-umum.pengurus.delete');
        Route::get('/ttd/{jenis?}/{id?}/{val?}', 'Pengurus@ttd')->name('buku-umum.pengurus.ttd');
        Route::post('/tukar', 'Pengurus@tukar')->name('buku-umum.pengurus.tukar');
        Route::get('/lock/{id?}/{val?}', 'Pengurus@lock')->name('buku-umum.pengurus.lock');
        Route::get('/kehadiran/{id?}/{val?}', 'Pengurus@kehadiran')->name('buku-umum.pengurus.kehadiran');
        Route::post('/daftar/{aksi?}', 'Pengurus@daftar')->name('buku-umum.pengurus.daftar');
        Route::get('/bagan/{ada_bpd?}', 'Pengurus@bagan')->name('buku-umum.pengurus.bagan');
        Route::get('/atur_bagan', 'Pengurus@atur_bagan')->name('buku-umum.pengurus.atur_bagan');
        Route::post('/update_bagan', 'Pengurus@update_bagan')->name('buku-umum.pengurus.update_bagan');
        Route::get('/jabatan', 'Pengurus@jabatan')->name('buku-umum.pengurus.jabatan');
        Route::get('/jabatanform/{id?}', 'Pengurus@jabatanform')->name('buku-umum.pengurus.jabatanform');
        Route::post('/jabataninsert', 'Pengurus@jabataninsert')->name('buku-umum.pengurus.jabataninsert');
        Route::post('/jabatanUpdate/{id?}', 'Pengurus@jabatanUpdate')->name('buku-umum.pengurus.jabatanUpdate');
        Route::match(['GET', 'POST'], '/jabatandelete/{id?}', 'Pengurus@jabatandelete')->name('buku-umum.pengurus.jabatandelete');
        Route::get('/apidaftarpenduduk', 'Pengurus@apidaftarpenduduk')->name('buku-umum.pengurus.apidaftarpenduduk');
    });

    // Surat Keluar
    Route::group('surat_keluar', static function (): void {
        Route::get('/', 'Surat_keluar@index')->name('buku-umum.surat_keluar.index');
        Route::get('/datatables', 'Surat_keluar@datatables')->name('buku-umum.surat_keluar.datatables');
        Route::get('/form/{id?}', 'Surat_keluar@form')->name('buku-umum.surat_keluar.form');
        Route::post('/insert', 'Surat_keluar@insert')->name('buku-umum.surat_keluar.insert');
        Route::post('/update/{id?}', 'Surat_keluar@update')->name('buku-umum.surat_keluar.update');
        Route::get('/delete/{id?}', 'Surat_keluar@delete')->name('buku-umum.surat_keluar.delete');
        Route::post('/delete_all', 'Surat_keluar@delete_all')->name('buku-umum.surat_keluar.delete_all');
        Route::get('/dialog/{aksi?}', 'Surat_keluar@dialog')->name('buku-umum.surat_keluar.dialog');
        Route::post('/cetak/{aksi?}', 'Surat_keluar@cetak')->name('buku-umum.surat_keluar.cetak');
        Route::get('/berkas/{idSuratKeluar?}/{tipe?}', 'Surat_keluar@berkas')->name('buku-umum.surat_keluar.berkas');
        Route::post('/nomor_surat_duplikat', 'Surat_keluar@nomor_surat_duplikat')->name('buku-umum.surat_keluar.nomor_surat_duplikat');
        Route::get('/untuk_ekspedisi/{id?}', 'Surat_keluar@untuk_ekspedisi')->name('buku-umum.surat_keluar.untuk_ekspedisi');
    });

    // Surat Masuk
    Route::group('surat_masuk', static function (): void {
        Route::get('/', 'Surat_masuk@index')->name('buku-umum.surat_masuk.index');
        Route::get('/datatables', 'Surat_masuk@datatables')->name('buku-umum.surat_masuk.datatables');
        Route::get('/form/{id?}', 'Surat_masuk@form')->name('buku-umum.surat_masuk.form');
        Route::post('/insert', 'Surat_masuk@insert')->name('buku-umum.surat_masuk.insert');
        Route::post('/update/{id?}', 'Surat_masuk@update')->name('buku-umum.surat_masuk.update');
        Route::get('/delete/{id?}', 'Surat_masuk@delete')->name('buku-umum.surat_masuk.delete');
        Route::post('/delete_all', 'Surat_masuk@delete_all')->name('buku-umum.surat_masuk.delete_all');
        Route::get('/dialog_disposisi/{id?}', 'Surat_masuk@dialog_disposisi')->name('buku-umum.surat_masuk.dialog_disposisi');
        Route::post('/disposisi/{id?}', 'Surat_masuk@disposisi')->name('buku-umum.surat_masuk.disposisi');
        Route::get('/dialog/{aksi?}', 'Surat_masuk@dialog')->name('buku-umum.surat_masuk.dialog');
        Route::post('/cetak/{aksi?}', 'Surat_masuk@cetak')->name('buku-umum.surat_masuk.cetak');
        Route::get('/berkas/{idSuratMasuk?}/{tipe?}', 'Surat_masuk@berkas')->name('buku-umum.surat_masuk.berkas');
        Route::post('/nomor_surat_duplikat', 'Surat_masuk@nomor_surat_duplikat')->name('buku-umum.surat_masuk.nomor_surat_duplikat');
    });
});

// Buku Tanah Kas Desa
Route::group('bumindes_tanah_kas_desa', static function (): void {
    Route::get('/datatables', 'Bumindes_tanah_kas_desa@datatables')->name('bumindes_tanah_kas_desa.datatables');
    Route::get('/clear', static function (): void {
        redirect('bumindes_tanah_kas_desa');
    });
    Route::match(['GET', 'POST'], '/', 'Bumindes_tanah_kas_desa@index')->name('bumindes_tanah_kas_desa.index');
    Route::get('/dialog_cetak/{aksi?}', 'Bumindes_tanah_kas_desa@dialog_cetak')->name('bumindes_tanah_kas_desa.dialog_cetak');
    Route::post('/cetak/{aksi?}', 'Bumindes_tanah_kas_desa@cetak')->name('bumindes_tanah_kas_desa.cetak');
    Route::get('/view_tanah_kas_desa/{id}', 'Bumindes_tanah_kas_desa@view_tanah_kas_desa')->name('bumindes_tanah_kas_desa.view_tanah_kas_desa');
    Route::get('/form/{id?}', 'Bumindes_tanah_kas_desa@form')->name('bumindes_tanah_kas_desa.form');
    Route::post('/add_tanah_kas_desa', 'Bumindes_tanah_kas_desa@add_tanah_kas_desa')->name('bumindes_tanah_kas_desa.add_tanah_kas_desa');
    Route::post('/update_tanah_kas_desa/{id?}', 'Bumindes_tanah_kas_desa@update_tanah_kas_desa')->name('bumindes_tanah_kas_desa.update_tanah_kas_desa');
    Route::get('/delete_tanah_kas_desa/{id?}', 'Bumindes_tanah_kas_desa@delete_tanah_kas_desa')->name('bumindes_tanah_kas_desa.delete_tanah_kas_desa');
    Route::post('/delete_all', 'Bumindes_tanah_kas_desa@delete_all')->name('bumindes_tanah_kas_desa.delete_all');
    // Route::post('/cetak_tanah_kas_desa/{aksi?}', 'Bumindes_tanah_kas_desa@cetak_tanah_kas_desa')->name('bumindes_tanah_kas_desa.cetak_tanah_kas_desa');
});

// Buku Tanah Desa
Route::group('bumindes_tanah_desa', static function (): void {
    Route::get('/clear', static function (): void {
        redirect('bumindes_tanah_desa');
    });
    Route::get('/', 'Bumindes_tanah_desa@index')->name('bumindes_tanah_desa.index');
    Route::get('/datatables', 'Bumindes_tanah_desa@datatables')->name('bumindes_tanah_desa.datatables');
    Route::get('/form/{id?}/{view?}', 'Bumindes_tanah_desa@form')->name('bumindes_tanah_desa.form');
    Route::get('/view/{id?}', 'Bumindes_tanah_desa@view')->name('bumindes_tanah_desa.view');
    Route::post('/create', 'Bumindes_tanah_desa@create')->name('bumindes_tanah_desa.create');
    Route::post('/update/{id}', 'Bumindes_tanah_desa@update')->name('bumindes_tanah_desa.update');
    Route::get('/delete/{id}', 'Bumindes_tanah_desa@delete')->name('bumindes_tanah_desa.delete');
    Route::get('/dialog/{aksi?}', 'Bumindes_tanah_desa@dialog')->name('bumindes_tanah_desa.dialog');
    Route::post('/cetak/{aksi?}', 'Bumindes_tanah_desa@cetak')->name('bumindes_tanah_desa.cetak');
});

// Buku inventaris dan kekayaan desa
Route::group('bumindes_inventaris_kekayaan', static function (): void {
    Route::get('/clear', static function (): void {
        redirect('bumindes_inventaris_kekayaan');
    });
    Route::get('/', 'Bumindes_inventaris_kekayaan@index')->name('bumindes_inventaris_kekayaan.index');
    Route::get('/datatables', 'Bumindes_inventaris_kekayaan@datatables')->name('bumindes_inventaris_kekayaan.datatables');
    // Route::get('/dialog/{aksi?}', 'Bumindes_inventaris_kekayaan@dialog')->name('bumindes_inventaris_kekayaan.dialog');
    Route::get('/cetak/{aksi?}', 'Bumindes_inventaris_kekayaan@cetak')->name('bumindes_inventaris_kekayaan.cetak');
});

// Administrasi Penduduk
Route::group('bumindes_penduduk_induk', static function (): void {
    Route::get('/clear', static function (): void {
        redirect('/bumindes_penduduk_induk');
    });
    Route::get('/', 'Bumindes_penduduk_induk@index')->name('bumindes_penduduk_induk.index');
    Route::get('/datatables', 'Bumindes_penduduk_induk@datatables')->name('bumindes_penduduk_induk.datatables');
    Route::get('/dialog/{aksi?}', 'Bumindes_penduduk_induk@dialog')->name('bumindes_penduduk_induk.dialog');
    Route::post('/cetak/{aksi?}', 'Bumindes_penduduk_induk@cetak')->name('bumindes_penduduk_induk.cetak');
});

Route::group('bumindes_penduduk_mutasi', static function (): void {
    Route::get('/', 'Bumindes_penduduk_mutasi@index')->name('bumindes_penduduk_mutasi.index');
    Route::get('/datatables', 'Bumindes_penduduk_mutasi@datatables')->name('bumindes_penduduk_mutasi.datatables');
    Route::get('/datatablesHapus', 'Bumindes_penduduk_mutasi@datatablesHapus')->name('bumindes_penduduk_mutasi.datatablesHapus');
    Route::get('/dialog/{aksi?}', 'Bumindes_penduduk_mutasi@dialog')->name('bumindes_penduduk_mutasi.dialog');
    Route::post('/cetak/{aksi?}', 'Bumindes_penduduk_mutasi@cetak')->name('bumindes_penduduk_mutasi.cetak');
});

Route::group('bumindes_penduduk_rekapitulasi', static function (): void {
    Route::get('/datatables', 'Bumindes_penduduk_rekapitulasi@datatables')->name('bumindes_penduduk_rekapitulasi.datatables');
    Route::get('/dialog_cetak/{aksi?}', 'Bumindes_penduduk_rekapitulasi@dialog_cetak')->name('bumindes_penduduk_rekapitulasi.dialog_cetak');
    Route::match(['GET', 'POST'], '/', 'Bumindes_penduduk_rekapitulasi@index');
    Route::match(['GET', 'POST'], '/index', 'Bumindes_penduduk_rekapitulasi@index');
    Route::get('/clear', static function (): void {
        redirect('/bumindes_penduduk_rekapitulasi');
    });
    Route::get('/ajax_cetak/{aksi}', 'Bumindes_penduduk_rekapitulasi@ajax_cetak')->name('bumindes_penduduk_rekapitulasi.ajax_cetak');
    Route::post('/cetak/{aksi}', 'Bumindes_penduduk_rekapitulasi@cetak')->name('bumindes_penduduk_rekapitulasi.cetak');
});

Route::group('bumindes_penduduk_sementara', static function (): void {
    Route::get('/', 'Bumindes_penduduk_sementara@index')->name('bumindes_penduduk_sementara.index');
    Route::get('/datatables', 'Bumindes_penduduk_sementara@datatables')->name('bumindes_penduduk_sementara.datatables');
    Route::get('/dialog/{aksi?}', 'Bumindes_penduduk_sementara@dialog')->name('bumindes_penduduk_sementara.dialog');
    Route::post('/cetak/{aksi?}', 'Bumindes_penduduk_sementara@cetak')->name('bumindes_penduduk_sementara.cetak');
});

Route::group('bumindes_penduduk_ktpkk', static function (): void {
    Route::get('/clear', static function (): void {
        redirect('/bumindes_penduduk_ktpkk');
    });
    Route::get('/dialog_cetak/{aksi?}', 'Bumindes_penduduk_ktpkk@dialog_cetak')->name('bumindes_penduduk_ktpkk.dialog_cetak');
    Route::post('/cetak/{aksi?}', 'Bumindes_penduduk_ktpkk@cetak')->name('bumindes_penduduk_ktpkk.cetak');
    Route::get('/datatables', 'Bumindes_penduduk_ktpkk@datatables')->name('bumindes_penduduk_ktpkk.datatables');
    Route::match(['GET', 'POST'], '/', 'Bumindes_penduduk_ktpkk@index');
    Route::match(['GET', 'POST'], '/index', 'Bumindes_penduduk_ktpkk@index');
});

// - Buku Administrasi Pembangunan
// -- Buku Rencana Kerja Pembangunan
Route::group('bumindes_rencana_pembangunan', static function (): void {
    Route::get('/', 'Bumindes_rencana_pembangunan@index')->name('bumindes_rencana_pembangunan.index');
    Route::get('/datatables', 'Bumindes_rencana_pembangunan@datatables')->name('bumindes_rencana_pembangunan.datatables');
    Route::get('/dialog/{aksi?}', 'Bumindes_rencana_pembangunan@dialog')->name('bumindes_rencana_pembangunan.dialog');
    Route::post('/cetak/{aksi?}', 'Bumindes_rencana_pembangunan@cetak')->name('bumindes_rencana_pembangunan.cetak');
    Route::get('/lainnya/{submenu}', 'Bumindes_rencana_pembangunan@lainnya')->name('bumindes_rencana_pembangunan.lainnya');
});

// -- Buku Kegiatan Pembangunan
Route::group('bumindes_kegiatan_pembangunan', static function (): void {
    Route::get('/', 'Bumindes_kegiatan_pembangunan@index')->name('bumindes_kegiatan_pembangunan.index');
    Route::get('/datatables', 'Bumindes_kegiatan_pembangunan@datatables')->name('bumindes_kegiatan_pembangunan.datatables');
    Route::get('/dialog/{aksi?}', 'Bumindes_kegiatan_pembangunan@dialog')->name('bumindes_kegiatan_pembangunan.dialog');
    Route::post('/cetak/{aksi?}', 'Bumindes_kegiatan_pembangunan@cetak')->name('bumindes_kegiatan_pembangunan.cetak');
    Route::get('/lainnya/{submenu}', 'Bumindes_kegiatan_pembangunan@lainnya')->name('bumindes_kegiatan_pembangunan.lainnya');
});

// -- Buku Inventaris Hasil-hasil Pembangunan
Route::group('bumindes_hasil_pembangunan', static function (): void {
    Route::get('/', 'Bumindes_hasil_pembangunan@index')->name('bumindes_hasil_pembangunan.index');
    Route::get('/datatables', 'Bumindes_hasil_pembangunan@datatables')->name('bumindes_hasil_pembangunan.datatables');
    Route::get('/dialog/{aksi?}', 'Bumindes_hasil_pembangunan@dialog')->name('bumindes_hasil_pembangunan.dialog');
    Route::post('/cetak/{aksi?}', 'Bumindes_hasil_pembangunan@cetak')->name('bumindes_hasil_pembangunan.cetak');
    Route::get('/lainnya/{submenu}', 'Bumindes_hasil_pembangunan@lainnya')->name('bumindes_hasil_pembangunan.lainnya');
});

// -- Buku Kader Pemberdayaan Masyarakat
Route::group('bumindes_kader', static function (): void {
    Route::get('/', 'Bumindes_kader@index')->name('bumindes_kader.index');
    Route::get('/datatables', 'Bumindes_kader@datatables')->name('bumindes_kader.datatables');
    Route::get('/get_bidang', 'Bumindes_kader@get_bidang')->name('bumindes_kader.get_bidang');
    Route::get('/get_kursus', 'Bumindes_kader@get_kursus')->name('bumindes_kader.get_kursus');
    Route::get('/form/{id?}', 'Bumindes_kader@form')->name('bumindes_kader.form');
    Route::post('/create', 'Bumindes_kader@create')->name('bumindes_kader.create');
    Route::post('/update/{id}', 'Bumindes_kader@update')->name('bumindes_kader.update');
    Route::get('/delete/{id}', 'Bumindes_kader@delete')->name('bumindes_kader.delete');
    Route::post('/delete_all', 'Bumindes_kader@delete_all')->name('bumindes_kader.delete_all');
    Route::get('/dialog/{aksi?}', 'Bumindes_kader@dialog')->name('bumindes_kader.dialog');
    Route::post('/cetak/{aksi?}', 'Bumindes_kader@cetak')->name('bumindes_kader.cetak');
});

// - Arsip Desa
Route::group('bumindes_arsip', static function (): void {
    Route::get('/', 'Bumindes_arsip@index')->name('bumindes_arsip.index-page');
    Route::get('/tindakan_lihat/{kategori}/{id}/{tindakan}', 'Bumindes_arsip@tindakan_lihat')->name('bumindes_arsip.tindakan_lihat');
    Route::get('/tindakan_ubah/{kategori}/{id}', 'Bumindes_arsip@tindakan_ubah')->name('bumindes_arsip.tindakan_ubah');
    Route::get('/tampilkan_berkas/{tabel}/{berkas}/{tampil?}', 'Bumindes_arsip@tampilkan_berkas')->name('bumindes_arsip.tampilkan_berkas');
    Route::get('/unduh_berkas/{tabel}/{berkas}', 'Bumindes_arsip@unduh_berkas')->name('bumindes_arsip.unduh_berkas');
    Route::get('/modal_ubah_arsip/{tabel}/{id}', 'Bumindes_arsip@modal_ubah_arsip')->name('bumindes_arsip.modal_ubah_arsip');
    Route::post('/ubah_dokumen/{tabel}/{id}', 'Bumindes_arsip@ubah_dokumen')->name('bumindes_arsip.ubah_dokumen');
    Route::get('/clear/{kategori?}', 'Bumindes_arsip@clear')->name('bumindes_arsip.clear');
    Route::match(['GET', 'POST'], '/', 'Bumindes_arsip@index');
    Route::match(['GET', 'POST'], '/{page_number}', 'Bumindes_arsip@index');
    Route::match(['GET', 'POST'], '/{page_number}/{order_by}', 'Bumindes_arsip@index');
    Route::match(['GET', 'POST'], '/index', 'Bumindes_arsip@index');
    Route::match(['GET', 'POST'], '/index/{page_number}', 'Bumindes_arsip@index');
    Route::match(['GET', 'POST'], '/index/{page_number}/{order_by}', 'Bumindes_arsip@index');
});

// Keuangan > Impor Data
// Keuangan > Laporan
Route::group('keuangan', static function (): void {
    Route::get('laporan', 'Keuangan_laporan@index')->name('keuangan_laporan.index');
    Route::get('data_anggaran', 'Keuangan_laporan@data_anggaran')->name('keuangan_laporan.data_anggaran');
    Route::get('load_data', 'Keuangan_laporan@load_data')->name('keuangan_laporan.load_data');
});
// Keuangan > Input Data
// Keuangan > Laporan Manual
Route::group('keuangan_manual', static function (): void {
    Route::match(['GET', 'POST'], '/', 'Keuangan_manual@index')->name('keuangan_manual.index');
    Route::get('/datatables', 'keuangan_manual@datatables')->name('keuangan_manual.datatables');
    Route::post('template', 'Keuangan_manual@template')->name('keuangan_manual.template');
    Route::get('form/{id}', 'Keuangan_manual@form')->name('keuangan_manual.form');
    Route::post('update/{id}', 'Keuangan_manual@update')->name('keuangan_manual.update');
    Route::get('impor_data', 'Keuangan_manual@impor_data')->name('keuangan_manual.impor_data');
    Route::post('proses_impor', 'Keuangan_manual@proses_impor')->name('keuangan_manual.proses_impor');
    Route::get('cek_tahun_manual', 'Keuangan_manual@cek_tahun_manual')->name('keuangan_manual.cek_tahun_manual');
});

// Keuangan > Laporan APBDes
Route::group('laporan_apbdes', static function (): void {
    Route::get('/', 'Laporan_apbdes@index')->name('laporan_apbdes.index');
    Route::post('/datatables', 'Laporan_apbdes@datatables')->name('laporan_apbdes.datatables');
    Route::get('/form/{id?}', 'Laporan_apbdes@form')->name('laporan_apbdes.form');
    Route::post('/insert', 'Laporan_apbdes@insert')->name('laporan_apbdes.insert');
    Route::post('/update/{id}', 'Laporan_apbdes@update')->name('laporan_apbdes.update');
    Route::match(['GET', 'POST'], '/delete', 'Laporan_apbdes@delete')->name('laporan_apbdes.delete');
    Route::get('/unduh/{id?}', 'Laporan_apbdes@unduh')->name('laporan_apbdes.unduh');
    Route::post('/kirim', 'Laporan_apbdes@kirim')->name('laporan_apbdes.kirim');
});

// Program Bantuan
Route::group('program_bantuan', static function (): void {
    Route::get('/datatables', 'Program_bantuan@datatables')->name('program_bantuan.datatables');
    Route::get('/clear', 'Program_bantuan@clear')->name('program_bantuan.clear');
    Route::post('/filter/{filter}', 'Program_bantuan@filter')->name('program_bantuan.filter');
    Route::match(['GET', 'POST'], '/', 'Program_bantuan@index')->name('program_bantuan.index');
    Route::get('/index/{p?}', 'Program_bantuan@index')->name('program_bantuan.index-page');
    Route::get('/apipendudukbantuan', 'Program_bantuan@apipendudukbantuan')->name('program_bantuan.apipendudukbantuan');
    Route::get('/panduan', 'Program_bantuan@panduan')->name('program_bantuan.panduan');
    Route::match(['GET', 'POST'], '/create', 'Program_bantuan@create')->name('program_bantuan.create');
    Route::match(['GET', 'POST'], '/edit/{id?}', 'Program_bantuan@edit')->name('program_bantuan.edit');
    Route::post('/update/{id}', 'Program_bantuan@update')->name('program_bantuan.update');
    Route::get('/hapus/{id}', 'Program_bantuan@hapus')->name('program_bantuan.hapus');
    Route::post('/impor', 'Program_bantuan@impor')->name('program_bantuan.impor');
    Route::get('/expor/{program_id?}', 'Program_bantuan@expor')->name('program_bantuan.expor');
    Route::get('/unduh_kartu_peserta/{id_peserta?}', 'Program_bantuan@unduh_kartu_peserta')->name('program_bantuan.unduh_kartu_peserta');
    Route::get('/bersihkan_data', 'Program_bantuan@bersihkan_data')->name('program_bantuan.bersihkan_data');
    Route::post('/bersihkan_data_peserta', 'Program_bantuan@bersihkan_data_peserta')->name('program_bantuan.bersihkan_data_peserta');
});

// Peserta Bantuan > Peserta hapus
Route::group('peserta_bantuan', static function (): void {
    Route::get('/datatable_peserta', 'Peserta_bantuan@datatable_peserta')->name('peserta_bantuan.datatable_peserta');
    Route::get('/datatables/{id}', 'Peserta_bantuan@datatables')->name('peserta_bantuan.datatables');
    Route::match(['GET', 'POST'], '/detail/{program_id?}/{p?}', 'Peserta_bantuan@detail')->name('peserta_bantuan.detail');
    Route::match(['GET', 'POST'], '/form/{program_id?}', 'Peserta_bantuan@form')->name('peserta_bantuan.form');
    Route::get('/peserta/{cat?}/{id?}', 'Peserta_bantuan@peserta')->name('peserta_bantuan.peserta');
    Route::get('/data_peserta/{id?}/{program_id?}', 'Peserta_bantuan@data_peserta')->name('peserta_bantuan.data_peserta');
    Route::match(['GET', 'POST'], '/add_peserta/{program_id?}', 'Peserta_bantuan@add_peserta')->name('peserta_bantuan.add_peserta');
    Route::post('/edit_peserta/{id?}', 'Peserta_bantuan@edit_peserta')->name('peserta_bantuan.edit_peserta');
    Route::get('/edit_peserta_form/{id?}/{program_id?}', 'Peserta_bantuan@edit_peserta_form')->name('peserta_bantuan.edit_peserta_form');
    Route::get('/hapus_peserta/{program_id?}/{peserta_id?}', 'Peserta_bantuan@hapus_peserta')->name('peserta_bantuan.hapus_peserta');
    Route::get('/aksi/{aksi?}/{program_id?}', 'Peserta_bantuan@aksi')->name('peserta_bantuan.aksi');
    Route::post('/delete_all/{program_id?}', 'Peserta_bantuan@delete_all')->name('peserta_bantuan.delete_all');
    Route::get('/daftar/{program_id?}/{aksi?}', 'Peserta_bantuan@daftar')->name('peserta_bantuan.daftar');
    Route::get('/detail_clear/{program_id}', 'Peserta_bantuan@detail_clear')->name('peserta_bantuan.detail_clear');
    Route::post('/search/{program_id?}', 'Peserta_bantuan@search')->name('peserta_bantuan.search');
});

// Pertanahan > Daftar Persil
Route::group('data_persil', static function (): void {
    Route::get('', 'Data_persil@index')->name('data_persil.index');
    Route::get('datatables', 'Data_persil@datatables')->name('data_persil.datatables');
    Route::get('rincian/{id}', 'Data_persil@rincian')->name('data_persil.rincian');
    Route::get('form/{id?}/{c_desa?}', 'Data_persil@form')->name('data_persil.form');
    Route::post('simpan/{id?}', 'Data_persil@simpan')->name('data_persil.simpan');
    Route::get('delete/{id}', 'Data_persil@delete')->name('data_persil.delete');
    Route::get('dialog/{aksi}/{id?}', 'Data_persil@dialog_cetak')->name('data_persil.dialog_cetak');
    Route::post('cetak/{aksi}/{id?}', 'Data_persil@cetak')->name('data_persil.cetak');
    Route::get('area_map', 'Data_persil@area_map')->name('data_persil.area_map');
});

// Pertanahan > C-Desa
// mutasi
Route::group('cdesa', static function (): void {
    // Route::post('/simpan_mutasi/{id}/{idMutasi?}', "Cdesa@simpanMutasi")->name("cdesa.simpan_mutasi");
    Route::get('/form_c_desa/{id}', 'Cdesa@form_c_desa')->name('cdesa.form_c_desa');
    Route::get('/apipendudukdesa', 'Cdesa@apipendudukdesa')->name('cdesa.apipendudukdesa');
    Route::get('/detail_penduduk', 'Cdesa@detailPenduduk')->name('cdesa.detail_penduduk');
    Route::get('/dialog/{aksi?}', 'Cdesa@dialog')->name('cdesa.dialog');
    Route::post('/cetak/{aksi?}', 'Cdesa@cetak')->name('cdesa.cetak');
    // Route::get('/create_mutasi/{id_cdesa}/{id_persil?}/{id_mutasi?}', "Cdesa@createMutasi")->name("cdesa.create_mutasi");
    Route::get('/', 'Cdesa@index')->name('cdesa.index');
    Route::get('/datatables', 'Cdesa@datatables')->name('cdesa.datatables');
    Route::get('/form/{id?}', 'Cdesa@form')->name('cdesa.form');
    Route::post('/insert', 'Cdesa@insert')->name('cdesa.insert');
    Route::post('/update/{id?}', 'Cdesa@update')->name('cdesa.update');
    Route::get('/delete/{id?}', 'Cdesa@delete')->name('cdesa.delete');
    Route::post('/delete_all', 'Cdesa@deleteAll')->name('cdesa.delete_all');

    // group rincian
    Route::group('rincian/{rincian}', static function (): void {
        Route::get('/', 'Cdesa_rincian@index')->name('cdesa.rincian');
        Route::get('/datatables', 'Cdesa_rincian@datatables')->name('cdesa.datatables_rincian');
        Route::get('/form/{id?}', 'Cdesa_rincian@form')->name('cdesa.form_rincian');
        Route::post('/insert', 'Cdesa_rincian@insert')->name('cdesa.insert_rincian');
        Route::post('/update/{id?}', 'Cdesa_rincian@update')->name('cdesa.update_rincian');
        Route::get('/delete/{id?}', 'Cdesa_rincian@delete')->name('cdesa.delete_rincian');
    });

    // group mutasi
    Route::group('mutasi/{id_cdesa}', static function (): void {
        Route::get('/form/{id_persil?}/{id_mutasi?}', 'Cdesa_mutasi@form')->name('cdesa.create_mutasi');
        Route::get('/{id_persil?}', 'Cdesa_mutasi@index')->name('cdesa.mutasi');
        Route::get('/delete/{id_persil}/{id_mutasi}', 'Cdesa_mutasi@delete')->name('cdesa.hapus_mutasi');
        Route::get('/datatables/{id_persil?}', 'Cdesa_mutasi@datatables')->name('cdesa.datatables_mutasi');
        Route::post('/simpan/{id_mutasi?}', 'Cdesa_mutasi@simpan')->name('cdesa.simpan_mutasi');
        // Route::get('/form/{id_persil?}/{id_mutasi?}', 'Cdesa_mutasi@form')->name('cdesa.create_mutasi');
        // Route::post('/update/{id?}', 'Cdesa_mutasi@update')->name('cdesa.update_mutasi');
        // Route::get('/delete/{id?}', 'Cdesa_mutasi@delete')->name('cdesa.delete_mutasi');
    });
});

// Pembagunan
Route::group('admin_pembangunan', static function (): void {
    Route::get('/', 'Admin_pembangunan@index')->name('admin_pembangunan.index');
    Route::get('/datatables', 'Admin_pembangunan@datatables')->name('admin_pembangunan.datatables');
    Route::get('/form/{id?}', 'Admin_pembangunan@form')->name('admin_pembangunan.form');
    Route::post('/create', 'Admin_pembangunan@create')->name('admin_pembangunan.create');
    Route::post('/update/{id?}', 'Admin_pembangunan@update')->name('admin_pembangunan.update');
    Route::get('/delete/{id?}', 'Admin_pembangunan@delete')->name('admin_pembangunan.delete');
    Route::match(['GET', 'POST'], '/maps/{id}', 'Admin_pembangunan@maps')->name('admin_pembangunan.maps');
    Route::post('/update-maps/{id}', 'Admin_pembangunan@updateMaps')->name('admin_pembangunan.update-maps');
    Route::get('/lock/{id?}', 'Admin_pembangunan@lock')->name('admin_pembangunan.lock');
});

// Pembagunan
Route::group('pembangunan_dokumentasi', static function (): void {
    Route::get('/dokumentasi/{id?}', 'Pembangunan_dokumentasi@dokumentasi')->name('pembangunan_dokumentasi.dokumentasi');
    Route::get('/datatables-dokumentasi/{id?}', 'Pembangunan_dokumentasi@datatablesDokumentasi')->name('pembangunan_dokumentasi.datatables-dokumentasi');
    Route::get('/form-dokumentasi/{id_pembangunan}/{id?}', 'Pembangunan_dokumentasi@formDokumentasi')->name('pembangunan_dokumentasi.form-dokumentasi');
    Route::post('/create-dokumentasi', 'Pembangunan_dokumentasi@createDokumentasi')->name('pembangunan_dokumentasi.create-dokumentasi');
    Route::post('/update-dokumentasi/{id}', 'Pembangunan_dokumentasi@updateDokumentasi')->name('pembangunan_dokumentasi.update-dokumentasi');
    Route::get('/delete-dokumentasi/{id_pembangunan}/{id?}', 'Pembangunan_dokumentasi@deleteDokumentasi')->name('pembangunan_dokumentasi.delete-dokumentasi');
    Route::get('/dialog/{id}/{aksi?}', 'Pembangunan_dokumentasi@dialog')->name('pembangunan_dokumentasi.dialog');
    Route::post('/daftar/{id}/{aksi?}', 'Pembangunan_dokumentasi@daftar')->name('pembangunan_dokumentasi.daftar');
});

// Pengaduan
Route::group('pengaduan_admin', static function (): void {
    Route::get('/', 'Pengaduan_admin@index')->name('pengaduan_admin.index');
    Route::get('/datatables', 'Pengaduan_admin@datatables')->name('pengaduan_admin.datatables');
    Route::get('/form/{id}', 'Pengaduan_admin@form')->name('pengaduan_admin.form');
    Route::post('/kirim/{id}', 'Pengaduan_admin@kirim')->name('pengaduan_admin.kirim');
    Route::get('/detail/{id}', 'Pengaduan_admin@detail')->name('pengaduan_admin.detail');
    Route::get('/delete/{id}', 'Pengaduan_admin@delete')->name('pengaduan_admin.delete');
    Route::post('/delete', 'Pengaduan_admin@delete')->name('pengaduan_admin.delete-all');
});

// OpenDK > Pesan
Route::group('opendk_pesan', static function (): void {
    Route::get('/', 'Opendk_pesan@index')->name('opendk_pesan.index');
    Route::get('/datatables', 'Opendk_pesan@datatables')->name('opendk_pesan.datatables');
    Route::get('/cek', 'Opendk_pesan@cek')->name('opendk_pesan.cek');
    Route::get('/clear/{return?}', 'Opendk_pesan@clear')->name('opendk_pesan.clear');
    Route::post('/filter/{filter}/{return?}', 'Opendk_pesan@filter')->name('opendk_pesan.filter');
    Route::get('/search/{slash?}', 'Opendk_pesan@search')->name('opendk_pesan.search');
    Route::get('/show/{id}', 'Opendk_pesan@show')->name('opendk_pesan.show');
    Route::get('/form', 'Opendk_pesan@form')->name('opendk_pesan.form');
    Route::post('/insert/{id?}', 'Opendk_pesan@insert')->name('opendk_pesan.insert');
    Route::get('/arsip', 'Opendk_pesan@arsip')->name('opendk_pesan.arsip');
    Route::post('/arsipkan', 'Opendk_pesan@arsipkan')->name('opendk_pesan.arsipkan');
    Route::get('/getPesan', 'Opendk_pesan@getPesan')->name('opendk_pesan.getPesan');
});

// OpenDK > Sinkronisasi
Route::group('sinkronisasi', static function (): void {
    Route::get('/', 'Sinkronisasi@index')->name('sinkronisasi.index');
    Route::get('/sterilkan', 'Sinkronisasi@sterilkan')->name('sinkronisasi.sterilkan');
    Route::get('/kirim/{modul}', 'Sinkronisasi@kirim')->name('sinkronisasi.kirim');
    Route::get('/unduh/{modul}', 'Sinkronisasi@unduh')->name('sinkronisasi.unduh');
    Route::post('/total', 'Sinkronisasi@total')->name('sinkronisasi.total');
    Route::get('/kirim_program_bantuan', 'Sinkronisasi@kirim_program_bantuan')->name('sinkronisasi.kirim_program_bantuan');
    Route::get('/data_program_bantuan', 'Sinkronisasi@data_program_bantuan')->name('sinkronisasi.data_program_bantuan');
    Route::get('/kirim_peserta_program_bantuan', 'Sinkronisasi@kirim_peserta_program_bantuan')->name('sinkronisasi.kirim_peserta_program_bantuan');
    Route::get('/data_peserta_program_bantuan', 'Sinkronisasi@data_peserta_program_bantuan')->name('sinkronisasi.data_peserta_program_bantuan');
    Route::get('/kirim_pembangunan', 'Sinkronisasi@kirim_pembangunan')->name('sinkronisasi.kirim_pembangunan');
    Route::get('/data_pembangunan', 'Sinkronisasi@data_pembangunan')->name('sinkronisasi.data_pembangunan');
    Route::get('/kirim_dokumentasi_pembangunan', 'Sinkronisasi@kirim_dokumentasi_pembangunan')->name('sinkronisasi.kirim_dokumentasi_pembangunan');
    Route::get('/make_dokumentasi_pembangunan', 'Sinkronisasi@make_dokumentasi_pembangunan')->name('sinkronisasi.make_dokumentasi_pembangunan');
});

// Pemetaan > Peta
Route::group('gis', static function (): void {
    Route::get('clear', 'Gis@clear')->name('gis.clear');
    Route::get('', 'Gis@index')->name('gis.index');
    Route::post('filter', 'Gis@filter')->name('gis.filter');
    Route::get('ajax_adv_search', 'Gis@ajax_adv_search')->name('gis.ajax_adv_search');
    Route::post('adv_search_proses', 'Gis@adv_search_proses')->name('gis.adv_search_proses');
});

// Pemetaan > Pengaturan > Lokasi
Route::group('plan', static function (): void {
    Route::get('/', 'Plan@index')->name('plan.index-default');
    Route::get('/index', 'Plan@index')->name('plan.index');
    Route::get('/index/{parent}', 'Plan@index')->name('plan.index-2');
    Route::get('/datatables', 'Plan@datatables')->name('plan.datatables');
    Route::get('/form/{parent?}/{id?}', 'Plan@form')->name('plan.form');
    Route::get('/ajax_lokasi_maps/{parent?}/{id?}', 'Plan@ajax_lokasi_maps')->name('plan.ajax_lokasi_maps');
    Route::post('/update_maps/{parent}/{id}', 'Plan@update_maps')->name('plan.update_maps');
    Route::post('/insert/{parent}', 'Plan@insert')->name('plan.insert');
    Route::post('/update/{parent}/{id}', 'Plan@update')->name('plan.update');
    Route::match(['GET', 'POST'], '/delete/{id?}', 'Plan@delete')->name('plan.delete');
    Route::get('/lock/{parent}/{id}', 'Plan@lock')->name('plan.lock');
    Route::get('/unlock/{parent}/{id}', 'Plan@unlock')->name('plan.unlock');
});

// Pemetaan > Pengaturan > Tipe Lokasi
Route::group('point', static function (): void {
    Route::get('/', 'Point@index')->name('point.index');
    Route::get('/datatables', 'Point@datatables')->name('point.datatables');
    Route::get('/form/{id?}', 'Point@form')->name('point.form-default');
    Route::get('/form/{id}/{subpoint?}', 'Point@form')->name('point.form');
    Route::get('/sub_point/{point}', 'Point@sub_point')->name('point.sub_point');
    Route::get('/ajax_add_sub_point/{point?}/{id?}', 'Point@ajax_add_sub_point')->name('point.ajax_add_sub_point');
    Route::match(['GET', 'POST'], '/insert', 'Point@insert')->name('point.insert-default');
    Route::match(['GET', 'POST'], '/insert/{subpoint}', 'Point@insert')->name('point.insert');
    Route::post('/update/{id?}/{subpoint?}', 'Point@update')->name('point.update');
    Route::match(['GET', 'POST'], '/delete/{id?}/{subpoint?}', 'Point@delete')->name('point.delete');
    Route::get('/lock/{id}/{val}/{subpoint?}', 'Point@lock')->name('point.lock');
});

// Pemetaan > Pengaturan > Simbol Lokasi
Route::group('simbol', static function (): void {
    Route::get('/', 'Simbol@index')->name('simbol.index');
    Route::post('/tambah_simbol', 'Simbol@tambah_simbol')->name('simbol.tambah_simbol');
    Route::get('/delete_simbol/{id?}', 'Simbol@delete_simbol')->name('simbol.delete_simbol');
    Route::get('/salin_simbol_default', 'Simbol@salin_simbol_default')->name('simbol.salin_simbol_default');
    Route::get('/salin_simbol', 'Simbol@salin_simbol')->name('simbol.salin_simbol');
    Route::post('/upload_simbol', 'Simbol@upload_simbol')->name('simbol.upload_simbol');
});

// Pemetaan > Pengaturan > Garis
Route::group('garis', static function (): void {
    Route::get('/', 'Garis@index')->name('garis.index-default');
    Route::get('/index', 'Garis@index')->name('garis.index');
    Route::get('/index/{parent?}', 'Garis@index')->name('garis.index-2');
    Route::get('/datatables', 'Garis@datatables')->name('garis.datatables');
    Route::get('/form/{parent}/{id?}', 'Garis@form')->name('garis.form');
    Route::get('/ajax_garis_maps/{parent}/{id}', 'Garis@ajax_garis_maps')->name('garis.ajax_garis_maps');
    Route::post('/update_maps/{parent}/{id}', 'Garis@update_maps')->name('garis.update_maps');
    Route::get('/kosongkan/{parent}/{id}', 'Garis@kosongkan')->name('garis.kosongkan');
    Route::post('/insert/{parent}', 'Garis@insert')->name('garis.insert');
    Route::post('/update/{parent}/{id?}', 'Garis@update')->name('garis.update');
    Route::match(['GET', 'POST'], '/delete/{parent}/{id?}', 'Garis@delete')->name('garis.delete');
    Route::get('/lock/{parent}/{id}', 'Garis@lock')->name('garis.lock');
    Route::get('/unlock/{parent}/{id}', 'Garis@unlock')->name('garis.unlock');
});

// Pemetaan > Pengaturan > Tipe Garis
Route::group('line', static function (): void {
    Route::get('/', 'Line@index')->name('line.index-default');
    Route::get('/index', 'Line@index')->name('line.index');
    Route::get('/datatables', 'Line@datatables')->name('line.datatables');
    Route::get('/form/{parent}', 'Line@form')->name('line.form-default');
    Route::get('/form/{parent}/{id?}', 'Line@form')->name('line.form');
    Route::post('/insert/{parent}/{tipe?}', 'Line@insert')->name('line.insert');
    Route::post('/update/{parent}/{id?}', 'Line@update')->name('line.update');
    Route::match(['GET', 'POST'], '/delete/{parent}/{id?}', 'Line@delete')->name('line.delete');
    Route::get('/lock/{parent}/{id?}', 'Line@lock')->name('line.lock');
    Route::get('/unlock/{parent}/{id?}', 'Line@unlock')->name('line.unlock');
});

// Pemetaan > Pengaturan > Area
Route::group('area', static function (): void {
    Route::get('/', 'Area@index')->name('area.index-default');
    Route::get('/index', 'Area@index')->name('area.index-1');
    Route::get('/index/{parent}', 'Area@index')->name('area.index');
    Route::get('/datatables', 'Area@datatables')->name('area.datatables');
    Route::get('/form', 'Area@form')->name('area.form-default');
    Route::get('/form/{parent}/{id?}', 'Area@form')->name('area.form');
    Route::get('/ajax_area_maps/{parent}/{id}', 'Area@ajax_area_maps')->name('area.ajax_area_maps');
    Route::post('/update_maps/{parent}/{id}', 'Area@update_maps')->name('area.update_maps');
    Route::get('/kosongkan/{parent}/{id}', 'Area@kosongkan')->name('area.kosongkan');
    Route::post('/insert/{parent}', 'Area@insert')->name('area.insert');
    Route::post('/update/{parent}/{id}', 'Area@update')->name('area.update');
    Route::match(['GET', 'POST'], '/delete/{parent}/{id?}', 'Area@delete')->name('area.delete');
    Route::get('/lock/{parent}/{id?}', 'Area@lock')->name('area.lock');
    Route::get('/unlock/{parent}/{id?}', 'Area@unlock')->name('area.unlock');
});

// Pemetaan > Pengaturan > Tipe Area
Route::group('polygon', static function (): void {
    Route::get('/', 'Polygon@index')->name('polygon.index-default');
    Route::get('/index', 'Polygon@index')->name('polygon.index');
    Route::get('/datatables', 'Polygon@datatables')->name('polygon.datatables');
    Route::get('/form', 'Polygon@form')->name('polygon.form-default');
    Route::get('/form/{parent}/{id?}', 'Polygon@form')->name('polygon.form');
    Route::post('/insert/{parent?}/{tipe?}', 'Polygon@insert')->name('polygon.insert');
    Route::post('/update/{parent}/{id?}', 'Polygon@update')->name('polygon.update');
    Route::get('/delete/{parent}/{id?}', 'Polygon@delete')->name('polygon.delete');
    Route::post('/delete_all/{parent}', 'Polygon@delete_all')->name('polygon.delete_all');
    Route::get('/polygon_lock/{parent}/{id?}', 'Polygon@polygon_lock')->name('polygon.polygon_lock');
    Route::get('/polygon_unlock/{parent}/{id?}', 'Polygon@polygon_unlock')->name('polygon.polygon_unlock');
});

// Hubung Warga > Kirim Pesan
Route::group('sms', static function (): void {
    Route::get('', 'Sms@index')->name('sms.index');
    Route::get('datatables', 'Sms@datatables')->name('sms.datatables');
    Route::get('form/{tipe?}/{id?}', 'Sms@form')->name('sms.form');
    Route::get('broadcast/{p?}/{s?}/{t?}', 'Sms@broadcast')->name('sms.broadcast');
    Route::post('broadcast_proses', 'Sms@broadcast_proses')->name('sms.broadcast_proses');
    Route::post('insert/{tipe}/{id?}', 'Sms@insert')->name('sms.insert');
    Route::post('update/{id?}', 'Sms@update')->name('sms.update');
    Route::match(['GET', 'POST'], 'delete/{tipe?}/{id?}', 'Sms@delete')->name('sms.delete');

    Route::group('outbox', static function (): void {
        Route::get('', 'Sms_outbox@index')->name('sms.outbox');
        Route::get('datatables', 'Sms_outbox@datatables')->name('sms_outbox.datatables');
    });
    Route::group('sentitem', static function (): void {
        Route::get('', 'Sms_sentitem@index')->name('sms.sentitem');
        Route::get('datatables', 'Sms_sentitem@datatables')->name('sms_sentitem.datatables');
    });
    Route::group('pending', static function (): void {
        Route::get('', 'Sms_pending@index')->name('sms.pending');
        Route::get('datatables', 'Sms_pending@datatables')->name('sms_pending.datatables');
    });

    Route::get('/arsip', 'Sms@arsip')->name('sms.arsip');
    Route::get('/arsipdatatables', 'Sms@arsipDatatables')->name('sms.arsipDatatables');
    Route::get('/kirim', 'Sms@kirim')->name('sms.kirim');
    Route::post('/proseskirim', 'Sms@prosesKirim')->name('sms.prosesKirim');
    Route::match(['GET', 'POST'], '/hubungDelete/{id?}', 'Sms@hubungDelete')->name('sms.hubungDelete');
});

// Hubung Warga > Daftar Kontak
Route::group('daftar_kontak', static function (): void {
    Route::get('/', 'Daftar_kontak@index')->name('daftar_kontak.index');
    Route::get('/datatables', 'Daftar_kontak@datatables')->name('daftar_kontak.datatables');
    Route::get('/penduduk', 'Daftar_kontak@penduduk')->name('daftar_kontak.penduduk');
    Route::get('/datatablesPenduduk', 'Daftar_kontak@datatablesPenduduk')->name('daftar_kontak.datatablesPenduduk');
    Route::get('/form/{id?}', 'Daftar_kontak@form')->name('daftar_kontak.form');
    Route::get('/form_penduduk/{id?}', 'Daftar_kontak@form_penduduk')->name('daftar_kontak.form_penduduk');
    Route::post('/insert', 'Daftar_kontak@insert')->name('daftar_kontak.insert');
    Route::post('/update/{id?}', 'Daftar_kontak@update')->name('daftar_kontak.update');
    Route::post('/update_penduduk/{id?}', 'Daftar_kontak@update_penduduk')->name('daftar_kontak.update_penduduk');
    Route::get('/delete/{id?}', 'Daftar_kontak@delete')->name('daftar_kontak.delete');
});

Route::group('grup_kontak', static function (): void {
    Route::get('/', 'Grup_kontak@index')->name('grup_kontak.index');
    Route::get('/datatables', 'Grup_kontak@datatables')->name('grup_kontak.datatables');
    Route::get('/form/{id?}', 'Grup_kontak@form')->name('grup_kontak.form');
    Route::post('/insert', 'Grup_kontak@insert')->name('grup_kontak.insert');
    Route::post('/update/{id?}', 'Grup_kontak@update')->name('grup_kontak.update');
    Route::get('/delete/{id?}', 'Grup_kontak@delete')->name('grup_kontak.delete');
    Route::get('/anggota/{id?}', 'Grup_kontak@anggota')->name('grup_kontak.anggota');
    Route::get('/anggotadatatables/{id}', 'Grup_kontak@anggotaDatatables')->name('grup_kontak.anggotaDatatables');
    Route::get('/anggotaform/{id?}', 'Grup_kontak@anggotaForm')->name('grup_kontak.anggotaForm');
    Route::post('/anggotainsert', 'Grup_kontak@anggotaInsert')->name('grup_kontak.anggotaInsert');
    Route::get('/anggotadelete/{id?}', 'Grup_kontak@anggotaDelete')->name('grup_kontak.anggotaDelete');
    Route::get('/penduduk/{id}', 'Grup_kontak@penduduk')->name('grup_kontak.penduduk');
    Route::get('/kontak/{id}', 'Grup_kontak@kontak')->name('grup_kontak.kontak');
});

// Pengaturan > Modul
Route::group('modul', static function (): void {
    Route::get('clear', static function (): void {
        redirect('modul');
    });
    Route::get('/datatables', 'Modul@datatables')->name('modul.datatables');
    Route::get('/form/{id}', 'Modul@form')->name('modul.form');
    Route::post('/update/{id}', 'Modul@update')->name('modul.update');
    Route::get('/lock/{id}', 'Modul@lock')->name('modul.lock');
    Route::get('/unlock/{id}', 'Modul@unlock')->name('modul.unlock');
    Route::post('/ubah_server', 'Modul@ubah_server')->name('modul.ubah_server');
    Route::get('/default_server', 'Modul@default_server')->name('modul.default_server');
    Route::get('/index/{parent?}', 'Modul@index')->name('modul.index');
    Route::get('/{parent?}', 'Modul@index')->name('modul.index-default');
});

// Pengaturan > Aplikasi
Route::group('setting', static function (): void {
    Route::get('/', 'Setting@index')->name('setting.index');
    Route::post('/update', 'Setting@update')->name('setting.update');
});

// Pengaturan > Pengguna > Pengguna
Route::group('man_user', static function (): void {
    Route::get('/', 'Man_user@index')->name('man_user.index-default');
    Route::get('/index', 'Man_user@index')->name('man_user.index');
    Route::get('/form/{id?}', 'Man_user@form')->name('man_user.form');
    Route::post('/insert', 'Man_user@insert')->name('man_user.insert');
    Route::get('/syarat_sandi/{str}', 'Man_user@syarat_sandi')->name('man_user.syarat_sandi');
    Route::post('/update/{id?}', 'Man_user@update')->name('man_user.update');
    Route::get('/delete/{id?}', 'Man_user@delete')->name('man_user.delete');
    Route::post('/delete_all', 'Man_user@delete_all')->name('man_user.delete_all');
    Route::get('/user_lock/{id?}', 'Man_user@user_lock')->name('man_user.user_lock');
    Route::get('/user_unlock/{id?}', 'Man_user@user_unlock')->name('man_user.user_unlock');
});

// Pengaturan > Pengguna > Grup
Route::group('grup', static function (): void {
    Route::get('/', 'Grup@index')->name('grup.index');
    Route::get('/datatables', 'Grup@datatables')->name('grup.datatables');
    Route::get('/form/{id?}', 'Grup@form')->name('grup.form');
    Route::get('/viewForm/{id}', 'Grup@viewForm')->name('grup.viewForm');
    Route::get('/salin/{id}', 'Grup@salin')->name('grup.salin');
    Route::post('/insert', 'Grup@insert')->name('grup.insert');
    Route::get('/syarat_nama', 'Grup@syarat_nama')->name('grup.syarat_nama');
    Route::post('/update/{id}', 'Grup@update')->name('grup.update');
    Route::match(['GET', 'POST'], '/delete/{id?}', 'Grup@delete')->name('grup.delete');
    Route::get('/lock/{id}', 'Grup@lock')->name('grup.lock');
    Route::post('/ekspor', 'Grup@ekspor')->name('grup.ekspor');
    Route::post('/impor', 'Grup@impor')->name('grup.impor');
    Route::post('impor_store', 'Grup@impor_store')->name('grup.impor_store');
});

// Pengaturan > Database
Route::group('database', static function (): void {
    Route::get('/', 'Database@index')->name('database.index');
    Route::get('/migrasi_cri', 'Database@migrasi_cri')->name('database.migrasi_cri');
    Route::match(['GET', 'POST'], '/migrasi_db_cri', 'Database@migrasi_db_cri')->name('database.migrasi_db_cri');
    Route::get('/exec_backup', 'Database@exec_backup')->name('database.exec_backup');
    Route::get('/desa_backup', 'Database@desa_backup')->name('database.desa_backup');
    Route::get('/desa_inkremental', 'Database@desa_inkremental')->name('database.desa_inkremental');
    Route::post('/inkremental_job', 'Database@inkremental_job')->name('database.inkremental_job');
    Route::get('/inkremental_download', 'Database@inkremental_download')->name('database.inkremental_download');
    Route::get('/inkremental_delete/{id?}', 'Database@inkremental_delete')->name('database.inkremental_delete');
    Route::post('/restore', 'Database@restore')->name('database.restore');
    Route::get('/acak', 'Database@acak')->name('database.acak');
    Route::get('/mutakhirkan_data_server', 'Database@mutakhirkan_data_server')->name('database.mutakhirkan_data_server');
    Route::post('/proses_sinkronkan', 'Database@proses_sinkronkan')->name('database.proses_sinkronkan');
    Route::get('/batal_backup', 'Database@batal_backup')->name('database.batal_backup');
    Route::post('/kirim_otp', 'Database@kirim_otp')->name('database.kirim_otp');
    Route::post('/verifikasi_otp', 'Database@verifikasi_otp')->name('database.verifikasi_otp');
    Route::post('/upload_restore', 'Database@upload_restore')->name('database.upload_restore');
    Route::get('/batal_restore', 'Database@batal_restore')->name('database.batal_restore');
});

Route::group('multiDB', static function (): void {
    Route::get('/backup', 'MultiDB@backup')->name('multiDB.backup');
    Route::post('/restore', 'MultiDB@restore')->name('multiDB.restore');
});

// Pengaturan > Info Sistem
Route::group('/info_sistem', static function (): void {
    Route::get('/', 'Info_sistem@index')->name('info_sistem.index');
    Route::post('/remove_log', 'Info_sistem@remove_log')->name('info_sistem.remove_log');
    Route::get('/cache_desa', 'Info_sistem@cache_desa')->name('info_sistem.cache_desa');
    Route::get('/cache_blade', 'Info_sistem@cache_blade')->name('info_sistem.cache_blade');
    Route::post('/set_permission_desa', 'Info_sistem@set_permission_desa')->name('info_sistem.set_permission_desa');
    Route::get('file_desa', 'Info_sistem@fileDesa')->name('info_sistem.file_desa');
    // Route::get('perbaiki_file_desa', 'Info_sistem@perbaikiFileDesa')->name('info_sistem.perbaiki_file_desa');
    Route::get('datatables', 'Info_sistem@datatables')->name('info_sistem.datatables');
});

// Pengaturan > QR Code
Route::group('qr_code', static function (): void {
    Route::get('clear', static function (): void {
        redirect('qr_code');
    });
    Route::post('/qrcode_generate', 'Qr_code@qrcode_generate')->name('qr_code.qrcode_generate');
    Route::match(['GET', 'POST'], '/', 'Qr_code@index')->name('qr_code.index');
});

// Pengaturan > Optimasi Gambar
Route::group('optimasi_gambar', static function (): void {
    Route::get('/', 'Optimasi_gambar@index')->name('optimasi_gambar.index');
    Route::get('/get_image/{dir?}', 'Optimasi_gambar@get_image')->name('optimasi_gambar.get_image');
    Route::get('/get_folders/{path?}', 'Optimasi_gambar@get_folders')->name('optimasi_gambar.get_folders');
    Route::post('/resize', 'Optimasi_gambar@resize')->name('optimasi_gambar.resize');
});

// Admin Web > Artikel
// Admin Web > Slider
Route::group('web', static function (): void {
    Route::get('clear', static function (): void {
        redirect('web');
    });
    Route::get('form/{cat?}/{id?}', 'Web@form')->name('web.form');
    Route::get('datatables', 'Web@datatables')->name('web.datatables');
    Route::post('insert/{cat}', 'Web@insert')->name('web.insert');
    Route::post('update/{cat}/{id?}', 'Web@update')->name('web.update');
    Route::match(['GET', 'POST'], 'delete/{cat?}/{id?}', 'Web@delete')->name('web.delete');
    Route::match(['GET', 'POST'], 'hapus/{cat}', 'Web@hapus')->name('web.hapus');
    Route::get('ubah_kategori_form/{id?}', 'Web@ubah_kategori_form')->name('web.ubah_kategori_form');
    Route::post('update_kategori/{id?}', 'Web@update_kategori')->name('web.update_kategori');
    Route::get('lock/{cat}/{column}/{id}', 'Web@lock')->name('web.lock');
    Route::get('slider', 'Web@slider')->name('web.slider');
    Route::post('update_slider', 'Web@update_slider')->name('web.update_slider');
    Route::post('reset/{cat}', 'Web@reset')->name('web.reset');
    Route::get('{cat?}', 'Web@index')->name('web.index');
});

// Admin Web > Widget
Route::group('web_widget', static function (): void {
    Route::get('clear', static function (): void {
        redirect('web_widget');
    });
    Route::get('/', 'Web_widget@index')->name('web_widget.index');
    Route::get('/datatables', 'Web_widget@datatables')->name('web_widget.datatables');
    Route::post('/tukar', 'Web_widget@tukar')->name('web_widget.tukar');
    Route::get('/form/{id?}', 'Web_widget@form')->name('web_widget.form');
    Route::get('/admin/{widget}', 'Web_widget@admin')->name('web_widget.admin');
    Route::post('/update_setting/{widget}', 'Web_widget@update_setting')->name('web_widget.update_setting');
    Route::post('/insert', 'Web_widget@insert')->name('web_widget.insert');
    Route::post('/update/{id?}', 'Web_widget@update')->name('web_widget.update');
    Route::get('/delete/{id?}', 'Web_widget@delete')->name('web_widget.delete');
    Route::post('/delete_all', 'Web_widget@delete_all')->name('web_widget.delete_all');
    Route::get('/lock/{id}', 'Web_widget@lock')->name('web_widget.lock');
});

// Admin Web > Menu
Route::group('menu', static function (): void {
    Route::get('/', 'Menu@index')->name('menu.index');
    Route::get('/index', 'Menu@index')->name('menu.index-default');
    Route::get('/datatables', 'Menu@datatables')->name('menu.datatables');
    Route::get('/ajax_menu/{parent}/{id?}', 'Menu@ajax_menu')->name('menu.ajax_menu');
    Route::post('/insert/{parent}', 'Menu@insert')->name('menu.insert');
    Route::post('/update/{parent}/{id}', 'Menu@update')->name('menu.update');
    Route::match(['GET', 'POST'], '/delete/{parent}/{id?}', 'Menu@delete')->name('menu.delete');
    Route::get('/lock/{parent}/{id}', 'Menu@lock')->name('menu.lock');
    Route::post('/tukar', 'Menu@tukar')->name('menu.tukar');
});

// Admin Web > Menu Kategori
Route::group('kategori', static function (): void {
    Route::get('/', 'Kategori@index')->name('kategori.index');
    Route::get('/index', 'Kategori@index')->name('kategori.index-default');
    Route::get('/datatables', 'Kategori@datatables')->name('kategori.datatables');
    Route::get('/ajax_form/{parent}/{id?}', 'Kategori@ajax_form')->name('kategori.ajax_form');
    Route::post('/insert/{parent}', 'Kategori@insert')->name('kategori.insert');
    Route::post('/update/{parent}/{id}', 'Kategori@update')->name('kategori.update');
    Route::match(['GET', 'POST'], '/delete/{parent}/{id?}', 'Kategori@delete')->name('kategori.delete');
    Route::get('/lock/{parent}/{id}', 'Kategori@lock')->name('kategori.lock');
    Route::get('/unlock/{parent}/{id}', 'Kategori@unlock')->name('kategori.unlock');
    Route::post('/tukar', 'Kategori@tukar')->name('kategori.tukar');
});

// Admin Web > Komentar
Route::group('komentar', static function (): void {
    Route::get('/clear', 'Komentar@clear')->name('komentar.clear');
    Route::get('/form/{id?}', 'Komentar@form')->name('komentar.form');
    Route::get('/datatables', 'Komentar@datatables')->name('komentar.datatables');
    Route::post('/insert', 'Komentar@insert')->name('komentar.insert');
    Route::post('/update/{id?}', 'Komentar@update')->name('komentar.update');
    Route::get('/delete/{parent_id?}/{id?}', 'Komentar@delete')->name('komentar.delete');
    Route::post('/delete_all', 'Komentar@delete_all')->name('komentar.delete_all');
    Route::get('/lock/{id?}', 'Komentar@lock')->name('komentar.lock');
    Route::get('/detail/{id?}', 'Komentar@detail')->name('komentar.detail');
    Route::post('/balas/{id?}', 'Komentar@balas')->name('komentar.balas');
    Route::match(['GET', 'POST'], '/', 'Komentar@index')->name('komentar.index-default');
});

// Admin Web > Galeri
Route::group('gallery', static function (): void {
    Route::get('/', 'Gallery@index')->name('gallery.index');
    Route::get('/index', 'Gallery@index')->name('gallery.index-default');
    Route::get('/datatables', 'Gallery@datatables')->name('gallery.datatables');
    Route::get('/form/{parent}/{id?}', 'Gallery@form')->name('gallery.form');
    Route::post('/insert/{parent}', 'Gallery@insert')->name('gallery.insert');
    Route::post('/update/{parent}/{id}', 'Gallery@update')->name('gallery.update');
    Route::match(['GET', 'POST'], '/delete/{parent}/{id?}', 'Gallery@delete')->name('gallery.delete');
    Route::get('/lock/{parent}/{id}', 'Gallery@lock')->name('gallery.lock');
    Route::get('/slider/{parent}/{id}', 'Gallery@slider')->name('gallery.slider');
    Route::post('/tukar', 'Gallery@tukar')->name('gallery.tukar');
});

// Admin Web > Media Sosial
Route::group('sosmed', static function (): void {
    Route::get('/', 'Sosmed@index')->name('sosmed.index');
    Route::get('/datatables', 'Sosmed@datatables')->name('sosmed.datatables');
    Route::get('/form/{id?}', 'Sosmed@form')->name('sosmed.form');
    Route::post('/insert', 'Sosmed@insert')->name('sosmed.insert');
    Route::post('/update/{id?}', 'Sosmed@update')->name('sosmed.update');
    Route::get('/delete/{id?}', 'Sosmed@delete')->name('sosmed.delete');
    Route::post('/delete', 'Sosmed@delete')->name('sosmed.delete-all');
    Route::get('/lock/{id?}', 'Sosmed@lock')->name('sosmed.lock');
});

// Admin Web > Teks Berjalan
Route::group('teks_berjalan', static function (): void {
    Route::get('/', 'Teks_berjalan@index')->name('teks_berjalan.index');
    Route::post('/tukar', 'Teks_berjalan@tukar')->name('teks_berjalan.tukar');
    Route::get('/datatables', 'Teks_berjalan@datatables')->name('teks_berjalan.datatables');
    Route::get('/form/{id?}', 'Teks_berjalan@form')->name('teks_berjalan.form');
    Route::post('/insert', 'Teks_berjalan@insert')->name('teks_berjalan.insert');
    Route::post('/update/{id?}', 'Teks_berjalan@update')->name('teks_berjalan.update');
    Route::match(['GET', 'POST'], '/delete/{id?}', 'Teks_berjalan@delete')->name('teks_berjalan.delete');
    Route::get('/lock/{id?}/{val?}', 'Teks_berjalan@lock')->name('teks_berjalan.lock');
});

// Admin Web > Sinergi Program
Route::group('sinergi_program', static function (): void {
    Route::get('/', 'Sinergi_program@index')->name('sinergi-program.index');
    Route::get('/datatables', 'Sinergi_program@datatables')->name('sinergi-program.datatables');
    Route::get('/form/{id?}', 'Sinergi_program@form')->name('sinergi-program.form');
    Route::post('/insert', 'Sinergi_program@insert')->name('sinergi-program.insert');
    Route::post('/update/{id?}', 'Sinergi_program@update')->name('sinergi-program.update');
    Route::get('/delete/{id?}', 'Sinergi_program@delete')->name('sinergi-program.delete');
    Route::post('/delete', 'Sinergi_program@delete')->name('sinergi-program.delete-all');
    Route::get('/lock/{id?}', 'Sinergi_program@lock')->name('sinergi-program.lock');
    Route::post('/tukar', 'Sinergi_program@tukar')->name('sinergi-program..tukar');
});

// Admin Web > Pengunjung
Route::group('pengunjung', static function (): void {
    Route::get('clear', static function (): void {
        redirect('pengunjung');
    });
    Route::get('/', 'Pengunjung@index')->name('pengunjung.index');
    Route::get('/detail/{id?}', 'Pengunjung@detail')->name('pengunjung.detail');
    Route::get('/cetak/{aksi?}', 'Pengunjung@cetak')->name('pengunjung.cetak');
});

// Admin Web > Pengaturan
Route::group('setting_web', static function (): void {
    Route::get('/', 'Setting_web@index')->name('setting_web.index');
    Route::post('/update', 'Setting_web@update')->name('setting_web.update');
});

// Layanan Mandiri > Kotak Pesan
Route::group('mailbox', static function (): void {
    Route::get('/datatables', 'Mailbox@datatables')->name('mailbox.datatables');
    Route::post('/kirim_pesan', 'Mailbox@kirim_pesan')->name('mailbox.kirim_pesan');
    Route::get('/read/{kat}/{id}', 'Mailbox@read')->name('mailbox.read');
    Route::match(['GET', 'POST'], '/form/{kat}', 'Mailbox@form')->name('mailbox.form');
    Route::get('/detail/{kat}/{id}', 'Mailbox@detail')->name('mailbox.detail');
    Route::get('/list_pendaftar_mandiri_ajax', 'Mailbox@list_pendaftar_mandiri_ajax')->name('mailbox.list_pendaftar_mandiri_ajax');
    Route::match(['GET', 'POST'], '/delete/{kat}/{id?}', 'Mailbox@delete')->name('mailbox.delete');
    Route::get('/{id?}', 'Mailbox@index')->name('mailbox.index')->param('id', 1);
});

// Layanan Mandiri > Pendaftaran Layanan Mandiri
Route::group('mandiri', static function (): void {
    Route::get('/', 'Mandiri@index')->name('mandiri.index');
    Route::get('/datatables', 'Mandiri@datatables')->name('mandiri.datatables');
    Route::get('/ajax_pin/{id_pend?}', 'Mandiri@ajax_pin')->name('mandiri.ajax_pin');
    Route::get('/ajax_hp/{id_pend}', 'Mandiri@ajax_hp')->name('mandiri.ajax_hp');
    Route::get('/ajax_verifikasi_warga/{id_pend}', 'Mandiri@ajax_verifikasi_warga')->name('mandiri.ajax_verifikasi_warga');
    Route::post('/verifikasi_warga/{id_pend}', 'Mandiri@verifikasi_warga')->name('mandiri.verifikasi_warga');
    Route::post('/ubah_hp/{id_pend}', 'Mandiri@ubah_hp')->name('mandiri.ubah_hp');
    Route::post('/insert', 'Mandiri@insert')->name('mandiri.insert');
    Route::post('/update/{id_pend}', 'Mandiri@update')->name('mandiri.update');
    Route::get('/delete/{id?}', 'Mandiri@delete')->name('mandiri.delete');
    Route::post('/kirim/{id_pend?}', 'Mandiri@kirim')->name('mandiri.kirim');
});

// Layanan Mandiri > Gawai Layanan
Route::group('gawai_layanan', static function (): void {
    Route::get('/', 'Gawai_layanan@index')->name('gawai_layanan.index');
    Route::get('/datatables', 'Gawai_layanan@datatables')->name('gawai_layanan.datatables');
    Route::get('/form/{id?}', 'Gawai_layanan@form')->name('gawai_layanan.form');
    Route::post('/insert', 'Gawai_layanan@insert')->name('gawai_layanan.insert');
    Route::post('/update/{id?}', 'Gawai_layanan@update')->name('gawai_layanan.update');
    Route::get('/delete/{id?}', 'Gawai_layanan@delete')->name('gawai_layanan.delete');
    Route::post('/delete', 'Gawai_layanan@delete')->name('gawai_layanan.delete-all');
    Route::get('/kunci/{id?}/{val?}', 'Gawai_layanan@kunci')->name('gawai_layanan.kunci');
});

// Layanan Mandiri > Pendapat
Route::group('pendapat', static function (): void {
    Route::get('/', 'Pendapat@index')->name('pendapat.index');
    Route::get('/detail/{tipe?}', 'Pendapat@detail')->name('pendapat.detail');
});

// Layanan Mandiri > Pengaturan
Route::group('setting', static function (): void {
    Route::get('/ambil_foto', 'Setting@ambil_foto')->name('setting.ambil_foto');
    Route::post('/aktifkan_tracking', 'Setting@aktifkan_tracking')->name('setting.aktifkan_tracking');
});

Route::group('setting_mandiri', static function (): void {
    Route::get('/', 'Setting_mandiri@index')->name('setting_mandiri.index');
    Route::post('/update', 'Setting_mandiri@update')->name('setting_mandiri.update');
});

// Satu Data > DTKS
Route::group('dtks', static function (): void {
    Route::get('/', 'Dtks@index')->name('dtks.index');
    Route::get('/datatables', 'Dtks@datatables')->name('dtks.datatables');
    Route::get('/listAnggota/{id_dtks}', 'Dtks@listAnggota')->name('dtks.listAnggota');
    Route::get('/loadRecentInfo', 'Dtks@loadRecentInfo')->name('dtks.loadRecentInfo');
    Route::get('/loadRecentImpor', 'Dtks@loadRecentImpor')->name('dtks.loadRecentImpor');
    Route::get('/ekspor', 'Dtks@ekspor')->name('dtks.ekspor');
    Route::match(['GET', 'POST'], '/cetak2/{id?}', 'Dtks@cetak2')->name('dtks.cetak2');
    Route::match(['GET', 'POST'], '/new/{id_rtm}', 'Dtks@new')->name('dtks.new');
    Route::get('/latest/{id_rtm}', 'Dtks@latest')->name('dtks.latest');
    Route::get('/form/{id}', 'Dtks@form')->name('dtks.form');
    Route::post('/savePengaturan/{versi_dtks}', 'Dtks@savePengaturan')->name('dtks.savePengaturan');
    Route::post('/save/{id}', 'Dtks@save')->name('dtks.save');
    Route::post('/delete/{id}', 'Dtks@delete')->name('dtks.delete');
    Route::post('/remove/{id}', 'Dtks@remove')->name('dtks.remove');
});

Route::group('token', static function (): void {
    Route::get('/', 'Token@index')->name('token.index');
    Route::post('/update', 'Token@update')->name('token.update');
});

Route::group('plugin', static function (): void {
    Route::get('/', 'Plugin@index')->name('plugin.index');
    Route::get('/installed', 'Plugin@installed')->name('plugin.installed');
    Route::post('/pasang', 'Plugin@pasang')->name('plugin.pasang');
    Route::post('/hapus', 'Plugin@hapus')->name('plugin.hapus');
});

// Pengaturan > Shortcut
Route::group('shortcut', static function (): void {
    Route::get('/', 'Shortcut@index')->name('shortcut.index');
    Route::get('/datatables', 'Shortcut@datatables')->name('shortcut.datatables');
    Route::post('/tukar', 'Shortcut@tukar')->name('shortcut.tukar');
    Route::get('/form/{id?}', 'Shortcut@form')->name('shortcut.form');
    Route::get('/admin/{widget}', 'Shortcut@admin')->name('shortcut.admin');
    Route::post('/insert', 'Shortcut@insert')->name('shortcut.insert');
    Route::post('/update/{id?}', 'Shortcut@update')->name('shortcut.update');
    Route::get('/delete/{id?}', 'Shortcut@delete')->name('shortcut.delete');
    Route::post('/delete_all', 'Shortcut@delete_all')->name('shortcut.delete_all');
    Route::get('/lock/{id}', 'Shortcut@lock')->name('shortcut.lock');
});

// Admin Web > Tema
Route::group('theme', static function (): void {
    Route::get('/', 'Theme@index')->name('theme.index');
    Route::get('/aktifkan/{id}', 'Theme@aktifkan')->name('theme.aktifkan');
    Route::get('/unggah', 'Theme@unggah')->name('theme.unggah');
    Route::post('/proses-unggah', 'Theme@proses_unggah')->name('theme.proses-unggah');
    Route::get('/pengaturan/{id?}', 'Theme@pengaturan')->name('theme.pengaturan');
    Route::post('/ubah-pengaturan/{id?}', 'Theme@ubah_pengaturan')->name('theme.ubah-pengaturan');
    Route::get('/salin_config/{id?}', 'Theme@salin_config')->name('theme.salin-config');
    Route::match(['GET', 'POST'], '/status/{id?}/{val?}', 'Theme@status')->name('theme.status');
    Route::match(['GET', 'POST'], '/delete/{id?}', 'Theme@delete')->name('theme.delete');
    Route::match(['GET', 'POST'], '/deleteAll', 'Theme@deleteAll')->name('smtemas.deleteAll');
    Route::get('/pindai', 'Theme@pindai')->name('theme.pindai');
});
