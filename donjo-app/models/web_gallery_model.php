<?php class Web_Gallery_Model extends CI_Model{

	function __construct(){
		parent::__construct();
	}

	function autocomplete(){
		$sql   = "SELECT gambar FROM gambar_gallery
					UNION SELECT nama FROM gambar_gallery";
		$query = $this->db->query($sql);
		$data  = $query->result_array();

		$i=0;
		$outp='';
		while($i<count($data)){
			$outp .= ",'" .$data[$i]['gambar']. "'";
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
			$search_sql= " AND (gambar LIKE '$kw' OR nama LIKE '$kw')";
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

		$sql      = "SELECT COUNT(id) AS id FROM gambar_gallery WHERE tipe = 0 ";
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

		switch($o){
			case 1: $order_sql = ' ORDER BY nama'; break;
			case 2: $order_sql = ' ORDER BY nama DESC'; break;
			case 3: $order_sql = ' ORDER BY enabled'; break;
			case 4: $order_sql = ' ORDER BY enabled DESC'; break;
			case 5: $order_sql = ' ORDER BY tgl_upload'; break;
			case 6: $order_sql = ' ORDER BY tgl_upload DESC'; break;
			default:$order_sql = ' ORDER BY id';
		}

		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;

		$sql   = "SELECT * FROM gambar_gallery WHERE tipe = 0  ";

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
				$data[$i]['aktif']="Yes";
			else
				$data[$i]['aktif']="No";

			$i++;
			$j++;
		}
		return $data;
	}

	function insert(){
		$_SESSION['success'] = 1;
		$_SESSION['error_msg'] = '';

	  $lokasi_file = $_FILES['gambar']['tmp_name'];
	  $tipe_file   = $_FILES['gambar']['type'];
	  $nama_file   = urlencode($_FILES['gambar']['name']);
		$data = $_POST;

	  if (!empty($lokasi_file)){
			if ($tipe_file == "image/jpeg" OR $tipe_file == "image/pjpeg"){
				UploadGallery($nama_file);
				$data['gambar'] = $nama_file;
			} else {
				$_SESSION['error_msg'].= " -> Jenis file salah: " . $tipe_file;
				$_SESSION['success']=-1;
			}
		}
		if($_SESSION['grup'] == 4){
			$data['enabled'] = 2;
		}

		// Bolehkan gallery kosong, tidak ada gambarnya
		$outp = $this->db->insert('gambar_gallery',$data);
		if(!$outp) $_SESSION['success'] = -1;
	}

	function update($id=0){
		$_SESSION['success'] = 1;
		$_SESSION['error_msg'] = '';

	  $x = $_POST;
	  $lokasi_file = $_FILES['gambar']['tmp_name'];
	  $tipe_file   = $_FILES['gambar']['type'];
	  $nama_file   = urlencode($_FILES['gambar']['name']);
	  $old_gambar  = $x['old_gambar'];

	  if (!empty($lokasi_file)){
			if ($tipe_file == "image/jpeg" OR $tipe_file == "image/pjpeg"){
				UploadGallery($nama_file,$old_gambar);
				unset($x['old_gambar']);
			} else {
				$_SESSION['error_msg'].= " -> Jenis file salah: " . $tipe_file;
				$_SESSION['success']=-1;
				$nama_file = $x['old_gambar'];
			}
	  }

		$data['gambar'] = $nama_file;
		$data['nama'] = $_POST['nama'];
		$this->db->where('id',$id);
		$outp = $this->db->update('gambar_gallery',$data);
		if(!$outp) $_SESSION['success'] = -1;
	}

	function delete($id=''){
		$sql  = "DELETE FROM gambar_gallery WHERE id=?";
		$outp = $this->db->query($sql,array($id));

		$sql  = "DELETE FROM gambar_gallery WHERE parrent=?";
		$outp = $this->db->query($sql,array($id));

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function delete_all(){
		$id_cb = $_POST['id_cb'];

		if(count($id_cb)){
			foreach($id_cb as $id){
				$sql  = "DELETE FROM gambar_gallery WHERE id=?";
				$outp = $this->db->query($sql,array($id));

				$sql  = "DELETE FROM gambar_gallery WHERE parrent=?";
				$outp = $this->db->query($sql,array($id));
			}
		}
		else $outp = false;

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function gallery_lock($id='',$val=0){

		$sql  = "UPDATE gambar_gallery SET enabled=? WHERE id=?";
		$outp = $this->db->query($sql, array($val,$id));

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function gallery_slider($id='',$val=0){
		if ($val==1){
			// Hanya satu gallery yang boleh tampil di slider
			$this->db->where('slider',1)->update('gambar_gallery',array('slider'=>0));
		}
		$this->db->where('id',$id)->update('gambar_gallery',array('slider'=>$val));
	}

	function get_gallery($id=0){
		$sql   = "SELECT * FROM gambar_gallery WHERE id=?";
		$query = $this->db->query($sql,$id);
		$data  = $query->row_array();
		return $data;
	}

	function gallery_show(){
		$sql   = "SELECT * FROM gambar_gallery WHERE enabled=?";
		$query = $this->db->query($sql,1);
		$data  = $query->result_array();
		return $data;
	}

	function paging2($gal=0,$p=1){

		$sql      = "SELECT COUNT(id) AS id FROM gambar_gallery WHERE parrent = ? AND tipe = 2 ";
		$sql     .= $this->search_sql();
		$query    = $this->db->query($sql,$gal);
		$row      = $query->row_array();
		$jml_data = $row['id'];

		$this->load->library('paging');
		$cfg['page']     = $p;
		$cfg['per_page'] = $_SESSION['per_page'];
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);

		return $this->paging;
	}

	function list_slide_galeri(){
		$gallery_slide_id = $this->db->select('id')->where('slider',1)->limit(1)->get('gambar_gallery')->row()->id;
		$slide_galeri = $this->db->select('gambar')->where(array('parrent'=>$gallery_slide_id, 'tipe'=>2))->get('gambar_gallery')->result_array();
		return $slide_galeri;
	}

	function list_sub_gallery($gal=1,$offset=0,$limit=500){

		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;
		$sql   = "SELECT * FROM gambar_gallery WHERE parrent = ? AND tipe = 2 ";

		$sql .= $this->search_sql();
		$sql .= $this->filter_sql();

		$sql .= $paging_sql;
		$query = $this->db->query($sql,$gal);
		$data=$query->result_array();

		$i=0;
		while($i<count($data)){
			$data[$i]['no']=$i+1;

			if($data[$i]['enabled']==1)
				$data[$i]['aktif']="Yes";
			else
				$data[$i]['aktif']="No";

			$i++;
		}
		return $data;
	}

	function insert_sub_gallery($parrent=0){
		  $lokasi_file = $_FILES['gambar']['tmp_name'];
		  $tipe_file   = $_FILES['gambar']['type'];
		  $nama_file   = urlencode($_FILES['gambar']['name']);
		  if (!empty($lokasi_file)){
			if ($tipe_file == "image/jpeg" OR $tipe_file == "image/pjpeg" OR $tipe_file == "image/png"){
				UploadGallery($nama_file);
				$data = $_POST;
				$data['gambar'] = $nama_file;
				$data['parrent'] = $parrent;
				$data['tipe'] = 2;

				if($_SESSION['grup'] == 4){
					$data['enabled'] = 2;
				}

				$outp = $this->db->insert('gambar_gallery',$data);
				if($outp) $_SESSION['success']=1;
			} else {
				$_SESSION['success']=-1;
			}
		  }else{
			$data = $_POST;
			unset($data['gambar']);
			$data['parrent'] = $parrent;
			$data['tipe'] = 2;
			$outp = $this->db->insert('gambar_gallery',$data);
}
	if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

		function update_sub_gallery($id=0){
		  $x = $_POST;
		  $lokasi_file = $_FILES['gambar']['tmp_name'];
		  $tipe_file   = $_FILES['gambar']['type'];
		  $nama_file   = urlencode($_FILES['gambar']['name']);
		  $old_gambar  = $x['old_gambar'];
		  if (!empty($nama_file)){
			if ($tipe_file == "image/jpeg" OR $tipe_file == "image/pjpeg"){
				UploadGallery($nama_file,$old_gambar);
				unset($x['old_gambar']);
			}} else {
				$_SESSION['success']=-1;
				$nama_file = $x['old_gambar'];

		  }

		$data['gambar'] = $nama_file;
		$data['nama'] = $_POST['nama'];
		$this->db->where('id',$id);
		$outp = $this->db->update('gambar_gallery',$data);
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
}
?>
