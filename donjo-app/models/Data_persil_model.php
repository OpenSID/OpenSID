<?php
define("NIK_LUAR_DESA", 999);

class Data_persil_model extends CI_Model{
	function __construct(){
		$this->load->database();
	}
	function autocomplete(){
		$sql = "SELECT nik FROM data_persil
					UNION SELECT p.nama AS nik FROM data_persil u LEFT JOIN tweb_penduduk p ON u.nik = p.nik";
		$query = $this->db->query($sql);
		$data = $query->result_array();

		$i=0;
		$outp='';
		while($i<count($data)){
			$outp .= ",'" .$data[$i]['nik']. "'";
			$i++;
		}
		$outp = strtolower(substr($outp, 1));
		$outp = '[' .$outp. ']';
		return $outp;
	}
	function search_sql(){
		if(isset($_SESSION['cari'])){
		$cari = $_SESSION['cari'];
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' .$kw. '%';
			$search_sql= " AND (u.nama LIKE '$kw' OR p.nik LIKE '$kw')";
			return $search_sql;
			}
		}

	private function _main_sql(){
		$sql = " FROM `data_persil` p
				LEFT JOIN tweb_penduduk u ON u.nik = p.nik
				LEFT JOIN tweb_wil_clusterdesa w ON w.id=p.id_clusterdesa
			 WHERE 1 ";
		return $sql;
	}

	private function _filtered_sql($kat='',$mana=0){
		$sql = $this->_main_sql();
		if($kat =="jenis"){
			if($mana > 0){
				$sql .= " AND (p.persil_jenis_id=".$mana.") ";
			}
		}elseif($kat =="peruntukan"){
			if($mana > 0){
				$sql .= " AND (p.persil_peruntukan_id=".$mana.") ";
			}
		}
		$sql .= $this->search_sql();
		return $sql;
	}

	function paging($kat='',$mana=0,$p=1){
		$sql      = "SELECT COUNT(*) AS jml".$this->_filtered_sql($kat,$mana);
		$query    = $this->db->query($sql);
		$row      = $query->row_array();
		$jml_data = $row['jml'];

		$this->load->library('paging');
		$cfg['page']     = $p;
		$cfg['per_page'] = $_SESSION['per_page'];
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);

		return $this->paging;
	}

	function list_persil($kat='',$mana=0,$offset,$per_page){
		$data = false;
		$strSQL = "SELECT p.`id` as id, p.`nik` as nik, p.`nama` as nopersil, p.`persil_jenis_id`, p.`id_clusterdesa`, p.`luas`, p.`kelas`, p.jenis_pemilik, p.pemilik_luar,
			p.rdate as tanggal_daftar,
			p.`no_sppt_pbb`, p.`persil_peruntukan_id`, u.nama as namapemilik, w.rt, w.rw, w.dusun".$this->_filtered_sql($kat,$mana);
		$strSQL .= " LIMIT ".$offset.",".$per_page;
		$query = $this->db->query($strSQL);
		if($query->num_rows()>0){
			$data = $query->result_array();
		}else{
			$_SESSION["pesan"]= $strSQL;
		}

		$i=0;
		$j=$offset;
		while($i<count($data)){
			$data[$i]['no']=$j+1;
			if ($data[$i]['jenis_pemilik'] == '2'){
				// Pemilik luar desa
				$data[$i]['namapemilik'] = $data[$i]['pemilik_luar'];
				$data[$i]['nik'] = "-";
			}
			$i++;
			$j++;
		}
		return $data;
	}

	function get_persil($id){
		$data = false;
		$strSQL = "SELECT p.`id` as id, p.`nik` as nik, p.`nama` as nopersil,
			p.`persil_jenis_id`, p.`id_clusterdesa`, p.`luas`, p.`kelas`, p.jenis_pemilik, p.pemilik_luar,
			p.`no_sppt_pbb`, p.`persil_peruntukan_id`, u.nama as namapemilik, w.rt, w.rw, w.dusun,alamat_luar
			FROM `data_persil` p
				LEFT JOIN tweb_penduduk u ON u.nik = p.nik
				LEFT JOIN tweb_wil_clusterdesa w ON w.id=p.id_clusterdesa
			 WHERE p.id=".$id;
		$query = $this->db->query($strSQL);
		if($query->num_rows()>0){
			$data = $query->row_array();
		}
		if ($data['jenis_pemilik'] == '2'){
			// Pemilik luar desa
			$data['namapemilik'] = $data['pemilik_luar'];
			$data['nik'] = "-";
		}
		return $data;
	}

	public function simpan_persil(){
		if (empty($_POST["nik"]))
		{
			$_SESSION["success"] = -1;
			$_SESSION["pesan"] = "Formulir belum/tidak terisi dengan benar";
			return false;
		}

		$data = $this->siapkanDataPersil();
		if ($_POST["id"]>0)
		{
			$hasil = $this->db->where('id', $_POST["id"])->update('data_persil', $data);
		}
		else
		{
			$hasil = $this->db->insert('data_persil', $data);
		}

		if ($hasil)
		{
			$_SESSION["success"] = 1;
			$_SESSION["pesan"] = "Data Persil telah DISIMPAN";
		}
		else
		{
			$_SESSION["success"] = -1;
		}
		return $hasil;
	}

	private function siapkanDataPersil()
	{
		$data = array();
		$data['nama'] = $_POST["nama"];
		$data['persil_jenis_id'] = $_POST["cid"];
		$data['id_clusterdesa'] = $_POST["pid"];
		$data['persil_peruntukan_id'] = $_POST["sid"];
		$data['luas'] = $_POST["luas"];
		$data['kelas'] = $_POST["kelas"];
		$data['no_sppt_pbb'] = $_POST["sppt"];
		$data['userID'] = $_SESSION['user'];
		$data['jenis_pemilik'] = $_POST['jenis_pemilik'];
		if ($_POST["jenis_pemilik"] == '2')
		{
			// Pemilik luar desa
			$data['nik'] = NIK_LUAR_DESA;
			$data['pemilik_luar'] = $_POST["pemilik_luar"];
			$data['alamat_luar'] = $_POST["alamat_luar"];
		} else
		{
			// Pemilik desa
			$data['nik'] = $_POST["nik"];
		}
		return $data;
	}

	public function hapus_persil($id)
	{
		$strSQL = "DELETE FROM `data_persil` WHERE id=".$id;
		$hasil = $this->db->query($strSQL);
		if($hasil)
		{
			$_SESSION["success"] = 1;
			$_SESSION["pesan"] = "Data Persil telah dihapus";
		}else{
			$_SESSION["success"] = -1;
			$_SESSION["pesan"] = "Gagal menghapus data persil";
		}
	}
	function list_dusunrwrt(){
		$strSQL = "SELECT `id`,`rt`,`rw`,`dusun` FROM `tweb_wil_clusterdesa` WHERE (`rt`>0) ORDER BY `dusun`";
		$query = $this->db->query($strSQL);
		return $query->result_array();
	}
	function get_penduduk($id){
		$strSQL = "SELECT p.nik,p.nama,k.no_kk,w.rt,w.rw,w.dusun FROM tweb_penduduk p
			LEFT JOIN tweb_keluarga k ON k.id=p.id_kk
			LEFT JOIN tweb_wil_clusterdesa w ON w.id=p.id_cluster
			WHERE p.nik='".fixSQL($id)."'";
		$query = $this->db->query($strSQL);
		$data = "";
		$data=$query->row_array();
		return $data;
	}

	function list_penduduk(){
		$strSQL = "SELECT p.nik,p.nama,k.no_kk,w.rt,w.rw,w.dusun FROM tweb_penduduk p
			LEFT JOIN tweb_keluarga k ON k.id=p.id_kk
			LEFT JOIN tweb_wil_clusterdesa w ON w.id=p.id_cluster
			WHERE 1 ORDER BY nama";
		$query = $this->db->query($strSQL);
		$data = "";
		$data=$query->result_array();
		if($query->num_rows() > 0){
			$i=0;$j=0;
			while($i<count($data)){
				if($data[$i]['nik']!=""){
					$data1[$j]['id']=$data[$i]['nik'];
					$data1[$j]['nik']=$data[$i]['nik'];
					$data1[$j]['nama']=strtoupper($data[$i]['nama'])." [NIK: ".$data[$i]['nik']."] / [NO KK: ".$data[$i]["no_kk"]."]";
					$data1[$j]['info']= "RT/RW ". $data[$i]['rt']."/".$data[$i]['rw']." - ".strtoupper($data[$i]['dusun']);
					$j++;
				}
				$i++;
			}
			$hasil2 = $data1;
		}else{
			$hasil2 = false;
		}
		return $hasil2;
	}
	function list_persil_peruntukan(){
		$data =false;
		$strSQL = "SELECT id,nama,ndesc FROM data_persil_peruntukan WHERE 1";
		$query = $this->db->query($strSQL);
		if($query->num_rows()>0){
			$data = array();
			foreach ($query->result() as $row){
				$data[$row->id] = array($row->nama,$row->ndesc);
			}
		}
		return $data;
	}
	function get_persil_peruntukan($id=0){
		$data =false;
		$strSQL = "SELECT id,nama,ndesc FROM data_persil_peruntukan WHERE id=".$id;
		$query = $this->db->query($strSQL);
		if($query->num_rows()>0){
			$data = array();
			$data[$id] = $query->row_array();
		}
		return $data;
	}
	public function update_persil_peruntukan(){
		if($this->input->post('id') == 0){
			$strSQL = "INSERT INTO `data_persil_peruntukan`(`nama`,`ndesc`) VALUES('".fixSQL($this->input->post('nama'))."','".fixSQL($this->input->post('ndesc'))."')";
		}else{
			$strSQL = "UPDATE `data_persil_peruntukan` SET
			`nama`='".fixSQL($this->input->post('nama'))."',
			`ndesc`='".fixSQL($this->input->post('ndesc'))."'
			 WHERE id=".$this->input->post('id');
		}

		$data["db"] = $strSQL;
		$hasil = $this->db->query($strSQL);
		if($hasil){
			$data["transaksi"] = true;
			$data["pesan"] = "Data Peruntukan Persil ".fixSQL($this->input->post('nama'))." telah disimpan/diperbarui";
			$_SESSION["success"] = 1;
			$_SESSION["pesan"] = "Data Peruntukan Persil ".fixSQL($this->input->post('nama'))." telah disimpan/diperbarui";
		}else{
			$data["transaksi"] = false;
			$data["pesan"] = "ERROR ".$strSQL;
		}
		return $data;
	}
	public function hapus_peruntukan($id){
		$strSQL = "DELETE FROM `data_persil_peruntukan` WHERE id=".$id;
		$hasil = $this->db->query($strSQL);
		if($hasil){
			$_SESSION["success"] = 1;
			$_SESSION["pesan"] = "Data Peruntukan Persil telah dihapus";
		}else{
			$_SESSION["success"] = -1;
		}
	}
	function list_persil_jenis(){
		$data =false;
		$strSQL = "SELECT id,nama,ndesc FROM data_persil_jenis WHERE 1";
		$query = $this->db->query($strSQL);
		if($query->num_rows()>0){
			$data = array();
			foreach ($query->result() as $row){
				$data[$row->id] = array($row->nama,$row->ndesc);
			}
		}
		return $data;
	}
	function get_persil_jenis($id=0){
		$data =false;
		$strSQL = "SELECT id,nama,ndesc FROM data_persil_jenis WHERE id=".$id;
		$query = $this->db->query($strSQL);
		if($query->num_rows()>0){
			$data = array();
			$data[$id] = $query->row_array();
		}
		return $data;
	}
	public function update_persil_jenis(){
		if($this->input->post('id') == 0){
			$strSQL = "INSERT INTO `data_persil_jenis`(`nama`,`ndesc`) VALUES('".fixSQL($this->input->post('nama'))."','".fixSQL($this->input->post('ndesc'))."')";
		}else{
			$strSQL = "UPDATE `data_persil_jenis` SET
			`nama`='".fixSQL($this->input->post('nama'))."',
			`ndesc`='".fixSQL($this->input->post('ndesc'))."'
			 WHERE id=".$this->input->post('id');
		}

		$data["db"] = $strSQL;
		$hasil = $this->db->query($strSQL);
		if($hasil){
			$data["transaksi"] = true;
			$data["pesan"] = "Data Jenis Persil ".fixSQL($this->input->post('nama'))." telah disimpan/diperbarui";
			$_SESSION["success"] = 1;
			$_SESSION["pesan"] = "Data Jenis Persil ".fixSQL($this->input->post('nama'))." telah disimpan/diperbarui";
		}else{
			$data["transaksi"] = false;
			$data["pesan"] = "ERROR ".$strSQL;
		}
		return $data;
	}
	public function hapus_jenis($id){
		$strSQL = "DELETE FROM `data_persil_jenis` WHERE id=".$id;
		$hasil = $this->db->query($strSQL);
		if($hasil){
			$_SESSION["success"] = 1;
			$_SESSION["pesan"] = "Data Jenis Persil telah dihapus";
		}else{
			$_SESSION["success"] = -1;
		}
	}
}
?>