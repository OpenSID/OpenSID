<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * Model untuk modul Statistik Kependudukan
 *
 * donjo-app/models/Laporan_penduduk_model.php
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

class Laporan_penduduk_model extends MY_Model {

	private $lap;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('program_bantuan_model');
	}

	public function search_sql()
	{
		if (isset($_SESSION['cari']))
		{
			$cari = $_SESSION['cari'];
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' .$kw. '%';
			$search_sql= " AND u.nama LIKE '$kw'";

			return $search_sql;
		}
	}

	// TODO: Ubah yg masih menggunakan, spy menggunakan penanganan wilayah di wilayah_model.php
	public function list_dusun()
	{
		$sql = $this->db
		->select()
		->from('tweb_wil_clusterdesa')
		->where('rt', '0')
		->where('rw', '0')
		->get();

		return $sql->result_array();
	}

	// TODO: Ubah yg masih menggunakan, spy menggunakan penanganan wilayah di wilayah_model.php
	public function list_rw($dusun = '')
	{
		$sql = $this->db
		->select()
		->from('tweb_wil_clusterdesa')
		->where('rt', '0')
		->where('dusun', $dusun)
		->where('rw <>', '0')
		->get();

		return $sql->result_array();
	}

	// TODO: Ubah yg masih menggunakan, spy menggunakan penanganan wilayah di wilayah_model.php
	public function list_rt($dusun = '', $rw = '')
	{
		$sql = $this->db
		->select()
		->from('tweb_wil_clusterdesa')
		->where('dusun', $dusun)
		->where('rw', $rw)
		->where('rt <>', '0')
		->get();

		return $sql->result_array();
	}

	private function dusun_sql()
	{
		if ($dusun = $this->session->userdata("dusun")) {
			$sql = "AND a.dusun = '$dusun' ";

			return $sql;
		}
	}

	private function rw_sql()
	{
		if ($rw = $this->session->userdata("rw")) {
			$sql = "AND a.rw = '$rw' ";

			return $sql;
		}
	}

	private function rt_sql()
	{
		if ($rt = $this->session->userdata("rt")) {
			$sql = "AND a.rt = '$rt' ";

			return $sql;
		}
	}

	protected function get_jumlah_sql($fk = false, $delimiter = false, $where = 0)
	{
		$sql = "(SELECT COUNT(b.id) FROM penduduk_hidup b
		LEFT JOIN tweb_wil_clusterdesa a ON b.id_cluster = a.id
		WHERE 1 ";
		$sql .= $fk ? "AND $fk = u.id " : "";
		$sql .= $where ? : '';
		$sql .= $this->dusun_sql();
		$sql .= $this->rw_sql();
		$sql .= $this->rt_sql();
		$sql .= ") AS jumlah";
		$sql .= $delimiter ? ',' : '';

		return $sql;
	}

	protected function get_laki_sql($fk = false, $delimiter = false, $where = 0)
	{
		$sql = "(SELECT COUNT(b.id) FROM penduduk_hidup b
		LEFT JOIN tweb_wil_clusterdesa a ON b.id_cluster = a.id
		WHERE sex = 1 ";
		$sql .= $fk ? "AND $fk = u.id " : "";
		$sql .= $where ? : '';
		$sql .= $this->dusun_sql();
		$sql .= $this->rw_sql();
		$sql .= $this->rt_sql();
		$sql .= ") AS laki";
		$sql .= $delimiter ? ',' : '';

		return $sql;
	}

	protected function get_perempuan_sql($fk = false, $delimiter = false, $where = 0)
	{
		$sql = "(SELECT COUNT(b.id) FROM penduduk_hidup b
		LEFT JOIN tweb_wil_clusterdesa a ON b.id_cluster = a.id
		WHERE sex = 2 ";
		$sql .= $fk ? "AND $fk = u.id " : "";
		$sql .= $where ? : '';
		$sql .= $this->dusun_sql();
		$sql .= $this->rw_sql();
		$sql .= $this->rt_sql();
		$sql .= ") AS perempuan";
		$sql .= $delimiter ? ',' : '';

		return $sql;
	}

	public function judul_statistik($lap)
	{
		// Program bantuan berbentuk '50<program_id>'
		if ($lap > 50)
		{
			$program_id = preg_replace("/^50/", "", $lap);

			$program = $this->db
				->select('nama')
				->where('id', $program_id)
				->get('program')
				->row_array();

			return $program['nama'];
		}

		$list_judul = unserialize(STAT_PENDUDUK) + unserialize(STAT_KELUARGA) + unserialize(STAT_BANTUAN);

		return $list_judul[$lap];
	}

	// -------------------- Siapkan data untuk statistik kependudukan -------------------

	protected function hitung_total(&$data)
	{
		$total['no'] = "";
		$total['id'] = TOTAL;
		$total['jumlah'] = 0;
		$total['laki'] = 0;
		$total['perempuan'] = 0;
		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $i + 1;
			$total['jumlah'] += $data[$i]['jumlah'];
			$total['laki'] += $data[$i]['laki'];
			$total['perempuan'] += $data[$i]['perempuan'];
		}

		return $total;
	}

	protected function isi_nomor(&$data)
	{
		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $i + 1;
		}
	}

	protected function hitung_persentase(&$data, $semua)
	{
		// Hitung semua presentase
		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['persen'] = persen($data[$i]['jumlah'] / $semua['jumlah']);
			$data[$i]['persen1'] = persen($data[$i]['laki'] / $semua['jumlah']);
			$data[$i]['persen2'] = persen($data[$i]['perempuan'] / $semua['jumlah']);
		}

		$data['total'] = $semua;
	}

	protected function baris_jumlah($total, $nama)
	{
		// Isi Total
		$baris_jumlah = array(
			'no' => "",
			'id' => JUMLAH,
			'nama' => $nama,
			'jumlah' => $total['jumlah'],
			'perempuan' => $total['perempuan'],
			'laki' => $total['laki']
		);

		return $baris_jumlah;
	}

	protected function baris_belum($semua, $total, $nama)
	{
		// Isi data jml belum mengisi
		$baris_belum = array(
			'no' => "",
			'id' => BELUM_MENGISI,
			'nama' => $nama,
			'jumlah' => $semua['jumlah'] - $total['jumlah'],
			'perempuan' => $semua['perempuan'] - $total['perempuan'],
			'laki' => $semua['laki'] - $total['laki']
		);
		if (isset($total['jumlah_nonaktif']))
		{
			$baris_belum['jumlah'] += $total['jumlah_nonaktif'];
			$baris_belum['perempuan'] += $total['jumlah_nonaktif_perempuan'];
			$baris_belum['laki'] += $total['jumlah_nonaktif_laki'];
		}

		return $baris_belum;
	}

	private function select_jml_penduduk_per_kategori($id_referensi, $tabel_referensi)
	{
		$this->db
		->select('u.*, COUNT(p.id) AS jumlah')
		->select('COUNT(CASE WHEN p.sex = 1 THEN p.id END) AS laki')
		->select('COUNT(CASE WHEN p.sex = 2 THEN p.id END) AS perempuan')
		->from("$tabel_referensi u")
		->join('penduduk_hidup p', "u.id = p.$id_referensi", 'left')
		->join('tweb_wil_clusterdesa a', 'p.id_cluster = a.id', 'left')
		->group_by('u.id');

		if ($dusun = $this->session->userdata("dusun")) $this->db->where('a.dusun', $dusun);
		if ($rw = $this->session->userdata("rw")) $this->db->where('a.rw', $rw);
		if ($rt = $this->session->userdata("rt")) $this->db->where('a.rt', $rt);
	}

	protected function data_jml_semua_penduduk()
	{
		$this->db
		->select('COUNT(b.id) AS jumlah')
		->select('COUNT(CASE WHEN b.sex = 1 THEN b.id END) AS laki')
		->select('COUNT(CASE WHEN b.sex = 2 THEN b.id END) AS perempuan')
		->from('penduduk_hidup b')
		->join('tweb_wil_clusterdesa a', 'b.id_cluster = a.id', 'left');

		if ($dusun = $this->session->userdata("dusun")) $this->db->where('a.dusun', $dusun);
		if ($rw = $this->session->userdata("rw")) $this->db->where('a.rw', $rw);
		if ($rt = $this->session->userdata("rt")) $this->db->where('a.rt', $rt);

		$semua = $this->db->get()->row_array();

		return $semua;
	}

	protected function data_jml_semua_keluarga()
	{
		// Data jumlah
		$semua = $this->db
		->select('COUNT(k.id) as jumlah')
		->select('COUNT(CASE WHEN p.sex = 1 THEN p.id END) AS laki')
		->select('COUNT(CASE WHEN p.sex = 2 THEN p.id END) AS perempuan')
		->from('keluarga_aktif k')
		->join('tweb_penduduk p', 'p.id=k.nik_kepala', 'left')
		->get()->row_array();

		return $semua;
	}

	protected function persentase_semua($semua)
	{
		// Hitung persentase
		$semua['no'] = "";
		$semua['id'] = TOTAL;
		$semua['nama'] = "TOTAL";
		$semua['persen'] = persen(($semua['laki'] + $semua['perempuan']) / $semua['jumlah']);
		$semua['persen1'] = persen($semua['laki'] / $semua['jumlah']);
		$semua['persen2'] = persen($semua['perempuan'] / $semua['jumlah']);

		return $semua;
	}

	protected function order_by($o)
	{
		//Ordering SQL
		switch ($o)
		{
			case 1: $this->db->order_by('u.id'); break;
			case 2: $this->db->order_by('u.id DESC'); break;
			case 3: $this->db->order_by('laki'); break;
			case 4: $this->db->order_by('laki DESC'); break;
			case 5: $this->db->order_by('jumlah'); break;
			case 6: $this->db->order_by('jumlah DESC'); break;
			case 7: $this->db->order_by('perempuan'); break;
			case 8: $this->db->order_by('perempuan DESC'); break;
		}
	}

	private function select_jml($where)
	{
		$str_jml_penduduk = $this->str_jml_penduduk($where);
		$str_jml_laki = $this->str_jml_penduduk($where, '1');
		$str_jml_perempuan = $this->str_jml_penduduk($where, '2');
		$this->db
		->select("($str_jml_penduduk) as jumlah")
		->select("($str_jml_laki) as laki")
		->select("($str_jml_perempuan) as perempuan");
	}

	private function str_jml_penduduk($where, $sex='')
	{
		if ($dusun = $this->session->userdata("dusun")) $this->db->where('a.dusun', $dusun);
		if ($rw = $this->session->userdata("rw")) $this->db->where('a.rw', $rw);
		if ($rt = $this->session->userdata("rt")) $this->db->where('a.rt', $rt);
		if ($sex) $this->db->where('b.sex', $sex);
		$str_jml_penduduk = $this->db->select('COUNT(b.id)')
		->from('penduduk_hidup b')
		->join('tweb_wil_clusterdesa a', 'b.id_cluster = a.id')
		->where($where)
		->get_compiled_select();

		return $str_jml_penduduk;
	}

	protected function select_per_kategori()
	{
		$lap = $this->lap;

		// Bagian Penduduk
		$statistik_penduduk = array(
			"0" => array('id_referensi' => "pendidikan_kk_id", 'tabel_referensi' => "tweb_penduduk_pendidikan_kk"),
			"1" => array('id_referensi' => "pekerjaan_id", 'tabel_referensi' => "tweb_penduduk_pekerjaan"),
			"2" => array('id_referensi' => "status_kawin", 'tabel_referensi' => "tweb_penduduk_kawin"),
			"3" => array('id_referensi' => "agama_id", 'tabel_referensi' => "tweb_penduduk_agama"),
			"4" => array('id_referensi' => "sex", 'tabel_referensi' => "tweb_penduduk_sex"),
			"hubungan_kk" => array('id_referensi' => "kk_level", 'tabel_referensi' => "tweb_penduduk_hubungan"),
			"5" => array('id_referensi' => "warganegara_id", 'tabel_referensi' => "tweb_penduduk_warganegara"),
			"6" => array('id_referensi' => "status", 'tabel_referensi' => "tweb_penduduk_status"),
			"7" => array('id_referensi' => "golongan_darah_id", 'tabel_referensi' => "tweb_golongan_darah"),
			"9" => array('id_referensi' => "cacat_id", 'tabel_referensi' => "tweb_cacat"),
			"10" => array('id_referensi' => "sakit_menahun_id", 'tabel_referensi' => "tweb_sakit_menahun"),
			"14" => array('id_referensi' => "pendidikan_sedang_id", 'tabel_referensi' => "tweb_penduduk_pendidikan"),
			"16" => array('id_referensi' => "cara_kb_id", 'tabel_referensi' => "tweb_cara_kb"),
			"19" => array('id_referensi' => "id_asuransi", 'tabel_referensi' => "tweb_penduduk_asuransi")
		);

		switch ("$lap")
		{
			//Bagian Keluarga
			case 'kelas_sosial':
				$this->db
				->select('u.*, COUNT(k.id) as jumlah')
				->select('COUNT(CASE WHEN kelas_sosial = u.id AND p.sex = 1 THEN p.id END) AS laki')
				->select('COUNT(CASE WHEN kelas_sosial = u.id AND p.sex = 2 THEN p.id END) AS perempuan')
				->from('tweb_keluarga_sejahtera u')
				->join('keluarga_aktif k', 'k.kelas_sosial = u.id', 'left')
				->join('tweb_penduduk p', 'p.id=k.nik_kepala', 'left')
				->group_by('u.id');
				break;

			//STATUS_COVID
			case 'covid':
				$this->db
				->select('u.*, COUNT(k.id) as jumlah')
				->select('COUNT(CASE WHEN k.status_covid = u.nama AND p.sex = 1 THEN k.id_terdata END) AS laki')
				->select('COUNT(CASE WHEN k.status_covid = u.nama AND p.sex = 2 THEN k.id_terdata END) AS perempuan')
				->from('ref_status_covid u')
				->join('covid19_pemudik k', 'k.status_covid = u.nama', 'left')
				->join('tweb_penduduk p', 'p.id=k.id_terdata', 'left')
				->group_by('u.id');
				break;

			//penerima_bantuan
			case 'bantuan_penduduk': $sql =
				"SELECT u.*,
				(SELECT COUNT(kartu_nik) FROM program_peserta WHERE program_id = u.id) AS jumlah,
				(SELECT COUNT(k.kartu_nik) FROM program_peserta k INNER JOIN tweb_penduduk p ON k.kartu_nik=p.nik WHERE program_id = u.id AND p.sex = 1) AS laki,
				(SELECT COUNT(k.kartu_nik) FROM program_peserta k INNER JOIN tweb_penduduk p ON k.kartu_nik=p.nik WHERE program_id = u.id AND p.sex = 2) AS perempuan
				FROM program u";
				break;

			case in_array($lap, array_keys($statistik_penduduk)):
				$this->select_jml_penduduk_per_kategori($statistik_penduduk["$lap"]['id_referensi'], $statistik_penduduk["$lap"]['tabel_referensi']);
				break;

			case "13":
					// Umur rentang
				$where = "(DATE_FORMAT(FROM_DAYS(TO_DAYS( NOW()) - TO_DAYS(tanggallahir)) , '%Y')+0)>=u.dari AND (DATE_FORMAT(FROM_DAYS( TO_DAYS(NOW()) - TO_DAYS(tanggallahir)) , '%Y')+0) <= u.sampai";
				$this->select_jml($where);
				$this->db->select('u.*')
				->from('tweb_penduduk_umur u')
				->where('u.status', "1");
				break;

			case "15":
					// Umur kategori
				$where = "(DATE_FORMAT(FROM_DAYS(TO_DAYS( NOW()) - TO_DAYS(tanggallahir)) , '%Y')+0)>=u.dari AND (DATE_FORMAT(FROM_DAYS( TO_DAYS(NOW()) - TO_DAYS(tanggallahir)) , '%Y')+0) <= u.sampai ";
				$this->select_jml($where);
				$this->db->select("u.*, concat(u.nama, ' (', u.dari, ' - ', u.sampai, ')') as nama")
				->from('tweb_penduduk_umur u')
				->where('u.status', "0");
				break;

			case "17":
					// Akta kelahiran
				$where = "(DATE_FORMAT(FROM_DAYS(TO_DAYS( NOW()) - TO_DAYS(tanggallahir)) , '%Y')+0)>=u.dari AND (DATE_FORMAT(FROM_DAYS( TO_DAYS(NOW()) - TO_DAYS(tanggallahir)) , '%Y')+0) <= u.sampai AND akta_lahir <> '' ";
				$this->select_jml($where);
				$this->db->select("u.*, concat('UMUR ', u.dari, ' S/D ', u.sampai, ' TAHUN') as nama")
				->from('tweb_penduduk_umur u')
				->where('u.status', "1");
				break;

			case "18":
					// Kepemilikan ktp
				$where = "((DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(tanggallahir)), '%Y')+0)>=17 OR (status_kawin IS NOT NULL AND status_kawin <> 1)) AND u.status_rekam = status_rekam ";
				$this->select_jml($where);
				$this->db->select("u.*")
				->from('tweb_status_ktp u');
				break;

			default:
				$this->select_jml_penduduk_per_kategori($statistik_penduduk["0"]['id_referensi'], $statistik_penduduk["0"]['tabel_referensi']);
		}
		return true;
	}

	protected function get_data_jml()
	{
		$lap = $this->lap;
		//Siapkan data baris rekap
		if ($lap == 18)
		{
			$this->db->where("((DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(tanggallahir)), '%Y')+0)>=17 OR (status_kawin IS NOT NULL AND status_kawin <> 1))");
			$semua = $this->data_jml_semua_penduduk();
		}
		elseif (in_array($lap, array('kelas_sosial', 'bantuan_keluarga')))
		{
			$semua = $this->data_jml_semua_keluarga();
		}
		else
		{
			$semua = $this->data_jml_semua_penduduk();
		}

		return $semua;
	}

	public function list_data($lap = 0, $o = 0)
	{
		$this->lap = $lap;

		$this->load->model('statistik_penduduk_model');
		if ($statistik = $this->statistik_penduduk_model->statistik($lap))
		{
			// Statistik yg sudah di-refactor
			$namespace = $statistik;
			$judul_belum = $statistik->judul_belum;
			$judul_jumlah = $statistik->judul_jumlah;
		}
		else
		{
			$namespace = $this;
			$judul_jumlah = 'JUMLAH';
			$judul_belum = 'BELUM MENGISI';
		}

		if ($namespace->select_per_kategori())
		{
			$this->order_by($o);
			$data = $this->db->get()->result_array();
			$this->isi_nomor($data);
		}
		else $data = [];

		$semua = $namespace->get_data_jml();
		$semua = $this->persentase_semua($semua);
		$total = $namespace->hitung_total($data);

		$data[] = $this->baris_jumlah($total, $judul_jumlah);

		$data[] = $this->baris_belum($semua, $total, $judul_belum);
		$this->hitung_persentase($data, $semua);

		return $data;
	}

	// -------------------- Akhir siapkan data untuk statistik kependudukan -------------------


	public function list_data_rentang()
	{
		$query = $this->db->where('status', 1)->order_by('dari')->get('tweb_penduduk_umur');
		$data = $query->result_array();

		return $data;
	}

	public function get_rentang($id=0)
	{
		$sql = "SELECT * FROM tweb_penduduk_umur WHERE id = $id ";
		$query = $this->db->query($sql);
		$data = $query->row_array();

		return $data;
	}

	public function get_rentang_terakhir()
	{
		$sql = "SELECT (case when max(sampai) is null then '0' else (max(sampai)+1) end) as dari FROM tweb_penduduk_umur WHERE status = 1 ";
		$query = $this->db->query($sql);
		$data = $query->row_array();

		return $data;
	}

	public function insert_rentang()
	{
		$data = $_POST;
		$data['status'] = 1;
		if ($data['sampai'] != '99999')
			$data['nama'] = $data['dari'].' s/d '.$data['sampai'].' Tahun';
		else
			$data['nama'] = 'Di atas '.$data['dari'].' Tahun';
		$outp = $this->db->insert('tweb_penduduk_umur', $data);

		status_sukses($outp); //Tampilkan Pesan
	}

	public function update_rentang($id=0)
	{
		$data = $_POST;
		if ($data['sampai'] != '99999')
			$data['nama'] = $data['dari'].' s/d '.$data['sampai'].' Tahun';
		else
			$data['nama'] = 'Di atas '.$data['dari'].' Tahun';
		$outp = $this->db->where('id',$id)->update('tweb_penduduk_umur', $data);

		status_sukses($outp); //Tampilkan Pesan
	}

	public function delete_rentang($id='', $semua=false)
	{
		if (!$semua) $this->session->success = 1;

		$outp = $this->db->where('id', $id)->delete('tweb_penduduk_umur');

		status_sukses($outp, $gagal_saja=true); //Tampilkan Pesan
	}

	public function delete_all_rentang()
	{
		$this->session->success = 1;

		$id_cb = $_POST['id_cb'];
		foreach ($id_cb as $id)
		{
			$this->delete_rentang($id, $semua=true);
		}
	}

}

?>
