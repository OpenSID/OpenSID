<?php
class Data_persil_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}

	public function autocomplete($cari='')
	{
		$sql = "SELECT
					pemilik_luar AS nik
				FROM
					data_persil
				WHERE pemilik_luar LIKE '%$cari%'
				UNION
				SELECT
					p.nama AS nik
				FROM
					data_persil u
				LEFT JOIN tweb_penduduk p ON
					u.id_pend = p.id
				WHERE p.nama LIKE '%$cari%'";
		$query = $this->db->query($sql);
		$data = $query->result_array();

		$str = autocomplete_data_ke_str($data);
		return $str;
	}

	private function search_sql()
	{
		if (isset($_SESSION['cari']))
		{
			$cari = $_SESSION['cari'];
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' .$kw. '%';
			$search_sql= " AND (u.nama LIKE '$kw' OR p.pemilik_luar like '$kw' OR y.c_desa LIKE '$kw')";
			return $search_sql;
			}
		}

	private function main_sql()
	{
		$sql = " FROM `data_persil` p
				LEFT JOIN tweb_penduduk u ON u.id = p.id_pend
				LEFT JOIN tweb_wil_clusterdesa w ON w.id = p.id_clusterdesa
				LEFT JOIN data_persil_c_desa y ON y.id = p.id_c_desa
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
				elseif($kat == "kelas")
		{
			if ($mana > 0)
			{
				$sql .= " AND (p.kelas=".$mana.") ";
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

	private function main_sql_c_desa()
	{
		$sql = " FROM data_persil_c_desa y
				LEFT JOIN data_persil p ON p.id_c_desa = y.id
				LEFT JOIN tweb_penduduk u ON u.id = y.id_pend
				LEFT JOIN tweb_wil_clusterdesa w ON w.id = u.id_cluster
				LEFT JOIN ref_persil_kelas x ON x.id = p.kelas
				WHERE 1  ";
		return $sql;
	}

	public function paging_c_desa($kat='', $mana=0, $p=1)
	{
		
		$sql = "SELECT COUNT(*) AS jml ".$this->main_sql_c_desa().$this->search_sql();
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

	public function list_c_desa($kat='', $mana=0, $offset, $per_page)
	{
		$data = [];		
		$strSQL = "SELECT y.`id` AS id, y.`c_desa`, p.`id_c_desa`, x.`kode`, u.nik AS nik, p.`id_pend`, p.`id_clusterdesa`,  p.`jenis_pemilik`, u.`nama` as namapemilik, p.pemilik_luar, p.`alamat_luar`,COUNT(p.id_c_desa) AS jumlah,
			p.`lokasi`, w.rt, w.rw, w.dusun, p.rdate as tanggal_daftar,
			SUM(IF(x.`kode`LIke '%S%', p.`luas`,0)) as basah,
			SUM(IF(x.`kode`LIke '%D%', p.`luas`,0)) as kering
		".$this->main_sql_c_desa().$this->search_sql()." 
		GROUP by c_desa";

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
		$persil = $this->list_c_desa_persil($kat, $mana, $offset, $per_page);
		$luar = $this->list_c_desa_persil_luar($kat, $mana, $offset, $per_page);
		$data = array_merge($data, $persil, $luar);
		return $data;
	}

	private function list_c_desa_persil($kat='', $mana=0, $offset, $per_page)
	{
		$data = [];	
		$strSQL = "SELECT p.`id` AS id_persil, p.`id_c_desa` as c_desa, u.nik AS nik, p.`id_pend`, p.`id_clusterdesa`, p.`jenis_pemilik`, u.`nama` as namapemilik, p.pemilik_luar, p.`alamat_luar`,COUNT(p.id_c_desa) AS jumlah, p.`lokasi`, w.rt, w.rw, w.dusun, p.rdate as tanggal_daftar, SUM(IF(x.`kode`LIke '%S%', p.`luas`,0)) as basah, SUM(IF(x.`kode`LIke '%D%', p.`luas`,0)) as kering 
		FROM data_persil p 
		LEFT JOIN tweb_penduduk u ON u.id = p.id_pend 
		LEFT JOIN tweb_wil_clusterdesa w ON w.id = u.id_cluster 
		LEFT JOIN ref_persil_kelas x ON x.id = p.kelas 
		WHERE p.`id_c_desa` = 0 AND p.`id_pend` IS NOT NULL
		GROUP by p.id_pend";
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

	private function list_c_desa_persil_luar($kat='', $mana=0, $offset, $per_page)
	{
		$data = [];	
		$strSQL = "SELECT p.`id` AS id_persil, p.`id_c_desa` as c_desa, u.nik AS nik, p.`id_pend`, p.`id_clusterdesa`, p.`jenis_pemilik`, u.`nama` as namapemilik, p.pemilik_luar, p.`alamat_luar`, p.`lokasi`, w.rt, w.rw, w.dusun, p.rdate as tanggal_daftar FROM data_persil p LEFT JOIN tweb_penduduk u ON u.id = p.id_pend LEFT JOIN tweb_wil_clusterdesa w ON w.id = u.id_cluster LEFT JOIN ref_persil_kelas x ON x.id = p.kelas WHERE p.`id_c_desa` = 0 AND p.`id_pend` IS NULL ";
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
				$data[$i]['jumlah'] = 1; 
			}
			$j++;

		}

		return $data;
	}

	public function get_persil($id)
	{
		$data = false;
		$strSQL = "SELECT p.`id` as id, u.`nik` as nik, y.`c_desa`, p.`jenis_pemilik` as jenis_pemilik, p.`nama` as nopersil, p.id_pend, p.`id_c_desa`, p.`persil_jenis_id`, kelas, x.`kode`, x.`tipe`, p.`id_clusterdesa`, p.`luas`, p.`kelas`, p.`pajak`,  p.pemilik_luar, p.`no_sppt_pbb`, p.`lokasi`, p.`persil_peruntukan_id`, u.nama as namapemilik, w.rt, w.rw, w.dusun,alamat_luar
			FROM `data_persil` p
				LEFT JOIN tweb_penduduk u ON u.id = p.id_pend
				LEFT JOIN tweb_wil_clusterdesa w ON w.id = p.id_clusterdesa
				LEFT JOIN ref_persil_kelas x ON x.id = p.kelas
				LEFT JOIN data_persil_c_desa y ON y.id = p.id_c_desa
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

	public function get_c_desa($id)
	{
		$data = false;
		$strSQL = "SELECT y.`id` AS id, y.`id_pend`, y.`c_desa`, u.nik AS nik, p.`jenis_pemilik`, u.`nama` as namapemilik, p.pemilik_luar, p.`alamat_luar`,w.rt, w.rw, w.dusun
		FROM data_persil_c_desa y
		LEFT JOIN data_persil p ON p.id_c_desa = y.id
		LEFT JOIN tweb_penduduk u ON u.id = y.id_pend
		LEFT JOIN tweb_wil_clusterdesa w ON w.id = u.id_cluster
		WHERE y.id =".$id ;
		$query = $this->db->query($strSQL);
		if ($query->num_rows() > 0)
		{
			$data = $query->row_array();
		}
		else
		{
			$_SESSION["pesan"]= $strSQL;
		}

		if ($data['jenis_pemilik'] == 2)
		{
			$data['namapemilik'] = $data['pemilik_luar'];
			$data['nik'] = "-";
		}
		return $data;
	}

	public function get_c_desa_persil($id)
	{
		$data = false;
		$strSQL = "SELECT p.`id` AS id, p.`id_pend`, u.nik AS nik, p.`jenis_pemilik`, u.`nama` as namapemilik, p.pemilik_luar, p.`alamat_luar`,w.rt, w.rw, w.dusun
		FROM data_persil p 
		LEFT JOIN tweb_penduduk u ON u.id = p.id_pend 
		LEFT JOIN tweb_wil_clusterdesa w ON w.id = u.id_cluster 
		WHERE p.id = ".$id ;
		$query = $this->db->query($strSQL);
		if ($query->num_rows() > 0)
		{
			$data = $query->row_array();
		}
		else
		{
			$_SESSION["pesan"]= $strSQL;
		}

		if ($data['jenis_pemilik'] == 2)
		{
			$data['namapemilik'] = $data['pemilik_luar'];
			$data['nik'] = "-";
		}
		return $data;
	}

	public function get_c_desa_id_pend($id)
	{
		$data = false;
		$strSQL = "SELECT p.`id` AS id, p.`id_pend`, u.nik AS nik, p.`jenis_pemilik`, u.`nama` as namapemilik, p.pemilik_luar, p.`alamat_luar`,w.rt, w.rw, w.dusun
		FROM data_persil p 
		LEFT JOIN tweb_penduduk u ON u.id = p.id_pend 
		LEFT JOIN tweb_wil_clusterdesa w ON w.id = u.id_cluster 
		WHERE p.id_pend = ".$id ;
		$query = $this->db->query($strSQL);
		if ($query->num_rows() > 0)
		{
			$data = $query->row_array();
		}
		else
		{
			$_SESSION["pesan"]= $strSQL;
		}
		return $data;
	}

	public function list_detail_c_desa($mode, $id)
	{
		$data = false;
		$strSQL = "SELECT p.`id` as id, u.`nik` as nik, y.`c_desa`, p.`jenis_pemilik` as jenis_pemilik, p.`nama` as nopersil, p.id_pend, p.`id_c_desa`, p.`persil_jenis_id`, kelas, x.`kode`, p.`id_clusterdesa`, p.`luas`, 
			p.`kelas`, p.`pajak`,  p.pemilik_luar,
			p.`no_sppt_pbb`, p.`lokasi`, p.`persil_peruntukan_id`, u.nama as namapemilik, w.rt, w.rw, w.dusun,alamat_luar
			FROM `data_persil` p
				LEFT JOIN tweb_penduduk u ON u.id = p.id_pend
				LEFT JOIN tweb_wil_clusterdesa w ON w.id = p.id_clusterdesa
				LEFT JOIN ref_persil_kelas x ON x.id = p.kelas
				LEFT JOIN data_persil_c_desa y ON y.id = p.id_c_desa ";

		$strSQL .=	$this->list_detail_c_desa_mode($mode, $id);

		$query = $this->db->query($strSQL);
		if ($query->num_rows()>0)
		{
			$data = $query->result_array();
		}

		if ($data['jenis_pemilik'] == 2)
		{
			$data['namapemilik'] = $data['pemilik_luar'];
			$data['nik'] = "-";
		}
		return $data;
	}

	private function list_detail_c_desa_mode($mode, $id)
	{
		if ($mode === 'id_pend') 
		{
		  $sql =  "WHERE p.id_pend = ".$id;			
		}
		elseif($mode === 'persil') 
		{
			$sql = "WHERE p.id = ".$id;
		}
		else
		{
			$sql = "WHERE p.id_c_desa = ".$id;
		}

		return $sql;
	}

	public function list_persil($kat='', $mana=0, $offset, $per_page)
	{
		$strSQL = "SELECT p.`id` as id, u.nik as nik, y.`c_desa`,p.`nama` as nama, p.`jenis_pemilik`, p.`nama` as nopersil, p.`persil_jenis_id`, p.`id_clusterdesa`, p.`luas`, p.`kelas`, p.pemilik_luar,
			p.rdate as tanggal_daftar,p.`no_sppt_pbb`, p.`lokasi`, p.`persil_peruntukan_id`, u.nama as namapemilik, w.rt, w.rw, w.dusun".$this->filtered_sql($kat, $mana);
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

	public function simpan_persil()
	{
		$hasil = false;
		if (@$_POST["nik"])
		{
			//Update data Persil
			if ($_POST["id"] > 0)
			{
				$data = array();
				$data['nama'] = $_POST["nama"];
				$data['jenis_pemilik'] = $_POST["jenis_pemilik"];
				if ($data['jenis_pemilik'] == 2)
					$data['pemilik_luar'] = strip_tags($_POST['nik']);
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

				$data['alamat_luar'] = strip_tags($_POST["alamat_luar"]);
				$data['id_c_desa'] = $this->db->select('id')->
							where('c_desa', ltrim($_POST['c_desa'], '0'))->
							get('data_persil_c_desa')->row()->id;
				$data['persil_jenis_id'] = $_POST["cid"];
				$data['id_clusterdesa'] = $_POST["pid"];
				$data['persil_peruntukan_id'] = $_POST["sid"];
				$data['luas'] = $_POST["luas"];
				$data['kelas'] = $_POST["kelas"];
				$data['pajak'] = $_POST["pajak"];
				$data['lokasi'] = strip_tags($_POST["lokasi"]);
				$data['no_sppt_pbb'] = strip_tags($_POST["sppt"]);
				$data['userID'] = $_SESSION['user'];
				$outp = $this->db->where('id', $_POST['id'])->update('data_persil', $data);
			}
			else
			{
				//Insert data  C-Desa / Penambahan persil pada C-Desa
				if (is_numeric($_POST["nik"]))
				{
					$data = array();
					// Ambil id penduduk baru
					$data['id_pend'] = $this->db->select('id')->
						where('nik', $_POST['nik'])->
						get('tweb_penduduk')->row()->id;

					//Pengecekan No C-Desa Apakah sama dengan NIK
					$query = $this->db->query("SELECT `id_pend` FROM `data_persil_c_desa` WHERE `id_pend` = ".$data['id_pend']);
					if ($query->num_rows() > 0)
					{
						$arr['c_desa'] = $this->db->select(['id', 'c_desa'])->
							where('id_pend', $data['id_pend'])->
							get('data_persil_c_desa')->result_array();

						if ( $arr['c_desa'][0]['c_desa'] <> ltrim($_POST['c_desa'], '0'))
						{
							$_SESSION["success"] = -1;
							$_SESSION["pesan"] = "NIK Sudah ada, tetapi Nomor C-DESA tidak sama";
						}
						else
						{
							$data['id_c_desa'] = $arr['c_desa'][0]['id'];
							$data['nama'] = $_POST["nama"];
							$data['jenis_pemilik'] = $_POST["jenis_pemilik"];
							$data['persil_jenis_id'] = $_POST["cid"];
							$data['id_clusterdesa'] = $_POST["pid"];
							$data['persil_peruntukan_id'] = $_POST["sid"];
							$data['luas'] = $_POST["luas"];
							$data['kelas'] = $_POST["kelas"];
							$data['pajak'] = $_POST["pajak"];
							$data['lokasi'] = $_POST["lokasi"];
							$data['no_sppt_pbb'] = $_POST["sppt"];
							$data['userID'] = $_SESSION['user'];
							$outp = $this->db->insert('data_persil', $data);
							$data_mutasi['id_persil'] = $this->db->insert_id();
							$data_mutasi['jenis_mutasi'] = strip_tags($_POST["jenis_mutasi"]);
							$data_mutasi['tanggalmutasi'] = tgl_indo_in($_POST["tanggalmutasi"]);
							$data_mutasi['sebabmutasi'] = strip_tags($_POST["sebabmutasi"]);
							$data_mutasi['luasmutasi'] = strip_tags($_POST["luasmutasi"]);
							$data_mutasi['no_c_desa'] = strip_tags($_POST["no_c_desa"]);
							$data_mutasi['keterangan'] = strip_tags($_POST["ket"]);
							$outp = $this->db->insert('data_persil_mutasi', $data_mutasi);
						}
					} 
					else 
					{
						//penambahan C-Desa Awal
						$datac['id_pend'] =$data['id_pend'];
						$datac['c_desa'] = ltrim($_POST['c_desa'], '0');
						$outp = $this->db->insert('data_persil_c_desa', $datac);
						$data['id_c_desa'] = $this->db->insert_id();
						$data['nama'] = $_POST["nama"];
						$data['jenis_pemilik'] = $_POST["jenis_pemilik"];
						$data['persil_jenis_id'] = $_POST["cid"];
						$data['id_clusterdesa'] = $_POST["pid"];
						$data['persil_peruntukan_id'] = $_POST["sid"];
						$data['luas'] = $_POST["luas"];
						$data['kelas'] = $_POST["kelas"];
						$data['pajak'] = $_POST["pajak"];
						$data['lokasi'] = strip_tags($_POST["lokasi"]);
						$data['no_sppt_pbb'] = strip_tags($_POST["sppt"]);
						$data['userID'] = $_SESSION['user'];
						$outp = $this->db->insert('data_persil', $data);
						$data_mutasi['id_persil'] = $this->db->insert_id();
						$data_mutasi['jenis_mutasi'] = strip_tags($_POST["jenis_mutasi"]);
						$data_mutasi['tanggalmutasi'] = tgl_indo_in($_POST["tanggalmutasi"]);
						$data_mutasi['sebabmutasi'] = strip_tags($_POST["sebabmutasi"]);
						$data_mutasi['luasmutasi'] = strip_tags($_POST["luasmutasi"]);
						$data_mutasi['no_c_desa'] = strip_tags($_POST["no_c_desa"]);
						$data_mutasi['keterangan'] = strip_tags($_POST["ket"]);
						$outp = $this->db->insert('data_persil_mutasi', $data_mutasi);
						
					}					
				}
				else
				{
					//Penambahan data Luar Desa
					$data = array();
					$datac['c_desa'] = ltrim($_POST['c_desa'], '0');

					if ($_POST['id_c_desa'] > 0)
						$data['id_c_desa'] = $_POST['id_c_desa'];
					else 
					{
						$outp = $this->db->insert('data_persil_c_desa', $datac);
						$data['id_c_desa'] = $this->db->insert_id();
					}
					$data['nama'] = strip_tags($_POST["nama"]);
					$data['jenis_pemilik'] = $_POST["jenis_pemilik"];
					$data['pemilik_luar'] = strip_tags($_POST['nik']);
					$data['alamat_luar'] = strip_tags($_POST["alamat_luar"]);
					$data['persil_jenis_id'] = $_POST["cid"];
					$data['id_clusterdesa'] = $_POST["pid"];
					$data['persil_peruntukan_id'] = $_POST["sid"];
					$data['luas'] = $_POST["luas"];
					$data['kelas'] = $_POST["kelas"];
					$data['pajak'] = $_POST["pajak"];
					$data['lokasi'] = strip_tags($_POST["lokasi"]);
					$data['no_sppt_pbb'] = strip_tags($_POST["sppt"]);
					$data['userID'] = $_SESSION['user'];
					$outp = $this->db->insert('data_persil', $data);
					$data_mutasi['id_persil'] = $this->db->insert_id();
					$data_mutasi['jenis_mutasi'] = strip_tags($_POST["jenis_mutasi"]);
					$data_mutasi['tanggalmutasi'] = tgl_indo_in($_POST["tanggalmutasi"]);
					$data_mutasi['sebabmutasi'] = strip_tags($_POST["sebabmutasi"]);
					$data_mutasi['luasmutasi'] = strip_tags($_POST["luasmutasi"]);
					$data_mutasi['no_c_desa'] = strip_tags($_POST["no_c_desa"]);
					$data_mutasi['keterangan'] = strip_tags($_POST["ket"]);
					$outp = $this->db->insert('data_persil_mutasi', $data_mutasi);
				}
			}

			if ($outp)
			{
				$_SESSION["success"] = 1;
				$_SESSION["pesan"] = "Data Persil telah DISIMPAN";
				$hasil["hasil"] = true;
				$hasil['id_c_desa'] = $data['id_c_desa'];
			}
		}
		else
		{
			$_SESSION["success"] = -1;
			$_SESSION["pesan"] = "Formulir belum/tidak terisi dengan benar";
		}
		
		return $hasil;
	}

	public function simpan_mutasi()
	{
		if ($_POST["id"] > 0)
		{
			$data_mutasi['id_persil'] = $_POST["id_persil"];
			$data_mutasi['jenis_mutasi'] = strip_tags($_POST["jenis_mutasi"]);
			$data_mutasi['tanggalmutasi'] = tgl_indo_in($_POST["tanggalmutasi"]);
			$data_mutasi['sebabmutasi'] = strip_tags($_POST["sebabmutasi"]);
			$data_mutasi['luasmutasi'] = strip_tags($_POST["luasmutasi"]);
			$data_mutasi['no_c_desa'] = strip_tags($_POST["no_c_desa"]);
			$data_mutasi['keterangan'] = strip_tags($_POST["ket"]);

			$outp = $this->db->where('id', $_POST['id'])->update('data_persil_mutasi', $data_mutasi);
		}
		else
		{
			if ($_POST["id_persil"] > 0)
			{
				$data_mutasi['id_persil'] = $_POST["id_persil"];
				$data_mutasi['jenis_mutasi'] = strip_tags($_POST["jenis_mutasi"]);
				$data_mutasi['tanggalmutasi'] = tgl_indo_in($_POST["tanggalmutasi"]);
				$data_mutasi['sebabmutasi'] = strip_tags($_POST["sebabmutasi"]);
				$data_mutasi['luasmutasi'] = strip_tags($_POST["luasmutasi"]);
				$data_mutasi['no_c_desa'] = strip_tags($_POST["no_c_desa"]);
				$data_mutasi['keterangan'] = strip_tags($_POST["ket"]);
				$outp = $this->db->insert('data_persil_mutasi', $data_mutasi);
			}
		}
		if ($outp)
			{
				$_SESSION["success"] = 1;
				$_SESSION["pesan"] = "Data Persil telah DISIMPAN";
				$data["hasil"] = true;
				$data["id"]= $_POST["id_persil"];
				$data['jenis'] = $_POST["jenis"];
			}
		return $data;
	}

	public function simpan_c_desa()
	{
		$data = array();
		if ($_POST['id_persil'] > 0)
		{
			$datac['c_desa'] = ltrim($_POST['c_desa'], '0');
			$query = $this->db->get_where('data_persil_c_desa', array('c_desa' => $datac['c_desa']));
			if ($query->num_rows() <= 0)
			{
				$outp = $this->db->insert('data_persil_c_desa', $datac);
				$data['id_c_desa'] = $this->db->insert_id();
			}
			else
			{
				$data['id_c_desa'] = $this->db->result()->id;
			}
			$outp = $this->db->where('id', $_POST['id_persil'])->update('data_persil', $data);
		}
		elseif ($_POST['id_pend'] > 0)
		{
			$datac['id_pend'] =$_POST['id_pend'];
			$datac['c_desa'] = ltrim($_POST['c_desa'], '0');
			$outp = $this->db->insert('data_persil_c_desa', $datac);
			$data['id_c_desa'] = $this->db->insert_id();
			$outp = $this->db->where('id_pend', $_POST['id_pend'])->update('data_persil', $data);
		}
		else
		{
			$data['c_desa'] = ltrim($_POST['c_desa'], '0');
			$outp = $this->db->where('id', $_POST['id_c_desa'])->update('data_persil_c_desa', $data);
		}

		if ($outp)
		{
			$_SESSION["success"] = 1;
			$_SESSION["pesan"] = "Data Persil telah DISIMPAN";
			$hasil = true;
		}
		else
		{
			$_SESSION["success"] = -1;
			$_SESSION["pesan"] = "Gagal Menyimpan data";
		}
	}

	public function hapus_c_desa($id, $mana)
	{
		if($mana === 'id_pend')
		{
			$strSQL = "DELETE FROM  data_persil WHERE id_pend = ".$id;
			$hasil = $this->db->query($strSQL);
		}	
		else
		{
			$strSQL = "SELECT * FROM data_persil WHERE id_c_desa = ".$id;
			$query = $this->db->query($strSQL);
			if ($query->num_rows() > 0)
			{
				$strSQL = "DELETE  a, b FROM data_persil_c_desa a , data_persil b WHERE a.id = ".$id." AND b.id_c_desa = ".$id;
				$hasil = $this->db->query($strSQL);
			}
			else
			{
				$strSQL = "DELETE FROM data_persil_c_desa WHERE id = ".$id;
				$hasil = $this->db->query($strSQL);
			}
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
			$data["pesan"] = "Data Peruntukan Tanah ".fixSQL($this->input->post('nama'))." telah disimpan/diperbarui";
			$_SESSION["success"] = 1;
			$_SESSION["pesan"] = "Data Peruntukan Tanah ".fixSQL($this->input->post('nama'))." telah disimpan/diperbarui";
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
			$_SESSION["pesan"] = "Data Peruntukan Tanah telah dihapus";
		}
		else
		{
			$_SESSION["success"] = -1;
		}
	}

	public function list_persil_jenis()
	{
		$data = $this->db->order_by('nama')
			->get('data_persil_jenis')
			->result_array();
		$result = array_combine(array_column($data, 'id'), $data);
		return $result;
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
			$strSQL = "INSERT INTO `data_persil_jenis`(`nama`,`ndesc`) VALUES('".strtoupper(fixSQL($this->input->post('nama')))."','".fixSQL($this->input->post('ndesc'))."')";
		}
		else
		{
			$strSQL = "UPDATE `data_persil_jenis` SET
			`nama`='".strtoupper(fixSQL($this->input->post('nama')))."',
			`ndesc`='".fixSQL($this->input->post('ndesc'))."'
			 WHERE id=".$this->input->post('id');
		}

		$data["db"] = $strSQL;
		$hasil = $this->db->query($strSQL);
		if ($hasil)
		{
			$data["transaksi"] = true;
			$data["pesan"] = "Data Jenis Tanah ".fixSQL($this->input->post('nama'))." telah disimpan/diperbarui";
			$_SESSION["success"] = 1;
			$_SESSION["pesan"] = "Data Jenis Tanah ".fixSQL($this->input->post('nama'))." telah disimpan/diperbarui";
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
			$_SESSION["pesan"] = "Data Jenis Tanah telah dihapus";
		}
		else
		{
			$_SESSION["success"] = -1;
		}
	}



	public function list_persil_kelas($table='')
	{
		if($table)
		{	$data =$this->db->order_by('kode') 
						->get_where('ref_persil_kelas', array('tipe' => $table))
						->result_array();
			$data = array_combine(array_column($data, 'id'), $data);
		}
		else
		{
			$data = $this->db->order_by('kode')
			->get('ref_persil_kelas')
			->result_array();
			$data = array_combine(array_column($data, 'id'), $data);
		}
		
		return $data;
	}

	public function list_persil_jenis_mutasi($table='')
	{
		$data = $this->db->order_by('id')
		->get('ref_persil_jenis_mutasi')
		->result_array();
		$data = array_combine(array_column($data, 'id'), $data);
		
		return $data;
	}

	public function list_persil_sebab_mutasi($table='')
	{
		$data = $this->db->order_by('id')
		->get('ref_persil_mutasi')
		->result_array();
		$data = array_combine(array_column($data, 'id'), $data);
		
		return $data;
	}

	public function list_persil_mutasi($id=0)
	{
		$this->db->select('m.*, p.nama as sebabmutasi')
			->from('data_persil_mutasi m')
			->join('ref_persil_mutasi p','m.sebabmutasi = p.id', 'left')
			->where('m.id_persil',$id);
		$data = $this->db->get()->result_array();

		$data = array_combine(array_column($data, 'id'), $data);		
		return $data;
	}

	public function get_persil_mutasi($id=0)
	{
		$this->db->select('m.*, p.nama, c.c_desa')
			->from('data_persil_mutasi m')
			->join('data_persil p','m.id = p.id', 'left')
			->join('data_persil_c_desa c','p.id_c_desa = c.id', 'left')
			->where('m.id', $id);
		$data = $this->db->get()->row_array();

		$data['tanggalmutasi'] = tgl_indo_out($data['tanggalmutasi']);		
		return $data;
	}

	public function hapus_mutasi($id)
	{
		$strSQL = "DELETE FROM `data_persil_mutasi` WHERE id = ".$id;
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

	public function get_persil_kelas($id=0)
	{
		$data = false;
		$strSQL = "SELECT id, kode, tipe, ndesc FROM ref_persil_kelas WHERE id = ".$id;
		$query = $this->db->query($strSQL);
		if ($query->num_rows() > 0)
		{
			$data = array();
			$data[$id] = $query->row_array();
		}
		return $data;
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

		status_sukses($outp); //Tampilkan Pesan
	}

	public function get_c_cetak($id, $tipe='')
	{
		$data = false;
		$strSQL = "SELECT p.`id` as id, u.`nik` as nik, y.`c_desa`, p.`jenis_pemilik` as jenis_pemilik, p.`nama` as nopersil, p.id_pend, p.`id_c_desa`, p.`persil_jenis_id`, kelas, x.`kode`, p.`id_clusterdesa`, p.`luas`, 
			p.`kelas`, p.`pajak`,  p.pemilik_luar,
			p.`no_sppt_pbb`, p.`lokasi`, p.`persil_peruntukan_id`, u.nama as namapemilik, w.rt, w.rw, w.dusun,alamat_luar, m.jenis_mutasi, m.tanggalmutasi, rm.nama as sebabmutasi, m.luasmutasi, m.no_c_desa, m.keterangan
			FROM `data_persil` p
				LEFT JOIN tweb_penduduk u ON u.id = p.id_pend
				LEFT JOIN tweb_wil_clusterdesa w ON w.id = p.id_clusterdesa
				LEFT JOIN ref_persil_kelas x ON x.id = p.kelas
				LEFT JOIN data_persil_c_desa y ON y.id = p.id_c_desa
				LEFT JOIN data_persil_mutasi m ON m.id_persil = p.id
				LEFT JOIN ref_persil_mutasi rm ON m.sebabmutasi = rm.id

			 WHERE p.id_c_desa = ".$id." AND x.kode LIKE '%".$tipe."%'";
		$query = $this->db->query($strSQL);
		if ($query->num_rows()>0)
		{
			$data = $query->result_array();
		}

		if ($data['jenis_pemilik'] == 2)
		{
			$data['namapemilik'] = $data['pemilik_luar'];
			$data['nik'] = "-";
		}
		$hasil=[];
		$count= count($data)-1;
		for ($x = 0; $x <= $count; $x++)
		{
			$hasil[]= $data[$x];
			if( $data[$x]['id']!= $data[$x+1]['id'])
				$hasil[]=[];
		}
		return $hasil;
	}
}
?>