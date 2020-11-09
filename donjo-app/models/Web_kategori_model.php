<?php

class Web_kategori_model extends CI_Model {

	private $urut_model;

	public function __construct()
	{
		parent::__construct();
	  require_once APPPATH.'/models/Urut_model.php';
		$this->urut_model = new Urut_Model('kategori');
	}

	public function autocomplete()
	{
		$data = $this->db->distinct()->
			select('kategori')->
			where('parrent', 0)->
			order_by('kategori')->
			get('kategori')->result_array();
		return autocomplete_data_ke_str($data);
	}

	private function search_sql()
	{
		if (isset($_SESSION['cari']))
		{
		$cari = $_SESSION['cari'];
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' .$kw. '%';
			$search_sql = " AND (kategori LIKE '$kw')";
			return $search_sql;
		}
	}

	private function filter_sql()
	{
		if (isset($_SESSION['filter']))
		{
			$kf = $_SESSION['filter'];
			$filter_sql = " AND enabled = $kf";
			return $filter_sql;
		}
	}

	public function paging($p=1, $o=0)
	{
		$sql = "SELECT COUNT(*) AS jml " . $this->list_data_sql();
		$query = $this->db->query($sql);
		$row = $query->row_array();
		$jml_data = $row['jml'];

		$this->load->library('paging');
		$cfg['page'] = $p;
		$cfg['per_page'] = $_SESSION['per_page'];
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);

		return $this->paging;
	}

	private function list_data_sql()
	{
		$sql = " FROM kategori k WHERE parrent = 0";
		$sql .= $this->search_sql();
		$sql .= $this->filter_sql();
		return $sql;
	}

	public function list_data($o=0, $offset=0, $limit=500)
	{
		switch ($o)
		{
			case 1: $order_sql = ' ORDER BY kategori'; break;
			case 2: $order_sql = ' ORDER BY kategori DESC'; break;
			case 3: $order_sql = ' ORDER BY enabled'; break;
			case 4: $order_sql = ' ORDER BY enabled DESC'; break;
			default:$order_sql = ' ORDER BY urut';
		}

		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;
		$sql = "SELECT k.*, k.kategori AS kategori " . $this->list_data_sql();
		$sql .= $order_sql;
		$sql .= $paging_sql;

		$query = $this->db->query($sql);
		$data =$query->result_array();

		$j = $offset;
		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $j + 1;

			if ($data[$i]['enabled'] == 1)
				$data[$i]['aktif'] = "Ya";
			else
				$data[$i]['aktif'] = "Tidak";

			$j++;
		}
		return $data;
	}

	public function insert()
	{
		$this->session->unset_userdata('error_msg');
		$this->session->set_userdata('success', 1);
		$data = [];
		$data['kategori'] = $this->input->post('kategori');
		$this->sterilkan_kategori($data);
		if (!$this->cek_nama($data['kategori']))
			return;
		$data['enabled'] = 1;
		$data['urut'] = $this->urut_model->urut_max(array('parrent' => 0)) + 1;
		$data['slug'] = url_title($data['kategori'], 'dash', TRUE);
		$outp = $this->db->insert('kategori', $data);

		status_sukses($outp); //Tampilkan Pesan

	}

	private function sterilkan_kategori(&$data)
	{
		unset($data['kategori_lama']);
		$data['kategori'] = htmlentities($data['kategori']);
	}

	private function cek_nama($kategori)
	{
		$ada_nama = $this->db->where('kategori', $kategori)
			->get('kategori')->num_rows();
		if ($ada_nama)
		{
			$_SESSION['error_msg'].= " -> Nama kategori tidak boleh sama";
		  $_SESSION['success'] = -1;
		  return false;
		}
		return true;
	}

	public function update($id=0)
	{
		$this->session->unset_userdata('error_msg');
		$this->session->set_userdata('success', 1);
		$data = [];
		$data['kategori'] = $this->input->post('kategori');
		if ($data['kategori'] == $data['kategori_lama'])
		{
			return; // Tidak ada yg diubah
		}
		else
		{
			if (!$this->cek_nama($data['kategori']))
				return;
		}
		$this->sterilkan_kategori($data);
		$outp = $this->db->where('id', $id)
			->update('kategori', $data);

		status_sukses($outp); //Tampilkan Pesan
	}

	public function delete($id='', $semua=false)
	{
		if (!$semua) $this->session->success = 1;

		$outp = $this->db->where('id', $id)->delete('kategori');

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

	public function list_sub_kategori($kategori=1)
	{
		$sql = "SELECT * FROM kategori WHERE parrent = ? ORDER BY urut";

		$query = $this->db->query($sql, $kategori);
		$data = $query->result_array();

		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $i + 1;

			if($data[$i]['enabled'] == 1)
				$data[$i]['aktif'] = "Ya";
			else
				$data[$i]['aktif'] = "Tidak";
		}
		return $data;
	}

	public function list_link()
	{
		$sql = "SELECT a.*
			FROM artikel a
			INNER JOIN kategori k ON a.id_kategori = k.id
			WHERE tipe = '2'";

		$query = $this->db->query($sql);
		$data = $query->result_array();

		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $i + 1;
		}
		return $data;
	}

	public function list_kategori($o = "")
	{
		if (empty($o)) $urut = "urut";
		else $urut = $o;

		$data = $this->db
			->select('k.*')
			->where('enabled', 1)
			->order_by($urut)
			->get('kategori k')
			->result_array();

		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $i + 1;
			$data[$i]['judul'] = $data[$i]['kategori'];
		}
		return $data;
	}

	public function insert_sub_kategori($kategori=0)
	{
		$data = [];
		$data['kategori'] = $this->input->post('kategori');
		$this->sterilkan_kategori($data);
		$data['parrent'] = $kategori;
		$data['urut'] = $this->urut_model->urut_max(array('parrent' => $kategori)) + 1;
		$data['slug'] = url_title($data['kategori'], 'dash', TRUE);
		$data['enabled'] = 1;
		$outp = $this->db->insert('kategori', $data);

		status_sukses($outp); //Tampilkan Pesan
	}

	public function update_sub_kategori($id=0)
	{
		$data = [];
		$data['kategori'] = $this->input->post('kategori');
		$this->sterilkan_kategori($data);
		$this->db->where('id', $id);
		$outp = $this->db->update('kategori', $data);

		status_sukses($outp); //Tampilkan Pesan
	}

	public function delete_sub_kategori($id='', $semua=false)
	{
		if (!$semua) $this->session->success = 1;

		$outp = $this->db->where('id', $id)->delete('kategori');

		status_sukses($outp); //Tampilkan Pesan
	}

	public function delete_all_sub_kategori()
	{
		$this->session->success = 1;

		$id_cb = $_POST['id_cb'];
		foreach ($id_cb as $id)
		{
			$this->delete_sub_kategori($id, $semua=true);
		}
	}

	public function kategori_lock($id='', $val=0)
	{
		$sql = "UPDATE kategori SET enabled = ? WHERE id = ?";
		$outp = $this->db->query($sql, array($val, $id));

		status_sukses($outp); //Tampilkan Pesan
	}

	public function get_kategori($id=0)
	{
		$query = $this->db->where('id', $id)->or_where('slug', $id)->get('kategori');
		$data  = $query->row_array();
		return $data;
	}

	// $arah:
	//		1 - turun
	// 		2 - naik
	public function urut($id, $arah, $kategori='')
	{
  	$subset = !empty($kategori) ? array('parrent' => $kategori) : array('parrent' => 0);
  	$this->urut_model->urut($id, $arah, $subset);
	}

}
?>
