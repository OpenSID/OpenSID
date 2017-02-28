<?php

class kelompok_model extends CI_Model{

	function __construct(){
		parent::__construct();
	}

	function autocomplete(){
		$sql   = "SELECT nama FROM kelompok";
		$query = $this->db->query($sql);
		$data  = $query->result_array();

		$i=0;
		$outp='';
		while($i<count($data)){
			$outp .= ",'" .$data[$i]['nama']. "'";
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
			$search_sql= " AND (u.nama LIKE '$kw' OR u.nama LIKE '$kw')";
			return $search_sql;
			}
		}

	function filter_sql(){
		if(isset($_SESSION['filter'])){
			$kf = $_SESSION['filter'];
			$filter_sql= " AND u.id_master = $kf";
		return $filter_sql;
		}
	}

	function paging($p=1,$o=0){

		$sql      = "SELECT COUNT(id) AS id FROM kelompok u WHERE 1";
		$sql     .= $this->search_sql();
		$sql .= $this->filter_sql();
		//$sql .= $this->state_sql();
		$query    = $this->db->query($sql);
		$row      = $query->row_array();
		$jml_data = $row['id'];

		$this->load->library('paging');
		$cfg['page']     = $p;
		$cfg['per_page'] = $_SESSION['per_page'];
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);

		return $this->paging;
	}

	function list_data($o=0,$offset=0,$limit=500){

		//Ordering SQL
		switch($o){
			case 1: $order_sql = ' ORDER BY u.nama'; break;
			case 2: $order_sql = ' ORDER BY u.nama DESC'; break;
			case 3: $order_sql = ' ORDER BY u.nama'; break;
			case 4: $order_sql = ' ORDER BY u.nama DESC'; break;
			case 5: $order_sql = ' ORDER BY g.nama'; break;
			case 6: $order_sql = ' ORDER BY g.nama DESC'; break;
			default:$order_sql = ' ORDER BY u.nama';
		}

		//Paging SQL
		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;

		//Main Query
		$sql   = "SELECT u.*,s.kelompok AS master,c.nama AS ketua FROM kelompok u LEFT JOIN kelompok_master s ON u.id_master = s.id LEFT JOIN tweb_penduduk c ON u.id_ketua = c.id  WHERE 1 ";

		$sql .= $this->search_sql();
		$sql .= $this->filter_sql();
		//$sql .= $this->state_sql();
		$sql .= $order_sql;
		$sql .= $paging_sql;

		$query = $this->db->query($sql);
		$data=$query->result_array();

		//Formating Output
		$i=0;
		$j=$offset;
		while($i<count($data)){
			$data[$i]['no']=$j+1;
			$i++;
			$j++;
		}
		return $data;
	}

	function insert(){
		$data = $_POST;
		$datax = array();
		$outp = $this->db->insert('kelompok',$data);

		$sql   = "SELECT * FROM kelompok WHERE 1 ORDER BY id DESC LIMIT 1";
		$query = $this->db->query($sql);
		$kel  = $query->row_array();


		$a="DELETE FROM kelompok_anggota WHERE id_kelompok = $kel[id];";
		$b = $this->db->simple_query($a);


		$datax['id_kelompok']=$kel['id'];
		$datax['id_penduduk']=$data['id_ketua'];
		$outp = $this->db->insert('kelompok_anggota',$datax);

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function insert_a($id=0){
		$data = $_POST;
		$data['id_kelompok']=$id;

		$sql   = "SELECT id FROM kelompok_anggota WHERE id_kelompok = ? AND id_penduduk = ?";
		$query = $this->db->query($sql,array($data['id_kelompok'],$data['id_penduduk']));
		$kel  = $query->row_array();

		if(!$kel){
			$outp = $this->db->insert('kelompok_anggota',$data);
		}

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function update($id=0){
		$data = $_POST;

		if($data['id_ketua']=="")
		unset($data['id_ketua']);

		$this->db->where('id',$id);
		$outp = $this->db->update('kelompok',$data);

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function delete($id=''){
		$sql  = "DELETE FROM kelompok WHERE id=?";
		$outp = $this->db->query($sql,array($id));

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function delete_a($id=''){
		$sql  = "DELETE FROM kelompok_anggota WHERE id=?";
		$outp = $this->db->query($sql,array($id));

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function delete_all(){
		$id_cb = $_POST['id_cb'];

		if(count($id_cb)){
			foreach($id_cb as $id){
				$sql  = "DELETE FROM kelompok WHERE id=?";
				$outp = $this->db->query($sql,array($id));
			}
		}
		else $outp = false;

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function get_kelompok($id=0){
		$sql   = "SELECT * FROM kelompok WHERE id=?";
		$query = $this->db->query($sql,$id);
		$data  = $query->row_array();
		return $data;
	}

	function get_ketua_kelompok($id){
		$this->load->model('penduduk_model');
		$sql   = "SELECT u.id,u.nik,u.nama,k.nama as nama_kelompok, k.id as id_kelompok,u.tempatlahir,u.tanggallahir,(SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0 FROM tweb_penduduk WHERE id = u.id) AS umur,d.nama as pendidikan,f.nama as warganegara,a.nama as agama
			FROM kelompok k
			LEFT JOIN tweb_penduduk u ON u.id= k.id_ketua
			LEFT JOIN tweb_penduduk_pendidikan_kk d ON u.pendidikan_kk_id = d.id
			LEFT JOIN tweb_penduduk_warganegara f ON u.warganegara_id = f.id
			LEFT JOIN tweb_penduduk_agama a ON u.agama_id = a.id
			WHERE k.id = ? LIMIT 1";
		$query = $this->db->query($sql,array($id));
		$data = $query->row_array();
		$data['alamat_wilayah'] = $this->penduduk_model->get_alamat_wilayah($data['id']);
		return $data;
	}

	function list_master(){
		$sql   = "SELECT * FROM kelompok_master";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	function list_penduduk(){
		$sql   = "SELECT id,nik,nama FROM tweb_penduduk WHERE status = 1";
		$query = $this->db->query($sql);
		$data=$query->result_array();

		//Formating Output
		$i=0;
		while($i<count($data)){
			$data[$i]['alamat']="Alamat :".$data[$i]['nama'];
			$i++;
		}
		return $data;
	}

	function list_anggota($id=0){
		$sql   = "SELECT u.*,p.nik,p.nama FROM kelompok_anggota u LEFT JOIN tweb_penduduk p ON u.id_penduduk = p.id WHERE id_kelompok = ?";
		$query = $this->db->query($sql,$id);
		$data=$query->result_array();

		//Formating Output
		$i=0;
		while($i<count($data)){
			$data[$i]['no']=$i+1;
			$i++;
		}
		return $data;
	}

}

?>
