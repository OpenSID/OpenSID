<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * Model Log Penduduk untuk modul Kependudukan > Penduduk
 *
 * donjo-app/models/Penduduk_log_model.php
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

	class Penduduk_log_model extends MY_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('penduduk_model');
	}

	/**
	 * Ambil data log penduduk
	 *
	 * @param $id_log 					id log penduduk
	 * @return array(data log)
	 */
	public function get_log($id_log)
	{
		$log = $this->db
			->select("s.nama as status, s.id as status_id, date_format(tgl_peristiwa, '%d-%m-%Y') as tgl_peristiwa, id_detail, ref_pindah, catatan")
			->where('l.id', $id_log)
			->join('tweb_penduduk p','l.id_pend = p.id', 'left')
			->join('tweb_status_dasar s','s.id = p.status_dasar', 'left')
			->get('log_penduduk l')->row_array();
		if (empty($log['tgl_peristiwa'])) $log['tgl_peristiwa'] = date("d-m-Y");
		return $log;
	}

	/**
	 * Update log penduduk
	 *
	 * @param $id_log 					id log penduduk
	 * @return void
	 */
	public function update($id_log)
	{
		unset($_SESSION['success']);
		$data['catatan'] = htmlentities($this->input->post('catatan'));
		$data['tgl_peristiwa'] = rev_tgl($this->input->post('tgl_peristiwa'));
		if (!$this->db->where('id', $id_log)->update('log_penduduk', $data))
			$_SESSION['success'] = -1;
	}

	/**
	 * Kembalikan status dasar penduduk ke hidup
	 *
	 * @param $id_log 			id log penduduk
	 * @return void
	 */
	public function kembalikan_status($id_log)
	{
		$log = $this->db->where('id', $id_log)->get('log_penduduk')->row();
		$data['status_dasar'] = 1; // status dasar hidup
		$data['updated_at'] = date('Y-m-d H:i:s');
		$data['updated_by'] = $this->session->user;
		if (!$this->db->where('id',$log->id_pend)->update('tweb_penduduk', $data))
			$_SESSION['success'] = - 1;
		// Hapus log penduduk
		if (!$this->db->where('id', $id_log)->delete('log_penduduk'))
			$_SESSION['success'] = - 1;
	}

	/**
	 * Kembalikan status dasar sekumpulan penduduk ke hidup
	 *
	 * @param
	 * @return void
	 */
	public function kembalikan_status_all()
	{
		unset($_SESSION['success']);
		$id_cb = $_POST['id_cb'];
		foreach ($id_cb as $id)
		{
			$this->kembalikan_status($id);
		}
	}

	private function search_sql()
	{
		if ($cari = $this->session->cari)
		{
			$kw = $this->db->escape_like_str($cari);
			$this->db
				->group_start()
					->or_like('u.nama', $kw, 'both', FALSE)
					->or_like('u.nik', $kw, 'both', cFALSE)
				->group_end();
		}
	}

	private function status_dasar_sql()
	{
		if ($kf = $this->session->status_dasar)
		{
			$this->db->where('u.status_dasar', $kf);
		}
	}

	private function sex_sql()
	{
		if ($kf = $this->session->sex)
		{
			$this->db->where('u.sex', $kf);
		}
	}

	private function agama_sql()
	{
		if ($kf = $this->session->agama)
		{
			$this->db->where('u.agama_id', $kf);
		}
	}

	private function dusun_sql()
	{
		if ($kf = $this->session->dusun)
		{
			$this->db->where('a.dusun', $kf);
		}
	}

	private function rw_sql()
	{
		if ($kf = $this->session->rw)
		{
			$this->db->where('a.rw', $kf);
		}
	}

	private function rt_sql()
	{
		if ($kf = $this->session->rt)
		{
			$this->db->where('a.rt', $kf);
		}
	}

	public function paging($p=1, $o=0)
	{
		$this->db->select('COUNT(u.id) AS id');
		$this->list_data_sql();
		$jml_data = $this->db->get()
			->row()->id;

		$this->load->library('paging');
		$cfg['page'] = $p;
		$cfg['per_page'] = $_SESSION['per_page'];
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);

		return $this->paging;
	}

	// Digunakan untuk paging dan query utama supaya jumlah data selalu sama
	//
	// Batasi pada rekaman ubah status dasar saja, untuk ditampilkan di Log Penduduk.
	// Yaitu, batasi pada id_detail berikut:
	//   2 = status menjadi mati
	//   3 = status menjadi pindah
	//   4 = status menjadi hilang

	private function list_data_sql()
	{
		$this->db
			->from('tweb_penduduk u')
			->join('tweb_keluarga d', 'u.id_kk = d.id', 'left')
			->join('tweb_wil_clusterdesa a', 'd.id_cluster = a.id', 'left')
			->join('tweb_penduduk_sex x', 'u.sex = x.id', 'left')
			->join('tweb_penduduk_agama g', 'u.agama_id = g.id', 'left')
			->join('tweb_status_dasar sd', 'u.status_dasar = sd.id', 'left')
			->join('log_penduduk log', 'u.id = log.id_pend', 'left')
			->join('ref_pindah rp', 'rp.id = log.ref_pindah', 'left')
			->where('u.status_dasar >', 1)
			->where_in('log.id_detail', array(2, 3, 4));

		$this->search_sql();
		$this->status_dasar_sql();
		$this->sex_sql();
		$this->agama_sql();
		$this->dusun_sql();
		$this->rw_sql();
		$this->rt_sql();
	}

	// $limit = 0 mengambil semua
	public function list_data($o = 0, $offset = 0, $limit = 0)
	{
		//Main Query
		$this->db
			->select('u.id, u.nik, u.tanggallahir, u.id_kk, u.nama, u.foto, a.dusun, a.rw, a.rt, d.alamat, log.id as id_log, log.no_kk AS no_kk, log.catatan as catatan, log.nama_kk as nama_kk')
			->select('(CASE when log.id_detail = 3 then rp.nama else sd.nama end) as status_dasar')
			->select("(SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(log.tgl_peristiwa)-TO_DAYS(u.tanggallahir)), '%Y')+0) AS umur_pada_peristiwa")
			->select('x.nama AS sex, g.nama AS agama, log.tanggal, log.tgl_peristiwa, log.id_detail');

		$this->list_data_sql();

		switch ($o)
		{
			case 1: $this->db->order_by('u.nik', 'ASC'); break;
			case 2: $this->db->order_by('u.nik', 'DESC'); break;
			case 3: $this->db->order_by('u.nama', 'ASC'); break;
			case 4: $this->db->order_by('u.nama', 'DESC'); break;
			case 5: $this->db->order_by('d.no_kk', 'ASC'); break;
			case 6: $this->db->order_by('d.no_kk', 'DESC'); break;
			case 7: $this->db->order_by('umur_pada_peristiwa', 'ASC'); break;
			case 8: $this->db->order_by('umur_pada_peristiwa', 'DESC'); break;
			// Untuk Log Penduduk
			case 9:  $this->db->order_by('log.tgl_peristiwa', 'ASC'); break;
			case 10: $this->db->order_by('log.tgl_peristiwa', 'DESC'); break;
			default:$this->db->order_by('log.tgl_peristiwa', 'DESC'); break;
		}

		//Paging SQL
		if ($limit > 0) $this->db->limit($limit, $offset);
		$data = $this->db->get()->result_array();

		//Formating Output
		$j = $offset;
		for ($i=0; $i<count($data); $i++)
		{
			// Ubah alamat penduduk lepas
			if (!$data[$i]['id_kk'] OR $data[$i]['id_kk'] == 0)
			{
				// Ambil alamat penduduk
				$query = $this->db->select('p.id_cluster, p.alamat_sekarang, c.dusun, c.rw, c.rt')
					->from('tweb_penduduk p')
					->join('tweb_wil_clusterdesa c', 'p.id_cluster = c.id', 'left')
					->where('p.id', $data[$i]['id']);
				$penduduk = $query->get()->row_array();
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

}
