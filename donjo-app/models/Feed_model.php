<?php class Feed_model extends CI_Model
{

	public function list_feeds()
	{
		$this->db->select('a.*, u.nama AS owner, k.kategori, k.slug AS kat_slug, YEAR(tgl_upload) AS thn, MONTH(tgl_upload) AS bln, DAY(tgl_upload) AS hri')
			->from('artikel a')
			->join('user u', 'a.id_user = u.id', 'left')
			->join('kategori k', 'a.id_kategori = k.id', 'left')
			->where('a.enabled', 1)
			->where('a.id_kategori NOT IN (1000)')
			->where('tgl_upload < NOW()')
			->order_by('a.tgl_upload', DESC)
			->limit('20');

			return $this->db->get()->result();
	}
}
?>
