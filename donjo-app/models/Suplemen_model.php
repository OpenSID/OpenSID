<?php class Suplemen_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function list_suplemen($sasaran=0)
	{
		if ($sasaran > 0)
		{
			$strSQL = "SELECT *
				FROM suplemen s
				WHERE s.sasaran=".$sasaran;
		}
		else
		{
			$strSQL = "SELECT *
				FROM suplemen s WHERE 1";
		}
		$query = $this->db->query($strSQL);
		$data = $query->result_array();
		return $data;
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
		$data['sasaran'] = $post['cid'];
		$data['nama'] = nomor_surat_keputusan($post['nama']);
		$data['keterangan'] = htmlentities($post['keterangan']);
		return $data;
	}

	public function list_data($sasaran=0)
	{
		if ($sasaran > 0)
		{
			$data = $this->db->select('*')->where('sasaran',$sasaran)->order_by('nama')->get('suplemen')->result_array();
		}
		else
		{
			$data = $this->db->select('*')->order_by('nama')->get('suplemen')->result_array();
		}
		return $data;
	}

	public function list_sasaran($id, $sasaran)
	{
		$data = array();
		switch ($sasaran)
		{
			case '1':
				$data = $this->list_penduduk($id);
				break;
			case '2': # sasaran KK
				$data = $this->list_kk($id);
			default:
				# code...
				break;
		}
		return $data;
	}

	private function get_id_terdata_penduduk($id_suplemen)
	{
		$hasil = array();
		$sql = "SELECT p.id
			FROM tweb_penduduk p
			LEFT JOIN suplemen_terdata t ON p.id = t.id_terdata
			WHERE t.id_suplemen = ?";
		$data = $this->db->query($sql, $id_suplemen)->result_array();
		foreach ($data as $item)
		{
			$hasil[] = $item['id'];
		}
		return $hasil;
	}

	private function list_penduduk($id)
	{
		// Penduduk yang sudah terdata untuk suplemen ini
		$terdata = "";
		$list_terdata = $this->get_id_terdata_penduduk($id);
		foreach ($list_terdata as $key => $value)
		{
			$terdata .= ",".$value;
		}
		$terdata = ltrim($terdata, ",");
		if (!empty($terdata))
			$this->db->where("p.id NOT IN ($terdata)");
		$data = $this->db->select('p.id as id, p.nik as nik, p.nama, w.rt, w.rw, w.dusun')
			->from('tweb_penduduk p')
			->join('tweb_wil_clusterdesa w', 'w.id = p.id_cluster', 'left')
			->get()->result_array();
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

	private function get_id_terdata_kk($id_suplemen)
	{
		$hasil = array();
		$sql = "SELECT k.id
			FROM tweb_keluarga k
			LEFT JOIN suplemen_terdata t ON k.id = t.id_terdata
			WHERE t.id_suplemen = ?";
		$data = $this->db->query($sql, $id_suplemen)->result_array();
		foreach ($data as $item)
		{
			$hasil[] = $item['id'];
		}
		return $hasil;
	}

	private function list_kk($id)
	{
		// Keluarga yang sudah terdata untuk suplemen ini
		$terdata = "";
		$list_terdata = $this->get_id_terdata_kk($id);
		foreach ($list_terdata as $key => $value)
		{
			$terdata .= ",".$value;
		}
		$terdata = ltrim($terdata, ",");
		if (!empty($terdata))
			$this->db->where("k.id NOT IN ($terdata)");
		// Daftar keluarga, tidak termasuk keluarga yang sudah terdata
		$data = $this->db->select('k.id as id, k.no_kk, p.nama, w.rt, w.rw, w.dusun')
			->from('tweb_keluarga k')
			->join('tweb_penduduk p', 'p.id = k.nik_kepala', 'left')
			->join('tweb_wil_clusterdesa w', 'w.id = p.id_cluster', 'left')
			->get()->result_array();
		$hasil = array();
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
		$data = $this->db->where('id',$id)->get('suplemen')->row_array();
		return $data;
	}

	public function get_rincian($p, $suplemen_id)
	{
		$suplemen = $this->db->where('id',$suplemen_id)->get('suplemen')->row_array();
		$sasaran = $suplemen['sasaran'];
		switch ($sasaran)
		{
			case '1':
				$suplemen['judul_terdata_nama'] = 'NIK';
				$suplemen['judul_terdata_info'] = 'Nama Penduduk';
				$data = $this->get_penduduk_terdata($suplemen_id, $p);
				break;
			case '2': # sasaran KK
				$suplemen['judul_terdata_nama'] = 'NO. KK';
				$suplemen['judul_terdata_info'] = 'Kepala Keluarga';
				$data = $this->get_kk_terdata($suplemen_id, $p);
				break;

			default:
				# code...
				break;
		}
		$data['suplemen'] = $suplemen;
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
		$hasil = array();
		$get_terdata_sql = $this->get_penduduk_terdata_sql($suplemen_id);
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
		$hasil = array();
		$get_terdata_sql = $this->get_kk_terdata_sql($suplemen_id);
		$select_sql = "SELECT s.*, s.id_terdata, o.no_kk as terdata_id, s.id_suplemen as nama, o.nik_kepala, o.no_kk, q.nama, q.tempatlahir, q.tanggallahir, q.sex, w.rt, w.rw, w.dusun ";
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
				$data[$i]['terdata_nama'] = $data[$i]['no_kk'];
				$data[$i]['terdata_info'] = $data[$i]['nama'];
				$data[$i]['nama'] = strtoupper($data[$i]['nama'])." [".$data[$i]['no_kk']."]";
				$data[$i]['tempat_lahir'] = strtoupper($data[$i]['tempatlahir']);
				$data[$i]['tanggal_lahir'] = tgl_indo($data[$i]['tanggallahir']);
				$data[$i]['sex'] = ($data[$i]['sex'] == 1) ? "LAKI-LAKI" : "PEREMPUAN";
				$data[$i]['info'] = "RT/RW ". $data[$i]['rt']."/".$data[$i]['rw']." - ".strtoupper($data[$i]['dusun']);
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
			case 1:
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
				break;
			case 2:
				# Data KK
				$data = $this->keluarga_model->get_kepala_kk($id_terdata);
				$data['id'] = $data['id_kk']; // id_kk digunakan sebagai id terdata
				break;

			default:
				break;
		}
		return $data;
	}

	public function hapus($id)
	{
		$hasil = $this->db->where('id', $id)->delete('suplemen');

		status_sukses($hasil); //Tampilkan Pesan
	}

	public function update($id)
	{
		$data = $this->validasi($this->input->post());
		$hasil = $this->db->where('id',$id)->update('suplemen', $data);

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
		$this->db->where('id',$id);
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
		$list_suplemen = array();
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
				$strSQL = "SELECT o.nama, o.foto, o.nik, w.rt, w.rw, w.dusun
					FROM tweb_penduduk o
				 	LEFT JOIN tweb_wil_clusterdesa w ON w.id = o.id_cluster
				 	WHERE o.id = '".$id_terdata."'";
				$query = $this->db->query($strSQL);
				if ($query->num_rows() > 0)
				{
					$row = $query->row_array();
					$data_profil = array(
						"id" => $id,
						"nama" => $row["nama"] ." - ".$row["nik"],
						"ndesc" => "Alamat: RT ".strtoupper($row["rt"])." / RW ".strtoupper($row["rw"])." ".strtoupper($row["dusun"]),
						"foto" => $row["foto"]
						);
				}

				break;
			case 2:
				/*
				 * KK
				 * */
				$strSQL = "SELECT o.nik_kepala, o.no_kk, p.nama, w.rt, w.rw, w.dusun
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
						"ndesc" => "Alamat: RT ".strtoupper($row["rt"])." / RW ".strtoupper($row["rw"])." ".strtoupper($row["dusun"]),
						"foto" => ""
						);
				}

				break;
			default:

		}
		if (!empty($list_suplemen))
		{
			$hasil = array("daftar_suplemen" => $list_suplemen, "profil" => $data_profil);
			return $hasil;
		}
		else
		{
			return null;
		}
	}
}
?>