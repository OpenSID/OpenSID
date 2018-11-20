<?php
class Data_persil_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}

	public function autocomplete()
	{
		$sql = "SELECT pemilik_luar as nik
			FROM data_persil
			UNION
				SELECT p.nik AS nik
				FROM data_persil u
				LEFT JOIN tweb_penduduk p ON u.id_pend = p.id
			UNION
				SELECT p.nama AS nik
				FROM data_persil u
				LEFT JOIN tweb_penduduk p ON u.id_pend = p.id";
		$query = $this->db->query($sql);
		$data = $query->result_array();

		$outp = '';
		for ($i=0; $i<count($data); $i++)
		{
			$outp .= ",'" .$data[$i]['nik']. "'";
		}
		$outp = strtolower(substr($outp, 1));
		$outp = '[' .$outp. ']';
		return $outp;
	}

	private function search_sql()
	{
		if (isset($_SESSION['cari']))
		{
			$cari = $_SESSION['cari'];
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' .$kw. '%';
			$search_sql= " AND (u.nama LIKE '$kw' OR p.pemilik_luar like '$kw' OR u.nik LIKE '$kw')";
			return $search_sql;
			}
		}

	private function main_sql()
	{
		$sql = " FROM `data_persil` p
				LEFT JOIN tweb_penduduk u ON u.id = p.id_pend
				LEFT JOIN tweb_wil_clusterdesa w ON w.id = p.id_clusterdesa
			 	WHERE 1 ";
		return $sql;
	}

	private function filtered_sql($kat='', $mana=0)
	{
		$sql = $this->main_sql();
		if ($kat == "jenis")
		{
			if ($mana > 0)
			{
				$sql .= " AND (p.persil_jenis_id=".$mana.") ";
			}
		}
		elseif($kat == "peruntukan")
		{
			if ($mana > 0)
			{
				$sql .= " AND (p.persil_peruntukan_id=".$mana.") ";
			}
		}
		$sql .= $this->search_sql();
		return $sql;
	}

	public function paging($kat='', $mana=0, $p=1)
	{
		$sql = "SELECT COUNT(*) AS jml".$this->filtered_sql($kat, $mana);
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

	public function list_persil($kat='', $mana=0, $offset, $per_page)
	{
		$strSQL = "SELECT p.`id` as id, u.nik as nik, p.`nama` as nama, p.`jenis_pemilik`, p.`nama` as nopersil, p.`persil_jenis_id`, p.`id_clusterdesa`, p.`luas`, p.`kelas`, p.pemilik_luar,
			p.rdate as tanggal_daftar,p.`no_sppt_pbb`, p.`persil_peruntukan_id`, u.nama as namapemilik, w.rt, w.rw, w.dusun".$this->filtered_sql($kat, $mana);
		$strSQL .= " LIMIT ".$offset.",".$per_page;
		$query = $this->db->query($strSQL);
		if ($query->num_rows() > 0)
		{
			$data = $query->result_array();
		}
		else
		{
			$_SESSION["pesan"]= $strSQL;
		}

		$j = $offset;
		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $j + 1;
			if (($data[$i]['jenis_pemilik']) == 2)
			{
				$data[$i]['namapemilik'] = $data[$i]['pemilik_luar'];
				$data[$i]['nik'] = "-";
			}
			$j++;
		}
		return $data;
	}

	public function get_persil($id)
	{
		$data = false;
		$strSQL = "SELECT p.`id` as id, u.`nik` as nik, p.`jenis_pemilik` as jenis_pemilik, p.`nama` as nopersil, p.id_pend, p.`persil_jenis_id`, p.`id_clusterdesa`, p.`luas`,
			p.`kelas`, p.pemilik_luar,
			p.`no_sppt_pbb`, p.`persil_peruntukan_id`, u.nama as namapemilik, w.rt, w.rw, w.dusun,alamat_luar
			FROM `data_persil` p
				LEFT JOIN tweb_penduduk u ON u.id = p.id_pend
				LEFT JOIN tweb_wil_clusterdesa w ON w.id = p.id_clusterdesa
			 WHERE p.id = ".$id;
		$query = $this->db->query($strSQL);
		if ($query->num_rows()>0)
		{
			$data = $query->row_array();
		}

		if ($data['jenis_pemilik'] == 2)
		{
			$data['namapemilik'] = $data['pemilik_luar'];
			$data['nik'] = "-";
		}
		return $data;
	}

	public function simpan_persil()
	{
		$hasil = false;
		if (@$_POST["nik"])
		{
			if ($_POST["id"] > 0)
			{
				$data = array();
				$data['nama'] = $_POST["nama"];
				$data['jenis_pemilik'] = $_POST["jenis_pemilik"];
				if ($data['jenis_pemilik'] == 2)
					$data['pemilik_luar'] = $_POST['nik'];
				else
				{
					if ($_POST['nik'] <> $_POST['nik_lama'])
					{
						// Ambil id penduduk baru
						$data['id_pend'] = $this->db->select('id')->
							where('nik', $_POST['nik'])->
							get('tweb_penduduk')->row()->id;
					}
				}
				$data['alamat_luar'] = $_POST["alamat_luar"];
				$data['persil_jenis_id'] = $_POST["cid"];
				$data['id_clusterdesa'] = $_POST["pid"];
				$data['persil_peruntukan_id'] = $_POST["sid"];
				$data['luas'] = $_POST["luas"];
				$data['kelas'] = $_POST["kelas"];
				$data['no_sppt_pbb'] = $_POST["sppt"];
				$data['userID'] = $_SESSION['user'];
				$outp = $this->db->where('id', $_POST['id'])->update('data_persil', $data);
			}
			else
			{
				if (is_numeric($_POST["nik"]))
				{
					$data = array();
					$data['nama'] = $_POST["nama"];
					$data['jenis_pemilik'] = $_POST["jenis_pemilik"];
					// Ambil id penduduk baru
					$data['id_pend'] = $this->db->select('id')->
						where('nik', $_POST['nik'])->
						get('tweb_penduduk')->row()->id;
					$data['persil_jenis_id'] = $_POST["cid"];
					$data['id_clusterdesa'] = $_POST["pid"];
					$data['persil_peruntukan_id'] = $_POST["sid"];
					$data['luas'] = $_POST["luas"];
					$data['kelas'] = $_POST["kelas"];
					$data['no_sppt_pbb'] = $_POST["sppt"];
					$data['userID'] = $_SESSION['user'];
					$outp = $this->db->insert('data_persil', $data);
				}
				else
				{
					$data = array();
					$data['nama'] = $_POST["nama"];
					$data['jenis_pemilik'] = $_POST["jenis_pemilik"];
					$data['pemilik_luar'] = $_POST['nik'];
					$data['alamat_luar'] = $_POST["alamat_luar"];
					$data['persil_jenis_id'] = $_POST["cid"];
					$data['id_clusterdesa'] = $_POST["pid"];
					$data['persil_peruntukan_id'] = $_POST["sid"];
					$data['luas'] = $_POST["luas"];
					$data['kelas'] = $_POST["kelas"];
					$data['no_sppt_pbb'] = $_POST["sppt"];
					$data['userID'] = $_SESSION['user'];
					$outp = $this->db->insert('data_persil', $data);
				}
			}
			if ($outp)
			{
				$_SESSION["success"] = 1;
				$_SESSION["pesan"] = "Data Persil telah DISIMPAN";
				$hasil = true;
			}
		}
		else
		{
			$_SESSION["success"] = -1;
			$_SESSION["pesan"] = "Formulir belum/tidak terisi dengan benar";
		}
		return $hasil;
	}

	public function hapus_persil($id)
	{
		$strSQL = "DELETE FROM `data_persil` WHERE id = ".$id;
		$hasil = $this->db->query($strSQL);
		if ($hasil)
		{
			$_SESSION["success"] = 1;
			$_SESSION["pesan"] = "Data Persil telah dihapus";
		}
		else
		{
			$_SESSION["success"] = -1;
			$_SESSION["pesan"] = "Gagal menghapus data persil";
		}
	}

	public function list_dusunrwrt()
	{
		$strSQL = "SELECT `id`,`rt`,`rw`,`dusun` FROM `tweb_wil_clusterdesa` WHERE (`rt`>0) ORDER BY `dusun`";
		$query = $this->db->query($strSQL);
		return $query->result_array();
	}

	public function get_penduduk($id, $nik=false)
	{
		$this->db->select('p.nik,p.nama,k.no_kk,w.rt,w.rw,w.dusun')
			->from('tweb_penduduk p')
			->join('tweb_keluarga k','k.id = p.id_kk', 'left')
			->join('tweb_wil_clusterdesa w', 'w.id = p.id_cluster', 'left');
		if ($nik)
			$this->db->where('p.nik', $id);
		else
			$this->db->where('p.id', $id);
		$data = $this->db->get()->row_array();
		return $data;
	}

	public function list_penduduk()
	{
		$strSQL = "SELECT p.nik,p.nama,k.no_kk,w.rt,w.rw,w.dusun FROM tweb_penduduk p
			LEFT JOIN tweb_keluarga k ON k.id = p.id_kk
			LEFT JOIN tweb_wil_clusterdesa w ON w.id = p.id_cluster
			WHERE 1 ORDER BY nama";
		$query = $this->db->query($strSQL);
		$data = "";
		$data = $query->result_array();
		if ($query->num_rows() > 0)
		{
			$j = 0;
			for ($i=0; $i<count($data); $i++)
			{
				if ($data[$i]['nik'] != "")
				{
					$data1[$j]['id']=$data[$i]['nik'];
					$data1[$j]['nik']=$data[$i]['nik'];
					$data1[$j]['nama']=strtoupper($data[$i]['nama'])." [NIK: ".$data[$i]['nik']."] / [NO KK: ".$data[$i]["no_kk"]."]";
					$data1[$j]['info']= "RT/RW ". $data[$i]['rt']."/".$data[$i]['rw']." - ".strtoupper($data[$i]['dusun']);
					$j++;
				}
			}
			$hasil2 = $data1;
		}
		else
		{
			$hasil2 = false;
		}
		return $hasil2;
	}

	public function list_persil_peruntukan()
	{
		$data = false;
		$strSQL = "SELECT id,nama,ndesc FROM data_persil_peruntukan WHERE 1";
		$query = $this->db->query($strSQL);
		if ($query->num_rows()>0)
		{
			$data = array();
			foreach ($query->result() as $row)
			{
				$data[$row->id] = array($row->nama,$row->ndesc);
			}
		}
		return $data;
	}

	public function get_persil_peruntukan($id=0)
	{
		$data = false;
		$strSQL = "SELECT id,nama,ndesc FROM data_persil_peruntukan WHERE id=".$id;
		$query = $this->db->query($strSQL);
		if ($query->num_rows() > 0)
		{
			$data = array();
			$data[$id] = $query->row_array();
		}
		return $data;
	}

	public function update_persil_peruntukan()
	{
		if ($this->input->post('id') == 0)
		{
			$strSQL = "INSERT INTO `data_persil_peruntukan`(`nama`,`ndesc`) VALUES('".fixSQL($this->input->post('nama'))."','".fixSQL($this->input->post('ndesc'))."')";
		}
		else
		{
			$strSQL = "UPDATE `data_persil_peruntukan` SET
			`nama` = '".fixSQL($this->input->post('nama'))."',
			`ndesc` = '".fixSQL($this->input->post('ndesc'))."'
			 WHERE id = ".$this->input->post('id');
		}

		$data["db"] = $strSQL;
		$hasil = $this->db->query($strSQL);
		if ($hasil)
		{
			$data["transaksi"] = true;
			$data["pesan"] = "Data Peruntukan Persil ".fixSQL($this->input->post('nama'))." telah disimpan/diperbarui";
			$_SESSION["success"] = 1;
			$_SESSION["pesan"] = "Data Peruntukan Persil ".fixSQL($this->input->post('nama'))." telah disimpan/diperbarui";
		}
		else
		{
			$data["transaksi"] = false;
			$data["pesan"] = "ERROR ".$strSQL;
		}
		return $data;
	}

	public function hapus_peruntukan($id)
	{
		$strSQL = "DELETE FROM `data_persil_peruntukan` WHERE id = ".$id;
		$hasil = $this->db->query($strSQL);
		if ($hasil)
		{
			$_SESSION["success"] = 1;
			$_SESSION["pesan"] = "Data Peruntukan Persil telah dihapus";
		}
		else
		{
			$_SESSION["success"] = -1;
		}
	}

	public function list_persil_jenis()
	{
		$data = false;
		$strSQL = "SELECT id,nama,ndesc FROM data_persil_jenis WHERE 1";
		$query = $this->db->query($strSQL);
		if ($query->num_rows() > 0)
		{
			$data = array();
			foreach ($query->result() as $row)
			{
				$data[$row->id] = array($row->nama, $row->ndesc);
			}
		}
		return $data;
	}

	public function get_persil_jenis($id=0)
	{
		$data = false;
		$strSQL = "SELECT id,nama,ndesc FROM data_persil_jenis WHERE id = ".$id;
		$query = $this->db->query($strSQL);
		if ($query->num_rows() > 0)
		{
			$data = array();
			$data[$id] = $query->row_array();
		}
		return $data;
	}

	public function update_persil_jenis()
	{
		if ($this->input->post('id') == 0)
		{
			$strSQL = "INSERT INTO `data_persil_jenis`(`nama`,`ndesc`) VALUES('".fixSQL($this->input->post('nama'))."','".fixSQL($this->input->post('ndesc'))."')";
		}
		else
		{
			$strSQL = "UPDATE `data_persil_jenis` SET
			`nama`='".fixSQL($this->input->post('nama'))."',
			`ndesc`='".fixSQL($this->input->post('ndesc'))."'
			 WHERE id=".$this->input->post('id');
		}

		$data["db"] = $strSQL;
		$hasil = $this->db->query($strSQL);
		if ($hasil)
		{
			$data["transaksi"] = true;
			$data["pesan"] = "Data Jenis Persil ".fixSQL($this->input->post('nama'))." telah disimpan/diperbarui";
			$_SESSION["success"] = 1;
			$_SESSION["pesan"] = "Data Jenis Persil ".fixSQL($this->input->post('nama'))." telah disimpan/diperbarui";
		}
		else
		{
			$data["transaksi"] = false;
			$data["pesan"] = "ERROR ".$strSQL;
		}
		return $data;
	}

	public function hapus_jenis($id)
	{
		$strSQL = "DELETE FROM `data_persil_jenis` WHERE id = ".$id;
		$hasil = $this->db->query($strSQL);
		if ($hasil)
		{
			$_SESSION["success"] = 1;
			$_SESSION["pesan"] = "Data Jenis Persil telah dihapus";
		}
		else
		{
			$_SESSION["success"] = -1;
		}
	}

	public function impor_persil()
	{
		$this->load->library('Spreadsheet_Excel_Reader');
		$data = new Spreadsheet_Excel_Reader($_FILES['persil']['tmp_name']);

		$sheet = 0;
		$baris = $data->rowcount($sheet_index = $sheet);
		$kolom = $data->colcount($sheet_index = $sheet);

		for ($i=2; $i<=$baris; $i++)
		{
			$nik = $data->val($i, 2, $sheet);
			$upd['id_pend'] = $this->db->select('id')->
						where('nik', $nik)->
						get('tweb_penduduk')->row()->id;
			$upd['nama'] = $data->val($i, 3, $sheet);
			$upd['persil_jenis_id'] = $data->val($i, 4, $sheet);
			$upd['id_clusterdesa'] = $data->val($i, 5, $sheet);
			$upd['luas'] = $data->val($i, 6, $sheet);
			$upd['kelas'] = $data->val($i, 7, $sheet);
			$upd['no_sppt_pbb'] = $data->val($i, 8, $sheet);
			$upd['persil_peruntukan_id'] = $data->val($i, 9, $sheet);

			$outp = $this->db->insert('data_persil',$upd);
		}

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

}
?>