<?php

class User_Model extends CI_Model {

	const
		GROUP_REDAKSI=3;

	
	function __construct() {
		parent::__construct();
		$this->load->model('laporan_bulanan_model');
		// Untuk password hashing
		$this->load->library('Bcrypter');
	}


	function siteman() {
		$username=$this->input->post('username');
		$password=$this->input->post('password');
		$sql="SELECT id, password, id_grup, session FROM user WHERE username=?";
		// User 'admin' tidak bisa di-non-aktifkan
		if ($username!=='admin')
			$sql.=' AND active=1';
		$query=$this->db->query($sql,array($username));
		$row=$query->row();
		// Verifikasi password ok
		if ($this->bcrypter->verify($password,$row->password)) {
			// Jika offline_mode dalam level yang menyembunyikan website, redaksi tidak diijinkan login
			if (($row->id_grup==self::GROUP_REDAKSI)
			&&($this->setting->offline_mode>=2))
				$_SESSION['siteman']=-2;
			else {
				$_SESSION['siteman']=1;
				$_SESSION['sesi']=$row->session;
				$_SESSION['user']=$row->id;
				$_SESSION['grup']=$row->id_grup;
				$_SESSION['per_page']=10;
				unset($_SESSION['siteman_timeout']);
			}
		}
		else {
			$_SESSION['siteman']=-1;
			if ($_SESSION['siteman_try']>2)
				$_SESSION['siteman_try']=$_SESSION['siteman_try']-1;
			else $_SESSION['siteman_wait']=1;
		}
	}


	function sesi_grup($sesi='') {
		$sql="SELECT id_grup FROM user WHERE session=?";
		$query=$this->db->query($sql,array($sesi));
		$row=$query->row_array();
		return $row['id_grup'];
	}


	function login() {
		$username=$this->input->post('username');
		$password=$this->input->post('password');
		$sql="SELECT id, password, id_grup, session FROM user WHERE id_grup=1 LIMIT 1";
		$query=$this->db->query($sql);
		$row=$query->row();
		// verifikasi password
		if ($this->bcrypter->verify($password,$row->password)) {
			// simpan sesi - sesi
			$_SESSION['siteman']=1;
			$_SESSION['sesi']=$row->session;
			$_SESSION['user']=$row->id;
			$_SESSION['grup']=$row->id_grup;
			$_SESSION['per_page']=10;
		}
		else $_SESSION['siteman']=-1;
	}


	function logout() {
		if (isset($_SESSION['user'])) {
			$id=$_SESSION['user'];
			$sql="UPDATE user SET last_login=NOW() WHERE id=?";
			$this->db->query($sql,$id);
		}
		// Catat jumlah penduduk saat ini
		$this->laporan_bulanan_model->tulis_log_bulanan();
		unset(
			$_SESSION['user'],
			$_SESSION['sesi'],
			$_SESSION['cari'],
			$_SESSION['filter']
		);
		// $this->create_xml();
		// if ($this->sid_online())
		// 	$this->send_data();
	}


	function autocomplete() {
		$sql="SELECT username FROM user UNION SELECT nama FROM user";
		$query=$this->db->query($sql);
		$data=$query->result_array();

		$i=0;
		$out='';
		while ($i<count($data)) {
			$out.=",'".$data[$i]['username']."'";
			$i++;
		}
		return '['.strtolower(substr($out,1)).']';
	}


	function search_sql() {
		if (isset($_SESSION['cari'])) {
			$cari=$_SESSION['cari'];
			$kw='%'.$this->db->escape_like_str($cari).'%';
			$search_sql=" AND (u.username LIKE '$kw' OR u.nama LIKE '$kw')";
			return $search_sql;
		}
	}


	function filter_sql() {
		if (isset($_SESSION['filter'])) {
			$kf=$_SESSION['filter'];
			$filter_sql=" AND u.id_grup=$kf";
		return $filter_sql;
		}
	}


	function paging($p=1,$o=0) {
		$sql="SELECT COUNT(id) AS id FROM user u WHERE 1";
		$sql.=$this->search_sql();
		$query=$this->db->query($sql);
		$row=$query->row_array();
		$jml_data=$row['id'];

		$this->load->library('paging');
		$cfg['page']=$p;
		$cfg['per_page']=$_SESSION['per_page'];
		$cfg['num_rows']=$jml_data;
		$this->paging->init($cfg);

		return $this->paging;
	}


	function list_data($o=0,$offset=0,$limit=500) {
		// Ordering SQL
		switch($o) {
			case 1:  $order_sql=' ORDER BY u.username';       break;
			case 2:  $order_sql=' ORDER BY u.username DESC';  break;
			case 3:  $order_sql=' ORDER BY u.nama';           break;
			case 4:  $order_sql=' ORDER BY u.nama DESC';      break;
			case 5:  $order_sql=' ORDER BY g.nama';           break;
			case 6:  $order_sql=' ORDER BY g.nama DESC';      break;
			default: $order_sql=' ORDER BY u.username';
		}
		// Paging SQL
		$paging_sql=' LIMIT '.$offset.','.$limit;
		// Main Query
		$sql="SELECT u.*, g.nama as grup FROM user u, user_grup g WHERE u.id_grup=g.id";
		$sql.=$this->search_sql();
		$sql.=$this->filter_sql();
		$sql.=$order_sql;
		$sql.=$paging_sql;

		$query=$this->db->query($sql);
		$data=$query->result_array();

		// Formating Output
		$i=0;
		$j=$offset;
		while ($i<count($data)) {
			$data[$i]['no']=$j+1;
			$i++;
			$j++;
		}
		return $data;
	}


	function insert() {
		$data=$_POST;
		unset(
			$data['old_foto'],
			$data['foto']
		);
		// buat hash password
		$pwhash=$this->bcrypter->hash($data['password']);
		// cek kekuatan hash, buat ulang jika masih lemah
		while ($this->bcrypter->needs_rehash($pwhash))
			$pwhash=$this->bcrypter->hash($data['password']);
		// cek kekuatan hash lolos, simpan ke array data
		$data['password']=$pwhash;
		
		$lokasi_file=$_FILES['foto']['tmp_name'];
		$tipe_file=$_FILES['foto']['type'];
		$nama_file=$_FILES['foto']['name'];
		$nama_file=str_replace(' ','-',$nama_file); // normalkan nama file
		$old_foto=$this->input->post('old_foto');
		if (!empty($lokasi_file)) {
			// tipe file yang diijinkan
			$allowed=array(
				'image/jpeg',
				'image/pjpeg',
				'image/png'
			);
			// cek tipe file
			if (!in_array($tipe_file,$allowed))
				$_SESSION['success']=-1;
			else {
				// cek tipe file lolos, upload!
				UploadFoto($nama_file,$old_foto,$tipe_file);
				$data['foto']=$nama_file;
			}
		  }
		$data['session']=md5(now());
		$out=$this->db->insert('user',$data);
		if ($out)
			$_SESSION['success']=1;
		else $_SESSION['success']=-1;
	}

	function update($id=0) {
		$_SESSION['success']=1;
		$_SESSION['error_msg']='';
		$data=$_POST;
		unset(
			$data['old_foto'],
			$data['foto']
		);
		$lokasi_file=$_FILES['foto']['tmp_name'];
		$tipe_file=$_FILES['foto']['type'];
		$nama_file=$_FILES['foto']['name'];
		$nama_file=str_replace(' ', '-', $nama_file); // normalkan nama file
		$old_foto=$this->input->post('old_foto');

		if (!empty($lokasi_file)) {
			if (UploadFoto($nama_file,$old_foto,$tipe_file))
				$data['foto']=$nama_file;
		}

		// apabila password tidak diganti
		if ($data['password']=='radiisi')
			unset($data['password']);
	  	// Jangan edit password admin apabila di situs demo
		elseif ($id==1&&config_item('demo'))
			unset($data['username'],$data['password']);
		else {
			// buat hash password
			$pwhash=$this->bcrypter->hash($data['password']);
			// cek kekuatan hash, regenerate jika masih lemah
			while ($this->bcrypter->needs_rehash($pwhash))
				$pwhash=$this->bcrypter->hash($data['password']);
			// cek kekuatan hash lolos, simpan ke array data
			$data['password']=$pwhash;
		}
		
		$this->db->where('id',$id);
		$out=$this->db->update('user',$data);
		if (!$out)
			$_SESSION['success']=-1;
	}


	function delete($id='') {
		// Jangan hapus admin
		if ($id==1)
			return;

		$sql="DELETE FROM user WHERE id=?";
		$out=$this->db->query($sql,array($id));
		if ($out)
			$_SESSION['success']=1;
		else $_SESSION['success']=-1;
	}


	function delete_all() {
		$id_cb=$_POST['id_cb'];
		if (count($id_cb)) {
			foreach ($id_cb as $id) {
				// Jangan hapus admin
				if ($id==1)
					continue;
				$sql="DELETE FROM user WHERE id=?";
				$out=$this->db->query($sql,array($id));
			}
		}
		else $out=false;

		if ($out)
			$_SESSION['success']=1;
		else $_SESSION['success']=-1;
	}


	function user_lock($id='',$val=0) {
		$sql="UPDATE user SET active=? WHERE id=?";
		$out=$this->db->query($sql,array($val,$id));
		if ($out)
			$_SESSION['success']=1;
		else $_SESSION['success']=-1;
	}


	function get_user($id=0) {
		$sql="SELECT * FROM user WHERE id=?";
		$query=$this->db->query($sql,$id);
		$data=$query->row_array();
		// Formating Output
		$data['password']='radiisi';
		return $data;
	}


	function get_user2($user='') {
		$sql="SELECT id,nama,username FROM user WHERE username=?";
		$query=$this->db->query($sql,$user);
		return $query->row_array();
	}


	function update_setting($id=0) {
		$_SESSION['success']=1;
		$_SESSION['error_msg']='';

		$data['nama']=$this->input->post('nama');
		$password=$this->input->post('pass_lama');
		$pass_baru=$this->input->post('pass_baru');
		$pass_baru1=$this->input->post('pass_baru1');

		// Jangan edit password admin apabila di situs demo
		if ($id==1&&config_item('demo'))
		  unset($data['password']);
		// Ganti password
		else {
			if ($this->input->post('pass_lama') != ""
			||$pass_baru != ""
			||$pass_baru1 != "") {
				$sql="SELECT password,id_grup,session FROM user WHERE id=?";
				$query=$this->db->query($sql,array($id));
				$row=$query->row();
				// cek input password
				if ($this->bcrypter->verify($password,$row->password)===FALSE)
					$_SESSION['error_msg'].=' -> Password lama salah<br />';
				if (empty($pass_baru1))
					$_SESSION['error_msg'].=' -> Password baru tidak boleh kosong<br />';
				if ($pass_baru!=$pass_baru1)
					$_SESSION['error_msg'].=' -> Password baru tidak cocok<br />';

				if (!empty($_SESSION['error_msg']))
					$_SESSION['success']=-1;
				// Cek input password lolos
				else {
					$_SESSION['success']=1;
					// buat hash password
					$pwhash=$this->bcrypter->hash($pass_baru);
					// cek kekuatan hash, regenerate jika masih lemah
					while ($this->bcrypter->needs_rehash($pwhash))
						$pwhash=$this->bcrypter->hash($pass_baru);
					// cek kekuatan hash lolos, simpan ke array data
					$data['password']=$pwhash;
				}

			}
		}
		// Update foto
		// TODO : mestinya pake cara upload CI?
		$lokasi_file=$_FILES['foto']['tmp_name'];
		$tipe_file=$_FILES['foto']['type'];
		$nama_file=$_FILES['foto']['name'];
		$nama_file=str_replace(' ','-',$nama_file); // normalkan nama file
		$old_foto=$this->input->post('old_foto');
		if (!empty($lokasi_file)) {
			if (UploadFoto($nama_file,$old_foto,$tipe_file))
				$data['foto']=$nama_file;
		}
		$this->db->where('id',$id);
		$out=$this->db->update('user',$data);
		if (!$out)
			$_SESSION['success']=-1;
	}


	function list_grup() {
		$sql="SELECT * FROM user_grup";
		$query=$this->db->query($sql);
		return $query->result_array();
	}


	function sid_online() {
		// $q=$_GET["q"]; // ????
		$q="sid.web.id";
		$input='';
		exec("ping -n 1 -w 1 $q",$input,$result);
		if ($result==0)
			return true;
		else return false;
	}


	function create_xml() {
		$sql="SELECT * FROM config WHERE 1";
		$query=$this->db->query($sql);
		$desa=$query->row_array();
		$nl="\r\n";
		$string="";

		// desa
		$string.="<desa>".$nl;
		$string.="<nama>".$desa['nama_desa']."</nama>".$nl;
		$string.="<kode>".$desa['kode_kabupaten'].$desa['kode_kecamatan'].$desa['kode_desa']."</kode>".$nl;
		$string.="<lat>".$desa['lat']."</lat>".$nl;
		$string.="<lng>".$desa['lng']."</lng>".$nl;
		//.......
		$string.="</desa>".$nl.$nl;
		
		// wilayah
		$sql="SELECT DISTINCT(dusun) FROM tweb_wil_clusterdesa";
		$query=$this->db->query($sql);
		$wilayah=$query->result_array();

		$string.="<wilayah>".$nl;
		foreach ($wilayah as $wil)
			$string.="<dusun>".$wil['dusun']."</dusun>".$nl;
		$string.="</wilayah>".$nl.$nl;

		// pendeuduk
		$sql ="SELECT * FROM data_surat";
		$query=$this->db->query($sql);
		$penduduk=$query->result_array();

		$string.="<penduduk>".$nl;
		foreach ($penduduk as $pend) {
			$string.="<individu>".$nl;
			$string.="<nik>".$pend['nik']."</nik>".$nl;
			$string.="<nama>".$pend['nama']."</nama>".$nl;
			$string.="<pekerjaan>".$pend['pekerjaan']."</pekerjaan>".$nl;
			$string.="</individu>".$nl;
		}
		$string.="</penduduk>".$nl.$nl;
		$mypath="assets\\sync\\";
		$path=str_replace("\\","/",$mypath)."/";
		$ccyymmdd=date("Y-m-d");
		$handle=fopen($path."sycn_data_".$ccyymmdd.".xml",'w+');
		fwrite($handle,$string);
		fclose($handle);
		// echo $string;
	}


	function send_data() {
		// $ip="sid.web.id";
		$ip="127.0.0.1";
		$connect=fsockopen($ip,"80",$errno,$errstr,1);
		if($connect) {
			$soap_request="<GetAttLog>".
				"<ArgComKey xsi:type=\"xsd:integer\">$key</ArgComKey>".
					"<Arg><PIN xsi:type=\"xsd:integer\">$p[id]</PIN></Arg>".
			"</GetAttLog>";
			$eol="\r\n";
			fputs($connect,"POST /iWsService HTTP/1.0".$eol);
			fputs($connect,"Content-Type: text/xml".$eol);
			fputs($connect,"Content-Length: ".strlen($soap_request).$eol.$eol);
			fputs($connect,$soap_request.$eol);
			
			$buffer='';
			while ($response=fgets($connect,8192))
				$buffer.=$response;
			echo $buffer;
		}
	}

}