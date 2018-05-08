<?php class Mandiri_model extends CI_Model{

	function __construct(){
		parent::__construct();
	}

	function autocomplete(){
		$sql   = "SELECT nik FROM tweb_penduduk_mandiri";
		$query = $this->db->query($sql);
		$data  = $query->result_array();

		$i=0;
		$outp='';
		while($i<count($data)){
			$outp .= ",'" .$data[$i]['nik']. "'";
			$i++;
		}
		$outp = substr($outp, 1);
		$outp = '[' .$outp. ']';

		return $outp;
	}

	function search_sql(){
		if(isset($_SESSION['cari'])){
		$cari = $_SESSION['cari'];
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' .$kw. '%';
			$search_sql= " AND (u.nik LIKE '$kw' OR n.nama LIKE '$kw')";
			return $search_sql;
			}
		}

	function filter_sql(){
		if(isset($_SESSION['filter'])){
			$kf = $_SESSION['filter'];
			if($kf=="0"){
			$filter_sql= "";} else {
			$filter_sql= " AND n.nik = '".$kf."'";}
		return $filter_sql;
		}
	}

	function filterku_sql($nik=0){
			$kf = $nik;
			if($kf==0){
			$filterku_sql= "";} else {
			$filterku_sql= " AND u.id_pend = '".$kf."'";}
		return $filterku_sql;
	}

	function paging($p=1,$o=0){

		$sql      = "SELECT COUNT(id) AS id FROM tweb_penduduk_mandiri u
			LEFT JOIN tweb_penduduk n ON u.nik = n.nik
			WHERE 1";
		$sql     .= $this->search_sql();
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
			case 1: $order_sql = ' ORDER BY u.last_login'; break;
			case 2: $order_sql = ' ORDER BY u.last_login DESC'; break;

			default:$order_sql = ' ORDER BY u.tanggal_buat';
		}

		//Paging SQL
		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;

		//Main Query

		$sql   = "SELECT u.*, u.nik as nik_lama, n.nama AS nama, n.nik AS nik
			FROM tweb_penduduk_mandiri u
			LEFT JOIN tweb_penduduk n ON u.id_pend = n.id
			WHERE 1 ";


		$sql .= $this->search_sql();
		$sql .= $this->filter_sql();
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

	function generate_pin($pin=""){
		if($pin==""){
			$pin = rand(100000,999999);
			$pin = strrev($pin);
		}
		return $pin;
	}


	function insert(){
		if($_POST['nik']=="")
			redirect("mandiri");

		$sql  = "DELETE FROM tweb_penduduk_mandiri WHERE nik=?";
		$outp = $this->db->query($sql,array($_POST['nik']));

		$rpin = $this->generate_pin($_POST['pin']);
		$hash_pin = hash_pin($rpin);
		$data['pin'] = $hash_pin;
		$data['nik'] = $_POST['nik'];
		$data['id_pend'] = $this->db->select('id')->where('nik',$_POST['nik'])
					->get('tweb_penduduk')->row()->id;
		$data['tanggal_buat'] = date("Y-m-d H:i:s");

		$outp = $this->db->insert('tweb_penduduk_mandiri',$data);

		if($_POST['pin']!="")
			return $_POST['pin'];
		else
			return $rpin;
	}

	function delete($id_pend=''){
		$sql  = "DELETE FROM tweb_penduduk_mandiri WHERE id_pend=?";
		$outp = $this->db->query($sql,array($id_pend));
		return $outp;
	}

	function delete_all(){
		$_SESSION['success']=1;
		$id_cb = $_POST['id_cb'];

		if(count($id_cb)){
			foreach($id_cb as $id){
				$outp = $this->delete($id);
				if (!$outp) $_SESSION['success']=-1;
			}
		}
	}

	function list_penduduk(){
		$sql   = "SELECT nik AS id,nik,nama FROM tweb_penduduk WHERE status = 1 AND nik<>'' AND nik<>0";
		$query = $this->db->query($sql);
		$data=$query->result_array();

		//Formating Output AND nik NOT IN(SELECT nik FROM tweb_penduduk_mandiri)
		$i=0;
		while($i<count($data)){
			$data[$i]['alamat']="Alamat :".$data[$i]['nama'];
			$i++;
		}
		return $data;
	}

	function update_setting($id=0){
		$password 		= md5($this->input->post('pass_lama'));
		$pass_baru 		= $this->input->post('pass_baru');
		$pass_baru1 	= $this->input->post('pass_baru1');
		$nama 			= $this->input->post('nama');

		$sql = "SELECT password,id_grup,session FROM user WHERE id=?";
		$query=$this->db->query($sql,array($id));
		$row=$query->row();

		if($password==$row->password){
			if($pass_baru == $pass_baru1){
				$pass_baru = md5($pass_baru);
				$sql  = "UPDATE user SET password=?,nama=? WHERE id=?";
				$outp = $this->db->query($sql,array($pass_baru,$nama,$id));
			}
		}

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function list_grup(){
		$sql   = "SELECT * FROM user_grup";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

}
