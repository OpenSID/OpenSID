<?php class Feed_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function list_feeds()
	{
		$this->db->select('a.judul as judulnya, a.*, u.nama AS owner, k.kategori AS kategori')
			->from('artikel a')
			->join('user u', 'a.id_user = u.id', 'left')
			->join('kategori k', 'a.id_kategori = k.id', 'left')
			->where('a.enabled', '1')
			->order_by('a.id', 'desc')
      ->limit('20');
    return $this->db->get()->result();
	}
}
?>