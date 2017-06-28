<?php class Web_Widget_Model extends CI_Model{

	function __construct(){
		parent::__construct();
		$this->load->model('first_gallery_m');
		$this->load->model('laporan_penduduk_model');
		$this->load->model('pamong_model');
	}

	function get_widget($id=''){
		$data = $this->db->where('id',$id)->get('widget')->row_array();
		return $data;
	}

	function get_widget_aktif(){

		$data = $this->db->where('enabled',1)->get('widget')->result_array();
		return $data;
	}

	function autocomplete(){
		$sql   = "SELECT judul FROM widget";
		$query = $this->db->query($sql);
		$data  = $query->result_array();

		$i=0;
		$outp='';
		while($i<count($data)){
			$outp .= ",'" .$data[$i]['judul']. "'";
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
			$search_sql= " AND (judul LIKE '$kw' OR isi LIKE '$kw')";
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

		$sql      = "SELECT COUNT(id) FROM widget WHERE 1";
		$sql     .= $this->search_sql();
		$sql 		 .= $this->filter_sql();
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

		$order_sql = ' ORDER BY urut';
		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;

		$sql   = "SELECT * FROM widget WHERE 1";

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
			else {
				$data[$i]['aktif']="Tidak";
				$data[$i]['enabled']=2;
			}
			$teks = htmlentities($data[$i]['isi'])	;
			if(strlen($teks)>150){
				$abstrak = substr($teks,0,150)."...";
			}else{
				$abstrak = $teks;
			}
			$data[$i]['isi'] = $abstrak;

			$i++;
			$j++;
		}
		return $data;
	}

  function urut_max(){
      $this->db->select_max('urut');
      $query = $this->db->get('widget');
      $widget = $query->row_array();
      return $widget['urut'];
  }

	function urut_semua(){
		$sql = "SELECT urut, COUNT(*) c FROM widget GROUP BY urut HAVING c > 1";
		$query = $this->db->query($sql);
		$urut_duplikat = $query->result_array();
		if ($urut_duplikat) {
			$this->db->select("id");
			$this->db->order_by("urut");
			$q = $this->db->get('widget');
			$widgets = $q->result_array();
			for ($i=0; $i<count($widgets); $i++){
				$this->db->where('id', $widgets[$i]['id']);
				$data['urut'] = $i + 1;
				$this->db->update('widget', $data);
			}
		}
	}

	// $arah:
	//		1 - turun
	// 		2 - naik
	function urut($id, $arah){
		$this->urut_semua();
		$this->db->where('id', $id);
		$q = $this->db->get('widget');
		$widget1 = $q->row_array();

		$this->db->select("id, urut");
		$this->db->order_by("urut");
		$q = $this->db->get('widget');
		$widgets = $q->result_array();
		for ($i=0; $i<count($widgets); $i++){
			if ($widgets[$i]['id'] == $id) {
				break;
			}
		}

		if ($arah == 1) {
			if ($i >= count($widgets) - 1) return;
			$widget2 = $widgets[$i+1];
		}
		if ($arah == 2) {
			if ($i <= 0) return;
			$widget2 = $widgets[$i-1];
		}

		// Tukar urutan
		$this->db->where('id', $widget2['id']);
		$data = array('urut' => $widget1['urut']);
		$this->db->update('widget', $data);
		$this->db->where('id', $widget1['id']);
		$data = array('urut' => $widget2['urut']);
		$this->db->update('widget', $data);
	}

	function lock($id='',$val=0){
		$sql  = "UPDATE widget SET enabled=? WHERE id=?";
		$this->db->query($sql, array($val,$id));
	}

	function insert(){
		$_SESSION['success']=1;
		$_SESSION['error_msg'] = "";

		$data = $_POST;
		$data['enabled'] = 2;

		// Widget diberi urutan terakhir
		$data['urut'] = $this->urut_max() + 1;
		if ($data['jenis_widget']==2){
			$data['isi'] = $data['isi-statis'];
		}
		elseif ($data['jenis_widget']==3){
			$data['isi'] = $data['isi-dinamis'];
		}
		unset($data['isi-dinamis']);
		unset($data['isi-statis']);

		$outp = $this->db->insert('widget',$data);
		if(!$outp) $_SESSION['success']=-1;
	}

	function update($id=0){
		$_SESSION['success']=1;
		$_SESSION['error_msg'] = "";

	  $data = $_POST;

		// Widget isinya tergantung jenis widget
		if ($data['jenis_widget']==2){
			$data['isi'] = $data['isi-statis'];
		}
		elseif ($data['jenis_widget']==3){
			$data['isi'] = $data['isi-dinamis'];
		}
		unset($data['isi-dinamis']);
		unset($data['isi-statis']);

		$this->db->where('id',$id);
		$outp = $this->db->update('widget',$data);
		if(!$outp) $_SESSION['success']=-1;
	}

	function delete($id=''){
		$sql  = "DELETE FROM widget WHERE id=? AND jenis_widget <> 1";
		$outp = $this->db->query($sql,array($id));

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function delete_all(){
		$id_cb = $_POST['id_cb'];

		if(count($id_cb)){
			foreach($id_cb as $id){
				$sql  = "DELETE FROM widget WHERE id=? AND jenis_widget <> 1";
				$outp = $this->db->query($sql,array($id));
			}
		}
		else $outp = false;

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	// pengambilan data yang akan ditampilkan di widget
	function get_widget_data(&$data){
		$data['w_gal']  = $this->first_gallery_m->gallery_widget();
		$data['agenda'] = $this->first_artikel_m->agenda_show();
		$data['komen'] = $this->first_artikel_m->komentar_show();
		$data['sosmed'] = $this->first_artikel_m->list_sosmed();
		$data['arsip'] = $this->first_artikel_m->arsip_show();
		$data['aparatur_desa'] = $this->pamong_model->list_data();
		$data['stat_widget'] = $this->laporan_penduduk_model->list_data(4);
	}

}
?>
