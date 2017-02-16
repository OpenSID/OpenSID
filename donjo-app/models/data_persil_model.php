<?php
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
	function list_persil($apa='',$mana=0,$page=1){
		$data = false;
		$limit = 20;
		$offset = ($page - 1) * $limit;
		$strSQL = "SELECT p.`id` as id, p.`nik` as nik, p.`nama` as nopersil, p.`persil_jenis_id`, p.`id_clusterdesa`, p.`luas`, p.`kelas`, 
			p.`no_sppt_pbb`, p.`persil_peruntukan_id`, u.nama as namapemilik, w.rt, w.rw, w.dusun 
			FROM `data_persil` p 
				LEFT JOIN tweb_penduduk u ON u.nik = p.nik 
				LEFT JOIN tweb_wil_clusterdesa w ON w.id=p.id_clusterdesa
			 WHERE ((1) ";
		if($apa =="jenis"){
			if($mana > 0){
				$strSQL .= " AND (p.persil_jenis_id=".$mana.") ";
			}
		}elseif($apa =="peruntukan"){
			if($mana > 0){
				$strSQL .= " AND (p.persil_peruntukan_id=".$mana.") ";
			}
		}
		
		$strSQL .= $this->search_sql(); 
		$strSQL .= ") LIMIT ".$offset.",".$limit;
		$query = $this->db->query($strSQL);
		if($query->num_rows()>0){
			$data = $query->result_array();
		}else{
			$_SESSION["pesan"]= $strSQL;
		}
		
		$i=0;
		
		while($i<count($data)){
			
			if(!is_numeric($data[$i]['nik']) AND $data[$i]['nik']<>''){
				$data[$i]['namapemilik'] = $data[$i]['nik'];
				$data[$i]['nik'] = "-";
			}
			$i++;
			
		}
		return $data;
	}
	function get_persil($id){
		$data = false;
		$strSQL = "SELECT p.`id` as id, p.`nik` as nik, p.`nama` as nopersil, 
			p.`persil_jenis_id`, p.`id_clusterdesa`, p.`luas`, p.`kelas`,
			p.`no_sppt_pbb`, p.`persil_peruntukan_id`, u.nama as namapemilik, w.rt, w.rw, w.dusun,alamat_ext 
			FROM `data_persil` p 
				LEFT JOIN tweb_penduduk u ON u.nik = p.nik 
				LEFT JOIN tweb_wil_clusterdesa w ON w.id=p.id_clusterdesa
			 WHERE p.id=".$id;
		$query = $this->db->query($strSQL);
		if($query->num_rows()>0){
			$data = $query->row_array();
		}
		
		if(!is_numeric($data['nik'])){
			$data['namapemilik'] = $data['nik'];
			$data['nik'] = "-";
		}
		return $data;
	}
	function simpan_persil(){
		$hasil = false;
		if(@$_POST["nik"]){
			if($_POST["id"]>0){
				$strSQL = "UPDATE data_persil SET 
				 `nik`='".fixSQL($_POST["nik"])."',
				 `nama`='".fixSQL($_POST["nama"])."',
				 `persil_jenis_id`='".fixSQL($_POST["cid"])."', 
				 `id_clusterdesa`='".fixSQL($_POST["pid"])."', 
				 `persil_peruntukan_id`='".fixSQL($_POST["sid"])."', 
				 `luas`='".fixSQL($_POST["luas"])."', 
				 `kelas`='".fixSQL($_POST["kelas"])."', 
				 `no_sppt_pbb`='".fixSQL($_POST["sppt"])."', 
				 `userID`='".$_SESSION['user']."' 
				 WHERE id=".fixSQL($_POST["id"]);
			}else{
				if(is_numeric($_POST["nik"])){
					$strSQL = "INSERT INTO data_persil(`nik`,`nama`, `persil_jenis_id`, `id_clusterdesa`, `persil_peruntukan_id`,
					`kelas`,`luas`, `no_sppt_pbb`, `userID`) VALUES('".fixSQL($_POST["nik"])."','".fixSQL($_POST["nama"])."','".fixSQL($_POST["cid"])."',
					'".fixSQL($_POST["pid"])."','".fixSQL($_POST["sid"])."','".fixSQL($_POST["kelas"])."','".fixSQL($_POST["luas"])."',
					'".fixSQL($_POST["sppt"])."','".fixSQL($_SESSION['user'])."')";
				}else{
					$strSQL = "INSERT INTO data_persil(`nik`,`nama`,`alamat_ext`, `persil_jenis_id`, `id_clusterdesa`, `persil_peruntukan_id`,
					`kelas`,`luas`, `no_sppt_pbb`, `userID`) VALUES('".fixSQL($_POST["nik"])."','".fixSQL($_POST["nama"])."','".fixSQL($_POST["alamat_ext"])."','".fixSQL($_POST["cid"])."',
					'".fixSQL($_POST["pid"])."','".fixSQL($_POST["sid"])."','".fixSQL($_POST["kelas"])."','".fixSQL($_POST["luas"])."',
					'".fixSQL($_POST["sppt"])."','".fixSQL($_SESSION['user'])."')";
				}
			}
			if($this->db->query($strSQL)){
				$_SESSION["success"] = 1;
				$_SESSION["pesan"] = "Data Persil telah DISIMPAN";
				$hasil = true;
			}
		}else{
			$_SESSION["success"] = -1;
			$_SESSION["pesan"] = "Formulir belum/tidak terisi dengan benar";
		}
		return $hasil;
	}
	public function hapus_persil($id){
		$strSQL = "DELETE FROM `data_persil` WHERE id=".$id;
		$hasil = $this->db->query($strSQL);
		if($hasil){
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