<?php

define("VERSION", '18.08');
define("LOKASI_LOGO_DESA", 'desa/logo/');
define("LOKASI_ARSIP", 'desa/arsip/');
define("LOKASI_CONFIG_DESA", 'desa/config/');
define("LOKASI_SURAT_DESA", 'desa/surat/');
define("LOKASI_SURAT_FORM_DESA", 'desa/surat/form/');
define("LOKASI_SURAT_PRINT_DESA", 'desa/surat/print/');
define("LOKASI_SURAT_EXPORT_DESA", 'desa/surat/export/');
define("LOKASI_USER_PICT", 'desa/upload/user_pict/');
define("LOKASI_GALERI", 'desa/upload/galeri/');
define("LOKASI_FOTO_ARTIKEL", 'desa/upload/artikel/');
define("LOKASI_FOTO_LOKASI", 'desa/upload/gis/lokasi/');
define("LOKASI_FOTO_AREA", 'desa/upload/gis/area/');
define("LOKASI_FOTO_GARIS", 'desa/upload/gis/garis/');
define("LOKASI_DOKUMEN", 'desa/upload/dokumen/');
define("LOKASI_PENGESAHAN", 'desa/upload/pengesahan/');
define("LOKASI_WIDGET", 'desa/widget/');
define("LOKASI_GAMBAR_WIDGET", 'desa/upload/widget/');

// Kode laporan statistik di mana kode isian belum di isi
define('BELUM_MENGISI', 777);

//
define("MAX_PINDAH", 7);
define("MAX_ANGGOTA", 7);

// Konversi tulisan kode Buku Induk Penduduk ke kode SID
define("KODE_SEX", serialize(array(
    "L" => "1",
    "Lk" => "1",
    "P" => "2",
    "Pr" => "2"
)));
define("KODE_AGAMA", serialize(array(
    strtolower("Islam") => "1",
    strtolower("Kristen") => "2",
    strtolower("Katholik") => "3",
    strtolower("Hindu") => "4",
    strtolower("Budha") => "5",
    strtolower("Konghuchu") => "6",
    strtolower("Aliran Kepercayaan") => "7"
)));
define("KODE_STATUS", serialize(array(
    strtolower("BELUM KAWIN") => "1",
    strtolower("BK") => "1",
    strtolower("KAWIN") => "2",
    strtolower("K") => "2",
    strtolower("CERAI HIDUP") => "3",
    strtolower("CH") => "3",
    strtolower("CERAI MATI") => "4",
    strtolower("CM") => "4"
)));
define("KODE_HUBUNGAN", serialize(array(
    strtolower("KEPALA KELUARGA") => "1",
    strtolower("SUAMI") => "2",
    strtolower("ISTRI") => "3",
    strtolower("ANAK") => "4",
    strtolower("MENANTU") => "5",
    strtolower("CUCU") => "6",
    strtolower("ORANG TUA") => "7",
    strtolower("MERTUA") => "8",
    strtolower("FAMILI LAIN") => "9",
    strtolower("PEMBANTU") => "10",
    strtolower("LAINNYA") => "11"
)));
define("KODE_PENDIDIKAN", serialize(array(
    strtolower("Tidak/Belum Sekolah") => "1",
    strtolower("Belum Tamat SD/Sederajat") => "2",
    strtolower("Tamat SD/Sederajat") => "3",
    strtolower("SLTP/Sederajat") => "4",
    strtolower("SLTA/Sederajat") => "5",
    strtolower("Diploma I/II") => "6",
    strtolower("Akademi/Diploma III/S. Muda") => "7",
    strtolower("Diploma IV/Strata I") => "8",
    strtolower("Strata II") => "9",
    strtolower("Strata III") => "10"
)));
define("KODE_PEKERJAAN", serialize(array(
    strtolower("BELUM/TIDAK BEKERJA") => "1",
    strtolower("MENGURUS RUMAH TANGGA") => "2",
    strtolower("PELAJAR/MAHASISWA") => "3",
    strtolower("PENSIUNAN") => "4",
    strtolower("PEGAWAI NEGERI SIPIL") => "5",
    strtolower("TENTARA NASIONAL INDONESIA") => "6",
    strtolower("KEPOLISIAN RI") => "7",
    strtolower("PERDAGANGAN") => "8",
    strtolower("PETANI/PEKEBUN") => "9",
    strtolower("PETERNAK") => "10",
    strtolower("NELAYAN/PERIKANAN") => "11",
    strtolower("INDUSTRI") => "12",
    strtolower("KONSTRUKSI") => "13",
    strtolower("TRANSPORTASI") => "14",
    strtolower("KARYAWAN SWASTA") => "15",
    strtolower("KARYAWAN BUMN") => "16",
    strtolower("KARYAWAN BUMD") => "17",
    strtolower("KARYAWAN HONORER") => "18",
    strtolower("BURUH HARIAN LEPAS") => "19",
    strtolower("BURUH TANI/PERKEBUNAN") => "20",
    strtolower("BURUH NELAYAN/PERIKANAN") => "21",
    strtolower("BURUH PETERNAKAN") => "22",
    strtolower("PEMBANTU RUMAH TANGGA") => "23",
    strtolower("TUKANG CUKUR") => "24",
    strtolower("TUKANG LISTRIK") => "25",
    strtolower("TUKANG BATU") => "26",
    strtolower("TUKANG KAYU") => "27",
    strtolower("TUKANG SOL SEPATU") => "28",
    strtolower("TUKANG LAS/PANDAI BESI") => "29",
    strtolower("TUKANG JAHIT") => "30",
    strtolower("TUKANG GIGI") => "31",
    strtolower("PENATA RIAS") => "32",
    strtolower("PENATA BUSANA") => "33",
    strtolower("PENATA RAMBUT") => "34",
    strtolower("MEKANIK") => "35",
    strtolower("SENIMAN") => "36",
    strtolower("TABIB") => "37",
    strtolower("PARAJI") => "38",
    strtolower("PERANCANG BUSANA") => "39",
    strtolower("PENTERJEMAH") => "40",
    strtolower("IMAM MESJID") => "41",
    strtolower("PENDETA") => "42",
    strtolower("PASTOR") => "43",
    strtolower("WARTAWAN") => "44",
    strtolower("USTADZ/MUBALIGH") => "45",
    strtolower("JURU MASAK") => "46",
    strtolower("PROMOTOR ACARA") => "47",
    strtolower("ANGGOTA DPR-RI") => "48",
    strtolower("ANGGOTA DPD") => "49",
    strtolower("ANGGOTA BPK") => "50",
    strtolower("PRESIDEN") => "51",
    strtolower("WAKIL PRESIDEN") => "52",
    strtolower("ANGGOTA MAHKAMAH KONSTITUSI") => "53",
    strtolower("ANGGOTA KABINET/KEMENTERIAN") => "54",
    strtolower("DUTA BESAR") => "55",
    strtolower("GUBERNUR") => "56",
    strtolower("WAKIL GUBERNUR") => "57",
    strtolower("BUPATI") => "58",
    strtolower("WAKIL BUPATI") => "59",
    strtolower("WALIKOTA") => "60",
    strtolower("WAKIL WALIKOTA") => "61",
    strtolower("ANGGOTA DPRD PROVINSI") => "62",
    strtolower("ANGGOTA DPRD KABUPATEN/KOTA") => "63",
    strtolower("DOSEN") => "64",
    strtolower("GURU") => "65",
    strtolower("PILOT") => "66",
    strtolower("PENGACARA") => "67",
    strtolower("NOTARIS") => "68",
    strtolower("ARSITEK") => "69",
    strtolower("AKUNTAN") => "70",
    strtolower("KONSULTAN") => "71",
    strtolower("DOKTER") => "72",
    strtolower("BIDAN") => "73",
    strtolower("PERAWAT") => "74",
    strtolower("APOTEKER") => "75",
    strtolower("PSIKIATER/PSIKOLOG") => "76",
    strtolower("PENYIAR TELEVISI") => "77",
    strtolower("PENYIAR RADIO") => "78",
    strtolower("PELAUT") => "79",
    strtolower("PENELITI") => "80",
    strtolower("SOPIR") => "81",
    strtolower("PIALANG") => "82",
    strtolower("PARANORMAL") => "83",
    strtolower("PEDAGANG") => "84",
    strtolower("PERANGKAT DESA") => "85",
    strtolower("KEPALA DESA") => "86",
    strtolower("BIARAWATI") => "87",
    strtolower("WIRASWASTA") => "88",
    strtolower("LAINNYA") => "89"
)));
define("KODE_GOLONGAN_DARAH", serialize(array(
    strtolower('A') => '1',
    strtolower('B') => '2',
    strtolower('AB') => '3',
    strtolower('O') => '4',
    strtolower('A+') => '5',
    strtolower('A-') => '6',
    strtolower('B+') => '7',
    strtolower('B-') => '8',
    strtolower('AB+') => '9',
    strtolower('AB-') => '10',
    strtolower('O+') => '11',
    strtolower('O-') => '12',
    strtolower('TIDAK TAHU') => '13'
)));
define("SASARAN", serialize(array(
    "1" => "Penduduk",
    "2" => "Keluarga / KK",
    "3" => "Rumah Tangga",
    "4" => "Kelompok/Organisasi Kemasyarakatan"
)));
define("KTP_EL", serialize(array(
    strtolower("BELUM") => "1",
    strtolower("KTP-EL") => "2"
)));
define("STATUS_REKAM", serialize(array(
    strtolower("BELUM WAJIB") => "1",
    strtolower("BELUM REKAM") => "2",
    strtolower("SUDAH REKAM") => "3",
    strtolower("CARD PRINTED") => "4",
    strtolower("PRINT READY RECORD") => "5",
    strtolower("CARD SHIPPED") => "6",
    strtolower("SENT FOR CARD PRINTING") => "7",
    strtolower("CARD ISSUED") => "8"
)));
define("TEMPAT_DILAHIRKAN", serialize(array(
    "RS/RB" => "1",
    "Puskemas" => "2",
    "Polindes" => "3",
    "Rumah" => "4",
    "Lainnya" => "5"
)));
define("JENIS_KELAHIRAN", serialize(array(
    "Tunggal" => "1",
    "Kembar 2" => "2",
    "Kembar 3" => "3",
    "Kembar 4" => "4"
)));
define("PENOLONG_KELAHIRAN", serialize(array(
    "Dokter" => "1",
    "Bidan Perawat" => "2",
    "Dukun" => "3",
    "Lainnya" => "4"
)));
define("JENIS_MUTASI", serialize(array(
    "Hapus barang masih baik" => "1",
    "Hapus barang rusak" => "4",
    "Status rusak" => "2",
    "Status diperbaiki" => "3"
)));
define("JENIS_PENGHAPUSAN", serialize(array(
    "Rusak" => "1",
    "Dijual" => "2",
    "Disumbang" => "3"
)));
define("ASAL_INVENTARIS", serialize(array(
    "Dibeli Sendiri" => "1",
    "Bantuan Pemerintah" => "2",
    "Bantuan Provinsi" => "3",
    "Bantuan Kabupaten" => "4",
    "Sumbangan" => "5"
)));

/**
 * Ambil Versi
 *
 * Mengembalikan nomor versi aplikasi
 *
 * @access  public
 * @return  string
 */
function AmbilVersi()
{
    return VERSION;
}

/**
 * LogoDesa
 *
 * Mengembalikan path lengkap untuk file logo desa
 *
 * @access  public
 * @return  string
 */
function LogoDesa($nama_logo)
{
	if (is_file(APPPATH .'../'. LOKASI_LOGO_DESA . $nama_logo)) {
		return $logo_desa = base_url() . LOKASI_LOGO_DESA . $nama_logo;
	}

	return $logo_desa = base_url() . 'assets/files/logo/opensid_logo.png';
}

/**
 * KonfigurasiDatabase
 *
 * Mengembalikan path file konfigurasi database desa
 *
 * @access  public
 * @return  string
 */
function KonfigurasiDatabase()
{
    $konfigurasi_database = LOKASI_CONFIG_DESA . 'database.php';
    return $konfigurasi_database;
}

/**
 * SuratExportDesa
 *
 * Mengembalikan path surat ubahan desa apabila ada.
 * Cek folder semua komponen surat dulu, baru cek folder export
 *
 * @access  public
 * @return  string
 */
function SuratExportDesa($nama_surat)
{
    $surat_export_desa = LOKASI_SURAT_DESA . $nama_surat . "/" . $nama_surat . ".rtf";
    if (is_file($surat_export_desa))
        return $surat_export_desa;
    else
        $surat_export_desa = LOKASI_SURAT_EXPORT_DESA . $nama_surat . ".rtf";
    if (is_file($surat_export_desa)) {
        return $surat_export_desa;
    } else {
        return "";
    }

}

/**
 * SuratCetakDesa
 *
 * Mengembalikan path surat ubahan desa apabila ada
 *
 * @access  public
 * @return  string
 */
function SuratCetakDesa($nama_surat)
{
    $surat_cetak_desa = LOKASI_SURAT_DESA . $nama_surat . "/print_" . $nama_surat . ".php";
    if (is_file($surat_cetak_desa))
        return $surat_cetak_desa;
    else
        $surat_cetak_desa = LOKASI_SURAT_PRINT_DESA . "print_" . $nama_surat . ".php";
    if (is_file($surat_cetak_desa)) {
        return $surat_cetak_desa;
    } else {
        return "";
    }

}

/**
 * SuratExport
 *
 * Mengembalikan path surat export apabila ada, dengan prioritas:
 *    1. surat export ubahan desa
 *    2. surat export asli SID
 *
 * @access  public
 * @return  string
 */
function SuratExport($nama_surat)
{
    if (SuratExportDesa($nama_surat) != "") {
        return SuratExportDesa($nama_surat);
    } elseif (is_file("surat/$nama_surat/$nama_surat.rtf")) {
        return "surat/$nama_surat/$nama_surat.rtf";
    } else {
        return "";
    }
}

/**
 * SuratCetak
 *
 * Mengembalikan path surat cetak apabila ada, dengan prioritas:
 *    1. surat export ubahan desa
 *    2. surat export asli SID
 *
 * @access  public
 * @return  string
 */
function SuratCetak($nama_surat)
{
    if (SuratCetakDesa($nama_surat) != "") {
        return SuratCetakDesa($nama_surat);
    } elseif (is_file("surat/$nama_surat/print_" . $nama_surat . ".php")) {
        return "surat/$nama_surat/print_" . $nama_surat . ".php";
    } else {
        return "";
    }
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
    if (!extension_loaded('curl') OR isset($_SESSION['no_curl']))
        return;

    $postData = '';
    //create name value pairs seperated by &
    foreach ($params as $k => $v) {
        $postData .= $k . '=' . $v . '&';
    }
    $postData = rtrim($postData, '&');

    try {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, count($postData));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

        // Paksa tidak menunggu hasil tracker
        curl_setopt($ch, CURLOPT_FORBID_REUSE, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
        curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        $output = curl_exec($ch);

        if ($output === false) {
            // echo 'Curl error: ' . curl_error($ch);
        }
        curl_close($ch);
        return $output;
    }
    catch (Exception $e) {
        return $e;
    }
}

/**
 * Cek ada koneksi internet.
 *
 * @param            string $sCheckHost Default: www.google.com
 * @return           boolean
 */
function cek_koneksi_internet($sCheckHost = 'www.google.com')
{
    return (bool) @fsockopen($sCheckHost, 80, $iErrno, $sErrStr, 5);
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
    if (strpos($message, 'curl_exec') !== FALSE) {
        $_SESSION['no_curl'] = 'y';
        echo "<strong>Apabila halamannya tidak tampil, coba di-refresh.</strong>";
        // Ulangi url yang memanggil fungsi tracker.
        redirect(base_url() . "index.php/" . $_SESSION['balik_ke']);
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

function ikut_case($format, $str)
{
    $str = strtolower($str);
    if (ctype_upper($format[0]) AND ctype_upper($format[1]))
        return strtoupper($str);
    elseif (ctype_upper($format[0]))
        return ucwords($str);
    else
        return $str;
}
/**
 * Membuat string yang diisi &nbsp; di awal dan di akhir, dengan panjang yang ditentukan.
 *
 * @param            str      Text yang akan ditambahi awal dan akhiran
 * @param            awal     Jumlah karakter &nbsp; pada awal text
 * @param            panjang  Panjang string yang dihasilkan,
 *                            di mana setiap &nbsp; dihitung sebagai satu karakter
 * @return           string berisi text yang telah diberi awalan dan akhiran &nbsp;
 */
function padded_string_fixed_length($str, $awal, $panjang)
{
    $padding         = "&nbsp;";
    $panjang_padding = strlen($padding);
    $panjang_text    = strlen($str);
    $str             = str_pad($str, ($awal * $panjang_padding) + $panjang_text, $padding, STR_PAD_LEFT);
    $str             = str_pad($str, (($panjang - $panjang_text) * $panjang_padding) + $panjang_text, $padding, STR_PAD_RIGHT);
    return $str;
}

function padded_string_center($str, $panjang)
{
    $padding         = "&nbsp;";
    $panjang_padding = strlen($padding);
    $panjang_text    = strlen($str);
    $to_pad          = ($panjang - $panjang_text) / 2;
    for ($i = 0; $i < $to_pad; $i++) {
        $str = $padding . $str . $padding;
    }
    return $str;
}

function get_dynamic_title_page_from_path()
{
    $parse = str_replace(array(
        '/first'
    ), '', $_SERVER['PATH_INFO']);
    $explo = explode('/', $parse);

    $title = '';
    for ($i = 0; $i < count($explo); $i++) {
        $t = trim($explo[$i]);
        if (!empty($t) && $t != '1' && $t != '0') {
            $title .= ((is_numeric($t)) ? ' ' : ' - ') . $t;
        }
    }
    return ucwords(str_replace(array(
        '  ',
        '_'
    ), ' ', $title));
}

function show_zero_as($val, $str)
{
    return (empty($val) ? $str : $val);
}

function log_time($msg)
{
    $now = DateTime::createFromFormat('U.u', microtime(true));
    error_log($now->format("m-d-Y H:i:s.u") . " : " . $msg . "\n", 3, "opensid.log");
}

/*
 * @return - null, kalau tgl_lahir bukan string tanggal
 */
function umur($tgl_lahir)
{
    try {
        $date = new DateTime($tgl_lahir);
    }
    catch (Exception $e) {
        return null;
    }
    $now      = new DateTime();
    $interval = $now->diff($date);
    return $interval->y;
}

// Dari https://stackoverflow.com/questions/4117555/simplest-way-to-detect-a-mobile-device
function isMobile()
{
    return preg_match("/\b(?:a(?:ndroid|vantgo)|b(?:lackberry|olt|o?ost)|cricket|do‌​como|hiptop|i(?:emob‌​ile|p[ao]d)|kitkat|m‌​(?:ini|obi)|palm|(?:‌​i|smart|windows )phone|symbian|up\.(?:browser|link)|tablet(?: browser| pc)|(?:hp-|rim |sony )tablet|w(?:ebos|indows ce|os))/i", $_SERVER["HTTP_USER_AGENT"]);
}

/*
Deteksi file berisi script PHP:
-- extension .php
-- berisi string '<?php', '<?=', '<script'
Perhatian: string '<?', '<%' tidak bisa digunakan sebagai indikator,
karena file image dan PDF juga mengandung string ini.
*/
function isPHP($file, $filename)
{
    $ext = get_extension($filename);
    if ($ext == '.php')
        return true;

    $handle = fopen($file, 'r');
    $buffer = stream_get_contents($handle);
    if (preg_match('/<\?php|<\?=|<script/i', $buffer)) {
        fclose($handle);
        return true;
    }
    fclose($handle);
    return false;
}

function get_extension($filename)
{
    $ext = explode('.', strtolower($filename));
    $ext = '.' . end($ext);
    return $ext;
}

function max_upload()
{
    $max_filesize = (int) (ini_get('upload_max_filesize'));
    $max_post     = (int) (ini_get('post_max_size'));
    $memory_limit = (int) (ini_get('memory_limit'));
    return min($max_filesize, $max_post, $memory_limit);
}

function get_external_ip()
{
    // Batasi waktu mencoba
    $options = stream_context_create(array('http'=>
        array(
        'timeout' => 2 //2 seconds
        )
    ));
    $externalContent = file_get_contents('http://checkip.dyndns.com/', false, $options);
    preg_match('/\b(?:\d{1,3}\.){3}\d{1,3}\b/', $externalContent, $m);
    $externalIp = $m[0];
    return $externalIp;
}

// Salin folder rekursif
// https://stackoverflow.com/questions/2050859/copy-entire-contents-of-a-directory-to-another-using-php
function xcopy($src, $dest)
{
    foreach (scandir($src) as $file) {
        $srcfile  = rtrim($src, '/') . '/' . $file;
        $destfile = rtrim($dest, '/') . '/' . $file;
        if (!is_readable($srcfile)) {
            continue;
        }
        if ($file != '.' && $file != '..') {
            if (is_dir($srcfile)) {
                if (!file_exists($destfile)) {
                    mkdir($destfile);
                }
                xcopy($srcfile, $destfile);
            } else {
                copy($srcfile, $destfile);
            }
        }
    }
}

function sql_in_list($list_array)
{
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
 */
function ambilBerkas($nama_berkas, $redirect_url, $unique_id = null, $lokasi = LOKASI_ARSIP)
{
    // Tentukan path berkas (absolut)
    $pathBerkas = FCPATH . $lokasi . $nama_berkas;
    $pathBerkas = str_replace('/', DIRECTORY_SEPARATOR, $pathBerkas);
    // Redirect ke halaman surat masuk jika path berkas kosong atau berkasnya tidak ada
    if (!file_exists($pathBerkas)) {
        $_SESSION['success'] = -1;
        $_SESSION['error_msg'] = 'Berkas tidak ditemukan';
        redirect($redirect_url);
    }
    // OK, berkas ada. Ambil konten berkasnya
    $data         = file_get_contents($pathBerkas);
    if(!is_null($unique_id)){
        // Buang unique id pada nama berkas download
        $nama_berkas       = explode($unique_id, $nama_berkas);
        $namaFile     = $nama_berkas[0];
        $ekstensiFile = explode('.', end($nama_berkas));
        $ekstensiFile = end($ekstensiFile);
        $nama_berkas       = $namaFile . '.' . $ekstensiFile;
    }
    force_download($nama_berkas, $data);
}
?>
