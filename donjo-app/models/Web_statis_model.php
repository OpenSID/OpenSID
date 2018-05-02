<?php class Web_statis_model extends CI_Model{

	function __construct(){
		parent::__construct();
	}

	function autocomplete(){
		$sql   = "SELECT tgl_upload, owner, email, komentar FROM komentar";
		$query = $this->db->query($sql);
		$data  = $query->result_array();

		$i=0;
		$outp='';
		while($i<count($data)){
			$outp .= ",'" .$data[$i]['komentar']. "'";
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
			$search_sql= " AND (komentar LIKE '$kw' OR komentar LIKE '$kw')";
			return $search_sql;
			}
		}

	function filter_sql(){
		if(isset($_SESSION['filter'])){
			$kf = $_SESSION['filter'];
			$filter_sql= " AND enabled = $kf";
		return $filter_sql;
		}
	}

	function paging($p=1,$o=0){

		$sql      = "SELECT COUNT(id) AS id FROM komentar WHERE 1";
		$sql     .= $this->search_sql();
		$sql .= $this->filter_sql();
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

		switch($o){
			case 1: $order_sql = ' ORDER BY tgl_upload DESC'; break;
			case 2: $order_sql = ' ORDER BY owner'; break;
			case 3: $order_sql = ' ORDER BY email'; break;
			case 4: $order_sql = ' ORDER BY komentar'; break;

			default:$order_sql = ' ORDER BY tgl_upload DESC';
		}

		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;

		$sql   = "SELECT * FROM komentar WHERE 1 ";

		$sql .= $this->search_sql();
		$sql .= $this->filter_sql();
		$sql .= $order_sql;
		$sql .= $paging_sql;

		$query = $this->db->query($sql);
		$data=$query->result_array();

		$i=0;
		$j=$offset;
		while($i<count($data)){
			$data[$i]['no']=$j+1;

			if($data[$i]['enabled']==1)
				$data[$i]['aktif']="Ya";
			else
				$data[$i]['aktif']="Tidak";

			$i++;
			$j++;
		}
		return $data;
	}

	function list_kategori($tipe=1){
		$sql     = "SELECT * FROM kategori WHERE tipe = ?";
		$query    = $this->db->query($sql,$tipe);
		return  $query->result_array();
	}

	function insert(){

		$data = $_POST;
		$data['id_user'] = $_SESSION['user'];
		$outp = $this->db->insert('komentar',$data);
		if($outp) $_SESSION['success']=1;
		else $_SESSION['success']=-1;
	}

	function update($id=0){
		  $data = $_POST;

		$this->db->where('id',$id);
		$outp = $this->db->update('komentar',$data);
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function delete($id=''){
		$sql  = "DELETE FROM komentar WHERE id=?";
		$outp = $this->db->query($sql,array($id));

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function delete_all(){
		$id_cb = $_POST['id_cb'];

		if(count($id_cb)){
			foreach($id_cb as $id){
				$sql  = "DELETE FROM komentar WHERE id=?";
				$outp = $this->db->query($sql,array($id));
			}
		}
		else $outp = false;

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function komentar_lock($id='',$val=0){

		$sql  = "UPDATE komentar SET enabled=? WHERE id=?";
		$outp = $this->db->query($sql, array($val,$id));

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function get_komentar($id=0){
		$sql   = "SELECT a.* FROM komentar a WHERE a.id=?";
		$query = $this->db->query($sql,$id);
		$data  = $query->row_array();

			//$judul=str_split($data['nama'],15);
			//$data['judul'] = "<h3>".$judul[6]."</h3>";

		return $data;
	}

	function komentar_show(){
		$sql   = "SELECT a.*,u.nama AS owner FROM komentar a LEFT JOIN user u ON a.id_user = u.id WHERE enabled=? ORDER BY a.tgl_upload DESC LIMIT 6";
		$query = $this->db->query($sql,1);
		$data  = $query->result_array();

		$i=0;
		while($i<count($data)){

			//$judul=str_split($data[$i]['nama'],15);
			//$data[$i]['judul'] = "<h3>".$judul[6]."</h3>";
			$id = $data[$i]['id'];
			//$data['link'] = site_url("first/komentar/$id");

			$pendek=str_split($data[$i]['isi'],100);
			$data[$i]['isi_short'] = $pendek[0];
			$panjang=str_split($data[$i]['isi'],150);
			$data[$i]['isi'] = "<label>".$panjang[0]."...</label><a href='".site_url("first/komentar/$id")."'>Baca Selengkapnya</a>";
			$i++;
		}
		return $data;
	}

	function insert_comment($id=0){
		$data = $_POST;
		//$data['id_user'] = $_SESSION['user'];
		$data['id_komentar'] = $id;
		$outp = $this->db->insert('komentar',$data);

		if($outp) $_SESSION['success']=1;
		else $_SESSION['success']=-1;
	}

	function list_komentar($id=0){
		$sql   = "SELECT * FROM komentar WHERE id_komentar = ? ORDER BY tgl_upload DESC";
		$query = $this->db->query($sql,$id);
		$data  = $query->result_array();

		$i=0;
		while($i<count($data)){
			$i++;
		}

		return $data;
	}

}
?>
