<?php

class Suplemen_model extends CI_Model{

	function __construct(){
		parent::__construct();
	}

	public function list_suplemen($sasaran=0){
		if ($sasaran > 0){
			$strSQL = "SELECT *
				FROM suplemen s
				WHERE s.sasaran=".$sasaran;
		}else{
			$strSQL = "SELECT *
				FROM suplemen s WHERE 1";
		}
		$query = $this->db->query($strSQL);
		$data = $query->result_array();
		return $data;
	}

	public function create(){

		$data = array(
			'sasaran' => $this->input->post('cid'),
			'nama' => $this->input->post('nama'),
			'keterangan' => $this->input->post('keterangan')
		);
		$hasil = $this->db->insert('suplemen', $data);
		if($hasil){
			$_SESSION["success"] = 1;
		}else{
			$_SESSION["success"] = -1;
		}
	}

	public function list_data($sasaran=0){
		if ($sasaran > 0){
			$data = $this->db->select('*')->where('sasaran',$sasaran)->order_by('nama')->get('suplemen')->result_array();
		}else{
			$data = $this->db->select('*')->order_by('nama')->get('suplemen')->result_array();
		}
		return $data;
	}

	public function list_sasaran($id, $sasaran){
		$data = array();
		switch ($sasaran) {
			case '1':
				$data = $this->_list_penduduk($id);
				break;

			case '2': # sasaran KK
				$data = $this->_list_kk($id);
			default:
				# code...
				break;
		}
		return $data;
	}

	private function _get_id_terdata_penduduk($id_suplemen){
		$hasil = array();
		$sql = "SELECT p.nik
			FROM tweb_penduduk p
			LEFT JOIN suplemen_terdata t ON p.nik = t.id_terdata
			WHERE t.id_suplemen = ?";
		$data = $this->db->query($sql, $id_suplemen)->result_array();
		foreach ($data as $item){
			$hasil[] = $item['nik'];
		}
		return $hasil;
	}

	private function _list_penduduk($id){
		// Penduduk yang sudah terdata untuk suplemen ini
		$terdata = "";
		$list_terdata = $this->_get_id_terdata_penduduk($id);
		foreach($list_terdata as $key => $value){
			$terdata .= ",".$value;
		}
		$terdata = ltrim($terdata,",");

		// Daftar penduduk, tidak termasuk penduduk yang sudah terdata
		$strSQL = "SELECT p.nik as id,p.nama as nama,w.rt,w.rw,w.dusun
			FROM tweb_penduduk p
			LEFT JOIN tweb_wil_clusterdesa w ON w.id=p.id_cluster
		WHERE p.nik NOT IN (?)";
		$hasil = array();
		$data = $this->db->query($strSQL,$terdata)->result_array();
		foreach($data as $item){
			$penduduk = array(
				'id' => $item['id'],
				'nik' => $item['id'],
				'nama' => strtoupper($item['nama']) ." [".$item['id']."]",
				'info' => "RT/RW ". $item['rt']."/".$item['rw']." - ".strtoupper($item['dusun'])
			);
			$hasil[] = $penduduk;
		}
		return $hasil;
	}

	private function _get_id_terdata_kk($id_suplemen){
		$hasil = array();
		$sql = "SELECT k.no_kk
			FROM tweb_keluarga k
			LEFT JOIN suplemen_terdata t ON k.no_kk = t.id_terdata
			WHERE t.id_suplemen = ?";
		$data = $this->db->query($sql, $id_suplemen)->result_array();
		foreach ($data as $item){
			$hasil[] = $item['no_kk'];
		}
		return $hasil;
	}

	private function _list_kk($id){
		// Keluarga yang sudah terdata untuk suplemen ini
		$terdata = "";
		$list_terdata = $this->_get_id_terdata_kk($id);
		foreach($list_terdata as $key => $value){
			$terdata .= ",".$value;
		}
		$terdata = ltrim($terdata,",");

		// Daftar keluarga, tidak termasuk keluarga yang sudah terdata
		$strSQL = "SELECT k.no_kk as id,p.nama as nama,w.rt,w.rw,w.dusun
			FROM tweb_keluarga k
			LEFT JOIN tweb_penduduk p ON p.id=k.nik_kepala
			LEFT JOIN tweb_wil_clusterdesa w ON w.id=p.id_cluster
		WHERE k.no_kk NOT IN (?)";
		$hasil = array();
		$data = $this->db->query($strSQL,$terdata)->result_array();
		foreach($data as $item){
			$item['id'] = preg_replace('/[^a-zA-Z0-9]/', '', $item['id']); //hapus non_alpha di no_kk
			$kk = array(
				'id' => $item['id'],
				'nik' => $item['id'],
				'nama' => strtoupper($item['nama']) ." [".$item['id']."]",
				'info' => "RT/RW ". $item['rt']."/".$item['rw']." - ".strtoupper($item['dusun'])
			);
			$hasil[] = $kk;
		}
		return $hasil;
	}

	public function get_suplemen($id){
		$data = $this->db->where('id',$id)->get('suplemen')->row_array();
		return $data;
	}

	public function get_rincian($p,$suplemen_id){
		$suplemen = $this->db->where('id',$suplemen_id)->get('suplemen')->row_array();
		$sasaran = $suplemen['sasaran'];
		switch ($sasaran) {
			case '1':
				$suplemen['judul_terdata_nama'] = 'NIK';
				$suplemen['judul_terdata_info'] = 'Nama Penduduk';
				$data = $this->_get_penduduk_terdata($suplemen_id, $p);
				break;
			case '2': # sasaran KK
				$suplemen['judul_terdata_nama'] = 'NO. KK';
				$suplemen['judul_terdata_info'] = 'Kepala Keluarga';
				$data = $this->_get_kk_terdata($suplemen_id, $p);
				break;

			default:
				# code...
				break;
		}
		$data['suplemen'] = $suplemen;
		return $data;
	}

	private function _paging($p, $get_terdata_sql) {
		$sql 			= "SELECT COUNT(*) as jumlah ".$get_terdata_sql;
		$query    = $this->db->query($sql);
		$row      = $query->row_array();
		$jml_data = $row['jumlah'];

		$this->load->library('paging');
		$cfg['page']     = $p;
		$cfg['per_page'] = $_SESSION['per_page'];
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);

		return $this->paging;
	}

	private function _get_penduduk_terdata_sql($suplemen_id){
		# Data penduduk
		if (!$jumlah) $select_sql = "p.*,o.nama,w.rt,w.rw,w.dusun";
		$sql = " FROM suplemen_terdata s
			LEFT JOIN tweb_penduduk o ON s.id_terdata=o.nik
			LEFT JOIN tweb_wil_clusterdesa w ON w.id=o.id_cluster
			WHERE s.id_suplemen=".$suplemen_id;
		return $sql;
	}

	private function _get_penduduk_terdata($suplemen_id, $p){
		$hasil = array();
		$get_terdata_sql = $this->_get_penduduk_terdata_sql($suplemen_id);
		$select_sql = "SELECT s.*,o.nama,w.rt,w.rw,w.dusun ";
		$sql = $select_sql.$get_terdata_sql;
		if (!empty($_SESSION['per_page']) and $_SESSION['per_page'] > 0)
		{
			$hasil["paging"] = $this->_paging($p, $get_terdata_sql);
			$paging_sql = ' LIMIT ' .$hasil["paging"]->offset. ',' .$hasil["paging"]->per_page;
			$sql .= $paging_sql;
		}
		$query = $this->db->query($sql);

		if($query->num_rows()>0){
			$data=$query->result_array();
			$i=0;
			while($i<count($data)){
				$data[$i]['id']=$data[$i]['id'];
				$data[$i]['nik']=$data[$i]['id_terdata'];
				$data[$i]['terdata_nama']=$data[$i]['id_terdata'];
				$data[$i]['terdata_info']=$data[$i]['nama'];
				$data[$i]['nama']=strtoupper($data[$i]['nama']);
				$data[$i]['info']= "RT/RW ". $data[$i]['rt']."/".$data[$i]['rw']." - ".strtoupper($data[$i]['dusun']);
				$i++;
			}
			$hasil['terdata'] = $data;
		}
		return $hasil;
	}

	private function _get_kk_terdata_sql($suplemen_id){
		# Data KK
		$sql = " FROM suplemen_terdata s
			LEFT JOIN tweb_keluarga o ON s.id_terdata=o.no_kk
			LEFT JOIN tweb_penduduk q ON o.nik_kepala=q.id
			LEFT JOIN tweb_wil_clusterdesa w ON w.id=q.id_cluster
			WHERE s.id_suplemen=".$suplemen_id;
		return $sql;
	}

	private function _get_kk_terdata($suplemen_id, $p){
		$hasil = array();
		$get_terdata_sql = $this->_get_kk_terdata_sql($suplemen_id);
		$select_sql = "SELECT s.*,s.id_suplemen as nama,o.nik_kepala,o.no_kk,q.nama,w.rt,w.rw,w.dusun ";
		$sql = $select_sql.$get_terdata_sql;
		if (!empty($_SESSION['per_page']) and $_SESSION['per_page'] > 0)
		{
			$hasil["paging"] = $this->_paging($p, $get_terdata_sql);
			$paging_sql = ' LIMIT ' .$hasil["paging"]->offset. ',' .$hasil["paging"]->per_page;
			$sql .= $paging_sql;
		}
		$query = $this->db->query($sql);

		if($query->num_rows()>0){
			$data=$query->result_array();
			$i=0;
			while($i<count($data)){
				$data[$i]['id']=$data[$i]['id'];
				$data[$i]['nik']=$data[$i]['no_kk'];
				$data[$i]['terdata_nama']=$data[$i]['no_kk'];
				$data[$i]['terdata_info']=$data[$i]['nama'];
				$data[$i]['nama']=strtoupper($data[$i]['nama'])." [".$data[$i]['no_kk']."]";
				$data[$i]['info']= "RT/RW ". $data[$i]['rt']."/".$data[$i]['rw']." - ".strtoupper($data[$i]['dusun']);
				$i++;
			}
			$hasil['terdata'] = $data;
		}
		return $hasil;
	}

	/*
		Mengambil data individu terdata
	*/
	public function get_terdata($id_terdata,$sasaran){
		$this->load->model('surat_model');
		switch ($sasaran) {
			case 1:
				# Data penduduk
				$sql   = "SELECT u.id AS id,u.nama AS nama,x.nama AS sex,u.id_kk AS id_kk,
				u.tempatlahir AS tempatlahir,u.tanggallahir AS tanggallahir,
				(select (date_format(from_days((to_days(now()) - to_days(tweb_penduduk.tanggallahir))),'%Y') + 0) AS `(date_format(from_days((to_days(now()) - to_days(tweb_penduduk.tanggallahir))),'%Y') + 0)`
				from tweb_penduduk where (tweb_penduduk.id = u.id)) AS umur,
				w.nama AS status_kawin,f.nama AS warganegara,a.nama AS agama,d.nama AS pendidikan,j.nama AS pekerjaan,u.nik AS nik,c.rt AS rt,c.rw AS rw,c.dusun AS dusun,k.no_kk AS no_kk,k.alamat,
				(select tweb_penduduk.nama AS nama from tweb_penduduk where (tweb_penduduk.id = k.nik_kepala)) AS kepala_kk
				from tweb_penduduk u
				left join tweb_penduduk_sex x on u.sex = x.id
				left join tweb_penduduk_kawin w on u.status_kawin = w.id
				left join tweb_penduduk_agama a on u.agama_id = a.id
				left join tweb_penduduk_pendidikan_kk d on u.pendidikan_kk_id = d.id
				left join tweb_penduduk_pekerjaan j on u.pekerjaan_id = j.id
				left join tweb_wil_clusterdesa c on u.id_cluster = c.id
				left join tweb_keluarga k on u.id_kk = k.id
				left join tweb_penduduk_warganegara f on u.warganegara_id = f.id
				WHERE u.nik = ?";
				$query = $this->db->query($sql,$id_terdata);
				$data  = $query->row_array();
				$data['alamat_wilayah']= $this->surat_model->get_alamat_wilayah($data);
				break;
			case 2:
				# Data KK
				$data = $this->keluarga_model->get_kepala_kk($id_terdata, true);
				$data['nik'] = $data['no_kk']; // no_kk digunakan sebagai id terdata
				break;

			default:
				break;
		}
		return $data;
	}

	public function hapus($id){
		$hasil = $this->db->where('id',$id)->delete('suplemen');
		if($hasil){
			$_SESSION["success"] = 1;
		}else{
			$_SESSION["success"] = -1;
		}
	}

	public function update($id){
		$data = array(
			'sasaran' => $this->input->post('cid'),
			'nama' => $this->input->post('nama'),
			'keterangan' => $this->input->post('keterangan')
		);
		$hasil = $this->db->where('id',$id)->update('suplemen',$data);
		if($hasil){
			$_SESSION["success"] = 1;
		}else{
			$_SESSION["success"] = -1;
		}
	}

	public function add_terdata($post,$id){
		$nik = $post['nik'];
		$sasaran = $this->db->select('sasaran')->where('id',$id)->get('suplemen')->row()->sasaran;
		$hasil = $this->db->where('id_suplemen',$id)->where('id_terdata',$nik)->get('suplemen_terdata');
		if($hasil->num_rows()>0){
			return false;
		}else{
			$data = array(
				'id_suplemen' => $id,
				'id_terdata' => $nik,
				'sasaran' => $sasaran,
				'keterangan' => $post['keterangan']
			);
			return $this->db->insert('suplemen_terdata',$data);
		}
	}

	public function hapus_terdata($terdata_id) {
		$this->db->where('id', $terdata_id);
		$this->db->delete('suplemen_terdata');
	}

	// $id = suplemen_terdata.id
	public function edit_terdata($post,$id){
		$data = $post;
		$this->db->where('id',$id);
		$this->db->update('suplemen_terdata', $data);
	}

	/*
		Mengambil data individu terdata menggunakan id tabel suplemen_terdata
	*/
	public function get_suplemen_terdata_by_id($id) {
		$data = $this->db->where('id', $id)->get('suplemen_terdata')->row_array();
		// Data tambahan untuk ditampilkan
		$terdata = $this->get_terdata($data['id_terdata'], $data['sasaran']);
		switch ($data['sasaran']){
			case 1:
				$data['judul_terdata_nama'] = 'NIK';
				$data['judul_terdata_info'] = 'Nama Terdata';
				$data['terdata_nama'] = $data['id_terdata'];
				$data['terdata_info'] = $terdata['nama'];
				break;
			case 2:
				$data['judul_terdata_nama'] = 'No. KK';
				$data['judul_terdata_info'] = 'Kepala Keluarga';
				$data['terdata_nama'] = $data['id_terdata'];
				$data['terdata_info'] = $terdata['nama'];
				break;
			default:
		}
		return $data;
	}

	public function get_terdata_suplemen($sasaran,$id_terdata){
		$list_suplemen = array();
		/*
		 * Menampilkan keterlibatan $id_terdata dalam data suplemen yang ada
		 *
		 * */
		$strSQL = "SELECT p.id as id, o.id_terdata as nik, p.nama as nama, p.keterangan
			FROM suplemen_terdata o
			LEFT JOIN suplemen p ON p.id = o.id_suplemen
			WHERE ((o.id_terdata='".$id_terdata."') AND (o.sasaran='".$sasaran."'))";
		$query = $this->db->query($strSQL);
		if($query->num_rows() > 0){
			$list_suplemen = $query->result_array();
		}

		switch ($sasaran){
			case 1:
				/*
				 * Rincian Penduduk
				 * */
				$strSQL = "SELECT o.nama,o.foto,o.nik,w.rt,w.rw,w.dusun
					FROM tweb_penduduk o
				 	LEFT JOIN tweb_wil_clusterdesa w ON w.id=o.id_cluster
				 	WHERE o.nik='".$id_terdata."'";
				$query = $this->db->query($strSQL);
				if($query->num_rows() > 0){
					$row = $query->row_array();
					$data_profil = array(
						"id"=>$id,
						"nama"=>$row["nama"] ." - ".$row["nik"],
						"ndesc"=>"Alamat: RT ".strtoupper($row["rt"])." / RW ".strtoupper($row["rw"])." ".strtoupper($row["dusun"]),
						"foto"=>$row["foto"]
						);
				}

				break;
			case 2:
				/*
				 * KK
				 * */
				$strSQL = "SELECT o.nik_kepala,o.no_kk,p.nama,w.rt,w.rw,w.dusun FROM tweb_keluarga o
					LEFT JOIN tweb_penduduk p ON o.nik_kepala=p.id
					LEFT JOIN tweb_wil_clusterdesa w ON w.id=p.id_cluster WHERE o.no_kk='".$id_terdata."'";
				$query = $this->db->query($strSQL);
				if($query->num_rows() > 0){
					$row = $query->row_array();
					$data_profil = array(
						"id"=>$id,
						"nama"=> "Kepala KK : ".$row["nama"].", NO KK: ".$row["no_kk"],
						"ndesc"=>"Alamat: RT ".strtoupper($row["rt"])." / RW ".strtoupper($row["rw"])." ".strtoupper($row["dusun"]),
						"foto"=>""
						);
				}

				break;
			default:

		}
		if(!empty($list_suplemen)){
			$hasil = array("daftar_suplemen"=>$list_suplemen,"profil"=>$data_profil);
			return $hasil;
		}else{
			return null;
		}
	}

}
?>