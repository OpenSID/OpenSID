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
		$query = $this->db->get_where('tweb_penduduk_asuransi', array('id !=' => 1));
		$data = $query->result_array();
		return $data;
	}
	public function get_data($id)
	{
		$query = $this->db->get_where('tweb_penduduk_asuransi', array('id' => $id));
		foreach ($query->result() as $row)
		{
<<<<<<< HEAD
        	$data = $row->nama;
=======
        		$data = $row->nama_asuransi;
>>>>>>> 89dcf8cad68ab35e7d49ed686a7f8dcfffd9a7b1
		}
		return $data;
	}
	public function update($id,$data)
	{
		$this->db->set('nama', $data);
		$this->db->where('id', $id);
		$this->db->update('tweb_penduduk_asuransi');
	}

	public function delete($id)
	{
		$this->db->delete('tweb_penduduk_asuransi', array('id' => $id));
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
