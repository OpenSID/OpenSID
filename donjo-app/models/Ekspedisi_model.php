<?php class Ekspedisi_model extends Surat_keluar_model {

	public function __construct()
	{
		parent::__construct();
	}

	public function list_data($o=0, $offset=0, $limit=500)
	{
		$this->db->where('ekspedisi', 1);
		$data = parent::list_data($o, $offset, $limit);
		return $data;
	}

}

?>
