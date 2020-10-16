<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * Model untuk modul Suplemen
 *
 * donjo-app/models/Suplemen_model.php
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

class Suplemen_model extends MY_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function create()
	{
		$data = $this->validasi($this->input->post());
		$hasil = $this->db->insert('suplemen', $data);
		$_SESSION["success"] = $hasil ? 1 : -1;
	}

	private function validasi($post)
	{
		$data = [];
		// Ambil dan bersihkan data input
		$data['sasaran'] = $post['sasaran'];
		$data['nama'] = nomor_surat_keputusan($post['nama']);
		$data['keterangan'] = htmlentities($post['keterangan']);
		return $data;
	}

	public function list_data($sasaran = 0)
	{
		if ($sasaran > 0) $this->db->where('s.sasaran', $sasaran);

		$data = $this->db
			->select('s.*')
			->select('COUNT(st.id) AS jml')
			->from('suplemen s')
			->join('suplemen_terdata st', "s.id = st.id_suplemen", 'left')
			->order_by('s.nama')
			->group_by('s.id')
			->get()
			->result_array();

		return $data;
	}

	public function list_sasaran($id, $sasaran)
	{
		$data = [];
		switch ($sasaran)
		{
			// Sasaran Penduduk
			case '1':
				$data['judul'] = 'NIK / Nama Penduduk';
				$data['data'] = $this->list_penduduk($id);
				break;

			// Sasaran Keluarga
			case '2':
				$data['judul'] = 'No.KK / Nama Kepala Keluarga';
				$data['data'] = $this->list_kk($id);

			default:
				# code...
				break;
		}

		return $data;
	}

	private function get_id_terdata_penduduk($id_suplemen)
	{
		$list_penduduk = $this->db
			->select('p.id')
			->from('tweb_penduduk p')
			->join('suplemen_terdata t', 'p.id = t.id_terdata', 'left')
			->where('t.id_suplemen', $id_suplemen)
			->get()
			->result_array();

		return sql_in_list(array_column($list_penduduk, 'id'));
	}

	private function list_penduduk($id)
	{
		// Penduduk yang sudah terdata untuk suplemen ini
		$terdata = $this->get_id_terdata_penduduk($id);
		if ($terdata) $this->db->where("p.id NOT IN ($terdata)");

		$data = $this->db->select('p.id as id, p.nik as nik, p.nama, w.rt, w.rw, w.dusun')
			->from('tweb_penduduk p')
			->join('tweb_wil_clusterdesa w', 'w.id = p.id_cluster', 'left')
			->get()
			->result_array();

		$hasil = [];
		foreach ($data as $item)
		{
			$penduduk = array(
				'id' => $item['id'],
				'nama' => strtoupper($item['nama']) ." [".$item['nik']."]",
				'info' => "RT/RW ". $item['rt']."/".$item['rw']." - ".strtoupper($item['dusun'])
			);
			$hasil[] = $penduduk;
		}
		return $hasil;
	}

	private function get_id_terdata_kk($id_suplemen)
	{
		$list_kk = $this->db
			->select('k.id')
			->from('tweb_keluarga k')
			->join('suplemen_terdata t', 'k.id = t.id_terdata', 'left')
			->where('t.id_suplemen', $id_suplemen)
			->get()
			->result_array();

		return sql_in_list(array_column($list_kk, 'id'));
	}

	private function list_kk($id)
	{
		// Keluarga yang sudah terdata untuk suplemen ini
		$terdata = $this->get_id_terdata_kk($id);
		if ($terdata) $this->db->where("k.id NOT IN ($terdata)");

		// Daftar keluarga, tidak termasuk keluarga yang sudah terdata
		$data = $this->db->select('k.id as id, k.no_kk, p.nama, w.rt, w.rw, w.dusun')
			->from('tweb_keluarga k')
			->join('tweb_penduduk p', 'p.id = k.nik_kepala', 'left')
			->join('tweb_wil_clusterdesa w', 'w.id = p.id_cluster', 'left')
			->get()
			->result_array();

		$hasil = [];
		foreach ($data as $item)
		{
			$item['id'] = preg_replace('/[^a-zA-Z0-9]/', '', $item['id']); //hapus non_alpha di no_kk
			$kk = array(
				'id' => $item['id'],
				'nama' => strtoupper($item['nama']) ." [".$item['no_kk']."]",
				'info' => "RT/RW ". $item['rt']."/".$item['rw']." - ".strtoupper($item['dusun'])
			);
			$hasil[] = $kk;
		}
		return $hasil;
	}

	public function get_suplemen($id)
	{
		$data = $this->db
			->select('s.*')
			->select('COUNT(st.id) AS jml')
			->from('suplemen s')
			->join('suplemen_terdata st', "s.id = st.id_suplemen", 'left')
			->where('s.id', $id)
			->group_by('s.id')
			->get()
			->row_array();

		return $data;
	}

	public function get_rincian($p, $suplemen_id)
	{
		$suplemen = $this->db->where('id', $suplemen_id)->get('suplemen')->row_array();

		switch ($suplemen['sasaran'])
		{
			// Sasaran Penduduk
			case '1':
				$data = $this->get_penduduk_terdata($suplemen_id, $p);
				$data['judul']['judul_terdata_info'] = 'No. KK';
				$data['judul']['judul_terdata_plus'] = 'NIK Penduduk';
				$data['judul']['judul_terdata_nama'] = 'Nama Penduduk';
				break;

			// Sasaran Keluarga
			case '2':
				$data = $this->get_kk_terdata($suplemen_id, $p);
				$data['judul']['judul_terdata_info'] = 'NIK KK';
				$data['judul']['judul_terdata_plus'] = 'No. KK';
				$data['judul']['judul_terdata_nama'] = 'Kepala Keluarga';

				break;

			// Sasaran X
			default:
				# code...
				break;
		}

		$data['suplemen'] = $suplemen;
		$data['keyword'] = $this->autocomplete($suplemen['sasaran']);

		return $data;
	}

	private function paging($p, $get_terdata_sql)
	{
		$sql = "SELECT COUNT(*) as jumlah ".$get_terdata_sql;
		$query = $this->db->query($sql);
		$row = $query->row_array();
		$jml_data = $row['jumlah'];

		$this->load->library('paging');
		$cfg['page'] = $p;
		$cfg['per_page'] = $_SESSION['per_page'];
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);

		return $this->paging;
	}

	private function get_penduduk_terdata_sql($suplemen_id)
	{
		# Data penduduk
		$sql = " FROM suplemen_terdata s
			LEFT JOIN tweb_penduduk o ON s.id_terdata = o.id
			LEFT JOIN tweb_keluarga k ON k.id = o.id_kk
			LEFT JOIN tweb_wil_clusterdesa w ON w.id = o.id_cluster
			WHERE s.id_suplemen=".$suplemen_id;
		return $sql;
	}

	private function get_penduduk_terdata($suplemen_id, $p)
	{
		$hasil = [];
		$get_terdata_sql = $this->get_penduduk_terdata_sql($suplemen_id);
		$select_sql = "SELECT s.*, s.id_terdata, o.nik, o.nama, o.tempatlahir, o.tanggallahir, o.sex, k.no_kk, w.rt, w.rw, w.dusun,
			(case when (o.id_kk IS NULL or o.id_kk = 0) then o.alamat_sekarang else k.alamat end) AS alamat
		 ";
		$sql = $select_sql.$get_terdata_sql;
		$sql .= $this->search_sql('1');
		if ( ! empty($_SESSION['per_page']) and $_SESSION['per_page'] > 0)
		{
			$hasil["paging"] = $this->paging($p, $get_terdata_sql.$this->search_sql('1'));
			$paging_sql = ' LIMIT ' .$hasil["paging"]->offset. ',' .$hasil["paging"]->per_page;
			$sql .= $paging_sql;
		}
		$query = $this->db->query($sql);

		if ($query->num_rows() > 0)
		{
			$data = $query->result_array();
			for ($i=0; $i<count($data); $i++)
			{
				$data[$i]['terdata_info'] = $data[$i]['no_kk'];
				$data[$i]['terdata_plus'] = $data[$i]['nik'];
				$data[$i]['terdata_nama'] = strtoupper($data[$i]['nama']);
				$data[$i]['tempat_lahir'] = strtoupper($data[$i]['tempatlahir']);
				$data[$i]['tanggal_lahir'] = tgl_indo($data[$i]['tanggallahir']);
				$data[$i]['sex'] = ($data[$i]['sex'] == 1) ? "LAKI-LAKI" : "PEREMPUAN";
				$data[$i]['info'] = strtoupper($data[$i]['alamat'] . " "  .  "RT/RW ". $data[$i]['rt']."/".$data[$i]['rw'] . " - " . $this->setting->sebutan_dusun . " " . $data[$i]['dusun']);
			}
			$hasil['terdata'] = $data;
		}

		return $hasil;
	}

	private function get_kk_terdata_sql($suplemen_id)
	{
		# Data KK
		$sql = " FROM suplemen_terdata s
			LEFT JOIN tweb_keluarga o ON s.id_terdata = o.id
			LEFT JOIN tweb_penduduk q ON o.nik_kepala = q.id
			LEFT JOIN tweb_wil_clusterdesa w ON w.id = q.id_cluster
			WHERE s.id_suplemen=".$suplemen_id;
		return $sql;
	}


	private function get_kk_terdata($suplemen_id, $p)
	{
		$hasil = [];
		$get_terdata_sql = $this->get_kk_terdata_sql($suplemen_id);
		$select_sql = "SELECT s.*, s.id_terdata, o.no_kk, s.id_suplemen, o.nik_kepala, o.alamat, q.nik, q.nama, q.tempatlahir, q.tanggallahir, q.sex, w.rt, w.rw, w.dusun ";
		$sql = $select_sql.$get_terdata_sql;
		$sql .= $this->search_sql('2');
		if ( ! empty($_SESSION['per_page']) and $_SESSION['per_page'] > 0)
		{
			$hasil["paging"] = $this->paging($p, $get_terdata_sql.$this->search_sql('2'));
			$paging_sql = ' LIMIT ' .$hasil["paging"]->offset. ',' .$hasil["paging"]->per_page;
			$sql .= $paging_sql;
		}
		$query = $this->db->query($sql);

		if ($query->num_rows() > 0)
		{
			$data = $query->result_array();
			for ($i=0; $i<count($data); $i++)
			{
				$data[$i]['terdata_info'] = $data[$i]['nik'];
				$data[$i]['terdata_plus'] = $data[$i]['no_kk'];
				$data[$i]['terdata_nama'] = strtoupper($data[$i]['nama']);
				$data[$i]['tempat_lahir'] = strtoupper($data[$i]['tempatlahir']);
				$data[$i]['tanggal_lahir'] = tgl_indo($data[$i]['tanggallahir']);
				$data[$i]['sex'] = ($data[$i]['sex'] == 1) ? "LAKI-LAKI" : "PEREMPUAN";
				$data[$i]['info'] = strtoupper($data[$i]['alamat'] . " "  .  "RT/RW ". $data[$i]['rt']."/".$data[$i]['rw'] . " - " . $this->setting->sebutan_dusun . " " . $data[$i]['dusun']);
			}
			$hasil['terdata'] = $data;
		}
		return $hasil;
	}

	/*
		Mengambil data individu terdata
	*/
	public function get_terdata($id_terdata, $sasaran)
	{
		$this->load->model('surat_model');
		switch ($sasaran)
		{
			// Sasaran Penduduk
			case 1:
				$sql = "SELECT u.id AS id, u.nama AS nama, x.nama AS sex, u.id_kk AS id_kk,
				u.tempatlahir AS tempatlahir, u.tanggallahir AS tanggallahir,
				(select (date_format(from_days((to_days(now()) - to_days(tweb_penduduk.tanggallahir))),'%Y') + 0) AS `(date_format(from_days((to_days(now()) - to_days(tweb_penduduk.tanggallahir))),'%Y') + 0)`
				from tweb_penduduk where (tweb_penduduk.id = u.id)) AS umur,
				w.nama AS status_kawin, f.nama AS warganegara, a.nama AS agama, d.nama AS pendidikan, j.nama AS pekerjaan, u.nik AS nik, c.rt AS rt, c.rw AS rw, c.dusun AS dusun, k.no_kk AS no_kk, k.alamat,
				(select tweb_penduduk.nama AS nama from tweb_penduduk where (tweb_penduduk.id = k.nik_kepala)) AS kepala_kk
				from tweb_penduduk u
				left join tweb_penduduk_sex x on u.sex = x.id
				left join tweb_penduduk_kawin w on u.status_kawin = w.id
				left join tweb_penduduk_agama a on u.agama_id = a.id
				left join tweb_penduduk_pendidikan_kk d on u.pendidikan_kk_id = d.id
				left join tweb_penduduk_pekerjaan j on u.pekerjaan_id = j.id
				left join tweb_wil_clusterdesa c on u.id_cluster = c.id
				left join tweb_keluarga k on u.id_kk = k.id
				left join tweb_penduduk_warganegara f on u.warganegara_id = f.id
				WHERE u.id = ?";
				$query = $this->db->query($sql, $id_terdata);
				$data  = $query->row_array();
				$data['terdata_info'] = $data['nik'];
				$data['terdata_plus'] = $data['no_kk'];
				$data['terdata_nama'] = $data['nama'];
				$data['alamat_wilayah']= $this->surat_model->get_alamat_wilayah($data);
				break;

			// Sasaran Keluarga
			case 2:
				$data = $this->keluarga_model->get_kepala_kk($id_terdata);
				$data['terdata_info'] = $data['nik'];
				$data['terdata_plus'] = $data['no_kk'];
				$data['terdata_nama'] = $data['nama'];
				$data['id'] = $data['id_kk']; // id_kk digunakan sebagai id terdata
				break;

			default:
				break;
		}
		return $data;
	}

	public function hapus($id)
	{
		$ada = $this->db->where('id_suplemen', $id)
			->get('suplemen_terdata')->num_rows();
		if ($ada)
		{
			$this->session->success = '-1';
			$this->session->error_msg = ' --> Tidak bisa dihapus, karena masih digunakan';
			return;
		}
		$hasil = $this->db->where('id', $id)->delete('suplemen');

		status_sukses($hasil); //Tampilkan Pesan
	}

	public function update($id)
	{
		$data = $this->validasi($this->input->post());
		$hasil = $this->db->where('id', $id)->update('suplemen', $data);

		status_sukses($hasil); //Tampilkan Pesan
	}

	public function add_terdata($post, $id)
	{
		$id_terdata = $post['id_terdata'];
		$sasaran = $this->db->select('sasaran')->where('id', $id)->get('suplemen')->row()->sasaran;
		$hasil = $this->db->where('id_suplemen', $id)->where('id_terdata', $id_terdata)->get('suplemen_terdata');
		if ($hasil->num_rows() > 0)
		{
			return false;
		}
		else
		{
			$data = array(
				'id_suplemen' => $id,
				'id_terdata' => $id_terdata,
				'sasaran' => $sasaran,
				'keterangan' => substr(htmlentities($post['keterangan']), 0, 100) // Batasi 100 karakter
			);
			return $this->db->insert('suplemen_terdata', $data);
		}
	}

	public function hapus_terdata($id_terdata)
	{
		$this->db->where('id', $id_terdata);
		$this->db->delete('suplemen_terdata');
	}

	// $id = suplemen_terdata.id
	public function edit_terdata($post,$id)
	{
		$data['keterangan'] = substr(htmlentities($post['keterangan']), 0, 100); // Batasi 100 karakter
		$this->db->where('id', $id);
		$this->db->update('suplemen_terdata', $data);
	}

	/*
		Mengambil data individu terdata menggunakan id tabel suplemen_terdata
	*/
	public function get_suplemen_terdata_by_id($id)
	{
		$data = $this->db->where('id', $id)->get('suplemen_terdata')->row_array();
		// Data tambahan untuk ditampilkan
		$terdata = $this->get_terdata($data['id_terdata'], $data['sasaran']);
		switch ($data['sasaran'])
		{
			case 1:
				$data['judul_terdata_nama'] = 'NIK';
				$data['judul_terdata_info'] = 'Nama Terdata';
				$data['terdata_nama'] = $terdata['nik'];
				$data['terdata_info'] = $terdata['nama'];
				break;
			case 2:
				$data['judul_terdata_nama'] = 'No. KK';
				$data['judul_terdata_info'] = 'Kepala Keluarga';
				$data['terdata_nama'] = $terdata['no_kk'];
				$data['terdata_info'] = $terdata['nama'];
				break;
			default:
		}

		return $data;
	}

	public function get_terdata_suplemen($sasaran,$id_terdata)
	{
		$list_suplemen = [];
		/*
		 * Menampilkan keterlibatan $id_terdata dalam data suplemen yang ada
		 *
		 * */
		$strSQL = "SELECT p.id as id, o.id_terdata as nik, p.nama as nama, p.keterangan
			FROM suplemen_terdata o
			LEFT JOIN suplemen p ON p.id = o.id_suplemen
			WHERE ((o.id_terdata='".$id_terdata."') AND (o.sasaran='".$sasaran."'))";
		$query = $this->db->query($strSQL);
		if ($query->num_rows() > 0)
		{
			$list_suplemen = $query->result_array();
		}

		switch ($sasaran)
		{
			case 1:
				/*
				 * Rincian Penduduk
				 * */
				$strSQL = "SELECT o.nama, o.foto, o.nik, w.rt, w.rw, w.dusun,
				(case when (o.id_kk IS NULL or o.id_kk = 0) then o.alamat_sekarang else k.alamat end) AS alamat
					FROM tweb_penduduk o
					LEFT JOIN tweb_keluarga k ON k.id = o.id_kk
					LEFT JOIN tweb_wil_clusterdesa w ON w.id = o.id_cluster
					WHERE o.id = '".$id_terdata."'";
				$query = $this->db->query($strSQL);
				if ($query->num_rows() > 0)
				{
					$row = $query->row_array();
					$data_profil = array(
						"id" => $id,
						"nama" => $row["nama"] ." - ".$row["nik"],
						"ndesc" => "Alamat: ".$row["alamat"]." RT ".strtoupper($row["rt"])." / RW ".strtoupper($row["rw"])." ".strtoupper($row["dusun"]),
						"foto" => $row["foto"]
						);
				}

				break;
			case 2:
				/*
				 * KK
				 * */
				$strSQL = "SELECT o.nik_kepala, o.no_kk, o.alamat, p.nama, w.rt, w.rw, w.dusun
					FROM tweb_keluarga o
					LEFT JOIN tweb_penduduk p ON o.nik_kepala = p.id
					LEFT JOIN tweb_wil_clusterdesa w ON w.id = p.id_cluster
					WHERE o.id = '".$id_terdata."'";
				$query = $this->db->query($strSQL);
				if ($query->num_rows() > 0)
				{
					$row = $query->row_array();
					$data_profil = array(
						"id" => $id,
						"nama" => "Kepala KK : ".$row["nama"].", NO KK: ".$row["no_kk"],
						"ndesc" => "Alamat: ".$row["alamat"]." RT ".strtoupper($row["rt"])." / RW ".strtoupper($row["rw"])." ".strtoupper($row["dusun"]),
						"foto" => ""
						);
				}

				break;
			default:

		}
		if ( ! empty($list_suplemen))
		{
			$hasil = array("daftar_suplemen" => $list_suplemen, "profil" => $data_profil);
			return $hasil;
		}
		else
		{
			return null;
		}
	}

	protected function search_sql($sasaran = '')
	{
		if ( $this->session->cari)
		{
			$cari = $this->session->cari;
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' .$kw. '%';
			switch ($sasaran)
			{
				case '1':
					## sasaran penduduk
					$search_sql = " AND (o.nama LIKE '$kw' OR o.nik LIKE '$kw' OR k.no_kk like '$kw')";
					break;
				case '2':
					## sasaran keluarga / KK
					$search_sql = " AND (o.no_kk LIKE '$kw' OR o.nik_kepala LIKE '$kw' OR q.nik LIKE '$kw' OR q.nama LIKE '$kw')";
					break;
			}
			return $search_sql;
		}
	}

	private function autocomplete($sasaran)
	{
		switch ($sasaran)
		{
			case '1':
				## sasaran penduduk
				$data = $this->db
					->select('p.nama')
					->from('suplemen_terdata s')
					->join('tweb_penduduk p', 'p.id = s.id_terdata', 'left')
					->where('s.sasaran', $sasaran)
					->group_by('p.nama')
					->get()
					->result_array();
				break;

			case '2':
				## sasaran keluarga / KK
				$data = $this->db
					->select('p.nama')
					->from('suplemen_terdata s')
					->join('tweb_keluarga k', 'k.id = s.id_terdata', 'left')
					->join('tweb_penduduk p', 'p.id = k.nik_kepala', 'left')
					->where('s.sasaran', $sasaran)
					->group_by('p.nama')
					->get()
					->result_array();
				break;
			default:
				break;
		}

		return autocomplete_data_ke_str($data);
	}

}
?>
