<?php

class Web_kategori_model extends CI_Model{

	function __construct(){
		parent::__construct();
	}

	function autocomplete(){
		$sql   = "SELECT kategori FROM kategori WHERE parrent =0";
		$query = $this->db->query($sql);
		$data  = $query->result_array();

		$i=0;
		$outp='';
		while($i<count($data)){
			$outp .= ',"'.$data[$i]['kategori'].'"';
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
			$search_sql= " AND (kategori LIKE '$kw')";
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

		$sql      = "SELECT COUNT(id) AS id FROM kategori WHERE parrent = 0";
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
			case 1: $order_sql = ' ORDER BY kategori'; break;
			case 2: $order_sql = ' ORDER BY kategori DESC'; break;
			case 3: $order_sql = ' ORDER BY enabled'; break;
			case 4: $order_sql = ' ORDER BY enabled DESC'; break;
			default:$order_sql = ' ORDER BY urut';
		}

		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;
		$sql   = "SELECT k.*,k.kategori AS kategori FROM kategori k WHERE parrent = 0";

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
		$data['enabled'] = 1;
		$data['urut'] = $this->urut_max() + 1;
		$outp = $this->db->insert('kategori',$data);
		if($outp) $_SESSION['success']=1;
		else $_SESSION['success']=-1;

	}

	function update($id=0){
		$data = $_POST;
		$this->db->where('id',$id);
		$outp = $this->db->update('kategori',$data);
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function delete($id=''){
		$sql  = "DELETE FROM kategori WHERE id=?";
		$outp = $this->db->query($sql,array($id));

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function delete_all(){
		$id_cb = $_POST['id_cb'];

		if(count($id_cb)){
			foreach($id_cb as $id){
				$sql  = "DELETE FROM kategori WHERE id=?";
				$outp = $this->db->query($sql,array($id));
			}
		}
		else $outp = false;

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function list_sub_kategori($kategori=1){

		$sql   = "SELECT * FROM kategori WHERE parrent = ? ORDER BY urut";

		$query = $this->db->query($sql,$kategori);
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

	function list_link(){

		$sql   = "SELECT a.* FROM artikel a INNER JOIN kategori k ON a.id_kategori=k.id WHERE tipe ='2'";

		$query = $this->db->query($sql);
		$data=$query->result_array();

		$i=0;
		while($i<count($data)){
			$data[$i]['no']=$i+1;
			$i++;
		}
		return $data;
	}

	function list_kategori($o=""){
		if (empty($o)) $urut = "urut"; else $urut = $o;

		$sql   = "SELECT k.id,k.kategori AS kategori FROM kategori k WHERE 1 ORDER BY $urut";

		$query = $this->db->query($sql);
		$data=$query->result_array();

		$i=0;
		while($i<count($data)){
			$data[$i]['no']=$i+1;
			$data[$i]['judul']=$data[$i]['kategori'];
			$i++;
		}
		return $data;
	}

	function insert_sub_kategori($kategori=0){
		$data = $_POST;

		$data['parrent'] = $kategori;
		$data['enabled'] = 1;
		$data['urut'] = $this->urut_max($kategori) + 1;
		$outp = $this->db->insert('kategori',$data);
		if($outp) $_SESSION['success']=1;
		else $_SESSION['success']=-1;
	}

	function update_sub_kategori($id=0){
		$data = $_POST;

		$this->db->where('id',$id);
		$outp = $this->db->update('kategori',$data);
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function delete_sub_kategori($id=''){
		$sql  = "DELETE FROM kategori WHERE id=?";
		$outp = $this->db->query($sql,array($id));

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function delete_all_sub_kategori(){
		$id_cb = $_POST['id_cb'];

		if(count($id_cb)){
			foreach($id_cb as $id){
				$sql  = "DELETE FROM kategori WHERE id=?";
				$outp = $this->db->query($sql,array($id));
			}
		}
		else $outp = false;

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function kategori_lock($id='',$val=0){

		$sql  = "UPDATE kategori SET enabled=? WHERE id=?";
		$outp = $this->db->query($sql, array($val,$id));

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function get_kategori($id=0){
		$sql   = "SELECT * FROM kategori WHERE id=?";
		$query = $this->db->query($sql,$id);
		$data  = $query->row_array();
		return $data;
	}

	function kategori_show(){
		$sql   = "SELECT * FROM kategori WHERE enabled=?";
		$query = $this->db->query($sql,1);
		$data  = $query->result_array();
		return $data;

	}

	function list_kategori_atas(){

		//$sql   = "SELECT m.*,s.kategori as sub_kategori,s.link as s_link FROM kategori m LEFT JOIN kategori s ON m.id = s.parrent WHERE m.parrent = 1 AND m.enabled = 1 AND (s.enabled = 1 OR s.enabled IS NULL) AND m.tipe = 1";

		$sql   = "SELECT m.* FROM kategori m WHERE m.parrent = 1 AND m.enabled = 1 AND m.tipe = 1";

		$query = $this->db->query($sql);
		$data=$query->result_array();
		$url = site_url("first");
		$i=0;
		while($i<count($data)){
				$data[$i]['kategori'] = "<li><a href='$url/".$data[$i]['link']."'>".$data[$i]['kategori']."</a>";

				$sql2   = "SELECT s.* FROM kategori s WHERE s.parrent = ? AND s.enabled = 1 AND s.tipe = 3";
				$query = $this->db->query($sql2,$data[$i]['id']);
				$data2=$query->result_array();

				if($data2){
					$data[$i]['kategori'] = $data[$i]['kategori']."<ul>";
					$j=0;
					while($j<count($data2)){
						$data[$i]['kategori'] = $data[$i]['kategori']."<li><a href='$url/".$data2[$j]['link']."'>".$data2[$j]['kategori']."</a></li>";
						$j++;
					}
					$data[$i]['kategori'] = $data[$i]['kategori']."</ul>";
				}
				$data[$i]['kategori'] = $data[$i]['kategori']."</li>";
			$i++;
		}
		return $data;
	}

	function list_kategori_kiri(){

		//$sql   = "SELECT m.*,s.kategori as sub_kategori,s.link as s_link FROM kategori m LEFT JOIN kategori s ON m.id = s.parrent WHERE m.parrent = 1 AND m.enabled = 1 AND (s.enabled = 1 OR s.enabled IS NULL) AND m.tipe = 1";

		$sql   = "SELECT m.* FROM kategori m WHERE m.parrent = 1 AND m.enabled = 1 AND m.tipe = 2";

		$query = $this->db->query($sql);
		$data=$query->result_array();
		$url = site_url("first");
		$i=0;
		while($i<count($data)){
				$data[$i]['kategori'] = "<li><a href='$url/".$data[$i]['link']."'>".$data[$i]['kategori']."</a>";

				$sql2   = "SELECT s.* FROM kategori s WHERE s.parrent = ? AND s.enabled = 1 AND s.tipe = 3";
				$query = $this->db->query($sql2,$data[$i]['id']);
				$data2=$query->result_array();

				if($data2){
					$data[$i]['kategori'] = $data[$i]['kategori']."<ul>";
					$j=0;
					while($j<count($data2)){
						$data[$i]['kategori'] = $data[$i]['kategori']."<li><a href='$url/".$data2[$j]['link']."'>".$data2[$j]['kategori']."</a></li>";
						$j++;
					}
					$data[$i]['kategori'] = $data[$i]['kategori']."</ul>";
				}
				$data[$i]['kategori'] = $data[$i]['kategori']."</li>";
			$i++;
		}
		return $data;
	}

  function urut_max($kategori=''){
    $this->db->select_max('urut');
    if ($kategori != '')
	    $this->db->where('parrent', $kategori);
	  else
	    $this->db->where('parrent', 0);
    $query = $this->db->get('kategori');
    $kategori = $query->row_array();
    return $kategori['urut'];
  }

	function urut_semua($kategori){
		if ($kategori != '') {
			$sql = "SELECT urut, COUNT(*) c FROM kategori WHERE parrent = ? GROUP BY urut HAVING c > 1";
			$query = $this->db->query($sql, $kategori);
	}
		else {
			$sql = "SELECT urut, COUNT(*) c FROM kategori WHERE parrent = 0 GROUP BY urut HAVING c > 1";
			$query = $this->db->query($sql);
		}
		$urut_duplikat = $query->result_array();
		if ($urut_duplikat) {
			$this->db->select("id");
			if ($kategori != '')
				$this->db->where("parrent", $kategori);
			else
				$this->db->where("parrent", 0);
			$this->db->order_by("urut");
			$q = $this->db->get('kategori');
			$kategoris = $q->result_array();
			for ($i=0; $i<count($kategoris); $i++){
				$this->db->where('id', $kategoris[$i]['id']);
				$data['urut'] = $i + 1;
				$this->db->update('kategori', $data);
			}
		}
	}

	// $arah:
	//		1 - turun
	// 		2 - naik
	function urut($id, $arah, $kategori=''){
		$this->urut_semua($kategori);
		$this->db->where('id', $id);
		$q = $this->db->get('kategori');
		$kategori1 = $q->row_array();

		$this->db->select("id, urut");
		if ($kategori != '')
			$this->db->where(array("parrent" => $kategori));
		else
			$this->db->where(array("parrent" => 0));
		$this->db->order_by("urut");
		$q = $this->db->get('kategori');
		$kategoris = $q->result_array();
		for ($i=0; $i<count($kategoris); $i++){
			if ($kategoris[$i]['id'] == $id) {
				break;
			}
		}

		if ($arah == 1) {
			if ($i >= count($kategoris) - 1) return;
			$kategori2 = $kategoris[$i+1];
		}
		if ($arah == 2) {
			if ($i <= 0) return;
			$kategori2 = $kategoris[$i-1];
		}

		// Tukar urutan
		$this->db->where('id', $kategori2['id']);
		$data = array('urut' => $kategori1['urut']);
		$this->db->update('kategori', $data);
		$this->db->where('id', $kategori1['id']);
		$data = array('urut' => $kategori2['urut']);
		$this->db->update('kategori', $data);
	}

}
?>
