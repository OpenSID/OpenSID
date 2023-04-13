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
 * Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

// Auth
$mandiri                                                      = 'layanan-mandiri';
$route[$mandiri . '/masuk']                                   = MANDIRI . '/masuk';
$route[$mandiri . '/cek']                                     = MANDIRI . '/masuk/cek';
$route[$mandiri . '/masuk-ektp']                              = MANDIRI . '/masuk_ektp';
$route[$mandiri . '/cek-ektp']                                = MANDIRI . '/masuk_ektp/cek_ektp';
$route[$mandiri . '/daftar']                                  = MANDIRI . '/daftar';
$route[$mandiri . '/proses-daftar']                           = MANDIRI . '/daftar/proses_daftar';
$route[$mandiri . '/daftar/verifikasi']                       = MANDIRI . '/daftar_verifikasi';
$route[$mandiri . '/daftar/verifikasi/telegram']              = MANDIRI . '/daftar_verifikasi/telegram';
$route[$mandiri . '/daftar/verifikasi/telegram/kirim-userid'] = MANDIRI . '/daftar_verifikasi/kirim_otp_telegram';
$route[$mandiri . '/daftar/verifikasi/telegram/kirim-otp']    = MANDIRI . '/daftar_verifikasi/verifikasi_telegram';
$route[$mandiri . '/daftar/verifikasi/email']                 = MANDIRI . '/daftar_verifikasi/email';
$route[$mandiri . '/daftar/verifikasi/email/kirim-email']     = MANDIRI . '/daftar_verifikasi/kirim_otp_email';
$route[$mandiri . '/daftar/verifikasi/email/kirim-otp']       = MANDIRI . '/daftar_verifikasi/verifikasi_email';
$route[$mandiri . '/lupa-pin']                                = MANDIRI . '/masuk/lupa_pin';
$route[$mandiri . '/cek-pin']                                 = MANDIRI . '/masuk/cek_pin';

// Anjungan
$route['layanan-mandiri'] = MANDIRI . '/anjungan';

// Beranda
$route['layanan-mandiri/beranda']     = MANDIRI . '/beranda';
$route[$mandiri . '/pendapat/(:num)'] = MANDIRI . '/beranda/pendapat/$1';

// Profil
$route[$mandiri . '/profil']           = MANDIRI . '/beranda/profil';
$route[$mandiri . '/cetak-biodata']    = MANDIRI . '/beranda/cetak_biodata';
$route[$mandiri . '/ganti-pin']        = MANDIRI . '/beranda/ganti_pin';
$route[$mandiri . '/proses-ganti-pin'] = MANDIRI . '/beranda/proses_ganti_pin';
$route[$mandiri . '/cetak-kk']         = MANDIRI . '/beranda/cetak_kk';
$route[$mandiri . '/keluar']           = MANDIRI . '/beranda/keluar';

// Pesan
$route[$mandiri . '/pesan-masuk']              = MANDIRI . '/pesan/index/2';
$route[$mandiri . '/pesan-keluar']             = MANDIRI . '/pesan/index/1';
$route[$mandiri . '/pesan/tulis']              = MANDIRI . '/pesan/tulis/1';
$route[$mandiri . '/pesan/balas']              = MANDIRI . '/pesan/tulis/2';
$route[$mandiri . '/pesan/kirim']              = MANDIRI . '/pesan/kirim';
$route[$mandiri . '/pesan/baca/(:num)/(:num)'] = MANDIRI . '/pesan/baca/$1/$2';

// Surat
$route[$mandiri . '/arsip-surat']        = MANDIRI . '/surat/index/2';
$route[$mandiri . '/permohonan-surat']   = MANDIRI . '/surat/index/1';
$route[$mandiri . '/surat/buat']         = MANDIRI . '/surat/buat';
$route[$mandiri . '/surat/buat/(:num)']  = MANDIRI . '/surat/buat/$1';
$route[$mandiri . '/surat/form']         = MANDIRI . '/surat/form';
$route[$mandiri . '/surat/form/(:num)']  = MANDIRI . '/surat/form/$1';
$route[$mandiri . '/surat/cetak/(:num)'] = MANDIRI . '/surat/cetak/$1';

// Dokumen
$route[$mandiri . '/dokumen']              = MANDIRI . '/dokumen';
$route[$mandiri . '/dokumen/form']         = MANDIRI . '/dokumen/form';
$route[$mandiri . '/dokumen/form/(:num)']  = MANDIRI . '/dokumen/form/$1';
$route[$mandiri . '/dokumen/tambah']       = MANDIRI . '/dokumen/tambah';
$route[$mandiri . '/dokumen/ubah/(:num)']  = MANDIRI . '/dokumen/ubah/$1';
$route[$mandiri . '/dokumen/hapus/(:num)'] = MANDIRI . '/dokumen/hapus/$1';
$route[$mandiri . '/dokumen/unduh/(:num)'] = MANDIRI . '/dokumen/unduh/$1';

// Lapak
$route[$mandiri . '/lapak']        = MANDIRI . '/lapak';
$route[$mandiri . '/lapak/(:num)'] = MANDIRI . '/lapak/index/$1';

//Verifikasi
$route[$mandiri . '/verifikasi']                       = MANDIRI . '/verifikasi';
$route[$mandiri . '/verifikasi/telegram']              = MANDIRI . '/verifikasi/telegram';
$route[$mandiri . '/verifikasi/telegram/kirim-userid'] = MANDIRI . '/verifikasi/kirim_otp_telegram';
$route[$mandiri . '/verifikasi/telegram/kirim-otp']    = MANDIRI . '/verifikasi/verifikasi_telegram';
$route[$mandiri . '/verifikasi/email']                 = MANDIRI . '/verifikasi/email';
$route[$mandiri . '/verifikasi/email/kirim-email']     = MANDIRI . '/verifikasi/kirim_otp_email';
$route[$mandiri . '/verifikasi/email/kirim-otp']       = MANDIRI . '/verifikasi/verifikasi_email';

// Bantuan
$route[$mandiri . '/bantuan'] = MANDIRI . '/bantuan';

// Kehadiran Perangkat Desa
$route[$mandiri . '/kehadiran']              = MANDIRI . '/kehadiran_perangkat';
$route[$mandiri . '/kehadiran/lapor/(:num)'] = MANDIRI . '/kehadiran_perangkat/lapor/$1';
