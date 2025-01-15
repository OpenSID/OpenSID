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

use App\Enums\SasaranEnum;
use App\Enums\Statistik\StatistikEnum;
use App\Models\Bantuan;
use App\Models\FormatSurat;
use App\Models\Menu;
use App\Models\RefJabatan;
use App\Models\Suplemen;
use App\Models\SuratDinas;
use App\Models\Wilayah;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use voku\helper\AntiXSS;
use Modules\Kehadiran\Models\JamKerja;
use Modules\Kehadiran\Models\Kehadiran;

// Kode laporan statistik
define('JUMLAH', 666);
define('BELUM_MENGISI', 777);
define('TOTAL', 888);

// Kode laporan mandiri di tabel komentar
define('LAPORAN_MANDIRI', 'pesan-mandiri');

// Kode artikel terkait agenda
define('AGENDA', 'agenda');

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

define('PENGGUNAAN_BARANG', serialize([
    '01' => 'Pemerintah Desa',
    '02' => 'Badan Permusyawaratan Desa',
    '03' => 'PKK',
    '04' => 'LKMD',
    '05' => 'Karang Taruna',
    '06' => 'RW',
    '07' => 'Puskesdes',
]));

/**
 * Ambil Versi
 *
 * Mengembalikan nomor versi aplikasi
 */
function AmbilVersi(): string
{
    return VERSION . (PREMIUM ? '-premium' : '');
}

/**
 * Ambil Current Version
 *
 * Mengembalikan nomor current_version
 */
function currentVersion(): string
{
    return substr_replace(substr(VERSION, 0, 4), '.', 2, 0);
}

/**
 * favico_desa
 *
 * Mengembalikan path lengkap untuk file favico desa
 *
 * @param mixed $favico
 */
function favico_desa($favico = 'favicon.ico'): string
{
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
 */
function gambar_desa(?string $nama_file = null, $type = false, $file = false): string
{
    if (is_file(FCPATH . LOKASI_LOGO_DESA . $nama_file)) {
        return ($file ? FCPATH : base_url()) . LOKASI_LOGO_DESA . $nama_file;
    }

    // type FALSE = logo, TRUE = kantor
    $default = ($type) ? 'opensid_kantor.jpg' : 'opensid_logo.png';

    return ($file ? FCPATH : base_url()) . "assets/files/logo/{$default}";
}

function session_error($pesan = ''): void
{
    // $_SESSION['error_msg'] = $pesan;
    // $_SESSION['success']   = -1;

    get_instance()->session->set_userdata([
        'error_msg' => $pesan,
        'success'   => -1,
    ]);
}

function session_error_clear(): void
{
    get_instance()->session->unset_userdata(['error_msg', 'success']);
}

function session_success(): void
{
    get_instance()->session->set_userdata([
        'error_msg' => '',
        'success'   => 1,
    ]);
}

// Untuk mengirim data ke OpenSID tracker
function httpPost($url, $params): ?string
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

        return null;
    } catch (Exception $e) {
        log_message('error', $e);

        return null;
    }

    return $response->getBody()->getContents();
}

/**
 * Ambil data desa dari pantau.opensid.my.id berdasarkan config_item('kode_desa')
 *
 * @return object|null
 */
function get_data_desa(string $kode_desa)
{
    try {
        $response = (new Client())->get(config_item('server_pantau') . '/index.php/api/wilayah/kodedesa?kode=' . $kode_desa, [
            'headers' => [
                'X-Requested-With' => 'XMLHttpRequest',
                'Authorization'    => 'Bearer ' . config_item('token_pantau'),
            ],
            'timeout'         => 5,
            'connect_timeout' => 4,
            // 'verify'          => false,
        ]);
    } catch (ClientException $cx) {
        log_message('error', $cx);

        return null;
    } catch (Exception $e) {
        log_message('error', $e);

        return null;
    }

    return json_decode($response->getBody()->getContents(), null);
}

/**
 * Cek ada koneksi internet.
 *
 * @param string $sCheckHost Default: www.google.com
 */
function cek_koneksi_internet(string $sCheckHost = 'www.google.com'): bool
{
    if (! setting('notifikasi_koneksi')) {
        return true;
    }

    $connected = @fsockopen($sCheckHost, 80, $errno, $errstr, 5);

    if ($connected) {
        fclose($connected);

        return true;
    }
    log_message('error', 'Gagal menghubungi ' . $sCheckHost . ' dengan status error ' . $errno . ' - ' . $errstr);

    return false;
}

function cek_bisa_akses_site($url): bool
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_exec($ch);
    $error = curl_error($ch);

    curl_close($ch);

    return $error === '';
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
function myErrorHandler($code, $message, $file, $line): void
{
    // Khusus untuk mencatat masalah dalam pemanggilan httpPost di track_model.php
    if (strpos($message, 'curl_exec') !== false) {
        $_SESSION['no_curl'] = 'y';
        echo '<strong>Apabila halamannya tidak tampil, coba di-refresh.</strong>';
        // Ulangi url yang memanggil fungsi tracker.
        redirect(base_url("index.php/{$_SESSION['balik_ke']}"));
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
function fatalErrorShutdownHandler(): void
{
    $last_error = error_get_last();
    
    if ($last_error && isset($last_error['type'], $last_error['message'], $last_error['file'], $last_error['line']) && $last_error['type'] === E_ERROR) {
        myErrorHandler(E_ERROR, $last_error['message'], $last_error['file'], $last_error['line']);
    }
}

function get_dynamic_title_page_from_path(): string
{
    $parse = str_replace([
        '/first',
    ], '', $_SERVER['PATH_INFO']);
    $explo = explode('/', $parse);

    $title   = '';
    $counter = count($explo);

    for ($i = 0; $i < $counter; $i++) {
        $t = trim($explo[$i]);
        if ($t !== '' && $t !== '1' && $t !== '0') {
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

function log_time(string $msg): void
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
function isPHP($file, $filename): bool
{
    $ext = get_extension($filename);
    if ($ext === '.php') {
        return true;
    }

    $handle = fopen($file, 'rb');
    $buffer = stream_get_contents($handle);
    if (preg_match('/<\?php|<script|__halt_compiler|<html/i', $buffer)) {
        fclose($handle);

        return true;
    }
    fclose($handle);

    return false;
}

function get_extension($filename): string
{
    $ext = explode('.', strtolower($filename));

    return '.' . end($ext);
}

function max_upload(): int
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

    $config  = identitas();
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
function xcopy($src = '', $dest = '', $exclude = [], $only = []): void
{
    if (! file_exists($dest)) {
        mkdir($dest, 0755, true);
    }

    foreach (scandir($src) as $file) {
        $srcfile  = rtrim($src, '/') . '/' . $file;
        $destfile = rtrim($dest, '/') . '/' . $file;
        if (! is_readable($srcfile)) {
            continue;
        }
        if ($exclude && in_array($file, $exclude)) {
            continue;
        }
        if ($file === '.') {
            continue;
        }
        if ($file === '..') {
            continue;
        }
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

function sql_in_list($list_array)
{
    if (empty($list_array)) {
        return false;
    }

    $prefix = $list = '';

    foreach ($list_array as $value) {
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
 * popup  : true kalau berkas ditampilkan pada popup
 */
function ambilBerkas(?string $nama_berkas, $redirect_url = null, $unique_id = null, string $lokasi = LOKASI_ARSIP, $tampil = false, $popup = false)
{
    $CI = &get_instance();
    $CI->load->helper('download');

    if (! preg_match('/^(?:[a-z0-9_-]|\.(?!\.))+$/iD', $nama_berkas)) {
        $pesan = 'Nama berkas tidak valid';
        if ($redirect_url) {
            if ($popup) {
                echo $pesan;

exit;
            }
                session_error($pesan);
                set_session('error', $pesan);
                redirect($redirect_url);

        } else {
            show_404();
        }
    }

    // Tentukan path berkas (absolut)
    $pathBerkas = FCPATH . $lokasi . $nama_berkas;
    $pathBerkas = str_replace('/', DIRECTORY_SEPARATOR, $pathBerkas);
    // Redirect ke halaman surat masuk jika path berkas kosong atau berkasnya tidak ada
    if (! file_exists($pathBerkas)) {
        $pesan = 'Berkas tidak ditemukan';
        if ($redirect_url) {
            if ($popup) {
                echo $pesan;

exit;
            }
                $_SESSION['success']   = -1;
                $_SESSION['error_msg'] = $pesan;
                set_session('error', $pesan);
                redirect($redirect_url);

        } else {
            show_404();
        }
    }
    // OK, berkas ada. Ambil konten berkasnya

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
        header('Content-Length: ' . filesize($pathBerkas));
        header('Cache-Control: private, no-transform, no-store, must-revalidate');

        return readfile($pathBerkas);
    }

    force_download($nama_berkas, file_get_contents($pathBerkas));
}

/**
 * @param array 		(0 => (kolom1 => teks, kolom2 => teks, ..), 1 => (kolom1 => teks, kolom2 => teks. ..), ..)
 * @param mixed $data
 *
 * @return string dalam bentuk siap untuk autocomplete, mengambil teks dari setiap kolom
 */
function autocomplete_data_ke_str($data): string
{
    $keys   = array_keys($data[0] ?? []);
    $values = [];

    foreach ($keys as $key) {
        $values = [...$values, ...array_column($data, $key)];
    }
    $values = array_unique($values);
    sort($values);

    return '["' . strtolower(implode('","', $values)) . '"]';
}

// Periksa apakah nilai bilangan Romawi
// https://recalll.co/?q=How%20to%20convert%20a%20Roman%20numeral%20to%20integer%20in%20PHP?&type=code
function is_angka_romawi($roman): bool
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
    $chunksize = 1024 * 1024; // how many bytes per chunk the user wishes to read
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

function alfa_spasi($str): ?string
{
    return preg_replace('/[^a-zA-Z ]/', '', strip_tags($str));
}

// https://www.php.net/manual/en/function.array-column.php
/**
 * @param mixed      $columnkey
 * @param mixed|null $indexkey
 *
 * @return mixed[]
 */
function array_column_ext(array $array, $columnkey, $indexkey = null): array
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

function nama_file($str): ?string
{
    return preg_replace('/[^a-zA-Z0-9\s]\./', '', strip_tags($str));
}

function alfanumerik($str): ?string
{
    return preg_replace('/[^a-zA-Z0-9]/', '', htmlentities($str));
}

function alfanumerik_spasi($str): ?string
{
    return preg_replace('/[^a-zA-Z0-9\s\-]/', '', htmlentities($str));
}

function bilangan($str)
{
    if ($str == null) {
        return null;
    }

    return preg_replace('/[^0-9]/', '', strip_tags($str));
}

function bilangan_spasi($str): ?string
{
    return preg_replace('/[^0-9\s]/', '', strip_tags($str));
}

function bilangan_titik($str): ?string
{
    return preg_replace('/[^0-9\.]/', '', strip_tags($str));
}

function alfanumerik_kolon($str): ?string
{
    return preg_replace('/[^a-zA-Z0-9:]/', '', strip_tags($str));
}

//hanya berisi karakter alfanumerik dan titik
function alfanumerik_titik($str): ?string
{
    return preg_replace('/[^a-zA-Z0-9\.]/', '', strip_tags($str));
}

function nomor_surat_keputusan($str)
{
    return preg_replace('/[^a-zA-Z0-9 \.\-\/,]/', '', $str);
}

function nama_peraturan_desa($str)
{
    return preg_replace('/[^a-zA-Z0-9 \.\-\/,()]/', '', $str);
}

// Nama hanya boleh berisi karakter alpha, spasi, titik, koma, tanda petik dan strip
function nama($str): ?string
{
    return preg_replace("/[^a-zA-Z '\\.,\\-]/", '', strip_tags($str));
}

function nama_desa($str): ?string
{
    return preg_replace("/[^a-zA-Z '\\.,`\\-\\/\\(\\)]/", '', strip_tags($str));
}

// Cek  nama hanya boleh berisi karakter alpha, spasi, titik, koma, tanda petik dan strip
function cekNama($str)
{
    return preg_match("/[^a-zA-Z '\\.,\\-]/", strip_tags($str));
}

// Nama hanya boleh berisi karakter alfanumerik, spasi, slash(/) dan strip
function nama_terbatas($str)
{
    return preg_replace('/[^a-zA-Z0-9 \\/\\-]/', '', $str);
}

// Judul hanya boleh berisi a-zA-Z0-9()[]&_:=°%'".,/ \-
function judul($str): ?string
{
    return preg_replace('/[^a-zA-Z0-9()[]&_:;=°%\'".,\\/ \\-]/', '', strip_tags($str));
}

// Nama surat hanya boleh berisi karakter alfanumerik, spasi, strip, (, )
function nama_surat($str)
{
    return preg_replace('/[^a-zA-Z0-9 \\-\\(\\)]/', '', $str);
}

// Alamat hanya boleh berisi karakter alpha, numerik, spasi, titik, koma, tanda petik, strip dan garis miring
function alamat($str): ?string
{
    return preg_replace("/[^a-zA-Z0-9 '\\.,\\-]/", '', htmlentities($str));
}

// Koordinat peta hanya boleh berisi numerik ,minus dan desimal
function koordinat($str): ?string
{
    if (empty($str)) {
        return null;
    }

    return preg_replace('/[^-?(?:\\d+|\\d{1,3}(?:,\\d{3})+)(?:\\.\\d+)?$]/', '', htmlentities($str));
}

// Email hanya boleh berisi karakter alpha, numeric, titik, strip dan Tanda et,
function email($str): ?string
{
    return preg_replace('/[^a-zA-Z0-9@._\\-]/', '', htmlentities($str));
}

// website hanya boleh berisi karakter alpha, numeric, titik, titik dua dan garis miring
function alamat_web($str): ?string
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

function buat_slug(array $data_slug): string
{
    return $data_slug['thn'] . '/' . $data_slug['bln'] . '/' . $data_slug['hri'] . '/' . $data_slug['slug'];
}

function namafile($str): string
{
    return Str::slug($str, '_') . '_' . date('d_m_Y');
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

function list_mutasi($mutasi = []): void
{
    if ($mutasi) {
        foreach ($mutasi as $item) {
            $div   = ($item['jenis_mutasi'] == 2) ? 'class="error"' : null;
            $hasil = "<p {$div}>";
            $hasil .= $item['sebabmutasi'];
            $hasil .= empty($item['no_c_desa']) ? null : ' ' . ket_mutasi_persil($item['jenis_mutasi']) . ' C No ' . sprintf('%04s', $item['no_c_desa']);
            $hasil .= empty($item['luasmutasi']) ? null : ', Seluas ' . number_format($item['luasmutasi']) . ' m<sup>2</sup>, ';
            $hasil .= empty($item['tanggalmutasi']) ? null : tgl_indo_out($item['tanggalmutasi']) . '<br />';
            $hasil .= empty($item['keterangan']) ? null : $item['keterangan'];
            $hasil .= '</p>';

            echo $hasil;
        }
    }
}

function ket_mutasi_persil($id = 0): string
{
    return $id == 1 ? 'dari' : 'ke';
}

function status_sukses($outp, $gagal_saja = false, $msg = ''): void
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
 * @param string
 * @param mixed $url
 *
 * @return string
 */
function getUrlContent($url)
{
    if (empty($url)) {
        throw new Exception('URL to parse is empty!.');
    }
    if (! in_array(explode(':', $url)[0], ['http', 'https'])) {
        throw new Exception('URL harus http atau https');
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

    if ($error === '') {
        return $content;
    }

    log_message('error', "Error occured while loading url by cURL. <br />\n" . $error);

    return false;
}

function crawler(): bool
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

function pre_print_r($data): void
{
    echo '<pre>' . print_r($data, true) . '</pre>';
}

// Kode Wilayah Dengan Titik
// Dari 5201142005 --> 52.01.14.2005
function kode_wilayah($kode_wilayah): string
{
    $kode_prov_kab_kec = str_split(substr($kode_wilayah, 0, 6), 2);
    $kode_desa         = (strlen($kode_wilayah) > 6) ? '.' . substr($kode_wilayah, 6) : '';

    return implode('.', $kode_prov_kab_kec) . $kode_desa;
}

// Dari 0892611042612 --> +6292611042612 untuk redirect WA
function format_telpon(string $no_telpon, string $kode_negara = '+62'): string
{
    $awalan = substr($no_telpon, 0, 2);

    if ($awalan === '62') {
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

function get_domain(string $url): ?string
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
function isLocalIPAddress($IPAddress): bool
{
    if (strpos($IPAddress, '127.0.') === 0) {
        return true;
    }

    return ! filter_var($IPAddress, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
}

function unique_slug($tabel = null, $judul = null, $id = null, $field = 'slug', $separator = '-', $config_id = null)
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
            $cek_slug = $CI->db->where('config_id', $config ?? identitas('id'))->get_where($tabel, [$field => $slug_unik])->num_rows();
            if ($cek_slug) {
                $slug_unik = $slug . '-' . $n++;
            }
        }

        return $slug_unik;
    }

    return null;
}

// Kode format lampiran surat
function kode_format($lampiran = ''): string
{
    $str = strtoupper(str_replace('.php', '', $lampiran));

    return str_replace(',', ', ', $str);
}

/**
 * Determine if the given key exists in the provided array.
 *
 * @param array|ArrayAccess $array
 * @param int|string        $key
 */
function exists($array, $key): bool
{
    if ($array instanceof ArrayAccess) {
        return $array->offsetExists($key);
    }

    return array_key_exists($key, $array);
}

/**
 * Remove one or many array items from a given array using "dot" notation.
 *
 * @param array        $array
 * @param array|string $keys
 */
function forget(&$array, $keys): void
{
    $original = &$array;
    $keys     = (array) $keys;

    if ($keys === []) {
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
    $ci         = &get_instance();
    $cache      = "idm_{$tahun}_{$kode_desa}.json";
    $cache_path = DESAPATH . "/cache/{$cache}";

    // Periksa apakah file cache ada dan tidak kadaluarsa
    if (file_exists($cache_path)) {
        $data = unserialize(file_get_contents($cache_path));
        $ci->cache->save($cache, $data['data'], YEAR); // Ubah ke satu tahun
    }

    // Ambil cache IDM
    if ($data = $ci->cache->get($cache)) {
        return $data;
    }

    // Periksa koneksi internet
    if (! cek_koneksi_internet()) {
        return (object) ['error_msg' => 'Periksa koneksi internet Anda.'];
    }

    $url = config_item('api_idm') . "/{$kode_desa}/{$tahun}";

    // Ambil dari API IDM
    try {
        $client   = new Client();
        $response = $client->get($url, [
            'headers' => ['X-Requested-With' => 'XMLHttpRequest'],
            'verify'  => false,
        ]);

        if ($response->getStatusCode() === 200) {
            $body_content = $response->getBody()->getContents();

            if (! empty($body_content)) {
                $idm_data = json_decode($body_content, null)->mapData;
                $ci->cache->save($cache, $idm_data, YEAR);

                return $ci->cache->get($cache);
            }
        }
    } catch (Exception $e) {
        log_message('error', $e->getMessage());
    }

    // Pesan error jika data gagal diambil
    $pesan_error = 'Tidak dapat mengambil data IDM.<br>';
    $pesan_error .= 'ID Desa ' . $kode_desa . ' pada tahun ' . $tahun . ' tidak dapat dimuat: ';
    $pesan_error .= '<a href="' . $url . '" target="_blank">' . $url . '</a>';

    return (object) ['error_msg' => $pesan_error];
}

function sdgs()
{
    $ci         = &get_instance();
    $kode_desa  = setting('kode_desa_bps');
    $cache      = "sdgs_{$kode_desa}.json";
    $cache_path = DESAPATH . "/cache/{$cache}";

    if (empty($kode_desa)) {
        return (object) [
            'error_msg' => 'Kode Desa BPS belum ditentukan. Periksa pengaturan <a href="#" style="text-decoration:none;" data-remote="false" data-toggle="modal" data-target="#pengaturan"><strong>Kode Desa BPS&nbsp;(<i class="fa fa-gear"></i>)</a>',
        ];
    }

    // Periksa apakah file cache ada dan perbaharui cache jika kadaluarsa
    if (file_exists($cache_path)) {
        $data = unserialize(file_get_contents($cache_path));
        $ci->cache->save($cache, $data['data'], YEAR); // Ubah ke satu tahun
    }

    // Ambil cache SDGs
    if ($data = $ci->cache->get($cache)) {
        return $data;
    }

    // Periksa koneksi internet
    if (! cek_koneksi_internet()) {
        return (object) ['error_msg' => 'Periksa koneksi internet Anda.'];
    }

    $url = config_item('api_sdgs') . $kode_desa;

    // Ambil dari API SDGs
    try {
        $client   = new Client();
        $response = $client->get($url, [
            'headers' => ['X-Requested-With' => 'XMLHttpRequest'],
            'verify'  => false,
        ]);

        if ($response->getStatusCode() === 200) {
            $body_content = $response->getBody()->getContents();

            if (! empty($body_content)) {
                $data = (object) collect(json_decode($body_content, null))
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

                $ci->cache->save($cache, $data, YEAR);

                return $ci->cache->get($cache);
            }
        }
    } catch (Exception $e) {
        log_message('error', $e->getMessage());
    }

    // Pesan error jika data gagal diambil
    $pesan_error = 'Tidak dapat mengambil data SDGS.<br>';
    $pesan_error .= 'ID Desa ' . $kode_desa . ' tidak dapat dimuat: ';
    $pesan_error .= '<a href="' . $url . '" target="_blank">' . $url . '</a>';

    return (object) ['error_msg' => $pesan_error];
}

function google_recaptcha()
{
    $ci = &get_instance();

    // periksa koneksi
    if (! cek_koneksi_internet()) {
        return (object) ['error_msg' => 'Periksa koneksi internet Anda.'];
    }

    try {
        $client = new Client([
            'base_uri' => config_item('api_google_recaptcha'),
            'timeout'  => 2.0,
        ]);

        $response = $client->request('POST', 'siteverify', [
            'query' => [
                'secret'   => setting('google_recaptcha_secret_key'),
                'response' => trim($ci->input->post('g-recaptcha-response')),
                'remoteip' => $ci->input->ip_address(),
            ],
        ]);
    } catch (Exception $e) {
        log_message('error', $e->getMessage());
    }

    return json_decode($response->getBody());
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
            $suplemen    = Suplemen::withCount('terdata')->find($cut[1]);
            $data        = $suplemen ? $suplemen->toArray() : [];
            $data['jml'] = $data['terdata_count'];
            $url         = ($data) ? ($cut[0] . '/' . ($data['slug'] ?? $cut[1])) : ($url);
            break;

        case 'data-kelompok':
        case 'data-lembaga':
            $CI->load->model('kelompok_model');
            $data = $CI->kelompok_model->get_kelompok($cut[1]);
            $url  = ($data) ? ($cut[0] . '/' . $data['slug']) : ($url);
            break;

        case 'dpt':
            $url = 'data-dpt';
            break;

        case 'statistik':
            $cek = StatistikEnum::slugFromKey($cut[1]);
            $url = $cek ? "data-statistik/{$cek}" : "first/{$url}";

            break;

        case 'informasi_publik':
            $url = 'informasi-publik';
            break;

            /*
                * TODO : Jika semua link pada tabel menu sudah tdk menggunakan first/ lagi
                * Ganti hapus case dibawah ini yg datanya diambil dari tabel menu dan ganti default adalah $url;
                */
        case 'arsip':
        case 'data_analisis':
        case 'ambil_data_covid':
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
        case 'inventaris':
        case 'struktur-organisasi-dan-tata-kerja':
        case 'data-kesehatan':
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
        return $nama . ', ' . $gelar_belakang;
    }

    return $nama;
}

function default_file($new_file = null, $default = null)
{
    // jika $default ada kata Module/ maka diabaikan, maka langsung kembalikan $default
    // contoh: http://opensid.test/Modules/Kehadiran/Views/assets/css/style.css
    if (preg_match('/modules\//', $default)) {
        $asset = $default;
    } else {
        $asset = asset(str_replace('assets/', '', $default));
    }

    return file_exists(FCPATH . $new_file) ? asset($new_file, false) : $asset;
}

// https://stackoverflow.com/questions/6824002/capitalize-last-letter-of-a-string
function uclast($str): string
{
    return strrev(ucfirst(strrev(strtolower($str))));
}

function kasus_lain($kategori = null, $str = null)
{
    $pendidikan = [
        'Tk',
        'Sd',
        'Sltp',
        'Slta',
        'Slb',
        'Iii/s',
        'Iii',
        'Ii',
        'Iv',
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

    if (null === $kategori || count($daftar_ganti ?? []) <= 0) {
        return $str;
    }

    return str_ireplace($daftar_ganti, array_map('strtoupper', $daftar_ganti), $str);
}

if (! function_exists('updateConfigFile')) {
    function updateConfigFile(string $key, string $value): void
    {
        // log_message('error', 'updateConfigFile ' . $key . ' - ' . $value);

        if ($key === 'password') {
            $file    = LOKASI_CONFIG_DESA . 'database.php';
            $pattern = '/(\$db\[\'default\'\]\[\'password\'\]\s*=\s*\'.*\';)/';
            $newKey  = "\$db['default']['password'] = '{$value}';";
        }
        $configContent = file_get_contents($file);
        if (preg_match($pattern, $configContent)) {
            $configContent = preg_replace(
                $pattern,
                $newKey,
                $configContent
            );
        } else {
            $configContent .= PHP_EOL . $newKey;
        }

        file_put_contents($file, $configContent);
    }
}

if (! function_exists('form_kode_isian')) {
    /**
     * - Fungsi untuk bersihkan kode isian.
     *
     * @param string $str
     * @param string $prefix
     */
    function form_kode_isian($str, $prefix = ''): string
    {
        return '[form_' . preg_replace('/\s+/', '_', preg_replace('/[^A-Za-z0-9& ]/', '', strtolower($str))) . $prefix . ']';
    }
}

if (! function_exists('kades')) {
    /**
     * - Fungsi untuk mengambil data jabatan kades.
     *
     * @return array|object
     */
    function kades()
    {
        return RefJabatan::getKades();
    }
}

if (! function_exists('sekdes')) {
    /**
     * - Fungsi untuk mengambil data jabatan sekdes.
     *
     * @return array|object
     */
    function sekdes()
    {
        return RefJabatan::getSekdes();
    }
}

if (! function_exists('super_admin')) {
    /**
     * - Fungsi untuk mengambil id dengan grup superadmin.
     *
     * @return int
     */
    function super_admin()
    {
        $ci = &get_instance();
        $ci->load->model('user_model');

        return $ci->user_model->get_super_admin();
    }
}

if (! function_exists('is_super_admin')) {
    /**
     * - Fungsi untuk mengecek apakah user adalah super admin.
     */
    function is_super_admin(): bool
    {
        return (int) ci_auth()->id === super_admin();
    }
}

if (! function_exists('ref')) {
    /**
     * - Fungsi untuk mengambil data tabel refrensi.
     *
     * @param mixed $alias
     *
     * @return array|object
     */
    function ref($alias)
    {
        return match ($alias) {
            'tweb_wil_clusterdesa' => Wilayah::dusun()->get()->pluck('dusun', 'id')->map(static function ($item, $key) {
                return (object) [
                    'id'   => $key,
                    'nama' => $item,
                ];
            })->values()->toArray(),

            default => ci()->db->get($alias)->result(),
        };
    }
}

if (! function_exists('getFormatIsian')) {
    /**
     * - Fungsi untuk mengembalikan format kode isian.
     *
     * @param mixed $kode_isian
     * @param bool  $case_sentence (opsional) - Menentukan apakah harus mereturn semua kasus kalimat
     *
     * @return array
     */
    function getFormatIsian($kode_isian, $case_sentence = false)
    {
        $netral = str_replace([' ', '[', ']'], '', $kode_isian);

        if ($case_sentence) {
            // jika gambar maka langsung kembalikan tanpa [ ]
            if (preg_match('/^<img/', $kode_isian)) {
                return [
                    'normal' => $kode_isian,
                ];
            }
            // NIK versi lama, banyak digunakan di template
            if (strpos($netral, 'nik') !== false) {
                $netral = ucfirst(uclast($netral));
            }

            return [
                'normal' => '[' . $netral . ']',
            ];
        }

        $strtolower = strtolower($netral);
        $ucfirst    = ucfirst($strtolower);
        $suffix     = in_array($strtolower, ['terbilang', 'hitung']) ? '[ ]' : '';

        return [
            'normal'  => '[' . ucfirst(uclast($netral)) . ']' . $suffix,
            'lower'   => '[' . $strtolower . ']' . $suffix,
            'ucfirst' => '[' . $ucfirst . ']' . $suffix,
            'ucwords' => '[' . substr_replace($ucfirst, strtoupper(substr($ucfirst, 2, 1)), 2, 1) . ']' . $suffix,
            'upper'   => '[' . substr_replace($ucfirst, strtoupper(substr($ucfirst, 1, 1)), 1, 1) . ']' . $suffix,
        ];
    }
}

/**
 * Buat hash password (bcrypt) dari string sebuah password
 *
 * @param [type]  $string  [description]
 *
 * @return [type]  [description]
 */
function generatePasswordHash($string): string
{
    // Pastikan inputnya adalah string
    $string = is_string($string) ? $string : (string) $string;
    // Buat hash password
    $pwHash = password_hash($string, PASSWORD_BCRYPT);
    // Cek kekuatan hash, regenerate jika masih lemah
    if (password_needs_rehash($pwHash, PASSWORD_BCRYPT)) {
        return password_hash($string, PASSWORD_BCRYPT);
    }

    return $pwHash;
}

if (! function_exists('resetCacheDesa')) {
    function resetCacheDesa(): void
    {
        $CI = &get_instance();
        $CI->load->helper('directory');
        // Hapus isi folder desa/cache
        $dir = config_item('cache_path');

        foreach (directory_map($dir) as $file) {
            if ($file !== 'index.html') {
                unlink($dir . DIRECTORY_SEPARATOR . $file);
            }
        }
    }
}

if (! function_exists('kosongkanFolder')) {
    function kosongkanFolder($directory = null, $except = []): void
    {
        if (null === $directory) {
            return;
        }

        $CI = &get_instance();
        $CI->load->helper('directory');

        $except = array_merge(['.htaccess', 'index.html', '.gitignore'], $except);

        foreach (directory_map($directory) as $file) {
            if (! in_array($file, $except)) {
                unlink($directory . DIRECTORY_SEPARATOR . $file);
            }
        }
    }
}

if (! function_exists('updateAppKey')) {
    function updateAppKey($app_key): void
    {
        file_put_contents(DESAPATH . 'app_key', $app_key);
    }
}

if (! function_exists('nextVersion')) {
    function nextVersion($version = null): string
    {
        $migrasi = str_replace('.', '', $version ?? currentVersion());
        $migrasi = substr($migrasi, 0, 4);
        $tahun   = substr($migrasi, 0, 2);
        $bulan   = substr($migrasi, -2);

        if ($bulan > 12) {
            $tahun++;
            $bulan = 1;
        } else {
            $bulan++;
            $bulan = '0' . $bulan;
        }

        return $tahun . $bulan;
    }
}

if (! function_exists('getVariableName')) {
    function getVariableName($class = null, $value = null)
    {
        if (null === $class || null === $value) {
            return null;
        }

        $reflection   = new ReflectionClass($class);
        $constants    = $reflection->getConstants();
        $variableName = array_search($value, $constants);

        return $variableName !== false ? $variableName : null;
    }
}

if (! function_exists('checkWebsiteAccessibility')) {
    function checkWebsiteAccessibility($url): bool
    {
        $options = [
            'http' => [
                'method'  => 'GET',
                'timeout' => 3,
            ],
        ];
        $context = stream_context_create($options);
        $headers = @get_headers($url, 0, $context);

        if ($headers) {
            $status = substr($headers[0], 9, 3);
            if ($status === '200') {
                return true;
            }

            $status = "(Status: {$status})";
        }

        log_message('notice', "Website tidak dapat diakses {$status}");

        return false;
    }
}

/**
 * Hapus Kata 'Kab' atau 'Kota' dari nama kabupaten/kota
 *
 * Mengembalikan nama kabupaten/kota tanpa kata 'Kab' atau 'Kota'
 *
 * @return string
 */
if (! function_exists('hapus_kab_kota')) {
    function hapus_kab_kota($str)
    {
        return preg_replace('/kab |kota /i', '', $str);
    }
}

function artikel_get_id($id)
{
    $CI = &get_instance();
    $CI->load->model('first_artikel_m');

    return $CI->first_artikel_m->get_artikel_by_id($id);
}

/**
 * @param string
 *
 * @return string
 */
if (! function_exists('bersihkan_xss')) {
    function bersihkan_xss($str)
    {
        $antiXSS = new AntiXSS();
        $antiXSS->removeEvilHtmlTags(['iframe']);
        $antiXSS->addEvilAttributes(['http-equiv', 'content']);

        return $antiXSS->xss_clean($str);
    }
}

/**
 * Kode isian nomor_surat bisa ditentukan panjangnya, diisi dengan '0' di sebelah kiri
 * Misalnya [nomor_surat, 3] akan menghasilkan seperti '012'
 *
 * @param mixed|null $nomor
 * @param mixed      $format
 */
function substitusiNomorSurat($nomor = null, $format = '')
{
    // tanpa panjang nomor surat
    $format = case_replace('[nomor_surat]', $nomor, $format);

    // jika terdapat panjang nomor surat
    if (preg_match_all('/\[nomor_surat,\s*(\d+)\]/i', $format, $matches)) {
        foreach ($matches[0] as $match) {
            $parts         = explode(',', $match);
            $panjang       = (int) trim(rtrim($parts[1], ']'));
            $nomor_panjang = str_pad($nomor, $panjang, '0', STR_PAD_LEFT);
            $format        = str_ireplace($match, $nomor_panjang, $format);
        }
    }

    return $format;
}

/**
 * @param mixed $data
 *
 * @return mixed[]
 */
function updateIndex($data): array
{
    $result = [];
    $index  = 2; // dimulai index 2 karena 1 untuk penduduk desa
    if (! empty($data)) {
        foreach ($data as $value) {
            $result[$index] = $value;
            $index++;
        }
    }

    return $result;
}

/**
 * @param string $tanggal
 *
 * @return string
 */
if (! function_exists('formatTanggal')) {
    function formatTanggal($tanggal = null)
    {
        if (null === $tanggal) {
            return setting('ganti_data_kosong');
        }

        return Carbon::parse($tanggal)->translatedFormat(setting('format_tanggal_surat'));
    }
}

/**
 * Kode isian tanggal
 *
 * @param string|null $tanggal
 * @param string      $format
 *
 * @return string
 */
if (! function_exists('kodeIsianTanggal')) {
    function kodeIsianTanggal($tanggal = null, $format = '')
    {
        try {
            $formatInput = 'd F Y';
            $tanggal     = $tanggal ? Carbon::createFromFormat($formatInput, $tanggal) : Carbon::now();

            return match ($format) {
                'hari'        => $tanggal->translatedFormat('l'),
                'tgl'         => $tanggal->format('d'),
                'bulan'       => $tanggal->translatedFormat('F'),
                'bulan_angka' => $tanggal->translatedFormat('m'),
                'tahun'       => $tanggal->format('Y'),
                default       => $tanggal->translatedFormat(setting('format_tanggal_surat')),
            };
        } catch (InvalidArgumentException $e) {
            return $tanggal;
        }
    }
}

/**
 * Menghasilkan kode isian tanggal dengan beberapa format
 *
 * @param string|null $tgl
 * @param string      $prefix
 *
 * @return array
 */
if (! function_exists('tanggalLengkap')) {
    function tanggalLengkap($tgl = null, $prefix = '')
    {
        $tgl = formatTanggal($tgl ?? Carbon::now());
        if (! empty($prefix)) {
            $prefix = '_' . $prefix;
        }

        return [
            [
                'judul' => 'Tanggal (Default)',
                'isian' => 'tgl' . $prefix,
                'data'  => $tgl,
            ],
            [
                'judul' => 'Tanggal (Dengan Hari)',
                'isian' => 'tgl_hari' . $prefix,
                'data'  => kodeIsianTanggal($tgl, 'hari') . ', ' . $tgl,
            ],
            [
                'case_sentence' => true,
                'judul'         => 'Tanggal (Angka)',
                'isian'         => 'tanggal' . $prefix,
                'data'          => kodeIsianTanggal($tgl, 'tgl'),
            ],
            [
                'case_sentence' => false,
                'judul'         => 'Hari',
                'isian'         => 'hari' . $prefix,
                'data'          => kodeIsianTanggal($tgl, 'hari'),
            ],
            [
                'case_sentence' => false,
                'judul'         => 'Bulan',
                'isian'         => 'bulan' . $prefix,
                'data'          => kodeIsianTanggal($tgl, 'bulan'),
            ],
            [
                'case_sentence' => true,
                'judul'         => 'Bulan (Angka)',
                'isian'         => 'bulan_angka' . $prefix,
                'data'          => kodeIsianTanggal($tgl, 'bulan_angka'),
            ],
            [
                'case_sentence' => true,
                'judul'         => 'Tahun',
                'isian'         => 'tahun' . $prefix,
                'data'          => kodeIsianTanggal($tgl, 'tahun'),
            ],
        ];
    }
}

if (! function_exists('daftar_statistik')) {
    function daftar_statistik()
    {
        $data = collect(StatistikEnum::allStatistik())->map(static fn ($items, $kategori) => collect($items)->map(static fn ($item): array => [
            'key'   => $item['key'],
            'slug'  => $item['slug'],
            'label' => $item['label'],
            'url'   => "data-statistik/{$item['slug']}",
        ])->all())->all();
        $kategori_bantuan = [
            [
                'key'   => 'bantuan_penduduk',
                'slug'  => 'bantuan-penduduk',
                'label' => 'Penerima Bantuan Penduduk',
                'url'   => 'first/statistik/bantuan_penduduk',
            ],
            [
                'key'   => 'bantuan_keluarga',
                'slug'  => 'bantuan-keluarga',
                'label' => 'Penerima Bantuan Keluarga',
                'url'   => 'first/statistik/bantuan_keluarga',
            ],
        ];
        $setiap_bantuan = Bantuan::all()->map(static fn ($item): array => [
            'key'   => "50{$item->id}",
            'slug'  => "50{$item->id}",
            'label' => $item->nama,
            'url'   => "first/statistik/50{$item->id}",
        ])->toArray();
        $data['bantuan'] = array_merge($kategori_bantuan, $setiap_bantuan);
        $data['lainnya'] = [
            [
                'key'   => 'dpt',
                'slug'  => 'dpt',
                'label' => 'Calon Pemilih',
                'url'   => 'data-dpt',
            ],
            [
                'key'   => 'data-wilayah',
                'slug'  => 'data-wilayah',
                'label' => 'Populasi Per Wilayah',
                'url'   => 'data-wilayah',
            ],
        ];

        return $data;
    }
}

if (! function_exists('menu_statistik_aktif')) {
    function menu_statistik_aktif()
    {
        return Menu::where('link', 'like', 'statistik%')->orWhereIn('link', ['dpt', 'data-wilayah'])->active()->pluck('link', 'link');
    }
}

if (! function_exists('isNestedArray')) {
    function isNestedArray($array, $json = false): bool
    {
        if (is_array($array)) {
            foreach ($array as $element) {
                if ($json) {
                    $element = json_decode($element, null);
                }
                if (is_array($element)) {
                    return true;
                }
            }
        }

        return false;
    }
}

if (! function_exists('getSuratBawaanTinyMCE')) {
    function getSuratBawaanTinyMCE($url_surat = null)
    {
        $list_data = file_get_contents(DEFAULT_LOKASI_IMPOR . 'template-surat-tinymce.json');

        return collect(json_decode($list_data, true))
            ->when($url_surat, static fn ($collection) => $collection->where('url_surat', $url_surat))->map(static fn ($item) => collect($item)->except('id', 'config_id', 'created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_at', 'margin_cm_to_mm', 'url_surat_sistem', 'url_surat_desa', 'kunci')->toArray());
    }
}

if (! function_exists('restoreSuratBawaanTinyMCE')) {
    function restoreSuratBawaanTinyMCE($id = null)
    {
        $id ??= identitas('id');
        $suratFormats = FormatSurat::withoutConfigId($id)
            ->where('jenis', 3)
            ->get()->keyBy('url_surat');

        $suratBawaanTinyMCE = getSuratBawaanTinyMCE();

        foreach ($suratBawaanTinyMCE as $defaultSurat) {
            $defaultSurat['config_id']  = $id;
            $defaultSurat['form_isian'] = $defaultSurat['form_isian'] ? json_encode($defaultSurat['form_isian']) : null;
            $defaultSurat['kode_isian'] = $defaultSurat['kode_isian'] ? json_encode($defaultSurat['kode_isian']) : null;
            $urlSurat                   = $defaultSurat['url_surat'];
            if (isset($suratFormats[$urlSurat])) {
                $defaultSurat['kunci']   = $suratFormats[$urlSurat]->kunci;
                $defaultSurat['favorit'] = $suratFormats[$urlSurat]->favorit;
            }
            FormatSurat::withoutConfigId($id)->upsert($defaultSurat, ['url_surat', 'config_id']);
        }
    }
}

if (! function_exists('getSuratBawaanDinasTinyMCE')) {
    function getSuratBawaanDinasTinyMCE($url_surat = null)
    {
        $list_data = file_get_contents(DEFAULT_LOKASI_IMPOR . 'template-surat-dinas-tinymce.json');

        return collect(json_decode($list_data, true))
            ->when($url_surat, static fn ($collection) => $collection->where('url_surat', $url_surat))->map(static fn ($item) => collect($item)->except('id', 'config_id', 'created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_at', 'margin_cm_to_mm', 'url_surat_sistem', 'url_surat_desa', 'kunci')->toArray());
    }
}

if (! function_exists('restoreSuratBawaanDinasTinyMCE')) {
    function restoreSuratBawaanDinasTinyMCE($id = null)
    {
        $id ??= identitas('id');

        $suratFormats = SuratDinas::withoutConfigId($id)
            ->where('jenis', 3)
            ->get()->keyBy('url_surat');

        $suratBawaanTinyMCE = getSuratBawaanDinasTinyMCE();

        foreach ($suratBawaanTinyMCE as $defaultSurat) {
            $defaultSurat['config_id']  = $id;
            $defaultSurat['form_isian'] = $defaultSurat['form_isian'] ? json_encode($defaultSurat['form_isian']) : null;
            $defaultSurat['kode_isian'] = $defaultSurat['kode_isian'] ? json_encode($defaultSurat['kode_isian']) : null;
            $urlSurat                   = $defaultSurat['url_surat'];
            if (isset($suratFormats[$urlSurat])) {
                $defaultSurat['kunci']   = $suratFormats[$urlSurat]->kunci;
                $defaultSurat['favorit'] = $suratFormats[$urlSurat]->favorit;
            }
            SuratDinas::withoutConfigId($id)->upsert($defaultSurat, ['url_surat', 'config_id']);
        }
    }
}

if (! function_exists('terjemahkanTerbilang')) {
    function terjemahkanTerbilang($teks)
    {
        $pola = '/\[(terbilang|TeRbilang|Terbilang|TerbilanG|TErbilang)]\[(.+?)]/';

        return preg_replace_callback($pola, static function (array $matches) {
            // jika ada - di depan, maka akan ditambahkan prefix depan yakni Minus
            $prefix = $suffix = '';

            if (strpos($matches[2], '-') === 0) {
                $prefix = 'minus ';
            }

            if (preg_match('/[Rr][pP]/', $matches[2])) {
                $suffix = ' rupiah';
            }

            $ke = $prefix . trim(to_word(preg_replace('/[^0-9\.]/', '', $matches[2]))) . $suffix;

            return caseWord($matches[1], $ke);
        }, $teks);
    }
}

if (! function_exists('caseWord')) {
    /**
     * Mengubah teks sesuai dengan kondisi
     *
     * @param string $condition
     * @param string $teks
     *
     * @return string
     */
    function caseWord($condition, $teks)
    {
        $suffix = $prefix = '';

        // Gelar
        if (in_array(strtolower($condition), ['nama_kepala_desa', 'nama_kepala_camat', 'nama_pamong'])) {
            $pecah = pecah_nama_gelar($teks);

            $teks = $pecah['nama'];

            if ($pecah['gelar_depan']) {
                $prefix = $pecah['gelar_depan'] . ' ';
            }

            if ($pecah['gelar_belakang']) {
                $suffix = ', ' . $pecah['gelar_belakang'];
            }
        }

        // Ganti '/' dengan ---atau--- Hanya untuk pendidikan dan pekerjaan saja
        if (preg_match('/\bpendidikan(?:_[^\s]*)?\b/i', strtolower($condition)) || preg_match('/\bpekerjaan(?:_[^\s]*)?\b/i', strtolower($condition))) {
            $teks = str_replace('/', ' ---atau--- ', $teks);
        }

        // Normal
        if (ctype_upper($condition[0]) && ctype_upper($condition[strlen($condition) - 1])) {
            $teks = set_words($teks);
        } elseif // Huruf kecil semua
        (ctype_lower($condition[0])) {
            $teks = set_words($teks, 'lower');
        } elseif // Huruf besar semua
        (ctype_upper($condition[0]) && ctype_upper($condition[1])) {
            $teks = set_words($teks, 'upper');
        } elseif // Huruf besar di awal kata
        (ctype_upper($condition[0]) && ctype_lower($condition[1])) {
            $teks = set_words($teks, 'ucwords');
        } elseif // Huruf besar di awal kalimat
        (ctype_upper($condition[0])) {
            $teks = set_words($teks, 'ucfirst');
        }

        // kembalikan '---atau---' menjadi '/'
        $teks = str_ireplace(' ---atau--- ', '/', $teks);

        // Kasus lain
        if (preg_match('/\bpendidikan(?:_[^\s]*)?\b/i', strtolower($condition)) || preg_match('/\bpekerjaan(?:_[^\s]*)?\b/i', strtolower($condition))) {
            $teks = kasus_lain('pendidikan', $teks);
            $teks = kasus_lain('pekerjaan', $teks);
        }

        // Kasus lain RT / RW
        if (preg_match('/\balamat(_[^\s]*)?\b/i', strtolower($condition))) {
            $teks = str_ireplace(['Rt', 'Rw'], ['RT', 'RW'], $teks);
        }

        // Return teks asli jika tidak sesuai kondisi
        return $prefix . $teks . $suffix;
    }
}

if (! function_exists('caseHitung')) {
    function caseHitung($teks)
    {
        $pola = '/\[(hitung|HiTung|Hitung|HitunG|HItung)]\[(.+?)]/';
        $teks = str_replace(['[Op+]', '[Op\\]', '[Op*]', '[Op-]'], ['+', '/', '*', '-'], $teks);

        return preg_replace_callback($pola, static function (array $matches) {
            $onlyNumberAndOperator = preg_replace('/[^0-9\+\-\*\/\(\)]/', '', $matches[2]);
            if (strpos($onlyNumberAndOperator, '/0') !== false) {
            return '0';
            }

            $operasi = eval("return {$onlyNumberAndOperator};");

            $ke = caseWord($matches[1], $operasi);

            if (preg_match('/[Rr][pP]/', $matches[2])) {
                // jika hasil operasinya -, maka minus berada di depan Rp. contohnya - Rp. 100.000
                return strpos($ke, '-') === 0 ? str_replace('-', '- Rp. ', rupiah24($ke, 'Rp. ', 0)) : rupiah24($ke, 'Rp. ', 0);
            }

            return $ke;
        }, $teks);
    }
}

if (! function_exists('caseReplaceFoto')) {
    function caseReplaceFoto($teks, $isian_foto = null, $ganti_dengan = null)
    {
        $pola = '/(<img src=")(.*?)(">)/';

        if (empty($ganti_dengan)) {
            return preg_replace($pola, '', $teks);
        }

        return preg_replace_callback($pola, static function (array $matches) use ($isian_foto, $ganti_dengan) {
            $cek1 = str_replace('"', '', explode(' ', $matches[2])[0]);
            $cek2 = str_replace('"', '', explode(' ', preg_replace('/^.*src="/', '', $isian_foto))[0]);

            if ($cek1 === $cek2) {
                return str_replace($cek2, $ganti_dengan, $matches[0]);
            }

            return $allImg;
        }, $teks);
    }
}

if (! function_exists('usia')) {
    /**
     * Menghitung usia berdasarkan tanggal lahir
     *
     * @param string $tanggal_lahir
     * @param string $tanggal_akhir
     * @param string $format
     *
     * contoh format : $y Tahun $m Bulan $d Hari
     *
     * return string
     */
    function usia($tanggal_lahir, $tanggal_akhir = null, $format = '%y Tahun'): string
    {
        $tanggal_akhir ??= date('Y-m-d');
        $tanggal_lahir = Carbon::parse($tanggal_lahir);
        $tanggal_akhir = Carbon::parse($tanggal_akhir);
        $usia          = $tanggal_lahir->diff($tanggal_akhir);

        return $usia->format($format);
    }
}

if (! function_exists('bungkusKotak')) {
    function bungkusKotak($teks, $setting = [])
    {
        $pola = '/\[#{1,2}\s*(.*?)\s*#{1,2}\]/';

        return preg_replace_callback($pola, static function (array $matches) use ($setting): string {
            $rapat = false;
            if (substr($matches[0], 1, 2) === '##') {
                $rapat = true;

                return tampilkanKotak($matches[1], $rapat, $setting);
            }

            return tampilkanKotak($matches[1], $rapat, $setting);
        }, $teks);
    }
}

if (! function_exists('tampilkanKotak')) {
    function tampilkanKotak(array $teks, $rapat = false, $setting = []): string
    {
        $jarakAntarKolom = $setting['jarak'] ?? 2;
        $lebarKolom      = $setting['lebar'] ?? 5;
        $collapse        = $rapat ? 'border-collapse: collapse;' : '';
        $style           = 'border: 1px solid #000; margin:0px;';
        if ($rapat) {
            $style .= 'border-collapse: collapse;';
        }
        $table = '<table style="' . $collapse . ' margin:0px; padding:0px" cellspacing="' . $jarakAntarKolom . '" border=0>';
        $table .= '<tr>';

        for ($i = 0; $i < strlen($teks); $i++) {
            $table .= '<td width=' . $lebarKolom . ' align="center" style=' . $style . '>' . $teks[$i] . '</td>';
        }
        $table .= '</tr>';

        return $table . '</table>';
    }
}

if (! function_exists('grup_kode_isian')) {
    /**
     * Membuat ulang kode isian berdasarkan masing-masing kategori
     *
     * @param array $kode_isian
     * @param bool  $individu
     *
     * @return array
     */
    function grup_kode_isian($kode_isian, $individu = true)
    {
        return collect($kode_isian)->groupBy(static fn ($item) => $item->kategori ?? 'individu')->map(static fn ($items) => $items->map(static fn ($item): array => (array) $item))->when(! $individu, static fn ($collection) => $collection->filter(static fn ($item): bool => isset($item['kategori']) && $item['kategori'] !== 'individu'))
            ->toArray();
    }
}

if (! function_exists('get_hari')) {
    /**
     * Mengembalikan nama hari berdasarkan tanggal
     *
     * @param string $tanggal
     *
     * @return string
     */
    function get_hari($tanggal)
    {
        $hari = Carbon::createFromFormat('d-m-Y', $tanggal)->locale('id');

        return $hari->dayName;
    }
}

if (! function_exists('akas')) {
    /**
     * Class registry
     *
     * This function acts as a singleton. If the requested class does not
     * exist it is instantiated and set to a static variable. If it has
     * previously been instantiated the variable is returned.
     *
     * @param string	the class name being requested
     * @param string	the directory where the class should be found
     * @param mixed	an optional argument to pass to the class constructor
     * @param mixed      $class
     * @param mixed      $directory
     * @param mixed|null $param
     *
     * @return object
     */
    function akas(string $class, string $directory = '', $param = null)
    {
        static $_classes = [];

        // Does the class exist? If so, we're done...
        if (isset($_classes[$class])) {
            return $_classes[$class];
        }

        $name = false;

        // Look for the class first in the local application/libraries folder
        // then in the native system/libraries folder
        foreach ([APPPATH, BASEPATH] as $path) {
            if (file_exists($path . $directory . '/' . $class . '.php')) {
                $name = 'CI_' . $class;

                if (class_exists($name, false) === false) {
                    require_once $path . $directory . '/' . $class . '.php';
                }

                break;
            }
        }

        // Is the request a class extension? If so we load it too
        if (file_exists(APPPATH . $directory . '/' . config_item('subclass_prefix') . $class . '.php')) {
            $name = config_item('subclass_prefix') . $class;

            if (class_exists($name, false) === false) {
                require_once APPPATH . $directory . '/' . $name . '.php';
            }
        }

        // Did we find the class?
        if ($name === false) {
            // Note: We use exit() rather than show_error() in order to avoid a
            // self-referencing loop with the Exceptions class
            set_status_header(503);
            echo 'Unable to locate the specified class: ' . $class . '.php';

            exit(5); // EXIT_UNK_CLASS
        }

        // Keep track of what we just loaded
        is_loaded($class);

        $_classes[$class] = isset($param)
            ? new $name($param)
            : new $name();

        return $_classes[$class];
    }
}

if (! function_exists('forceRemoveDir')) {
    function forceRemoveDir(string $dir): void
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);

            foreach ($objects as $object) {
                if ($object !== '.' && $object !== '..') {
                    $item = $dir . '/' . $object;

                    if (is_dir($item)) {
                        forceRemoveDir($item);
                    } else {
                        unlink($item);
                    }
                }
            }

            reset($objects);
            rmdir($dir);
        }
    }
}

if (! function_exists('getStatistikLabel')) {
    /**
     * Mendapatkan label statistik berdasarkan kode laporan.
     *
     * @param mixed $lap
     * @param mixed $stat
     * @param mixed $namaDesa
     *
     * @return array
     */
    function getStatistikLabel($lap, $stat, $namaDesa)
    {
        $akhiran  = ' di ' . ucwords(setting('sebutan_desa') . ' ' . $namaDesa) . ', ' . date('Y');
        $kategori = 'Penduduk';
        $label    = 'Jumlah dan Persentase Penduduk Berdasarkan ' . $stat . $akhiran;

        if ((int) $lap > 50) {
            // Untuk program bantuan, $lap berbentuk '50<program_id>'
            $program_id               = substr($lap, 2);
            $program                  = Bantuan::select(['nama', 'sasaran'])->find($program_id)->toArray();
            $program['judul_sasaran'] = SasaranEnum::valueOf($program['sasaran']);
            $kategori                 = 'Bantuan';
            $label                    = 'Jumlah dan Persentase Peserta ' . $program['nama'] . $akhiran;
        } elseif ((int) $lap > 20 || $lap === 'kelas_sosial') {
            $kategori = 'Keluarga';
            $label    = 'Jumlah dan Persentase Keluarga Berdasarkan ' . $stat . $akhiran;
        } else {
            switch ($lap) {
                case 'bantuan_keluarga':
                    $kategori = 'Bantuan';
                    $label    = 'Jumlah dan Persentase ' . $stat . $akhiran;
                    break;

                case 'bdt':
                    $kategori = 'RTM';
                    $label    = 'Jumlah dan Persentase Rumah Tangga Berdasarkan ' . $stat . $akhiran;
                    break;

                case '1':
                    $label = 'Jumlah dan Persentase Penduduk Berdasarkan Aktivitas atau Jenis Pekerjaannya ' . $akhiran;
                    break;

                case '0':
                case '14':
                    $label = 'Jumlah dan Persentase Penduduk Berdasarkan ' . $stat . ' yang Dicatat dalam Kartu Keluarga ' . $akhiran;
                    break;

                case '13':
                case '15':
                    $label = 'Jumlah dan Persentase Penduduk Menurut Kelompok ' . $stat . $akhiran;
                    break;

                case '16':
                    $label = 'Jumlah dan Persentase Penduduk Menurut Penggunaan Alat Keluarga Berencana dan Jenis Kelamin ' . $akhiran;
                    break;

                case '13':
                    $label = 'Jumlah Keluarga dan Penduduk Berdasarkan Wilayah RT ' . $akhiran;
                    break;

                case '4':
                    $label = 'Jumlah Penduduk yang Memiliki Hak Suara ' . $stat . $akhiran;
                    break;

                case 'hamil':
                    $label = 'Jumlah dan Persentase Penduduk Perempuan Berdasarkan ' . $stat . $akhiran;
                    break;
            }
        }

        return [
            'kategori' => $kategori,
            'label'    => $label,
        ];
    }
}

function waktu($waktu_terakhir): string
{
    $waktu_sekarang = time();
    $selisih_detik  = $waktu_sekarang - strtotime($waktu_terakhir);

    $detik  = $selisih_detik;
    $menit  = floor($selisih_detik / 60);
    $jam    = floor($selisih_detik / 3600);
    $hari   = floor($selisih_detik / 86400);
    $minggu = floor($selisih_detik / 604800);
    $bulan  = floor($selisih_detik / 2_628_000);
    $tahun  = floor($selisih_detik / 31_536_000);

    if ($detik <= 60) {
        return 'Baru saja';
    }
    if ($menit <= 60) {
        return "{$menit} menit yang lalu";
    }
    if ($jam <= 24) {
        return "{$jam} jam yang lalu";
    }
    if ($hari <= 7) {
        return "{$hari} hari yang lalu";
    }
    if ($minggu <= 4) {
        return "{$minggu} minggu yang lalu";
    }
    if ($bulan <= 12) {
        return "{$bulan} bulan yang lalu";
    }

        return "{$tahun} tahun yang lalu";

}

function versiUmumSetara($version): string
{
    $formatVersi = 'y.m'; // contoh format 24.01
    $versiSetara = Carbon::createFromFormat($formatVersi, $version)->addMonths(7);

    return $versiSetara->format($formatVersi);
}

function copyFavicon(): void
{
    if (file_exists(LOKASI_LOGO_DESA . 'favicon.ico')) {
        copy(FCPATH . LOKASI_LOGO_DESA . 'favicon.ico', FCPATH . 'favicon.ico');
    } else {
        copy(FCPATH . LOKASI_FILES_LOGO . 'favicon.ico', FCPATH . 'favicon.ico');
    }
}

function dummyQrCode($logo)
{
    $qrCode = [
        'isiqr'   => 'dummy qrcode OpenSID',
        'urls_id' => 'http://dummy.com',
        'logoqr'  => gambar_desa($logo, false, true),
        'sizeqr'  => 6,
        'foreqr'  => '#000000',
    ];

    $qrCode['viewqr'] = qrcode_generate($qrCode);

    return $qrCode;
}

function randomCode($length)
{
    return substr(base_convert(sha1(uniqid(random_int(0, mt_getrandmax()))), 16, 36), 0, $length);
}

function encodeId($plainText)
{
    $key         = time();
    $random_code = randomCode(20);
    $base64      = base64_encode($random_code . ',' . $plainText . ',' . $key . ',' . $plainText);
    $base64url   = strtr($base64, '+/=', '-  ');

    return trim($base64url);
}

function decodeId($plainText)
{
    $base64url = strtr($plainText, '-  ', '+/=');
    $base64    = base64_decode($base64url, true);
    $exp       = explode(',', $base64);

    return ($exp[1] !== $exp[3]) ? $plainText : $exp[1];
}

if (! function_exists('cek_kehadiran')) {
    /**
     * Cek perangkat lupa absen
     */
    function cek_kehadiran(): void
    {
        $cek_libur = JamKerja::libur()->first();
        $cek_jam   = JamKerja::jamKerja()->first();
        $kehadiran = Kehadiran::where('status_kehadiran', 'hadir')->where('jam_keluar', null)->get();
        if ($kehadiran->count() > 0 && ($cek_jam != null || $cek_libur != null)) {
            foreach ($kehadiran as $data) {
                Kehadiran::lupaAbsen($data->tanggal);
            }
        }
    }
}
