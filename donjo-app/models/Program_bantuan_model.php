<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Program_bantuan_model extends CI_Model{

	function __construct(){
		$this->load->database();
		$this->load->model('rtm_model');
		$this->load->model('kelompok_model');
	}
	public function list_program($sasaran=0){
		if ($sasaran > 0){
			$strSQL = "SELECT p.id,p.nama,p.sasaran,p.ndesc,p.sdate,p.edate,p.userid,p.status
				FROM program p WHERE p.sasaran=".$sasaran;
		}else{
			$strSQL = "SELECT p.id,p.nama,p.sasaran,p.ndesc,p.sdate,p.edate,p.userid,p.status, CONCAT('50',p.id) as lap
				FROM program p WHERE 1";
		}
		$query = $this->db->query($strSQL);
		$data = $query->result_array();
		return $data;
	}

	public function link_statistik_program_bantuan(){
		$strSQL = "
			SELECT CONCAT('statistik/50',p.id) as id, p.nama, p.sasaran
			FROM program p
			WHERE 1 ORDER BY p.nama";
		$query = $this->db->query($strSQL);
		$hasil = $query->result_array();
		$data = array();
		$sasaran = unserialize(SASARAN);
		foreach($hasil as $program){
			$data[$program['id']] = $program['nama'].' ('.$sasaran[$program['sasaran']].')';
		}
		return $data;
	}

	public function list_program_keluarga($kk_id=0){
		$this->load->model('keluarga_model'); // Di-load di sini karena tidak bisa diload di constructor
		$no_kk = $this->keluarga_model->get_nokk($kk_id);
		$sasaran = 2;
		$strSQL = "
			SELECT p.id,p.nama,p.sasaran,p.ndesc,p.sdate,p.edate,p.userid,p.status, CONCAT('50',p.id) as lap, pp.peserta
			FROM program p
			LEFT OUTER JOIN program_peserta pp ON p.id = pp.program_id AND pp.peserta = '$no_kk'
			WHERE p.sasaran = $sasaran";
		$query = $this->db->query($strSQL);
		$data = $query->result_array();
		return $data;
	}

	function paging_peserta($p, $slug, $sasaran) {
		$sql 			= $this->get_peserta_sql($slug,$sasaran,true);
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

	function paging_bantuan($p) {
		$sql 			= "SELECT count(*) as jumlah FROM program";
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

	/*
		Mengambil data individu peserta
	*/
	function get_peserta($peserta_id, $sasaran) {
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
				$query = $this->db->query($sql,$peserta_id);
				$data  = $query->row_array();
				$data['alamat_wilayah']= $this->surat_model->get_alamat_wilayah($data);
				break;
			case 2:
				# Data KK
				$data = $this->keluarga_model->get_kepala_kk($peserta_id, true);
				$data['nik'] = $data['no_kk']; // no_kk digunakan sebagai id peserta
				break;
			case 3:
				# Data RTM
				$data = $this->rtm_model->get_kepala_rtm($peserta_id,true);
				$data['nik'] = $data['no_kk']; // no_kk digunakan sebagai id peserta
				break;
			case 4:
				# Data Kelompok
				$data = $this->kelompok_model->get_ketua_kelompok($peserta_id);
				$data['nik'] = $data['nama_kelompok']; // nama_kelompok untuk tampilan, id_kelompok digunakan sebagai id peserta
				break;

			default:
				break;
		}
		return $data;
	}

	// Query dibuat pada satu tempat, supaya penghitungan baris untuk paging selalu
	// konsisten dengan data yang diperoleh
	function get_peserta_sql($slug, $sasaran, $jumlah=false) {
		if ($jumlah) $select_sql = "COUNT(*) as jumlah";
		switch ($sasaran) {
			case 1:
				# Data penduduk
				if (!$jumlah) $select_sql = "p.*,o.nama,w.rt,w.rw,w.dusun";
				$strSQL = "SELECT ". $select_sql." FROM program_peserta p
					LEFT JOIN tweb_penduduk o ON p.peserta=o.nik
					LEFT JOIN tweb_wil_clusterdesa w ON w.id=o.id_cluster WHERE p.program_id=".$slug;
				break;
			case 2:
				# Data KK
				if (!$jumlah) $select_sql = "p.*,p.peserta as nama,o.nik_kepala,o.no_kk,q.nama,w.rt,w.rw,w.dusun";
				$strSQL = "SELECT ". $select_sql." FROM program_peserta p
					LEFT JOIN tweb_keluarga o ON p.peserta=o.no_kk
					LEFT JOIN tweb_penduduk q ON o.nik_kepala=q.id
					LEFT JOIN tweb_wil_clusterdesa w ON w.id=q.id_cluster
					WHERE p.program_id=".$slug;
				break;
			case 3:
				# Data RTM
				if (!$jumlah) $select_sql = "p.*,o.nama,o.nik,r.no_kk,w.rt,w.rw,w.dusun";
				$strSQL = "SELECT ". $select_sql." FROM program_peserta p
					LEFT JOIN tweb_rtm r ON r.no_kk = p.peserta
					LEFT JOIN tweb_penduduk o ON o.id=r.nik_kepala
					LEFT JOIN tweb_wil_clusterdesa w ON w.id=o.id_cluster
					WHERE p.program_id=".$slug;
				break;
			case 4:
				# Data Kelompok
				if (!$jumlah) $select_sql = "p.*,o.nama,o.nik,r.nama as nama_kelompok,w.rt,w.rw,w.dusun";
				$strSQL = "SELECT ". $select_sql." FROM program_peserta p
					LEFT JOIN kelompok r ON r.id = p.peserta
					LEFT JOIN tweb_penduduk o ON o.id=r.id_ketua
					LEFT JOIN tweb_wil_clusterdesa w ON w.id=o.id_cluster
					WHERE p.program_id=".$slug;
				break;

			default:
				break;
		}
		return $strSQL;
	}

	function get_sasaran($id) {
		$this->db->select('sasaran, nama');
		$this->db->where('id', $id);
		$query = $this->db->get('program');
		$data = $query->row_array();
		switch ($data['sasaran']) {
			case 1:
				$data['judul_sasaran'] = 'Sasaran Penduduk';
				break;
			case 2:
				$data['judul_sasaran'] = 'Sasaran Keluarga';
				break;
			case 3:
				$data['judul_sasaran'] = 'Sasaran Rumah Tangga';
				break;
			case 4:
				$data['judul_sasaran'] = 'Sasaran Kelompok';
				break;

			default:
				$data['judul_sasaran'] = 'Sasaran Penduduk';
				break;
		}
		return $data;
	}

	public function get_program($p, $slug){
		if ($slug === false){
			$response['paging'] = $this->paging_bantuan($p);
			$strSQL   = "SELECT p.id,p.nama,p.sasaran,p.ndesc,p.sdate,p.edate,p.userid,p.status  FROM program p WHERE 1";
			$strSQL .= ' LIMIT ' .$response["paging"]->offset. ',' .$response["paging"]->per_page;
			$query = $this->db->query($strSQL);
			$data = $query->result_array();
			$response['program'] = $data;
			return $response;
		}else{
			// Untuk program bantuan, $slug berbentuk '50<program_id>'
			$slug = preg_replace("/^50/", "", $slug);
			$strSQL   = "SELECT p.id,p.nama,p.sasaran,p.ndesc,p.sdate,p.edate,p.userid,p.status  FROM program p WHERE p.id=".$slug;
			$query = $this->db->query($strSQL);
			$hasil0 = $query->row_array();

			$hasil0["paging"] = $this->paging_peserta($p, $slug, $hasil0["sasaran"]);
			$paging_sql = ' LIMIT ' .$hasil0["paging"]->offset. ',' .$hasil0["paging"]->per_page;
			$strSQL = $this->get_peserta_sql($slug,$hasil0["sasaran"]);
			$strSQL .= $paging_sql;
			$query = $this->db->query($strSQL);

			switch ($hasil0["sasaran"]){
				case 1:
					/*
					 * Data penduduk
					 * */
					$hasil0['judul_peserta'] = 'NIK';
					$hasil0['judul_peserta_info'] = 'Nama Peserta';
					$filter = array();
					if($query->num_rows()>0){
						$data=$query->result_array();
						$i=0;
						while($i<count($data)){
							$data[$i]['id']=$data[$i]['id'];
							$data[$i]['nik']=$data[$i]['peserta'];
							$data[$i]['peserta_nama']=$data[$i]['peserta'];
							$data[$i]['peserta_info']=$data[$i]['nama'];
							$filter[] = $data[$i]['peserta'];
							$data[$i]['nama']=strtoupper($data[$i]['nama']);
							$data[$i]['info']= "RT/RW ". $data[$i]['rt']."/".$data[$i]['rw']." - ".strtoupper($data[$i]['dusun']);
							$i++;
						}
						$hasil1 = $data;
					}else{
						$hasil1 = false;
					}

					$strSQL = "SELECT p.nik,p.nama,w.rt,w.rw,w.dusun FROM tweb_penduduk p
						LEFT JOIN tweb_wil_clusterdesa w ON w.id=p.id_cluster
						WHERE 1 ORDER BY nama";
					$query = $this->db->query($strSQL);
					$data = "";
					$data=$query->result_array();
					if($query->num_rows() > 0){
						$i=0;$j=0;
						while($i<count($data)){
							// Abaikan penduduk yang sudah terdaftar pada program
							if(!in_array($data[$i]['nik'],$filter)){
								if($data[$i]['nik']!=""){
									$data1[$j]['id']=$data[$i]['nik'];
									$data1[$j]['nik']=$data[$i]['nik'];
									$data1[$j]['nama']=strtoupper($data[$i]['nama'])." [".$data[$i]['nik']."]";
									$data1[$j]['info']= "RT/RW ". $data[$i]['rt']."/".$data[$i]['rw']." - ".strtoupper($data[$i]['dusun']);
									$j++;
								}
							}
							$i++;
						}
						$hasil2 = $data1;
					}else{
						$hasil2 = false;
					}
					break;
				case 2:
					/*
					 * Data KK
					 * */
					$hasil0['judul_peserta'] = 'NO. KK';
					$hasil0['judul_peserta_info'] = 'Kepala Keluarga';
					$filter = array();
					if($query->num_rows()>0){
						$data=$query->result_array();
						$i=0;
						while($i<count($data)){
							$data[$i]['id']=$data[$i]['id'];
							$data[$i]['nik']=$data[$i]['no_kk'];
							$data[$i]['peserta_nama']=$data[$i]['no_kk'];
							$data[$i]['peserta_info']=$data[$i]['nama'];
							$filter[] = $data[$i]['no_kk'];
							$data[$i]['nama']=strtoupper($data[$i]['nama'])." [".$data[$i]['no_kk']."]";

							$data[$i]['info']= "RT/RW ". $data[$i]['rt']."/".$data[$i]['rw']." - ".strtoupper($data[$i]['dusun']);
							$i++;
						}
						$hasil1 = $data;
					}else{
						$hasil1 = false;
					}
					// Daftar keluarga, tidak termasuk keluarga yang sudah menjadi peserta
					$strSQL = "SELECT k.no_kk as id,p.nama as nama,w.rt,w.rw,w.dusun FROM tweb_keluarga k
						LEFT JOIN tweb_penduduk p ON p.id=k.nik_kepala
						LEFT JOIN tweb_wil_clusterdesa w ON w.id=p.id_cluster
					WHERE 1";
					$query = $this->db->query($strSQL);
					$hasil2 = array();
					$data=$query->result_array();
					if($query->num_rows() > 0){
						$i=0;$j=0;
						while($i<count($data)){
							// Abaikan keluarga yang sudah terdaftar pada program
							if(!in_array($data[$i]['id'],$filter)){
								$data[$i]['id'] = preg_replace('/[^a-zA-Z0-9]/', '', $data[$i]['id']); //Hapus karakter non alpha di no_kk
								$hasil2[$j]['id']=$data[$i]['id'];
								$hasil2[$j]['nik']=$data[$i]['id'];
								$hasil2[$j]['nama']=strtoupper($data[$i]['nama']) ." [".$data[$i]['id']."]";
								$hasil2[$j]['info']= "RT/RW ". $data[$i]['rt']."/".$data[$i]['rw']." - ".strtoupper($data[$i]['dusun']);
								$j++;
							}
							$i++;
						}
					}else{
						$hasil2 = false;
					}
					break;
				case 3:
					/*
					 * Data RTM
					 * */
					$hasil0['judul_peserta'] = 'NO. Rumah Tangga';
					$hasil0['judul_peserta_info'] = 'Kepala Rumah Tangga';
					$filter = array();
					if($query->num_rows()>0){
						$data=$query->result_array();
						$i=0;
						while($i<count($data)){
							$data[$i]['id']=$data[$i]['id'];
							$data[$i]['nik']=$data[$i]['peserta'];
							$data[$i]['peserta_nama']=$data[$i]['no_kk'];
							$data[$i]['peserta_info']=$data[$i]['nama'];
							$filter[] = $data[$i]['peserta'];
							$data[$i]['nama']=strtoupper($data[$i]['nama'])." [".$data[$i]['nik']." - ".$data[$i]['no_kk']."]";
							$data[$i]['info']= "RT/RW ". $data[$i]['rt']."/".$data[$i]['rw']." - ".strtoupper($data[$i]['dusun']);
							$i++;
						}
						$hasil1 = $data;
					}else{
						$hasil1 = false;
					}

					$strSQL = "SELECT r.no_kk as id, o.nama,w.rt,w.rw,w.dusun  FROM tweb_rtm r
						LEFT JOIN tweb_penduduk o ON o.id=r.nik_kepala
						LEFT JOIN tweb_wil_clusterdesa w ON w.id=o.id_cluster
						WHERE 1
						";
					$query = $this->db->query($strSQL);
					$hasil2 = array();;
					$data=$query->result_array();
					if($query->num_rows() > 0){
						$i=0;$j=0;
						while($i<count($data)){
							// Abaikan RTM yang sudah terdaftar pada program
							if(!in_array($data[$i]['id'],$filter)){
								$hasil2[$j]['id']=$data[$i]['id'];
								$hasil2[$j]['nik']=$data[$i]['id'];
								$hasil2[$j]['nama']=strtoupper($data[$i]['nama'])." [".$data[$i]['id']."]";
								$hasil2[$j]['info']="RT/RW ". $data[$i]['rt']."/".$data[$i]['rw']." - ".strtoupper($data[$i]['dusun']);
								$j++;
							}
							$i++;
						}
					}else{
						$hasil2 = false;
					}
					break;
				case 4:
					/*
					 * Data Kelompok
					 * */

					$hasil0['judul_peserta'] = 'Nama Kelompok';
					$hasil0['judul_peserta_info'] = 'Ketua Kelompok';
					$filter = array();
					if($query->num_rows()>0){
						$data=$query->result_array();
						$i=0;
						while($i<count($data)){
							$data[$i]['id']=$data[$i]['id'];
							$data[$i]['nik']=$data[$i]['nama_kelompok'];
							$data[$i]['peserta_nama']=$data[$i]['nama_kelompok'];
							$data[$i]['peserta_info']=$data[$i]['nama'];
							$filter[] = $data[$i]['peserta'];
							$data[$i]['nama']=strtoupper($data[$i]['nama']);
							$data[$i]['info']= "RT/RW ". $data[$i]['rt']."/".$data[$i]['rw']." - ".strtoupper($data[$i]['dusun']);
							$i++;
						}
						$hasil1 = $data;
					}else{
						$hasil1 = false;
					}

					$strSQL = "SELECT k.id,k.nama as nama_kelompok,o.nama,w.rt,w.rw,w.dusun FROM kelompok k
						LEFT JOIN tweb_penduduk o ON o.id=k.id_ketua
						LEFT JOIN tweb_wil_clusterdesa w ON w.id=o.id_cluster
						WHERE 1";
					$query = $this->db->query($strSQL);
					$hasil2 = array();
					$data=$query->result_array();
					if($query->num_rows() > 0){
						$i=0; $j=0;
						while($i<count($data)){
							// Abaikan kelompok yang sudah terdaftar pada program
							if(!in_array($data[$i]['id'],$filter)){
								$hasil2[$j]['id']=$data[$i]['id'];
								$hasil2[$j]['nik']=$data[$i]['id'];
								$hasil2[$j]['nama']=strtoupper($data[$i]['nama'])." [".$data[$i]['nama_kelompok']."]";
								$hasil2[$j]['info']="RT/RW ". $data[$i]['rt']."/".$data[$i]['rw']." - ".strtoupper($data[$i]['dusun']);
								$j++;
							}
							$i++;
						}
					}else{
						$hasil2 = false;
					}
					break;
				default:

			}
			$hasil = array($hasil0,$hasil1,$hasil2);
			return $hasil;
		}
	}

	// Ambil data program
	function get_data_program($id){
		// Untuk program bantuan, $id '50<program_id>'
		$program_id = preg_replace("/^50/", "", $id);
		return $this->db->select("*")->where("id",$program_id)->get("program")->row_array();
	}

	public function get_peserta_program($cat,$id){
		$data_program = false;
		/*
		 * fungsi untuk menampilkan keterlibatan $id dalam program intervensi yg telah dilakukan,
		 * $cat => $sasaran adalah tipe/kategori si $id.
		 *
		 * */
		$strSQL = "SELECT p.id as id, o.peserta as nik, p.nama as nama, p.sdate, p.edate, p.ndesc
			FROM program_peserta o
			LEFT JOIN program p ON p.id = o.program_id
			WHERE ((o.peserta='".fixSQL($id)."') AND (o.sasaran='".fixSQL($cat)."'))";
		$query = $this->db->query($strSQL);
		if($query->num_rows() > 0){
			$data_program = $query->result_array();
		}

		switch ($cat){
			case 1:
				/*
				 * Rincian Penduduk
				 * */
				$strSQL = "SELECT o.nama,o.foto,o.nik,w.rt,w.rw,w.dusun
					FROM tweb_penduduk o
				 	LEFT JOIN tweb_wil_clusterdesa w ON w.id=o.id_cluster
				 	WHERE o.nik='".fixSQL($id)."'";
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
					LEFT JOIN tweb_wil_clusterdesa w ON w.id=p.id_cluster WHERE o.no_kk='".fixSQL($id)."'";
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
			case 3:
				/*
				 * RTM
				 * */
				$strSQL = "SELECT r.id, r.no_kk, o.nama, o.nik,w.rt,w.rw,w.dusun  FROM tweb_rtm r
					LEFT JOIN tweb_penduduk o ON o.id=r.nik_kepala
					LEFT JOIN tweb_wil_clusterdesa w ON w.id=o.id_cluster
					WHERE r.no_kk=$id";
				$query = $this->db->query($strSQL);
				if($query->num_rows() > 0){
					$row = $query->row_array();
					$data_profil = array(
						"id"=>$id,
						"nama"=> "Kepala RTM : ".$row["nama"].", NIK: ".$row["nik"],
						"ndesc"=>"Alamat: RT ".strtoupper($row["rt"])." / RW ".strtoupper($row["rw"])." ".strtoupper($row["dusun"]),
						"foto"=>""
						);
				}

				break;
			case 4:
				/*
				 * Kelompok
				 * */
				$strSQL = "SELECT k.id as id,k.nama as nama,p.nama as ketua,p.nik as nik,w.rt,w.rw,w.dusun FROM kelompok k
				 LEFT JOIN tweb_penduduk p ON p.id=k.id_ketua
				 LEFT JOIN tweb_wil_clusterdesa w ON w.id=p.id_cluster
				 WHERE k.id='".fixSQL($id)."'";
				$query = $this->db->query($strSQL);
				if($query->num_rows() > 0){
					$row = $query->row_array();
					$data_profil = array(
						"id"=>$id,
						"nama"=> $row["nama"],
						"ndesc"=>"Ketua: ".$row["ketua"]." [".$row["nik"]."]<br />Alamat: RT ".strtoupper($row["rt"])." / RW ".strtoupper($row["rw"])." ".strtoupper($row["dusun"]),
						"foto"=>""
						);
				}
				break;
			default:

		}
		if(!$data_program==false){
			$hasil = array("programkerja"=>$data_program,"profil"=>$data_profil);
			return $hasil;
		}else{
			return null;
		}
	}

	public function set_program(){

		$data = array(
			'sasaran' => $this->input->post('cid'),
			'nama' => fixSQL($this->input->post('nama')),
			'ndesc' => fixSQL($this->input->post('ndesc')),
			'userid' =>  $_SESSION['user'],
			'sdate' => date("Y-m-d",strtotime($this->input->post('sdate'))),
			'edate' => date("Y-m-d",strtotime($this->input->post('edate')))
		);
		return $this->db->insert('program', $data);
	}

	public function add_peserta($post,$id){
		$nik = $post['nik'];
		$strSQL = "SELECT sasaran FROM program WHERE id=".$id;
		$hasil = $this->db->query($strSQL);
		$row = $hasil->row_array();
		$sasaran = $row['sasaran'];
		// Untuk sasaran kelompok, $id adalah nama kelompok, jadi perlu mengambil
		// id kelompok yang digunakan sebagai id peserta
		if ($sasaran == 4){
			$this->db->select('id');
			$this->db->where('nama',$nik);
			$query = $this->db->get('kelompok');
			$kelompok = $query->row_array();
			$nik = $kelompok['id'];
		}
		$strSQL = "SELECT id FROM `program_peserta` WHERE program_id='".fixSQL($id)."' AND peserta='".fixSQL($nik)."'";
		$hasil = $this->db->query($strSQL);
		if($hasil->num_rows()>0){
			return false;
		}else{
			$data = array(
				'program_id' => $id,
				'peserta' => fixSQL($nik),
				'sasaran' => $row["sasaran"],
				'no_id_kartu' => $post['no_id_kartu'],
				'kartu_nik' => $post['kartu_nik'],
				'kartu_nama' => $post['kartu_nama'],
				'kartu_tempat_lahir' => $post['kartu_tempat_lahir'],
				'kartu_tanggal_lahir' => tgl_indo_in($post['kartu_tanggal_lahir']),
				'kartu_alamat' => $post['kartu_alamat']
			);
			$file_gambar = $this->_upload_gambar();
			if($file_gambar) $data['kartu_peserta'] = $file_gambar;
			return $this->db->insert('program_peserta',$data);
		}
	}

	// $id = program_peserta.id
	public function edit_peserta($post,$id){
		$data = $post;
		if($data['gambar_hapus']){
		  unlink(LOKASI_DOKUMEN . $data['gambar_hapus']);
			$data['kartu_peserta'] = '';
		}
		unset($data['gambar_hapus']);
		$file_gambar = $this->_upload_gambar($data['old_gambar']);
		if($file_gambar) $data['kartu_peserta'] = $file_gambar;
		unset($data['old_gambar']);
		$this->db->where('id',$id);
		$data['kartu_tanggal_lahir'] = tgl_indo_in($data['kartu_tanggal_lahir']);
		$outp = $this->db->update('program_peserta', $data);
	}

	function _upload_gambar($old_document=''){
		$lokasi_file = $_FILES['satuan']['tmp_name'];
		if (!empty($lokasi_file)){
			$nama_file = $_FILES['satuan']['name'];
			$nama_file   = time().'-'.urlencode($nama_file); 	 // normalkan nama file
			UploadDocument($nama_file,$old_document);
			return $nama_file;
		}
	}


	public function hapus_peserta_program($peserta_id, $program_id) {
		$this->db->where(array('peserta' => $peserta_id, 'program_id' => $program_id));
		$this->db->delete('program_peserta');
	}

	public function hapus_peserta($peserta_id) {
		$this->db->where('id', $peserta_id);
		$this->db->delete('program_peserta');
	}

	/*
		Mengambil data individu peserta menggunakan id tabel program_peserta
	*/
	public function get_program_peserta_by_id($id) {
		$this->db->where('id', $id);
		$query = $this->db->get('program_peserta');
		$data = $query->row_array();
		// Data tambahan untuk ditampilkan
		$peserta = $this->get_peserta($data['peserta'], $data['sasaran']);
		switch ($data['sasaran']){
			case 1:
				$data['judul_peserta'] = 'NIK';
				$data['judul_peserta_info'] = 'Nama Peserta';
				$data['peserta_nama'] = $data['peserta'];
				$data['peserta_info'] = $peserta['nama'];
				break;
			case 2:
				$data['judul_peserta'] = 'No. KK';
				$data['judul_peserta_info'] = 'Kepala Keluarga';
				$data['peserta_nama'] = $data['peserta'];
				$data['peserta_info'] = $peserta['nama'];
				break;
			case 3:
				$data['judul_peserta'] = 'No. Rumah Tangga';
				$data['judul_peserta_info'] = 'Kepala Rumah Tangga';
				$data['peserta_nama'] = $data['peserta'];
				$data['peserta_info'] = $peserta['nama'];
				break;
			case 4:
				$data['judul_peserta'] = 'Nama Kelompok';
				$data['judul_peserta_info'] = 'Ketua Kelompok';
				$data['peserta_nama'] = $peserta['nama_kelompok'];
				$data['peserta_info'] = $peserta['nama'];
				break;
			default:
		}
		return $data;
	}


	public function update_program($id){
		$strSQL = "UPDATE `program` SET `sasaran`='".$this->input->post('cid')."',
		`nama`='".fixSQL($this->input->post('nama'))."',
		`ndesc`='".fixSQL($this->input->post('ndesc'))."',
		`sdate`='".date("Y-m-d",strtotime($this->input->post('sdate')))."',
		`edate`='".date("Y-m-d",strtotime($this->input->post('edate')))."',
		`status`='".$this->input->post('status')."'
		 WHERE id=".$id;

		$hasil = $this->db->query($strSQL);
		if($hasil){
			$_SESSION["success"] = 1;
			$_SESSION["pesan"] = "Data program telah diperbarui";
		}else{
			$_SESSION["success"] = -1;
		}
	}

	public function hapus_program($id){
		$strSQL = "DELETE FROM `program` WHERE id=".$id;
		$hasil = $this->db->query($strSQL);
		if($hasil){
			$_SESSION["success"] = 1;
			$_SESSION["pesan"] = "Data program telah dihapus";
		}else{
			$_SESSION["success"] = -1;
		}
	}

	/* Mendapatkan daftar bantuan yang diterima oleh penduduk
		 parameter pencarian yang digunakan adalah nik ( data nik disimpan pada kolom peserta tabel program_peserta ).
		 Saat ini terbatas pada program bantuan perorangan
	*/
	public function daftar_bantuan_yang_diterima($nik){
		return $this->db->select('p.*,pp.*')
					->where(array('peserta' => $nik))
					->join('program p','p.id = pp.program_id')
					->get('program_peserta pp')
					->result_array();
	}
}

?>
