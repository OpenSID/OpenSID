<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * Model Penduduk untuk modul Kependudukan > Penduduk
 *
 * donjo-app/models/Penduduk_model.php
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

class Penduduk_model extends MY_Model {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('keluarga_model');
		$this->load->model('web_dokumen_model');
		$this->ktp_el = array_flip(unserialize(KTP_EL));
		$this->status_rekam = array_flip(unserialize(STATUS_REKAM));
		$this->tempat_dilahirkan = array_flip(unserialize(TEMPAT_DILAHIRKAN));
		$this->jenis_kelahiran = array_flip(unserialize(JENIS_KELAHIRAN));
		$this->penolong_kelahiran = array_flip(unserialize(PENOLONG_KELAHIRAN));
	}


	public function autocomplete($cari='')
	{
		return $this->autocomplete_str('nama', 'tweb_penduduk', $cari);
	}

	protected function search_sql()
	{
		if (isset($_SESSION['cari']))
		{
			$cari = $_SESSION['cari'];
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' .$kw. '%';
			$search_sql = " AND (u.nama LIKE '$kw' OR u.nik LIKE '$kw' OR u.tag_id_card LIKE '$kw')";
			return $search_sql;
		}
	}

	protected function kumpulan_nik_sql()
	{
		if (empty($this->session->kumpulan_nik)) return;

		$kumpulan_nik = preg_replace('/[^0-9\,]/', '', $this->session->kumpulan_nik);
		$kumpulan_nik = array_filter(array_slice(explode(",", $kumpulan_nik), 0, 20)); // ambil 20 saja
		$kumpulan_nik = implode(',', $kumpulan_nik);
		$this->session->kumpulan_nik = $kumpulan_nik;
		$sql = " AND u.nik in ($kumpulan_nik)";
		return $sql;
	}

	protected function keluarga_sql()
	{
		if ($_SESSION['layer_keluarga'] == 1)
		{
			$sql = " AND u.kk_level = 1";
			return $sql;
		}
	}

	protected function dusun_sql()
	{
		if (!empty($_SESSION['dusun']))
		{
			$kf = $_SESSION['dusun'];
			$dusun_sql = " AND ((u.id_kk <> '0' AND a.dusun = '$kf') OR (u.id_kk = '0' AND a2.dusun = '$kf'))";
			return $dusun_sql;
		}
	}

	protected function rw_sql()
	{
		if (!empty($_SESSION['rw']))
		{
			$kf = $_SESSION['rw'];
			$rw_sql = " AND ((u.id_kk <> '0' AND a.rw = '$kf') OR (u.id_kk = '0' AND a2.rw = '$kf'))";
			return $rw_sql;
		}
	}

	protected function rt_sql()
	{
		if (!empty($_SESSION['rt']))
		{
			$kf = $_SESSION['rt'];
			$rt_sql = " AND ((u.id_kk <> '0' AND a.rt = '$kf') OR (u.id_kk = '0' AND a2.rt = '$kf'))";
			return $rt_sql;
		}
	}

	protected function get_sql_kolom_kode($session, $kolom)
	{
		$kf = $this->session->$session;
		if ( ! empty($kf))
		{
			if ($kf == JUMLAH)
				$sql = " AND (" . $kolom . " IS NOT NULL OR " . $kolom . " != '')";
			else if ($kf == BELUM_MENGISI)
				$sql = " AND (" . $kolom . " IS NULL OR " . $kolom . " = '')";
			else
				$sql = " AND " . $kolom . " = $kf";

			return $sql;
		}
	}

	// Filter belum digunakan
	protected function hamil_sql()
	{
		if (isset($_SESSION['hamil']))
		{
			$kf = $_SESSION['hamil'];
			$hamil_sql = " AND u.hamil = $kf";
			return $hamil_sql;
		}
	}

	protected function umur_max_sql()
	{
		$kf = $this->session->umur_max;
		if (isset($kf))
		{
			$umur_max_sql = " AND (SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0 FROM tweb_penduduk WHERE id = u.id) <= $kf ";
			return $umur_max_sql;
		}
	}

	protected function umur_min_sql()
	{
		$kf = $this->session->umur_min;
		if (isset($kf))
		{
			$umur_min_sql = " AND (SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0 FROM tweb_penduduk WHERE id = u.id) >= $kf ";
			return $umur_min_sql;
		}
	}

	protected function umur_sql()
	{
		$kf = $this->session->umurx;
		if (isset($kf))
		{
			if ($kf == JUMLAH) $sql = " AND u.tanggallahir <> '' ";
			else if ($kf == BELUM_MENGISI) $sql = " AND (u.tanggallahir IS NULL OR u.tanggallahir = '') ";
			else
				$sql = " AND (SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0 FROM tweb_penduduk WHERE id = u.id) >= (SELECT dari FROM tweb_penduduk_umur WHERE id=$kf ) AND (SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0 FROM tweb_penduduk WHERE id = u.id) <= (SELECT sampai FROM tweb_penduduk_umur WHERE id=$kf ) ";

			return $sql;
		}
	}

	protected function akta_kelahiran_sql()
	{
		$kf = $this->session->akta_kelahiran;
		if (isset($kf))
		{
			if ( ! in_array($kf, [JUMLAH, BELUM_MENGISI]))
			{
				$this->session->umurx = $kf;
				$sql = " AND u.akta_lahir <> '' ";
				$sql .= $this->umur_sql();

				return $sql;
			}

			if ($kf == JUMLAH) $sql = " AND u.akta_lahir <> '' ";
			else if ($kf == BELUM_MENGISI) $sql = " AND (u.akta_lahir IS NULL OR u.akta_lahir = '') ";

			return $sql;
		}
	}

	protected function status_ktp_sql()
	{
		if ( ! $this->session->status_ktp) return;

		// Filter berdasarkan data eKTP
		$wajib_ktp_sql = " AND ((DATE_FORMAT( FROM_DAYS( TO_DAYS( NOW( ) ) - TO_DAYS( tanggallahir ) ) , '%Y' ) +0)>=17 OR (status_kawin IS NOT NULL AND status_kawin <> 1)) ";

		$kf = $this->session->status_ktp;
		switch (true)
		{
			case ($kf == BELUM_MENGISI):
				$sql = $wajib_ktp_sql." AND (u.status_rekam IS NULL OR u.status_rekam = '')";
				break;
			case ($kf == JUMLAH):
				$sql = $wajib_ktp_sql." AND u.status_rekam IS NOT NULL AND u.status_rekam <> ''";
				break;
			case ($kf == TOTAL):
				// TOTAL hanya yang wajib KTP
				$sql = $wajib_ktp_sql;
				break;
			case ($kf <> 0):
				$status_ktp = $this->db->where('id',$kf)->get('tweb_status_ktp')->row_array();
				$status_rekam = $status_ktp['status_rekam'];
				$sql = $wajib_ktp_sql." AND u.status_rekam = $status_rekam";
				break;
			default:
		}
		return $sql;
	}

	public function get_alamat_wilayah($id)
	{
		// Alamat anggota keluarga diambil dari tabel keluarga
		$this->db->select('id_kk');
		$this->db->where('id', $id);
		$q = $this->db->get('tweb_penduduk');
		$penduduk = $q->row_array();
		if ($penduduk['id_kk'] > 0)
		{
			return $this->keluarga_model->get_alamat_wilayah($penduduk['id_kk']);
		}
		// Alamat penduduk lepas diambil dari kolom alamat_sekarang
		$sql = "SELECT a.dusun, a.rw, a.rt, u.alamat_sekarang as alamat
				FROM tweb_penduduk u
				LEFT JOIN tweb_wil_clusterdesa a ON u.id_cluster = a.id
				WHERE u.id = ?";
		$query = $this->db->query($sql,$id);
		$data = $query->row_array();

		$alamat_wilayah= trim("$data[alamat] RT $data[rt] / RW $data[rw] ".ikut_case($data['dusun'],$this->setting->sebutan_dusun)." $data[dusun]");
		return $alamat_wilayah;
	}

	public function paging($p=1, $o=0)
	{
		$list_data_sql = $this->list_data_sql();
		$sql = "SELECT COUNT(u.id) AS id ".$list_data_sql;
		$query = $this->db->query($sql);
		$row = $query->row_array();
		$jml_data = $row['id'];

		$this->load->library('paging');
		$cfg['page'] = $p;
		$cfg['per_page'] = $_SESSION['per_page'];
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);

		return $this->paging;
	}

	// Digunakan untuk paging dan query utama supaya jumlah data selalu sama
	private function list_data_sql()
	{
		$sql = "
		FROM tweb_penduduk u
		LEFT JOIN tweb_keluarga d ON u.id_kk = d.id
		LEFT JOIN tweb_rtm b ON u.id_rtm = b.no_kk
		LEFT JOIN tweb_wil_clusterdesa a ON d.id_cluster = a.id
		LEFT JOIN tweb_wil_clusterdesa a2 ON u.id_cluster = a2.id
		LEFT JOIN tweb_penduduk_pendidikan_kk n ON u.pendidikan_kk_id = n.id
		LEFT JOIN tweb_penduduk_pendidikan sd ON u.pendidikan_sedang_id = sd.id
		LEFT JOIN tweb_penduduk_pekerjaan p ON u.pekerjaan_id = p.id
		LEFT JOIN tweb_penduduk_kawin k ON u.status_kawin = k.id
		LEFT JOIN tweb_penduduk_sex x ON u.sex = x.id
		LEFT JOIN tweb_penduduk_agama g ON u.agama_id = g.id
		LEFT JOIN tweb_penduduk_warganegara v ON u.warganegara_id = v.id
		LEFT JOIN tweb_golongan_darah m ON u.golongan_darah_id = m.id
		LEFT JOIN tweb_cacat f ON u.cacat_id = f.id
		LEFT JOIN tweb_penduduk_hubungan hub ON u.kk_level = hub.id
		LEFT JOIN tweb_sakit_menahun j ON u.sakit_menahun_id = j.id
		LEFT JOIN log_penduduk log ON u.id = log.id_pend and log.id_detail in (2,3,4)
		LEFT JOIN covid19_pemudik c ON c.id_terdata = u.id
		LEFT JOIN ref_status_covid rc ON c.status_covid = rc.nama";

		// TODO: ubah ke query builder
		// Yg berikut hanya untuk menampilkan peserta bantuan
		if ($_SESSION['penerima_bantuan'])
		{
			$sql .= "
				LEFT JOIN program_peserta bt ON bt.peserta = u.nik
				LEFT JOIN program rcb ON bt.program_id = rcb.id
			";
		}

		$sql .= " WHERE 1 ";
		$sql .= $this->search_sql();
		$sql .= $this->kumpulan_nik_sql();
		$sql .= $this->dusun_sql();
		$sql .= $this->rw_sql();
		$sql .= $this->rt_sql();

		// Filter data penduduk digunakan dibeberapa tempat, termasuk untuk laporan statistik kependudukan.
		// Filter untuk statistik kependudukan menggunakan kode yang ada di daftar STAT_PENDUDUK di referensi_model.php
		$kolom_kode = array(
			array('filter', 'u.status'), //  Kode 6 Tetap, Tidak Tetap, Pendatang
			array('status_penduduk', 'u.status'), // Status Tetap, Tidak Tetap, Pendatang -> Hanya u/ Pencarian Spesifik
			array('status_dasar', 'u.status_dasar'), // Status : Hidup, Maati, Dll -> Hanya u/ Pencarian Spesifik
			array('sex', 'u.sex'), // Kode 4
			array('pendidikan_kk_id', 'u.pendidikan_kk_id'), // Kode 0
			array('cacat', 'u.cacat_id'), // Kode 9
			array('cara_kb_id', 'u.cara_kb_id'), // Kode 16
			array('menahun', 'u.sakit_menahun_id'), // Kode 10
			array('status', 'u.status_kawin'), // Kode 2
			array('pendidikan_sedang_id', 'u.pendidikan_sedang_id'), // Kode 14
			array('pekerjaan_id', 'u.pekerjaan_id'), // Kode 1
			array('agama', 'u.agama_id'), // Kode 3
			array('warganegara', 'u.warganegara_id'), // Kode 5
			array('golongan_darah', 'u.golongan_darah_id'), // Kode 7
			array('hubungan', 'u.kk_level'), // Kode hubungan_kk
			array('id_asuransi', 'u.id_asuransi'), // Kode 19
			array('status_covid', 'rc.id') // Kode covid
		);

		if ($_SESSION['penerima_bantuan'])
		{
			$kolom_kode[] = array('penerima_bantuan', 'rcb.id');
		}

		foreach ($kolom_kode as $kolom)
		{
			// Gunakan cara ini u/ filter sederhana
			$sql .= $this->get_sql_kolom_kode($kolom[0], $kolom[1]);
		}

		$sql .= $this->status_ktp_sql(); // Kode 18
		$sql .= $this->umur_min_sql(); // Hanya u/ Pencarian Spesifik
		$sql .= $this->umur_max_sql(); // Hanya u/ Pencarian Spesifik
		$sql .= $this->umur_sql(); // Kode 13, 15
		$sql .= $this->akta_kelahiran_sql(); // Kode 17
		$sql .= $this->hamil_sql(); // Filter blum digunakan
		return $sql;
	}

	// $limit = 0 mengambil semua
	public function list_data($o = 1, $offset = 0, $limit = 0)
	{
		$select_sql = "SELECT DISTINCT ";
		if ($_SESSION['penerima_bantuan'])
		{
			$select_sql .= 'rcb.id as penerima_bantuan,';
		}

		$select_sql .= "u.id, u.nik, u.tanggallahir, u.tempatlahir, u.foto, u.status, u.status_dasar, u.id_kk, u.nama, u.nama_ayah, u.nama_ibu, a.dusun, a.rw, a.rt, d.alamat, d.no_kk AS no_kk, u.kk_level, u.tag_id_card, u.created_at, rc.id as status_covid,
			(CASE
				when u.status_kawin IS NULL then ''
				when u.status_kawin <> 2 then k.nama
				else
					case when u.akta_perkawinan = ''
						then 'KAWIN BELUM TERCATAT'
						else 'KAWIN TERCATAT'
					end
			end) as kawin,
			(SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0 FROM tweb_penduduk WHERE id = u.id) AS umur,
			(SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(log.tgl_peristiwa)-TO_DAYS(u.tanggallahir)), '%Y')+0) AS umur_pada_peristiwa,
			x.nama AS sex, sd.nama AS pendidikan_sedang, n.nama AS pendidikan, p.nama AS pekerjaan, g.nama AS agama, m.nama AS gol_darah, hub.nama AS hubungan, b.no_kk AS no_rtm, b.id AS id_rtm
		";
		//Main Query
		$list_data_sql = $this->list_data_sql();
		$sql = $select_sql." ".$list_data_sql;

		//Ordering SQL
		switch ($o)
		{
			case 1: $order_sql = ' ORDER BY u.nik'; break;
			case 2: $order_sql = ' ORDER BY u.nik DESC'; break;
			case 3: $order_sql = ' ORDER BY u.nama'; break;
			case 4: $order_sql = ' ORDER BY u.nama DESC'; break;
			case 5: $order_sql = ' ORDER BY CONCAT(d.no_kk, u.kk_level)'; break;
			case 6: $order_sql = ' ORDER BY d.no_kk DESC, u.kk_level'; break;
			case 7: $order_sql = ' ORDER BY umur'; break;
			case 8: $order_sql = ' ORDER BY umur DESC'; break;
			case 9: $order_sql = ' ORDER BY created_at'; break;
			case 10: $order_sql = ' ORDER BY created_at DESC'; break;
			default:$order_sql = ' ORDER BY CONCAT(d.no_kk, u.kk_level)';
		}

		//Paging SQL
		$paging_sql = $limit > 0 ? ' LIMIT ' . $offset . ',' . $limit : '';

		$sql .= $order_sql;
		$sql .= $paging_sql;

		$query = $this->db->query($sql);
		$data = $query->result_array();

		//Formating Output
		$j = $offset;
		for ($i=0; $i<count($data); $i++)
		{
			// Untuk penduduk mati atau hilang, gunakan umur pada tgl peristiwa
			if (in_array($data[$i]['status_dasar'], array('2', '4')))
				$data[$i]['umur'] = $data[$i]['umur_pada_peristiwa'];
			// Ubah alamat penduduk lepas
			if (!$data[$i]['id_kk'] OR $data[$i]['id_kk'] == 0)
			{
				// Ambil alamat penduduk
				$sql = "SELECT p.id_cluster, p.alamat_sekarang, c.dusun, c.rw, c.rt
					FROM tweb_penduduk p
					LEFT JOIN tweb_wil_clusterdesa c on p.id_cluster = c.id
					WHERE p.id = ?
					";
				$query = $this->db->query($sql, $data[$i]['id']);
				$penduduk = $query->row_array();
				$data[$i]['alamat'] = $penduduk['alamat_sekarang'];
				$data[$i]['dusun'] = $penduduk['dusun'];
				$data[$i]['rw'] = $penduduk['rw'];
				$data[$i]['rt'] = $penduduk['rt'];
			}
			$data[$i]['no'] = $j + 1;
			$j++;
		}
		return $data;
	}

	public function list_data_map()
	{
		//Main Query
		$sql = "SELECT u.id, u.nik, u.nama, map.lat, map.lng, a.dusun, a.rw, a.rt, u.foto, d.no_kk AS no_kk,
					(SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0
					FROM tweb_penduduk
					WHERE id = u.id) AS umur,
				x.nama AS sex, sd.nama AS pendidikan_sedang, n.nama AS pendidikan, p.nama AS pekerjaan, k.nama AS kawin, g.nama AS agama, m.nama AS gol_darah, hub.nama AS hubungan,
				@alamat:=trim(concat_ws('',
					case
						when a.rt != '-' then concat('RT-', a.rt)
						else ''
					end,
					case
						when a.rw != '-' then concat('RW-', a.rw)
						else ''
					end,
					case
						when a.dusun != '-' then concat('Dusun ', a.dusun)
						else ''
					end
				)),
				case
					when length(@alamat) > 0 then @alamat
					else 'Alamat penduduk belum valid'
				end as alamat
				FROM tweb_penduduk u
				LEFT JOIN tweb_wil_clusterdesa a ON u.id_cluster = a.id
				LEFT JOIN tweb_wil_clusterdesa a2 ON u.id_cluster = a2.id
				LEFT JOIN tweb_keluarga d ON u.id_kk = d.id
				LEFT JOIN tweb_penduduk_pendidikan_kk n ON u.pendidikan_kk_id = n.id
				LEFT JOIN tweb_penduduk_pendidikan sd ON u.pendidikan_sedang_id = sd.id
				LEFT JOIN tweb_penduduk_pekerjaan p ON u.pekerjaan_id = p.id
				LEFT JOIN tweb_penduduk_kawin k ON u.status_kawin = k.id
				LEFT JOIN tweb_penduduk_sex x ON u.sex = x.id
				LEFT JOIN tweb_penduduk_agama g ON u.agama_id = g.id
				LEFT JOIN tweb_penduduk_warganegara v ON u.warganegara_id = v.id
				LEFT JOIN tweb_golongan_darah m ON u.golongan_darah_id = m.id
				LEFT JOIN tweb_cacat f ON u.cacat_id = f.id
				LEFT JOIN tweb_penduduk_hubungan hub ON u.kk_level = hub.id
				LEFT JOIN tweb_sakit_menahun j ON u.sakit_menahun_id = j.id
				LEFT JOIN tweb_penduduk_map map ON u.id = map.id ";

		$sql .= " WHERE 1 ";
		$sql .= $this->keluarga_sql();
		$sql .= $this->search_sql();
		$sql .= $this->dusun_sql();
		$sql .= $this->rw_sql();
		$sql .= $this->rt_sql();

		// Filter data penduduk juga digunakan untuk laporan statistik kependudukan di peta.
		// Filter untuk statistik kependudukan menggunakan kode yang ada di daftar STAT_PENDUDUK di referensi_model.php
		$kolom_kode = array(
			array('filter', 'u.status'), // Status : Hidup, Mati, Dll -> Load data awal (filtering combobox)
			array('status_penduduk', 'u.status'), // Status : Hidup, Mati, Dll -> Hanya u/ Pencarian Spesifik
			array('status_dasar', 'u.status_dasar'), // Kode 6
			array('sex', 'u.sex'), // Kode 4
			array('pendidikan_kk_id', 'u.pendidikan_kk_id'), // Kode 0
			array('cacat', 'u.cacat_id'), // Kode 9
			array('cara_kb_id', 'u.cara_kb_id'), // Kode 16
			array('menahun', 'u.sakit_menahun_id'), // Kode 10
			array('status', 'u.status_kawin'), // Kode 2
			array('pendidikan_sedang_id', 'u.pendidikan_sedang_id'), // Kode 14
			array('pekerjaan_id', 'u.pekerjaan_id'), // Kode 1
			array('agama', 'u.agama_id'), // Kode 3
			array('warganegara', 'u.warganegara_id'), // Kode 5
			array('golongan_darah', 'u.golongan_darah_id'), // Kode 7
			array('hubungan', 'u.kk_level'), // Kode 11
			array('id_asuransi', 'u.id_asuransi'), // Kode 19
			array('status_covid', 'rc.id') // Kode covid
		);
		foreach ($kolom_kode as $kolom)
		{
			// Gunakan cara ini u/ filter sederhana
			$sql .= $this->get_sql_kolom_kode($kolom[0], $kolom[1]);
		}

		$sql .= $this->status_ktp_sql(); // Kode 18
		$sql .= $this->umur_min_sql(); // Kode 13, 15
		$sql .= $this->umur_max_sql(); // Kode 13, 15
		$sql .= $this->umur_sql(); // Kode 13, 15
		$sql .= $this->akta_kelahiran_sql(); // Kode 17
		$sql .= $this->hamil_sql(); // Filter blum digunakan

		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function validasi_data_penduduk(&$data)
	{
		$data['tanggallahir'] = empty($data['tanggallahir']) ? NULL : tgl_indo_in($data['tanggallahir']);
		$data['tanggal_akhir_paspor'] = empty($data['tanggal_akhir_paspor']) ? NULL : tgl_indo_in($data['tanggal_akhir_paspor']);
		$data['tanggalperkawinan'] = empty($data['tanggalperkawinan']) ? NULL : tgl_indo_in($data['tanggalperkawinan']);
		$data['tanggalperceraian'] = empty($data['tanggalperceraian']) ? NULL : tgl_indo_in($data['tanggalperceraian']);

		$data['pendidikan_kk_id'] = $data['pendidikan_kk_id'] ?: NULL;
		$data['pendidikan_sedang_id'] = $data['pendidikan_sedang_id'] ?: NULL;
		$data['pekerjaan_id'] = $data['pekerjaan_id'] ?: NULL;
		$data['status_kawin'] = $data['status_kawin'] ?: NULL;
		$data['id_asuransi'] = $data['id_asuransi'] ?: NULL;
		$data['hamil'] = $data['hamil'] ?: NULL;

		$data['ktp_el'] = $data['ktp_el'] ?: NULL;
		$data['status_rekam'] = $data['status_rekam'] ?: NULL;
		$data['berat_lahir'] = $data['berat_lahir'] ?: NULL;
		$data['tempat_dilahirkan'] = $data['tempat_dilahirkan'] ?: NULL;
		$data['jenis_kelahiran'] = $data['jenis_kelahiran'] ?: NULL;
		$data['penolong_kelahiran'] = $data['penolong_kelahiran'] ?: NULL;
		$data['panjang_lahir'] = $data['panjang_lahir'] ?: NULL;
		$data['cacat_id'] = $data['cacat_id'] ?: NULL;
		$data['sakit_menahun_id'] = $data['sakit_menahun_id'] ?: NULL;
		$data['kk_level'] = $data['kk_level'] ?: 0;
		$data['email'] = strip_tags($data['email']);
		if (empty($data['id_asuransi']) or $data['id_asuransi'] == 1)
			$data['no_asuransi'] = NULL;
		if (empty($data['warganegara_id'])) $data['warganegara_id'] = 1; //default WNI

		// Hanya status 'kawin' yang boleh jadi akseptor kb
		if ($data['status_kawin'] != 2 or empty($data['cara_kb_id'])) $data['cara_kb_id'] = NULL;
		// Status hamil tidak berlaku bagi laki-laki
		if ($data['sex'] == 1) $data['hamil'] = 0;
		if (empty($data['kelahiran_anak_ke'])) $data['kelahiran_anak_ke'] = NULL;
		if ($data['warganegara_id'] == 1 or empty($data['dokumen_kitas']))
			$data['dokumen_kitas'] = NULL;
		switch ($data['status_kawin']) {
			case 1:
				// Status 'belum kawin' tidak berlaku akta perkawinan dan perceraian
				$data['akta_perkawinan'] = '';
				$data['akta_perceraian'] = '';
				$data['tanggalperkawinan'] = NULL;
				$data['tanggalperceraian'] = NULL;
				break;
			case 2:
				// Status 'kawin' tidak berlaku akta perceraian
				$data['akta_perceraian'] = '';
				$data['tanggalperceraian'] = NULL;
				break;
			case 3:
			case 4:
				break;
		}

		// Sterilkan data
		$data['no_kk_sebelumnya'] = preg_replace('/[^0-9\.]/', '', strip_tags($data['no_kk_sebelumnya']));
		$data['akta_lahir'] =  nomor_surat_keputusan($data['akta_lahir']);
		$data['tempatlahir'] = strip_tags($data['tempatlahir']);
		$data['dokumen_pasport'] = nomor_surat_keputusan($data['dokumen_pasport']);
		$data['nama_ayah'] = nama($data['nama_ayah']);
		$data['nama_ibu'] = nama($data['nama_ibu']);
		$data['telepon'] = preg_replace('/[^0-9 \-\+\.]/', '', strip_tags($data['telepon']));
		$data['alamat_sebelumnya'] = strip_tags($data['alamat_sebelumnya']);
		$data['alamat_sekarang'] = strip_tags($data['alamat_sekarang']);
		$data['akta_perkawinan'] = nomor_surat_keputusan($data['akta_perkawinan']);
		$data['akta_perceraian'] = nomor_surat_keputusan($data['akta_perceraian']);

		$valid = array();
		if (preg_match("/[^a-zA-Z '\.,\-]/", $data['nama']))
		{
			array_push($valid, "Nama hanya boleh berisi karakter alpha, spasi, titik, koma, tanda petik dan strip");
		}
		if (isset($data['nik']))
		{
			if ($error_nik = $this->nik_error($data['nik'], 'NIK'))
				array_push($valid, $error_nik);
			if ($this->db->select('nik')->from('tweb_penduduk')->where(array('nik'=>$data['nik']))->limit(1)->get()->row()->nik)
				array_push($valid, "NIK {$data['nik']} sudah digunakan");
		}
		if ($error_nik = $this->nik_error($data['ayah_nik'], 'NIK Ayah'))
			array_push($valid, $error_nik);
		if ($error_nik = $this->nik_error($data['ibu_nik'], 'NIK Ibu'))
				array_push($valid, $error_nik);
		if (!empty($valid))
			$_SESSION['validation_error'] = true;
		return $valid;
	}

	private function nik_error($nilai, $judul)
	{
		if (empty($nilai)) return false;
		if (!ctype_digit($nilai))
			return $judul . " hanya berisi angka";
		if (strlen($nilai) != 16 AND $nilai != '0')
			return $judul .  " panjangnya harus 16 atau bernilai 0";
		return false;
	}

	// Tambah penduduk domisili (tidak ada nomor KK)
	public function insert()
	{
		unset($_SESSION['validation_error']);
		unset($_SESSION['success']);
		$_SESSION['error_msg'] = '';

		$data = $_POST;

		$error_validasi = $this->validasi_data_penduduk($data);
		if (!empty($error_validasi))
		{
			foreach ($error_validasi as $error)
			{
				$_SESSION['error_msg'] .= ': ' . $error . '\n';
			}
			// Form menggunakan kolom id_sex = sex
			$_POST['id_sex'] = $_POST['sex'];
			// Tampilkan tanda kutip dalam nama
			$_POST['nama'] =  str_replace ( "\"", "&quot;", $_POST['nama'] ) ;
			$_SESSION['post'] = $_POST;
			$_SESSION['success']=-1;
			return;
		}

		unset($data['file_foto']);
		unset($data['old_foto']);
		unset($data['nik_lama']);
		unset($data['kk_level_lama']);
		unset($data['dusun']);
		unset($data['rw']);
		unset($data['no_kk']);

		$data['created_at'] = date('Y-m-d H:i:s');
		$data['created_by'] = $this->session->user;
		if ($data['tanggallahir'] == '') unset($data['tanggallahir']);
		if ($data['tanggalperkawinan'] == '') unset($data['tanggalperkawinan']);
		if ($data['tanggalperceraian'] == '') unset($data['tanggalperceraian']);
		$outp = $this->db->insert('tweb_penduduk', $data);
		$idku = $this->db->insert_id();

		// Upload foto dilakukan setelah ada id, karena nama foto berisi id pend
		if ($foto = $this->upload_foto_penduduk($idku))
			$this->db->where('id', $idku)->update('tweb_penduduk', ['foto' => $foto]);

		$satuan = $_POST['tanggallahir'];
		$blnlahir = substr($satuan, 3, 2);
		$thnlahir= substr($satuan, 6, 4);
		$blnskrg = (date("m"));
		$thnskrg = (date("Y"));
		if ($_POST['status'] == '3')
		{
			// Pendatang
			$log['id_detail'] = "8";
		}
		else
		{
			if (($blnlahir == $blnskrg) and ($thnlahir == $thnskrg))
			{
				// Lahir
				$log['id_detail'] = '1';
			}
			else
			{
				$log['id_detail'] = '5';
			}
		}
		$log['id_pend'] = $idku;
		//$log['id_detail'] = "8";
		$log['bulan'] = date("m");
		$log['tahun'] = date("Y");
		$log['tgl_peristiwa'] = date("d-m-y");
		$this->tulis_log_penduduk_data($log);

		$log1['id_pend'] = $idku;
		$log1['id_cluster'] = 1;
		$log1['tanggal'] = date("d-m-y");

		$outp = $this->db->insert('log_perubahan_penduduk', $log1);

		status_sukses($outp); //Tampilkan Pesan

		return $idku;
	}

	public function update($id=0)
	{
		unset($_SESSION['validation_error']);
		unset($_SESSION['success']);
		unset($_SESSION['error_msg']);
		$data = $_POST;

		// Jangan update nik apabila tidak berubah
		if ($data['nik_lama'] == $data['nik'])
		{
			unset($data['nik']);
		}
		unset($data['nik_lama']);

		$error_validasi = $this->validasi_data_penduduk($data);
		if (!empty($error_validasi))
		{
			foreach ($error_validasi as $error)
			{
				$_SESSION['error_msg'] .= ': ' . $error . '\n';
			}
			// Form menggunakan kolom id_sex = sex
			$_POST['id_sex'] = $_POST['sex'];
			// Tampilkan tanda kutip dalam nama
			$_POST['nama'] =  str_replace ( "\"", "&quot;", $_POST['nama'] ) ;
			$_SESSION['post'] = $_POST;
			$_SESSION['success'] = -1;
			return;
		}

		$sql = "SELECT id_kk, id_cluster, status_dasar FROM tweb_penduduk WHERE id = ?";
		$query = $this->db->query($sql, $id);
		$pend = $query->row_array();
		if ($pend['status_dasar'] != 1)
		{
			$_SESSION['success'] = -1;
			$_SESSION['error_msg'] = "Data penduduk dengan status dasar MATI/HILANG/PINDAH tidak dapat diubah!";
			return;
		}

		$this->keluarga_model->update_kk_level($id, $pend['id_kk'], $data['kk_level'], $data['kk_level_lama']);
		unset($data['kk_level_lama']);

		// Untuk anggota keluarga
		if (!empty($data['no_kk']))
		{
			// Ganti alamat KK
			$this->db->
				where('id', $pend['id_kk'])->
				update('tweb_keluarga', array('alamat' => $data['alamat']));
			if ($pend['id_cluster'] != $data['id_cluster'])
			{
				$this->keluarga_model->pindah_keluarga($pend['id_kk'], $data['id_cluster']);
			}
			unset($data['alamat']);
		}
		if ($foto = $this->upload_foto_penduduk($id))
			$data['foto'] = $foto;
		else
			unset($data['foto']);

		unset($data['no_kk']);
		unset($data['dusun']);
		unset($data['rw']);
		unset($data['file_foto']);
		unset($data['old_foto']);

		$data['updated_at'] = date('Y-m-d H:i:s');
		$data['updated_by'] = $this->session->user;
		$this->db->where('id', $id);
		$outp = $this->db->update('tweb_penduduk', $data);

		status_sukses($outp); //Tampilkan Pesan
	}

	private function upload_foto_penduduk($id)
	{
		if (empty($_FILES['foto']['tmp_name'])) return '';

		$nama_file = ($this->input->post('nik') ?: '0') . '-' . $id . get_extension($_FILES['foto']['name']);
		$old_foto = $this->input->post('old_foto');
		UploadFoto($nama_file, $old_foto);

		return $nama_file;
	}

	public function update_position($id=0)
	{
		$sql  = "SELECT m.id, p.status_dasar FROM tweb_penduduk_map m RIGHT JOIN tweb_penduduk p ON m.id = p.id WHERE p.id = ?";
		$query = $this->db->query($sql, $id);
		$cek = $query->row_array();
		if ($cek['status_dasar'] != 1)
		{
			$_SESSION['success'] = -1;
			$_SESSION['error_msg'] = "Data penduduk dengan status dasar MATI/HILANG/PINDAH tidak dapat diubah!";
			return;
		}
		$data = $_POST;
		unset($data['zoom']);
		unset($data['map_tipe']);
		if ($cek['id'] == $id)
		{
			if ($data['lat'])
			{
				$this->db->where('id', $id);
				$outp = $this->db->update('tweb_penduduk_map', $data);
			}
		}
		else
		{
			if ($data['lat'])
			{
				$data['id'] = $id;
				$outp = $this->db->insert('tweb_penduduk_map', $data);
			}
		}
		status_sukses($outp); //Tampilkan Pesan
	}

	public function get_penduduk_map($id=0)
	{
		$sql = "SELECT m.*, p.nama, p.status_dasar FROM tweb_penduduk_map m RIGHT JOIN tweb_penduduk p ON m.id = p.id WHERE p.id = ? ";
		$query = $this->db->query($sql, $id);
		return $query->row_array();
	}

	public function update_status_dasar($id=0)
	{
		$data['status_dasar'] = $_POST['status_dasar'];
		$data['updated_at'] = date('Y-m-d H:i:s');
		$data['updated_by'] = $this->session->user;
		$this->db->where('id',$id);
		$this->db->update('tweb_penduduk', $data);
		$penduduk = $this->get_penduduk($id);

		// Tulis log_keluarga jika penduduk adalah kepala keluarga
		if ($penduduk['kk_level'] == 1)
		{
			$id_peristiwa = $penduduk['status_dasar_id'] + 2; // lihat kode di keluarga_model
			$this->keluarga_model->log_keluarga($penduduk['id_kk'], $penduduk['id'], $id_peristiwa);
		}

		// Tulis log_penduduk
		$log['id_pend'] = $id;
		$log['no_kk'] = $penduduk['no_kk'];
		$log['nama_kk'] = $penduduk['kepala_kk'];
		$log['tgl_peristiwa'] = rev_tgl($_POST['tgl_peristiwa']);
		$log['id_detail'] = $data['status_dasar'];
		if ($log['id_detail'] == 3)
			$log['ref_pindah'] = !empty($_POST['ref_pindah']) ? $_POST['ref_pindah'] : 1;
		$log['bulan'] = date("m");
		$log['tahun'] = date("Y");
		$log['catatan'] = $_POST['catatan'];

		$this->tulis_log_penduduk_data($log);
	}

	/**
	 * Kembalikan status dasar penduduk ke hidup
	 *
	 * @param $id 			id penduduk
	 * @return void
	 */
	public function kembalikan_status($id)
	{
		$_SESSION['success'] = 1;
		$data['status_dasar'] = 1; // status dasar hidup
		$data['updated_at'] = date('Y-m-d H:i:s');
		$data['updated_by'] = $this->session->user;
		if (!$this->db->where('id', $id)->update('tweb_penduduk', $data))
			$_SESSION['success'] = - 1;
	}

	public function delete($id='', $semua=false)
	{
		if (!$semua) $this->session->success = 1;

		$outp = $this->db->where('id', $id)->delete('tweb_penduduk');

		status_sukses($outp, $gagal_saja=true); //Tampilkan Pesan
	}

	public function delete_all()
	{
		$this->session->success = 1;

		$id_cb = $_POST['id_cb'];
		foreach ($id_cb as $id)
		{
			$this->delete($id, $semua=true);
		}
	}

	public function adv_search_proses()
	{
		UNSET($_POST['umur1']);
		UNSET($_POST['umur2']);

		UNSET($_POST['dusun']);
		UNSET($_POST['rt']);
		UNSET($_POST['rw']);
		$i = 0;
		while ($i++ < count($_POST))
		{
			$col[$i] = key($_POST);
				next($_POST);
		}
		$i = 0;
		while ($i++ < count($col))
		{
			if ($_POST[$col[$i]] == "")
				UNSET($_POST[$col[$i]]);
		}

		$data = $_POST;
		$this->db->where($data);
		return $this->db->get('tweb_penduduk');
	}

	public function get_id_kk($id=0)
	{
		$sql = "SELECT u.id_kk
				FROM tweb_penduduk u
				WHERE id = ? limit 1";
		$query = $this->db->query($sql, $id);
		$data = $query->row_array();
		return $data['id_kk'];
	}

	public function get_penduduk($id=0)
	{
		$sql = "SELECT u.sex as id_sex, u.*, a.dusun, a.rw, a.rt, t.id AS id_status, t.nama AS status, o.nama AS pendidikan_sedang, m.nama as golongan_darah, h.nama as hubungan,
			b.nama AS pendidikan_kk, d.no_kk AS no_kk, d.alamat, u.id_cluster as id_cluster, ux.nama as nama_pengubah, ucreate.nama as nama_pendaftar, polis.nama AS asuransi,
			(CASE
				when u.status_kawin IS NULL then ''
				when u.status_kawin <> 2 then k.nama
				else
					case when u.akta_perkawinan = ''
						then 'KAWIN BELUM TERCATAT'
						else 'KAWIN TERCATAT'
					end
			end) as kawin,
			(SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0  FROM tweb_penduduk WHERE id = u.id)
			 AS umur, x.nama AS sex, w.nama AS warganegara,
			 p.nama AS pekerjaan, g.nama AS agama, c.nama as cacat,
			 kb.nama as cara_kb, sm.nama as sakit_menahun,
			 sd.nama as status_dasar, u.status_dasar as status_dasar_id,
			(select tweb_penduduk.nama AS nama from tweb_penduduk where (tweb_penduduk.id = d.nik_kepala)) AS kepala_kk,
			log.no_kk as log_no_kk
		 FROM tweb_penduduk u
			LEFT JOIN tweb_keluarga d ON u.id_kk = d.id
			LEFT JOIN tweb_wil_clusterdesa a ON u.id_cluster = a.id
			LEFT JOIN tweb_penduduk_pendidikan o ON u.pendidikan_sedang_id = o.id
			LEFT JOIN tweb_penduduk_pendidikan_kk b ON u.pendidikan_kk_id = b.id
			LEFT JOIN tweb_penduduk_warganegara w ON u.warganegara_id = w.id
			LEFT JOIN tweb_penduduk_status t ON u.status = t.id
			LEFT JOIN tweb_penduduk_pekerjaan p ON u.pekerjaan_id = p.id
			LEFT JOIN tweb_penduduk_kawin k ON u.status_kawin = k.id
			LEFT JOIN tweb_penduduk_sex x ON u.sex = x.id
			LEFT JOIN tweb_penduduk_agama g ON u.agama_id = g.id
			LEFT JOIN tweb_golongan_darah m ON u.golongan_darah_id = m.id
			LEFT JOIN tweb_penduduk_hubungan h on u.kk_level = h.id
			LEFT JOIN tweb_cacat c ON u.cacat_id = c.id
			LEFT JOIN tweb_sakit_menahun sm ON u.sakit_menahun_id = sm.id
			LEFT JOIN tweb_cara_kb kb ON u.cara_kb_id = kb.id
			LEFT JOIN tweb_status_dasar sd ON u.status_dasar = sd.id
			LEFT JOIN log_penduduk log ON u.id = log.id_pend
			LEFT JOIN user ux ON u.updated_by = ux.id
			LEFT JOIN user ucreate ON u.created_by = ucreate.id
			LEFT JOIN tweb_penduduk_asuransi polis ON polis.id = u.id_asuransi
			WHERE u.id=?";
		$query = $this->db->query($sql, $id);
		$data = $query->row_array();
		$data['tanggallahir'] = tgl_indo_out($data['tanggallahir']);
		$data['tanggal_akhir_paspor'] = tgl_indo_out($data['tanggal_akhir_paspor']);
		$data['tanggalperkawinan'] = tgl_indo_out($data['tanggalperkawinan']);
		$data['tanggalperceraian'] = tgl_indo_out($data['tanggalperceraian']);
		// Penduduk lepas, pakai alamat penduduk
		if ($data['id_kk'] == 0 OR $data['id_kk'] == '')
		{
			$data['alamat'] = $data['alamat_sekarang'];
			$this->db->where('id', $data['id_cluster']);
			$query = $this->db->get('tweb_wil_clusterdesa');
			$cluster = $query->row_array();
			$data['dusun'] = $cluster['dusun'];
			$data['rw'] = $cluster['rw'];
			$data['rt'] = $cluster['rt'];
		}
		// Data ektp: cari tulisan untuk kode
		$wajib_ktp = $this->is_wajib_ktp($data);
		if ($wajib_ktp !== null)
			$data['wajib_ktp'] = $wajib_ktp ? 'WAJIB' : 'BELUM';
		$data['ktp_el'] = strtoupper($this->ktp_el[$data['ktp_el']]);
		$data['status_rekam'] = strtoupper($this->status_rekam[$data['status_rekam']]);
		$data['tempat_dilahirkan_nama'] = strtoupper($this->tempat_dilahirkan[$data['tempat_dilahirkan']]);
		$data['jenis_kelahiran_nama'] = strtoupper($this->jenis_kelahiran[$data['jenis_kelahiran']]);
		$data['penolong_kelahiran_nama'] = strtoupper($this->penolong_kelahiran[$data['penolong_kelahiran']]);
		// Tampilkan tanda kutip dalam nama
		$data['nama'] =  str_replace ( "\"", "&quot;", $data['nama'] ) ;

		return $data;
	}

	public function get_penduduk_by_nik($nik=0)
	{
		$sql = "SELECT u.id AS id, u.nama AS nama, x.nama AS sex, u.id_kk AS id_kk,
		u.tempatlahir AS tempatlahir, u.tanggallahir AS tanggallahir, u.kk_level,
		(select (date_format(from_days((to_days(now()) - to_days(tweb_penduduk.tanggallahir))),'%Y') + 0) AS `(date_format(from_days((to_days(now()) - to_days(tweb_penduduk.tanggallahir))),'%Y') + 0)`
		from tweb_penduduk where (tweb_penduduk.id = u.id)) AS umur,
		w.nama AS status_kawin, f.nama AS warganegara, a.nama AS agama, h.nama as hubungan, d.nama AS pendidikan, j.nama AS pekerjaan, u.nik AS nik, c.rt AS rt, c.rw AS rw, c.dusun AS dusun, k.no_kk AS no_kk, k.alamat,
		(select tweb_penduduk.nama AS nama from tweb_penduduk where (tweb_penduduk.id = k.nik_kepala)) AS kepala_kk
		from tweb_penduduk u
		left join tweb_penduduk_sex x on u.sex = x.id
		left join tweb_penduduk_kawin w on u.status_kawin = w.id
		left join tweb_penduduk_agama a on u.agama_id = a.id
		left join tweb_penduduk_hubungan h on u.kk_level = h.id
		left join tweb_penduduk_pendidikan_kk d on u.pendidikan_kk_id = d.id
		left join tweb_penduduk_pekerjaan j on u.pekerjaan_id = j.id
		left join tweb_wil_clusterdesa c on u.id_cluster = c.id
		left join tweb_keluarga k on u.id_kk = k.id
		left join tweb_penduduk_warganegara f on u.warganegara_id = f.id
		WHERE u.nik = ?";
		$query = $this->db->query($sql, $nik);
		$data = $query->row_array();
		$data['alamat_wilayah'] = trim("$data[alamat] RT $data[rt] / RW $data[rw] $data[dusun]");
		return $data;
	}

	// TODO: Ubah yg masih menggunakan, spy menggunakan penanganan wilayah di wilayah_model.php
	public function list_dusun()
	{
		$sql = "SELECT * FROM tweb_wil_clusterdesa WHERE rt = '0' AND rw = '0' ";
		$query = $this->db->query($sql);
		$data = $query->result_array();
		return $data;
	}

	// TODO: Ubah yg masih menggunakan, spy menggunakan penanganan wilayah di wilayah_model.php
	public function list_wil()
	{
		$sql = "SELECT * FROM tweb_wil_clusterdesa WHERE zoom > '0'";
		$query = $this->db->query($sql);
		$data = $query->result_array();
		return $data;
	}

	// TODO: Ubah yg masih menggunakan, spy menggunakan penanganan wilayah di wilayah_model.php
	public function list_rw($dusun='')
	{
		$data = $this->db->
			where('rt', '0')->
			where('dusun', $dusun)->
			where("rw <> '0'")->
			get('tweb_wil_clusterdesa')->
			result_array();
		return $data;
	}

	// TODO: Ubah yg masih menggunakan, spy menggunakan penanganan wilayah di wilayah_model.php
	public function list_rw_all()
	{
		$sql = "SELECT * FROM tweb_wil_clusterdesa WHERE rt = '0' AND rw <> '0'";
		$query = $this->db->query($sql);
		$data = $query->result_array();
		return $data;
	}

	// TODO: Ubah yg masih menggunakan, spy menggunakan penanganan wilayah di wilayah_model.php
	public function list_rt($dusun='', $rw='')
	{
		$sql = "SELECT * FROM tweb_wil_clusterdesa WHERE rw = ? AND dusun = ? AND rt <> '0'";
		$query = $this->db->query($sql,array($rw,$dusun));
		$data = $query->result_array();
		return $data;
	}

	// TODO: Ubah yg masih menggunakan, spy menggunakan penanganan wilayah di wilayah_model.php
	public function list_rt_all()
	{
		$sql = "SELECT * FROM tweb_wil_clusterdesa WHERE rt <> '0' AND rw <> '-'";
		$query = $this->db->query($sql);
		$data = $query->result_array();
		return $data;
	}

	/**
		$status_kawin_kk adalah status kawin dari kepala keluarga.
		Digunakan pada saat menambah anggota keluarga, supaya yang ditampilkan hanya
		hubungan yang berlaku
	**/
	public function list_hubungan($status_kawin_kk = NULL, $sex = 1)
	{
		if (! empty($status_kawin_kk))
		{
			/***
				Untuk Kepala Keluarga yang belum kawin, hubungan berikut tidak berlaku:
					menantu, cucu, mertua, suami, istri; anak hanya berlaku untuk kk perempuan
				Untuk semua Kepala Keluarga, hubungan 'kepala keluarga' tidak berlaku
			***/

			if ($status_kawin_kk == 1)
			{
				($sex == 2) ? $this->db->where("id NOT IN ('1', '2', '3', '5', '6', '8') ")
										: $this->db->where("id NOT IN ('1', '2', '3', '4', '5', '6', '8') ");
			}
			else
			{
				$this->db->where("id <> 1");
			}
		}
		$data = $this->db
			->get('tweb_penduduk_hubungan')
			->result_array();
		return $data;
	}

	// Hapus jika tdk ada modul yg gunakan, untuk selanjutnya penanganan wilayah terdapat pd wilayah_model.php
	public function list_pendidikan()
	{
		$sql = "SELECT * FROM tweb_penduduk_pendidikan WHERE 1";
		$query = $this->db->query($sql);
		$data = $query->result_array();
		return $data;
	}

	// TODO: tinjau apakah bisa digunakan atau perlu dihapus
	public function list_pendidikan_telah()
	{
		$sql = "SELECT * FROM tweb_penduduk_pendidikan WHERE left(nama, 6) <> 'SEDANG' ";
		$query = $this->db->query($sql);
		$data = $query->result_array();
		return $data;
	}

	public function list_pendidikan_sedang()
	{
		$sql = "SELECT * FROM tweb_penduduk_pendidikan WHERE left(nama, 5) <> 'TAMAT' ";
		$query = $this->db->query($sql);
		$data = $query->result_array();
		return $data;
	}

	// Hapus jika tdk ada modul yg gunakan, untuk selanjutnya penanganan wilayah terdapat pd wilayah_model.php
	public function list_pendidikan_kk()
	{
		$sql = "SELECT * FROM tweb_penduduk_pendidikan_kk WHERE 1";
		$query = $this->db->query($sql);
		$data = $query->result_array();
		return $data;
	}

	// Untuk pekerjaan, ubah bentuk seperti 'Belum/tidak Bekerja' menjadi 'Belum/Tidak Bekerja'
	private function ubah_ke_huruf_besar($matches)
	{
		$matches[0][1] = strtoupper($matches[0][1]);
		return $matches[0];
	}

	public function normalkanPekerjaan($nama)
	{
		$nama_pekerjaan = array(
			"(pns)" => "(PNS)",
			"(tni)" => "(TNI)",
			"(polri)" => "(POLRI)",
			" Ri " => " RI ",
			"Dpr-ri" => "DPR-RI",
			"Dpd" => "DPD",
			"Bpk" => "BPK",
			"Dprd" => "DPRD"
		);
		$nama = ucwords(strtolower($nama));
		foreach ($nama_pekerjaan as $key => $value)
		{
			$nama = str_replace($key, $value, $nama);
		}
		if (strpos($nama,'/'))
		{
			$nama = $nama;
			$nama = preg_replace_callback('/\/\S{1}/', "Penduduk_Model::ubah_ke_huruf_besar", $nama);
		}
		return $nama;
	}

	public function list_pekerjaan($case='')
	{
		$sql = "SELECT * FROM tweb_penduduk_pekerjaan WHERE 1";
		$query = $this->db->query($sql);
		$data = $query->result_array();
		if ($case == 'ucwords')
		{
			for ($i=0; $i<count($data); $i++)
			{
				$data[$i]['nama'] = $this->normalkanPekerjaan($data[$i]['nama']);
			}
		}
		return $data;
	}

	public function list_warganegara()
	{
		$sql = "SELECT * FROM tweb_penduduk_warganegara WHERE 1";
		$query = $this->db->query($sql);
		$data = $query->result_array();
		return $data;
	}

	public function list_status_kawin()
	{
		$sql = "SELECT * FROM tweb_penduduk_kawin WHERE 1";
		$query = $this->db->query($sql);
		$data = $query->result_array();
		return $data;
	}

	public function list_golongan_darah()
	{
		$sql = "SELECT * FROM tweb_golongan_darah WHERE 1";
		$query = $this->db->query($sql);
		$data = $query->result_array();
		return $data;
	}

	public function list_sex()
	{
		$data = $this->db->select('*')->get("tweb_penduduk_sex")->result_array();
		return $data;
	}

	public function list_cacat()
	{
		$sql   = "SELECT * FROM tweb_cacat WHERE 1";
		$query = $this->db->query($sql);
		$data = $query->result_array();
		return $data;
	}

	public function list_cara_kb($sex='')
	{
		if ($sex != 1 AND $sex != 2)
		{
			$sql   = "SELECT * FROM tweb_cara_kb WHERE 1";
		}
		else
		{
			$sql   = "SELECT * FROM tweb_cara_kb WHERE sex = ? OR sex = 3";
		}
		$query = $this->db->query($sql, $sex);
		$data = $query->result_array();
		return $data;
	}

	public function is_anggota_keluarga($id)
	{
		$this->db->select('id_kk');
		$this->db->where('id', $id);
		$q = $this->db->get('tweb_penduduk');
		$penduduk = $q->row_array();
		if ($penduduk['id_kk'] > 0) return true;
		else return false;
	}

	public function tulis_log_penduduk_data($log)
	{
		$sql = $this->db->insert_string('log_penduduk', $log) . duplicate_key_update_str($log);
		$this->db->query($sql);
	}

	public function tulis_log_penduduk($id_pend, $id_detail, $bulan, $tahun)
	{
		$data = array(
			'id_pend' => $id_pend,
			'id_detail' => $id_detail,
			'bulan' => $bulan,
			'tahun' => $tahun,
			'tgl_peristiwa' => date("d-m-y")
		);
		$query = $this->db->insert_string('log_penduduk', $data) .
		"ON DUPLICATE KEY UPDATE
				id_pend = VALUES(id_pend),
				id_detail = VALUES(id_detail),
				bulan = VALUES(bulan),
				tahun = VALUES(tahun),
				tgl_peristiwa = VALUES(tgl_peristiwa)
				;
		";
		$this->db->query($query);
	}

	public function get_judul_statistik($tipe = '0', $nomor = 0, $sex = NULL)
	{
		if ($nomor == JUMLAH)
			$judul = array("nama" => "JUMLAH");
		else if ($nomor == BELUM_MENGISI)
			$judul = array("nama" => "BELUM MENGISI");
		elseif ($nomor == TOTAL)
			$judul = array("nama" => "TOTAL");
		else
		{
			switch ($tipe)
			{
				case '0': $table = 'tweb_penduduk_pendidikan_kk'; break;
				case 1: $table = 'tweb_penduduk_pekerjaan'; break;
				case 2: $table = 'tweb_penduduk_kawin'; break;
				case 3: $table = 'tweb_penduduk_agama'; break;
				case 4: $table = 'tweb_penduduk_sex'; break;
				case 5: $table = 'tweb_penduduk_warganegara'; break;
				case 6: $table = 'tweb_penduduk_status'; break;
				case 7: $table = 'tweb_golongan_darah'; break;
				case 9: $table = 'tweb_cacat'; break;
				case 10: $table = 'tweb_sakit_menahun'; break;
				case 14: $table = 'tweb_penduduk_pendidikan'; break;
				case 16: $table = 'tweb_cara_kb'; break;
				case 13: // = 17
				case 15: // = 17
				case 17: $table = 'tweb_penduduk_umur'; break;
				case 18: $table = 'tweb_status_ktp'; break;
				case 19: $table = 'tweb_penduduk_asuransi'; break;
				case 'covid': $table = 'ref_status_covid'; break;
				case 'bantuan_penduduk': $table = 'program'; break;
				case 'hubungan_kk' : $table = 'tweb_penduduk_hubungan'; break;
			}

			if ($tipe == 13 OR $tipe == 17) $this->db->where('STATUS', 1);
			if ($tipe == 15) $this->db->where('STATUS', 0);

			$judul = $this->db->get_where($table, ['id' => $nomor])->row_array();
		}

		if ($sex == 1) $judul['nama'] .= " - LAKI-LAKI";
		elseif ($sex == 2) $judul['nama'] .= " - PEREMPUAN";

		return $judul;
	}

	// Untuk form surat
	public function list_penduduk_status_dasar($status_dasar=1)
	{
		$sql = "SELECT u.id, nik, nama,
			CONCAT('Alamat : RT-', w.rt, ', RW-', w.rw, '', w.dusun) AS alamat,
			CONCAT('NIK: ', nik, ' - ', nama, '\nAlamat : RT-', w.rt, ', RW-', w.rw, '', w.dusun) AS info_pilihan_penduduk,
			w.rt, w.rw, w.dusun, u.sex FROM tweb_penduduk u LEFT JOIN tweb_wil_clusterdesa w ON u.id_cluster = w.id WHERE u.status_dasar = ?";
		$data = $this->db->query($sql, array($status_dasar))->result_array();
		return $data;
	}

	public function get_cluster($id_cluster=0)
	{
		$sql = "SELECT * FROM tweb_wil_clusterdesa WHERE id = $id_cluster ";
		$query = $this->db->query($sql);
		return $query->row_array();
	}

	public function list_dokumen($id="")
	{
		$sql = "SELECT * FROM dokumen_hidup WHERE id_pend = ? AND deleted = 0";
		$query = $this->db->query($sql, $id);
		$data = null;
		if ($query)
			$data=$query->result_array();

		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $i + 1;
			$data[$i]['hidden'] = false;

			// jika dokumen berelasi dengan dokumen kepala kk
			if (isset($data[$i]['id_parent']))
				$data[$i]['hidden'] = true;
		}
		return $data;
	}

	public function list_kelompok($id="")
	{
		$sql = "SELECT k.nama, m.kelompok AS kategori
			FROM kelompok_anggota a
			LEFT JOIN kelompok k ON a.id_kelompok = k.id
			LEFT JOIN kelompok_master m ON k.id_master = m.id
			WHERE a.id_penduduk = ? ";
		$query = $this->db->query($sql, $id);
		$data = null;
		if ($query)
			$data=$query->result_array();

		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $i + 1;
		}
		return $data;
	}

	public function get_dokumen($id=0)
	{
		$sql = "SELECT * FROM dokumen WHERE id = ?";
		$query = $this->db->query($sql, $id);
		$data = $query->row_array();
		return $data;
	}

	public function is_wajib_ktp($data)
	{
		// Wajib KTP = sudah umur 17 atau pernah kawin
		$umur = umur($data['tanggallahir']);
		if ($umur === null) return null;
		$wajib_ktp = (($umur > 16) OR (!empty($data['status_kawin']) AND $data['status_kawin'] != 1));
		return $wajib_ktp;
	}

	public function jml_penduduk()
	{
		$jml = $this->db->select('count(*) as jml')->where('status', '1')->
				get('tweb_penduduk')->row()->jml;
		return $jml;
	}

}
