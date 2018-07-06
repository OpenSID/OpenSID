<?php class Modul_model extends CI_Model{

	function __construct()
	{
		parent::__construct();
	}

	function list_data()
	{
		$sql = "SELECT u.* FROM setting_modul u WHERE hidden = 0 AND parent = 0 ORDER BY urut";
		$sql .= $this->search_sql();
		$sql .= $this->filter_sql();
		$sql .= ' ORDER BY urut';
		$query = $this->db->query($sql);
		$data  = $query->result_array();

		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no']=$i+1;
			$data[$i]['submodul'] = $this->list_sub_modul($data[$i]['id']);
		}
		return $data;
	}

	// Menampilkan menu dan sub menu halaman pengguna login berdasarkan daftar modul dan sub modul yang aktif.
	function list_aktif()
	{
		if (empty($_SESSION['grup'])) return array();
		$data = $this->db->where('aktif',1)->where('parent',0)->where("level >= {$_SESSION['grup']}")
			->order_by('urut')
			->get('setting_modul')->result_array();
			for($i=0; $i<count($data); $i++)
			{
				$data[$i]['submodul'] = $this->list_sub_modul_aktif($data[$i]['id']);
			}
		return $data;
	}

	function list_sub_modul_aktif($modul_id)
	{
		$data	= $this->db->select('*')->where(array('parent'=>$modul_id,'aktif'=>1))->order_by('urut')->get('setting_modul')->result_array();
		return $data;
	}

	// Menampilkan tabel sub modul
	function list_sub_modul($modul=1)
	{
		$sql   = "SELECT u.* FROM setting_modul u WHERE hidden = 0 AND parent = ? ORDER BY urut";
		$query = $this->db->query($sql,$modul);
		$data=$query->result_array();

		$i=0;
		while($i<count($data))
		{
			$data[$i]['no']=$i+1;
			$i++;
		}
		return $data;
	}

	function autocomplete()
	{
		$sql = "SELECT modul FROM setting_modul WHERE hidden = 0
					UNION SELECT url FROM setting_modul WHERE  hidden = 0";
		$query = $this->db->query($sql);
		$data = $query->result_array();

		$i=0;
		$outp='';
		while($i<count($data)){
			$outp .= ",'" .$data[$i]['modul']. "'";
			$i++;
		}
		$outp = substr($outp, 1);
		$outp = '[' .$outp. ']';
		return $outp;
	}

	function search_sql(){
		if(isset($_SESSION['cari'])){
		$cari = $_SESSION['cari'];
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' .$kw. '%';
			$search_sql= " AND (u.modul LIKE '$kw' OR u.url LIKE '$kw')";
			return $search_sql;
			}
		}

	function filter_sql(){
		if(isset($_SESSION['filter'])){
			$kf = $_SESSION['filter'];
			$filter_sql= " AND u.aktif = $kf";
		return $filter_sql;
		}
	}

	function get_data($id=0){
		$sql = "SELECT * FROM setting_modul WHERE id=?";
		$query = $this->db->query($sql,$id);
		$data = $query->row_array();
		return $data;
	 }

	function update($id=0){
		$data = $_POST;
		$this->db->where('id',$id);
		$outp = $this->db->update('setting_modul',$data);
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function delete($id=''){
		$sql = "DELETE FROM setting_modul WHERE id=?";
		$outp = $this->db->query($sql,array($id));

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function delete_all(){
		$id_cb = $_POST['id_cb'];

		if(count($id_cb)){
			foreach($id_cb as $id){
				$sql = "DELETE FROM setting_modul WHERE id=?";
				$outp = $this->db->query($sql,array($id));
			}
		}
		else $outp = false;

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
}
