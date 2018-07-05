<?php

class First_artikel_m extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	function get_headline(){
		$sql   = "SELECT a.*,u.nama AS owner FROM artikel a LEFT JOIN user u ON a.id_user = u.id WHERE headline = 1 AND a.tgl_upload < NOW() ORDER BY tgl_upload DESC LIMIT 1 ";
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
		$sql   = "SELECT a.isi FROM artikel a LEFT JOIN kategori k ON a.id_kategori = k.id WHERE k.kategori = 'teks_berjalan' AND k.enabled = 1 AND a.tgl_upload < NOW()";
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

		$sql = "SELECT COUNT(a.id) AS id FROM artikel a
			LEFT JOIN kategori k ON a.id_kategori = k.id
			WHERE ((a.enabled=1) AND (headline <> 1)) AND a.tgl_upload < NOW() ";
		$cari = trim($this->input->get('cari'));
		if ( ! empty($cari)) {
			$cari = $this->db->escape_like_str($cari);
			$sql .= "AND (a.judul like '%$cari%' or a.isi like '%$cari%') ";
			$cfg['suffix'] = "?cari=$cari";
		}
		$sql .= "ORDER BY a.tgl_upload DESC";
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
				LEFT JOIN kategori k ON a.id_kategori = k.id WHERE a.enabled=1 AND headline <> 1 AND a.id=".$id;
		}else{
			// Penampilan daftar artikel di halaman depan tidak terbatas pada artikel dinamis saja
			$sql = "SELECT a.*,u.nama AS owner,k.kategori AS kategori FROM artikel a
				LEFT JOIN user u ON a.id_user = u.id
				LEFT JOIN kategori k ON a.id_kategori = k.id WHERE a.enabled=1 AND headline <> 1";
			$cari = trim($this->input->get('cari'));
			if ( ! empty($cari)) {
				$cari = $this->db->escape_like_str($cari);
				$sql .= " AND (a.judul like '%$cari%' or a.isi like '%$cari%') ";
			}
			$sql .= " AND a.tgl_upload < NOW()";
			$sql .= " ORDER BY a.tgl_upload DESC LIMIT ".$offset.", ".$limit;
		}
		$query = $this->db->query($sql);
		$data  = $query->result_array();
		for ($i=0; $i < count($data); $i++)
		{
			$data[$i]['judul'] = $this->security->xss_clean($data[$i]['judul']);
			if (empty($this->setting->user_admin) or $data[$i]['id_user'] != $this->setting->user_admin)
				$data[$i]['isi'] = $this->security->xss_clean($data[$i]['isi']);
		}
		return $data;
	}

	function arsip_show(){
		$sql   = "SELECT a.*,u.nama AS owner,k.kategori AS kategori FROM artikel a LEFT JOIN user u ON a.id_user = u.id LEFT JOIN kategori k ON a.id_kategori = k.id WHERE a.enabled=?
			AND a.tgl_upload < NOW()
		 ORDER BY a.tgl_upload DESC LIMIT 7 ";
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
		$sql      = "SELECT COUNT(a.id) AS id FROM artikel a LEFT JOIN user u ON a.id_user = u.id LEFT JOIN kategori k ON a.id_kategori = k.id WHERE a.enabled=1 AND a.tgl_upload < NOW()";
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
		$sql   = "SELECT a.*,u.nama AS owner,k.kategori AS kategori FROM artikel a LEFT JOIN user u ON a.id_user = u.id LEFT JOIN kategori k ON a.id_kategori = k.id WHERE a.enabled=?
			AND a.tgl_upload < NOW()
		ORDER BY a.tgl_upload DESC";

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

	// Jika $gambar_utama, hanya tampilkan gambar utama masing2 artikel terbaru
	function slide_show($gambar_utama=FALSE){
		$sql   = "SELECT id,judul,gambar FROM artikel WHERE (enabled=1 AND headline=3 AND tgl_upload < NOW())";
		if (!$gambar_utama) $sql .= "
			UNION SELECT id,judul,gambar1 FROM artikel WHERE (enabled=1 AND headline=3 AND tgl_upload < NOW())
			UNION SELECT id,judul,gambar2 FROM artikel WHERE (enabled=1 AND headline=3 AND tgl_upload < NOW())
			UNION SELECT id,judul,gambar3 FROM artikel WHERE (enabled=1 AND headline=3 AND tgl_upload < NOW())
		";
		$sql .= ($gambar_utama) ? "ORDER BY tgl_upload DESC LIMIT 10" : "ORDER BY RAND() LIMIT 10";
		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			$data  = $query->result_array();
		}else{
			$data  = false;
		}
		return $data;
	}

	// Ambil gambar slider besar tergantung dari settingnya.
	function slider_gambar(){
		$slider_gambar = array();
		switch ($this->setting->sumber_gambar_slider) {
			case '1':
				# 10 gambar utama semua artikel terbaru
				$slider_gambar['gambar'] = $this->db->select('id,judul,gambar')->where('enabled',1)->where('gambar !=','')->where('tgl_upload < NOW()')->order_by('tgl_upload DESC')->limit(10)->get('artikel')->result_array();
				$slider_gambar['lokasi'] = LOKASI_FOTO_ARTIKEL;
				break;
			case '2':
				# 10 gambar utama artikel terbaru yang masuk ke slider atas
				$slider_gambar['gambar'] = $this->slide_show(true);
				$slider_gambar['lokasi'] = LOKASI_FOTO_ARTIKEL;
				break;
			case '3':
				# 10 gambar dari galeri yang masuk ke slider besar
				$this->load->model('web_gallery_model');
				$slider_gambar['gambar'] = $this->web_gallery_model->list_slide_galeri();
				$slider_gambar['lokasi'] = LOKASI_GALERI;
				break;
			default:
				# code...
				break;
		}
		return $slider_gambar;
	}

	function agenda_show(){
		$sql   = "SELECT a.*,u.nama AS owner,k.kategori AS kategori FROM artikel a LEFT JOIN user u ON a.id_user = u.id LEFT JOIN kategori k ON a.id_kategori = k.id WHERE id_kategori='4'
		AND a.tgl_upload < NOW() ORDER BY a.tgl_upload DESC";
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
		$sql   = "SELECT a.*,u.nama AS owner,k.kategori FROM artikel a LEFT JOIN user u ON a.id_user = u.id LEFT JOIN kategori k ON a.id_kategori = k.id WHERE a.id=? AND a.tgl_upload < NOW()";
		$query = $this->db->query($sql,$id);
		if ($query->num_rows()>0)
		{
			$data  = $query->row_array();
			$data['judul'] = $this->security->xss_clean($data['judul']);
			if (empty($this->setting->user_admin) or $data['id_user'] != $this->setting->user_admin)
				$data['isi'] = $this->security->xss_clean($data['isi']);
		}
		else
		{
			$data  = false;
		}
		return $data;
	}

	function list_artikel($offset=0,$limit=50,$id=0)
	{
		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;
		$sql   = "SELECT a.*,u.nama AS owner,k.kategori AS kategori FROM artikel a LEFT JOIN user u ON a.id_user = u.id LEFT JOIN kategori k ON a.id_kategori = k.id WHERE a.enabled=1 AND a.tgl_upload < NOW() ";
		if($id!=0)
			$sql .= "AND id_kategori = $id OR parrent = $id";
		$sql .= " ORDER BY a.tgl_upload DESC ";
		$sql .= $paging_sql;
		$query = $this->db->query($sql);
		if ($query->num_rows()>0)
		{
			$data  = $query->result_array();
			for ($i=0; $i < count($data); $i++)
			{
				$data[$i]['judul'] = $this->security->xss_clean($data[$i]['judul']);
				if (empty($this->setting->user_admin) or $data[$i]['id_user'] != $this->setting->user_admin)
					$data[$i]['isi'] = $this->security->xss_clean($data[$i]['isi']);
			}
		}
		else
		{
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
