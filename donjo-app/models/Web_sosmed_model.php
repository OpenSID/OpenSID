<?php

class Web_sosmed_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function get_sosmed($sosmed)
	{
		$id = $this->get_id($sosmed);

		$data = $this->db->where('id', $id)->get('media_sosial')->row_array();

		return $data;
	}

	public function list_sosmed()
	{
		$data = $this->db->get('media_sosial')->result_array();

		return $data;
	}

	public function get_id($sosmed)
	{
		$list_sosmed = $this->list_sosmed();

		foreach ($list_sosmed as $list)
		{
			$nama = str_replace(' ', '-', strtolower($list['nama']));

			if($nama == $sosmed) return $list['id'];
		}
	}

	public function update($sosmed)
	{
		$id = $this->get_id($sosmed);

		$data = $this->input->post();
		$link = trim(strip_tags($this->input->post('link')));

		switch ($id)
		{
			case '6':
				$data['link'] = preg_replace('/[^A-Za-z0-9]/', '', $link);
				break;

			case '7':
				$data['link'] = preg_replace('/[^A-Za-z0-9_]/', '', $link);
				break;

			default:
				$data['link'] = $link;
				break;
		}

		$this->db->where('id', $id);
		$outp = $this->db->update('media_sosial', $data);

		status_sukses($outp); //Tampilkan Pesan
	}

	// Penanganan khusus sesuai jenis sosmed
	public function link_sosmed($id = 0, $link = '', $tipe = 1)
	{
		if (empty($link)) return $link;

		switch (true)
		{
			case ($id == 1 && $tipe == 1) :
				$link = 'https://web.facebook.com/' . $link;
				break;

			case ($id == 1 && $tipe == 2) :
				$link = 'https://web.facebook.com/groups/' . $link;
				break;

			case ($id == 2) :
				$link = 'https://twitter.com/' . $link;
				break;

			case ($id == 4) :
				$link = 'https://www.youtube.com/channel/' . $link;
				break;

			case ($id == 5) :
				$link = 'https://www.instagram.com/' . $link . '/';
				break;

			case ($id == 6 && $tipe == 1) :
				$link = 'https://api.whatsapp.com/send?phone=' . $link;
				break;

			case ($id == 6 && $tipe == 2) :
				$link = 'https://chat.whatsapp.com/' . $link;
				break;

			case ($id == 7 && $tipe == 1) :
				$link = 'https://t.me/' . $link;
				break;

			case ($id == 7 && $tipe == 2) :
				$link = 'https://t.me/joinchat/' . $link;
				break;

			default:
				break;
		}

		return $link;
	}

}
?>
