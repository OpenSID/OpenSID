<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * Model untuk modul Rumah Tangga
 *
 * donjo-app/models/Rtm_model.php,
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
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
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

class Rtm_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('config_model');
	}

	// Digunakan dimana ????
	private function jenis_sql()
	{
		if (isset($_SESSION['jenis']))
		{
			$kh = $_SESSION['jenis'];
			$jenis_sql = " AND jenis = $kh";
			return $jenis_sql;
		}
	}

	public function insert()
	{
		$nik = $_POST['nik_kepala'];

		$no_rtm = $this->db->select('no_kk')
			->order_by('length(no_kk) DESC, no_kk DESC')->limit(1)
			->get('tweb_rtm')
			->row()->no_kk;
		if ($no_rtm)
		{
			if (strlen($no_rtm) >= 5)
			{
				// Gunakan 5 digit terakhir sebagai nomor urut
				$kw = substr($no_rtm, 0, strlen($no_rtm) - 5);
				$no_urut = substr($no_rtm, -5);
				$no_urut = str_pad($no_urut + 1, 5, '0', STR_PAD_LEFT);
				$rtm['no_kk'] = $kw . $no_urut;
			}
			else
				$rtm['no_kk'] = str_pad($no_rtm + 1, strlen($no_rtm), '0', STR_PAD_LEFT);;
		}
		else
		{
			$kw = $this->get_kode_wilayah();
			$rtm['no_kk'] = $kw . str_pad('1', 5, '0', STR_PAD_LEFT);
		}

		$rtm['nik_kepala'] = $nik;
		$outp = $this->db->insert('tweb_rtm', $rtm);

		$default['id_rtm'] = $rtm['no_kk'];
		$default['rtm_level'] = 1;
		$default['updated_at'] = date('Y-m-d H:i:s');
		$default['updated_by'] = $this->session->user;
		$this->db->where('id', $nik);
		$this->db->update('tweb_penduduk', $default);

		status_sukses($outp); //Tampilkan Pesan
	}

	public function delete($no_kk='', $semua=false)
	{
		if (!$semua) $this->session->success = 1;

		$temp['id_rtm'] = 0;
		$temp['rtm_level'] = 0;
		$temp['updated_at'] = date('Y-m-d H:i:s');
		$temp['updated_by'] = $this->session->user;

		$this->db->where('id_rtm', $no_kk)->update('tweb_penduduk', $temp);

		$outp = $this->db->where('no_kk', $no_kk)->delete('tweb_rtm');

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

	public function add_anggota($id)
	{
		$data = $_POST;
		$no_rtm = $this->db->select('no_kk')
			->where('id', $id)
			->get('tweb_rtm')->row()->no_kk;
		$temp['id_rtm'] = $no_rtm;
		$temp['rtm_level'] = 2;
		$temp['updated_at'] = date('Y-m-d H:i:s');
		$temp['updated_by'] = $this->session->user;

		$this->db->where('id', $data['nik']);
		$outp = $this->db->update('tweb_penduduk', $temp);

		status_sukses($outp); //Tampilkan Pesan
	}

	// id = id_penduduk pd tweb_penduduk, id = nik_kepala pd tweb_rtm
	public function update_anggota($id, $id_rtm)
	{
		// Krn tweb_penduduk menggunakan no_kk(no_rtm) bukan id sebagai id_rtm, jd perlu dicari dlu
		$no_rtm = $this->db->get_where('tweb_rtm', ['id' => $id_rtm])->row();

		$rtm_level = $this->input->post('rtm_level');

		$data = [
			'rtm_level' => $rtm_level,
			'updated_at' => date('Y-m-d H:i:s'),
			'updated_by' => $this->session->user
		];

		if ($rtm_level == 1)
		{
			// Ganti semua level penduduk dgn id_rtm yg sma -> rtm_level = 2 (Anggota)
			$this->db->where('id_rtm', $no_rtm->no_kk)->update('tweb_penduduk', ['rtm_level' => '2']);

			// nik_kepala = id_penduduk pd table tweb_penduduk
			$this->db->where('id', $no_rtm->no_kk)->update('tweb_rtm', ['nik_kepala' => $id]);
		}

		$outp = $this->db->where('id', $id)->update('tweb_penduduk', $data);

		status_sukses($outp); //Tampilkan Pesan
	}

	public function rem_anggota($kk, $id)
	{
		$temp['id_rtm'] = 0;
		$temp['rtm_level'] = 0;
		$temp['updated_at'] = date('Y-m-d H:i:s');
		$temp['updated_by'] = $this->session->user;

		$pend = $this->rtm_model->get_anggota($id);
		$this->db->where('id', $id);
		$outp = $this->db->update('tweb_penduduk', $temp);
		if ($pend['rtm_level'] == '1')
		{
			$temp2['nik_kepala'] = 0;
			$this->db->where('id', $pend['id_rtm']);
			$outp = $this->db->update('tweb_rtm', $temp2);
		}

		if (!$outp) $this->session->success = -1;
	}

	public function rem_all_anggota($kk)
	{
		$id_cb = $_POST['id_cb'];
		foreach ($id_cb as $id)
		{
			$this->rem_anggota($kk, $id);
		}
	}

	public function get_dusun($id)
	{
		$sql = "SELECT * FROM tweb_rtm WHERE dusun_id = ?";
		$query = $this->db->query($sql, $id);
		$data = $query->row_array();
		return $data;
	}

	public function get_rtm($id)
	{
		$sql = "SELECT * FROM tweb_rtm WHERE id = ?";
		$query = $this->db->query($sql, $id);
		$data  = $query->row_array();
		return $data;
	}

	public function get_anggota($id)
	{
		$sql = "SELECT * FROM tweb_penduduk WHERE id_rtm = ?";
		$query = $this->db->query($sql, $id);
		$data  = $query->row_array();
		return $data;
	}

	private function get_kode_wilayah()
	{
		$data = $this->config_model->get_data();

		return $data['kode_desa'];
	}

	public function list_penduduk_lepas()
	{
		$sql = "SELECT p.id, p.nik, p.nama, h.nama as kk_level
			FROM tweb_penduduk p
			LEFT JOIN tweb_penduduk_hubungan h ON p.kk_level = h.id
			WHERE (status = 1 OR status = 3) AND status_dasar = 1 AND (id_rtm = 0 OR id_rtm IS NULL)";
		$query = $this->db->query($sql);
		$data = $query->result_array();

		//Formating Output
		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['alamat'] = "Alamat :".$data[$i]['nama'];
			$data[$i]['nama'] = ''.$data[$i]['nama'].' - '.$data[$i]['kk_level'].'';
		}
		return $data;
	}

	public function list_anggota($id)
	{
		$sql = "SELECT b.dusun, b.rw, b.rt, u.id, nik, x.nama as sex, k.no_kk, u.rtm_level, tempatlahir, tanggallahir, a.nama as agama, d.nama as pendidikan, j.nama as pekerjaan, w.nama as status_kawin, f.nama as warganegara, nama_ayah, nama_ibu, g.nama as golongan_darah, u.nama, status, h.nama AS hubungan
			FROM tweb_penduduk u
			LEFT JOIN tweb_keluarga k ON u.id_kk = k.id
			LEFT JOIN tweb_penduduk_agama a ON u.agama_id = a.id
			LEFT JOIN tweb_penduduk_pekerjaan j ON u.pekerjaan_id = j.id
			LEFT JOIN tweb_penduduk_pendidikan_kk d ON u.pendidikan_kk_id = d.id
			LEFT JOIN tweb_penduduk_warganegara f ON u.warganegara_id = f.id
			LEFT JOIN tweb_golongan_darah g ON u.golongan_darah_id = g.id
			LEFT JOIN tweb_penduduk_kawin w ON u.status_kawin = w.id
			LEFT JOIN tweb_penduduk_sex x ON u.sex = x.id
			LEFT JOIN tweb_rtm_hubungan h ON u.rtm_level = h.id
			LEFT JOIN tweb_rtm r ON u.id_rtm = r.no_kk
			LEFT JOIN tweb_wil_clusterdesa b ON u.id_cluster = b.id
			WHERE r.id = ? ORDER BY rtm_level";

		$query = $this->db->query($sql, array($id));
		$data = $query->result_array();

		//Formating Output
		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $i + 1;
			$data[$i]['alamat'] = "Dusun ".$data[$i]['dusun'].", RW ".$data[$i]['rw'].", RT ".$data[$i]['rt'];
			$data[$i]['tanggallahir'] = tgl_indo($data[$i]['tanggallahir']);
		}
		return $data;
	}

	public function get_kepala_rtm($id, $is_no_kk=false)
	{
		if (empty($id)) return;

		$kolom_id = ($is_no_kk) ? "no_kk" : "id";
		$this->load->model('penduduk_model');
		$sql = "SELECT u.id, u.nik, u.nama, r.no_kk, x.nama AS sex, u.tempatlahir, u.tanggallahir, (SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(`tanggallahir`)), '%Y') + 0 FROM tweb_penduduk WHERE id = u.id) AS umur, d.nama as pendidikan, f.nama as warganegara, a.nama as agama, wil.rt, wil.rw, wil.dusun
			FROM tweb_rtm r
			LEFT JOIN tweb_penduduk u ON u.id = r.nik_kepala
			LEFT JOIN tweb_penduduk_sex x ON u.sex = x.id
			LEFT JOIN tweb_penduduk_pendidikan_kk d ON u.pendidikan_kk_id = d.id
			LEFT JOIN tweb_penduduk_warganegara f ON u.warganegara_id = f.id
			LEFT JOIN tweb_penduduk_agama a ON u.agama_id = a.id
			LEFT JOIN tweb_wil_clusterdesa wil ON wil.id = u.id_cluster
			WHERE r.$kolom_id = $id LIMIT 1";
		$query = $this->db->query($sql);
		$data = $query->row_array();
		$data['alamat_wilayah'] = $this->penduduk_model->get_alamat_wilayah($data['id']);
		return $data;
	}

	public function list_hubungan()
	{
		$sql = "SELECT id, nama as hubungan FROM tweb_rtm_hubungan WHERE 1";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function update_nokk($id)
	{
		$data = $_POST;
		if ($data['no_kk'])
		{
			$ada_nokk = $this->db->select('id')
				->where('no_kk', $data['no_kk'])
				->get('tweb_rtm')->row()->id;
			if ($ada_nokk and $ada_nokk != $id)
			{
				$_SESSION['success'] = -1;
				$_SESSION['error_msg'] = 'Nomor RTM itu sudah ada';
				return;
			}
			$rtm = $this->db->where('id', $id)->get('tweb_rtm')->row();
			$this->db->where('id_rtm', $rtm->no_kk)
				->update('tweb_penduduk', array('id_rtm' => $data['no_kk']));
		}
		$outp = $this->db->where("id", $id)->update("tweb_rtm", $data);

		status_sukses($outp); //Tampilkan Pesan
	}

	/*
	 * -----------------------------------------------------------------------------------------------------
	 * Susun ulang afa28
	 */

	public function autocomplete()
	{
		$this->db
			->select('t.nama')
			->from('tweb_rtm u')
			->join('tweb_penduduk t', 'u.nik_kepala = t.id', LEFT);

		$data = $this->db->get()->result_array();

		return autocomplete_data_ke_str($data);
	}

	public function paging($p)
	{
		$this->db->select('COUNT(u.id) AS jml');
		$this->list_data_sql();

		$row = $this->db->get()->row_array();
		$jml_data = $row['jml'];

		$this->load->library('paging');
		$cfg['page'] = $p;
		$cfg['per_page'] = $this->session->per_page;
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);

		return $this->paging;
	}

	// $limit = 0 mengambil semua
	public function list_data($o = 0, $offset = 0, $limit = 0)
	{
		$this->db->select('u.id, u.no_kk, t.foto, t.nama AS kepala_kk, t.nik, k.alamat, (SELECT COUNT(id) FROM tweb_penduduk WHERE id_rtm = u.no_kk ) AS jumlah_anggota, c.dusun, c.rw, c.rt, u.tgl_daftar');

		$this->list_data_sql();

		switch ($o)
		{
			case 1: $this->db->order_by('u.no_kk'); break;
			case 2: $this->db->order_by('u.no_kk', DESC); break;
			case 3: $this->db->order_by('t.nama'); break;
			case 4: $this->db->order_by('t.nama', DESC); break;
			case 5: $this->db->order_by('u.tgl_daftar'); break;
			case 6: $this->db->order_by('u.tgl_daftar', DESC); break;
			default: ' ';
		}

		if ($limit > 0 ) $this->db->limit($limit, $offset);

		$data = $this->db->get()->result_array();

		return $data;
	}

	private function list_data_sql()
	{
		$this->db
			->from('tweb_rtm u')
			->join('tweb_penduduk t', 'u.no_kk = t.id_rtm AND t.rtm_level = 1', 'LEFT')
			->join('tweb_keluarga k', 't.id_kk = k.id', 'LEFT')
			->join('tweb_wil_clusterdesa c', 't.id_cluster = c.id', 'LEFT');

		$this->search_sql();

		//['kelas', 'kelas_sosial']

		$list_kode = [['dusun', 'c.dusun'], ['rw', 'c.rw'], ['rt', 'c.rt']];
		foreach ($list_kode as $list)
		{
			$this->filter_sql($list[0], $list[1]);
		}
	}

	private function search_sql()
	{
		$cari = $this->session->cari;
		if ($cari)
		{
			$cari = $this->db->escape_like_str($cari);
			$this->db->like('t.nama', $cari);
		}
	}

	private function filter_sql($session, $field)
	{
		$value = $this->session->$session;

		if (isset($value))
		{
				$this->db->where($field, $value);
		}
	}


}
?>
