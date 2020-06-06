<?php
class Kelompok_model extends MY_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function autocomplete()
	{
		return $this->autocomplete_str('nama', 'kelompok');
	}

	private function search_sql()
	{
		if (isset($_SESSION['cari']))
		{
			$cari = $_SESSION['cari'];
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' .$kw. '%';
			$search_sql= " AND (u.nama LIKE '$kw' OR u.nama LIKE '$kw')";
			return $search_sql;
		}
	}

	private function filter_sql()
	{
		if (isset($_SESSION['filter']))
		{
			$kf = $_SESSION['filter'];
			$filter_sql= " AND u.id_master = $kf";
			return $filter_sql;
		}
	}

	public function paging($p=1, $o=0)
	{
		$sql = "SELECT COUNT(*) AS jml ";
		$sql .= $this->list_data_sql();

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
		$sql = "FROM kelompok u
			LEFT JOIN kelompok_master s ON u.id_master = s.id
			LEFT JOIN tweb_penduduk c ON u.id_ketua = c.id
			WHERE 1 ";
		$sql .= $this->search_sql();
		$sql .= $this->filter_sql();
		return $sql;
	}

	public function list_data($o=0, $offset=0, $limit=500)
	{
		switch ($o)
		{
			case 1: $order_sql = ' ORDER BY u.nama'; break;
			case 2: $order_sql = ' ORDER BY u.nama DESC'; break;
			case 3: $order_sql = ' ORDER BY u.nama'; break;
			case 4: $order_sql = ' ORDER BY u.nama DESC'; break;
			case 5: $order_sql = ' ORDER BY master'; break;
			case 6: $order_sql = ' ORDER BY master DESC'; break;
			default:$order_sql = ' ORDER BY u.nama';
		}

		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;

		$select_sql = "SELECT u.*, s.kelompok AS master, c.nama AS ketua, (SELECT COUNT(id) FROM kelompok_anggota WHERE id_kelompok = u.id) AS jml_anggota ";

		$sql = $select_sql . $this->list_data_sql();
		$sql .= $order_sql;
		$sql .= $paging_sql;

		$query = $this->db->query($sql);
		$data = $query->result_array();

		$j = $offset;
		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no']=$j+1;
			$j++;
		}
		return $data;
	}

	public function insert()
	{
		$data = $_POST;
		$datax = array();

		$outpa = $this->db->insert('kelompok', $data);
		$insert_id = $this->db->insert_id();

		$datax['id_kelompok'] = $insert_id;
		$datax['id_penduduk'] = $data['id_ketua'];
		$outpb = $this->db->insert('kelompok_anggota', $datax);

		if ($outpa && $outpb) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function insert_a($id=0)
	{
		$data = $_POST;
		$data['id_kelompok'] = $id;

		$sql = "SELECT id FROM kelompok_anggota WHERE id_kelompok = ? AND id_penduduk = ?";
		$query = $this->db->query($sql,array($data['id_kelompok'],$data['id_penduduk']));
		$kel = $query->row_array();

		if (!$kel)
		{
			$outp = $this->db->insert('kelompok_anggota', $data);
		}

		status_sukses($outp); //Tampilkan Pesan
	}

	public function update($id=0)
	{
		$data = $_POST;
		if ($data['id_ketua'] == "")
		unset($data['id_ketua']);

		$this->db->where('id', $id);
		$outp = $this->db->update('kelompok', $data);

		status_sukses($outp); //Tampilkan Pesan
	}

	public function update_a($id=0, $id_a=0)
	{
		$data = $_POST;

		$this->db->where('id_kelompok', $id);
		$this->db->where('id_penduduk', $id_a);
		$outp = $this->db->update('kelompok_anggota', $data);

		status_sukses($outp); //Tampilkan Pesan
	}

	public function delete($id='', $semua=false)
	{
		if (!$semua) $this->session->success = 1;

		$outp = $this->db->where('id', $id)->delete('kelompok');

		status_sukses($outp, $gagal_saja=true); //Tampilkan Pesan
	}

	public function delete_a($id='', $semua=false)
	{
		if (!$semua) $this->session->success = 1;

		$outp = $this->db->where('id', $id)->delete('kelompok_anggota');

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

	public function get_kelompok($id=0)
	{
		$sql = "SELECT * FROM kelompok WHERE id = ?";
		$query = $this->db->query($sql, $id);
		$data = $query->row_array();
		return $data;
	}

	public function get_ketua_kelompok($id)
	{
		$this->load->model('penduduk_model');
		$sql = "SELECT u.id,u.nik,u.nama,k.id as id_kelompok,k.nama as nama_kelompok,u.tempatlahir,u.tanggallahir,(SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0 FROM tweb_penduduk WHERE id = u.id) AS umur,d.nama as pendidikan,f.nama as warganegara,a.nama as agama,
			wil.rt, wil.rw, wil.dusun
			FROM kelompok k
			LEFT JOIN tweb_penduduk u ON u.id= k.id_ketua
			LEFT JOIN tweb_penduduk_pendidikan_kk d ON u.pendidikan_kk_id = d.id
			LEFT JOIN tweb_penduduk_warganegara f ON u.warganegara_id = f.id
			LEFT JOIN tweb_penduduk_agama a ON u.agama_id = a.id
			LEFT JOIN tweb_wil_clusterdesa wil ON wil.id = u.id_cluster
			WHERE k.id = $id LIMIT 1";
		$query = $this->db->query($sql);
		$data = $query->row_array();
		$data['alamat_wilayah'] = $this->penduduk_model->get_alamat_wilayah($data['id']);
		return $data;
	}

	public function get_anggota($id=0, $id_a=0)
	{
		$sql = "SELECT * FROM kelompok_anggota WHERE id_kelompok = ? AND id_penduduk = ?";
		$query = $this->db->query($sql,array($id, $id_a));
		$data = $query->row_array();
		return $data;
	}

	public function list_master()
	{
		$sql = "SELECT * FROM kelompok_master";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function list_penduduk()
	{
		$sql = "SELECT id,nik,nama FROM tweb_penduduk WHERE status_dasar = 1";
		$query = $this->db->query($sql);
		$data = $query->result_array();

		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['alamat']="Alamat :".$data[$i]['nama'];
		}
		return $data;
	}

	public function list_anggota($id=0)
	{
		$sql = "SELECT u.*,p.nik,p.nama,p.sex,(SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(tanggallahir)), '%Y')+0 FROM tweb_penduduk WHERE id = p.id) AS umur,a.dusun,a.rw,a.rt FROM kelompok_anggota u LEFT JOIN tweb_penduduk p ON u.id_penduduk = p.id LEFT JOIN tweb_wil_clusterdesa a ON p.id_cluster = a.id WHERE id_kelompok = ?";
		$query = $this->db->query($sql, $id);
		$data=$query->result_array();

		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $i + 1;
			$data[$i]['alamat'] = "Dusun ".$data[$i]['dusun']." RW".$data[$i]['rw']." RT".$data[$i]['rt'];
		}
		return $data;
	}
}
?>