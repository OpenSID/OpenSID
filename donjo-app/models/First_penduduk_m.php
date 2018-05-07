<?php

class First_penduduk_m extends CI_Model{

	function __construct(){
		parent::__construct();
		$this->load->model('laporan_penduduk_model');
	}

	function wilayah(){

		$sql   = "SELECT u.*,a.nama AS nama_kadus,a.nik AS nik_kadus,
		(SELECT COUNT(rw.id) FROM tweb_wil_clusterdesa rw WHERE dusun = u.dusun AND rw <> '-' AND rt = '-') AS jumlah_rw,
		(SELECT COUNT(v.id) FROM tweb_wil_clusterdesa v WHERE dusun = u.dusun AND v.rt <> '0' AND v.rt <> '-') AS jumlah_rt,
		(SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = u.dusun) and status_dasar=1) AS jumlah_warga,
		(SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = u.dusun) AND p.sex = 1 and status_dasar=1) AS jumlah_warga_l,
		(SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = u.dusun) AND p.sex = 2 and status_dasar=1) AS jumlah_warga_p,
		(SELECT COUNT(p.id) FROM tweb_keluarga k inner join tweb_penduduk p ON k.nik_kepala=p.id  WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = u.dusun) AND p.kk_level = 1 and status_dasar=1) AS jumlah_kk
		FROM tweb_wil_clusterdesa u LEFT JOIN tweb_penduduk a ON u.id_kepala = a.id WHERE u.rt = '0' AND u.rw = '0'  ";


		$query = $this->db->query($sql);
		$data=$query->result_array();

		//Formating Output
		$i=0;
		while($i<count($data)){
			$data[$i]['no']=$i+1;
			$i++;
		}
		return $data;
	}

	function list_indikator(){
		$sql = "SELECT u.id,u.pertanyaan AS indikator,s.subjek,p.nama AS periode,p.tahun_pelaksanaan AS tahun,m.nama AS master,m.subjek_tipe,p.id AS id_periode
			FROM analisis_indikator u
			LEFT JOIN analisis_master m ON u.id_master = m.id
			LEFT JOIN analisis_ref_subjek s ON m.subjek_tipe = s.id
			LEFT JOIN analisis_periode p ON p.id_master = m.id AND p.aktif = 1
			WHERE u.is_publik = 1 ORDER BY u.nomor ASC";
		$query = $this->db->query($sql);
		$data= $query->result_array();

		$i=0;
		while($i<count($data)){
			$data[$i]['no']=$i+1;
			$i++;
		}
		return $data;
	}

	function get_indikator($id=0){
		$sql = "SELECT pertanyaan FROM analisis_indikator WHERE id = ?";
		$query = $this->db->query($sql,$id);
		$data= $query->row_array();
		return $data['pertanyaan'];
	}
	function list_jawab($id=0,$sb=0,$per=0){
		switch($sb){
			case 1: $sbj = "LEFT JOIN tweb_penduduk p ON r.id_subjek = p.id LEFT JOIN tweb_wil_clusterdesa a ON p.id_cluster = a.id "; break;
			case 2: $sbj = "LEFT JOIN tweb_keluarga v ON r.id_subjek = v.id LEFT JOIN tweb_penduduk p ON v.nik_kepala = p.id LEFT JOIN tweb_wil_clusterdesa a ON p.id_cluster = a.id  " ; break;
			case 3: $sbj = "LEFT JOIN tweb_rtm v ON r.id_subjek = v.id LEFT JOIN tweb_penduduk p ON v.nik_kepala = p.id LEFT JOIN tweb_wil_clusterdesa a ON p.id_cluster = a.id "; break;
			case 4: $sbj = "LEFT JOIN kelompok v ON r.id_subjek = v.id LEFT JOIN tweb_penduduk p ON v.id_ketua = p.id LEFT JOIN tweb_wil_clusterdesa a ON p.id_cluster = a.id  "; break;
		}

		$sql = "SELECT * FROM analisis_parameter WHERE id_indikator = ? ORDER BY kode_jawaban ASC ";
		$query = $this->db->query($sql,$id);
		$data= $query->result_array();

		$i=0;
		while($i<count($data)){
			$data[$i]['no']=$i+1;

			$sql = "SELECT COUNT(r.id_subjek) AS jml FROM analisis_respon r $sbj WHERE r.id_parameter = ? AND r.id_periode = $per";
			$query = $this->db->query($sql,$data[$i]['id']);
			$respon= $query->row_array();

			$data[$i]['nilai']=$respon['jml'];

			$i++;
		}
		return $data;
	}

}
