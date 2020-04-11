<?php class Covid19_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
	}

	private function paging($p)
	{
		$this->db->select('COUNT(*) as jumlah');
		$this->db->from('covid19_pemudik s');
		$this->db->join('tweb_penduduk o', 's.id_terdata = o.id', 'left');
		$this->db->join('tweb_keluarga k', 'k.id = o.id_kk', 'left');
		$this->db->join('tweb_wil_clusterdesa w', 'w.id = o.id_cluster', 'left');

		$row = $this->db->get()->row_array();
		$jml_data = $row['jumlah'];

		$this->load->library('paging');
		$cfg['page'] = $p;
		$cfg['per_page'] = $this->session->userdata('per_page');
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);

		return $this->paging;
	}

	private function get_penduduk_pemudik($p)
	{
		$hasil = array();
		if ($this->session->has_userdata('per_page') and $this->session->userdata('per_page') > 0)
		{
			$hasil["paging"] = $this->paging($p);

		}

		$this->db->select('s.*');
		$this->db->select('s.id_terdata');
		$this->db->select('o.nik as terdata_id');
		$this->db->select('o.nama');
		$this->db->select('o.tempatlahir');
		$this->db->select('o.tanggallahir');
		$this->db->select('o.sex');
		$this->db->select('w.rt');
		$this->db->select('w.rw');
		$this->db->select('w.dusun');
		$this->db->select('(case when (o.id_kk IS NULL or o.id_kk = 0) then o.alamat_sekarang else k.alamat end) AS `alamat`');
		$this->db->from('covid19_pemudik s');
		$this->db->join('tweb_penduduk o', 's.id_terdata = o.id', 'left');
		$this->db->join('tweb_keluarga k', 'k.id = o.id_kk', 'left');
		$this->db->join('tweb_wil_clusterdesa w', 'w.id = o.id_cluster', 'left');

		if(isset($hasil["paging"])) {
			$this->db->limit($hasil["paging"]->per_page, $hasil["paging"]->offset);
		}

		$query = $this->db->get();		
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

	private function get_id_penduduk_pemudik()
	{
		$this->db->select('p.id');
		$this->db->from('tweb_penduduk p');
		$this->db->join('covid19_pemudik t', 'p.id = t.id_terdata', 'right');
		$data = $this->db->get()->result_array();

		$hasil = array();
		foreach ($data as $item)
		{
			$hasil[] = $item['id'];
		}
		return $hasil;
	}

	public function get_rincian_pemudik($p)
	{
		$covid19['judul_terdata_nama'] = 'NIK';
		$covid19['judul_terdata_info'] = 'Nama Penduduk';
		
		$data = $this->get_penduduk_pemudik($p);
		$data['covid19'] = $covid19;

		return $data;
	}

	public function list_penduduk_pemudik()
	{
		// Penduduk yang sudah terdata untuk suplemen ini
		$terdata = "";
		$list_terdata = $this->get_id_penduduk_pemudik();

		foreach ($list_terdata as $key => $value)
		{
			$terdata .= ",".$value;
		}
		$terdata = ltrim($terdata, ",");

		$this->db->select('p.id as id, p.nik as nik, p.nama, w.rt, w.rw, w.dusun')
			->from('tweb_penduduk p')
			->join('tweb_wil_clusterdesa w', 'w.id = p.id_cluster', 'left');

		if (!empty($terdata)) {
			$this->db->where("p.id NOT IN ($terdata)");
		}

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

	public function get_pemudik($id_pemudik)
	{
		$this->db->select('u.id AS id');
		$this->db->select('u.nama AS nama');
		$this->db->select('x.nama AS sex');
		$this->db->select('u.id_kk AS id_kk');
		$this->db->select('u.tempatlahir AS tempatlahir');
		$this->db->select('u.tanggallahir AS tanggallahir');
		$this->db->select("(select (date_format(from_days((to_days(now()) - to_days(tweb_penduduk.tanggallahir))),'%Y') + 0) AS `(date_format(from_days((to_days(now()) - to_days(tweb_penduduk.tanggallahir))),'%Y') + 0)`
		from tweb_penduduk where (tweb_penduduk.id = u.id)) AS umur");
		$this->db->select('w.nama AS status_kawin');
		$this->db->select('f.nama AS warganegara');
		$this->db->select('a.nama AS agama');
		$this->db->select('d.nama AS pendidikan');
		$this->db->select('j.nama AS pekerjaan');
		$this->db->select('u.nik AS nik');
		$this->db->select('c.rt AS rt');
		$this->db->select('c.rw AS rw');
		$this->db->select('c.dusun AS dusun');
		$this->db->select('k.no_kk AS no_kk');
		$this->db->select('k.alamat AS alamat');
		$this->db->select('(select tweb_penduduk.nama AS nama from tweb_penduduk where (tweb_penduduk.id = k.nik_kepala)) AS kepala_kk');

		$this->db->from('tweb_penduduk u');

		$this->db->join('tweb_penduduk_sex x', 'u.sex = x.id', 'left');
		$this->db->join('tweb_penduduk_kawin w', 'u.status_kawin = w.id', 'left');
		$this->db->join('tweb_penduduk_agama a', 'u.agama_id = a.id', 'left');
		$this->db->join('tweb_penduduk_pendidikan_kk d', 'u.pendidikan_kk_id = d.id', 'left');
		$this->db->join('tweb_penduduk_pekerjaan j', 'u.pekerjaan_id = j.id', 'left');
		$this->db->join('tweb_wil_clusterdesa c', 'u.id_cluster = c.id', 'left');
		$this->db->join('tweb_keluarga k', 'u.id_kk = k.id', 'left');
		$this->db->join('tweb_penduduk_warganegara f', 'u.warganegara_id = f.id', 'left');

		$this->db->where('u.id', $id_pemudik);

		$query = $this->db->get();
		$data  = $query->row_array();

		$this->load->model('surat_model');
		$data['alamat_wilayah']= $this->surat_model->get_alamat_wilayah($data);

		return $data;
	}

	public function add_pemudik($post)
	{
		$tujuan = "";
		switch($post['tujuan_pemudik'])
		{
			case "1":
				$tujuan = "Liburan";
				break;
			case "2":
				$tujuan = "Menjenguk Keluarga";
				break;
			case "3":
				$tujuan = "Pulang Kampung";
				break;
			case "4":
				$tujuan = "Dll";
				break;
		}

		$data = array(
			'id_terdata' => $post['id_terdata'],
			'tanggal_datang' => $post['tanggal_tiba'],
			'asal_mudik' => $post['asal_pemudik'],
			'durasi_mudik' => $post['durasi_pemudik'],
			'tujuan_mudik' => $tujuan,
			'no_hp' => $post['hp_pemudik'],
			'email' => $post['email_pemudik'],
			'keluhan_kesehatan' => $post['keluhan'],
			'keterangan' => $post['keterangan']
		);
		return $this->db->insert('covid19_pemudik', $data);
	}

	public function hapus_pemudik($id_pemudik)
	{
		$this->db->where('id', $id_pemudik);
		$this->db->delete('covid19_pemudik');
	}

	public function get_pemudik_by_id($id)
	{
		$data = $this->db->where('id', $id)->get('covid19_pemudik')->row_array();
		// Data tambahan untuk ditampilkan
		$terdata = $this->get_terdata($data['id_terdata']);
		$data['judul_terdata_nama'] = 'NIK';
		$data['judul_terdata_info'] = 'Nama Terdata';
		$data['terdata_nama'] = $terdata['nik'];
		$data['terdata_info'] = $terdata['nama'];

		return $data;
	}

	public function edit_pemudik($post,$id)
	{
		$tujuan = "";
		switch($post['tujuan_pemudik'])
		{
			case "1":
				$tujuan = "Liburan";
				break;
			case "2":
				$tujuan = "Menjenguk Keluarga";
				break;
			case "3":
				$tujuan = "Pulang Kampung";
				break;
			case "4":
				$tujuan = "Dll";
				break;
		}

		$data = array(
			'tanggal_datang' => $post['tanggal_tiba'],
			'asal_mudik' => $post['asal_pemudik'],
			'durasi_mudik' => $post['durasi_pemudik'],
			'tujuan_mudik' => $tujuan,
			'no_hp' => $post['hp_pemudik'],
			'email' => $post['email_pemudik'],
			'keluhan_kesehatan' => $post['keluhan'],
			'keterangan' => $post['keterangan']
		);

		$this->db->where('id',$id);
		$this->db->update('covid19_pemudik', $data);
	}

	public function get_detil_pemudik_by_id($id)
	{
		$data = $this->db->where('id', $id)->get('covid19_pemudik')->row_array();
		return $data;
	}
	
}
?>