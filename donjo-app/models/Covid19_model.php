<?php class Covid19_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
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

	private function get_penduduk_terdata_sql()
	{
		# Data penduduk
		$sql = " FROM covid19_terdata s
			LEFT JOIN tweb_penduduk o ON s.id_terdata = o.id
			LEFT JOIN tweb_keluarga k ON k.id = o.id_kk
			LEFT JOIN tweb_wil_clusterdesa w ON w.id = o.id_cluster";
		return $sql;
	}

	private function get_penduduk_terdata($p)
	{
		$hasil = array();

		$get_terdata_sql = $this->get_penduduk_terdata_sql();

		$select_sql = "SELECT s.*, s.id_terdata, o.nik as terdata_id, o.nama, o.tempatlahir, o.tanggallahir, o.sex, w.rt, w.rw, w.dusun,
			(case when (o.id_kk IS NULL or o.id_kk = 0) then o.alamat_sekarang else k.alamat end) AS alamat
		 ";

		$sql = $select_sql.$get_terdata_sql;


		if (!empty($_SESSION['per_page']) and $_SESSION['per_page'] > 0)
		{
			$hasil["paging"] = $this->paging($p, $get_terdata_sql);
			$paging_sql = ' LIMIT ' .$hasil["paging"]->offset. ',' .$hasil["paging"]->per_page;
			$sql .= $paging_sql;
		}
		$query = $this->db->query($sql);

		if ($query->num_rows() > 0)
		{
			$data = $query->result_array();
			for ($i=0; $i<count($data); $i++)
			{
				$data[$i]['id'] = $data[$i]['id'];
				$data[$i]['terdata_nama'] = $data[$i]['terdata_id'];
				$data[$i]['terdata_info'] = $data[$i]['nama'];
				$data[$i]['nama'] = strtoupper($data[$i]['nama']);
				$data[$i]['tempat_lahir'] = strtoupper($data[$i]['tempatlahir']);
				$data[$i]['tanggal_lahir'] = tgl_indo($data[$i]['tanggallahir']);
				$data[$i]['sex'] = ($data[$i]['sex'] == 1) ? "LAKI-LAKI" : "PEREMPUAN";
				$data[$i]['info'] = $data[$i]['alamat'] . " "  .  "RT/RW ". $data[$i]['rt']."/".$data[$i]['rw']." - ". "Dusun " . strtoupper($data[$i]['dusun']);
			}
			$hasil['terdata'] = $data;
		}
		return $hasil;
	}

	private function get_id_terdata_penduduk()
	{
		$hasil = array();
		$sql = "SELECT p.id
			FROM tweb_penduduk p
			RIGHT JOIN covid19_terdata t ON p.id = t.id_terdata";
		$data = $this->db->query($sql, $id_suplemen)->result_array();
		foreach ($data as $item)
		{
			$hasil[] = $item['id'];
		}
		return $hasil;
	}

	public function list_penduduk()
	{
		// Penduduk yang sudah terdata untuk suplemen ini
		$terdata = "";
		$list_terdata = $this->get_id_terdata_penduduk();

		foreach ($list_terdata as $key => $value)
		{
			$terdata .= ",".$value;
		}
		$terdata = ltrim($terdata, ",");
		
		

		$this->db->select('p.id as id, p.nik as nik, p.nama, w.rt, w.rw, w.dusun')
			->from('tweb_penduduk p')
			->join('tweb_wil_clusterdesa w', 'w.id = p.id_cluster', 'left');

		if (!empty($terdata))
			$this->db->where("p.id NOT IN ($terdata)");

		$data = $this->db->get()->result_array();

		$hasil = array();
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

	public function get_terdata($id_terdata)
	{
		$this->load->model('surat_model');
		# Data penduduk
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
		$data['alamat_wilayah']= $this->surat_model->get_alamat_wilayah($data);

		return $data;
	}

	public function add_terdata($post)
	{
		$id_terdata = $post['id_terdata'];
		$data = array(
			'id_terdata' => $id_terdata,
			'keterangan' => $post['keterangan']
		);
		return $this->db->insert('covid19_terdata', $data);
	}

	public function get_rincian($p)
	{
		$covid19['judul_terdata_nama'] = 'NIK';
		$covid19['judul_terdata_info'] = 'Nama Penduduk';
		
		$data = $this->get_penduduk_terdata($p);
		$data['covid19'] = $covid19;

		return $data;
	}

}
?>