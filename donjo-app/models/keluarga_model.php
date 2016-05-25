<?php class Keluarga_Model extends CI_Model{

	function __construct(){
		parent::__construct();
	}
	
	function autocomplete(){
		$sql   = "SELECT t.nama FROM tweb_keluarga u LEFT JOIN tweb_penduduk t ON u.nik_kepala = t.id LEFT JOIN tweb_wil_clusterdesa c ON t.id_cluster = c.id WHERE 1  ";
		$query = $this->db->query($sql);
		$data  = $query->result_array();
		
		$i=0;
		$outp='';
		while($i<count($data)){
			$outp .= ',"'.$data[$i]['nama'].'"';
			$i++;
		}
		$outp = strtolower(substr($outp, 1));
		$outp = '[' .$outp. ']';
		return $outp;
	}
	
	function dusun_sql(){		
		if(isset($_SESSION['dusun'])){
			$kf = $_SESSION['dusun'];
			$dusun_sql= " AND c.dusun = '$kf'";
		return $dusun_sql;
		}
	}
	
	function rw_sql(){		
		if(isset($_SESSION['rw'])){
			$kf = $_SESSION['rw'];
			$rw_sql= " AND c.rw = '$kf'";
		return $rw_sql;
		}
	}
	
	function rt_sql(){		
		if(isset($_SESSION['rt'])){
			$kf = $_SESSION['rt'];
			$rt_sql= " AND c.rt = '$kf'";
		return $rt_sql;
		}
	}
	
	function search_sql(){
		if(isset($_SESSION['cari'])){
		$cari = $_SESSION['cari'];
			$kw = penetration($this->db->escape_like_str($cari));
			$kw = '%' .$kw. '%';
			$search_sql= " AND t.nama LIKE '$kw'";
			return $search_sql;
			}
		}
		
	function jenis_sql(){		
		if(isset($_SESSION['jenis'])){
			$kh = $_SESSION['jenis'];
			$jenis_sql= " AND jenis = $kh";
		return $jenis_sql;
		}
	}	

	function kelas_sql(){		
		if(isset($_SESSION['kelas'])){
			$kh = $_SESSION['kelas'];
			$kelas_sql= " AND kelas_sosial= $kh";
		return $kelas_sql;
		}
	}	

	function raskin_sql(){		
		if(isset($_SESSION['raskin'])){
			$kh = $_SESSION['raskin'];
			$raskin_sql= " AND raskin= $kh";
		return $raskin_sql;
		}
	}	

	function blt_sql(){		
		if(isset($_SESSION['id_blt'])){
			$kh = $_SESSION['id_blt'];
			$blt_sql= " AND id_blt= $kh";
		return $blt_sql;
		}
	}

	function bos_sql(){		
		if(isset($_SESSION['id_bos'])){
			$kh = $_SESSION['id_bos'];
			$bos_sql= " AND id_bos= $kh";
		return $bos_sql;
		}
	}

	function pkh_sql(){		
		if(isset($_SESSION['id_pkh'])){
			$kh = $_SESSION['id_pkh'];
			$pkh_sql= " AND id_pkh= $kh";
		return $pkh_sql;
		}
	}

	function jampersal_sql(){		
		if(isset($_SESSION['id_jampersal'])){
			$kh = $_SESSION['id_jampersal'];
			$jampersal_sql= " AND id_jampersal= $kh";
		return $jampersal_sql;
		}
	}

	function bedah_rumah_sql(){		
		if(isset($_SESSION['id_bedah_rumah'])){
			$kh = $_SESSION['id_bedah_rumah'];
			$bedah_rumah_sql= " AND id_bedah_rumah= $kh";
		return $bedah_rumah_sql;
		}
	}

	function paging($p=1,$o=0){
	
		$sql      = "SELECT COUNT(u.id) AS id FROM tweb_keluarga u LEFT JOIN tweb_penduduk t ON u.nik_kepala = t.id LEFT JOIN tweb_wil_clusterdesa c ON t.id_cluster = c.id WHERE 1  ";
		$sql     .= $this->search_sql();     
		$sql     .= $this->dusun_sql();   
		$sql     .= $this->rw_sql();  
		$sql     .= $this->rt_sql();    
		$query    = $this->db->query($sql);
		$row      = $query->row_array();
		$jml_data = $row['id'];
		
		$this->load->library('paging');
		$cfg['page']     = $p;
		$cfg['per_page'] = $_SESSION['per_page'];
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);
		
		return $this->paging;
	}
	
	
	function list_data($o=0,$offset=0,$limit=500){
	
		//Ordering SQL
		switch($o){
			case 1: $order_sql = ' ORDER BY u.no_kk'; break;
			case 2: $order_sql = ' ORDER BY u.no_kk DESC'; break;
			case 3: $order_sql = ' ORDER BY kepala_kk'; break;
			case 4: $order_sql = ' ORDER BY kepala_kk DESC'; break;
			case 5: $order_sql = ' ORDER BY g.nama'; break;
			case 6: $order_sql = ' ORDER BY g.nama DESC'; break;
			default:$order_sql = ' ORDER BY u.tgl_daftar DESC';
		}
	
		//Paging SQL
		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;
		
		$sql   = "SELECT u.*,t.nama AS kepala_kk,(SELECT COUNT(id) FROM tweb_penduduk WHERE id_kk = u.id ) AS jumlah_anggota,c.dusun,c.rw,c.rt FROM tweb_keluarga u LEFT JOIN tweb_penduduk t ON u.nik_kepala = t.id LEFT JOIN tweb_wil_clusterdesa c ON t.id_cluster = c.id WHERE 1 ";
			
		$sql .= $this->search_sql();
		
		$sql     .= $this->dusun_sql(); 
		$sql     .= $this->rw_sql();  
		$sql     .= $this->rt_sql(); 
		$sql .= $order_sql; 
		$sql .= $paging_sql;
		
		$query = $this->db->query($sql);
		$data=$query->result_array();
		
		//Formating Output
		$i=0;
		$j=$offset;
		while($i<count($data)){
			$data[$i]['no']=$j+1;
			if($data[$i]['jumlah_anggota']==0)
				$data[$i]['jumlah_anggota'] = "-";
			
			$i++;
			$j++;
		}
		return $data;
	}
	
	function paging_statistik($p=1,$o=0){
		if($_SESSION['kelas']){
			$sql="SELECT COUNT(u.id) AS id FROM tweb_keluarga u LEFT JOIN tweb_penduduk t ON u.nik_kepala = t.id LEFT JOIN tweb_wil_clusterdesa c ON t.id_cluster = c.id WHERE kelas_sosial = $_SESSION[kelas] ";
			$sql .= $this->search_sql();
		}else{
			$sql      = "SELECT COUNT(u.id) AS id FROM tweb_keluarga u LEFT JOIN tweb_penduduk t ON u.nik_kepala = t.id LEFT JOIN tweb_wil_clusterdesa c ON t.id_cluster = c.id WHERE 1  ";
			$sql     .= $this->search_sql();     
			////$sql     .= $this->dusun_sql();   
			///$sql     .= $this->rw_sql();  
			//sql     .= $this->rt_sql(); 
			$sql     .= $this->raskin_sql(); 
			$sql     .= $this->kelas_sql();
			$sql	.= $this->blt_sql();
			$sql	.= $this->bos_sql();   
			$sql 	.= $this->pkh_sql();
			$sql 	.= $this->jampersal_sql();
			$sql 	.= $this->bedah_rumah_sql(); 
		}
		$query    = $this->db->query($sql);
		$row      = $query->row_array();
		$jml_data = $row['id'];
		
		$this->load->library('paging');
		$cfg['page']     = $p;
		$cfg['per_page'] = $_SESSION['per_page'];
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);
		
		return $this->paging;
	}
	
	
	function list_data_statistik($tipe=21,$o=0,$offset=0,$limit=500){
	
		//Ordering SQL
		switch($o){
			case 1: $order_sql = ' ORDER BY u.no_kk'; break;
			case 2: $order_sql = ' ORDER BY u.no_kk DESC'; break;
			case 3: $order_sql = ' ORDER BY kepala_kk'; break;
			case 4: $order_sql = ' ORDER BY kepala_kk DESC'; break;
			case 5: $order_sql = ' ORDER BY g.nama'; break;
			case 6: $order_sql = ' ORDER BY g.nama DESC'; break;
			default:$order_sql = ' ORDER BY u.tgl_daftar DESC';
		}
	
		//Paging SQL
		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;
		
		if($tipe==21){
			$sql="SELECT u.*,t.nama AS kepala_kk,(SELECT COUNT(id) FROM tweb_penduduk WHERE id_kk = u.id ) AS jumlah_anggota,c.dusun,c.rw,c.rt FROM tweb_keluarga u LEFT JOIN tweb_penduduk t ON u.nik_kepala = t.id LEFT JOIN tweb_wil_clusterdesa c ON t.id_cluster = c.id WHERE kelas_sosial=$_SESSION[kelas] ";
			$sql .= $this->search_sql();
		}else{
			$sql   = "SELECT u.*,t.nama AS kepala_kk,(SELECT COUNT(id) FROM tweb_penduduk WHERE id_kk = u.id ) AS jumlah_anggota,c.dusun,c.rw,c.rt FROM tweb_keluarga u LEFT JOIN tweb_penduduk t ON u.nik_kepala = t.id LEFT JOIN tweb_wil_clusterdesa c ON t.id_cluster = c.id WHERE 1 ";
				
			$sql .= $this->search_sql();			
			$sql     .= $this->raskin_sql(); 
			//$sql     .= $this->kelas_sql();  
			$sql	.= $this->blt_sql();
			$sql 	.= $this->bos_sql();
			$sql 	.= $this->pkh_sql();
			$sql 	.= $this->jampersal_sql();
			$sql 	.= $this->bedah_rumah_sql();
			//$sql     .= $this->rt_sql(); 
			//$sql .= $order_sql; 
			$sql .= $paging_sql;
		}
		$query = $this->db->query($sql);
		$data=$query->result_array();
		
		//Formating Output
		$i=0;
		$j=$offset;
		while($i<count($data)){
			$data[$i]['no']=$j+1;
			if($data[$i]['jumlah_anggota']==0)
				$data[$i]['jumlah_anggota'] = "-";
			
			$i++;
			$j++;
		}
		return $data;
	}


	function insert(){
		$data = $_POST;

		$temp = $data['nik_kepala'];
		$outp = $this->db->insert('tweb_keluarga',penetration($data));
		
		$sql   = "SELECT id FROM tweb_keluarga WHERE nik_kepala=?";
		$query = $this->db->query($sql,$temp);
		$kk = $query->row_array();
		
		$default['id_kk'] = $kk['id'];
		$default['kk_level'] = 1;
		
		$this->db->where('id',$temp);
		$this->db->update('tweb_penduduk',$default);
		
		$satuan=$_POST['tanggallahir'];
		$blnlahir = substr($satuan,3,2);
		$thnlahir= substr($satuan,6,4);
		$blnskrg = (date("m"));
		$thnskrg = (date("Y"));
		if(($blnlahir==$blnskrg)and($thnlahir==$thnskrg)){
			$x['id_detail']='1';
		}else{
			$x['id_detail']='5';
		}
		
		$x['id_pend']=$temp;
		$x['bulan']=$blnskrg;
		$x['tahun']=$thnskrg;		
		$outp = $this->db->insert('log_penduduk',$x);

		$log['id_pend'] = 1;
		$log['id_cluster'] = 1;
		$log['tanggal'] = date("m-d-y");
		$outp = $this->db->insert('log_perubahan_penduduk',$log);

		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	
	
	function insert_new(){
		$data = $_POST;
		$lokasi_file = $_FILES['foto']['tmp_name'];
		$tipe_file   = $_FILES['foto']['type'];
		$nama_file   = $_FILES['foto']['name'];
		$old_foto    = '';
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
		
		$data['id_cluster'] = $data['rt'];
		UNSET($data['dusun']);
		UNSET($data['rw']);
		UNSET($data['rt']);
		UNSET($data['no_kk']);
		UNSET($data['new']);
		
		$data['tanggallahir'] = tgl_indo_in($data['tanggallahir']);
		
		$outp = $this->db->insert('tweb_penduduk',penetration($data));
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
		
		$sql   = "SELECT id FROM tweb_penduduk WHERE nik=?";
		$query = $this->db->query($sql,$data['nik']);
		$temp2  = $query->row_array();
		
		$data2['nik_kepala'] = $temp2['id'];
		$data2['no_kk'] = $_POST['no_kk'];
	
		$temp = $data2['nik_kepala'];
		$outp = $this->db->insert('tweb_keluarga',$data2);
		
		$sql   = "SELECT id FROM tweb_keluarga WHERE nik_kepala=?";
		$query = $this->db->query($sql,$temp);
		$kk = $query->row_array();
		
		$default['id_kk'] = $kk['id'];
		$default['kk_level'] = 1;
		
		$this->db->where('id',$temp);
		$this->db->update('tweb_penduduk',$default);
		
		$satuan=$_POST['tanggallahir'];
		$blnlahir = substr($satuan,3,2);
		$thnlahir= substr($satuan,6,4);
		$blnskrg = (date("m"));
		$thnskrg = (date("Y"));
		if(($blnlahir==$blnskrg)and($thnlahir==$thnskrg)){
			$x['id_detail']='1';
		}else{
			$x['id_detail']='5';
		}
		
		$x['id_pend']=$temp;
		$x['bulan']=$blnskrg;
		$x['tahun']=$thnskrg;		
		$outp = $this->db->insert('log_penduduk',$x);

		$log['id_pend'] = 1;
		$log['id_cluster'] = 1;
		$log['tanggal'] = date("m-d-y");
		$outp = $this->db->insert('log_perubahan_penduduk',$log);
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function delete($id=''){
	
		$sql   = "SELECT nik_kepala FROM tweb_keluarga WHERE id=?";
		$query = $this->db->query($sql,$id);
		$temp = $query->row_array();
		
		$default['id_kk'] = "";
		$default['kk_level'] = "";
		
		$this->db->where('id_kk',$id);
		$this->db->update('tweb_penduduk',$default);
		
		$sql  = "DELETE FROM tweb_keluarga WHERE id=?";
		$outp = $this->db->query($sql,array($id));
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	
	function delete_all(){
		$id_cb = $_POST['id_cb'];
		
		if(count($id_cb)){
			foreach($id_cb as $id){
				$sql  = "DELETE FROM tweb_keluarga WHERE id=?";
				$outp = $this->db->query($sql,array($id));
			}
		}
		else $outp = false;
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	
	
	function add_anggota($id=0){
		$data = $_POST;
		$temp['id_kk'] = $id;
		$temp['kk_level'] = $data['kk_level'];

		$this->db->where('id',$data['nik']);
		$outp = $this->db->update('tweb_penduduk',$temp);
				
		if($temp['kk_level']=="1"){
			$temp2['nik_kepala'] = $data['nik'];
			$this->db->where('id',$temp['id_kk']);
			$outp = $this->db->update('tweb_keluarga',$temp2);
		}
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}	
		
	function update_anggota($id=0){
		$data = $_POST;
		
		$sql   = "SELECT id_kk FROM tweb_penduduk WHERE id=?";
		$query = $this->db->query($sql,$id);
		$pend = $query->row_array();
		
		if($data['kk_level']==1){
		
			$lvl['kk_level'] = 11;
			$this->db->where('id_kk',$pend['id_kk']);
			$this->db->where('kk_level',1);
			$this->db->update('tweb_penduduk',$lvl);
			
			$nik['nik_kepala'] = $id;
			$this->db->where('id',$pend['id_kk']);
			$this->db->update('tweb_keluarga',$nik);
			
		}
		
		$this->db->where('id',$id);
		$outp = $this->db->update('tweb_penduduk',$data);
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}	
	
	function rem_anggota($kk=0,$id=0){
		$temp['id_kk'] = 0;
		$temp['kk_level'] = 0;
		
		$pend     = $this->keluarga_model->get_anggota($id);
		$this->db->where('id',$id);
		$outp = $this->db->update('tweb_penduduk',$temp);
		if($pend['kk_level']=='1'){
			$temp2['nik_kepala']=0;
			$this->db->where('id',$pend['id_kk']);
			$outp = $this->db->update('tweb_keluarga',$temp2);
		}
		
		$log['id_pend'] = $id;
		$log['id_detail'] = "7";
		$log['bulan'] = date("m");
		$log['tahun'] = date("Y");
		$outp = $this->db->insert('log_penduduk',$log);
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}	
		
	
	function rem_all_anggota($kk){
		$id_cb = $_POST['id_cb'];
		$temp['id_kk'] = 0;
		
		if(count($id_cb)){
			foreach($id_cb as $id){
				$this->db->where('id',$id);
				$outp = $this->db->update('tweb_penduduk',$temp);
			}
		}
		else $outp = false;
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
		
	function get_dusun($id=0){
		$sql   = "SELECT * FROM tweb_keluarga WHERE dusun_id=?";
		$query = $this->db->query($sql,$id);
		$data  = $query->row_array();
		return $data;
	}
		
	function get_keluarga($id=0){
		$sql   = "SELECT * FROM tweb_keluarga WHERE id=?";
		$query = $this->db->query($sql,$id);
		$data  = $query->row_array();
		return $data;
	}
	
	function get_anggota($id=0){
		$sql   = "SELECT * FROM tweb_penduduk WHERE id=?";
		$query = $this->db->query($sql,$id);
		$data  = $query->row_array();
		return $data;
	}
	
	function list_penduduk_lepas(){
		$sql   = "SELECT id,nik,nama FROM tweb_penduduk WHERE (status = 1 OR status = 3) AND id_kk = 0";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		
		//Formating Output
		$i=0;
		while($i<count($data)){
			$data[$i]['alamat']="Alamat :".$data[$i]['nama'];
			$i++;
		}
		return $data;
	}
	
	function list_anggota($id=0){
		$sql   = "SELECT b.dusun,b.rw,b.rt,u.id,nik,dokumen_pasport,dokumen_kitas,x.nama as sex,u.kk_level,tempatlahir,tanggallahir,a.nama as agama, d.nama as pendidikan,j.nama as pekerjaan,w.nama as status_kawin,f.nama as warganegara,nama_ayah,nama_ibu,g.nama as golongan_darah,u.nama,status,h.nama AS hubungan FROM tweb_penduduk u LEFT JOIN tweb_penduduk_agama a ON u.agama_id = a.id LEFT JOIN tweb_penduduk_pekerjaan j ON u.pekerjaan_id = j.id LEFT JOIN tweb_penduduk_pendidikan_kk d ON u.pendidikan_kk_id = d.id LEFT JOIN tweb_penduduk_warganegara f ON u.warganegara_id = f.id LEFT JOIN tweb_golongan_darah g ON u.golongan_darah_id = g.id LEFT JOIN tweb_penduduk_kawin w ON u.status_kawin = w.id LEFT JOIN tweb_penduduk_sex x ON u.sex = x.id LEFT JOIN tweb_penduduk_hubungan h ON u.kk_level = h.id LEFT JOIN tweb_wil_clusterdesa b ON u.id_cluster = b.id WHERE status = 1 AND id_kk = ? ORDER BY kk_level";
		
		$query = $this->db->query($sql,array($id));
		$data=$query->result_array();
		
		//Formating Output
		$i=0;
		while($i<count($data)){
			$data[$i]['no']=$i+1;
			$data[$i]['alamat']="Dusun ".ununderscore($data[$i]['dusun']).", RW ".$data[$i]['rw'].", RT ".$data[$i]['rt'];
			$data[$i]['tanggallahir']= tgl_indo($data[$i]['tanggallahir']);
			
			$i++;
		}
		return $data;
	}
			
	function get_kepala_kk($id){
		
		$sql   = "SELECT nik,u.nama,tempatlahir,tanggallahir,a.nama as agama,d.nama as pendidikan,j.nama as pekerjaan, x.nama as sex,w.nama as status_kawin,h.nama as hubungan,warganegara_id,nama_ayah,nama_ibu,g.nama as golongan_darah ,c.rt as rt,c.rw as rw,c.dusun as dusun, (SELECT no_kk FROM tweb_keluarga WHERE id = ?) AS no_kk FROM tweb_penduduk u LEFT JOIN tweb_penduduk_pekerjaan j ON u.pekerjaan_id = j.id LEFT JOIN tweb_golongan_darah g ON u.golongan_darah_id = g.id LEFT JOIN tweb_penduduk_pendidikan_kk d ON u.pendidikan_kk_id = d.id LEFT JOIN tweb_penduduk_agama a ON u.agama_id = a.id LEFT JOIN tweb_penduduk_kawin w ON u.status_kawin = w.id LEFT JOIN tweb_penduduk_sex x ON u.sex = x.id LEFT JOIN tweb_penduduk_hubungan h ON u.kk_level = h.id LEFT JOIN tweb_wil_clusterdesa c ON u.id_cluster = c.id WHERE u.id = (SELECT nik_kepala FROM tweb_keluarga WHERE id = ?) ";
		$query = $this->db->query($sql,array($id,$id));
		return $query->row_array();
		
	}
	function get_kepala_a($id){
		
		$sql   = "SELECT u.*,c.*, (SELECT no_kk FROM tweb_keluarga WHERE id = ?) AS no_kk FROM tweb_penduduk u LEFT JOIN tweb_wil_clusterdesa c ON u.id_cluster = c.id WHERE u.id = (SELECT nik_kepala FROM tweb_keluarga WHERE id = ?) ";
		$query = $this->db->query($sql,array($id,$id));
		return $query->row_array();
		
	}
        
        function get_desa(){
		$sql   = "SELECT * FROM config WHERE 1";
		$query = $this->db->query($sql);
		return $query->row_array();
	}
	
	function list_hubungan(){
		$sql   = "SELECT *,nama as hubungan FROM tweb_penduduk_hubungan WHERE 1";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	function insert_a(){
		$data = $_POST;
		$lokasi_file = $_FILES['foto']['tmp_name'];
		$tipe_file   = $_FILES['foto']['type'];
		$nama_file   = $_FILES['foto']['name'];
		if (!empty($lokasi_file)){
			if ($tipe_file != "image/jpeg" AND $tipe_file != "image/pjpeg" AND $tipe_file != "image/png"){
				unset($data['foto']);
			} else {
				UploadFoto($nama_file);
				$data['foto'] = $nama_file;
			}
		}else{
			unset($data['foto']);
		}
		
		unset($data['file_foto']);
		unset($data['old_foto']);	
		
		$satuan=$_POST['tanggallahir'];
		$blnlahir = substr($satuan,3,2);
		$thnlahir= substr($satuan,6,4);
		$blnskrg = (date("m"));
		$thnskrg = (date("Y"));
		if(($blnlahir==$blnskrg)and($thnlahir==$thnskrg)){
			$x['id_detail']='1';
		}else{
			$x['id_detail']='5';
		}
		$data['nama'] = penetration($data['nama']);
		$data['nama_ayah'] = penetration($data['nama_ayah']);
		$data['nama_ibu'] = penetration($data['nama_ibu']);
		$data['tanggallahir'] = tgl_indo_in($data['tanggallahir']);
		$outp = $this->db->insert('tweb_penduduk',$data);
		
		$sql="select max(id) as id_pend from tweb_penduduk";
		$query = $this->db->query($sql);
		$id_pend = $query->row_array();
		$x['id_pend']=$id_pend['id_pend'];
		$x['bulan']=$blnskrg;
		$x['tahun']=$thnskrg;		
		$outp = $this->db->insert('log_penduduk',$x);
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	
	function update_nokk($id=0){
		$data = $_POST;
		
		$this->db->where("id",$id);
		$outp=$this->db->update("tweb_keluarga",$data);
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
		
	}
	
	function list_sosial(){
		
		$dus = "";
		$rw = "";
		$rt = "";
		
		if(isset($_SESSION['dusun']))
			$dus = " AND c.dusun = '$_SESSION[dusun]'";
	
		if(isset($_SESSION['rw']))
			$rw = " AND c.rw = '$_SESSION[rw]'";
	
		if(isset($_SESSION['rt']))
			$rt = " AND c.rt = '$_SESSION[rt]'";
	
		$sql   = "SELECT s.*,(SELECT COUNT(u.id) AS id FROM tweb_keluarga u LEFT JOIN tweb_penduduk t ON u.nik_kepala = t.id LEFT JOIN tweb_wil_clusterdesa c ON t.id_cluster = c.id WHERE  u.kelas_sosial = s.id $dus $rw $rt) as jumlah FROM ref_kelas_sosial s WHERE 1";
		
		
		$query = $this->db->query($sql);
		return $query->result_array();
	}
		function list_raskin(){
		
		$dus = "";
		$rw = "";
		$rt = "";
		
		if(isset($_SESSION['dusun']))
			$dus = " AND c.dusun = '$_SESSION[dusun]'";
	
		if(isset($_SESSION['rw']))
			$rw = " AND c.rw = '$_SESSION[rw]'";
	
		if(isset($_SESSION['rt']))
			$rt = " AND c.rt = '$_SESSION[rt]'";
	
		$sql   = "SELECT s.*,
		(SELECT COUNT(u.id) AS id FROM tweb_keluarga u LEFT JOIN tweb_penduduk t ON u.nik_kepala = t.id LEFT JOIN tweb_wil_clusterdesa c ON t.id_cluster = c.id WHERE  u.kelas_sosial = s.id $dus $rw $rt) as jumlah,
		(SELECT COUNT(u.id) AS id FROM tweb_keluarga u LEFT JOIN tweb_penduduk t ON u.nik_kepala = t.id LEFT JOIN tweb_wil_clusterdesa c ON t.id_cluster = c.id WHERE  u.kelas_sosial = s.id $dus $rw $rt AND u.raskin = 1) as raskin,
		(SELECT COUNT(u.id) AS id FROM tweb_keluarga u LEFT JOIN tweb_penduduk t ON u.nik_kepala = t.id LEFT JOIN tweb_wil_clusterdesa c ON t.id_cluster = c.id WHERE  u.kelas_sosial = s.id $dus $rw $rt AND t.jamkesmas = 1) as jamkesmas FROM ref_kelas_sosial s WHERE 1";
		
		
		$query = $this->db->query($sql);
		return $query->result_array();
	}
	
	function pindah_proses($id=0,$id_cluster=''){
		$this->db->where('id_kk',$id);
		$data['id_cluster'] = $id_cluster;
		$outp = $this->db->update('tweb_penduduk',$data);
			
		$sql   = "SELECT id FROM tweb_penduduk WHERE id_kk=$id";
				
		$query = $this->db->query($sql);
		$data2= $query->result_array();
		
		foreach($data2 as $datanya){
			$log['id_pend'] = $datanya['id'];
			$log['id_detail'] = "6";
			$log['bulan'] = date("m");
			$log['tahun'] = date("Y");
			$outp = $this->db->insert('log_penduduk',$log);
		}
				
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	
	function get_judul_statistik($tipe=0,$nomor=1){
		switch($tipe){
			case 21: $sql   = "SELECT * FROM klasifikasi_analisis_keluarga WHERE id=? and jenis='1'  ";break;
			case 22: $sql   = "SELECT * FROM ref_raskin WHERE id=?";break;
			case 23: $sql   = "SELECT * FROM ref_blt WHERE id=?";break;
			case 24: $sql   = "SELECT * FROM ref_bos WHERE id=?";break;
			case 25: $sql   = "SELECT * FROM ref_pkh WHERE id=?";break;
			case 26: $sql   = "SELECT * FROM ref_jampersal WHERE id=?";break;
			case 27: $sql   = "SELECT * FROM ref_bedah_rumah WHERE id=?";break;
		}
		$query = $this->db->query($sql,$nomor);
		return $query->row_array();
	}	
}

?>
