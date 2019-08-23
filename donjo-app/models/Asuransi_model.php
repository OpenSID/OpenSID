<?php class Asuransi_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function create($data)
	{
		$this->db->insert('tweb_penduduk_asuransi', $data);
	}

	public function get_all()
	{
		$query = $this->db->get_where('tweb_penduduk_asuransi', array('id_asuransi !=' => 1));
		$data = $query->result_array();
		return $data;
	}
	public function get_data($id)
	{
		$query = $this->db->get_where('tweb_penduduk_asuransi', array('id_asuransi' => $id));
		foreach ($query->result() as $row)
		{
        		$data = $row->nama_asuransi;
		}
		return $data;
	}
	public function update($id,$data)
	{
		$this->db->set('nama_asuransi', $data);
		$this->db->where('id_asuransi', $id);
		$this->db->update('tweb_penduduk_asuransi');
	}

	public function delete($id)
	{
		$this->db->delete('tweb_penduduk_asuransi', array('id_asuransi' => $id));
	}
	public function delete_all()
	{
		$id_cb = $_POST['id_cb'];

		if (count($id_cb))
		{
			foreach ($id_cb as $id)
			{
				$this->delete($id);
			}
		}
	}
}

?>
