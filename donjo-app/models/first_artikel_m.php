<?php

class First_Artikel_M extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	function get_headline(){
		$sql   = "SELECT a.*,u.nama AS owner FROM artikel a LEFT JOIN user u ON a.id_user = u.id WHERE headline = 1 ORDER BY tgl_upload DESC LIMIT 1 ";
		$query = $this->db->query($sql);
		$data  = $query->row_array();
		if(empty($data))
			$data = null;
		else{
			$id = $data['id'];
			//$panjang=str_split($data['isi'],800);
			//$data['isi'] = "<label>".strip_tags($panjang[0])."...</label><a href='".site_url("first/artikel/$id")."'>Baca Selengkapnya</a>";
		}
		return $data;
	}
	function get_teks_berjalan(){
		$sql   = "SELECT a.isi FROM artikel a LEFT JOIN kategori k ON a.id_kategori = k.id WHERE k.kategori = 'teks_berjalan' AND k.enabled = 1";
		$query = $this->db->query($sql);
		$data  = $query->result_array();
		return $data;
	}

	function get_widget(){
		$sql   = "SELECT * FROM widget LIMIT 1 ";
		$query = $this->db->query($sql);
		$data  = $query->result_array();
		return $data;
	}

	function paging($p=1){

		$sql      = "SELECT COUNT(a.id) AS id FROM artikel a
			LEFT JOIN kategori k ON a.id_kategori = k.id
			WHERE ((a.enabled=1) AND (headline <> 1) AND (k.tipe = 1))
			ORDER BY a.tgl_upload DESC";
		$query    = $this->db->query($sql);
		$row      = $query->row_array();
		$jml_data = $row['id'];

		$this->load->library('paging');
		$cfg['page']     = $p;
		$cfg['per_page'] = 8;
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);

		return $this->paging;
	}
	function paging_kat($p=1,$id=0){

		$sql      = "SELECT COUNT(a.id) AS id FROM artikel a LEFT JOIN user u ON a.id_user = u.id LEFT JOIN kategori k ON a.id_kategori = k.id WHERE 1 ";
		if($id!=0)
			$sql .= "AND ((id_kategori = ".$id.") OR (parrent = ".$id."))";
		$query    = $this->db->query($sql);
		$row      = $query->row_array();
		$jml_data = $row['id'];

		$this->load->library('paging');
		$cfg['page']     = $p;
		$cfg['per_page'] = 8;
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);

		return $this->paging;
	}

	function artikel_show($id='0',$offset,$limit){
		if($id > 0){
			$sql   = "SELECT a.*,u.nama AS owner,k.kategori AS kategori FROM artikel a
				LEFT JOIN user u ON a.id_user = u.id
				LEFT JOIN kategori k ON a.id_kategori = k.id WHERE a.enabled=1 AND headline <> 1 AND k.tipe = 1 AND a.id=".$id;
		}else{
			$sql   = "SELECT a.*,u.nama AS owner,k.kategori AS kategori FROM artikel a
				LEFT JOIN user u ON a.id_user = u.id
				LEFT JOIN kategori k ON a.id_kategori = k.id WHERE a.enabled=1 AND headline <> 1 AND k.tipe = 1
				ORDER BY a.tgl_upload DESC LIMIT ".$offset.", ".$limit;
		}

		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			$data  = $query->result_array();

			$i=0;
			while($i<count($data)){
				$id = $data[$i]['id'];
				$teks = strip_tags($data[$i]['isi']);
				$pendek = (strlen($teks)>120)? substr($teks,0,120):$teks;
				$data[$i]['isi_short'] = $pendek;
				$panjang = (strlen($teks)>300)? substr($teks,0,300):$teks;
				$data[$i]['isi'] = "<label>".$panjang."...</label>";
				$i++;
			}
		}else{
			$data = false;
		}
		return $data;
	}

	function arsip_show(){
		$sql   = "SELECT a.*,u.nama AS owner,k.kategori AS kategori FROM artikel a LEFT JOIN user u ON a.id_user = u.id LEFT JOIN kategori k ON a.id_kategori = k.id WHERE a.enabled=?  ORDER BY a.tgl_upload DESC LIMIT 7 ";
		$query = $this->db->query($sql,1);
		$data  = $query->result_array();

		$i=0;
		while($i<count($data)){

			//$judul=str_split($data[$i]['nama'],15);
			//$data[$i]['judul'] = "<h3>".$judul[6]."</h3>";
			$id = $data[$i]['id'];
			//$data['link'] = site_url("first/artikel/$id");

			$pendek=str_split($data[$i]['isi'],100);
			$pendek2=str_split($pendek[0],90);
			$data[$i]['isi_short'] = $pendek2[0]."...";
			$panjang=str_split($data[$i]['isi'],150);
			$data[$i]['isi'] = "<label>".$panjang[0]."...</label><a href='".site_url("first/artikel/$id")."'>baca selengkapnya</a>";
			$i++;
		}
		return $data;
	}


	function paging_arsip($p=1){
		$sql      = "SELECT COUNT(a.id) AS id FROM artikel a LEFT JOIN user u ON a.id_user = u.id LEFT JOIN kategori k ON a.id_kategori = k.id WHERE a.enabled=1";
		$query    = $this->db->query($sql);
		$row      = $query->row_array();
		$jml_data = $row['id'];

		$this->load->library('paging');
		$cfg['page']     = $p;
		$cfg['per_page'] = 20;
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);

		return $this->paging;
	}

	function full_arsip($offset=0,$limit=50){
		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;
		$sql   = "SELECT a.*,u.nama AS owner,k.kategori AS kategori FROM artikel a LEFT JOIN user u ON a.id_user = u.id LEFT JOIN kategori k ON a.id_kategori = k.id WHERE a.enabled=? ORDER BY a.tgl_upload DESC";

		$sql .= $paging_sql;

		$query = $this->db->query($sql,1);
		$data  = $query->result_array();
		if($query->num_rows()>0){
			$i=0;
			while($i<count($data)){
				$nomer = $offset + $i+1;
				$id = $data[$i]['id'];
				$tgl = date("d/m/Y",strtotime($data[$i]['tgl_upload']));
				//$data[$i]['isi_short'] = $pendek2[0]."...";
				//$panjang=str_split($data[$i]['isi'],150);
				$data[$i]['no'] = $nomer;
				$data[$i]['tgl'] = $tgl;
				$data[$i]['isi'] = "<a href='".site_url("first/artikel/$id")."'>".$data[$i]['judul']."</a>, <i class=\"fa fa-user\"></i> ".$data[$i]['owner'];
				$i++;
			}
		}else{
			$data  = false;
		}

		return $data;
	}


	function slide_show(){
		$sql   = "SELECT gambar FROM artikel WHERE (enabled=1 AND headline=3)
		UNION SELECT gambar1 FROM artikel WHERE (enabled=1 AND headline=3)
		UNION SELECT gambar2 FROM artikel WHERE (enabled=1 AND headline=3)
		UNION SELECT gambar3 FROM artikel WHERE (enabled=1 AND headline=3)
		ORDER BY RAND() LIMIT 10 ";
		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			$data  = $query->result_array();
		}else{
			$data  = false;
		}
		return $data;
	}

	function cos_widget(){
		$sql   = "SELECT a.*,u.nama AS owner,k.kategori AS kategori FROM artikel a LEFT JOIN user u ON a.id_user = u.id LEFT JOIN kategori k ON a.id_kategori = k.id WHERE id_kategori='1003' ORDER BY a.tgl_upload DESC";
		$sql   = "SELECT a.*,u.nama AS owner,k.kategori AS kategori
		FROM artikel a
		LEFT JOIN user u ON a.id_user = u.id
		LEFT JOIN kategori k ON a.id_kategori = k.id
		WHERE a.id_kategori='1003' AND a.enabled=1
		ORDER BY a.tgl_upload DESC";
		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			$data  = $query->result_array();
		}else{
			$data  = false;
		}
		return $data;
	}

	function agenda_show(){
		$sql   = "SELECT a.*,u.nama AS owner,k.kategori AS kategori FROM artikel a LEFT JOIN user u ON a.id_user = u.id LEFT JOIN kategori k ON a.id_kategori = k.id WHERE id_kategori='4' ORDER BY a.tgl_upload DESC";
		$query = $this->db->query($sql);
		$data = $query->result_array();
		return $data;
	}

	function komentar_show(){
		$sql   = "SELECT * FROM komentar WHERE enabled=? AND id_artikel <> 775 order by tgl_upload desc limit 10";
		$query = $this->db->query($sql,1);
		$data  = $query->result_array();

		$i=0;
		while($i<count($data)){

			//$judul=str_split($data[$i]['nama'],15);
			//$data[$i]['judul'] = "<h3>".$judul[6]."</h3>";
			$id = $data[$i]['id_artikel'];
			//$data['link'] = site_url("first/artikel/$id");

			$pendek=str_split($data[$i]['komentar'],25);
			$pendek2=str_split($pendek[0],90);
			$data[$i]['komentar_short'] = $pendek2[0]."...";
			$panjang=str_split($data[$i]['komentar'],50);
			$data[$i]['komentar'] = "".$panjang[0]."...<a href='".site_url("first/artikel/$id")."'>baca selengkapnya</a>";
			$i++;
		}
		return $data;
	}


	function get_kategori($id=0){
		$sql   = "SELECT a.kategori FROM kategori a WHERE a.id=?";
		$query = $this->db->query($sql,$id);
		if($query->num_rows()>0){
			$data  = $query->row_array();
		}else{
			$data  = false;
		}
		return $data;
	}



	function get_artikel($id=0){
		$sql   = "SELECT a.*,u.nama AS owner FROM artikel a LEFT JOIN user u ON a.id_user = u.id WHERE a.id=?";
		$query = $this->db->query($sql,$id);
		if($query->num_rows()>0){
			$data  = $query->row_array();
		}else{
			$data  = false;
		}
		return $data;
	}

	function list_artikel($offset=0,$limit=50,$id=0){
		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;
		$sql   = "SELECT a.*,u.nama AS owner,k.kategori AS kategori FROM artikel a LEFT JOIN user u ON a.id_user = u.id LEFT JOIN kategori k ON a.id_kategori = k.id WHERE a.enabled=1 ";
		if($id!=0)
			$sql .= "AND id_kategori = $id OR parrent = $id";
		$sql .= " ORDER BY a.tgl_upload DESC ";
		$sql .= $paging_sql;
		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			$data  = $query->result_array();
		}else{
			$data = false;
		}
		return $data;
	}


	/**
	 * Simpan komentar yang dikirim oleh pengunjung
	 */
	function insert_comment($id=0)
	{
		$data['komentar'] = strip_tags($_POST["komentar"]);
		$data['owner'] = strip_tags($_POST["owner"]);
		$data['email'] = strip_tags($_POST["email"]);

		$data['enabled'] = 2;
		$data['id_artikel'] = $id;
		$outp = $this->db->insert('komentar',$data);

		if($outp) {
			$_SESSION['success']=1;
			return true;
		}

		$_SESSION['success']=-1;
		return false;
	}


	function list_komentar($id=0){
		$sql   = "SELECT * FROM komentar WHERE id_artikel = ? ORDER BY tgl_upload DESC";
		$query = $this->db->query($sql,$id);
		if($query->num_rows()>0){
			$data  = $query->result_array();

			$i=0;
			while($i<count($data)){
				$i++;
			}
		}else{
			$data  = false;
		}
		return $data;
	}
	function list_sosmed(){

		$sql   = "SELECT * FROM media_sosial WHERE enabled=1";
		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			$data  = $query->result_array();
		}else{
			$data  = false;
		}
		return $data;
	}


}