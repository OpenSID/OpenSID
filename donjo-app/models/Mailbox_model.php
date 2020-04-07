<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mailbox_model extends CI_Model {

	/**
	 * Gunakan model ini untuk memindahkan semua method terkait mailbox layanan mandiri.
	 * Dimana layanan mailbox memiliki perlakuan yang sepenuhnya berbeda dengan komentar web
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('referensi_model');
		
	}

	public function autocomplete()
	{
		$str = autocomplete_str('isi_pesan', 'kotak_pesan');
		return $str;
	}

	public function list_data($o=0, $offset=0, $limit=500, $kat=0)
	{
		// var o = pengurutan
		// var kat = pesan masuk / keluar
		$this->db->select('k.*, p.nik, p.nama');
		$this->list_data_sql($kat);

		$data = $this->db->limit($limit, $offset)->get()->result_array();

		$j = $offset;
		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $j + 1;
			if ($data[$i]['status'] == 1)
				$data[$i]['aktif'] = "Ya";
			else
				$data[$i]['aktif'] = "Tidak";
			$j++;
		}
		return $data;
	}

	private function list_data_sql($kat=0)
	{
		$this->db->from('kotak_pesan k')
			->join('tweb_penduduk p','p.id = k.id_pengirim', 'left');

		if ($kat != 0) {
			$this->db->where('tipe', $kat);
			$this->filter_nik_sql();
			$this->filter_status_sql();
		}
		$this->search_sql();
		$this->filter_status_sql();
	}

	private function filter_nik_sql()
	{
		if (isset($_SESSION['filter_nik']))
		{
			$kf = $_SESSION['filter_nik'];
			$this->db->where('p.nik', $kf);
		}
	}

	private function search_sql()
	{
		if (isset($_SESSION['cari']))
		{
			$cari = $_SESSION['cari'];
			$kw = $this->db->escape_like_str($cari);
			$this->db->like('isi_pesan', $kw)->or_like('subjek', $kw);
		}
	}

	private function filter_baca_sql()
	{
		if (isset($_SESSION['filter_baca']))
		{
			$kf = $_SESSION['filter_baca'];
			$this->db->where('k.baca', $kf);
		}
	}

	private function filter_status_sql()
	{
		$kf = $_SESSION['filter_status'] ?: 0;
		$this->db->where('k.status', $kf);
	}

	public function paging($p=1, $o=0, $kat=0)
	{
		$this->db->select('COUNT(*) AS jml');
		$this->list_data_sql($kat);
		$row = $this->db->get()->row_array();

		$this->load->library('paging');
		$cfg['page'] = $p;
		$cfg['per_page'] = $_SESSION['per_page'];
		$cfg['num_rows'] = $jml;
		$this->paging->init($cfg);

		return $this->paging;
	}


	public function baca($id, $baca)
	{
		$outp = $this->db->where('id', $id)
			->update('kotak_pesan', array('baca' => $baca));
		
		status_sukses($outp); //Tampilkan Pesan
	}

	public function archive($id='', $semua=false)
	{
		if (!$semua) $this->session->success = 1;
		
		$archive = array(
			'status' => 1,
			'updated_at' => date('Y-m-d H:i:s')
		);
		$outp = $this->db->where('id', $id)->update('kotak_pesan', $archive);

		status_sukses($outp, $gagal_saja=true); //Tampilkan Pesan
	}

	public function archive_all()
	{
		$this->session->success = 1;

		$id_cb = $_POST['id_cb'];
		foreach ($id_cb as $id)
		{
			$this->archive($id, $semua=true);
		}
	}

	

	public function insert($data)
	{
		$data = $data;
		$data['id'] = 775;
		$data['created_at'] = date('Y-m-d H:i:s');
		$data['updated_at'] = date('Y-m-d H:i:s');
		$outp = $this->db->insert('komentar', $data);
		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function list_menu()
	{
		return $this->referensi_model->list_kode_array(KATEGORI_MAILBOX);
	}

	public function get_kat_nama($kat)
	{
		$sub_menu = $this->list_menu();	
		$data = $sub_menu[$kat];
		return $data;
	}

	/**
	 * Tipe 1: Inbox untuk admin, Outbox untuk pengguna layanan mandiri
	 * Tipe 2: Outbox untuk admin, Inbox untuk pengguna layanan mandiri
	 */

	public function get_inbox_user($nik)
	{
		$outp = $this->db
			->where('nik', $nik)
			->where('tipe', 2)
			->where('id', 775)
			->from('komentar')
			->order_by('id', 'DESC')
			->get()
			->result_array();
		$j = 1;
		for ($i=0; $i < count($outp); $i++) 
		{ 
			$outp[$i]['no'] = $j++;
		}
		return $outp;
	}

	public function get_outbox_user($nik)
	{
		$outp = $this->db
			->where('nik', $nik)
			->where('tipe', 1)
			->where('id', 775)
			->from('komentar')
			->order_by('id','DESC')
			->get()
			->result_array();
		$j = 1;
		for ($i=0; $i < count($outp); $i++) 
		{ 
			$outp[$i]['no'] = $j++;
		}
		return $outp;
	}

	public function get_pesan($nik, $id)
	{
		return $this->db
			->where('nik', $nik)
			->where('id', $id)
			->where('id', 775)
			->from('komentar')
			->get()
			->row_array();
	}

	public function ubah_status_pesan($nik, $id, $status)
	{
		return $this->db
			->where('nik', $nik)
			->where('id', $id)
			->where('tipe', 2)
			->where('id', 775)
			->update('komentar', array('status' => $status));
	}
}
?>