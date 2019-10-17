<?php class Wilayah_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function autocomplete()
	{
		$str = autocomplete_str('dusun', 'tweb_wil_clusterdesa');
		return $str;
	}

	private function search_sql()
	{
		if (isset($_SESSION['cari']))
		{
			$cari = $this->db->escape_like_str($_SESSION['cari']);
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' .$kw. '%';
			$search_sql= " AND u.dusun LIKE '$kw'";
			return $search_sql;
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
		$sql = " FROM tweb_wil_clusterdesa u
			LEFT JOIN tweb_penduduk a ON u.id_kepala = a.id
			WHERE u.rt = '0' AND u.rw = '0'  ";
		$sql .= $this->search_sql();
		return $sql;
	}

	/*
		Struktur tweb_wil_clusterdesa:
		- baris dengan kolom rt = '0' dan rw = '0' menunjukkan dusun
		- baris dengan kolom rt = '-' dan rw <> '-' menunjukkan rw
		- baris dengan kolom rt <> '0' dan rt <> '0' menunjukkan rt

		Di tabel tweb_penduduk  dan tweb_keluarga, kolom id_cluster adalah id untuk
		baris rt.
	*/
	public function list_data($o=0, $offset=0, $limit=500)
	{
		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;

		$select_sql = "SELECT u.*, a.nama AS nama_kadus, a.nik AS nik_kadus,
		(SELECT COUNT(rw.id) FROM tweb_wil_clusterdesa rw WHERE dusun = u.dusun AND rw <> '-' AND rt = '-') AS jumlah_rw,
		(SELECT COUNT(v.id) FROM tweb_wil_clusterdesa v WHERE dusun = u.dusun AND v.rt <> '0' AND v.rt <> '-') AS jumlah_rt,
		(SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = u.dusun) and status_dasar=1) AS jumlah_warga,
		(SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = u.dusun) AND p.sex = 1 and status_dasar=1) AS jumlah_warga_l,
		(SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = u.dusun) AND p.sex = 2 and status_dasar=1) AS jumlah_warga_p,
		(SELECT COUNT(p.id) FROM tweb_keluarga k inner join tweb_penduduk p ON k.nik_kepala = p.id  WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = u.dusun) AND p.kk_level = 1 and status_dasar=1) AS jumlah_kk ";
		$sql = $select_sql . $this->list_data_sql();
		$sql .= $paging_sql;

		$query = $this->db->query($sql);
		$data = $query->result_array();

		//Formating Output
		$j = $offset;
		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $j + 1;
			$j++;
		}
		return $data;
	}

	private function bersihkan_data($data)
	{
		if (empty((int)$data['id_kepala']))
			unset($data['id_kepala']);
		$data['dusun'] = strip_tags($data['dusun']);
		return $data;
	}

	public function insert()
	{
		$data = $this->bersihkan_data($_POST);
		$data['dusun'] = $_POST['dusun'];
		$this->db->insert('tweb_wil_clusterdesa', $data);

		$rw = $data;
		$rw['rw'] = "-";
		$this->db->insert('tweb_wil_clusterdesa', $rw);

		$rt = $rw;
		$rt['rt'] = "-";
		$outp = $this->db->insert('tweb_wil_clusterdesa', $rt);

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function update($id='')
	{
		if (empty($_POST['id_kepala']) || !is_numeric($_POST['id_kepala']))
			UNSET($_POST['id_kepala']);

		$data = $_POST;
		$data['dusun']=$_POST['dusun'];
		$temp = $this->wilayah_model->cluster_by_id($id);
		$this->db->where('dusun',$temp['dusun']);
		$this->db->where('rw', '0');
		$this->db->where('rt', '0');
		$outp1 = $this->db->update('tweb_wil_clusterdesa', $data);

		// Ubah nama dusun di semua baris rw/rt untuk dusun ini
		$outp2 = $this->db->where('dusun', $temp['dusun'])->
			update('tweb_wil_clusterdesa', array('dusun' => $data['dusun']));

		if( $outp1 AND $outp2) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function delete($id='')
	{
		// Perlu hapus berdasarkan nama, supaya baris RW dan RT juga terhapus
    $temp = $this->cluster_by_id($id);
    $dusun = $temp['dusun'];

    $sql = "DELETE FROM tweb_wil_clusterdesa WHERE dusun = '$dusun'";
    $outp = $this->db->query($sql);

    if ($outp) $_SESSION['success'] = 1;
    else $_SESSION['success'] = -1;
  }

	//Bagian RW
	public function list_data_rw($id='')
	{
		$temp = $this->cluster_by_id($id);
		$dusun = $temp['dusun'];

		$sql = "SELECT u.*, a.nama AS nama_ketua, a.nik AS nik_ketua,
		(SELECT COUNT(rt.id) FROM tweb_wil_clusterdesa rt WHERE dusun = u.dusun AND rw = u.rw AND rt <> '-' AND rt <> '0' ) AS jumlah_rt,
		(SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = '$dusun' AND rw = u.rw) AND p.status_dasar=1) AS jumlah_warga,
		(SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = '$dusun' AND rw = u.rw) AND p.sex = 1 AND p.status_dasar=1) AS jumlah_warga_l,
		(SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = '$dusun' AND rw = u.rw) AND p.sex = 2 AND p.status_dasar=1) AS jumlah_warga_p,
		(SELECT COUNT(p.id) FROM tweb_keluarga k inner join tweb_penduduk p ON k.nik_kepala=p.id  WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = '$dusun' AND rw = u.rw) AND p.kk_level = 1 AND p.status_dasar=1) AS jumlah_kk
		FROM tweb_wil_clusterdesa u LEFT JOIN tweb_penduduk a ON u.id_kepala = a.id WHERE u.rt = '0' AND u.rw <> '0' AND u.dusun = '$dusun'";
		$query = $this->db->query($sql);
		$data = $query->result_array();

		//Formating Output
		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $i + 1;
		}
		return $data;
	}

	public function insert_rw($dusun='')
	{

                if (empty($_POST['id_kepala']) || !is_numeric($_POST['id_kepala']))
		  UNSET($_POST['id_kepala']);

		$data = $_POST;
		$temp = $this->cluster_by_id($dusun);
		$data['dusun']= $temp['dusun'];
		$outp = $this->db->insert('tweb_wil_clusterdesa', $data);

		$rt = $data;
		$rt['rt'] = "-";
		$outp = $this->db->insert('tweb_wil_clusterdesa', $rt);

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function update_rw($dusun='', $rw='')
	{
		if (empty($_POST['id_kepala']) || !is_numeric($_POST['id_kepala']))
		  UNSET($_POST['id_kepala']);

		$data = $_POST;

		$temp = $this->wilayah_model->cluster_by_id($dusun);
		// print_r($rw);exit;
		$this->db->where('dusun', $temp['dusun']);
		$this->db->where('rw', $rw);
                $this->db->where('rt', '0');//rw pasti data rt 0
		$outp = $this->db->update('tweb_wil_clusterdesa', $data);

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function delete_rw($id)
	{
		$temp = $this->cluster_by_id($id);
		$rw = $temp['rw'];
		$dusun = $temp['dusun'];

		$sql = "DELETE FROM tweb_wil_clusterdesa WHERE rw = '$rw' and dusun = '$dusun'";
		$outp = $this->db->query($sql, array($id));

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	//Bagian RT
	public function list_data_rt($dusun='', $rw='')
	{
		$sql = "SELECT u.*, a.nama AS nama_ketua, a.nik AS nik_ketua,
		(SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = '$dusun' AND rw = '$rw' AND rt = u.rt) AND p.status_dasar=1) AS jumlah_warga,
		(SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = '$dusun' AND rw = '$rw' AND rt = u.rt) AND p.sex = 1 AND p.status_dasar=1) AS jumlah_warga_l,(
		SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = '$dusun' AND rw = '$rw' AND rt = u.rt) AND p.sex = 2 AND p.status_dasar=1) AS jumlah_warga_p,
		(SELECT COUNT(p.id) FROM tweb_keluarga k inner join tweb_penduduk p ON k.nik_kepala=p.id  WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = '$dusun' AND rw = '$rw' AND rt = u.rt) AND p.kk_level = 1) AS jumlah_kk
		FROM tweb_wil_clusterdesa u LEFT JOIN tweb_penduduk a ON u.id_kepala = a.id WHERE u.rt <> '0' AND u.rw = '$rw' AND u.dusun = '$dusun' AND u.rt <> '-'";

		$query = $this->db->query($sql);
		$data=$query->result_array();

		//Formating Output
		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $i + 1;
		}
		return $data;
	}

	public function insert_rt($dusun='', $rw='')
	{
		if (empty($_POST['id_kepala']) || !is_numeric($_POST['id_kepala']))
			UNSET($_POST['id_kepala']);

                $data = $_POST;
		$temp = $this->cluster_by_id($dusun);
		$data['dusun']= $temp['dusun'];
		$data['rw'] = $rw;
		$outp = $this->db->insert('tweb_wil_clusterdesa', $data);

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function update_rt($id=0)
	{
		//Untuk mengakali update Nama RT saja tidak dengan kepala, sehingga ambil kepala sebelumnya
		if (empty($_POST['id_kepala']) || !is_numeric($_POST['id_kepala']))
			UNSET($_POST['id_kepala']);

		$data = $_POST;

		$this->db->where('id', $id);
		$outp = $this->db->update('tweb_wil_clusterdesa', $data);

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

        public function delete_rt($id=0)
	{
		$sql = "DELETE FROM tweb_wil_clusterdesa WHERE id = ?";
		$outp = $this->db->query($sql, $id);

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	
	public function list_penduduk($ex_id=0)
	{
		$sql = "SELECT p.id, p.nik, p.nama, c.dusun
			FROM tweb_penduduk p
			LEFT JOIN tweb_wil_clusterdesa c ON p.id_cluster = c.id
			WHERE p.status = 1";
		if ($ex_id)
			$sql .= ' AND p.id NOT IN(?)';
		$query = $this->db->query($sql, $ex_id);
		$data = $query->result_array();

		//Formating Output
		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['alamat'] = "Alamat :".$data[$i]['nama'];
		}
		return $data;
	}

	public function list_penduduk_ex($id=0)
	{
		return $this->list_penduduk($id);
	}

	public function list_dusun_rt($dusun='')
	{
		$sql = "SELECT * FROM tweb_clusterdesa WHERE dusun = ? AND rt <> '' ";
		$query = $this->db->query($sql, $id);
		$data = $query->row_array();
		return $data;
	}

	public function get_penduduk($id=0)
	{
		$sql = "SELECT id,nik,nama FROM tweb_penduduk WHERE id = ?";
		$query = $this->db->query($sql, $id);
		$data = $query->row_array();
		return $data;
	}

	public function cluster_by_id($id='')
	{
		$sql = "SELECT w.*, c.id as id_dusun
			FROM tweb_wil_clusterdesa w
			LEFT JOIN tweb_wil_clusterdesa c ON w.dusun = c.dusun AND c.rw = 0 AND c.rt = 0
			WHERE w.id = ?";
		$query = $this->db->query($sql, $id);
		return $query->row_array();
	}

	public function list_dusun()
	{
		$data = $this->db->
			where('rt', '0')->
			where('rw', '0')->
			get('tweb_wil_clusterdesa')->
			result_array();
		return $data;
	}

	public function list_rw($dusun='')
	{
		$data = $this->db->
			where('rt', '0')->
			where('dusun', urldecode($dusun))->
			where('rw <>', '0')->
			order_by('rw')->
			get('tweb_wil_clusterdesa')->
			result_array();
		return $data;
	}

        public function get_rw($dusun='', $rw='')
	{
		$sql = "SELECT * FROM tweb_wil_clusterdesa WHERE dusun = ? AND rw = ? AND rt = '0'";
		$query = $this->db->query($sql, array($dusun, $rw));
		return $query->row_array();
	}

	public function list_rt($dusun='', $rw='')
	{
		$data = $this->db->
			where('rt <>', '0')->
			where('dusun', urldecode($dusun))->
			where('rw', $rw)->
			order_by('rt')->
			get('tweb_wil_clusterdesa')->
			result_array();
		return $data;
	}

	public function get_rt($dusun='', $rw='', $rt='')
	{
		$sql = "SELECT * FROM tweb_wil_clusterdesa WHERE dusun = ? AND rw = ? AND rt = ?";
		$query = $this->db->query($sql, array($dusun, $rw, $rt));
		return $query->row_array();
	}

	public function total()
	{
		$sql = "SELECT
		(SELECT COUNT(rw.id) FROM tweb_wil_clusterdesa rw WHERE  rw <> '-' AND rt = '-') AS total_rw,
		(SELECT COUNT(v.id) FROM tweb_wil_clusterdesa v WHERE v.rt <> '0' AND v.rt <> '-') AS total_rt,
		(SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa ) and status_dasar=1) AS total_warga,
		(SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa) AND p.sex = 1 and status_dasar=1) AS total_warga_l,
		(SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa) AND p.sex = 2 and status_dasar=1) AS total_warga_p,
		(SELECT COUNT(p.id) FROM tweb_keluarga k inner join tweb_penduduk p ON k.nik_kepala=p.id WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa) AND p.kk_level = 1 and status_dasar=1) AS total_kk
		FROM tweb_wil_clusterdesa u
		LEFT JOIN tweb_penduduk a ON u.id_kepala = a.id WHERE u.rt = '0' AND u.rw = '0' limit 1";
		$query = $this->db->query($sql);
		return $query->row_array();
	}

	public function total_rw($dusun='')
	{
		$sql = "SELECT sum(jumlah_rt) as jmlrt, sum(jumlah_warga) as jmlwarga, sum(jumlah_warga_l) as jmlwargal, sum(jumlah_warga_p) as jmlwargap, sum(jumlah_kk) as jmlkk
			FROM
			(SELECT u.*, a.nama AS nama_ketua, a.nik AS nik_ketua,
				(SELECT COUNT(rt.id) FROM tweb_wil_clusterdesa rt WHERE dusun = u.dusun AND rw = u.rw AND rt <> '-' AND rt <> '0' ) AS jumlah_rt,
				(SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = '$dusun' AND rw = u.rw ) and status_dasar = 1) AS jumlah_warga,
				(SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = '$dusun' AND rw = u.rw) AND p.sex = 1 and status_dasar = 1) AS jumlah_warga_l,
				(SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = '$dusun' AND rw = u.rw) AND p.sex = 2 and status_dasar = 1) AS jumlah_warga_p,
				(SELECT COUNT(p.id) FROM  tweb_keluarga k inner join tweb_penduduk p ON k.nik_kepala=p.id   WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = '$dusun' AND rw = u.rw) AND p.kk_level = 1 and status_dasar = 1) AS jumlah_kk
				FROM tweb_wil_clusterdesa u
				LEFT JOIN tweb_penduduk a ON u.id_kepala = a.id
				WHERE u.rt = '0' AND u.rw <> '0' AND u.dusun = '$dusun') as x ";
		$query = $this->db->query($sql);
		$data = $query->row_array();
		return $data;
	}

	public function total_rt($dusun='', $rw='')
	{
		$sql = "SELECT sum(jumlah_warga) as jmlwarga, sum(jumlah_warga_l) as jmlwargal, sum(jumlah_warga_p) as jmlwargap, sum(jumlah_kk) as jmlkk
			FROM
				(SELECT u.*, a.nama AS nama_ketua,a.nik AS nik_ketua,
					(SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = '$dusun' AND rw = '$rw' AND rt = u.rt) and status_dasar = 1) AS jumlah_warga,
					(SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = '$dusun' AND rw = '$rw' AND rt = u.rt) AND p.sex = 1 and status_dasar = 1) AS jumlah_warga_l,
					(SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = '$dusun' AND rw = '$rw' AND rt = u.rt) AND p.sex = 2 and status_dasar = 1) AS jumlah_warga_p,
					(SELECT COUNT(p.id) FROM  tweb_keluarga k inner join tweb_penduduk p ON k.nik_kepala=p.id   WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = '$dusun' AND rw = '$rw' AND rt = u.rt) AND p.kk_level = 1 and status_dasar = 1) AS jumlah_kk
					FROM tweb_wil_clusterdesa u
					LEFT JOIN tweb_penduduk a ON u.id_kepala = a.id
					WHERE u.rt <> '0' AND u.rt <> '-' AND u.rw = '$rw' AND u.dusun = '$dusun') as x  ";
		$query = $this->db->query($sql);
		$data = $query->row_array();
		return $data;
	}


       public function update_kantor_dusun_map($id='')
	{
                $data = $_POST;
                $id = $_POST['id'];
		$this->db->where('id', $id);
		$outp = $this->db->update('tweb_wil_clusterdesa', $data);

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

        
        public function update_wilayah_dusun_map($id='')
	{
                $data = $_POST;
                $id = $_POST['id'];
		$this->db->where('id', $id);
		$outp = $this->db->update('tweb_wil_clusterdesa', $data);

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

       public function get_dusun_maps($dusun='')
       {
		$sql = "SELECT * FROM tweb_wil_clusterdesa WHERE dusun = ?";
		$query = $this->db->query($sql, $dusun);
		return $query->row_array();
       }


       public function update_kantor_rw_map($id='')
	{
                $data = $_POST;
                $id = $_POST['id'];
                $this->db->where('id', $id);
		$outp = $this->db->update('tweb_wil_clusterdesa', $data);

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}


       public function update_wilayah_rw_map($id='')
	{
                $data = $_POST;
                $id = $_POST['id'];
                $this->db->where('id', $id);
		$outp = $this->db->update('tweb_wil_clusterdesa', $data);

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}


       public function get_rw_maps($dusun='', $rw='')
	{
		$sql = "SELECT * FROM tweb_wil_clusterdesa WHERE dusun = ? AND rw = ?";
		$query = $this->db->query($sql, array($dusun, $rw));
		return $query->row_array();
	}

	
       public function update_kantor_rt_map($id='')
	{
                $data = $_POST;
                $id = $_POST['id'];
                $this->db->where('id', $id);
		$outp = $this->db->update('tweb_wil_clusterdesa', $data);

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}


       public function update_wilayah_rt_map($id='')
	{
                $data = $_POST;
                $id = $_POST['id'];
                $this->db->where('id', $id);
		$outp = $this->db->update('tweb_wil_clusterdesa', $data);

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}


       public function get_rt_maps($dusun='', $rw='', $rt='')
	{
		$sql = "SELECT * FROM tweb_wil_clusterdesa WHERE dusun = ? AND rw = ? AND rt = ?";
		$query = $this->db->query($sql, array($dusun, $rw, $rt));
		return $query->row_array();
	}

       public function list_rw_gis($dusun='')
	{
		$data = $this->db->
			where('rt', '0')->
			//where('dusun', urldecode($dusun))->
			where('rw <>', '0')->
			order_by('rw')->
			get('tweb_wil_clusterdesa')->
			result_array();
		return $data;
	}

        public function list_rt_gis($dusun='', $rw='')
	{
		$data = $this->db->
			where('rt <>', '0')->
			//where('dusun', urldecode($dusun))->
			//where('rw', $rw)->
			order_by('rt')->
			get('tweb_wil_clusterdesa')->
			result_array();
		return $data;
	}

}

?>
