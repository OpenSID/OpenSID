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
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

$lang['db_invalid_connection_str']     = 'Tidak dapat menentukan pengaturan basis data berdasarkan string koneksi yang Anda kirimkan.';
$lang['db_unable_to_connect']          = '<h3>Ada kesalahan dalam pemasangan OpenSID</h3>Aplikasi tidak bisa terhubung ke database.<br />Periksa isi berkas desa/config/database.php. Pastikan pada bagian "Data Konfigurasi MySQL yang disesuaikan" terisi dengan benar.<p></p><p>Untuk mengatasi kendala ini anda bisa membaca panduan/petunjuk di <a href="https://github.com/OpenSID/OpenSID/wiki/Panduan-Install-OpenSID" target="_blank">tautan ini.</a></p>';
$lang['db_unable_to_select']           = 'Tidak dapat memilih basis data yang telah ditentukan: %s';
$lang['db_unable_to_create']           = 'Tidak dapat membuat basis data yang telah ditentukan: %s';
$lang['db_invalid_query']              = 'Kueri yang Anda kirimkan tidak valid.';
$lang['db_must_set_table']             = 'Anda harus mengatur tabel basis data yang akan digunakan dengan kueri Anda.';
$lang['db_must_use_set']               = 'Anda harus menggunakan metode "set" untuk memperbarui entri.';
$lang['db_must_use_index']             = 'Anda harus menentukan indeks untuk pencocokkan selama update batch.';
$lang['db_batch_missing_index']        = 'Satu atau lebih baris diajukan untuk update Batch kehilangan indeks tertentu.';
$lang['db_must_use_where']             = 'Pembaruan tidak diperbolehkan kecuali mereka mengandung klausa "where".';
$lang['db_del_must_use_where']         = 'Menghapus tidak diperbolehkan kecuali mereka mengandung klausa "where" atau "like".';
$lang['db_field_param_missing']        = 'Untuk mengambil bidang membutuhkan nama tabel sebagai parameter.';
$lang['db_unsupported_function']       = 'Fitur ini tidak tersedia untuk basis data yang Anda gunakan.';
$lang['db_transaction_failure']        = 'Kegagalan transaksi: Dikembalikan ke data semula.';
$lang['db_unable_to_drop']             = 'Tidak dapat menghapus basis data yang telah ditentukan.';
$lang['db_unsuported_feature']         = 'Fitur yang tidak didukung platform basis data yang Anda gunakan.';
$lang['db_unsuported_compression']     = 'Format kompresi berkas yang Anda pilih tidak didukung oleh server Anda.';
$lang['db_filepath_error']             = 'Tidak dapat menulis data ke jalur berkas yang telah Anda kirimkan.';
$lang['db_invalid_cache_path']         = 'Jalur Cache yang Anda diajukan tidak sah atau ditulis.';
$lang['db_table_name_required']        = 'Sebuah nama tabel diperlukan untuk operasi tersebut.';
$lang['db_column_name_required']       = 'Sebuah nama kolom diperlukan untuk operasi tersebut.';
$lang['db_column_definition_required'] = 'Definisi kolom diperlukan untuk operasi tersebut.';
$lang['db_unable_to_set_charset']      = 'Tidak dapat mengatur set karakter koneksi klien: %s';
$lang['db_error_heading']              = 'Sebuah Kesalahan Basis Data Telah Terjadi';
