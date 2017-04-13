<?php

class First_Gallery_M extends CI_Model{

	function __construct(){
		parent::__construct();
	}

	function paging($p=1){

		$sql      = "SELECT COUNT(id) AS id FROM gambar_gallery WHERE enabled=1 AND tipe='0'";
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

	function gallery_show($offset=0,$limit=50){

		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;

		$sql   = "SELECT * FROM gambar_gallery WHERE enabled=1 AND tipe='0' ";
		$sql .= $paging_sql;

		$query = $this->db->query($sql);
		$data  = $query->result_array();
		// Untuk album yang tidak ada gambar cover, cari gambar di sub-gallery
		for ($i=0; $i<count($data); $i++) {
			if ($data[$i]['gambar'] == '') {
				$galeri = $data[$i]['id'];
				$sql   = "SELECT gambar FROM gambar_gallery WHERE ((enabled='1') AND ((parrent='".$galeri."') OR (id='".$galeri."')) AND (gambar<>'')) LIMIT 1";
				$query = $this->db->query($sql);
				$row  = $query->row_array();
				$data[$i]['gambar'] = $row['gambar'];
			}
		}
		return $data;
	}

	function paging2($gal=0,$p=1){

		$sql      = "SELECT COUNT(id) AS id FROM gambar_gallery WHERE enabled=1 AND parrent=?";
		$query    = $this->db->query($sql,$gal);
		$row      = $query->row_array();
		$jml_data = $row['id'];

		$this->load->library('paging');
		$cfg['page']     = $p;
		$cfg['per_page'] = 8;
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);

		return $this->paging;
	}

	function sub_gallery_show($gal=0,$offset=0,$limit=50){
		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;

		$sql   = "SELECT * FROM gambar_gallery WHERE ((enabled='1') AND ((parrent='".$gal."') OR (id='".$gal."'))) ";
		$sql .= $paging_sql;

		$query = $this->db->query($sql);
		$data  = $query->result_array();
		return $data;
	}

	function get_parrent($parrent){
		$sql   = "SELECT * FROM gambar_gallery WHERE id='$parrent'";
		$query = $this->db->query($sql);
		$data  = $query->row_array();
		return $data;
	}


	// daftar album
	function gallery_widget(){
		$sql   = "SELECT * FROM gambar_gallery WHERE enabled='1' and parrent=0 order by id desc";
		$query = $this->db->query($sql);
		$data  = $query->result_array();
		return $data;
	}

}

