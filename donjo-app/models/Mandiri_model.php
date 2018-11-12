<?php class Mandiri_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function autocomplete()
	{
		$sql = "SELECT p.nik
			FROM tweb_penduduk_mandiri m
			LEFT JOIN tweb_penduduk p ON m.id_pend = p.id";
		$query = $this->db->query($sql);
		$data = $query->result_array();

		$outp = '';
		for ($i=0; $i<count($data); $i++)
		{
			$outp .= ",'" .$data[$i]['nik']. "'";
		}
		$outp = substr($outp, 1);
		$outp = '[' .$outp. ']';

		return $outp;
	}

	private function search_sql()
	{
		if (isset($_SESSION['cari']))
		{
			$cari = $_SESSION['cari'];
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' .$kw. '%';
			$search_sql= " AND (n.nik LIKE '$kw' OR n.nama LIKE '$kw')";
			return $search_sql;
			}
		}

	public function paging($p=1, $o=0)
	{
		$list_data_sql = $this->list_data_sql($log);
		$sql = "SELECT COUNT(*) AS jml ".$list_data_sql;
		$query = $this->db->query($sql);
		$row = $query->row_array();
		$jml_data = $row['jml'];

		$this->load->library('paging');
		$cfg['page'] = $p;
		$cfg['per_page'] = $_SESSION['per_page'];
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);

		return $this->paging;
	}

	private function list_data_sql()
	{
		$sql = "FROM tweb_penduduk_mandiri u
			LEFT JOIN tweb_penduduk n ON u.id_pend = n.id
			WHERE 1";
		$sql .= $this->search_sql();
		return $sql;
	}

	public function list_data($o=0, $offset=0, $limit=500)
	{
		//Ordering SQL
		switch ($o)
		{
			case 1: $order_sql = ' ORDER BY u.last_login'; break;
			case 2: $order_sql = ' ORDER BY u.last_login DESC'; break;
			default:$order_sql = ' ORDER BY u.tanggal_buat';
		}

		//Paging SQL
		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;

		//Main Query
		$select_sql = "SELECT u.*, n.nama AS nama, n.nik AS nik ";
		$list_data_sql = $this->list_data_sql();
		$sql = $select_sql." ".$list_data_sql;

		$sql .= $order_sql;
		$sql .= $paging_sql;
		$query = $this->db->query($sql);
		$data = $query->result_array();
		//Formating Output
		$j = $offset;
		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $j + 1;
			$j++;
		}
		return $data;
	}

	private function generate_pin($pin="")
	{
		if ($pin == "")
		{
			$pin = rand(100000, 999999);
			$pin = strrev($pin);
		}
		return $pin;
	}

	public function insert()
  {
    if ($_POST['nik'] == "")
    {
        redirect("mandiri");
    }
    if (empty($_POST['pin']))
    {
    	$rpin = $this->generate_pin($_POST['pin']);
    }
    else
    {
	    // load library form_validation
	    $this->load->library('form_validation');
	    $this->form_validation->set_rules('pin', 'Pin', 'trim|numeric|required|min_length[6]|max_length[6]');
	    if ($this->form_validation->run() !== true)
	    {
	    	$_SESSION['error_msg'] = 'PIN harus 6 (enam) digit angka.';
	    	return;
	    }
	    $rpin = $_POST['pin'];
    }

    $sql = "DELETE FROM tweb_penduduk_mandiri
				WHERE id_pend = (SELECT id FROM tweb_penduduk WHERE strcmp(nik, ?) = 0)";
    $outp = $this->db->query($sql, array($_POST['nik']));
    $hash_pin = hash_pin($rpin);
    $data['pin'] = $hash_pin;
    $data['id_pend'] = $this->db->select('id')->where('nik', $_POST['nik'])
        ->get('tweb_penduduk')->row()->id;
    $data['tanggal_buat'] = date("Y-m-d H:i:s");
    $outp = $this->db->insert('tweb_penduduk_mandiri', $data);
    if ($_POST['pin'] != "")
    {
      return $_POST['pin'];
    }
    else
    {
      return $rpin;
    }
  }

	public function delete($id_pend='')
	{
		$sql = "DELETE FROM tweb_penduduk_mandiri WHERE id_pend = ?";
		$outp = $this->db->query($sql, array($id_pend));
		return $outp;
	}

	public function delete_all()
	{
		$_SESSION['success'] = 1;
		$id_cb = $_POST['id_cb'];

		if (count($id_cb))
		{
			foreach($id_cb as $id)
			{
				$outp = $this->delete($id);
				if (!$outp) $_SESSION['success'] = -1;
			}
		}
	}

	public function list_penduduk()
	{
		$sql = "SELECT nik AS id, nik, nama FROM tweb_penduduk WHERE status = 1 AND nik <> '' AND nik <> 0";
		$query = $this->db->query($sql);
		$data = $query->result_array();

		//Formating Output AND nik NOT IN(SELECT nik FROM tweb_penduduk_mandiri)
		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['alamat'] = "Alamat :".$data[$i]['nama'];
		}
		return $data;
	}

}
