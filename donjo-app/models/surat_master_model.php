<?php class surat_master_model extends CI_Model{

	function __construct(){
		parent::__construct();
	}

	function autocomplete(){
		$sql   = "SELECT nama FROM tweb_surat_format";
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
			$search_sql= " AND (u.pertanyaan LIKE '$kw' OR u.pertanyaan LIKE '$kw')";
			return $search_sql;
			}
		}

	function filter_sql(){
		if(isset($_SESSION['filter'])){
			$kf = $_SESSION['filter'];
			$filter_sql= " AND u.act_analisis = $kf";
		return $filter_sql;
		}
	}

	function master_sql(){
		if(isset($_SESSION['analisis_master'])){
			$kf = $_SESSION['analisis_master'];
			$filter_sql= " AND u.id_master = $kf";
		return $filter_sql;
		}
	}

	function tipe_sql(){
		if(isset($_SESSION['tipe'])){
			$kf = $_SESSION['tipe'];
			$filter_sql= " AND u.id_tipe = $kf";
		return $filter_sql;
		}
	}

	function kategori_sql(){
		if(isset($_SESSION['kategori'])){
			$kf = $_SESSION['kategori'];
			$filter_sql= " AND u.id_kategori = $kf";
		return $filter_sql;
		}
	}

	function paging($p=1,$o=0){

		$sql      = "SELECT COUNT(id) AS id FROM tweb_surat_format u WHERE 1";
		$sql     .= $this->search_sql();
		$sql .= $this->filter_sql();

		$sql .= $this->tipe_sql();
		$sql .= $this->kategori_sql();
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
			case 1: $order_sql = ' ORDER BY u.nomor'; break;
			case 2: $order_sql = ' ORDER BY u.nomor DESC'; break;
			case 3: $order_sql = ' ORDER BY u.pertanyaan'; break;
			case 4: $order_sql = ' ORDER BY u.pertanyaan DESC'; break;
			case 5: $order_sql = ' ORDER BY u.id_kategori'; break;
			case 6: $order_sql = ' ORDER BY u.id_kategori DESC'; break;
			default:$order_sql = ' ORDER BY u.id';
		}

		//Paging SQL
		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;

		//Main Query
		$sql   = "SELECT u.* FROM tweb_surat_format u  WHERE 1 ";

		$sql .= $this->search_sql();
		$sql .= $this->filter_sql();

		$sql .= $this->tipe_sql();
		$sql .= $this->kategori_sql();
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

		$data['url_surat'] = str_replace(" ","_",$data['nama']);
		$data['url_surat'] = strtolower($data['url_surat']);
		$data['url_surat'] = "surat_".$data['url_surat'];
		$outp = $this->db->insert('tweb_surat_format',$data);

		$path="surat/".$data['url_surat']."/";

		if (!file_exists($path)) {
			mkdir($path, 0777, true);
		}

		//doc
		$raw="surat\\raw\\";
		$raw_path = "".str_replace("\\","/",$raw);
		$file = $raw_path."template.rtf";
		$handle = fopen($file,'r');

		$buffer = stream_get_contents($handle);
		//$handle = fopen($path.$data['url_surat'],'w+');

		$berkas = $path.$data['url_surat'].".rtf";
		$handle = fopen($berkas,'w+');
		fwrite($handle,$buffer);
		fclose($handle);

		//form
		$mypath="donjo-app\\views\\surat\\form\\";
		$path_form = "".str_replace("\\","/",$mypath)."/";

		$raw="surat\\raw\\";
		$raw_path = "".str_replace("\\","/",$raw);
		$file = $raw_path."form.raw";
		$handle = fopen($file,'r');

		$buffer = stream_get_contents($handle);
		//$handle = fopen($path_form.$data['url_surat'],'w+');

		$berkas = $path_form.$data['url_surat'].".php";
		$handle = fopen($berkas,'w+');
		$buffer=str_replace("[nama_surat]","Surat $data[nama]",$buffer);
		fwrite($handle,$buffer);
		fclose($handle);

		//cetak
		$mypath="donjo-app\\views\\surat\\print\\";
		$path_form = "".str_replace("\\","/",$mypath)."/";

		$raw="surat\\raw\\";
		$raw_path = "".str_replace("\\","/",$raw);
		$file = $raw_path."print.raw";
		$handle = fopen($file,'r');

		$buffer = stream_get_contents($handle);
		//$handle = fopen($path_form.$data['url_surat'],'w+');

		$berkas = $path_form."print_".$data['url_surat'].".php";
		$handle = fopen($berkas,'w+');
		$nama_surat = strtoupper($data['nama']);
		$buffer=str_replace("[nama_surat]","SURAT $nama_surat",$buffer);
		fwrite($handle,$buffer);
		fclose($handle);

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function update($id=0){
		$data = $_POST;
		$this->db->where('id',$id);
		$outp = $this->db->update('tweb_surat_format',$data);

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function upload($url=""){
		$_SESSION['error_msg'] = '';
		$tipe_file   = $_FILES['foto']['type'];
		$mime_type_rtf = array("application/rtf", "text/rtf", "application/msword");
		if(!in_array($tipe_file, $mime_type_rtf)){
			$_SESSION['error_msg'].= " -> Jenis file salah: " . $tipe_file;
			$_SESSION['success']=-1;
		} else {
			// Upload ke folder surat export ubahan desa
			$vdir_upload = LOKASI_SURAT_EXPORT_DESA . $url . ".rtf";
			move_uploaded_file($_FILES["foto"]["tmp_name"], $vdir_upload);
			$_SESSION['success']=1;
		}

	}

	function delete($id=''){
		$sql  = "DELETE FROM tweb_surat_format WHERE id=?";
		$outp = $this->db->query($sql,array($id));

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function delete_all(){
		$id_cb = $_POST['id_cb'];

		if(count($id_cb)){
			foreach($id_cb as $id){
				$sql  = "DELETE FROM tweb_surat_format WHERE id=?";
				$outp = $this->db->query($sql,array($id));
			}
		}
		else $outp = false;

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function list_atribut($id=0){
		$sql   = "SELECT * FROM tweb_surat_atribut WHERE id_surat = ?";
		$query = $this->db->query($sql,$id);
		$data= $query->result_array();

		//Formating Output
		$i=0;
		while($i<count($data)){
			$data[$i]['no']=$i+1;

			$i++;
		}
		return $data;
	}

	function get_surat_format($id=0){
		$sql   = "SELECT * FROM tweb_surat_format WHERE id=?";
		$query = $this->db->query($sql,$id);
		$data  = $query->row_array();
		return $data;
	}

	function get_analisis_master(){
		$sql   = "SELECT * FROM analisis_master WHERE id=?";
		$query = $this->db->query($sql,$_SESSION['analisis_master']);
		return $query->row_array();
	}

	function get_tweb_surat_atribut($id=''){
		$sql   = "SELECT * FROM tweb_surat_atribut WHERE id=?";
		$query = $this->db->query($sql,$id);
		return $query->row_array();
	}

	function list_tipe(){
		$sql   = "SELECT * FROM analisis_tipe_indikator";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	function list_kategori(){
		$sql   = "SELECT u.* FROM analisis_kategori_indikator u WHERE 1";
		$sql .= $this->master_sql();
		$query = $this->db->query($sql);
		return $query->result_array();
	}

  function get_kode_isian($surat) {
		// Lokasi instalasi SID mungkin di sub-folder
    include FCPATH . '/vendor/simple_html_dom.php';
    $html = file_get_html(FCPATH . "/donjo-app/views/surat/form/".$surat['url_surat'].".php");

    // Kumpulkan semua isian (tag input) di form surat
    // Asumsi di form surat, struktur input seperti ini
    // <tr>
    // 		<th>Keterangan Isian</th>
    // 		<td><input><td>
    // </tr>
    $inputs = array();
    foreach($html->find('input') as $input) {
      if ($input->type == 'hidden') {
        continue;
      }
      $inputs[$input->name] = $input->parent->parent->children[0]->innertext;
    }
    foreach($html->find('select') as $input) {
      if ($input->type == 'hidden') {
        continue;
      }
      $inputs[$input->name] = $input->parent->parent->children[0]->innertext;
    }
    $html->clear();
    unset($html);
    return $inputs;
  }

}

?>
