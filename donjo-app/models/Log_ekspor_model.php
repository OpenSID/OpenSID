<?php class Log_ekspor_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function tulis_log($data)
	{
		$this->db->insert('log_ekspor', $data);
	}

	public function log_terakhir($kode_ekspor, $semua=true)
	{
		return $this->db->where('kode_ekspor', $kode_ekspor)
			->where('semua', $semua)
			->order_by('tgl_ekspor DESC')
			->limit(1)->get('log_ekspor')->row();
	}
}
