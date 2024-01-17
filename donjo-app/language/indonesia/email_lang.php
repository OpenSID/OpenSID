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

$lang['email_must_be_array']         = 'Metode validasi email harus melewati sebuah array.';
$lang['email_invalid_address']       = 'Alamat email tidak sah: %s';
$lang['email_attachment_missing']    = 'Tidak dapat menemukan lampiran email berikut: %s';
$lang['email_attachment_unreadable'] = 'Tidak dapat membuka lampiran ini: %s';
$lang['email_no_from']               = 'Tidak dapat mengirim email tanpa kepala "Dari".';
$lang['email_no_recipients']         = 'Anda harus menyertakan penerima: Kepada, CC, atau BCC';
$lang['email_send_failure_phpmail']  = 'Tidak dapat mengirim email menggunakan PHP mail(). Server Anda mungkin tidak dikonfigurasi untuk mengirim email menggunakan metode ini.';
$lang['email_send_failure_sendmail'] = 'Tidak dapat mengirim email menggunakan PHP Sendmail. Server Anda mungkin tidak dikonfigurasi untuk mengirim email menggunakan metode ini.';
$lang['email_send_failure_smtp']     = 'Tidak dapat mengirim email menggunakan PHP SMTP. Server Anda mungkin tidak dikonfigurasi untuk mengirim email menggunakan metode ini.';
$lang['email_sent']                  = 'Pesan Anda telah berhasil dikirim menggunakan protokol berikut: %s';
$lang['email_no_socket']             = 'Tidak dapat membuka socket untuk Sendmail. Silakan periksa pengaturan.';
$lang['email_no_hostname']           = 'Anda tidak menentukan nama host SMTP.';
$lang['email_smtp_error']            = 'Berikut kesalahan SMTP ditemui: %s';
$lang['email_no_smtp_unpw']          = 'Kesalahan: Anda harus menetapkan nama pengguna dan password SMTP.';
$lang['email_failed_smtp_login']     = 'Gagal mengirim perintah AUTH LOGIN. Kesalahan: %s';
$lang['email_smtp_auth_un']          = 'Gagal untuk mengotentikasi nama pengguna. Kesalahan: %s';
$lang['email_smtp_auth_pw']          = 'Gagal untuk mengotentikasi password. Kesalahan: %s';
$lang['email_smtp_data_failure']     = 'Tidak dapat mengirim data: %s';
$lang['email_exit_status']           = 'Kode status keluar: %s';
