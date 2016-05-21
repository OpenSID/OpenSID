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
			
			//$panjang=str_split($data['isi'],300);
			//$data['isi'] = "<label>".$panjang[0]."...</label><a href='".site_url("first/artikel/$id")."'>Baca Selengkapnya</a>";
		}
		
		return $data;
	}
	
	function get_widget(){
		$sql   = "SELECT * FROM widget LIMIT 1 ";
		$query = $this->db->query($sql);
		$data  = $query->row_array();
		
		return $data;
	}
	
		
	function paging($p=1){
	
		$sql      = "SELECT COUNT(a.id) AS id FROM artikel a LEFT JOIN user u ON a.id_user = u.id LEFT JOIN kategori k ON a.id_kategori = k.id WHERE a.enabled=1 AND headline <> 1 AND k.tipe = 1 ORDER BY a.tgl_upload DESC";
		$query    = $this->db->query($sql);
		$row      = $query->row_array();
		$jml_data = $row['id'];
		
		$this->load->library('paging');
		$cfg['page']     = $p;
		$cfg['per_page'] = 4;
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);
		
		return $this->paging;
	}
	
	function artikel_show($offset=0,$limit=50){
	
		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;
		$sql   = "SELECT a.*,u.nama AS owner,k.kategori AS kategori FROM artikel a LEFT JOIN user u ON a.id_user = u.id LEFT JOIN kategori k ON a.id_kategori = k.id WHERE a.enabled=1 AND headline <> 1 AND k.tipe = 1 ORDER BY a.tgl_upload DESC";
		
		$sql .= $paging_sql;
		
		$query = $this->db->query($sql);
		$data  = $query->result_array();
		
		$i=0;
		while($i<count($data)){
		
			//$judul=str_split($data[$i]['nama'],15);
			//$data[$i]['judul'] = "<h3>".$judul[6]."</h3>";
			$id = $data[$i]['id'];
			//$data['link'] = site_url("first/artikel/$id");
			
			$pendek=str_split($data[$i]['isi'],120);
			$data[$i]['isi_short'] = $pendek[0];
			
			//$panjang=Parse_Data($data[$i]['isi'],">","<");
			$panjang=strip_tags($data[$i]['isi']);
			$panjang=str_split($panjang,350);
			$panjang = ''.$panjang[0].'';
			
			
			$data[$i]['isi'] = "<label>".$panjang."...</label><a href='".site_url("first/artikel/$id")."'>Baca Selengkapnya</a>";
			$i++;
		}
		return $data;
	}
	
	function produk_show(){
		$sql   = "SELECT a.*,u.nama AS owner,k.kategori AS kategori FROM artikel a LEFT JOIN user u ON a.id_user = u.id LEFT JOIN kategori k ON a.id_kategori = k.id WHERE a.enabled=? AND k.tipe = 1 AND id_kategori = 7 ORDER BY a.tgl_upload DESC LIMIT 4";
		$query = $this->db->query($sql,1);
		$data  = $query->result_array();
		
		$i=0;
		while($i<count($data)){
		
			//$judul=str_split($data[$i]['nama'],15);
			//$data[$i]['judul'] = "<h3>".$judul[6]."</h3>";
			$id = $data[$i]['id'];
			//$data['link'] = site_url("first/artikel/$id");
			
			$pendek=str_split($data[$i]['isi'],100);
			$data[$i]['isi_short'] = $pendek[0];
			$panjang=strip_tags($data[$i]['isi']);
			$panjang=str_split($panjang,350);
			$panjang = ''.$panjang[0].'';
			$data[$i]['isi'] = "<label>".$panjang."...</label><a href='".site_url("first/artikel/$id")."'>Baca Selengkapnya</a>";
			$i++;
		}
		return $data;
	}
	
	function potensi_show(){
		$sql   = "SELECT a.*,u.nama AS owner,k.kategori AS kategori FROM artikel a LEFT JOIN user u ON a.id_user = u.id LEFT JOIN kategori k ON a.id_kategori = k.id WHERE a.enabled=? AND k.tipe = 1 AND id_kategori = 3 ORDER BY a.tgl_upload DESC LIMIT 4";
		$query = $this->db->query($sql,1);
		$data  = $query->result_array();
		
		$i=0;
		while($i<count($data)){
		
			//$judul=str_split($data[$i]['nama'],15);
			//$data[$i]['judul'] = "<h3>".$judul[6]."</h3>";
			$id = $data[$i]['id'];
			//$data['link'] = site_url("first/artikel/$id");
			
			$pendek=str_split($data[$i]['isi'],100);
			$data[$i]['isi_short'] = $pendek[0];
			$panjang=strip_tags($data[$i]['isi']);
			$panjang=str_split($panjang,350);
			$panjang = ''.$panjang[0].'';
			$data[$i]['isi'] = "<label>".$panjang."...</label><a href='".site_url("first/artikel/$id")."'>Baca Selengkapnya</a>";
			$i++;
		}
		return $data;
	}
	function peraturan_show(){
		$sql   = "SELECT a.*,u.nama AS owner,k.kategori AS kategori FROM artikel a LEFT JOIN user u ON a.id_user = u.id LEFT JOIN kategori k ON a.id_kategori = k.id WHERE a.enabled=? AND k.tipe = 2 AND id_kategori=1001 ORDER BY a.tgl_upload DESC LIMIT 4";
		$query = $this->db->query($sql,1);
		$data  = $query->result_array();
		
		$i=0;
		while($i<count($data)){
		
			//$judul=str_split($data[$i]['nama'],15);
			//$data[$i]['judul'] = "<h3>".$judul[6]."</h3>";
			$id = $data[$i]['id'];
			//$data['link'] = site_url("first/artikel/$id");
			
			//$pendek=str_split($data[$i]['isi'],100);
			//$data[$i]['isi_short'] = $pendek[0];
			//$panjang=str_split($data[$i]['isi'],150);
			//$data[$i]['isi'] = "<label>".$panjang[0]."...</label><a href='".site_url("first/artikel/$id")."'>Baca Selengkapnya</a>";
			$i++;
		}
		return $data;
	}
	function laporan_show(){
		$sql   = "SELECT a.*,u.nama AS owner,k.kategori AS kategori FROM artikel a LEFT JOIN user u ON a.id_user = u.id LEFT JOIN kategori k ON a.id_kategori = k.id WHERE a.enabled=? AND k.tipe = 2 AND id_kategori=1002 ORDER BY a.tgl_upload DESC LIMIT 4";
		$query = $this->db->query($sql,1);
		$data  = $query->result_array();
		
		$i=0;
		while($i<count($data)){
		
			//$judul=str_split($data[$i]['nama'],15);
			//$data[$i]['judul'] = "<h3>".$judul[6]."</h3>";
			$id = $data[$i]['id'];
			//$data['link'] = site_url("first/artikel/$id");
			
			//$pendek=str_split($data[$i]['isi'],100);
			//$data[$i]['isi_short'] = $pendek[0];
			//$panjang=str_split($data[$i]['isi'],150);
			//$data[$i]['isi'] = "<label>".$panjang[0]."...</label><a href='".site_url("first/artikel/$id")."'>Baca Selengkapnya</a>";
			$i++;
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
			$data[$i]['isi'] = "<label>".$panjang[0]."...</label><a href='".site_url("first/artikel/$id")."'>Baca Selengkapnya</a>";
			$i++;
		}
		return $data;
	}
	
	function full_arsip(){
		$sql   = "SELECT a.*,u.nama AS owner,k.kategori AS kategori FROM artikel a LEFT JOIN user u ON a.id_user = u.id LEFT JOIN kategori k ON a.id_kategori = k.id WHERE a.enabled=? ORDER BY a.tgl_upload DESC";
		$query = $this->db->query($sql,1);
		$data  = $query->result_array();
		
		$i=0;
		while($i<count($data)){
		
			$id = $data[$i]['id'];
			$tgl = tgl_indo($data[$i]['tgl_upload']);
			//$data[$i]['isi_short'] = $pendek2[0]."...";
			//$panjang=str_split($data[$i]['isi'],150);
			$data[$i]['isi'] = "<label><a href='".site_url("first/artikel/$id")."'>".$data[$i]['judul']."</a>, ditulis oleh: ".$data[$i]['owner']."</label>- ".$tgl."";
			$i++;
		}
		return $data;
	}

	function slide_show(){
		$sql   = "SELECT gambar FROM artikel WHERE enabled=1 
		UNION SELECT gambar1 FROM artikel WHERE enabled=1 
		UNION SELECT gambar2 FROM artikel WHERE enabled=1 
		UNION SELECT gambar3 FROM artikel WHERE enabled=1 
		ORDER BY RAND() ";
		$query = $this->db->query($sql);
		$data  = $query->result_array();
		
		return $data;
	}
	
	function cos_widget(){
		$sql   = "SELECT a.*,u.nama AS owner,k.kategori AS kategori FROM artikel a LEFT JOIN user u ON a.id_user = u.id LEFT JOIN kategori k ON a.id_kategori = k.id WHERE id_kategori='1003' ORDER BY a.tgl_upload DESC";
		$query = $this->db->query($sql);
		$data  = $query->result_array();
		
		return $data;
	}
	
	function agenda_show(){
		$sql   = "SELECT a.*,u.nama AS owner,k.kategori AS kategori FROM artikel a LEFT JOIN user u ON a.id_user = u.id LEFT JOIN kategori k ON a.id_kategori = k.id WHERE id_kategori='1000' ORDER BY a.tgl_upload DESC";
		$query = $this->db->query($sql);
		$data  = $query->result_array();
		
		return $data;
	}
	
	function komentar_show(){
		$sql   = "SELECT * FROM komentar WHERE enabled=? order by tgl_upload desc limit 10";
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
			$data[$i]['komentar'] = "<label>".$panjang[0]."...</label><a href='".site_url("first/artikel/$id")."'><br />Baca Selengkapnya</a>";
			$i++;
		}
		return $data;
	}
	
	function get_artikel($id=0){
		$sql   = "SELECT a.*,u.nama AS owner FROM artikel a LEFT JOIN user u ON a.id_user = u.id WHERE a.id=?";
		$query = $this->db->query($sql,$id);
		$data  = $query->row_array();
		
			//$judul=str_split($data['nama'],15);
			
		return $data;
	}
	
	function list_artikel($id=0){
		$sql   = "SELECT a.*,u.nama AS owner,k.kategori AS kategori FROM artikel a LEFT JOIN user u ON a.id_user = u.id LEFT JOIN kategori k ON a.id_kategori = k.id WHERE a.enabled=? AND k.tipe = 1 ORDER BY a.tgl_upload DESC LIMIT 4";
		$query = $this->db->query($sql,1);
		$data  = $query->result_array();
		
			//$judul=str_split($data['nama'],15);
			//$data['judul'] = "<h3>".$judul[6]."</h3>";
			
		return $data;
	}
	
	function insert_comment($id=0){
		$data = $_POST;
		$data['enabled'] = 2;
		$data['id_artikel'] = $id;
		$outp = $this->db->insert('komentar',$data);
		
		if($outp) $_SESSION['success']=1;
		else $_SESSION['success']=-1;
	}

	function list_komentar($id=0){
		$sql   = "SELECT * FROM komentar WHERE id_artikel = ? ORDER BY tgl_upload DESC";
		$query = $this->db->query($sql,$id);
		$data  = $query->result_array();
		
		$i=0;
		while($i<count($data)){
			$i++;
		}
		
		return $data;
	}
	function list_sosmed(){
	
		$sql   = "SELECT * FROM media_sosial WHERE enabled=1";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		
		return $data;
	}
	
}

