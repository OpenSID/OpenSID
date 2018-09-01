<?php class Pamong_model extends CI_Model{

	function __construct(){
		parent::__construct();
	}

	function list_data($aktif = false){
		$sql   = "SELECT u.* FROM tweb_desa_pamong u WHERE 1";
        $sql .= $aktif ? " AND u.pamong_status = '1'" : null;
		$sql .= $this->search_sql();
		$sql .= $this->filter_sql();

		$query = $this->db->query($sql);
		$data  = $query->result_array();

		$i=0;
		while($i<count($data)){
			$data[$i]['no']=$i+1;
			$i++;
		}
		return $data;
	}

	function list_semua(){
		$data = $this->db->select('*')->get('tweb_desa_pamong')->result_array();
		return $data;
	}

	function autocomplete(){
		$sql   = "SELECT pamong_nama FROM tweb_desa_pamong
					UNION SELECT pamong_nip FROM tweb_desa_pamong
					UNION SELECT pamong_nik FROM tweb_desa_pamong";
		$query = $this->db->query($sql);
		$data  = $query->result_array();

		$i=0;
		$outp='';
		while($i<count($data)){
			$outp .= ",'" .addslashes($data[$i]['pamong_nama']). "'";
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
			$search_sql= " AND (u.pamong_nama LIKE '$kw' OR u.pamong_nip LIKE '$kw' OR u.pamong_nik LIKE '$kw')";
			return $search_sql;
			}
		}

	function filter_sql(){
		if(isset($_SESSION['filter'])){
			$kf = $_SESSION['filter'];
			$filter_sql= " AND u.pamong_status = $kf";
		return $filter_sql;
		}
	}

	function get_data($id=0){

		$sql   = "SELECT * FROM tweb_desa_pamong WHERE pamong_id=?";
		$query = $this->db->query($sql,$id);
		$data  = $query->row_array();
		return $data;
	 }

	function get_pamong_by_nama($nama=''){
		$pamong = $this->db->select('*')->from('tweb_desa_pamong')->where('pamong_nama', $nama)->limit(1)->get()->row_array();
		return $pamong;
	}

	function insert(){
		$nip    			= $this->input->post('pamong_nip');
		$nama       	= $this->input->post('pamong_nama');
		$nik        	= $this->input->post('pamong_nik');
		$jabatan  		= $this->input->post('jabatan');
		$status  			= $this->input->post('pamong_status');
		$nama_file 		= '';
		$lokasi_file 	= $_FILES['foto']['tmp_name'];
		$tipe_file   	= $_FILES['foto']['type'];
		$nama_file   	= $_FILES['foto']['name'];

		if(!empty($nama_file)){
		  $nama_file  = urlencode(generator(6)."_".$_FILES['foto']['name']);
			if (!empty($lokasi_file) AND in_array($tipe_file, unserialize(MIME_TYPE_GAMBAR))){
				UploadFoto($nama_file,$old_foto,$tipe_file);
			} else {
				$nama_file = '';
				$_SESSION['success'] = -1;
				$_SESSION['error_msg'] = " -> Jenis file salah: " . $tipe_file;
			}
		}

		$sql = "INSERT INTO tweb_desa_pamong (pamong_nama,pamong_nip,pamong_nik,jabatan,pamong_status,pamong_tgl_terdaftar,foto)
				VALUES (?,?,?,?,?,NOW(),?)";

		$outp = $this->db->query($sql, array($nama,$nip,$nik,$jabatan,$status,$nama_file));

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function update($id=0){
		unset($_SESSION['validation_error']);
		$_SESSION['success'] = 1;;
		unset($_SESSION['error_msg']);
		$lokasi_file = $_FILES['foto']['tmp_name'];
		$tipe_file   = $_FILES['foto']['type'];
		$nama_file   = $_FILES['foto']['name'];
		$old_foto    = $this->input->post('old_foto');
		if(!empty($nama_file)){
		  $nama_file  = urlencode(generator(6)."_".$_FILES['foto']['name']);
			if (!empty($lokasi_file) AND in_array($tipe_file, unserialize(MIME_TYPE_GAMBAR))){
				UploadFoto($nama_file,$old_foto,$tipe_file);
			} else {
				$nama_file = '';
				$_SESSION['success'] = -1;
				$_SESSION['error_msg'] = " -> Jenis file salah: " . $tipe_file;
			}
		}

		$data = array(
			"pamong_nip" => $this->input->post('pamong_nip'),
			"pamong_nama" =>$this->input->post('pamong_nama'),
			"pamong_nik" => $this->input->post('pamong_nik'),
			"jabatan" => $this->input->post('jabatan'),
			"pamong_status" => $this->input->post('pamong_status')
		);
		if(!empty($nama_file)){
			$data['foto'] = $nama_file;
		}
		$this->db->where("pamong_id", $id)->update('tweb_desa_pamong', $data);
	}

	function delete($id=''){
		$foto = $this->db->select('foto')->where('pamong_id',$id)->get('tweb_desa_pamong')->row()->foto;
		if (!empty($foto)) {
			unlink(LOKASI_USER_PICT.$foto);
			unlink(LOKASI_USER_PICT.'kecil_'.$foto);
		}
		$sql  = "DELETE FROM tweb_desa_pamong WHERE pamong_id=?";
		$outp = $this->db->query($sql,array($id));
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

	function ttd($id='',$val=0){
		if ($val==1){
			// Hanya satu pamong yang boleh digunakan sebagai ttd default
			$this->db->where('pamong_ttd',1)->update('tweb_desa_pamong',array('pamong_ttd'=>0));
		}
		$this->db->where('pamong_id',$id)->update('tweb_desa_pamong',array('pamong_ttd'=>$val));
	}

}
?>
