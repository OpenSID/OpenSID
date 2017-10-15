<?php class Penduduk_Model extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	function autocomplete(){
		$sql = "SELECT nama FROM tweb_penduduk";
		$query = $this->db->query($sql);
		$data = $query->result_array();
		
		$i=0;
		$outp='';
		while($i<count($data)){
			$outp .= ',"'.$data[$i]['nama'].'"';
			$i++;
		}
		$outp = substr($outp, 1);
		$outp = '[' .$outp. ']';
		if(count($data)<=1000)
			return $outp;
		else
			return null;
	}
	function duplikasi(){
		$sql = "SELECT no_kk FROM tweb_keluarga WHERE 1 ";
		$query = $this->db->query($sql);
		$data = $query->result_array();
		
		$i=0;
		$outp='';
		while($i<count($data)){
			$outp .= ',"'.$data[$i]['no_kk'].'"';
			$i++;
		}
		$outp = strtolower(substr($outp, 1));
		$outp = '[' .$outp. ']';
		
		return $outp;
	}
	function dp(){
		$sql = "SELECT no_kk FROM tweb_keluarga WHERE 1 ";
		$query = $this->db->query($sql);
		$data = $query->result_array();
		
		return $data;
	}
	function dn(){
		$sql = "SELECT nik FROM tweb_penduduk WHERE 1 ";
		$query = $this->db->query($sql);
		$data = $query->result_array();
		
		return $data;
	}
	function search_sql(){
		if(isset($_SESSION['cari'])){
		$cari = $_SESSION['cari'];
			$kw = penetration($this->db->escape_like_str($cari));
			$kw = '%' .$kw. '%';
			$search_sql= " AND (u.nama LIKE '$kw' OR u.nik LIKE '$kw' OR d.no_kk LIKE '$kw')";
			return $search_sql;
			}
	}
	function sex_sql(){		
		if(isset($_SESSION['sex'])){
			$kf = $_SESSION['sex'];
			$sex_sql= " AND u.sex = $kf";
		return $sex_sql;
		}
	}
	function dusun_sql(){		
		if(isset($_SESSION['dusun'])){
			$kf = $_SESSION['dusun'];
			$dusun_sql= " AND a.dusun = '$kf'";
		return $dusun_sql;
		}
	}
	function rw_sql(){		
		if(isset($_SESSION['rw'])){
			$kf = $_SESSION['rw'];
			$rw_sql= " AND a.rw = '$kf'";
		return $rw_sql;
		}
	}
	function rt_sql(){		
		if(isset($_SESSION['rt'])){
			$kf = $_SESSION['rt'];
			$rt_sql= " AND a.rt = '$kf'";
		return $rt_sql;
		}
	}
	function agama_sql(){		
		if(isset($_SESSION['agama'])){
			$kf = $_SESSION['agama'];
			$agama_sql= " AND u.agama_id = $kf";
		return $agama_sql;
		}
	}
	function warganegara_sql(){		
		if(isset($_SESSION['warganegara'])){
			$kf = $_SESSION['warganegara'];
			$warganegara_sql= " AND u.warganegara_id = $kf";
		return $warganegara_sql;
		}
	}
	function golongan_darah_sql(){		
		if(isset($_SESSION['golongan_darah'])){
			$kf = $_SESSION['golongan_darah'];
			$golongan_darah_sql= " AND u.golongan_darah_id = $kf";
		return $golongan_darah_sql;
		}
	}
	function pekerjaan_sql(){		
		if(isset($_SESSION['pekerjaan_id'])){
			$kf = $_SESSION['pekerjaan_id'];
			$pekerjaan_sql= " AND u.pekerjaan_id = $kf";
		return $pekerjaan_sql;
		}
	}
	function cacat_sql(){		
		if(isset($_SESSION['cacat'])){
			$kf = $_SESSION['cacat'];
			$cacat_sql= " AND u.cacat_id = $kf";
		return $cacat_sql;
		}
	}
	function hubungan_sql(){		
		if(isset($_SESSION['hubungan'])){
			$kf = $_SESSION['hubungan'];
			$cacat_sql= " AND u.kk_level = $kf";
		return $cacat_sql;
		}
	}
	function cacatx_sql(){		
		if(isset($_SESSION['cacatx'])){
			$kf = $_SESSION['cacatx'];
			$cacatx_sql= " AND u.cacat_id <> $kf AND u.cacat_id is not null and u.cacat_id<>''";
		return $cacatx_sql;
		}
	}
	function menahun_sql(){		
		if(isset($_SESSION['menahun'])){
			$kf = $_SESSION['menahun'];
			$menahun_sql= " AND u.sakit_menahun_id = $kf";
		return $menahun_sql;
		}
	}
	function menahunx_sql(){		
		if(isset($_SESSION['menahunx'])){
			$kf = $_SESSION['menahunx'];
			$menahunx_sql= " AND u.sakit_menahun_id <> $kf and u.sakit_menahun_id is not null and u.sakit_menahun_id<>'0' ";
		return $menahunx_sql;
		}
	}
	function statuskawin_sql(){		
		if(isset($_SESSION['status'])){
			$kf = $_SESSION['status'];
			$statuskawin_sql= " AND u.status_kawin = $kf";
		return $statuskawin_sql;
		}
	}
	function pendidikan_kk_sql(){		
		if(isset($_SESSION['pendidikan_kk_id'])){
			$kf = $_SESSION['pendidikan_kk_id'];
			$pendidikan_kk_sql= " AND u.pendidikan_kk_id = $kf";
		return $pendidikan_kk_sql;
		}
	}
	function hamil_sql(){		
		if(isset($_SESSION['hamil'])){
			$kf = $_SESSION['hamil'];
			$hamil_sql= " AND u.hamil = $kf";
		return $hamil_sql;
		}
	}
	function pendidikan_sedang_sql(){		
		if(isset($_SESSION['pendidikan_sedang_id'])){
			$kf = $_SESSION['pendidikan_sedang_id'];
			$pendidikan_sedang_sql= " AND u.pendidikan_sedang_id = $kf";
		return $pendidikan_sedang_sql;
		}
	}
	function status_penduduk_sql(){		
		if(isset($_SESSION['status_penduduk'])){
			$kf = $_SESSION['status_penduduk'];
			$status_penduduk_sql= " AND u.status = $kf";
		return $status_penduduk_sql;
		}
	}
	function umur_max_sql(){		
		if(isset($_SESSION['umur_max'])){
			$kf = $_SESSION['umur_max'];
			$umur_max_sql= " AND (SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(tanggallahir)), '%Y')+0 FROM tweb_penduduk WHERE id = u.id) <= $kf ";
		return $umur_max_sql;
		}
	}
	function umur_min_sql(){		
		if(isset($_SESSION['umur_min'])){
			$kf = $_SESSION['umur_min'];
			$umur_min_sql= " AND (SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(tanggallahir)), '%Y')+0 FROM tweb_penduduk WHERE id = u.id) >= $kf ";
		return $umur_min_sql;
		}
	}
	function umur_sql(){		
		if(isset($_SESSION['umurx'])){
			$kf = $_SESSION['umurx'];
			$umur_sql= " AND (SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(tanggallahir)), '%Y')+0 FROM tweb_penduduk WHERE id = u.id) >= (SELECT dari FROM tweb_penduduk_umur WHERE id=$kf ) AND (SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(tanggallahir)), '%Y')+0 FROM tweb_penduduk WHERE id = u.id) <= (SELECT sampai FROM tweb_penduduk_umur WHERE id=$kf ) ";
		return $umur_sql;
		}
	}
	function filter_sql(){		
		if(isset($_SESSION['filter'])){
			$kf = $_SESSION['filter'];
			if($kf == '77'){
				$filter_sql= " AND (u.status = 1 OR u.status = 2) ";
			}else
			$filter_sql= " AND u.status = $kf";
		return $filter_sql;
		}
	}
        function duplikat_sql(){		
		if(isset($_SESSION['duplikat'])){
			$duplikat_sql= " AND u.id NOT IN (SELECT id FROM tweb_penduduk GROUP BY nik, nama HAVING COUNT(*) = 1) ";
		return $duplikat_sql;
		}
	}
	function status_dasar_sql(){		
		if(isset($_SESSION['status_dasar'])){
			$kf = $_SESSION['status_dasar'];
				$status_dasar= " AND u.status_dasar = $kf";
		return $status_dasar;
		}
	}
	function log_sql(){		
		if(isset($_SESSION['log'])){
			$log_sql= " AND u.id > 1 AND u.id IN (SELECT id_pend FROM log_penduduk)";
		return $log_sql;
		}else{
			$log_sql = "";
			//$log_sql= " AND u.status_dasar = 1 ";
		return $log_sql;
		}
	}
	function paging($p=1,$o=0,$log=0){
		$sql = "SELECT COUNT(u.id) AS id FROM tweb_penduduk u LEFT JOIN tweb_wil_clusterdesa a ON u.id_cluster = a.id LEFT JOIN tweb_keluarga d ON u.id_kk = d.id LEFT JOIN tweb_penduduk_pendidikan_kk n ON u.pendidikan_kk_id = n.id LEFT JOIN tweb_penduduk_pekerjaan p ON u.pekerjaan_id = p.id LEFT JOIN tweb_penduduk_kawin k ON u.status_kawin = k.id LEFT JOIN tweb_penduduk_sex x ON u.pendidikan_id = x.id LEFT JOIN tweb_penduduk_agama g ON u.agama_id = g.id LEFT JOIN tweb_penduduk_warganegara v ON u.warganegara_id = v.id LEFT JOIN tweb_golongan_darah m ON u.golongan_darah_id = m.id LEFT JOIN tweb_cacat f ON u.cacat_id = f.id LEFT JOIN tweb_sakit_menahun j ON u.sakit_menahun_id = j.id WHERe 1 ";
		$sql .= $this->search_sql();
		$sql .= $this->filter_sql();
                $sql .= $this->duplikat_sql();
		$sql .= $this->status_dasar_sql();
		$sql .= $this->sex_sql();
		$sql .= $this->dusun_sql();
		$sql .= $this->rw_sql();
		$sql .= $this->rt_sql();
		$sql .= $this->hubungan_sql();
		$sql .= $this->agama_sql();
		$sql .= $this->cacat_sql();
		$sql .= $this->cacatx_sql();
		$sql .= $this->menahun_sql();
		$sql .= $this->menahunx_sql();
		$sql .= $this->golongan_darah_sql();
		$sql .= $this->warganegara_sql();
		$sql .= $this->umur_min_sql();
		$sql .= $this->umur_max_sql(); 
		$sql .= $this->pekerjaan_sql();
		$sql .= $this->statuskawin_sql();
		$sql .= $this->pendidikan_kk_sql();
		$sql .= $this->pendidikan_sedang_sql();
		$sql .= $this->status_penduduk_sql();
		$sql .= $this->hamil_sql();
		$sql .= $this->umur_sql();
		$sql .= $this->log_sql();
		$query = $this->db->query($sql);
		$row = $query->row_array();
		$jml_data = $row['id'];
		
		$this->load->library('paging');
		$cfg['page'] = $p;
		$cfg['per_page'] = $_SESSION['per_page'];
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);
		
		return $this->paging;
	}
	function list_data($o=0,$offset=0,$limit=500,$log=0){
		
		switch($o){
			case 1: $order_sql = ' ORDER BY u.nik'; break;
			case 2: $order_sql = ' ORDER BY u.nik DESC'; break;
			case 3: $order_sql = ' ORDER BY u.nama'; break;
			case 4: $order_sql = ' ORDER BY u.nama DESC'; break;
			case 5: $order_sql = ' ORDER BY d.no_kk'; break;
			case 6: $order_sql = ' ORDER BY d.no_kk DESC'; break;
			case 7: $order_sql = ' ORDER BY umur'; break;
			case 8: $order_sql = ' ORDER BY umur DESC'; break;
			default:$order_sql = '';
		}
		
		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;
		
		if($log==1)
			$sql = "SELECT u.id,u.nik,u.tanggallahir,u.tempatlahir,u.status,u.status_dasar,u.id_kk,u.nama,u.nama_ayah,u.nama_ibu,a.dusun,a.rw,a.rt,d.no_kk AS no_kk,(SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(tanggallahir)), '%Y')+0 FROM tweb_penduduk WHERE id = u.id) AS umur,x.nama AS sex,sd.nama AS pendidikan_sedang,n.nama AS pendidikan,p.nama AS pekerjaan,k.nama AS kawin,g.nama AS agama,m.nama AS gol_darah,hub.nama AS hubungan,log.tgl_peristiwa,log.id_detail AS status_dasar_log FROM tweb_penduduk u LEFT JOIN tweb_wil_clusterdesa a ON u.id_cluster = a.id LEFT JOIN tweb_keluarga d ON u.id_kk = d.id LEFT JOIN tweb_penduduk_pendidikan_kk n ON u.pendidikan_kk_id = n.id LEFT JOIN tweb_penduduk_pendidikan sd ON u.pendidikan_sedang_id = sd.id LEFT JOIN tweb_penduduk_pekerjaan p ON u.pekerjaan_id = p.id LEFT JOIN tweb_penduduk_kawin k ON u.status_kawin = k.id LEFT JOIN tweb_penduduk_sex x ON u.sex = x.id LEFT JOIN tweb_penduduk_agama g ON u.agama_id = g.id LEFT JOIN tweb_penduduk_warganegara v ON u.warganegara_id = v.id LEFT JOIN tweb_golongan_darah m ON u.golongan_darah_id = m.id LEFT JOIN tweb_cacat f ON u.cacat_id = f.id LEFT JOIN tweb_penduduk_hubungan hub ON u.kk_level = hub.id LEFT JOIN tweb_sakit_menahun j ON u.sakit_menahun_id = j.id LEFT JOIN log_penduduk log ON u.id = log.id_pend WHERE 1 ";
		else
			$sql = "SELECT u.id,u.nik,u.tanggallahir,u.tempatlahir,u.status,u.status_dasar,u.id_kk,u.nama,u.nama_ayah,u.nama_ibu,a.dusun,a.rw,a.rt,d.no_kk AS no_kk,(SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(tanggallahir)), '%Y')+0 FROM tweb_penduduk WHERE id = u.id) AS umur,x.nama AS sex,sd.nama AS pendidikan_sedang,n.nama AS pendidikan,p.nama AS pekerjaan,k.nama AS kawin,g.nama AS agama,m.nama AS gol_darah,hub.nama AS hubungan,b.no_kk AS no_rtm,b.id AS id_rtm 
			FROM tweb_penduduk u LEFT JOIN tweb_wil_clusterdesa a ON u.id_cluster = a.id LEFT JOIN tweb_keluarga d ON u.id_kk = d.id LEFT JOIN tweb_rtm b ON u.id_rtm = b.id LEFT JOIN tweb_penduduk_pendidikan_kk n ON u.pendidikan_kk_id = n.id LEFT JOIN tweb_penduduk_pendidikan sd ON u.pendidikan_sedang_id = sd.id LEFT JOIN tweb_penduduk_pekerjaan p ON u.pekerjaan_id = p.id LEFT JOIN tweb_penduduk_kawin k ON u.status_kawin = k.id LEFT JOIN tweb_penduduk_sex x ON u.sex = x.id LEFT JOIN tweb_penduduk_agama g ON u.agama_id = g.id LEFT JOIN tweb_penduduk_warganegara v ON u.warganegara_id = v.id LEFT JOIN tweb_golongan_darah m ON u.golongan_darah_id = m.id LEFT JOIN tweb_cacat f ON u.cacat_id = f.id LEFT JOIN tweb_penduduk_hubungan hub ON u.kk_level = hub.id LEFT JOIN tweb_sakit_menahun j ON u.sakit_menahun_id = j.id WHERE 1 ";
			
		$sql .= $this->search_sql();
		$sql .= $this->filter_sql();
                $sql .= $this->duplikat_sql();
		$sql .= $this->status_dasar_sql();
		$sql .= $this->sex_sql();
		$sql .= $this->dusun_sql();
		$sql .= $this->rw_sql();
		$sql .= $this->rt_sql();
		$sql .= $this->hubungan_sql();
		$sql .= $this->agama_sql();
		$sql .= $this->cacat_sql();
		$sql .= $this->cacatx_sql();
		$sql .= $this->menahun_sql();
		$sql .= $this->menahunx_sql();
		$sql .= $this->warganegara_sql();
		$sql .= $this->golongan_darah_sql();
		$sql .= $this->umur_min_sql();
		$sql .= $this->umur_max_sql();
		$sql .= $this->pekerjaan_sql();
		$sql .= $this->statuskawin_sql();
		$sql .= $this->pendidikan_sedang_sql();
		$sql .= $this->pendidikan_kk_sql();
		$sql .= $this->umur_sql();
		$sql .= $this->status_penduduk_sql();
		$sql .= $this->log_sql();
		$sql .= $this->hamil_sql();
		$sql .= $order_sql;
		$sql .= $paging_sql;
		
		$query = $this->db->query($sql);
		$data=$query->result_array();
		
		
		$i=0;
		$j=$offset;
		while($i<count($data)){
		
			$data[$i]['no']=$j+1;
			$data[$i]['alamat']='';
			
			if($data[$i]['rt'] != "-")
				$data[$i]['alamat']="RT-".$data[$i]['rt'];
			
			if($data[$i]['rw'] != "-")
				$data[$i]['alamat']=$data[$i]['alamat']." RW-".$data[$i]['rw'];
			
			if($data[$i]['dusun'] != "-")
				$data[$i]['alamat']=$data[$i]['alamat']." Dusun ".$data[$i]['dusun'];
			else
				$data[$i]['alamat']="Alamat penduduk belum valid";
			
			$i++;
			$j++;
		}
		return $data;
	}
	function list_data_map(){
		
		$sql = "SELECT u.id,u.nik,u.nama,map.lat,map.lng,a.dusun,a.rw,a.rt,d.no_kk AS no_kk,(SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(tanggallahir)), '%Y')+0 FROM tweb_penduduk WHERE id = u.id) AS umur,x.nama AS sex,sd.nama AS pendidikan_sedang,n.nama AS pendidikan,p.nama AS pekerjaan,k.nama AS kawin,g.nama AS agama,m.nama AS gol_darah,hub.nama AS hubungan FROM tweb_penduduk u LEFT JOIN tweb_wil_clusterdesa a ON u.id_cluster = a.id LEFT JOIN tweb_keluarga d ON u.id_kk = d.id LEFT JOIN tweb_penduduk_pendidikan_kk n ON u.pendidikan_kk_id = n.id LEFT JOIN tweb_penduduk_pendidikan sd ON u.pendidikan_sedang_id = sd.id LEFT JOIN tweb_penduduk_pekerjaan p ON u.pekerjaan_id = p.id LEFT JOIN tweb_penduduk_kawin k ON u.status_kawin = k.id LEFT JOIN tweb_penduduk_sex x ON u.sex = x.id LEFT JOIN tweb_penduduk_agama g ON u.agama_id = g.id LEFT JOIN tweb_penduduk_warganegara v ON u.warganegara_id = v.id LEFT JOIN tweb_golongan_darah m ON u.golongan_darah_id = m.id LEFT JOIN tweb_cacat f ON u.cacat_id = f.id LEFT JOIN tweb_penduduk_hubungan hub ON u.kk_level = hub.id LEFT JOIN tweb_sakit_menahun j ON u.sakit_menahun_id = j.id LEFT JOIN tweb_penduduk_map map ON u.id = map.id WHERE 1 ";
			
		$sql .= $this->search_sql();
		$sql .= $this->filter_sql();
		$sql .= $this->sex_sql();
		$sql .= $this->dusun_sql();
		$sql .= $this->rw_sql();
		$sql .= $this->rt_sql();
		$sql .= $this->agama_sql();
		$sql .= $this->cacat_sql();
		$sql .= $this->cacatx_sql();
		$sql .= $this->menahun_sql();
		$sql .= $this->menahunx_sql();
		$sql .= $this->warganegara_sql();
		$sql .= $this->golongan_darah_sql();
		$sql .= $this->umur_min_sql();
		$sql .= $this->umur_max_sql();
		$sql .= $this->pekerjaan_sql();
		$sql .= $this->statuskawin_sql();
		$sql .= $this->pendidikan_sedang_sql();
		$sql .= $this->pendidikan_kk_sql();
		$sql .= $this->umur_sql();
		$sql .= $this->status_penduduk_sql();
		$sql .= $this->hamil_sql();
		$query = $this->db->query($sql);
		$data=$query->result_array();
		
		$i=0;
		while($i<count($data)){
			$data[$i]['alamat']='';
			
			if($data[$i]['rt'] != "-")
				$data[$i]['alamat']="RT-".$data[$i]['rt'];
			
			if($data[$i]['rw'] != "-")
				$data[$i]['alamat']=$data[$i]['alamat']." RW-".$data[$i]['rw'];
			
			if($data[$i]['dusun'] != "-")
				$data[$i]['alamat']=$data[$i]['alamat']." Dusun ".$data[$i]['dusun'];
			else
				$data[$i]['alamat']="Alamat penduduk belum valid";
			
			$i++;
		}
		return $data;
	}
	function insert(){
		$data = $_POST;
		$lokasi_file = $_FILES['foto']['tmp_name'];
		$tipe_file = $_FILES['foto']['type'];
		$nama_file = $_FILES['foto']['name'];
		$old_foto = $data['old_foto'];
		if (!empty($lokasi_file)){
			if ($tipe_file != "image/jpeg" AND $tipe_file != "image/jpg" AND $tipe_file != "image/png"){
				unset($data['foto']);
			} else {
				UploadFoto($nama_file,$old_foto);
				$data['foto'] = $nama_file;
			}
		}else{
			unset($data['foto']);
		}
		
		unset($data['file_foto']);
		unset($data['old_foto']);
		
		$data['id_cluster'] = $data['rt'];
		UNSET($data['dusun']);
		UNSET($data['rw']);
		UNSET($data['rt']);
		
		$data['tanggallahir'] = tgl_indo_in($data['tanggallahir']);
		$data['tanggalperkawinan'] = tgl_indo_in($data['tanggalperkawinan']);
		$data['tanggalperceraian'] = tgl_indo_in($data['tanggalperceraian']);
		$data['nama'] = penetration($data['nama']);
		$data['nama_ayah'] = penetration($data['nama_ayah']);
		$data['nama_ibu'] = penetration($data['nama_ibu']);
		$outp = $this->db->insert('tweb_penduduk',$data);
		
		$sql="SELECT MAX(id) as id FROM tweb_penduduk";
		$query = $this->db->query($sql);
		$data = $query->row_array();
		$idku = $data['id'];
		
		$satuan=$_POST['tanggallahir'];
		$blnlahir = substr($satuan,3,2);
		$thnlahir= substr($satuan,6,4);
		$blnskrg = (date("m"));
		$thnskrg = (date("Y"));
		if($_POST['status']=='3'){
			$log['id_detail']="8";
			}else{
			if(($blnlahir==$blnskrg)and($thnlahir==$thnskrg)){
				$log['id_detail']='1';
			}else{
				$log['id_detail']='5';
			}
		}
		$log['id_pend'] = $idku;
		
		$log['bulan'] = date("m");
		$log['tahun'] = date("Y");
		$log['tgl_peristiwa'] = date("d-m-Y");
		$outp = $this->db->insert('log_penduduk',$log);
		
		$log1['id_pend'] = $idku;
		$log1['id_cluster'] = 1;
		$log1['tanggal'] = date("m-d-y");
		$outp = $this->db->insert('log_perubahan_penduduk',$log1);
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	function update($id=0){
		$data = $_POST;
		
		$sql = "SELECT id_kk FROM tweb_penduduk WHERE id=?";
		$query = $this->db->query($sql,$id);
		$pend = $query->row_array();
		
		if($data['kk_level']==1){
			$lvl['kk_level'] = 11;
			$this->db->where('id_kk',$pend['id_kk']);
			$this->db->where('kk_level',1);
			$this->db->update('tweb_penduduk',$lvl);
			
			$nik['nik_kepala'] = $id;
			$this->db->where('id',$pend['id_kk']);
			$outp = $this->db->update('tweb_keluarga',$nik);
		}
		
		$lokasi_file = $_FILES['foto']['tmp_name'];
		$tipe_file = $_FILES['foto']['type'];
		$nama_file = $_FILES['foto']['name'];
		$old_foto = $data['old_foto'];
		if (!empty($lokasi_file)){
			if ($tipe_file != "image/jpeg" AND $tipe_file != "image/pjpeg" AND $tipe_file != "image/png"){
				unset($data['foto']);
			} else {
				UploadFoto($nama_file,$old_foto);
				$data['foto'] = $nama_file;
			}
		}else{
			unset($data['foto']);
		}
		
		unset($data['file_foto']);
		unset($data['old_foto']);
		
		$data['nama'] = $data['nama'];
		$data['nama_ayah'] = $data['nama_ayah'];
		$data['nama_ibu'] = $data['nama_ibu'];
		
		$data['tanggallahir'] = tgl_indo_in($data['tanggallahir']);
		$data['tanggalperkawinan'] = tgl_indo_in($data['tanggalperkawinan']);
		$data['tanggalperceraian'] = tgl_indo_in($data['tanggalperceraian']);
		
		$this->db->where('id',$id);
		$outp = $this->db->update('tweb_penduduk',$data);
		
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	function update_position($id=0){
		$sql = "SELECT id FROM tweb_penduduk_map WHERE id=?";
		$query = $this->db->query($sql,$id);
		$cek = $query->row_array();
		
		$data = $_POST;
		unset($data['zoom']);
		unset($data['map_tipe']);
		if($cek['id']==$id){
			if($data['lat']){
				$this->db->where('id',$id);
				$outp = $this->db->update('tweb_penduduk_map',$data);
			}
		}else{
			if($data['lat']){
				$data['id'] = $id;
				$outp = $this->db->insert('tweb_penduduk_map',$data);
			}
		}
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	function get_penduduk_map($id=0){
		$sql = "SELECT m.*,p.nama FROM tweb_penduduk_map m LEFT JOIN tweb_penduduk p ON m.id = p.id WHERE m.id = ? ";
		$query = $this->db->query($sql,$id);
		return $query->row_array();
	}
	function update_status_dasar($id=0){
		$data['status_dasar'] = $_POST['status_dasar'];
		$this->db->where('id',$id);
		$this->db->update('tweb_penduduk',$data);
		
		//pindah luar desa
		if($data['status_dasar'] == 3){
			$out['id_kk'] = '';
			$out['kk_level'] = '';
			$this->db->where('id',$id);
			$this->db->update('tweb_penduduk',$out);
		}
		
		$log['id_pend'] = $id;
		$log['tgl_peristiwa'] = rev_tgl($_POST['tgl_peristiwa']);
		$log['id_detail'] = $data['status_dasar'];
		$log['bulan'] = date("m");
		$log['tahun'] = date("Y");
		$outp = $this->db->insert('log_penduduk',$log);
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	function delete($id=''){
		$sql = "DELETE FROM tweb_penduduk WHERE id=?";
		$outp = $this->db->query($sql,array($id));
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	function delete_all(){
		$id_cb = $_POST['id_cb'];
		
		if(count($id_cb)){
			foreach($id_cb as $id){
				$sql = "DELETE FROM tweb_penduduk WHERE id=?";
				$outp = $this->db->query($sql,array($id));
			}
		}
		else $outp = false;
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	function adv_search_proses(){
		UNSET($_POST['umur1']);
		UNSET($_POST['umur2']);
		
		UNSET($_POST['dusun']);
		UNSET($_POST['rt']);
		UNSET($_POST['rw']);
		$i=0;
		while($i++ < count($_POST)){
			$col[$i] = key($_POST);
				next($_POST);
		}
		$i=0;
		while($i++ < count($col)){
			if($_POST[$col[$i]]=="")
				UNSET($_POST[$col[$i]]);
		}
		
		$data=$_POST;
		print_r($data);
		$this->db->where($data);
		return $this->db->get('tweb_penduduk');
	}
	 
	function get_penduduk($id=0){
		$sql = "SELECT u.sex as id_sex,u.*,a.dusun,a.rw,a.rt,t.nama AS status,o.nama AS pendidikan_sedang,
		b.nama AS pendidikan_kk,d.no_kk AS no_kk,
		(
			SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(tanggallahir)), '%Y')+0 FROM tweb_penduduk WHERE id = u.id
		)
		 AS umur,x.nama AS sex,w.nama AS warganegara,n.nama AS pendidikan,p.nama AS pekerjaan,k.nama AS kawin,g.nama AS agama,ct.nama AS cacat 
		 FROM tweb_penduduk u 
			LEFT JOIN tweb_wil_clusterdesa a ON u.id_cluster = a.id 
			LEFT JOIN tweb_keluarga d ON u.id_kk = d.id 
			LEFT JOIN tweb_penduduk_pendidikan n ON u.pendidikan_id = n.id 
			LEFT JOIN tweb_penduduk_pendidikan o ON u.pendidikan_sedang_id = o.id 
			LEFT JOIN tweb_penduduk_pendidikan_kk b ON u.pendidikan_kk_id = b.id 
			LEFT JOIN tweb_penduduk_warganegara w ON u.warganegara_id = w.id 
			LEFT JOIN tweb_penduduk_status t ON u.status = t.id 
			LEFT JOIN tweb_penduduk_pekerjaan p ON u.pekerjaan_id = p.id 
			LEFT JOIN tweb_penduduk_kawin k ON u.status_kawin = k.id 
			LEFT JOIN tweb_penduduk_sex x ON u.sex = x.id 
			LEFT JOIN tweb_cacat ct ON u.cacat_id = ct.id 
			LEFT JOIN tweb_penduduk_agama g ON u.agama_id = g.id WHERE u.id=?";
		$query = $this->db->query($sql,$id);
		$data = $query->row_array();
		$data['tanggallahir'] = tgl_indo_out($data['tanggallahir']);
		$data['tanggalperkawinan'] = tgl_indo_out($data['tanggalperkawinan']);
		$data['tanggalperceraian'] = tgl_indo_out($data['tanggalperceraian']);
		return $data;
	}
		
	function list_wil(){
		$sql = "SELECT * FROM tweb_wil_clusterdesa WHERE zoom > '0'";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}
		
	function list_dusun(){
		$sql = "SELECT * FROM tweb_wil_clusterdesa WHERE rt = '0' AND rw = '0' ";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}
		
	function list_rw($dusun=''){
		$sql = "SELECT * FROM tweb_wil_clusterdesa WHERE rt = '0' AND dusun = ? AND rw <> '0'";
		$query = $this->db->query($sql,$dusun);
		$data=$query->result_array();
		return $data;
	}
			
	function list_rt($dusun='',$rw=''){
		$sql = "SELECT * FROM tweb_wil_clusterdesa WHERE rw = ? AND dusun = ? AND rt <> '0'";
		$query = $this->db->query($sql,array($rw,$dusun));
		$data=$query->result_array();
		return $data;
	}
		
	function list_rw_all(){
		$sql = "SELECT * FROM tweb_wil_clusterdesa WHERE rt = '0' AND rw <> '0'";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}
			
	function list_rt_all(){
		$sql = "SELECT * FROM tweb_wil_clusterdesa WHERE rt <> '0' AND rw <> '-'";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}
	function list_agama(){
		$sql = "SELECT * FROM tweb_penduduk_agama WHERE 1";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}
	function list_hubungan(){
		$sql = "SELECT * FROM tweb_penduduk_hubungan WHERE 1";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}
		
	function list_pendidikan(){
		$sql = "SELECT * FROM tweb_penduduk_pendidikan WHERE 1";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}
	function list_pendidikan_telah(){
		$sql = "SELECT * FROM tweb_penduduk_pendidikan WHERE left(nama,6)<> 'SEDANG' ";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}
	function list_pendidikan_sedang(){
		$sql = "SELECT * FROM tweb_penduduk_pendidikan WHERE left(nama,5)<> 'TAMAT' ";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}
	function list_pendidikan_kk(){
		$sql = "SELECT * FROM tweb_penduduk_pendidikan_kk WHERE 1";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}
	function list_pekerjaan(){
		$sql = "SELECT * FROM tweb_penduduk_pekerjaan WHERE 1";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}
	function list_warganegara(){
		$sql = "SELECT * FROM tweb_penduduk_warganegara WHERE 1";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}
	function list_status_kawin(){
		$sql = "SELECT * FROM tweb_penduduk_kawin WHERE 1";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}
	function list_golongan_darah(){
		$sql = "SELECT * FROM tweb_golongan_darah WHERE 1";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}
	function list_cacat(){
		$sql = "SELECT * FROM tweb_cacat WHERE 1";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}
		
	function list_sakit_menahun(){
		$sql = "SELECT * FROM tweb_sakit_menahun WHERE 1";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}
		
	function get_desa(){
		$sql = "SELECT * FROM config WHERE 1";
		$query = $this->db->query($sql);
		return $query->row_array();
	}
	function pindah_proses($id=0,$id_cluster=''){
		$this->db->where('id',$id);
		$data['id_cluster'] = $id_cluster;
		$outp = $this->db->update('tweb_penduduk',$data);
			
		$log['id_pend'] = $id;
		$log['id_detail'] = "6";
		$log['bulan'] = date("m");
		$log['tahun'] = date("Y");
		$outp = $this->db->insert('log_penduduk',$log);
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	
	/*
	
		unset($_SESSION['filter']);
		unset($_SESSION['sex']);
		unset($_SESSION['warganegara']);
		unset($_SESSION['cacat']);
		unset($_SESSION['menahun']);		
		unset($_SESSION['golongan_darah']);
		unset($_SESSION['dusun']);
		unset($_SESSION['rw']);
		unset($_SESSION['rt']);
		unset($_SESSION['hubungan']);
		unset($_SESSION['agama']);
		unset($_SESSION['umur_min']);
		unset($_SESSION['umur_max']);
		unset($_SESSION['pekerjaan_id']);
		unset($_SESSION['pendidikan_sedang_id']);
		unset($_SESSION['pendidikan_kk_id']);
		unset($_SESSION['status_penduduk']);
		unset($_SESSION['hamil']);
	*/
	
	function list_filter($id=0){	
		if($id != 0){
			$kf = "";
			$head = "";
			switch($id){
				case 1: $table = 'tweb_penduduk_hubungan'; 			if(isset($_SESSION['hubungan'])){$head = "STATUS HUBUNGAN DALAM KELUARGA";$kf = $_SESSION['hubungan'];} break;
				case 2: $table = 'tweb_penduduk_agama'; 			if(isset($_SESSION['agama'])){$head = "AGAMA";$kf = $_SESSION['agama'];} break;
				case 3: $table = 'tweb_penduduk_pendidikan_kk'; 	if(isset($_SESSION['pendidikan_kk_id'])){$head = "PENDIDIKAN DALAM KK";$kf = $_SESSION['pendidikan_kk_id'];} break;
				case 4: $table = 'tweb_penduduk_pendidikan'; if(isset($_SESSION['pendidikan_sedang_id'])){$head = "PENDIDIKAN SEDANG DITEMPUH";$kf = $_SESSION['pendidikan_sedang_id'];} break;
				case 5: $table = 'tweb_penduduk_pekerjaan'; 		if(isset($_SESSION['pekerjaan_id'])){$head = "PEKERJAAN";$kf = $_SESSION['pekerjaan_id'];} break;
				case 6: $table = 'tweb_penduduk_kawin'; 			if(isset($_SESSION['status'])){$head = "STATUS PERKAWINAN";$kf = $_SESSION['status'];} break;
				case 7: $table = 'tweb_penduduk_warganegara'; 		if(isset($_SESSION['warganegara'])){$head = "KEWARGANEGARAAN";$kf = $_SESSION['warganegara'];} break;
				case 8: $table = 'tweb_golongan_darah'; 			if(isset($_SESSION['golongan_darah'])){$head = "GOLONGAN DARAH";$kf = $_SESSION['golongan_darah'];} break;
				case 9: $table = 'tweb_penduduk_sex'; 			if(isset($_SESSION['sex'])){$head = "JENIS KELAMIN";$kf = $_SESSION['sex'];} break;
				case 10: $table = 'tweb_penduduk_status'; 			if(isset($_SESSION['filter'])){$head = "STATUS PENDUDUK";$kf = $_SESSION['filter'];} break;
				case 11: /*$table = 'tweb_status_dasar'; 			if(isset($_SESSION['status_dasar'])){$head = "STATUS DASAR";$kf = $_SESSION['status_dasar'];}*/ break;
				case 12: $table = 'tweb_cacat'; 			if(isset($_SESSION['cacat'])){$head = "DIFABLE";$kf = $_SESSION['cacat'];} break;
				default: $table = "";
			}
			
			if($kf != ""){
				$sql   = "SELECT nama FROM $table WHERE id IN ($kf)";
				$query = $this->db->query($sql);
				$data = $query->result_array();
				if(count($data) > 0){
					
					$br = " ";
					$rn = "\r\n";
					//$out = "| ".$head.":";
					$out = " _ ";
					
					$i=0;
					while($i<count($data)){
						$out .= $data[$i]['nama'].$br;
						$i++;
					}
					return $out;
				}
			}
		}
	}
	function get_filter(){
		$data = "";
		
		if(isset($_SESSION['dusun'])){
			$kf = $_SESSION['dusun'];
			$data .= " _ DUSUN : $kf";
		}
		if(isset($_SESSION['rw'])){
			$kf = $_SESSION['rw'];
			$data .= " _ RW $kf";
		}
			
		if(isset($_SESSION['rt'])){
			$kf = $_SESSION['rt'];
			$data .= " _ RT $kf";
		}
		if(isset($_SESSION['umur_min'])){
			$kf = $_SESSION['umur_min'];
			$data .= " _ usia minimal $kf Tahun";
		}
		
		if(isset($_SESSION['umur_max'])){
			$kf = $_SESSION['umur_max'];
			$data .= " _ usia maksimal $kf Tahun";
		}
		
		$i=1;
		while($i<13){
			$data .= $this->list_filter($i);
			$i++;
		}
		if($data!=""){
			$data[0] = "";
			$data[1] = "";
		}
		return $data;
	}
	
	function get_judul_statistik($tipe=0,$nomor=1){
		switch($tipe){
			case 0: $sql = "SELECT * FROM tweb_penduduk_pendidikan WHERE id=?";break;
			case 1: $sql = "SELECT * FROM tweb_penduduk_pekerjaan WHERE id=?";break;
			case 2: $sql = "SELECT * FROM tweb_penduduk_kawin WHERE id=?";break;
			case 3: $sql = "SELECT * FROM tweb_penduduk_agama WHERE id=?";break;
			case 4: $sql = "SELECT * FROM tweb_penduduk_sex WHERE id=?";break;
			case 5: $sql = "SELECT * FROM tweb_penduduk_warganegara WHERE id=?";break;
			case 6: $sql = "SELECT * FROM tweb_penduduk_status WHERE id=?";break;
			case 7: $sql = "SELECT * FROM tweb_golongan_darah WHERE id=?";break;
			case 9: $sql = "SELECT * FROM tweb_cacat WHERE id=?";break;
			case 10: $sql = "SELECT * FROM tweb_sakit_menahun WHERE id=?";break;
			case 11: $sql = "SELECT * FROM ref_jamkesmas WHERE id=?";break;
			case 12: $sql = "SELECT * FROM tweb_penduduk_pendidikan_kk WHERE id=?";break;
			case 13: $sql = "SELECT * FROM tweb_penduduk_umur WHERE id=?";break;			
			case 14: $sql = "SELECT * FROM tweb_penduduk_pendidikan WHERE id=?";break;		
		}
		$query = $this->db->query($sql,$nomor);
		return $query->row_array();
	}	
	function get_cluster($id_cluster=0){
		
		$sql = "SELECT * FROM tweb_wil_clusterdesa WHERE id=$id_cluster ";
		$query = $this->db->query($sql);
		return $query->row_array();
	}	
	function randomap(){
		$sql = "SELECT u.id,id_cluster,map.lat,map.lng FROM tweb_penduduk u LEFT JOIN tweb_penduduk_map map ON u.id = map.id WHERE 1 ";
		$query = $this->db->query($sql);
		$data=$query->result_array();
				
		$i=0;
		while($i<count($data)){
		
		$lat = "-7.5";
		$lng = "110.4";
			$id = $data[$i]['id'];
						
			$lat .= $this->generateRandomString2(1);
			$lng .= $this->generateRandomString2(1);
			
			$lat .= $this->generateRandomString(17);
			$lng .= $this->generateRandomString(17);
			
			$data2['lat'] = $lat;
			$data2['lng'] = $lng;
			$data2['id'] = $id;
			$this->db->insert('tweb_penduduk_map',$data2);
			
			$i++;
		}
	}
	function generateRandomString($length = 5) {
		$characters = '0123456789';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	function generateRandomString2($length = 1) {
		$characters = '5678';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	function coba2(){
		$mypath="surat\\undangan\\";
		$mypath_arsip="surat\\arsip\\";
		
		$path = "".str_replace("\\","/",$mypath);
		$path_arsip = "".str_replace("\\","/",$mypath_arsip);
		
		$file = $path."pemuda.rtf";
		if(is_file($file)){
			$buffer2 ="";
			
			$handle = fopen($file,'r');
			$b = stream_get_contents($handle);
			
			$c = Parse_Data($b,'\expshrtn','{\*\themedata');
			$c = "\expshrtn".$c;
			$awal = Parse_Data($b,'{','\expshrtn');
			$awal = "{".$awal;
			$akhir = strstr($b,'{\*\themedata');
			
			$data = $this->list_data();
			$i=1;
			$h = substr_count($c,"fxnama");
			$h = 4;
			$j=count($data);
			$k =1;
			$buffer=$c;
			foreach($data AS $d){
				if($d['sex']=="PEREMPUAN")
					$sex = "Sdri.";
				else
					$sex = "Sdr.";
				
				$alamat = $d['dusun'].", RT ".$d['rt']."/RW ".$d['rw'];
				$buffer=str_replace("fxnama$k","\caps $d[nama]",$buffer);
				$buffer=str_replace("fxalamat$k","\caps $alamat",$buffer);
				$buffer=str_replace("fxpre$k","\caps $sex",$buffer);
				
				if($k==$h){
					$k=0;
					
					if($i>=$j)
						$buffer2 .= $buffer;
					else
						$buffer2 .= $buffer." \page ";
					
					$buffer=$c;
				}
				
				$k++;
				$i++;
			}
			$buffer2 .= " \page ".$buffer;
			
			$buffers = $awal.$buffer2.$akhir;
			
			$berkas_arsip = $path_arsip."undangan.rtf";
			$handle = fopen($berkas_arsip,'w+');
			fwrite($handle,$buffers);
			fclose($handle);
			$_SESSION['success']=8;
			header("location:".base_url($berkas_arsip));
		}
		
	}
	
	function list_dokumen($id=""){
		$sql = "SELECT * FROM dokumen WHERE id_pend = ? ";
		$query = $this->db->query($sql,$id);
		$data=null;
		if($query)
			$data=$query->result_array();
		
		$i=0;
		while($i<count($data)){
			$data[$i]['no']=$i+1;
			$i++;
		}
		return $data;
	}
	function list_kelompok($id=""){
		$sql = "SELECT k.nama,m.kelompok AS kategori FROM kelompok_anggota a LEFT JOIN kelompok k ON a.id_kelompok = k.id LEFT JOIN kelompok_master m ON k.id_master = m.id WHERE a.id_penduduk = ? ";
		$query = $this->db->query($sql,$id);
		$data=null;
		if($query)
			$data=$query->result_array();
		
		$i=0;
		while($i<count($data)){
			$data[$i]['no']=$i+1;
			$i++;
		}
		return $data;
	}
	function dokumen_insert(){
		$lokasi_file = $_FILES['satuan']['tmp_name'];
		$nama_file = $_FILES['satuan']['name'];
		if (!empty($lokasi_file)){
				$data = $_POST;
				$nama_file = $data['id_pend']."_".$data['nama']."_".generator(6)."_".$nama_file;
				$nama_file = urlencode($nama_file);
				UploadDocument($nama_file);
				$data['satuan'] = $nama_file;
				unset($data['nik']);
				$outp = $this->db->insert('dokumen',$data);
				if($outp) $_SESSION['success']=1;
			} else {
				$_SESSION['success']=-1;
		}
	}
	function delete_dokumen($id=''){
		$sql = "DELETE FROM dokumen WHERE id=?";
		$outp = $this->db->query($sql,array($id));
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	function delete_all_dokumen(){
		$id_cb = $_POST['id_cb'];
		
		if(count($id_cb)){
			foreach($id_cb as $id){
				$sql = "DELETE FROM dokumen WHERE id=?";
				$outp = $this->db->query($sql,array($id));
			}
		}
		else $outp = false;
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	function get_dokumen($id=0){
		$sql = "SELECT * FROM dokumen WHERE id=?";
		$query = $this->db->query($sql,$id);
		$data = $query->row_array();
		return $data;
	}
}