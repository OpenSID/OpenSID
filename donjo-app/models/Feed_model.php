<?php class Feed_model extends CI_Model
{

	function __construct()
	{

		$this->load->database();

	}
	
	public function list_feeds()
	{
		$this->db->select('a.judul as judulnya,a.*,u.nama AS owner,k.kategori AS kategori');		
		$this->db->from('artikel a LEFT JOIN user u ON (a.id_user = u.id)
		LEFT JOIN kategori k ON (a.id_kategori = k.id)');
		$this->db->where('a.enabled=1');
		$this->db->order_by('a.id', 'desc');
        $this->db->limit('20');
	    return $this->db->get()->result();		
	}
}
?>