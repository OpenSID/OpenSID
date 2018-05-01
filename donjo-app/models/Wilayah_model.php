<?php class Wilayah_model extends CI_Model {

	function __construct(){
		parent::__construct();
	}

	function autocomplete(){
		$sql   = "SELECT dusun FROM tweb_wil_clusterdesa";
		$query = $this->db->query($sql);
		$data  = $query->result_array();

		$i=0;
		$outp='';
		while($i<count($data)){
			$outp .= ",'" .$data[$i]['dusun']. "'";
			$i++;
		}
		$outp = strtolower(substr($outp, 1));
		$outp = '[' .$outp. ']';
		return $outp;
	}

	function search_sql(){
		if(isset($_SESSION['cari'])){
		$cari = penetration($_SESSION['cari']);
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' .$kw. '%';
			$search_sql= " AND u.dusun LIKE '$kw'";
			return $search_sql;
			}
		}

	function paging($p=1,$o=0){

		$sql      = "SELECT COUNT(id) AS id FROM tweb_wil_clusterdesa u WHERE u.rt = '0' AND u.rw = '0' ";
		$sql     .= $this->search_sql();
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

	/*
		Struktur tweb_wil_clusterdesa:
		- baris dengan kolom rt = '0' dan rw = '0' menunjukkan dusun
		- baris dengan kolom rt = '-' dan rw <> '-' menunjukkan rw
		- baris dengan kolom rt <> '0' dan rt <> '0' menunjukkan rt

		Di tabel tweb_penduduk  dan tweb_keluarga, kolom id_cluster adalah id untuk
		baris rt.
	*/
	function list_data($o=0,$offset=0,$limit=500){

		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;

		$sql   = "SELECT u.*,a.nama AS nama_kadus,a.nik AS nik_kadus,
		(SELECT COUNT(rw.id) FROM tweb_wil_clusterdesa rw WHERE dusun = u.dusun AND rw <> '-' AND rt = '-') AS jumlah_rw,
		(SELECT COUNT(v.id) FROM tweb_wil_clusterdesa v WHERE dusun = u.dusun AND v.rt <> '0' AND v.rt <> '-') AS jumlah_rt,
		(SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = u.dusun) and status_dasar=1) AS jumlah_warga,
		(SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = u.dusun) AND p.sex = 1 and status_dasar=1) AS jumlah_warga_l,
		(SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = u.dusun) AND p.sex = 2 and status_dasar=1) AS jumlah_warga_p,
		(SELECT COUNT(p.id) FROM tweb_keluarga k inner join tweb_penduduk p ON k.nik_kepala=p.id  WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = u.dusun) AND p.kk_level = 1 and status_dasar=1) AS jumlah_kk
		FROM tweb_wil_clusterdesa u LEFT JOIN tweb_penduduk a ON u.id_kepala = a.id WHERE u.rt = '0' AND u.rw = '0'  ";

		$sql .= $this->search_sql();
		$sql .= $paging_sql;

		$query = $this->db->query($sql);
		$data=$query->result_array();

		//Formating Output
		$i=0;
		$j=$offset;
		while($i<count($data)){
			$data[$i]['no']=$j+1;
			$i++;
			$j++;
		}
		return $data;
	}

	function insert(){
		$data = $_POST;
		$data['dusun']=underscore($_POST['dusun']);
		$this->db->insert('tweb_wil_clusterdesa',penetration($data));

		$rw   = penetration($data);
		$rw['rw'] = "-";
		$this->db->insert('tweb_wil_clusterdesa',$rw);

		$rt   = penetration($rw);
		$rt['rt'] = "-";
		$outp = $this->db->insert('tweb_wil_clusterdesa',$rt);

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function update($id=''){
		if(empty($_POST['id_kepala']))
			UNSET($_POST['id_kepala']);

		$data = $_POST;
		$data['dusun']=$_POST['dusun'];
		$temp = $this->wilayah_model->cluster_by_id($id);
		$this->db->where('dusun',$temp['dusun']);
		$this->db->where('rw','0');
		$this->db->where('rt','0');
		$outp1 = $this->db->update('tweb_wil_clusterdesa',$data);

		// Ubah nama dusun di semua baris rw/rt untuk dusun ini
		$outp2 = $this->db->where('dusun',$temp['dusun'])->update('tweb_wil_clusterdesa',array('dusun'=>$data['dusun']));

		if($outp1 AND $outp2) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function delete($id=''){

                $temp = $this->cluster_by_id($id);
                $dusun = (penetration($temp['dusun']));

                $sql = "DELETE FROM tweb_wil_clusterdesa WHERE dusun='$dusun'";
                $outp = $this->db->query($sql);

                if($outp) $_SESSION['success']=1;
                else $_SESSION['success']=-1;
        }

	function delete_all(){
		$id_cb = $_POST['id_cb'];

		if(count($id_cb)){
			foreach($id_cb as $dusun){
				$sql  = "DELETE FROM tweb_wil_clusterdesa WHERE id=?";
				$outp = $this->db->query($sql,array($dusun));
			}
		}
		else $outp = false;

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

//Bagian RW
	function list_data_rw($id=''){

		$temp = $this->cluster_by_id($id);
		$dusun = $temp['dusun'];

		$sql   = "SELECT u.*,a.nama AS nama_ketua,a.nik AS nik_ketua,
		(SELECT COUNT(rt.id) FROM tweb_wil_clusterdesa rt WHERE dusun = u.dusun AND rw = u.rw AND rw <> '-' AND rt <> '-' AND rt <> '0' ) AS jumlah_rt,
		(SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = '$dusun' AND rw = u.rw) AND p.status_dasar=1) AS jumlah_warga,
		(SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = '$dusun' AND rw = u.rw) AND p.sex = 1 AND p.status_dasar=1) AS jumlah_warga_l,
		(SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = '$dusun' AND rw = u.rw) AND p.sex = 2 AND p.status_dasar=1) AS jumlah_warga_p,
		(SELECT COUNT(p.id) FROM tweb_keluarga k inner join tweb_penduduk p ON k.nik_kepala=p.id  WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = '$dusun' AND rw = u.rw) AND p.kk_level = 1 AND p.status_dasar=1) AS jumlah_kk
		FROM tweb_wil_clusterdesa u LEFT JOIN tweb_penduduk a ON u.id_kepala = a.id WHERE u.rt = '0' AND u.rw <> '0' AND u.dusun = '$dusun'";
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

	function insert_rw($dusun=''){

		$data = $_POST;
		$temp = $this->cluster_by_id($dusun);
		$data['dusun']= $temp['dusun'];
		$outp = $this->db->insert('tweb_wil_clusterdesa',$data);

		$rt   = $data;
		$rt['rt'] = "-";
		$outp = $this->db->insert('tweb_wil_clusterdesa',$rt);

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function update_rw($dusun='',$rw=''){

		if(empty($_POST['id_kepala']))
			UNSET($_POST['id_kepala']);

		$data = $_POST;
		$temp = $this->wilayah_model->cluster_by_id($dusun);
		$this->db->where('dusun',$temp['dusun']);
		$this->db->where('rw',$rw);
		$outp = $this->db->update('tweb_wil_clusterdesa',$data);

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function delete_rw($id){
	 $temp = $this->cluster_by_id($id);
                $rw = $temp['rw'];
                $dusun = $temp['dusun'];

                $sql = "DELETE FROM tweb_wil_clusterdesa WHERE rw='$rw' and dusun='$dusun'";
                $outp = $this->db->query($sql,array($id));

                if($outp) $_SESSION['success']=1;
                else $_SESSION['success']=-1;

	}

	function delete_all_rw(){
		$id_cb = $_POST['id_cb'];

		if(count($id_cb)){
			foreach($id_cb as $id){
				$sql  = "DELETE FROM tweb_wil_clusterdesa WHERE id=?";
				$outp = $this->db->query($sql,array($id));
			}
		}
		else $outp = false;

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}


//Bagian RT
	function list_data_rt($dusun='',$rw=''){

		$sql   = "SELECT u.*,a.nama AS nama_ketua,a.nik AS nik_ketua,
		(SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = '$dusun' AND rw = '$rw' AND rt = u.rt) AND p.status_dasar=1) AS jumlah_warga,
		(SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = '$dusun' AND rw = '$rw' AND rt = u.rt) AND p.sex = 1 AND p.status_dasar=1) AS jumlah_warga_l,(
		SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = '$dusun' AND rw = '$rw' AND rt = u.rt) AND p.sex = 2 AND p.status_dasar=1) AS jumlah_warga_p,
		(SELECT COUNT(p.id) FROM tweb_keluarga k inner join tweb_penduduk p ON k.nik_kepala=p.id  WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = '$dusun' AND rw = '$rw' AND rt = u.rt) AND p.kk_level = 1) AS jumlah_kk
		FROM tweb_wil_clusterdesa u LEFT JOIN tweb_penduduk a ON u.id_kepala = a.id WHERE u.rt <> '0' AND u.rw = '$rw' AND u.dusun = '$dusun' AND u.rt <> '-'";

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

	function insert_rt($dusun='',$rw=''){
		$data = $_POST;
		$temp = $this->cluster_by_id($dusun);
		$data['dusun']= $temp['dusun'];
		$data['rw']    = $rw;
		$outp = $this->db->insert('tweb_wil_clusterdesa',$data);

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function update_rt($id=0){

		if(empty($_POST['id_kepala']))
			UNSET($_POST['id_kepala']);

		$data = $_POST;
		$this->db->where('id',$id);
		$outp = $this->db->update('tweb_wil_clusterdesa',$data);

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function update_dusun_map($dusun=''){

		$data = $_POST;
		$this->db->where('id',$dusun);
		$outp = $this->db->update('tweb_wil_clusterdesa',$data);

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
        function get_dusun_maps($id=''){
		$sql   = "SELECT * FROM tweb_wil_clusterdesa WHERE id=?";
		$query = $this->db->query($sql,$id);
		return $query->row_array();
	}


	function update_rw_map($dus=0,$id=0){

		$data = $_POST;
		$this->db->where('dusun',$dus);
		$this->db->where('rw',$id);
		$this->db->where('rt','0');
		$outp = $this->db->update('tweb_wil_clusterdesa',$data);

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function update_rt_map($dus=0,$rw=0,$id=0){

		$data = $_POST;
		$this->db->where('dusun',$dus);
		$this->db->where('rw',$rw);
		$this->db->where('rt',$id);
		$outp = $this->db->update('tweb_wil_clusterdesa',$data);

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function delete_rt($id=0){
		$sql  = "DELETE FROM tweb_wil_clusterdesa WHERE id = ?";
		$outp = $this->db->query($sql,$id);

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function delete_all_rt(){
		$id_cb = $_POST['id_cb'];

		if(count($id_cb)){
			foreach($id_cb as $id){
				$sql  = "DELETE FROM tweb_wil_clusterdesa WHERE  id = ?";
				$outp = $this->db->query($sql,$id);
			}
		}
		else $outp = false;

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function list_penduduk(){
		$sql   = "SELECT id,nik,nama FROM tweb_penduduk WHERE status = 1";
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

	function list_penduduk_ex($id=0){
		$sql   = "SELECT id,nik,nama FROM tweb_penduduk WHERE status = 1 AND id NOT IN(?)";
		$query = $this->db->query($sql,$id);
		$data=$query->result_array();

		//Formating Output
		$i=0;
		while($i<count($data)){
			$data[$i]['alamat']="Alamat :".$data[$i]['nama'];
			$i++;
		}
		return $data;
	}

	function list_dusun_rt($dusun=''){
		$sql = "select * FROM tweb_clusterdesa Where dusun = ? AND rt <> '' ";
		$query = $this->db->query($sql,$id);
		$data = $query->row_array();
		return $data;
	}

	function get_penduduk($id=0){
		$sql   = "SELECT id,nik,nama FROM tweb_penduduk WHERE id = ?";
		$query = $this->db->query($sql,$id);
		$data  = $query->row_array();
		return $data;
	}

	function get_dusun($dusun=''){
		$sql   = "SELECT * FROM tweb_wil_clusterdesa WHERE dusun = ? AND rt = '0' AND rw = '0'";
		$query = $this->db->query($sql,$dusun);
		return $query->row_array();
	}

	function cluster_by_id($id=''){
		$sql   = "SELECT * FROM tweb_wil_clusterdesa WHERE id = ?";
		$query = $this->db->query($sql,$id);
		return $query->row_array();
	}

	function get_rw($dusun='',$rw=''){
		$sql   = "SELECT * FROM tweb_wil_clusterdesa WHERE dusun = ? AND rw = ? AND rt = '0'";
		$query = $this->db->query($sql,array($dusun,$rw));
		return $query->row_array();
	}

	function get_rt($dusun='',$rw='',$rt=''){
		$sql   = "SELECT * FROM tweb_wil_clusterdesa WHERE dusun = ? AND rw = ? AND rt = ?";
		$query = $this->db->query($sql,array($dusun,$rw,$rt));
		return $query->row_array();
	}

	function total(){
		$sql = "SELECT
		(SELECT COUNT(rw.id) FROM tweb_wil_clusterdesa rw WHERE  rw <> '-' AND rt = '-') AS total_rw,
		(SELECT COUNT(v.id) FROM tweb_wil_clusterdesa v WHERE v.rt <> '0' AND v.rt <> '-') AS total_rt,
		(SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa ) and status_dasar=1) AS total_warga,
		(SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa) AND p.sex = 1 and status_dasar=1) AS total_warga_l,
		(SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa) AND p.sex = 2 and status_dasar=1) AS total_warga_p,
		(SELECT COUNT(p.id) FROM tweb_keluarga k inner join tweb_penduduk p ON k.nik_kepala=p.id WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa) AND p.kk_level = 1 and status_dasar=1) AS total_kk
		FROM tweb_wil_clusterdesa u
		LEFT JOIN tweb_penduduk a ON u.id_kepala = a.id WHERE u.rt = '0' AND u.rw = '0' limit 1";
		$query = $this->db->query($sql);
		return $query->row_array();
	}

	function total_rw($dusun=''){
		$sql   = "select sum(jumlah_rt) as jmlrt,sum(jumlah_warga) as jmlwarga,sum(jumlah_warga_l) as jmlwargal,sum(jumlah_warga_p) as jmlwargap,sum(jumlah_kk) as jmlkk from
		(SELECT u.*,a.nama AS nama_ketua,a.nik AS nik_ketua,(SELECT COUNT(rt.id) FROM tweb_wil_clusterdesa rt WHERE dusun = u.dusun AND rw = u.rw AND rw <> '-' AND rt <> '-' AND rt <> '0' ) AS jumlah_rt,(SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = '$dusun' AND rw = u.rw ) and status_dasar=1) AS jumlah_warga,(SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = '$dusun' AND rw = u.rw) AND p.sex = 1 and status_dasar=1) AS jumlah_warga_l,(SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = '$dusun' AND rw = u.rw) AND p.sex = 2 and status_dasar=1) AS jumlah_warga_p,(SELECT COUNT(p.id) FROM  tweb_keluarga k inner join tweb_penduduk p ON k.nik_kepala=p.id   WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = '$dusun' AND rw = u.rw) AND p.kk_level = 1 and status_dasar=1) AS jumlah_kk FROM tweb_wil_clusterdesa u LEFT JOIN tweb_penduduk a ON u.id_kepala = a.id WHERE u.rt = '0' AND u.rw <> '0' AND u.dusun = '$dusun') as x  ";
		$query = $this->db->query($sql);
		$data=$query->row_array();
		return $data;
	}

	function total_rt($dusun='',$rw=''){
		$sql   = "select sum(jumlah_warga) as jmlwarga,sum(jumlah_warga_l) as jmlwargal,sum(jumlah_warga_p) as jmlwargap,sum(jumlah_kk) as jmlkk from
		(SELECT u.*,a.nama AS nama_ketua,a.nik AS nik_ketua,(SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = '$dusun' AND rw = '$rw' AND rt = u.rt) and status_dasar=1) AS jumlah_warga,(SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = '$dusun' AND rw = '$rw' AND rt = u.rt) AND p.sex = 1 and status_dasar=1) AS jumlah_warga_l,(SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = '$dusun' AND rw = '$rw' AND rt = u.rt) AND p.sex = 2 and status_dasar=1) AS jumlah_warga_p, (SELECT COUNT(p.id) FROM  tweb_keluarga k inner join tweb_penduduk p ON k.nik_kepala=p.id   WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = '$dusun' AND rw = '$rw' AND rt = u.rt) AND p.kk_level = 1 and status_dasar=1) AS jumlah_kk FROM tweb_wil_clusterdesa u LEFT JOIN tweb_penduduk a ON u.id_kepala = a.id WHERE u.rt <> '0' AND u.rt <> '-' AND u.rw = '$rw' AND u.dusun = '$dusun') as x  ";
		$query = $this->db->query($sql);
		$data=$query->row_array();
		return $data;
	}

}

?>
