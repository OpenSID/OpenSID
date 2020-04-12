<?php

define("TUJUAN_MUDIK", serialize(array(
	"Liburan" => "1",
	"Menjenguk Keluarga" => "2",
	"Pulang Kampung" => "3",
	"Dll" => "4",
)));

define("STATUS_COVID", serialize(array(
	"Orang Dalam Pemantauan (ODP)" => "ODP",
	"Pasien Dalam Pengawasan (PDP)" => "PDP",
	"Orang Dalam Resiko (ODR)" => "ODR",
	"Orang Tanpa Gejala (OTG)" => "OTG",
	"Positif Covid-19" => "POSITIF",
	"Dll" => "DLL",
)));

class Covid19_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
	}

	public function list_tujuan_mudik()
	{
		$status_rekam = array_flip(unserialize(TUJUAN_MUDIK));
		return $status_rekam;
	}

	public function list_status_covid()
	{
		$status_rekam = array_flip(unserialize(STATUS_COVID));
		return $status_rekam;
	}

	private function paging($p)
	{
		$this->db->select('COUNT(*) as jumlah');
		$this->db->from('covid19_pemudik s');
		$this->db->join('tweb_penduduk o', 's.id_terdata = o.id', 'left');
		$this->db->join('tweb_keluarga k', 'k.id = o.id_kk', 'left');
		$this->db->join('tweb_wil_clusterdesa w', 'w.id = o.id_cluster', 'left');
		$this->db->join('tweb_penduduk_map m', 's.id_terdata = m.id', 'left');

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

		$this->db->select('s.*, s.id_terdata, o.nik as terdata_id, o.nama, o.tempatlahir, o.tanggallahir, o.sex, w.rt, w.rw, w.dusun');
		$this->db->select("(select (date_format(from_days((to_days(now()) - to_days(tweb_penduduk.tanggallahir))),'%Y') + 0) AS `(date_format(from_days((to_days(now()) - to_days(tweb_penduduk.tanggallahir))),'%Y') + 0)`
		from tweb_penduduk where (tweb_penduduk.id = o.id)) AS umur");
		$this->db->select('(case when (o.id_kk IS NULL or o.id_kk = 0) then o.alamat_sekarang else k.alamat end) AS `alamat`');
		$this->db->from('covid19_pemudik s');
		$this->db->join('tweb_penduduk o', 's.id_terdata = o.id', 'left');
		$this->db->join('tweb_keluarga k', 'k.id = o.id_kk', 'left');
		$this->db->join('tweb_wil_clusterdesa w', 'w.id = o.id_cluster', 'left');
		$this->db->join('tweb_penduduk_map m', 's.id_terdata = m.id', 'left');

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
		$this->db->select('u.id, u.nama, x.nama AS sex, u.id_kk, u.tempatlahir, u.tanggallahir, w.nama AS status_kawin, f.nama AS warganegara, a.nama AS agama, d.nama AS pendidikan, j.nama AS pekerjaan, u.nik, c.rt, c.rw, c.dusun, k.no_kk, k.alamat');
		$this->db->select("(select (date_format(from_days((to_days(now()) - to_days(tweb_penduduk.tanggallahir))),'%Y') + 0) AS `(date_format(from_days((to_days(now()) - to_days(tweb_penduduk.tanggallahir))),'%Y') + 0)`
		from tweb_penduduk where (tweb_penduduk.id = u.id)) AS umur");
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
			'status_covid' => $post['status_covid'],
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
		$terdata = $this->get_pemudik($data['id_terdata']);
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
			'status_covid' => $post['status_covid'],
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

	public function get_lokasi($id)
	{
		$data = $this->db->where('id', $id)->get('covid19_pemudik')->row_array();
		return $data;
	}

	public function update_position($id=0)
	{
		$data['lat'] = $this->input->post('lat');
		$data['lng'] = $this->input->post('lng');
		$this->db->where('id', $id);
		$outp = $this->db->update('covid19_pemudik', $data);

		status_sukses($outp); //Tampilkan Pesan
	}

	public function list_dusun()
	{
		$sql = "SELECT * FROM tweb_wil_clusterdesa WHERE rt = '0' AND rw = '0' ";
		$query = $this->db->query($sql);
		$data = $query->result_array();
		return $data;
	}

	public function list_pemudik_gis()
	{
		//Main Query
		$sql = "SELECT u.id, u.nik, u.nama, map.*, a.dusun, a.rw, a.rt, u.foto, d.no_kk AS no_kk,
					(SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0
					FROM tweb_penduduk
					WHERE id = u.id) AS umur,
				x.nama AS sex, sd.nama AS pendidikan_sedang, n.nama AS pendidikan, p.nama AS pekerjaan, k.nama AS kawin, g.nama AS agama, m.nama AS gol_darah, hub.nama AS hubungan,
				@alamat:=trim(concat_ws(' ',
					case
						when a.rt != '-' then concat('RT-', a.rt)
						else ''
					end,
					case
						when a.rw != '-' then concat('RW-', a.rw)
						else ''
					end,
					case
						when a.dusun != '-' then concat('Dusun ', a.dusun)
						else ''
					end
				)),
				case
					when length(@alamat) > 0 then @alamat
					else 'Alamat penduduk belum valid'
				end as alamat
				FROM tweb_penduduk u
				LEFT JOIN tweb_wil_clusterdesa a ON u.id_cluster = a.id
				LEFT JOIN tweb_wil_clusterdesa a2 ON u.id_cluster = a2.id
				LEFT JOIN tweb_keluarga d ON u.id_kk = d.id
				LEFT JOIN tweb_penduduk_pendidikan_kk n ON u.pendidikan_kk_id = n.id
				LEFT JOIN tweb_penduduk_pendidikan sd ON u.pendidikan_sedang_id = sd.id
				LEFT JOIN tweb_penduduk_pekerjaan p ON u.pekerjaan_id = p.id
				LEFT JOIN tweb_penduduk_kawin k ON u.status_kawin = k.id
				LEFT JOIN tweb_penduduk_sex x ON u.sex = x.id
				LEFT JOIN tweb_penduduk_agama g ON u.agama_id = g.id
				LEFT JOIN tweb_penduduk_warganegara v ON u.warganegara_id = v.id
				LEFT JOIN tweb_golongan_darah m ON u.golongan_darah_id = m.id
				LEFT JOIN tweb_cacat f ON u.cacat_id = f.id
				LEFT JOIN tweb_penduduk_hubungan hub ON u.kk_level = hub.id
				LEFT JOIN tweb_sakit_menahun j ON u.sakit_menahun_id = j.id
				LEFT JOIN covid19_pemudik map ON u.id = map.id_terdata ";

		$query = $this->db->query($sql);
		return $query->result_array();
	}

}
?>
