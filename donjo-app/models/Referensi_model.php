<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * Model untuk referensi data statis
 *
 * donjo-app/models/Referensi_model.php
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

// Model ini digunakan untuk data referensi statis yg tidak disimpan pd database atau sebagai referensi global

define("MASA_BERLAKU", serialize([
	"d" => "Hari",
	"w" => "Minggu",
	"M" => "Bulan",
	"y" => "Tahun"
]));

define("JENIS_PERATURAN_DESA", serialize([
	"Peraturan Desa (Perdes)",
	"Peraturan Kepala Desa (Perkades)",
	"Peraturan Bersama Kepala Desa"
]));

define("KATEGORI_PUBLIK", serialize([
	"Informasi Berkala" => "1",
	"Informasi Serta-merta" => "2",
	"Informasi Setiap Saat" => "3",
	"Informasi Dikecualikan" => "4"
]));

define("STATUS_PERMOHONAN", serialize([
	"Sedang diperiksa" => "0",
	"Belum lengkap" => "1",
	"Menunggu tandatangan" => "2",
	"Siap diambil" => "3",
	"Sudah diambil" => "4",
	"Dibatalkan" => "9"
]));

define("LINK_TIPE", serialize([
	'1' => 'Artikel Statis',
	'7' => 'Kategori Artikel',
	'2' => 'Statistik Penduduk',
	'3' => 'Statistik Keluarga',
	'4' => 'Statistik Program Bantuan',
	'5' => 'Halaman Statis Lainnya',
	'6' => 'Artikel Keuangan',
	'7' => 'Kelompok',
	'99' => 'Eksternal'
]));

// Statistik Penduduk
define("STAT_PENDUDUK", serialize([
	'13' => 'Umur (Rentang)',
	'15' => 'Umur (Kategori)',
	'0' => 'Pendidikan Dalam KK',
	'14' => 'Pendidikan Sedang Ditempuh',
	'1' => 'Pekerjaan',
	'2' => 'Status Perkawinan',
	'3' => 'Agama',
	'4' => 'Jenis Kelamin',
	'hubungan_kk' => 'Hubungan Dalam KK',
	'5' => 'Warga Negara',
	'6' => 'Status Penduduk',
	'7' => 'Golongan Darah',
	'9' => 'Penyandang Cacat',
	'10' => 'Penyakit Menahun',
	'16' => 'Akseptor KB',
	'17' => 'Akta Kelahiran',
	'18' => 'Kepemilikan KTP',
	'19' => 'Jenis Asuransi',
	'covid' => 'Status Covid'
]));

// Statistik Keluarga
define("STAT_KELUARGA", serialize([
	'kelas_sosial' => 'Kelas Sosial'
]));

// Statistik Bantuan
define("STAT_BANTUAN", serialize([
	'bantuan_penduduk' => 'Penerima Bantuan Penduduk',
	'bantuan_keluarga' => 'Penerima Bantuan Keluarga'
]));

// Statistik Lainnya
define("STAT_LAINNYA", serialize([
	'dpt' => 'Calon Pemilih',
	'wilayah' => 'Wilayah Administratif',
	'peraturan_desa' => 'Produk Hukum',
	'informasi_publik' => 'Informasi Publik',
	'peta' => 'Peta',
	'status_idm' => 'Status IDM',
	'data_analisis' => 'Data Analisis'
]));

// Jabatan Kelompok
define("JABATAN_KELOMPOK", serialize([
	1 => 'KETUA',
	2 => 'WAKIL KETUA',
	3 => 'SEKRETARIS',
	4 => 'BENDAHARA',
	90 => 'ANGGOTA'
]));

// API Server
define("STATUS_AKTIF", serialize([
	'0' => 'Tidak Aktif',
	'1' => 'Aktif'
]));

define("JENIS_NOTIF", serialize([
	'pemberitahuan',
	'pengumuman',
	'peringatan'
]));

define("SERVER_NOTIF", serialize([
	'TrackSID'
]));

define("JENIS_PELANGGAN", serialize([
	1 => 'hosting + update',
	2 => 'hosting saja',
	3 => 'premium',
	4 => 'update saja',
	5 => 'hosting + domain',
	6 => 'hosting + domain + update'
]));

define("STATUS_LANGGANAN", serialize([
	1 => 'aktif',
	2 => 'suspended',
	3 => 'tidak aktif',
]));

define("FILTER_LANGGANAN", serialize([
	1 => 'aktif',
	2 => 'suspended',
	3 => 'tidak aktif',
	4 => 'sebentar lagi berakhir',
	5 => 'baru berakhir',
	6 => 'sudah berakhir'
]));

define("PELAKSANA", serialize([
	1 => 'Herry Wanda',
	2 => 'Mohammad Ihsan',
	3 => 'Rudy Purwanto'
]));

class Referensi_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function list_nama($tabel)
	{
		$data = $this->list_data($tabel);
		$list = [];
		foreach ($data as $key => $value)
		{
			$list[$value['id']] = $value['nama'];
		}
		return $list;
	}

	public function list_data($tabel, $kecuali='', $termasuk=null)
	{
		if ($kecuali) $this->db->where("id NOT IN ($kecuali)");

		if ($termasuk) $this->db->where("id IN ($termasuk)");

		$data = $this->db->select('*')->order_by('id')->get($tabel)->result_array();
		return $data;
	}

	public function list_wajib_ktp()
	{
		$wajib_ktp = array_flip(unserialize(WAJIB_KTP));
		return $wajib_ktp;
	}

	public function list_ktp_el()
	{
		$ktp_el = array_flip(unserialize(KTP_EL));
		return $ktp_el;
	}

	public function list_status_rekam()
	{
		$status_rekam = array_flip(unserialize(STATUS_REKAM));
		return $status_rekam;
	}

	public function list_by_id($tabel, $id = 'id')
	{
		$data = $this->db->order_by($id)
			->get($tabel)
			->result_array();
		$data = array_combine(array_column($data, $id), $data);
		return $data;
	}

	public function list_ref($stat = STAT_PENDUDUK)
	{
		$list_ref = unserialize($stat);
		return $list_ref;
	}

	public function list_ref_flip($s_array)
	{
		$list = array_flip(unserialize($s_array));
		return $list;
	}

	public function list_ref_pelanggan($stat)
	{
		$list_ref = unserialize($stat);
		return $list_ref;
	}

}
?>
