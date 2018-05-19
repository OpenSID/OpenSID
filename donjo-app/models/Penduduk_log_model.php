<?php class Penduduk_log_model extends CI_Model{

	function __construct(){
		parent::__construct();

		$this->load->model('penduduk_model');
	}

	/**
	 * Ambil data log penduduk
	 *
	 * @param $id_log 					id log penduduk
	 * @return array(data log)
	 */
	function get_log($id_log)
	{
		$log = $this->db
					->select("s.nama as status, date_format(tgl_peristiwa, '%d-%m-%Y') as tgl_peristiwa, id_detail, catatan")
					->where('l.id', $id_log)
					->join('tweb_penduduk p','l.id_pend = p.id')
					->join('tweb_status_dasar s','s.id = p.status_dasar')
					->get('log_penduduk l')->row_array();
		if (empty($log['tgl_peristiwa'])) $log['tgl_peristiwa'] = date("d-m-Y");
		return $log;
	}

	/**
	 * Update log penduduk
	 *
	 * @param $id_log 					id log penduduk
	 * @return void
	 */
	function update($id_log)
	{
		unset($_SESSION['success']);
		$data = $this->input->post();
		$data['tgl_peristiwa'] = rev_tgl($data['tgl_peristiwa']);
		if (!$this->db->where('id', $id_log)->update('log_penduduk', $data))
			$_SESSION['success'] = -1;
	}

	/**
	 * Kembalikan status dasar penduduk ke hidup
	 *
	 * @param $id_log 			id log penduduk
	 * @return void
	 */
	public function kembalikan_status($id_log)
	{
		$log = $this->db->where('id', $id_log)->get('log_penduduk')->row();
		$data['status_dasar'] = 1; // status dasar hidup
		if (!$this->db->where('id',$log->id_pend)->update('tweb_penduduk', $data))
			$_SESSION['success'] = - 1;
		// Hapus log penduduk
		if (!$this->db->where('id', $id_log)->delete('log_penduduk'))
			$_SESSION['success'] = - 1;
	}

	/**
	 * Kembalikan status dasar sekumpulan penduduk ke hidup
	 *
	 * @param
	 * @return void
	 */
	public function kembalikan_status_all()
	{
		unset($_SESSION['success']);
		$id_cb = $_POST['id_cb'];
		foreach($id_cb as $id)
		{
			$this->kembalikan_status($id);
		}
	}
}
