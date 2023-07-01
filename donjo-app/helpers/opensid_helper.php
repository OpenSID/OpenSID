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

/**
 * VERSION
 * Format => [dua digit tahun dan dua digit bulan].[nomor urut digit beta].[nomor urut digit bugfix]
 * Untuk rilis resmi (tgl 1 tiap bulan) dimulai dari 0 (beta) dan 0 (bugfix)
 */
define('VERSION', '2307.0.0');

/**
 * VERSI_DATABASE
 * Ubah setiap kali mengubah struktur database atau melakukan proses rilis (tgl 01)
 * Simpan nilai ini di tabel migrasi untuk menandakan sudah migrasi ke versi ini
 * Versi database = [yyyymmdd][nomor urut dua digit]
 * [nomor urut dua digit] : 01 => rilis umum, 51 => rilis bugfix, 71 => rilis premium,
 */
define('VERSI_DATABASE', '2023070101');

// Kode laporan statistik
define('JUMLAH', 666);
define('BELUM_MENGISI', 777);
define('TOTAL', 888);

// Kode laporan mandiri di tabel komentar
define('LAPORAN_MANDIRI', 775);

// Kode artikel terkait agenda
define('AGENDA', 1000);

define('MAX_PINDAH', 7);
define('MAX_ANGGOTA', 7);
define('SASARAN', serialize([
    '1' => 'Penduduk',
    '2' => 'Keluarga / KK',
    '3' => 'Rumah Tangga',
    '4' => 'Kelompok/Organisasi Kemasyarakatan',
]));
define('ASALDANA', serialize([
    'Pusat'             => 'Pusat',
    'Provinsi'          => 'Provinsi',
    'Kab/Kota'          => 'Kab/Kota',
    'Dana Desa'         => 'Dana Desa',
    'Lain-lain (Hibah)' => 'Lain-lain (Hibah)',
]));
define('KTP_EL', serialize([
    strtolower('BELUM')  => '1',
    strtolower('KTP-EL') => '2',
    strtolower('KIA')    => '3',
]));
define('TEMPAT_DILAHIRKAN', serialize([
    'RS/RB'    => '1',
    'Puskemas' => '2',
    'Polindes' => '3',
    'Rumah'    => '4',
    'Lainnya'  => '5',
]));
define('JENIS_KELAHIRAN', serialize([
    'Tunggal'  => '1',
    'Kembar 2' => '2',
    'Kembar 3' => '3',
    'Kembar 4' => '4',
]));
define('PENOLONG_KELAHIRAN', serialize([
    'Dokter'        => '1',
    'Bidan Perawat' => '2',
    'Dukun'         => '3',
    'Lainnya'       => '4',
]));
define('JENIS_MUTASI', serialize([
    'Hapus barang masih baik' => '1',
    'Hapus barang rusak'      => '4',
    'Status rusak'            => '2',
    'Status diperbaiki'       => '3',
]));
define('JENIS_PENGHAPUSAN', serialize([
    'Rusak'     => '1',
    'Dijual'    => '2',
    'Disumbang' => '3',
]));
define('ASAL_INVENTARIS', serialize([
    'Dibeli Sendiri'     => '1',
    'Bantuan Pemerintah' => '2',
    'Bantuan Provinsi'   => '3',
    'Bantuan Kabupaten'  => '4',
    'Sumbangan'          => '5',
]));
define('KATEGORI_MAILBOX', serialize([
    'Kotak Masuk'  => '1',
    'Kotak Keluar' => '2',
]));

define('NILAI_PENDAPAT', serialize([
    1 => 'Sangat Puas',
    2 => 'Puas',
    3 => 'Cukup',
    4 => 'Buruk',
]));

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

/**
 * Ambil Versi
 *
 * Mengembalikan nomor versi aplikasi
 *
 * @return string
 */
function AmbilVersi()
{
    return VERSION;
}

/**
 * Ambil Current Version
 *
 * Mengembalikan nomor current_version
 *
 * @return string
 */
function currentVersion()
{
    return substr_replace(substr(VERSION, 0, 4), '.', 2, 0);
}

/**
 * favico_desa
 *
 * Mengembalikan path lengkap untuk file favico desa
 *
 * @param mixed $favico
 *
 * @return string
 */
function favico_desa($favico = 'favicon.ico')
{
    if (is_file(LOKASI_LOGO_DESA . $favico)) {
        $favico = LOKASI_LOGO_DESA . $favico;
    }

    return base_url($favico) . '?v' . md5_file($favico);
}

/**
 * gambar_desa / KantorDesa
 *
 * Mengembalikan path lengkap untuk file logo desa / kantor desa
 *
 * @param mixed $nama_file
 * @param mixed $type
 * @param mixed $file
 *
 * @return string
 */
function gambar_desa($nama_file, $type = false, $file = false)
{
    if (is_file(FCPATH . LOKASI_LOGO_DESA . $nama_file)) {
        $nama_file = LOKASI_LOGO_DESA . $nama_file;

        return $file ? FCPATH . $nama_file : to_base64($nama_file);
    }

    // type FALSE = logo, TRUE = kantor
    $default = ($type) ? 'opensid_kantor.jpg' : 'opensid_logo.png';
    $default = "assets/files/logo/{$default}";

    return $file ? FCPATH . $default : to_base64($default);
}

function session_error($pesan = '')
{
    $_SESSION['error_msg'] = $pesan;
    $_SESSION['success']   = -1;
}

function session_error_clear()
{
    $_SESSION['error_msg'] = '';
    unset($_SESSION['success']);
}

function session_success()
{
    $_SESSION['error_msg'] = '';
    $_SESSION['success']   = 1;
}

// Untuk mengirim data ke OpenSID tracker
function httpPost($url, $params)
{
    try {
        $response = (new Client())->post($url, [
            'headers' => [
                'X-Requested-With' => 'XMLHttpRequest',
                'Authorization'    => 'Bearer ' . config_item('token_pantau'),
            ],
            'form_params'     => $params,
            'timeout'         => 5,
            'connect_timeout' => 4,
        ]);
    } catch (ClientException $cx) {
        log_message('error', $cx);

        return;
    } catch (Exception $e) {
        log_message('error', $e);

        return;
    }

    return $response->getBody()->getContents();
}

/**
 * Cek ada koneksi internet.
 *
 * @param string $sCheckHost Default: www.google.com
 *
 * @return bool
 */
function cek_koneksi_internet($sCheckHost = 'www.google.com')
{
    $connected = @fsockopen($sCheckHost, 443);

    if ($connected) {
        fclose($connected);

        return true;
    }

    return false;
}

function cek_bisa_akses_site($url)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $content = curl_exec($ch);
    $error   = curl_error($ch);

    curl_close($ch);

    return empty($error);
}

/**
 * Laporkan error PHP.
 * Script ini digunakan untuk mengatasi masalah di mana ada hosting (seperti indoreg.co.id)
 * yang tidak mengizinkan fungsi sistem, seperti curl.
 * Simpan info ini di $_SESSION, supaya pada pemanggilan berikut
 * tracker tidak dijalankan.
 */
set_error_handler('myErrorHandler');
register_shutdown_function('fatalErrorShutdownHandler');
function myErrorHandler($code, $message, $file, $line)
{
    // Khusus untuk mencatat masalah dalam pemanggilan httpPost di track_model.php
    if (strpos($message, 'curl_exec') !== false) {
        $_SESSION['no_curl'] = 'y';
        echo '<strong>Apabila halamannya tidak tampil, coba di-refresh.</strong>';
        // Ulangi url yang memanggil fungsi tracker.
        redirect(base_url() . 'index.php/' . $_SESSION['balik_ke']);
    }
    // Uncomment apabila melakukan debugging
    // else {
    //   echo "<strong>Telah dialami error PHP sebagai berikut: </strong><br><br>";
    //   echo "Severity: ".$code."<br>";
    //   echo "Pesan: ".$message."<br>";
    //   echo "Nama File: ".$file."<br>";
    //   echo "Nomor Baris: ".$line;
    // }
}
function fatalErrorShutdownHandler()
{
    $last_error = error_get_last();
    if ($last_error['type'] === E_ERROR) {
        // fatal error
        myErrorHandler(E_ERROR, $last_error['message'], $last_error['file'], $last_error['line']);
    }
}

function get_dynamic_title_page_from_path()
{
    $parse = str_replace([
        '/first',
    ], '', $_SERVER['PATH_INFO']);
    $explo = explode('/', $parse);

    $title = '';

    for ($i = 0; $i < count($explo); $i++) {
        $t = trim($explo[$i]);
        if (! empty($t) && $t != '1' && $t != '0') {
            $title .= ((is_numeric($t)) ? ' ' : ' - ') . $t;
        }
    }

    return ucwords(str_replace([
        '  ',
        '_',
    ], ' ', $title));
}

function show_zero_as($val, $str)
{
    return empty($val) ? $str : $val;
}

function log_time($msg)
{
    $now = DateTime::createFromFormat('U.u', microtime(true));
    error_log($now->format('m-d-Y H:i:s.u') . ' : ' . $msg . "\n", 3, 'opensid.log');
}

/**
 * @param mixed $tgl_lahir
 *
 * @return - null, kalau tgl_lahir bukan string tanggal
 */
function umur($tgl_lahir)
{
    try {
        $date = new DateTime($tgl_lahir);
    } catch (Exception $e) {
        return null;
    }
    $now      = new DateTime();
    $interval = $now->diff($date);

    return $interval->y;
}

// Dari https://stackoverflow.com/questions/4117555/simplest-way-to-detect-a-mobile-device
function isMobile()
{
    return preg_match("/\\b(?:a(?:ndroid|vantgo)|b(?:lackberry|olt|o?ost)|cricket|do‌\u{200b}como|hiptop|i(?:emob‌\u{200b}ile|p[ao]d)|kitkat|m‌\u{200b}(?:ini|obi)|palm|(?:‌\u{200b}i|smart|windows )phone|symbian|up\\.(?:browser|link)|tablet(?: browser| pc)|(?:hp-|rim |sony )tablet|w(?:ebos|indows ce|os))/i", $_SERVER['HTTP_USER_AGENT']);
}

/*
Deteksi file berisi script PHP:
-- extension .php
-- berisi string '<?php', '<script', function, __halt_compiler,<html
Perhatian: string '<?', '<%' tidak bisa digunakan sebagai indikator,
karena file image dan PDF juga mengandung string ini.
*/
function isPHP($file, $filename)
{
    $ext = get_extension($filename);
    if ($ext == '.php') {
        return true;
    }

    $handle = fopen($file, 'rb');
    $buffer = stream_get_contents($handle);
    if (preg_match('/<\?php|<script|function|__halt_compiler|<html/i', $buffer)) {
        fclose($handle);

        return true;
    }
    fclose($handle);

    return false;
}

function get_extension($filename)
{
    $ext = explode('.', strtolower($filename));

    return '.' . end($ext);
}

function max_upload()
{
    $max_filesize = (int) bilangan(ini_get('upload_max_filesize'));
    $max_post     = (int) bilangan(ini_get('post_max_size'));
    $memory_limit = (int) bilangan(ini_get('memory_limit'));

    return min($max_filesize, $max_post, $memory_limit);
}

function getKodeDesaFromTrackSID()
{
    if (session('trackSID_bps_code') && session('trackSID_bps_code') != null) {
        return session('trackSID_bps_code');
    }

    $config  = \App\Models\Config::first();
    $tracker = config_item('server_pantau');

    $trackSID_bps_code = getUrlContent($tracker . '/index.php/api/wilayah/kodedesa?kode=' . $config->kode_desa . '&token=' . config_item('token_pantau'));

    if (! empty($trackSID_bps_code)) {
        set_session(['trackSID_bps_code' => json_decode($trackSID_bps_code, true)]);

        return session('trackSID_bps_code');
    }

    return null;
}

function get_external_ip()
{
    // Batasi waktu mencoba
    $options = stream_context_create([
        'http' => [
            'timeout' => 2, //2 seconds
        ],
    ]);
    $externalContent = file_get_contents('http://checkip.dyndns.com/', false, $options);
    preg_match('/\b(?:\d{1,3}\.){3}\d{1,3}\b/', $externalContent, $m);

    return $m[0];
}

// Salin folder rekursif
// https://stackoverflow.com/questions/2050859/copy-entire-contents-of-a-directory-to-another-using-php
function xcopy($src = '', $dest = '', $exclude = [], $only = [])
{
    if (! file_exists($dest)) {
        mkdir($dest, 0755, true);
    }

    foreach (scandir($src) as $file) {
        $srcfile  = rtrim($src, '/') . '/' . $file;
        $destfile = rtrim($dest, '/') . '/' . $file;

        if (! is_readable($srcfile) || ($exclude && in_array($file, $exclude))) {
            continue;
        }

        if ($file != '.' && $file != '..') {
            if (is_dir($srcfile)) {
                if (! file_exists($destfile)) {
                    mkdir($destfile);
                }
                xcopy($srcfile, $destfile, $exclude, $only);
            } else {
                if ($only && ! in_array($file, $only)) {
                    continue;
                }

                copy($srcfile, $destfile);
            }
        }
    }
}

function sql_in_list($list_array)
{
    if (empty($list_array)) {
        return false;
    }

    $prefix = $list = '';

    foreach ($list_array as $key => $value) {
        $list .= $prefix . "'" . $value . "'";
        $prefix = ', ';
    }

    return $list;
}

/*
 * ambilBerkas
 * Method untuk mengambil berkas
 * param :
 * nama_berkas : nama berkas yang ingin diambil (hanya nama, bukan lokasi berkas)
 * redirect_url : jika terjadi error, maka halaman akan dialihkan ke redirect_url
 * unique_id : diperlukan jika nama file asli tidak sama dengan nama didatabase
 * lokasi : lokasi folder berkas berada (contoh : desa/arsip)
 * tampil : true kalau berkas akan ditampilkan inline (tidak diunduh)
 */
function ambilBerkas($nama_berkas, $redirect_url = null, $unique_id = null, $lokasi = LOKASI_ARSIP, $tampil = false)
{
    $CI = &get_instance();
    $CI->load->helper('download');

    // Batasi akses LOKASI_ARSIP hanya untuk admin
    // if ($lokasi == LOKASI_ARSIP && $CI->session->siteman != 1) {
    //     redirect('/');
    // }

    // Tentukan path berkas (absolut)
    $pathBerkas = FCPATH . $lokasi . $nama_berkas;
    $pathBerkas = str_replace('/', DIRECTORY_SEPARATOR, $pathBerkas);
    // Redirect ke halaman surat masuk jika path berkas kosong atau berkasnya tidak ada
    if (! file_exists($pathBerkas)) {
        $_SESSION['success']   = -1;
        $_SESSION['error_msg'] = 'Berkas tidak ditemukan';
        if ($redirect_url) {
            redirect($redirect_url);
        } else {
            show_404();
        }
    }
    // OK, berkas ada. Ambil konten berkasnya

    $data = file_get_contents($pathBerkas);

    if (null !== $unique_id) {
        // Buang unique id pada nama berkas download
        $nama_berkas  = explode($unique_id, $nama_berkas);
        $namaFile     = $nama_berkas[0];
        $ekstensiFile = explode('.', end($nama_berkas));
        $ekstensiFile = end($ekstensiFile);
        $nama_berkas  = $namaFile . '.' . $ekstensiFile;
    }

    // Kalau $tampil, tampilkan secara inline.
    if ($tampil) {
        // Set the default MIME type to send
        switch (get_extension($nama_berkas)) {
            case '.gif':
                $mime = 'image/gif';
                break;

            case '.png':
                $mime = 'image/png';
                break;

            case '.jpeg':
                $mime = 'image/jpeg';
                break;

            case '.jpg':
                $mime = 'image/jpeg';
                break;

            case '.svg':
                $mime = 'image/svg+xml';
                break;

            case '.pdf':
                $mime = 'application/pdf';
                break;

            default:
                $mime = 'application/octet-stream';
                break;
        }

        // Generate the server headers
        header('Content-Type: ' . $mime);
        header('Content-Disposition: inline; filename="' . $nama_berkas . '"');
        header('Expires: 0');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . strlen($data));
        header('Cache-Control: private, no-transform, no-store, must-revalidate');

        return readfile($pathBerkas);
    }

    force_download($nama_berkas, $data);
}

/**
 * @param array 		(0 => (kolom1 => teks, kolom2 => teks, ..), 1 => (kolom1 => teks, kolom2 => teks. ..), ..)
 * @param mixed $data
 *
 * @return string dalam bentuk siap untuk autocomplete, mengambil teks dari setiap kolom
 */
function autocomplete_data_ke_str($data)
{
    $str    = '';
    $keys   = array_keys($data[0]);
    $values = [];

    foreach ($keys as $key) {
        $values = array_merge($values, array_column($data, $key));
    }
    $values = array_unique($values);
    sort($values);

    return '["' . strtolower(implode('","', $values)) . '"]';
}

// Periksa apakah nilai bilangan Romawi
// https://recalll.co/?q=How%20to%20convert%20a%20Roman%20numeral%20to%20integer%20in%20PHP?&type=code
function is_angka_romawi($roman)
{
    $roman_regex = '/^M{0,3}(CM|CD|D?C{0,3})(XC|XL|L?X{0,3})(IX|IV|V?I{0,3})$/';

    return preg_match($roman_regex, $roman) > 0;
}

function bulan_romawi($bulan)
{
    if ($bulan < 1 || $bulan > 12) {
        return false;
    }

    $bulan_romawi = [
        1  => 'I',
        2  => 'II',
        3  => 'III',
        4  => 'IV',
        5  => 'V',
        6  => 'VI',
        7  => 'VII',
        8  => 'VIII',
        9  => 'IX',
        10 => 'X',
        11 => 'XI',
        12 => 'XII',
    ];

    return $bulan_romawi[$bulan];
}

function buang_nondigit($str)
{
    return preg_replace('/[^0-9]/', '', $str);
}

/**
 * @param array $files = array($file1, $file2, ...)
 *
 * @return string path ke zip file
 *                Masukkan setiap berkas ke dalam zip.
 *                $file bisa:
 *                - array('nama' => nama-file-yg diinginkan, 'file' => full-path-ke-berkas); atau
 *                - full-path-ke-berkas
 *                Untuk membuat folder di dalam zip gunakan:
 *                $file = array('nama' => 'dir', 'file' => nama-folder)
 */
function masukkan_zip($files = [])
{
    $zip = new ZipArchive();
    // create a temp file & open it
    $tmp_file = tempnam(sys_get_temp_dir(), '');
    $zip->open($tmp_file, ZipArchive::CREATE);

    foreach ($files as $file) {
        if (is_array($file)) {
            if ($file['nama'] == 'dir') {
                $zip->addEmptyDir($file['file']);

                continue;
            }

            $nama_file = $file['nama'];
            $file      = $file['file'];
        } else {
            $nama_file = basename($file);
        }
        $download_file = file_get_contents($file);
        $zip->addFromString($nama_file, $download_file);
    }
    $zip->close();

    return $tmp_file;
}

// https://www.tutorialspoint.com/how-to-download-large-files-through-php-script
// Baca file sepotong-sepotong untuk mengunduh file besar sebagai pengganti readfile()
function readfile_chunked($filename, $retbytes = true)
{
    $chunksize = 1 * (1024 * 1024); // how many bytes per chunk the user wishes to read
    $buffer    = '';
    $cnt       = 0;
    $handle    = fopen($filename, 'rb');
    if ($handle === false) {
        return false;
    }

    while (! feof($handle)) {
        $buffer = fread($handle, $chunksize);
        echo $buffer;
        if ($retbytes) {
            $cnt += strlen($buffer);
        }
    }
    $status = fclose($handle);
    if ($retbytes && $status) {
        return $cnt; // return number of bytes delivered like readfile() does.
    }

    return $status;
}

function alfa_spasi($str)
{
    return preg_replace('/[^a-zA-Z ]/', '', strip_tags($str));
}

// https://www.php.net/manual/en/function.array-column.php
function array_column_ext($array, $columnkey, $indexkey = null)
{
    $result = [];

    foreach ($array as $subarray => $value) {
        if (array_key_exists($columnkey, $value)) {
            $val = $array[$subarray][$columnkey];
        } elseif ($columnkey === null) {
            $val = $value;
        } else {
            continue;
        }

        if ($indexkey === null) {
            $result[] = $val;
        } elseif ($indexkey == -1 || array_key_exists($indexkey, $value)) {
            $result[($indexkey == -1) ? $subarray : $array[$subarray][$indexkey]] = $val;
        }
    }

    return $result;
}

function nama_file($str)
{
    return preg_replace('/[^a-zA-Z0-9\s]\./', '', strip_tags($str));
}

function alfanumerik($str)
{
    return preg_replace('/[^a-zA-Z0-9]/', '', htmlentities($str));
}

function alfanumerik_spasi($str)
{
    return preg_replace('/[^a-zA-Z0-9\s]/', '', htmlentities($str));
}

function bilangan($str)
{
    if ($str == null) {
        return null;
    }

    return preg_replace('/[^0-9]/', '', strip_tags($str));
}

function bilangan_spasi($str)
{
    return preg_replace('/[^0-9\s]/', '', strip_tags($str));
}

function bilangan_titik($str)
{
    return preg_replace('/[^0-9\.]/', '', strip_tags($str));
}

function alfanumerik_kolon($str)
{
    return preg_replace('/[^a-zA-Z0-9:]/', '', strip_tags($str));
}

//hanya berisi karakter alfanumerik dan titik
function alfanumerik_titik($str)
{
    return preg_replace('/[^a-zA-Z0-9\.]/', '', strip_tags($str));
}

function nomor_surat_keputusan($str)
{
    return preg_replace('/[^a-zA-Z0-9 \.\-\/]/', '', $str);
}

// Nama hanya boleh berisi karakter alpha, spasi, titik, koma, tanda petik dan strip
function nama($str)
{
    return preg_replace("/[^a-zA-Z '\\.,\\-]/", '', strip_tags($str));
}

function nama_desa($str)
{
    return preg_replace("/[^a-zA-Z '\\.,`\\-]/", '', strip_tags($str));
}

// Cek  nama hanya boleh berisi karakter alpha, spasi, titik, koma, tanda petik dan strip
function cekNama($str)
{
    return preg_match("/[^a-zA-Z '\\.,\\-]/", strip_tags($str));
}

// Nama hanya boleh berisi karakter alfanumerik, spasi dan strip
function nama_terbatas($str)
{
    return preg_replace('/[^a-zA-Z0-9 \\-]/', '', $str);
}

// Alamat hanya boleh berisi karakter alpha, numerik, spasi, titik, koma, tanda petik, strip dan garis miring
function alamat($str)
{
    return preg_replace("/[^a-zA-Z0-9 '\\.,\\-]/", '', htmlentities($str));
}

// Koordinat peta hanya boleh berisi numerik ,minus dan desimal
function koordinat($str)
{
    return preg_replace('/[^-?(?:\\d+|\\d{1,3}(?:,\\d{3})+)(?:\\.\\d+)?$]/', '', htmlentities($str));
}

// Email hanya boleh berisi karakter alpha, numeric, titik, strip dan Tanda et,
function email($str)
{
    return preg_replace('/[^a-zA-Z0-9@\\.\\-]/', '', htmlentities($str));
}

// website hanya boleh berisi karakter alpha, numeric, titik, titik dua dan garis miring
function alamat_web($str)
{
    return preg_replace('/[^a-zA-Z0-9:\\/\\.\\-]/', '', htmlentities($str));
}

// Format wanrna #803c3c dan rgba(131,127,127,1)
if (! function_exists('warna')) {
    function warna($str)
    {
        return preg_replace('/[^a-zA-Z0-9\\#\\,\\.\\(\\)]/', '', $str ?? '#000000');
    }
}

function buat_slug($data_slug)
{
    return $data_slug['thn'] . '/' . $data_slug['bln'] . '/' . $data_slug['hri'] . '/' . $data_slug['slug'];
}

function namafile($str)
{
    $tgl = date('d_m_Y');

    return urlencode(underscore($str, true, true) . '_' . $tgl);
}

function luas($int = 0, $satuan = 'meter')
{
    if (($int / 10000) >= 1) {
        $ukuran        = $int / 10000;
        $pisah         = explode('.', $ukuran);
        $luas['ha']    = number_format($pisah[0]);
        $luas['meter'] = round(($ukuran - $luas['ha']) * 10000, 2);
    } else {
        $luas['ha']    = 0;
        $luas['meter'] = round($int, 2);
    }

    return ($int != 0) ? $luas[$satuan] : null;
}

function list_mutasi($mutasi = [])
{
    if ($mutasi) {
        foreach ($mutasi as $item) {
            $div   = ($item['jenis_mutasi'] == 2) ? 'class="error"' : null;
            $hasil = "<p {$div}>";
            $hasil .= $item['sebabmutasi'];
            $hasil .= ! empty($item['no_c_desa']) ? ' ' . ket_mutasi_persil($item['jenis_mutasi']) . ' C No ' . sprintf('%04s', $item['no_c_desa']) : null;
            $hasil .= ! empty($item['luasmutasi']) ? ', Seluas ' . number_format($item['luasmutasi']) . ' m<sup>2</sup>, ' : null;
            $hasil .= ! empty($item['tanggalmutasi']) ? tgl_indo_out($item['tanggalmutasi']) . '<br />' : null;
            $hasil .= ! empty($item['keterangan']) ? $item['keterangan'] : null;
            $hasil .= '</p>';

            echo $hasil;
        }
    }
}

function ket_mutasi_persil($id = 0)
{
    if ($id == 1) {
        $ket = 'dari';
    } else {
        $ket = 'ke';
    }

    return $ket;
}

function status_sukses($outp, $gagal_saja = false, $msg = '')
{
    $CI = &get_instance();
    if ($msg) {
        $CI->session->error_msg = $msg;
    }
    if ($gagal_saja) {
        if (! $outp) {
            $CI->session->success = -1;
        }
    } else {
        $CI->session->success = $outp ? 1 : -1;
    }
}

// https://stackoverflow.com/questions/11807115/php-convert-kb-mb-gb-tb-etc-to-bytes
function convertToBytes(string $from)
{
    $units  = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
    $number = substr($from, 0, -2);
    $suffix = strtoupper(substr($from, -2));

    //B or no suffix
    if (is_numeric(substr($suffix, 0, 1))) {
        return preg_replace('/[^\d]/', '', $from);
    }

    $exponent = array_flip($units);
    $exponent = $exponent[$suffix] ?? null;
    if ($exponent === null) {
        return null;
    }

    return $number * (1024 ** $exponent);
}

/**
 * Disalin dari FeedParser.php
 * Load the whole contents of a web page
 *
 * @param    string
 * @param mixed $url
 *
 * @return string
 */
function getUrlContent($url)
{
    if (empty($url)) {
        throw new Exception('URL to parse is empty!.');

        return false;
    }
    if (! in_array(explode(':', $url)[0], ['http', 'https'])) {
        throw new Exception('URL harus http atau https');

        return false;
    }
    if ($content = @file_get_contents($url)) {
        return $content;
    }

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $content = curl_exec($ch);
    $error   = curl_error($ch);

    curl_close($ch);

    if (empty($error)) {
        return $content;
    }

    log_message('error', "Error occured while loading url by cURL. <br />\n" . $error);

    return false;
}

function crawler()
{
    $file = APPPATH . 'config/crawler-user-agents.json';
    $data = json_decode(file_get_contents($file), true);

    foreach ($data as $entry) {
        if (preg_match('/' . strtolower($entry['pattern']) . '/', $_SERVER['HTTP_USER_AGENT'])) {
            return true;
        }
    }

    return false;
}

function pre_print_r($data)
{
    echo '<pre>' . print_r($data, true) . '</pre>';
}

// Kode Wilayah Dengan Titik
// Dari 5201142005 --> 52.01.14.2005
function kode_wilayah($kode_wilayah)
{
    $kode_prov_kab_kec = str_split(substr($kode_wilayah, 0, 6), 2);
    $kode_desa         = (strlen($kode_wilayah) > 6) ? '.' . substr($kode_wilayah, 6) : '';

    return implode('.', $kode_prov_kab_kec) . $kode_desa;
}

// Dari 0892611042612 --> +6292611042612 untuk redirect WA
function format_telpon(string $no_telpon, $kode_negara = '+62')
{
    $awalan = substr($no_telpon, 0, 2);

    if ($awalan == '62') {
        return '+' . $no_telpon;
    }

    return $kode_negara . substr($no_telpon, 1, strlen($no_telpon));
}

// https://stackoverflow.com/questions/6158761/recursive-php-function-to-replace-characters/24482733
function strReplaceArrayRecursive($replacement = [], $strArray = false, $isReplaceKey = false)
{
    if (! is_array($strArray)) {
        return str_replace(array_keys($replacement), array_values($replacement), $strArray);
    }

    $newArr = [];

    foreach ($strArray as $key => $value) {
        $replacedKey = $key;
        if ($isReplaceKey) {
            $replacedKey = str_replace(array_keys($replacement), array_values($replacement), $key);
        }
        $newArr[$replacedKey] = strReplaceArrayRecursive($replacement, $value, $isReplaceKey);
    }

    return $newArr;
}

function get_domain(string $url)
{
    $parse = parse_url($url);

    return preg_replace('#^(http(s)?://)?w{3}\.#', '$1', $parse['host']);
}

function get_antrian($antrian)
{
    return substr_replace($antrian, '-', 6, 0);
}

function get_nik($nik = '0')
{
    if (substr($nik, 0, 1) !== '0') {
        return $nik;
    }

    return '0';
}

// Sama dengan nik sementara
function get_nokk($nokk = '0')
{
    return get_nik($nokk);
}

// https://stackoverflow.com/questions/24043400/php-check-if-ipaddress-is-local/37725041
function isLocalIPAddress($IPAddress)
{
    if (strpos($IPAddress, '127.0.') === 0) {
        return true;
    }

    return ! filter_var($IPAddress, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
}

function unique_slug($tabel = null, $judul = null, $id = null, $field = 'slug', $separator = '-')
{
    if ($tabel && $judul) {
        $CI = &get_instance();

        $slug      = url_title($judul, $separator, true);
        $cek_slug  = true;
        $n         = 1;
        $slug_unik = $slug;

        while ($cek_slug) {
            if ($id) {
                $CI->db->where('id !=', $id);
            }
            $cek_slug = $CI->db->get_where($tabel, [$field => $slug_unik])->num_rows();
            if ($cek_slug) {
                $slug_unik = $slug . '-' . $n++;
            }
        }

        return $slug_unik;
    }

    return null;
}

// Kode format lampiran surat
function kode_format($lampiran = '')
{
    $str = strtoupper(str_replace('.php', '', $lampiran));

    return str_replace(',', ', ', $str);
}

/**
 * Determine if the given key exists in the provided array.
 *
 * @param array|ArrayAccess $array
 * @param int|string        $key
 *
 * @return bool
 */
function exists($array, $key)
{
    if ($array instanceof \ArrayAccess) {
        return $array->offsetExists($key);
    }

    return array_key_exists($key, $array);
}

/**
 * Remove one or many array items from a given array using "dot" notation.
 *
 * @param array        $array
 * @param array|string $keys
 *
 * @return void
 */
function forget(&$array, $keys)
{
    $original = &$array;
    $keys     = (array) $keys;

    if (count($keys) === 0) {
        return;
    }

    foreach ($keys as $key) {
        // if the exact key exists in the top-level, remove it
        if (exists($array, $key)) {
            unset($array[$key]);

            continue;
        }

        $parts = explode('.', $key);
        // clean up before each pass
        $array = &$original;

        while (count($parts) > 1) {
            $part = array_shift($parts);

            if (isset($array[$part]) && is_array($array[$part])) {
                $array = &$array[$part];
            } else {
                continue 2;
            }
        }
        unset($array[array_shift($parts)]);
    }
}

/**
 * Get all of the given array except for a specified array of keys.
 *
 * @param array        $array
 * @param array|string $keys
 *
 * @return array
 */
function except($array, $keys)
{
    forget($array, $keys);

    return $array;
}

/**
 * Get the directory size
 *
 * @param string $directory
 *
 * @return int
 *
 * https://stackoverflow.com/questions/478121/how-to-get-directory-size-in-php
 */
function dirSize($directory)
{
    $size = 0;

    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory)) as $file) {
        $size += $file->getSize();
    }

    return $size;
}

function getSizeDB()
{
    $CI = &get_instance();

    $query = "SELECT
        TABLE_SCHEMA AS DB_Name,
        count(TABLE_SCHEMA) AS total_tables,
        SUM(TABLE_ROWS) AS total_tables_row,
        ROUND(sum(data_length + index_length)) AS 'size'
        FROM information_schema.TABLES
        WHERE TABLE_SCHEMA = '{$CI->db->database}'
        GROUP BY TABLE_SCHEMA
    ";

    return $CI->db->query($query)->row();
}

function idm($kode_desa, $tahun)
{
    $cache = 'idm_' . $tahun . '_' . $kode_desa . '.json';

    return get_instance()->cache->pakai_cache(static function () use ($kode_desa, $tahun) {
        if (! cek_koneksi_internet()) {
            return (object) ['error_msg' => 'Periksa koneksi internet Anda.'];
        }

        try {
            $client   = new \GuzzleHttp\Client();
            $response = $client->get(config_item('api_idm') . "/{$kode_desa}/{$tahun}", [
                'headers' => [
                    'X-Requested-With' => 'XMLHttpRequest',
                ],
                'verify' => false,
            ]);

            return json_decode($response->getBody()->getContents())->mapData;
        } catch (Exception $e) {
            log_message('error', $e->getMessage());

            return (object) ['error_msg' => 'Tidak dapat mengambil data IDM.'];
        }
    }, $cache, 604800);
}

function sdgs()
{
    $kode_desa      = setting('kode_desa_bps');
    $kode_kecamatan = substr($kode_desa, 0, 6);
    $kode_kabupaten = substr($kode_desa, 0, 4);
    $kode_provinsi  = substr($kode_desa, 0, 2);
    $cache          = 'sdgs_' . $kode_desa . '.json';

    if (! empty($kode_desa)) {
        return get_instance()->cache->pakai_cache(static function () use ($kode_provinsi, $kode_kabupaten, $kode_kecamatan, $kode_desa) {
            if (! cek_koneksi_internet()) {
                return (object) ['error_msg' => 'Periksa koneksi internet Anda.'];
            }

            try {
                $client   = new \GuzzleHttp\Client();
                $response = $client->get(config_item('api_sdgs') . "province_code={$kode_provinsi}&city_code={$kode_kabupaten}&district_code={$kode_kecamatan}&village_code={$kode_desa}", [
                    'headers' => [
                        'X-Requested-With' => 'XMLHttpRequest',
                    ],
                    'verify' => false,
                ]);

                return (object) collect(json_decode($response->getBody()->getContents()))
                    ->map(static function ($item, $key) {
                        if ($key === 'data') {
                            return collect($item)->map(static function ($item) {
                                $item->image = last(explode('/', $item->image));

                                return (object) $item;
                            });
                        }

                        return $item;
                    })
                    ->toArray();
            } catch (Exception $e) {
                log_message('error', $e->getMessage());

                return (object) ['error_msg' => 'Tidak dapat mengambil data SDGS.'];
            }
        }, $cache, 604800);
    }

    return (object) ['error_msg' => 'Kode Desa BPS belum ditentukan. Periksa pengaturan <a href="#" style="text-decoration:none;" data-remote="false" data-toggle="modal" data-target="#pengaturan"><strong>Kode Desa BPS&nbsp;(<i class="fa fa-gear"></i>)</a>'];
}

function menu_slug($url)
{
    $CI = &get_instance();
    $CI->load->model('first_artikel_m');

    $cut = explode('/', $url);

    switch ($cut[0]) {
        case 'artikel':
            $data = $CI->first_artikel_m->get_artikel_by_id($cut[1]);
            $url  = ($data) ? ($cut[0] . '/' . buat_slug($data)) : ($url);
            break;

        case 'kategori':
            $data = $CI->first_artikel_m->get_kategori($cut[1]);
            $url  = ($data) ? ('artikel/' . $cut[0] . '/' . $data['slug']) : ($url);
            break;

        case 'data-suplemen':
            $CI->load->model('suplemen_model');
            $data = $CI->suplemen_model->get_suplemen($cut[1]);
            $url  = ($data) ? ($cut[0] . '/' . $data['slug']) : ($url);
            break;

        case 'data-kelompok':
        case 'data-lembaga':
            $CI->load->model('kelompok_model');
            $data = $CI->kelompok_model->get_kelompok($cut[1]);
            $url  = ($data) ? ($cut[0] . '/' . $data['slug']) : ($url);
            break;

            /*
                * TODO : Jika semua link pada tabel menu sudah tdk menggunakan first/ lagi
                * Ganti hapus case dibawah ini yg datanya diambil dari tabel menu dan ganti default adalah $url;
                */
        case 'arsip':
        case 'data_analisis':
        case 'ambil_data_covid':
        case 'informasi_publik':
        case 'load_aparatur_desa':
        case 'load_apbdes':
        case 'load_aparatur_wilayah':
        case 'peta':
        case 'data-wilayah':
        case 'status-idm':
        case 'status-sdgs':
        case 'lapak':
        case 'pembangunan':
        case 'galeri':
        case 'pengaduan':
        case 'data-vaksinasi':
        case 'peraturan-desa':
        case 'pemerintah':
        case 'layanan-mandiri':
            break;

        default:
            $url = 'first/' . $url;
            break;
    }

    return site_url($url);
}

function gelar($gelar_depan = null, $nama = null, $gelar_belakang = null)
{
    // Gelar depan
    if ($gelar_depan) {
        $nama = $gelar_depan . ' ' . $nama;
    }

    // Gelar belakang
    if ($gelar_belakang) {
        $nama = $nama . ', ' . $gelar_belakang;
    }

    return $nama;
}

function default_file($new_file = null, $default = null)
{
    return file_exists(FCPATH . $new_file) ? asset($new_file, false) : asset(str_replace('assets/', '', $default));
}

// https://stackoverflow.com/questions/6824002/capitalize-last-letter-of-a-string
function uclast($str)
{
    return strrev(ucfirst(strrev($str)));
}

function kasus_lain($kategori = null, $str = null)
{
    $pendidikan = [
        ' Tk',
        ' Sd',
        ' Sltp',
        ' Slta',
        ' Slb',
        ' Iii/s',
        ' Iii',
        ' Ii',
        ' Iv',
    ];

    $pekerjaan = [
        '(pns)',
        '(tni)',
        '(polri)',
        ' Ri ',
        'Dpr-ri',
        'Dpd',
        'Bpk',
        'Dprd',
    ];

    $daftar_ganti = ${$kategori};

    if (null === $kategori || count($daftar_ganti) <= 0) {
        return $str;
    }

    return str_ireplace($daftar_ganti, array_map('strtoupper', $daftar_ganti), $str);
}

if (! function_exists('encrypt')) {
    /**
     * - Fungsi untuk encrypt string.
     *
     * @param string $str
     *
     * @return string
     */
    function encrypt($str = '')
    {
        $CI = &get_instance();
        $CI->load->library('encryption');

        $result = $CI->encryption->encrypt($str);

        $result = strtr(
            $result,
            [
                '+' => '.',
                '=' => '-',
                '/' => '~',
            ]
        );

        return $result;
    }
}

if (! function_exists('decrypt')) {
    /**
     * - Fungsi untuk decrypt string.
     *
     * @param string $str
     *
     * @return string
     */
    function decrypt($str = '')
    {
        $CI = &get_instance();
        $CI->load->library('encryption');

        $str = strtr(
            $str,
            [
                '.' => '+',
                '-' => '=',
                '~' => '/',
            ]
        );

        $result = $CI->encryption->decrypt($str);

        return $result;
    }
}

if (! function_exists('form_kode_isian')) {
    /**
     * - Fungsi untuk bersihkan kode isian.
     *
     * @param string $str
     *
     * @return string
     */
    function form_kode_isian($str)
    {
        return '[form_' . preg_replace('/\s+/', '_', preg_replace('/[^A-Za-z0-9& ]/', '', strtolower($str))) . ']';
    }
}
