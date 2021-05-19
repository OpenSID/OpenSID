<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * Helper berisi function umum
 *
 * donjo-app/helpers/opensid_helper.php
 *
 */

/**
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
 * Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @package	OpenSID
 * @author	Tim Pengembang OpenDesa
 * @copyright	Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright	Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license	http://www.gnu.org/licenses/gpl.html	GPL V3
 * @link 	https://github.com/OpenSID/OpenSID
 */

define("VERSION", '21.05-pasca');
/**
 * Untuk migrasi database. Simpan nilai ini di tabel migrasi untuk menandakan sudah migrasi ke versi ini
 * Versi database = [yyyymmdd][nomor urut dua digit]. Ubah setiap kali mengubah struktur database.
 */
define('VERSI_DATABASE', '2021050101');
define("LOKASI_LOGO_DESA", 'desa/logo/');
define("LOKASI_ARSIP", 'desa/arsip/');
define("LOKASI_CONFIG_DESA", 'desa/config/');
define("LOKASI_SURAT_DESA", 'desa/template-surat/');
define("LOKASI_SURAT_FORM_DESA", 'desa/template-surat/form/');
define("LOKASI_SURAT_PRINT_DESA", 'desa/template-surat/print/');
define("LOKASI_SURAT_EXPORT_DESA", 'desa/template-surat/export/');
define("LOKASI_USER_PICT", 'desa/upload/user_pict/');
define("LOKASI_GALERI", 'desa/upload/galeri/');
define("LOKASI_FOTO_ARTIKEL", 'desa/upload/artikel/');
define("LOKASI_FOTO_LOKASI", 'desa/upload/gis/lokasi/');
define("LOKASI_FOTO_AREA", 'desa/upload/gis/area/');
define("LOKASI_FOTO_GARIS", 'desa/upload/gis/garis/');
define("LOKASI_DOKUMEN", 'desa/upload/dokumen/');
define("LOKASI_PENGESAHAN", 'desa/upload/pengesahan/');
define("LOKASI_WIDGET", 'desa/widgets/');
define("LOKASI_GAMBAR_WIDGET", 'desa/upload/widgets/');
define("LOKASI_KEUANGAN_ZIP", 'desa/upload/keuangan/');
define("LOKASI_MEDIA", 'desa/upload/media/');
define("LOKASI_SIMBOL_LOKASI", 'desa/upload/gis/lokasi/point/');
define("LOKASI_SIMBOL_LOKASI_DEF", 'assets/images/gis/point/');

// Kode laporan statistik
define('JUMLAH', 666);
define('BELUM_MENGISI', 777);
define('TOTAL', 888);

// Kode laporan mandiri di tabel komentar
define('LAPORAN_MANDIRI', 775);

// Kode artikel terkait agenda
define('AGENDA', 1000);

//
define("MAX_PINDAH", 7);
define("MAX_ANGGOTA", 7);

// Konversi tulisan kode Buku Induk Penduduk ke kode SID
define("STATUS_DASAR", serialize(array(
	strtolower("HIDUP") => "1",
	strtolower("MATI") => "2",
	strtolower("PINDAH") => "3",
	strtolower("PINDAH DALAM NEGERI") => "3",
	strtolower("PINDAH LUAR NEGERI") => "3",
	strtolower("HILANG") => "4"
)));
define("KODE_SEX", serialize(array(
	"L" => "1",
	"Lk" => "1",
	"Laki-Laki" => "1",
	"P" => "2",
	"Pr" => "2",
	"Perempuan" => "2"
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
	strtolower("Tidak/Blm Sekolah") => "1",
	strtolower("Belum Tamat SD/Sederajat") => "2",
	strtolower("Tidak Tamat SD/Sederajat") => "2",
	strtolower("Tamat SD/Sederajat") => "3",
	strtolower("Tamat SD / Sederajat") => "3",
	strtolower("SLTP/Sederajat") => "4",
	strtolower("SLTA/Sederajat") => "5",
	strtolower("Diploma I/II") => "6",
	strtolower("Akademi/Diploma III/S. Muda") => "7",
	strtolower("Akademi/Diploma III/Sarjana Muda") => "7",
	strtolower("Diploma IV/Strata I") => "8",
	strtolower("Strata II") => "9",
	strtolower("Strata-II") => "9",
	strtolower("Strata III") => "10"
)));
define("KODE_PEKERJAAN", serialize(array(
	strtolower("BELUM/TIDAK BEKERJA") => "1",
	strtolower("MENGURUS RUMAH TANGGA") => "2",
	strtolower("PELAJAR/MAHASISWA") => "3",
	strtolower("PENSIUNAN") => "4",
	strtolower("PEGAWAI NEGERI SIPIL") => "5",
	strtolower("PEGAWAI NEGERI SIPIL (PNS)") => "5",
	strtolower("TENTARA NASIONAL INDONESIA") => "6",
	strtolower("TENTARA NASIONAL INDONESIA (TNI)") => "6",
	strtolower("KEPOLISIAN RI") => "7",
	strtolower("KEPOLISIAN RI (POLRI)") => "7",
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
	strtolower("PEKERJAAN LAINNYA") => "89",
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
	strtolower('TIDAK TAHU') => '13',
	strtolower('Tdk Th') => '13'
)));
define("KODE_CACAT", serialize(array(
	strtolower('CACAT FISIK') => '1',
	strtolower('CACAT NETRA/BUTA') => '2',
	strtolower('CACAT RUNGU/WICARA') => '3',
	strtolower('CACAT MENTAL/JIWA') => '4',
	strtolower('CACAT FISIK DAN MENTAL') => '5',
	strtolower('CACAT LAINNYA') => '6',
	strtolower('TIDAK CACAT') => '7'
)));
define("SASARAN", serialize(array(
	"1" => "Penduduk",
	"2" => "Keluarga / KK",
	"3" => "Rumah Tangga",
	"4" => "Kelompok/Organisasi Kemasyarakatan"
)));
define("ASALDANA", serialize(array(
	"Pusat" => "Pusat",
	"Provinsi" => "Provinsi",
	"Kab/Kota" => "Kab/Kota",
	"Dana Desa" => "Dana Desa",
	"Lain-lain (Hibah)" => "Lain-lain (Hibah)"
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
define("KATEGORI_MAILBOX", serialize(array(
	"Kotak Masuk" => "1",
	"Kotak Keluar" => "2"
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
 * favico_desa
 *
 * Mengembalikan path lengkap untuk file favico desa
 *
 * @access  public
 * @return  string
 */
function favico_desa()
{
	$favico = 'favicon.ico';
	$favico_desa = (is_file(APPPATH .'../'. LOKASI_LOGO_DESA . $favico)) ?
		base_url() . LOKASI_LOGO_DESA . $favico :
		base_url() . $favico;
	return $favico_desa;
}

/**
 * gambar_desa / KantorDesa
 *
 * Mengembalikan path lengkap untuk file logo desa / kantor desa
 *
 * @access  public
 * @return  string
 */
function gambar_desa($nama_file, $type = FALSE, $file = FALSE)
{
	if (is_file(APPPATH .'../'. LOKASI_LOGO_DESA . $nama_file))
	{

		return $logo_desa = ($file ? APPPATH.'../' : base_url()) . LOKASI_LOGO_DESA . $nama_file;
	}

	// type FALSE = logo, TRUE = kantor
	$default = ($type)  ? 'opensid_kantor.jpg' : 'opensid_logo.png';
	return $logo_desa = ($file ? APPPATH.'../' : base_url()). "assets/files/logo/$default";
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
	{
		log_message('error', 'curl tidak bisa dijalankan 1.'.$_SESSION['no_curl'].' 2.'.extension_loaded('curl'));
		return;
	}

	$postData = '';
	//create name value pairs seperated by &
	foreach ($params as $k => $v)
	{
		$postData .= $k . '=' . $v . '&';
	}
	$postData = rtrim($postData, '&');

	try
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_POST, count($postData));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

		// Batasi waktu koneksi dan ambil data, supaya tidak menggantung kalau ada error koneksi
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 4);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);

		/*curl_setopt($ch, CURLOPT_FORBID_REUSE, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
		curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 1);*/
		$output = curl_exec($ch);

		if ($output === false)
		{
			log_message('error', 'Curl error: ' . curl_error($ch));
			log_message('error', print_r(curl_getinfo($ch), true));
		}
		curl_close($ch);
		return $output;
	}
	catch (Exception $e)
	{
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
	$connected = @fsockopen($sCheckHost, 443);

  if ($connected)
  {
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
  $error = curl_error($ch);

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
	if (preg_match('/<\?php|<script/i', $buffer)) {
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
	$max_filesize = (int) bilangan(ini_get('upload_max_filesize'));
	$max_post     = (int) bilangan(ini_get('post_max_size'));
	$memory_limit = (int) bilangan(ini_get('memory_limit'));
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
	if (empty($list_array)) return FALSE;

	$prefix = $list = '';
	foreach ($list_array as $key => $value)
	{
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
	if (!file_exists($pathBerkas))
	{
		$_SESSION['success'] = -1;
		$_SESSION['error_msg'] = 'Berkas tidak ditemukan';
		if ($redirect_url)
			redirect($redirect_url);
		else
		{
			http_response_code(404);
			include(FCPATH . 'donjo-app/views/errors/html/error_404.php');
			die();
		}
	}
	// OK, berkas ada. Ambil konten berkasnya
	$data = file_get_contents($pathBerkas);
	if (!is_null($unique_id))
	{
		// Buang unique id pada nama berkas download
		$nama_berkas = explode($unique_id, $nama_berkas);
		$namaFile = $nama_berkas[0];
		$ekstensiFile = explode('.', end($nama_berkas));
		$ekstensiFile = end($ekstensiFile);
		$nama_berkas = $namaFile . '.' . $ekstensiFile;
	}
	force_download($nama_berkas, $data);
}

/**
 * @param array 		(0 => (kolom => teks), 1 => (kolom => teks), ..)
 * @return string 	dalam bentuk siap untuk autocomplete
 */
function autocomplete_data_ke_str($data)
{
	$str = '';
	foreach ($data as $baris)
	{
		$keys = array_keys($baris);
		$first_key = $keys[0];
		$str .= ','.json_encode(substr($baris[$first_key], 0, 30));
	}
	$str = '[' . strtolower(substr($str, 1)) . ']';
	return $str;
}

// Periksa apakah nilai bilangan Romawi
// https://recalll.co/?q=How%20to%20convert%20a%20Roman%20numeral%20to%20integer%20in%20PHP?&type=code
function is_angka_romawi($roman) {
  $roman_regex='/^M{0,3}(CM|CD|D?C{0,3})(XC|XL|L?X{0,3})(IX|IV|V?I{0,3})$/';
  return preg_match($roman_regex, $roman) > 0;
}

function bulan_romawi($bulan)
{
	if ($bulan < 1 or $bulan > 12) return false;

	$bulan_romawi = array(
		1 => "I",
		2 => "II",
		3 => "III",
		4 => "IV",
		5 => "V",
		6 => "VI",
		7 => "VII",
		8 => "VIII",
		9 => "IX",
		10 => "X",
		11 => "XI",
		12 => "XII"
	);
	return ($bulan_romawi[$bulan]);
}

function buang_nondigit($str)
{
	return preg_replace('/[^0-9]/', '', $str);
}

/**
 * @param array 		$files = array($file1, $file2, ...)
 * @return string 	path ke zip file

	Masukkan setiap berkas ke dalam zip.

	$file bisa:
		- array('nama' => nama-file-yg diinginkan, 'file' => full-path-ke-berkas); atau
		- full-path-ke-berkas
	Untuk membuat folder di dalam zip gunakan:
		$file = array('nama' => 'dir', 'file' => nama-folder)
*/
function masukkan_zip($files=array())
{
  $zip = new ZipArchive();
  # create a temp file & open it
  $tmp_file = tempnam(sys_get_temp_dir(),'');
  $zip->open($tmp_file, ZipArchive::CREATE);

  foreach ($files as $file)
  {
		if (is_array($file))
		{
			if ($file['nama'] == 'dir')
			{
				$zip->addEmptyDir($file['file']);
				continue;
			}
			else
			{
				$nama_file = $file['nama'];
				$file = $file['file'];
			}
		}
		else
		{
			$nama_file = basename($file);
		}
    $download_file = file_get_contents($file);
    $zip->addFromString($nama_file, $download_file);
  }
  $zip->close();
  return $tmp_file;
}

function alfa_spasi($str)
{
	return preg_replace('/[^a-zA-Z ]/', '', strip_tags($str));
}

// https://www.php.net/manual/en/function.array-column.php
function array_column_ext($array, $columnkey, $indexkey = null) {
  $result = array();
  foreach ($array as $subarray => $value) {
    if (array_key_exists($columnkey,$value)) { $val = $array[$subarray][$columnkey]; }
    else if ($columnkey === null) { $val = $value; }
    else { continue; }

    if ($indexkey === null) { $result[] = $val; }
    elseif ($indexkey == -1 || array_key_exists($indexkey,$value)) {
      $result[($indexkey == -1)?$subarray:$array[$subarray][$indexkey]] = $val;
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

function nomor_surat_keputusan($str)
{
	return preg_replace('/[^a-zA-Z0-9 \.\-\/]/', '', $str);
}

// Nama hanya boleh berisi karakter alpha, spasi, titik, koma, tanda petik dan strip
function nama($str)
{
	return preg_replace("/[^a-zA-Z '\.,\-]/", '', strip_tags($str));
}

// Nama hanya boleh berisi karakter alfanumerik, spasi dan strip
function nama_terbatas($str)
{
	return preg_replace("/[^a-zA-Z0-9 \-]/", '', $str);
}

// Alamat hanya boleh berisi karakter alpha, numerik, spasi, titik, koma, strip dan garis miring
function alamat($str)
{
	return preg_replace("/[^a-zA-Z0-9 \.,\-]/", '', htmlentities($str));
}

// Koordinat peta hanya boleh berisi numerik ,minus dan desimal
function koordinat($str)
{
	return preg_replace("/[^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$]/", '', htmlentities($str));
}

// Email hanya boleh berisi karakter alpha, numeric, titik, strip dan Tanda et,
function email($str)
{
	return preg_replace("/[^a-zA-Z0-9@\.\-]/", '', htmlentities($str));
}

// website hanya boleh berisi karakter alpha, numeric, titik, titik dua dan garis miring
function alamat_web($str)
{
	return preg_replace("/[^a-zA-Z0-9:\/\.\-]/", '', htmlentities($str));
}

function buat_slug($data_slug)
{
	$slug = $data_slug['thn'].'/'.$data_slug['bln'].'/'.$data_slug['hri'].'/'.$data_slug['slug'];
	return $slug;
}

function namafile($str)
{
	$tgl =  date('d_m_Y');
	$filename = urlencode(underscore(strtolower($str))."_".$tgl);
	return $filename;
}

function luas($int=0, $satuan="meter")
{
	if (($int / 10000) >= 1)
	{
		$ukuran = $int/10000;
		$pisah = explode('.', $ukuran);
		$luas['ha'] = number_format($pisah[0]);
		$luas['meter'] = round(($ukuran-$luas["ha"])*10000, 2);
	}
	else
	{
		$luas['ha'] =0;
		$luas['meter'] = round($int,2);
	}
	$hasil = ($int!=0)?$luas[$satuan]:null;
	return $hasil;
}

function list_mutasi($mutasi=[])
{
	if($mutasi)
	{
		foreach($mutasi as $item)
		{
			$div = ($item['jenis_mutasi'] == 2)? 'class="error"':null;
			$hasil = "<p $div>";
			$hasil .= $item['sebabmutasi'];
			$hasil .= !empty($item['no_c_desa']) ? " ".ket_mutasi_persil($item['jenis_mutasi'])." C No ".sprintf("%04s",$item['no_c_desa']): null;
			$hasil .= !empty($item['luasmutasi']) ? ", Seluas ".number_format($item['luasmutasi'])." m<sup>2</sup>, " : null;
			$hasil .= !empty($item['tanggalmutasi']) ? tgl_indo_out($item['tanggalmutasi'])."<br />" : null;
			$hasil .= !empty($item['keterangan']) ? $item['keterangan']: null;
			$hasil .= "</p>";

			echo $hasil;
		}
	}
}

function ket_mutasi_persil($id=0)
{
	if ($id==1)
		$ket = "dari";
	else
		$ket = "ke";
	return $ket;
}

function status_sukses($outp, $gagal_saja=false, $msg='')
{
	$CI =& get_instance();
	if ($msg) $CI->session->error_msg = $msg;
	if ($gagal_saja)
	{
		if (!$outp) $CI->session->success = -1;
	}
	else
		$CI->session->success = $outp ? 1 : -1;
}

// https://stackoverflow.com/questions/11807115/php-convert-kb-mb-gb-tb-etc-to-bytes
function convertToBytes(string $from)
{
  $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
  $number = substr($from, 0, -2);
  $suffix = strtoupper(substr($from,-2));

  //B or no suffix
  if(is_numeric(substr($suffix, 0, 1))) {
      return preg_replace('/[^\d]/', '', $from);
  }

  $exponent = array_flip($units);
  $exponent = isset($exponent[$suffix]) ? $exponent[$suffix] : null;
  if($exponent === null) {
      return null;
  }

  return $number * (1024 ** $exponent);
}

  /**
  * Disalin dari FeedParser.php
	* Load the whole contents of a web page
	*
	* @access   public
	* @param    string
	* @return   string
	*/
	function getUrlContent($url)
	{
		if (empty($url))
		{
			throw new Exception("URL to parse is empty!.");
			return false;
		}
		if (!in_array(explode(':', $url)[0], array('http', 'https')))
		{
			throw new Exception("URL harus http atau https");
			return false;
		}
		if ($content = @file_get_contents($url))
		{
			return $content;
		}
		else
		{
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			$content = curl_exec($ch);
			$error = curl_error($ch);

			curl_close($ch);

			if (empty($error))
			{
				return $content;
			}
			else
			{
				log_message('error', "Error occured while loading url by cURL. <br />\n" . $error) ;
				return false;
			}
		}
	}

function crawler()
{
	$file = APPPATH.'config/crawler-user-agents.json';
	$data = json_decode(file_get_contents($file), true);

	foreach($data as $entry)
	{
		if (preg_match('/'.strtolower($entry['pattern']).'/', $_SERVER['HTTP_USER_AGENT']))
			return TRUE;
	}

	return FALSE;
}

// Kode Wilayah Dengan Titik
// Dari 5201142005 --> 52.01.14.2005
function kode_wilayah($kode_wilayah)
{
	$kode_prov_kab_kec = str_split(substr($kode_wilayah, 0, 6), 2);
	$kode_desa = (strlen($kode_wilayah) > 6) ? '.' . substr($kode_wilayah, 6) : '';
	$kode_standar = implode('.', $kode_prov_kab_kec) . $kode_desa;
	return $kode_standar;
}

function pre_print_r($data)
{
	print("<pre>".print_r($data, true)."</pre>");
}
