<?php class Analisis_parameter_model extends MY_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function list_parameter_by_id_master($id_master)
	{
		$data = $this->db
			->select('i.nomor, p.kode_jawaban, p.jawaban, p.nilai')
			->from('analisis_indikator i')
			->join('analisis_parameter p', 'i.id = p.id_indikator')
			->where('i.id_master', $id_master)
			->order_by('LPAD(i.nomor, 10, " ") ASC', 'p.kode_jawaban ASC')
			->get()->result_array();
		return $data;
	}
}
?>
