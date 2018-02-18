<?php class Inventaris_Model extends CI_Model{

	function __construct(){
		parent::__construct();
	}

	function autocomplete(){
		$data = $this->db->select('nama')->order_by('nama')->get('jenis_barang')->result_array();
		$outp='';
		for($i=0; $i<count($data); $i++){
			$outp .= ',"'.$data[$i]['nama'].'"';
		}
		$outp = substr($outp, 1);
		$outp = '[' .$outp. ']';
		return $outp;
	}

	function get_jenis($id=0){
		$hasil = $this->db->where('id',$id)->get('jenis_barang')->row_array();
		$status = $this->status_inventaris($id);
		foreach($status as $key => $value){
			$hasil[$key] = $value;
		}
		return $hasil;
	}

	function search_jenis_sql(){
		if(isset($_SESSION['cari'])){
			$cari = $_SESSION['cari'];
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' .$kw. '%';
			$search_sql= " AND (j.keterangan LIKE '$kw')";
			return $search_sql;
		}
	}

	function paging_jenis($p=1,$o=0){
		$list_data_sql = $this->list_jenis_sql();
		$sql = "SELECT COUNT(*) AS jml ".$list_data_sql;
		$query    = $this->db->query($sql);
		$row      = $query->row_array();
		$jml_data = $row['jml'];

		$this->load->library('paging');
		$cfg['page']     = $p;
		$cfg['per_page'] = $_SESSION['per_page'];
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);
		return $this->paging;
	}

	// Digunakan untuk paging dan query utama supaya jumlah data selalu sama
	private function list_jenis_sql() {
		$sql = "
			FROM jenis_barang j
			WHERE 1 ";
		$sql .= $this->search_jenis_sql();
		return $sql;
	}

	function list_jenis($o=0,$offset=0,$limit=500){
		$select_sql = "SELECT *
			";
		//Main Query
		$list_data_sql = $this->list_jenis_sql();
		$sql = $select_sql." ".$list_data_sql;

		//Ordering SQL
		switch($o){
			case 1: $order_sql = ' ORDER BY j.nama'; break;
			case 2: $order_sql = ' ORDER BY j.nama DESC'; break;
			default:$order_sql = '';
		}

		//Paging SQL
		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;

		$sql .= $order_sql;
		$sql .= $paging_sql;

		$query = $this->db->query($sql);
		$data = $query->result_array();
		// Isi status setiap jenis barang yang ditampilkan
		for($i=0; $i<count($data); $i++){
			$status = $this->status_inventaris($data[$i]['id']);
			foreach($status as $key => $value){
				$data[$i][$key] = $value;
			}
			$status_awal_tahun = $this->status_awal_tahun($data[$i]['id']);
			foreach($status_awal_tahun as $key => $value){
				$data[$i][$key] = $value;
			}
			$status_akhir_tahun = $this->status_akhir_tahun($data[$i]['id']);
			foreach($status_akhir_tahun as $key => $value){
				$data[$i][$key] = $value;
			}
			$status_penghapusan = $this->status_penghapusan($data[$i]['id']);
			foreach($status_penghapusan as $key => $value){
				$data[$i][$key] = $value;
			}
		}
		return $data;
	}

	function tahun_condition(){
		if(isset($_SESSION['tahun'])){
			$tahun = $_SESSION['tahun'];
			$this->db->where("DATE(tanggal_mutasi) <= '".$tahun."-12-31'");
		}
	}

	function status_sebelum_tgl($id_jenis_barang,$tgl_str){
		if(!isset($_SESSION['tahun'])) return;
		$kriteria_tanggal = "DATE(tanggal_mutasi) < '".$tgl_str."'";
		$this->db->where($kriteria_tanggal);
		$pengadaan = $this->get_pengadaan($id_jenis_barang);
		$this->db->where($kriteria_tanggal);
		$penghapusan = $this->get_penghapusan($id_jenis_barang);
		$total = 0;
		foreach($pengadaan as $key => $value){
			$total += ($value - $penghapusan[$key]);
		}
		$this->db->where($kriteria_tanggal);
		$jml_rusak = $this->get_jml_rusak($id_jenis_barang);
		$this->db->where($kriteria_tanggal);
		$jml_diperbaiki = $this->get_jml_diperbaiki($id_jenis_barang);
		$status = array();
		$status['status_rusak'] = $jml_rusak - $jml_diperbaiki;
		$status['status_baik'] = $total - $status['status_rusak'];
		return $status;
	}

	function status_awal_tahun($id_jenis_barang){
		if(!isset($_SESSION['tahun'])) return;
		$tgl_str = $_SESSION['tahun']."-01-01";
		$status = $this->status_sebelum_tgl($id_jenis_barang,$tgl_str);
		$status_awal_tahun['status_rusak_awal'] = $status['status_rusak'];
		$status_awal_tahun['status_baik_awal'] = $status['status_baik'];
		return $status_awal_tahun;
	}

	function status_akhir_tahun($id_jenis_barang){
		if(!isset($_SESSION['tahun'])) return;
		$tgl_str = ($_SESSION['tahun']+1)."-01-01";
		$status = $this->status_sebelum_tgl($id_jenis_barang,$tgl_str);
		$status_awal_tahun['status_rusak_akhir'] = $status['status_rusak'];
		$status_awal_tahun['status_baik_akhir'] = $status['status_baik'];
		return $status_awal_tahun;
	}

	function jml_asal_sql(){
		$sql = 'SUM(asal_sendiri) as asal_sendiri, SUM(asal_pemerintah) as asal_pemerintah, SUM(asal_provinsi) as asal_provinsi, SUM(asal_kab) as asal_kab, SUM(asal_sumbangan) as asal_sumbangan';
		return $sql;
	}

	function get_pengadaan($id_jenis_barang){
		$pengadaan = $this->db->select($this->jml_asal_sql())->where('id_jenis_barang',$id_jenis_barang)->where('jenis_mutasi',1)->get('inventaris')->result_array();
		return $pengadaan[0];
	}

	function get_penghapusan($id_jenis_barang){
		$penghapusan = $this->db->select($this->jml_asal_sql())->where('id_jenis_barang',$id_jenis_barang)->where('jenis_mutasi',2)->get('inventaris')->result_array();
		return $penghapusan[0];
	}

	function get_jml_rusak($id_jenis_barang){
		$rusak = $this->db->select($this->jml_asal_sql())->where('id_jenis_barang',$id_jenis_barang)->where('jenis_mutasi',3)->get('inventaris')->result_array();
		$jml_rusak = 0;
		foreach($rusak[0] as $key => $value){
			$jml_rusak += $value;
		}
		return $jml_rusak;
	}

	function get_jml_diperbaiki($id_jenis_barang){
		$diperbaiki = $this->db->select($this->jml_asal_sql())->where('id_jenis_barang',$id_jenis_barang)->where('jenis_mutasi',4)->get('inventaris')->result_array();
		$jml_diperbaiki = 0;
		foreach($diperbaiki[0] as $key => $value){
			$jml_diperbaiki += $value;
		}
		return $jml_diperbaiki;
	}

	function get_jenis_penghapusan($id_jenis_barang){
		$jml_hapus = 'SUM(hapus_rusak) as hapus_rusak, SUM(hapus_dijual) as hapus_dijual, SUM(hapus_sumbangkan) as hapus_sumbangkan, MAX(tanggal_mutasi) as tgl_penghapusan';
		$jenis_penghapusan = $this->db->select($jml_hapus)->where('id_jenis_barang',$id_jenis_barang)->where('jenis_mutasi',2)->get('inventaris')->result_array();
		return $jenis_penghapusan[0];
	}

	function status_penghapusan($id_jenis_barang){
		if(isset($_SESSION['tahun'])){
			$tahun = $_SESSION['tahun'];
			$this->db->where("DATE(tanggal_mutasi) >= '".$tahun."-01-01' AND DATE(tanggal_mutasi) <= '".$tahun."-12-31'");
		}
		$jenis_penghapusan = $this->get_jenis_penghapusan($id_jenis_barang);
		return $jenis_penghapusan;
	}

	function status_inventaris($id_jenis_barang){
		$this->tahun_condition();
		$pengadaan = $this->get_pengadaan($id_jenis_barang);
		$this->tahun_condition();
		$penghapusan = $this->get_penghapusan($id_jenis_barang);
		$status = array();
		$total = 0;
		foreach($pengadaan as $key => $value){
			$status[$key] = $value - $penghapusan[$key];
			$total += $status[$key];
		}
		$this->tahun_condition();
		$jml_rusak = $this->get_jml_rusak($id_jenis_barang);
		$this->tahun_condition();
		$jml_diperbaiki = $this->get_jml_diperbaiki($id_jenis_barang);
		$status['status_rusak'] = $jml_rusak - $jml_diperbaiki;
		$status['status_baik'] = $total - $status['status_rusak'];
		return $status;
	}

	function insert_jenis(){
		$data = $_POST;
		$outp = $this->db->insert('jenis_barang',$data);
		if(!$outp) session_error(); else session_success();
	}

	function update_jenis($id=''){
	  $data = $_POST;
		$outp = $this->db->where('id',$id)->update('jenis_barang',$data);
		if(!$outp) session_error(); else session_success();
	}

	function delete_jenis($id=''){
		$outp = $this->db->where('id',$id)->delete('jenis_barang');
		if(!$outp) session_error(); else session_success();
	}

// ===============

	function get_inventaris($id=0){
		$hasil = $this->db->where('id',$id)->get('inventaris')->row_array();
		$hasil['tanggal_mutasi'] = tgl_indo_out($hasil['tanggal_mutasi']);
		return $hasil;
	}

	function search_sql(){
		if(isset($_SESSION['cari'])){
			$cari = $_SESSION['cari'];
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' .$kw. '%';
			$search_sql= " AND (i.keterangan LIKE '$kw')";
			return $search_sql;
		}
	}

	function paging($id_jenis,$p=1,$o=0){
		$list_data_sql = $this->list_data_sql($id_jenis);
		$sql = "SELECT COUNT(*) AS jml ".$list_data_sql;
		$query    = $this->db->query($sql);
		$row      = $query->row_array();
		$jml_data = $row['jml'];

		$this->load->library('paging');
		$cfg['page']     = $p;
		$cfg['per_page'] = $_SESSION['per_page'];
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);
		return $this->paging;
	}

	// Digunakan untuk paging dan query utama supaya jumlah data selalu sama
	private function list_data_sql($id_jenis) {
		$sql = "
			FROM inventaris i
			WHERE i.id_jenis_barang = $id_jenis ";
		$sql .= $this->search_sql();
		return $sql;
	}

	function list_data($id_jenis=0,$o=0,$offset=0,$limit=500){
		$select_sql = "SELECT *
			";
		//Main Query
		$list_data_sql = $this->list_data_sql($id_jenis);
		$sql = $select_sql." ".$list_data_sql;

		//Ordering SQL
		switch($o){
			case 1: $order_sql = ' ORDER BY i.tanggal_mutasi'; break;
			case 2: $order_sql = ' ORDER BY i.tanggal_mutasi DESC'; break;
			case 3: $order_sql = ' ORDER BY i.jenis_mutasi'; break;
			case 4: $order_sql = ' ORDER BY i.jenis_mutasi DESC'; break;
			default:$order_sql = ' ORDER BY i.tanggal_mutasi DESC';
		}

		//Paging SQL
		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;

		$sql .= $order_sql;
		$sql .= $paging_sql;

		$query = $this->db->query($sql);
		$data=$query->result_array();

		return $data;
	}

	function insert($id_jenis_barang){
		$data = $_POST;
		$data['id_jenis_barang'] = $id_jenis_barang;
		$data['tanggal_mutasi'] = tgl_indo_in($data['tanggal_mutasi']);
		$outp = $this->db->insert('inventaris',$data);
		if(!$outp) session_error(); else session_success();
	}

	function update($id=''){
	  $data = $_POST;
		$data['tanggal_mutasi'] = tgl_indo_in($data['tanggal_mutasi']);
		$outp = $this->db->where('id',$id)->update('inventaris',$data);
		if(!$outp) session_error(); else session_success();
	}

	function delete($id=''){
		$outp = $this->db->where('id',$id)->delete('inventaris');
		if(!$outp) session_error(); else session_success();
	}

	function delete_all(){
		session_success();
		$id_cb = $_POST['id_cb'];
		if(count($id_cb)){
			foreach($id_cb as $id){
				$outp = $this->delete($id);
				if (!$outp) session_error();
			}
		}
	}

	function list_tahun(){
		$tahun = $this->db->distinct()->select('YEAR(tanggal_mutasi) AS tahun')->order_by('tanggal_mutasi DESC')->get('inventaris')->result_array();
		return $tahun;
	}

}