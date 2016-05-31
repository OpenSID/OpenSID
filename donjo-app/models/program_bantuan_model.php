<?php class Program_bantuan_model extends CI_Model{

	function __construct(){
		$this->load->database();
	}
	public function list_program($sasaran=0){
		if ($sasaran > 0){
			$strSQL   = "SELECT p.id,p.nama,p.sasaran,p.ndesc,p.sdate,p.edate,p.userid,p.status  FROM program p WHERE p.sasaran=".$sasaran;
		}else{
			$strSQL   = "SELECT p.id,p.nama,p.sasaran,p.ndesc,p.sdate,p.edate,p.userid,p.status  FROM program p WHERE 1";
		}
		$query = $this->db->query($strSQL);
		$data = $query->result_array();
		return $data;
	}
	
	public function get_program($slug){
		if ($slug === false){
			$strSQL   = "SELECT p.id,p.nama,p.sasaran,p.ndesc,p.sdate,p.edate,p.userid,p.status  FROM program p WHERE 1";
			$query = $this->db->query($strSQL);
			$data = $query->result_array();
			return $data;
		}else{
			$strSQL   = "SELECT p.id,p.nama,p.sasaran,p.ndesc,p.sdate,p.edate,p.userid,p.status  FROM program p WHERE p.id=".$slug;
			$query = $this->db->query($strSQL);
			$hasil0 = $query->row_array();
			
			switch ($hasil0["sasaran"]){
				case 1:
					/*
					 * Data penduduk
					 * */
					$strSQL = "SELECT p.id,p.peserta,o.nama,w.rt,w.rw,w.dusun FROM program_peserta p 
						LEFT JOIN tweb_penduduk o ON p.peserta=o.nik 
						LEFT JOIN tweb_wil_clusterdesa w ON w.id=o.id_cluster WHERE p.program_id=".$slug;
					$query = $this->db->query($strSQL);
					$filter = array();
					if($query->num_rows()>0){
						$data=$query->result_array();
						$i=0;
						while($i<count($data)){
							$data[$i]['id']=$data[$i]['id'];
							$data[$i]['nik']=$data[$i]['peserta'];
							$filter[] = $data[$i]['peserta'];
							$data[$i]['nama']=strtoupper($data[$i]['nama'])." [".$data[$i]['peserta']."]";
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
					$strSQL = "SELECT p.id as id,p.peserta as nama,o.nik_kepala,o.no_kk,q.nama,w.rt,w.rw,w.dusun FROM program_peserta p 
						LEFT JOIN tweb_keluarga o ON p.peserta=o.no_kk 
						LEFT JOIN tweb_penduduk q ON o.nik_kepala=q.id 
						LEFT JOIN tweb_wil_clusterdesa w ON w.id=q.id_cluster 
						WHERE p.program_id=".$slug;
					$query = $this->db->query($strSQL);
					$filter = array();
					if($query->num_rows()>0){
						$data=$query->result_array();
						$i=0;
						while($i<count($data)){
							$data[$i]['id']=$data[$i]['id'];
							$data[$i]['nik']=$data[$i]['no_kk'];
							$filter[] = $data[$i]['id'];
							$data[$i]['nama']=strtoupper($data[$i]['nama'])." [".$data[$i]['no_kk']."]";
							$data[$i]['info']= "RT/RW ". $data[$i]['rt']."/".$data[$i]['rw']." - ".strtoupper($data[$i]['dusun']);
							$i++;
						}
						$hasil1 = $data;
					}else{
						$hasil1 = false;
					}
					$strSQL = "SELECT k.no_kk as id,p.nama as nama,w.rt,w.rw,w.dusun FROM tweb_keluarga k 
						LEFT JOIN tweb_penduduk p ON p.id=k.nik_kepala 
						LEFT JOIN tweb_wil_clusterdesa w ON w.id=p.id_cluster 
					WHERE 1";
					$query = $this->db->query($strSQL);
					$data = "";
					$data=$query->result_array();
					if($query->num_rows() > 0){
						$i=0;$j=0;
						while($i<count($data)){
							if(!in_array($data[$i]['id'],$filter)){
								$data[$j]['id']=$data[$i]['id'];
								$data[$j]['nik']=$data[$i]['id'];
								$data[$j]['nama']=strtoupper($data[$i]['nama']) ." [".$data[$i]['id']."]";
								$data[$j]['info']= "RT/RW ". $data[$i]['rt']."/".$data[$i]['rw']." - ".strtoupper($data[$i]['dusun']);
								$j++;
							}
							$i++;
						}
						$hasil2 = $data;
					}else{
						$hasil2 = false;
					}
					break;
				case 3:
					/*
					 * Data RTM
					 * */
					$strSQL = "SELECT p.id,p.peserta,o.nama,o.nik,r.no_kk,w.rt,w.rw,w.dusun FROM program_peserta p 
						LEFT JOIN tweb_rtm r ON r.id = p.peserta
						LEFT JOIN tweb_penduduk o ON o.id=r.nik_kepala 
						LEFT JOIN tweb_wil_clusterdesa w ON w.id=o.id_cluster WHERE p.program_id=".$slug;
					$query = $this->db->query($strSQL);
					$filter = array();
					if($query->num_rows()>0){
						$data=$query->result_array();
						$i=0;
						while($i<count($data)){
							$data[$i]['id']=$data[$i]['id'];
							$data[$i]['nik']=$data[$i]['peserta'];
							$filter[] = $data[$i]['peserta'];
							$data[$i]['nama']=strtoupper($data[$i]['nama'])." [".$data[$i]['nik']." - ".$data[$i]['no_kk']."]";
							$data[$i]['info']= "RT/RW ". $data[$i]['rt']."/".$data[$i]['rw']." - ".strtoupper($data[$i]['dusun']);
							$i++;
						}
						$hasil1 = $data;
					}else{
						$hasil1 = false;
					}
					 
					$strSQL = "SELECT r.id, r.no_kk, o.nama,w.rt,w.rw,w.dusun  FROM tweb_rtm r 
						LEFT JOIN tweb_penduduk o ON o.id=r.nik_kepala  
						LEFT JOIN tweb_wil_clusterdesa w ON w.id=o.id_cluster 
						WHERE 1
						";
					$query = $this->db->query($strSQL);
					$data = "";
					$data=$query->result_array();
					if($query->num_rows() > 0){
						$i=0;$j=0;
						while($i<count($data)){
							if(!in_array($data[$i]['id'],$filter)){
								$data[$j]['id']=$data[$i]['id'];
								$data[$j]['nik']=$data[$i]['id'];
								$data[$j]['nama']=strtoupper($data[$i]['nama']);
								$data[$j]['info']="RT/RW ". $data[$i]['rt']."/".$data[$i]['rw']." - ".strtoupper($data[$i]['dusun']);
								$j++;
							}
							$i++;
						}
						$hasil2 = $data;
					}else{
						$hasil2 = false;
					}
					break;
				case 4:
					/*
					 * Data Kelompok
					 * */
					$strSQL = "SELECT p.id as id, p.peserta as peserta, k.nama as nama FROM program_peserta p 
						LEFT JOIN kelompok k ON k.id=p.peserta 
						WHERE p.program_id=".$slug;
					$query = $this->db->query($strSQL);
					$filter = array();
					if($query->num_rows()>0){
						$data=$query->result_array();
						$i=0;
						while($i<count($data)){
							$data[$i]['id']=$data[$i]['id'];
							$data[$i]['nik']=$data[$i]['peserta'];
							$filter[] = $data[$i]['id'];
							$data[$i]['nama']=strtoupper($data[$i]['nama']);
							$data[$i]['info']="";
							$i++;
						}
						$hasil1 = $data;
					}else{
						$hasil1 = false;
					}

					$strSQL = "SELECT id,nama FROM kelompok WHERE 1";
					$query = $this->db->query($strSQL);
					$data = "";
					$data=$query->result_array();
					if($query->num_rows() > 0){
						$i=0;
						while($i<count($data)){
							if(!in_array($data[$i]['id'],$filter)){
								$data[$i]['id']=$data[$i]['id'];
								$data[$i]['nik']=$data[$i]['id'];
								$data[$i]['nama']=strtoupper($data[$i]['nama']);
								$data[$i]['info']="";
							}
							$i++;
						}
						$hasil2 = $data;
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
	
	public function get_peserta_program($cat,$id){
		$data_program = false;
		/*
		 * fungsi untuk menampilkan keterlibatan $id dalam program intervensi yg telah dilakukan, 
		 * $cat => $sasaran adalah tipe/kategori si $id. 
		 * 
		 * */
		$strSQL = "SELECT p.id as id, o.peserta as nik, p.nama as nama, p.sdate, p.edate, p.ndesc FROM program_peserta o 
			LEFT JOIN program p ON p.id = o.program_id 
			WHERE ((o.peserta='".fixSQL($id)."') AND (o.sasaran='".fixSQL($cat)."'))";
		$query = $this->db->query($strSQL);
		if($query->num_rows() > 0){
			$data_program = $query->result_array();
		}
		
		switch ($cat){
			case 1:
				/*
				 * Detail Penduduk
				 * */
				$strSQL = "SELECT o.nama,o.foto,o.nik,w.rt,w.rw,w.dusun FROM tweb_penduduk o 
				 LEFT JOIN tweb_wil_clusterdesa w ON w.id=o.id_cluster WHERE o.nik='".fixSQL($id)."'";
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
					WHERE 1
					";
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
			$hasil = array($data_program,$data_profil);
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
			'userid' => $this->input->post('userid'),
			'sdate' => date("Y-m-d",strtotime($this->input->post('sdate'))),
			'edate' => date("Y-m-d",strtotime($this->input->post('edate')))
		);
		return $this->db->insert('program', $data);
	}
	
	public function add_peserta($nik,$id){
		$strSQL = "SELECT sasaran FROM program WHERE id=".$id;
		$hasil = $this->db->query($strSQL);
		if($hasil->num_rows()>0){
			$row = $hasil->row_array();
		}		
		$strSQL = "SELECT id FROM `program_peserta` WHERE program_id='".fixSQL($id)."' AND peserta='".fixSQL($nik)."'";
		$hasil = $this->db->query($strSQL);
		if($hasil->num_rows()>0){
			return false;
		}else{
			$strSQL = "INSERT INTO `program_peserta`(program_id,peserta,sasaran) VALUES('".$id."','".fixSQL($nik)."','".$row["sasaran"]."')";
			$hasil = $this->db->query($strSQL);
			if($hasil){
				return true;
			}else{
				return false;
			}
		}
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
}

?>
 
