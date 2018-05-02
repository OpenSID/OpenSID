<?php class Plan_line_model extends CI_Model{

	function __construct(){
		parent::__construct();
	}

	function autocomplete(){
		$sql   = "SELECT nama FROM line";
		$query = $this->db->query($sql);
		$data  = $query->result_array();

		$i=0;
		$outp='';
		while($i<count($data)){
			$outp .= ',"'.$data[$i]['nama'].'"';
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
			$search_sql= " AND (nama LIKE '$kw')";
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

		$sql      = "SELECT COUNT(id) AS id FROM line  WHERE tipe = 0 ";
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
			default:$order_sql = ' ORDER BY id';
		}

		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;

		$sql   = "SELECT * FROM line WHERE tipe = 0  ";

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


	function insert(){
				$data = $_POST;
		  $lokasi_file = $_FILES['simbol']['tmp_name'];
		  $tipe_file   = $_FILES['simbol']['type'];
		  $nama_file   = $_FILES['simbol']['name'];
		  $nama_file   = str_replace(' ', '-', $nama_file); 	 // normalkan nama file
		  if (!empty($lokasi_file)){
			if ($tipe_file == "image/png" OR $tipe_file == "image/gif"){
				UploadSimbol($nama_file);
				$data['simbol'] = $nama_file;
				$outp = $this->db->insert('line',$data);
			}
		  }else {
				unset($data['simbol']);
				$outp = $this->db->insert('line',$data);
			}
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function update($id=0){
		  $data = $_POST;
		  $lokasi_file = $_FILES['simbol']['tmp_name'];
		  $tipe_file   = $_FILES['simbol']['type'];
		  $nama_file   = $_FILES['simbol']['name'];
		  $nama_file   = str_replace(' ', '-', $nama_file); 	 // normalkan nama file
		  if (!empty($lokasi_file)){
			if ($tipe_file == "image/png" OR $tipe_file == "image/gif"){
				UploadSimbol($nama_file);
				$data['simbol'] = $nama_file;
				$this->db->where('id',$id);
				$outp = $this->db->update('line',$data);
			}
			$_SESSION['success']=1;
		  }

			unset($data['simbol']);
			$this->db->where('id',$id);
			$outp = $this->db->update('line',$data);

	if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
}

	function delete($id=''){
		$sql  = "DELETE FROM line WHERE id=?";
		$outp = $this->db->query($sql,array($id));

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function delete_all(){
		$id_cb = $_POST['id_cb'];

		if(count($id_cb)){
			foreach($id_cb as $id){
				$sql  = "DELETE FROM line WHERE id=?";
				$outp = $this->db->query($sql,array($id));
			}
		}
		else $outp = false;

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function list_sub_line($line=1){

		$sql   = "SELECT * FROM line WHERE parrent = ? AND tipe = 2 ";

		$query = $this->db->query($sql,$line);
		$data=$query->result_array();

		$i=0;
		while($i<count($data)){
			$data[$i]['no']=$i+1;

			if($data[$i]['enabled']==1)
				$data[$i]['aktif']="Ya";
			else
				$data[$i]['aktif']="Tidak";

			$i++;
		}
		return $data;
	}

	function insert_sub_line($parrent=0){
		  $lokasi_file = $_FILES['simbol']['tmp_name'];
		  $tipe_file   = $_FILES['simbol']['type'];
		  $nama_file   = $_FILES['simbol']['name'];
		  $nama_file   = str_replace(' ', '-', $nama_file); 	 // normalkan nama file
		  if (!empty($lokasi_file)){
			if ($tipe_file == "image/png" OR $tipe_file == "image/gif"){
				UploadSimbol($nama_file);
				$data = $_POST;
				$data['simbol'] = $nama_file;
				$data['parrent'] = $parrent;
				$data['tipe'] = 2;
				$outp = $this->db->insert('line',$data);
				if($outp) $_SESSION['success']=1;
			} else {
				$_SESSION['success']=-1;
			}
		  }else{
			$data = $_POST;
			unset($data['simbol']);
			$data['parrent'] = $parrent;
			$data['tipe'] = 2;
			$outp = $this->db->insert('line',$data);
}
	if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function update_sub_line($id=0){
		  $data = $_POST;
		  $lokasi_file = $_FILES['simbol']['tmp_name'];
		  $tipe_file   = $_FILES['simbol']['type'];
		  $nama_file   = $_FILES['simbol']['name'];
		  $nama_file   = str_replace(' ', '-', $nama_file); 	 // normalkan nama file
		  if (!empty($lokasi_file)){
			if ($tipe_file == "image/png" OR $tipe_file == "image/gif"){
				UploadSimbol($nama_file);
				$data['simbol'] = $nama_file;
				$this->db->where('id',$id);
				$outp = $this->db->update('line',$data);
			}
			$_SESSION['success']=1;
		  }else{

			unset($data['simbol']);
			$this->db->where('id',$id);
			$outp = $this->db->update('line',$data);
	}
	if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
}

	function delete_sub_line($id=''){
		$sql  = "DELETE FROM line WHERE id=?";
		$outp = $this->db->query($sql,array($id));

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function delete_all_sub_line(){
		$id_cb = $_POST['id_cb'];

		if(count($id_cb)){
			foreach($id_cb as $id){
				$sql  = "DELETE FROM line WHERE id=?";
				$outp = $this->db->query($sql,array($id));
			}
		}
		else $outp = false;

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function line_lock($id='',$val=0){

		$sql  = "UPDATE line SET enabled=? WHERE id=?";
		$outp = $this->db->query($sql, array($val,$id));

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function get_line($id=0){
		$sql   = "SELECT * FROM line WHERE id=?";
		$query = $this->db->query($sql,$id);
		$data  = $query->row_array();
		return $data;
	}

	function line_show(){
		$sql   = "SELECT * FROM line WHERE enabled=?";
		$query = $this->db->query($sql,1);
		$data  = $query->result_array();
		return $data;

	}

	function list_line_atas(){

		//$sql   = "SELECT m.*,s.nama as sub_line,s.simbol as s_simbol FROM line m LEFT JOIN line s ON m.id = s.parrent WHERE m.parrent = 1 AND m.enabled = 1 AND (s.enabled = 1 OR s.enabled IS NULL) AND m.tipe = 1";

		$sql   = "SELECT m.* FROM line m WHERE m.parrent = 1 AND m.enabled = 1 AND m.tipe = 1";

		$query = $this->db->query($sql);
		$data=$query->result_array();
		$url = site_url("first");
		$i=0;
		while($i<count($data)){
				$data[$i]['line'] = "<li><a href='$url/".$data[$i]['simbol']."'>".$data[$i]['nama']."</a>";

				$sql2   = "SELECT s.* FROM line s WHERE s.parrent = ? AND s.enabled = 1 AND s.tipe = 3";
				$query = $this->db->query($sql2,$data[$i]['id']);
				$data2=$query->result_array();

				if($data2){
					$data[$i]['line'] = $data[$i]['line']."<ul>";
					$j=0;
					while($j<count($data2)){
						$data[$i]['line'] = $data[$i]['line']."<li><a href='$url/".$data2[$j]['simbol']."'>".$data2[$j]['nama']."</a></li>";
						$j++;
					}
					$data[$i]['line'] = $data[$i]['line']."</ul>";
				}
				$data[$i]['line'] = $data[$i]['line']."</li>";
			$i++;
		}
		return $data;
	}

	function list_line_kiri(){

		//$sql   = "SELECT m.*,s.nama as sub_line,s.simbol as s_simbol FROM line m LEFT JOIN line s ON m.id = s.parrent WHERE m.parrent = 1 AND m.enabled = 1 AND (s.enabled = 1 OR s.enabled IS NULL) AND m.tipe = 1";

		$sql   = "SELECT m.* FROM line m WHERE m.parrent = 1 AND m.enabled = 1 AND m.tipe = 2";

		$query = $this->db->query($sql);
		$data=$query->result_array();
		$url = site_url("first");
		$i=0;
		while($i<count($data)){
				$data[$i]['line'] = "<li><a href='$url/".$data[$i]['simbol']."'>".$data[$i]['nama']."</a>";

				$sql2   = "SELECT s.* FROM line s WHERE s.parrent = ? AND s.enabled = 1 AND s.tipe = 3";
				$query = $this->db->query($sql2,$data[$i]['id']);
				$data2=$query->result_array();

				if($data2){
					$data[$i]['line'] = $data[$i]['line']."<ul>";
					$j=0;
					while($j<count($data2)){
						$data[$i]['line'] = $data[$i]['line']."<li><a href='$url/".$data2[$j]['simbol']."'>".$data2[$j]['nama']."</a></li>";
						$j++;
					}
					$data[$i]['line'] = $data[$i]['line']."</ul>";
				}
				$data[$i]['line'] = $data[$i]['line']."</li>";
			$i++;
		}
		return $data;
	}

}
?>
