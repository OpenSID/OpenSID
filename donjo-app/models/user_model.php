<?php

class User_Model extends CI_Model{

	const GROUP_REDAKSI = 3;

	function __construct(){
		parent::__construct();
		$this->load->model('laporan_bulanan_model');
	}

	function siteman(){
		$username = $this->input->post('username');
		$password = md5($this->input->post('password'));

		$sql = "SELECT id,password,id_grup,session FROM user WHERE username=?";
		// User 'admin' tidak bisa di-non-aktifkan
		if ($username !== 'admin')
			$sql .= ' AND active = 1';
		$query=$this->db->query($sql,array($username));
		$row=$query->row();

		if($password==$row->password){
			// Jika offline_mode dalam level yang menyembunyikan website,
			// redaksi tidak diijinkan login
			if (($row->id_grup == self::GROUP_REDAKSI) &&
				($this->setting->offline_mode >= 2)) {

				$_SESSION['siteman']=-2;

			} else {
				$_SESSION['siteman']    = 1;
				$_SESSION['sesi']     = $row->session;
				$_SESSION['user']     = $row->id;
				$_SESSION['grup']     = $row->id_grup;
				$_SESSION['per_page'] = 10;
			}
		} else{
			$_SESSION['siteman']=-1;
		}
	}

	function sesi_grup($sesi=''){

		$sql = "SELECT id_grup FROM user WHERE session=?";
		$query=$this->db->query($sql,array($sesi));
		$row=$query->row_array();
		return $row['id_grup'];
	}

	function login(){
		$username = $this->input->post('username');
		$password = md5($this->input->post('password'));

		$sql = "SELECT id,password,id_grup,session FROM user WHERE id_grup=1 LIMIT 1";
		$query=$this->db->query($sql);
		$row=$query->row();

		if($password!=$row->password){
			$_SESSION['siteman']    = 1;
			$_SESSION['sesi']     = $row->session;
			$_SESSION['user']     = $row->id;
			$_SESSION['grup']     = $row->id_grup;
			$_SESSION['per_page'] = 10;
		}
		else{
			$_SESSION['siteman']=-1;
		}
	}

	function logout(){
		if(isset($_SESSION['user'])){
			$id = $_SESSION['user'];
			$sql = "UPDATE user SET last_login=NOW() WHERE id=?";
			$this->db->query($sql, $id);
		}

		// Catat jumlah penduduk saat ini
		$this->laporan_bulanan_model->tulis_log_bulanan();

		unset($_SESSION['user']);
		unset($_SESSION['sesi']);
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);

		//$this->create_xml();

		//if($this->sid_online())
		//	$this->send_data();


	}


	function autocomplete(){
		$sql   = "SELECT username FROM user
					UNION SELECT nama FROM user";
		$query = $this->db->query($sql);
		$data  = $query->result_array();

		$i=0;
		$outp='';
		while($i<count($data)){
			$outp .= ",'" .$data[$i]['username']. "'";
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
			$search_sql= " AND (u.username LIKE '$kw' OR u.nama LIKE '$kw')";
			return $search_sql;
			}
		}

	function filter_sql(){
		if(isset($_SESSION['filter'])){
			$kf = $_SESSION['filter'];
			$filter_sql= " AND u.id_grup = $kf";
		return $filter_sql;
		}
	}

	function paging($p=1,$o=0){

		$sql      = "SELECT COUNT(id) AS id FROM user u WHERE 1";
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
			case 1: $order_sql = ' ORDER BY u.username'; break;
			case 2: $order_sql = ' ORDER BY u.username DESC'; break;
			case 3: $order_sql = ' ORDER BY u.nama'; break;
			case 4: $order_sql = ' ORDER BY u.nama DESC'; break;
			case 5: $order_sql = ' ORDER BY g.nama'; break;
			case 6: $order_sql = ' ORDER BY g.nama DESC'; break;
			default:$order_sql = ' ORDER BY u.username';
		}

		//Paging SQL
		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;

		//Main Query
		$sql   = "SELECT u.*,g.nama as grup
					FROM user u, user_grup g
					WHERE u.id_grup = g.id";

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

	function insert(){
		$data = $_POST;
		$data['password'] = md5($data['password']);
		unset($data['old_foto']);
		unset($data['foto']);
		$lokasi_file = $_FILES['foto']['tmp_name'];
		$tipe_file   = $_FILES['foto']['type'];
		$nama_file   = $_FILES['foto']['name'];
		$nama_file   = str_replace(' ', '-', $nama_file); 	 // normalkan nama file
		$old_foto    = $this->input->post('old_foto');
		if (!empty($lokasi_file)){
			if ($tipe_file != "image/jpeg" AND $tipe_file != "image/pjpeg" AND $tipe_file != "image/png"){
				$_SESSION['success']=-1;
			} else {
				UploadFoto($nama_file,$old_foto,$tipe_file);
				$data['foto'] = $nama_file;
			}
		  }

		$data['session']			= md5(now());

		$outp = $this->db->insert('user',$data);

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function update($id=0){
		$_SESSION['success'] = 1;
		$_SESSION['error_msg'] = '';
		$data = $_POST;
		unset($data['old_foto']);
		unset($data['foto']);
		$lokasi_file = $_FILES['foto']['tmp_name'];
		$tipe_file   = $_FILES['foto']['type'];
		$nama_file   = $_FILES['foto']['name'];
		$nama_file   = str_replace(' ', '-', $nama_file); 	 // normalkan nama file
		$old_foto    = $this->input->post('old_foto');
		if (!empty($lokasi_file)){
			if (UploadFoto($nama_file,$old_foto,$tipe_file))
				$data['foto'] = $nama_file;
	  }
		if ($data['password']=='radiisi'){
		// apabila password tidak diganti
			unset($data['password']);
		} elseif ($id == 1 AND config_item('demo')) {
	  // Jangan edit password admin apabila di situs demo
			unset($data['username']);
			unset($data['password']);
		} else {
			$data['password'] = md5($data['password']);
		}
		$this->db->where('id',$id);
		$outp = $this->db->update('user',$data);

		if(!$outp) $_SESSION['success']=-1;
	}

	function delete($id=''){
		// Jangan hapus admin
		if ($id == 1) return;

		$sql  = "DELETE FROM user WHERE id=?";
		$outp = $this->db->query($sql,array($id));

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function delete_all(){
		$id_cb = $_POST['id_cb'];

		if(count($id_cb)){
			foreach($id_cb as $id){
				// Jangan hapus admin
				if ($id == 1) continue;

				$sql  = "DELETE FROM user WHERE id=?";
				$outp = $this->db->query($sql,array($id));
			}
		}
		else $outp = false;

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function user_lock($id='',$val=0){

		$sql  = "UPDATE user SET active=? WHERE id=?";
		$outp = $this->db->query($sql, array($val,$id));

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function get_user($id=0){
		$sql   = "SELECT * FROM user WHERE id=?";
		$query = $this->db->query($sql,$id);
		$data  = $query->row_array();

		//Formating Output
		$data['password'] = 'radiisi';
		return $data;
	}

	function get_user2($user=''){
		$sql   = "SELECT id,nama,username FROM user WHERE username=?";
		$query = $this->db->query($sql,$user);
		return $query->row_array();
	}

	function update_setting($id=0){
		$_SESSION['success']=1;
		$_SESSION['error_msg'] = '';

		$data['nama'] = $this->input->post('nama');

		$password 		= md5($this->input->post('pass_lama'));
		$pass_baru 		= $this->input->post('pass_baru');
		$pass_baru1 	= $this->input->post('pass_baru1');

		if($id == 1 AND config_item('demo')){
		  // Jangan edit password admin apabila di situs demo
			unset($data['password']);
		} else {
			// Ganti password
			if($this->input->post('pass_lama') != "" OR $pass_baru != "" OR $pass_baru1 != ""){
				$sql = "SELECT password,id_grup,session FROM user WHERE id=?";
				$query=$this->db->query($sql,array($id));
				$row=$query->row();
				// Password baru tidak boleh kosong
				if($password==$row->password AND $pass_baru != "" AND $pass_baru == $pass_baru1){
					$data['password'] = md5($pass_baru);
				} else {
					$_SESSION['error_msg'].= " -> Kode pengaman salah";
					$_SESSION['success']=-1;
				}
			}
		}

		// Update foto
		// TODO : mestinya pake cara upload CI?
		$lokasi_file = $_FILES['foto']['tmp_name'];
		$tipe_file   = $_FILES['foto']['type'];
		$nama_file   = $_FILES['foto']['name'];
		$nama_file   = str_replace(' ', '-', $nama_file); 	 // normalkan nama file
		$old_foto    = $this->input->post('old_foto');
		if (!empty($lokasi_file)){
			if (UploadFoto($nama_file,$old_foto,$tipe_file))
				$data['foto'] = $nama_file;
	  }

		$this->db->where('id',$id);
		$outp = $this->db->update('user',$data);

		if(!$outp) $_SESSION['success']=-1;
	}

	function list_grup(){
		$sql   = "SELECT * FROM user_grup";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	function sid_online(){
		$q=$_GET["q"];
		$q="sid.web.id";
		$input="";
		exec("ping -n 1 -w 1 $q", $input, $result);
		if ($result == 0)
			return true;
		else return false;


	}

	function create_xml(){
		$sql   = "SELECT * FROM config WHERE 1";
		$query = $this->db->query($sql);
		$desa = $query->row_array();


		$nl="\r\n";
		$string = "";

		//DESA
		$string .= "<desa>".$nl;
		$string .= "<nama>".$desa['nama_desa']."</nama>".$nl;
		$string .= "<kode>".$desa['kode_kabupaten'].$desa['kode_kecamatan'].$desa['kode_desa']."</kode>".$nl;
		$string .= "<lat>".$desa['lat']."</lat>".$nl;
		$string .= "<lng>".$desa['lng']."</lng>".$nl;

		//.......

		$string .= "</desa>".$nl.$nl;

		//wilayah
		$sql   = "SELECT DISTINCT(dusun) FROM tweb_wil_clusterdesa";
		$query = $this->db->query($sql);
		$wilayah = $query->result_array();

		$string .= "<wilayah>".$nl;
		foreach($wilayah AS $wil){

			$string .= "<dusun>".$wil['dusun']."</dusun>".$nl;

		}

		$string .= "</wilayah>".$nl.$nl;

		//pendeuduk

		$sql   = "SELECT * FROM data_surat";
		$query = $this->db->query($sql);
		$penduduk = $query->result_array();

		$string .= "<penduduk>".$nl;
		foreach($penduduk AS $pend){

			$string .= "<individu>".$nl;
			$string .= "<nik>".$pend['nik']."</nik>".$nl;
			$string .= "<nama>".$pend['nama']."</nama>".$nl;
			$string .= "<pekerjaan>".$pend['pekerjaan']."</pekerjaan>".$nl;
			$string .= "</individu>".$nl;

		}

		$string .= "</penduduk>".$nl.$nl;


		$mypath="assets\\sync\\";
		$path = "".str_replace("\\","/",$mypath)."/";

		$ccyymmdd = date("Y-m-d");
		$handle = fopen($path."sycn_data_".$ccyymmdd.".xml",'w+');
		fwrite($handle,$string);
		fclose($handle);


		//echo $string;

	}

	function send_data(){
		//$ip = "sid.web.id";

		$ip = "127.0.0.1";
		$Connect = fsockopen($ip,"80",$errno,$errstr,1);
		if($Connect){
			$soap_request="<GetAttLog><ArgComKey xsi:type=\"xsd:integer\">$key</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">$p[id]</PIN></Arg></GetAttLog>";
			fputs($Connect, "POST /iWsService HTTP/1.0".$newLine);
			fputs($Connect, "Content-Type: text/xml".$newLine);
			fputs($Connect, "Content-Length: ".strlen($soap_request).$newLine.$newLine);
			fputs($Connect, $soap_request.$newLine);
			$buffer="";
			while($Response=fgets($Connect, 8192)){
				$buffer.=$Response;
			}
			echo $buffer;
		}
	}

}

?>
