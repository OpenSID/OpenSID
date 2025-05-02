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

// Konfigurasi aplikasi dalam berkas ini merupakan setting konfigurasi tambahan
// SID. Letakkan setting konfigurasi ini di desa/config/config.php.
// ----------------------------------------------------------------------------

// Uncomment jika situs ini untuk demo. Pada demo, user admin tidak bisa dihapus
// dan username/password tidak bisa diubah

$config['demo_mode'] = true;

// Setting ini untuk menentukan user yang dipercaya. User dengan id di setting ini
// dapat membuat artikel berisi video yang aktif ditampilkan di Web.
// Misalnya, ganti dengan id = 1 jika ingin membuat pengguna admin sebagai pengguna terpecaya.
$config['user_admin'] = 1;

// config email
$config['protocol']  = 'smtp';  // mail	mail, sendmail, or smtp	The mail sending protocol.
$config['smtp_host'] = 'sandbox.smtp.mailtrap.io';      // SMTP Server Address.
$config['smtp_user'] = 'bc8078c6c21871';      // SMTP Username.
$config['smtp_pass'] = '********ccff';      // SMTP Password.
$config['smtp_port'] = 2525;      // SMTP Port."

// $config['server_layanan'] = 'http://127.0.0.1:8000';
// $config['token_layanan']  = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvbGF5YW5hbi5vcGVuZGVzYS5pZCIsImlhdCI6MTY5MjA4OTIzMywiZXhwIjoxOTA2ODc5NjMzLCJuYmYiOjE2OTIwODkyMzMsImp0aSI6ImFmN2UzYTRkZDkxMWQ0NjgyM2I0MjgxNzdiMzFlOTc0MWYzY2I2MTBkNWE2YmRlNzc0YjAxMWI5NDZhYTAxMmIiLCJzdWIiOiIxNTQiLCJwcnYiOiJmOTMwN2ViNWYyOWM3MmE5MGRiYWFlZjBlMjZmMDI2MmVkZTg2ZjU1IiwiZGVzYV9pZCI6IjYyLjAxLjAyLjIwMTYiLCJrZWNhbWF0YW5faWQiOm51bGwsImRvbWFpbiI6Imh0dHBzOlwvXC93d3cubmF0YWlyYXlhLmRlc2EuaWQiLCJ0YW5nZ2FsX2JlcmxhbmdnYW5hbiI6eyJtdWxhaSI6IjIwMjEtMDEtMDEiLCJha2hpciI6IjIwMzAtMDEtMzEifX0.ajhi4KG-x4hfkyubJpdpTbL6JDmAg5FP_hSHVNYgiD4';

// $config['token_layanan']  = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvbGF5YW5hbi5vcGVuZGVzYS5pZCIsImlhdCI6MTY5MjA4OTIzMywiZXhwIjoxOTA2ODc5NjMzLCJuYmYiOjE2OTIwODkyMzMsImp0aSI6ImFmN2UzYTRkZDkxMWQ0NjgyM2I0MjgxNzdiMzFlOTc0MWYzY2I2MTBkNWE2YmRlNzc0YjAxMWI5NDZhYTAxMmIiLCJzdWIiOiIxNTQiLCJwcnYiOiJmOTMwN2ViNWYyOWM3MmE5MGRiYWFlZjBlMjZmMDI2MmVkZTg2ZjU1IiwiZGVzYV9pZCI6IjYyLjAxLjAyLjIwMTYiLCJrZWNhbWF0YW5faWQiOm51bGwsImRvbWFpbiI6Imh0dHBzOlwvXC93d3cubmF0YWlyYXlhLmRlc2EuaWQiLCJ0YW5nZ2FsX2JlcmxhbmdnYW5hbiI6eyJtdWxhaSI6IjIwMjEtMDEtMDEiLCJha2hpciI6IjIwMzAtMDEtMzEifX0.ajhi4KG-x4hfkyubJpdpTbL6JDmAg5FP_hSHVNYgiD4';

// $config['token_layanan']  = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvbGF5YW5hbi5vcGVuZGVzYS5pZCIsImlhdCI6MTY5MjA4OTIzMywiZXhwIjoxOTA2ODc5NjMzLCJuYmYiOjE2OTIwODkyMzMsImp0aSI6ImFmN2UzYTRkZDkxMWQ0NjgyM2I0MjgxNzdiMzFlOTc0MWYzY2I2MTBkNWE2YmRlNzc0YjAxMWI5NDZhYTAxMmIiLCJzdWIiOiIxNTQiLCJwcnYiOiJmOTMwN2ViNWYyOWM3MmE5MGRiYWFlZjBlMjZmMDI2MmVkZTg2ZjU1IiwiZGVzYV9pZCI6IjYyLjAxLjAyLjIwMTYiLCJrZWNhbWF0YW5faWQiOm51bGwsImRvbWFpbiI6Imh0dHBzOlwvXC93d3cubmF0YWlyYXlhLmRlc2EuaWQiLCJ0YW5nZ2FsX2JlcmxhbmdnYW5hbiI6eyJtdWxhaSI6IjIwMjEtMDEtMDEiLCJha2hpciI6IjIwMzAtMDEtMzEifX0.ajhi4KG-x4hfkyubJpdpTbL6JDmAg5FP_hSHVNYgiD4';
// $config['token_layanan']  = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvbGF5YW5hbi5vcGVuZGVzYS5pZCIsImlhdCI6MTY5MjA4OTIzMywiZXhwIjoxOTA2ODc5NjMzLCJuYmYiOjE2OTIwODkyMzMsImp0aSI6ImFmN2UzYTRkZDkxMWQ0NjgyM2I0MjgxNzdiMzFlOTc0MWYzY2I2MTBkNWE2YmRlNzc0YjAxMWI5NDZhYTAxMmIiLCJzdWIiOiIxNTQiLCJwcnYiOiJmOTMwN2ViNWYyOWM3MmE5MGRiYWFlZjBlMjZmMDI2MmVkZTg2ZjU1IiwiZGVzYV9pZCI6IjYyLjAxLjAyLjIwMTYiLCJrZWNhbWF0YW5faWQiOm51bGwsImRvbWFpbiI6Imh0dHBzOlwvXC93d3cubmF0YWlyYXlhLmRlc2EuaWQiLCJ0YW5nZ2FsX2JlcmxhbmdnYW5hbiI6eyJtdWxhaSI6IjIwMjEtMDEtMDEiLCJha2hpciI6IjIwMzAtMDEtMzEifX0.ajhi4KG-x4hfkyubJpdpTbL6JDmAg5FP_hSHVNYgiD4';
// $config['token_layanan']  = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvbGF5YW5hbi5vcGVuZGVzYS5pZCIsImlhdCI6MTY5MjA4OTIzMywiZXhwIjoxOTA2ODc5NjMzLCJuYmYiOjE2OTIwODkyMzMsImp0aSI6ImFmN2UzYTRkZDkxMWQ0NjgyM2I0MjgxNzdiMzFlOTc0MWYzY2I2MTBkNWE2YmRlNzc0YjAxMWI5NDZhYTAxMmIiLCJzdWIiOiIxNTQiLCJwcnYiOiJmOTMwN2ViNWYyOWM3MmE5MGRiYWFlZjBlMjZmMDI2MmVkZTg2ZjU1IiwiZGVzYV9pZCI6IjYyLjAxLjAyLjIwMTYiLCJrZWNhbWF0YW5faWQiOm51bGwsImRvbWFpbiI6Imh0dHBzOlwvXC93d3cubmF0YWlyYXlhLmRlc2EuaWQiLCJ0YW5nZ2FsX2JlcmxhbmdnYW5hbiI6eyJtdWxhaSI6IjIwMjEtMDEtMDEiLCJha2hpciI6IjIwMzAtMDEtMzEifX0.ajhi4KG-x4hfkyubJpdpTbL6JDmAg5FP_hSHVNYgiD4';
// $config['web_theme']     = 'desa/bimaaaaaaa';
// $config['kode_desa']     = '5306132002';
// $config['kode_desa'] = '6201022016';
// $config['warna_tema'] = '#ffff';

// $config['kode_desa']     = '6201022016'; // Natai Raya
// $config['token_layanan']  = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvbGF5YW5hbi5vcGVuZGVzYS5pZCIsImlhdCI6MTY5MjA4OTIzMywiZXhwIjoxOTA2ODc5NjMzLCJuYmYiOjE2OTIwODkyMzMsImp0aSI6ImFmN2UzYTRkZDkxMWQ0NjgyM2I0MjgxNzdiMzFlOTc0MWYzY2I2MTBkNWE2YmRlNzc0YjAxMWI5NDZhYTAxMmIiLCJzdWIiOiIxNTQiLCJwcnYiOiJmOTMwN2ViNWYyOWM3MmE5MGRiYWFlZjBlMjZmMDI2MmVkZTg2ZjU1IiwiZGVzYV9pZCI6IjYyLjAxLjAyLjIwMTYiLCJrZWNhbWF0YW5faWQiOm51bGwsImRvbWFpbiI6Imh0dHBzOlwvXC93d3cubmF0YWlyYXlhLmRlc2EuaWQiLCJ0YW5nZ2FsX2JlcmxhbmdnYW5hbiI6eyJtdWxhaSI6IjIwMjEtMDEtMDEiLCJha2hpciI6IjIwMzAtMDEtMzEifX0.ajhi4KG-x4hfkyubJpdpTbL6JDmAg5FP_hSHVNYgiD4';
// $config['token_layanan']  = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvbGF5YW5hbi5vcGVuZGVzYS5pZCIsImlhdCI6MTY5MjA4OTIzMywiZXhwIjoxOTA2ODc5NjMzLCJuYmYiOjE2OTIwODkyMzMsImp0aSI6ImFmN2UzYTRkZDkxMWQ0NjgyM2I0MjgxNzdiMzFlOTc0MWYzY2I2MTBkNWE2YmRlNzc0YjAxMWI5NDZhYTAxMmIiLCJzdWIiOiIxNTQiLCJwcnYiOiJmOTMwN2ViNWYyOWM3MmE5MGRiYWFlZjBlMjZmMDI2MmVkZTg2ZjU1IiwiZGVzYV9pZCI6IjYyLjAxLjAyLjIwMTYiLCJrZWNhbWF0YW5faWQiOm51bGwsImRvbWFpbiI6Imh0dHBzOlwvXC93d3cubmF0YWlyYXlhLmRlc2EuaWQiLCJ0YW5nZ2FsX2JlcmxhbmdnYW5hbiI6eyJtdWxhaSI6IjIwMjEtMDEtMDEiLCJha2hpciI6IjIwMzAtMDEtMzEifX0.ajhi4KG-x4hfkyubJpdpTbL6JDmAg5FP_hSHVNYgiD4';
// $config['kode_desa'] = '1101012004';
$config['DeNava'] = '84E81F454AE5';

// $config['server_layanan'] = 'https://layanan.opendesa.id';