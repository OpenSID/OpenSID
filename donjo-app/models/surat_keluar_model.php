<?php class Surat_Keluar_Model extends CI_Model{

	function __construct(){
		parent::__construct();
	}

	function autocomplete(){
		$sql   = "SELECT no_surat FROM log_surat";
		$query = $this->db->query($sql);
		$data  = $query->result_array();

		$i=0;
		$outp='';
		while($i<count($data)){
			$outp .= ",'" .$data[$i]['no_surat']. "'";
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
			$search_sql= " AND (u.no_surat LIKE '$kw' OR u.id_pend LIKE '$kw')";
			return $search_sql;
			}
		}

	function filter_sql(){
		if(isset($_SESSION['nik'])){
			$kf = $_SESSION['nik'];
			if($kf=="0"){
			$filter_sql= "";} else {
			$filter_sql= " AND n.id = '".$kf."'";}
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

		$sql      = "SELECT COUNT(id) AS id FROM log_surat u WHERE 1";
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

	function paging_perorangan($nik=0,$p=1,$o=0){

		$sql   = "SELECT count(id_format_surat) as id FROM log_surat u LEFT JOIN tweb_penduduk n ON u.id_pend = n.id LEFT JOIN tweb_surat_format k ON u.id_format_surat = k.id LEFT JOIN tweb_desa_pamong s ON u.id_pamong = s.pamong_id  WHERE 1 ";
		$sql .= $this->filterku_sql($nik);
		//$sql     .= $this->search_sql();
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

	function list_data_surat($nik=0,$o=0,$offset=0,$limit=500){

		//Ordering SQL
		switch($o){
			case 1: $order_sql = ' ORDER BY u.no_surat'; break;
			case 2: $order_sql = ' ORDER BY u.no_surat DESC'; break;

			default:$order_sql = ' ORDER BY u.tanggal DESC';
		}

		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;

		$sql   = "SELECT u.*,n.nama AS nama,w.nama AS nama_user, n.nik AS nik,k.nama AS format, k.url_surat as berkas,s.pamong_nama AS pamong
			FROM log_surat u
			LEFT JOIN tweb_penduduk n ON u.id_pend = n.id
			LEFT JOIN tweb_surat_format k ON u.id_format_surat = k.id
			LEFT JOIN tweb_desa_pamong s ON u.id_pamong = s.pamong_id
			LEFT JOIN user w ON u.id_user = w.id
			WHERE 1 ";

		$sql .= $this->search_sql();
		$sql .= $this->filterku_sql($nik);
		$sql .= $order_sql;
		$sql .= $paging_sql;

		$query = $this->db->query($sql);
		$data=$query->result_array();

		//Formating Output
		$i=0;
		$j=$offset;
		while($i<count($data)){
			$data[$i]['no']=$j+3;
			$i++;
			$j++;
		}
		return $data;
	}

	function list_data($o=0,$offset=0,$limit=500){

		//Ordering SQL
		switch($o){
			case 1: $order_sql = ' ORDER BY u.no_surat'; break;
			case 2: $order_sql = ' ORDER BY u.no_surat DESC'; break;

			default:$order_sql = ' ORDER BY u.tanggal DESC';
		}

		//Paging SQL
		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;

		//Main Query

		$sql   = "SELECT u.*,n.nama AS nama,w.nama AS nama_user, n.nik AS nik,k.nama AS format, k.url_surat as berkas,s.pamong_nama AS pamong
			FROM log_surat u
			LEFT JOIN tweb_penduduk n ON u.id_pend = n.id
			LEFT JOIN tweb_surat_format k ON u.id_format_surat = k.id
			LEFT JOIN tweb_desa_pamong s ON u.id_pamong = s.pamong_id
			LEFT JOIN user w ON u.id_user = w.id
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
			$data[$i]['t']=$data[$i]['id_pend'];

			if($data[$i]['id_pend'] == -1)
				$data[$i]['id_pend'] = "Masuk";
			else
				$data[$i]['id_pend'] = "Keluar";

			$i++;
			$j++;
		}
		return $data;
	}

	function log_surat($f=0,$id='',$g='',$u='',$z=''){

		$data['id_pend']=$id;

			$sql   = "SELECT id FROM tweb_surat_format WHERE url_surat = ?";
			$query = $this->db->query($sql,$f);
			if($query->num_rows() > 0){
				$pam=$query->row_array();
				$data['id_format_surat']=$pam['id'];
			}else{
				$data['id_format_surat'] = $f;
			}

			$sql   = "SELECT pamong_id FROM tweb_desa_pamong WHERE pamong_nama = ?";
			$query = $this->db->query($sql,$g);
			if($query->num_rows() > 0){
				$pam=$query->row_array();
				$data['id_pamong']=$pam['pamong_id'];
			}else{
				$data['id_pamong'] = 1;
			}


		if($data['id_pamong']=='')
			$data['id_pamong'] = 1;

		$data['id_user']=$u;
		$data['bulan']=date('m');
		$data['tahun']=date('Y');
		$data['no_surat']=$z;
		//print_r($data);
		$this->db->insert('log_surat',$data);

	}

	function grafik(){
		$sql   = "select round(((jml*100)/(select count(id) from log_surat)),2) as jumlah, nama from (SELECT COUNT(l.id) as jml, f.nama from log_surat l left join tweb_surat_format f on l.id_format_surat=f.id group by l.id_format_surat) as a";

		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}

	function update($id=0){

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function delete($id=''){
		$sql  = "DELETE FROM log_surat WHERE id=?";
		$outp = $this->db->query($sql,array($id));

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function delete_all(){
		$id_cb = $_POST['id_cb'];

		if(count($id_cb)){
			foreach($id_cb as $id){
				$sql  = "DELETE FROM log_surat WHERE id=?";
				$outp = $this->db->query($sql,array($id));
			}
		}
		else $outp = false;

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
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

?>
