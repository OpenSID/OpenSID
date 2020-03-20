<?php class Teks_berjalan_model extends CI_Model {

	private $urut_model;

	public function __construct()
	{
		parent::__construct();
	  require_once APPPATH.'/models/Urut_model.php';
		$this->urut_model = new Urut_Model('teks_berjalan');
	}

	/*
	 * Teks untuk ditampilkan di widget teks berjalan di web
	*/
	public function isi_teks_berjalan()
	{
		$data = $this->db->select('teks, tautan, judul_tautan')
			->where('status', 1)
			->order_by('urut')
			->get('teks_berjalan')->result_array();
		return $data;
	}

	public function get_teks($id='')
	{
		$data = $this->db->select('t.*, a.judul')
			->where('t.id', $id)
			->from('teks_berjalan t')
			->join('artikel a', 'a.id = t.tautan', 'left')
			->get()->row_array();
		$data['teks'] = strip_tags($data['teks']);
		return $data;
	}

	public function get_teks_aktif()
	{
		$data = $this->db->where('status', 1)->
			order_by('urut')->
			get('teks')->result_array();
		return $data;
	}

	private function list_data_sql()
	{
		$sql = " FROM teks_berjalan t
			LEFT JOIN artikel a ON a.id = t.tautan
			WHERE 1";
		return $sql;
	}

	public function list_data()
	{
		$order_sql = ' ORDER BY urut';

		$sql = "SELECT t.*, a.judul " . $this->list_data_sql();
		$sql .= $order_sql;

		$query = $this->db->query($sql);
		$data = $query->result_array();

		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $i + 1;

			if ($data[$i]['status'] == 1)
				$data[$i]['aktif'] = "Ya";
			else
			{
				$data[$i]['aktif'] = "Tidak";
				$data[$i]['status'] = 2;
			}
			$teks = strip_tags($data[$i]['teks']);
			if (strlen($teks) > 150)
			{
				$abstrak = substr($teks,0,150)."...";
			}
			else
			{
				$abstrak = $teks;
			}
			$data[$i]['teks'] = $abstrak;
		}
		return $data;
	}

	/**
	 * @param $id Id teks
	 * @param $arah Arah untuk menukar dengan teks: 1) bawah, 2) atas
	 * @return int Nomer urut teks lain yang ditukar
	 */
	public function urut($id, $arah)
	{
  	return $this->urut_model->urut($id, $arah);
	}

	public function lock($id='', $val=0)
	{
		$this->db->where('id', $id)->update('teks_berjalan', array('status' => $val));
	}

	public function insert()
	{
		$this->session->success = 1;
		$this->session->error_msg = '';

		$data = $this->input->post();
		$data['status'] = 2;

		// insert baru diberi urutan terakhir
		$data['urut'] = $this->urut_model->urut_max() + 1;
		$data = $this->sanitise_data($data);
		$data['created_by'] = $this->session->user;

		$outp = $this->db->insert('teks_berjalan', $data);
		
		status_sukses($outp, $gagal_saja=true); //Tampilkan Pesan
	}

	private function sanitise_data($data)
	{
		$data['teks'] = strip_tags($data['teks']);
		$data['judul_tautan'] = $data['tautan'] ? strip_tags($data['judul_tautan']) : '';
		return $data;
	}

	public function update($id=0)
	{
		$this->session->success = 1;
		$this->session->error_msg = '';

		$data = $this->input->post();
		$data = $this->sanitise_data($data);
		$data['updated_by'] = $this->session->user;
		$data['updated_at'] = date('Y-m-d H:i:s');
		$this->db->where('id', $id);
		$outp = $this->db->update('teks_berjalan', $data);
		
		status_sukses($outp, $gagal_saja=true); //Tampilkan Pesan
	}

	public function delete($id='', $semua=false)
	{
		if (!$semua) $this->session->success = 1;
		
		$outp = $this->db->where('id', $id)->delete('teks_berjalan');
		
		status_sukses($outp, $gagal_saja=true); //Tampilkan Pesan
	}

	public function delete_all()
	{
		$this->session->success = 1;

		$id_cb = $_POST['id_cb'];
		foreach ($id_cb as $id)
		{
			$this->delete($id, $semua=true);
		}
	}

}
?>
