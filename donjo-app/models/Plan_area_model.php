<?php
/*
 * Berkas default dari halaman web utk publik
 *
 * Copyright 2013
 * Rizka Himawan <himawan.rizka@gmail.com>
 * Muhammad Khollilurrohman <adsakle1@gmail.com>
 * Asep Nur Ajiyati <asepnurajiyati@gmail.com>
 *
 * SID adalah software tak berbayar (Opensource) yang boleh digunakan oleh siapa saja selama bukan untuk kepentingan profit atau komersial.
 * Lisensi ini mengizinkan setiap orang untuk menggubah, memperbaiki, dan membuat ciptaan turunan bukan untuk kepentingan komersial
 * selama mereka mencantumkan asal pembuat kepada Anda dan melisensikan ciptaan turunan dengan syarat yang serupa dengan ciptaan asli.
 * Untuk mendapatkan SID RESMI, Anda diharuskan mengirimkan surat permohonan ataupun izin SID terlebih dahulu,
 * aplikasi ini akan tetap bersifat opensource dan anda tidak dikenai biaya.
 * Bagaimana mendapatkan izin SID, ikuti link dibawah ini:
 * http://lumbungkomunitas.net/bergabung/pendaftaran/daftar-onpolygon/
 * Creative Commons Attribution-NonCommercial 3.0 Unported License
 * SID Opensource TIDAK BOLEH digunakan dengan tujuan profit atau segala usaha  yang bertujuan untuk mencari keuntungan.
 * Pelanggaran HaKI (Hak Kekayaan Intelektual) merupakan tindakan  yang menghancurkan dan menghambat karya bangsa.
 */
?><?php class Plan_area_model extends MY_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function autocomplete()
	{
		return $this->autocomplete_str('nama', 'area');
	}

	private function search_sql()
	{
		if (isset($_SESSION['cari']))
		{
			$cari = $_SESSION['cari'];
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' .$kw. '%';
			$search_sql = " AND l.nama LIKE '$kw'";
			return $search_sql;
		}
	}

	private function filter_sql()
	{
		if (isset($_SESSION['filter']))
		{
			$kf = $_SESSION['filter'];
			$filter_sql = " AND l.enabled = $kf";
			return $filter_sql;
		}
	}

	private function polygon_sql()
	{
		if (isset($_SESSION['polygon']))
		{
			$kf = $_SESSION['polygon'];
			$polygon_sql = " AND p.id = $kf";
			return $polygon_sql;
		}
	}

	private function subpolygon_sql()
	{
		if (isset($_SESSION['subpolygon']))
		{
			$kf = $_SESSION['subpolygon'];
			$subpolygon_sql = " AND m.id = $kf";
			return $subpolygon_sql;
		}
	}

	public function paging($p=1, $o=0)
	{
		$sql = "SELECT COUNT(l.id) AS id FROM area l LEFT JOIN polygon p ON l.ref_polygon = p.id LEFT JOIN polygon m ON p.parrent = m.id WHERE 1 ";
		$sql .= $this->search_sql();
		$sql .= $this->filter_sql();
		$sql .= $this->polygon_sql();
		$sql .= $this->subpolygon_sql();
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

	public function list_data($o=0,$offset=0, $limit=500)
	{
		switch ($o)
		{
			case 1: $order_sql = ' ORDER BY nama'; break;
			case 2: $order_sql = ' ORDER BY nama DESC'; break;
			case 3: $order_sql = ' ORDER BY enabled'; break;
			case 4: $order_sql = ' ORDER BY enabled DESC'; break;
			default:$order_sql = ' ORDER BY id';
		}

		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;

		$sql = "SELECT l.*,p.nama AS kategori,m.nama AS jenis,p.simbol AS simbol,p.color AS color
			FROM area l
			LEFT JOIN polygon p ON l.ref_polygon = p.id
			LEFT JOIN polygon m ON p.parrent = m.id
			WHERE 1";

		$sql .= $this->search_sql();
		$sql .= $this->filter_sql();
		$sql .= $this->polygon_sql();
		$sql .= $this->subpolygon_sql();
		$sql .= $order_sql;
		$sql .= $paging_sql;

		$query = $this->db->query($sql);
		$data = $query->result_array();

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
		  $data = $_POST;
		  $area_file = $_FILES['foto']['tmp_name'];
		  $tipe_file = $_FILES['foto']['type'];
		  $nama_file = $_FILES['foto']['name'];
		  $nama_file = str_replace(' ', '-', $nama_file); 	 // normalkan nama file
		  if (!empty($area_file))
		  {
			if ($tipe_file == "image/jpg" OR $tipe_file == "image/jpeg")
			{
				Uploadarea($nama_file);
				$data['foto'] = $nama_file;
				$outp = $this->db->insert('area', $data);
			}
		}
		else
		{
			unset($data['foto']);
			$outp = $this->db->insert('area', $data);
		}

		status_sukses($outp); //Tampilkan Pesan

	}

	public function update($id=0)
	{
		  $data = $_POST;
		  $area_file = $_FILES['foto']['tmp_name'];
		  $tipe_file = $_FILES['foto']['type'];
		  $nama_file = $_FILES['foto']['name'];
		  $nama_file = str_replace(' ', '-', $nama_file); 	 // normalkan nama file
		  if (!empty($area_file))
		  {
			if ($tipe_file == "image/jpg" OR $tipe_file == "image/jpeg")
			{
				Uploadarea($nama_file);
				$data['foto'] = $nama_file;
				$this->db->where('id', $id);
				$outp = $this->db->update('area', $data);
			}
		}
		else
		{
			unset($data['foto']);
			$this->db->where('id', $id);
			$outp = $this->db->update('area', $data);
		}
		status_sukses($outp); //Tampilkan Pesan
  }

	public function delete($id='', $semua=false)
	{
		if (!$semua) $this->session->success = 1;

		$outp = $this->db->where('id', $id)->delete('area');

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

	public function list_polygon()
	{
		$sql = "SELECT * FROM polygon WHERE tipe = 2 ";

		if (isset($_SESSION['subpolygon']))
		{
			$kf = $_SESSION['subpolygon'];
			$sql .= " AND parrent = $kf";
		}

		$query = $this->db->query($sql);
		$data = $query->result_array();
		return $data;
	}

	public function list_subpolygon()
	{
		$sql = "SELECT * FROM polygon WHERE tipe = 0 ";

		if (isset($_SESSION['polygon']))
		{
			$sqlx = "SELECT * FROM polygon WHERE id = ?";
			$query = $this->db->query($sqlx,$_SESSION['polygon']);
			$temp = $query->row_array();
			$kf = $temp['parrent'];
		}

		$query = $this->db->query($sql);
		$data = $query->result_array();
		return $data;
	}

	public function area_lock($id='', $val=0)
	{
		$sql = "UPDATE area SET enabled=? WHERE id = ?";
		$outp = $this->db->query($sql, array($val, $id));

		status_sukses($outp); //Tampilkan Pesan
	}

	public function get_area($id=0)
	{
		$sql = "SELECT * FROM area WHERE id = ?";
		$query = $this->db->query($sql, $id);
		$data = $query->row_array();
		return $data;
	}

	public function update_position($id=0)
	{
		$data = $_POST;
		$this->db->where('id', $id);
		$outp = $this->db->update('area', $data);

		status_sukses($outp); //Tampilkan Pesan
	}

	public function list_dusun()
	{
		$sql = "SELECT * FROM tweb_wil_clusterdesa WHERE rt = '0' AND rw = '0' ";
		$query = $this->db->query($sql);
		$data = $query->result_array();
		return $data;
	}
}
?>
