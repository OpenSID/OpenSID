<?php

class Web_sosmed_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function get_sosmed($sosmed)
	{
		$id = $this->get_id($sosmed);

		return $this->db->where('id', $id)->get('media_sosial')->row_array();
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

		$data['link'] = $link;

		$this->db->where('id', $id);
		$outp = $this->db->update('media_sosial', $data);

		status_sukses($outp); //Tampilkan Pesan
	}

	// Penanganan khusus sesuai jenis sosmed
	public function link_sosmed($id = 0, $link = '', $tipe = 1)
	{
		if (empty($link)) {
			return $link;
		}

		// list domain yang akan digunakan untuk ditambahkan protokol https
		// ini digunakan untuk cek apakah mengandung string domain dibawah atau tidak
		// jika $link tidak ada protokol http/https maka akan ditambahkan terlebih dahulu
		$list_domain = [
			'facebook.com',
			'instagram.com',
			't.me',
			'telegram.me',
			'twitter.com',
			'whatsapp.com',
			'youtube.com',
		];

		foreach ($list_domain as $key) {
			if (strpos($link, $key) !== false) {
				// tambahkan https di awal link
				$link = preg_replace('/^http:/i', 'https:', prep_url($link));
			}
		}

		// Remove all illegal characters from a url
		// remove `@` with ''
		$link = str_replace('@', '', $link);
		$link = filter_var($link, FILTER_SANITIZE_URL);

		// validasi link
		$valid_link = filter_var($link, FILTER_VALIDATE_URL);

		switch (true) {
			case $id === '1' && $tipe === '1':
				$link = ($valid_link ? $link : 'https://web.facebook.com/' . $link);
				break;

			case $id === '1' && $tipe === '2':
				$link = ($valid_link !== false ? $link : 'https://web.facebook.com/groups/' . $link);
				break;

			case $id === '2':
				$link = ($valid_link !== false ? $link : 'https://twitter.com/' . $link);
				break;

			case $id === '4':
				$link = ($valid_link !== false ? $link : 'https://www.youtube.com/channel/' . $link);
				break;

			case $id === '5':
				$link = ($valid_link !== false ? $link : 'https://www.instagram.com/' . $link . '/');
				break;

			case $id === '6' && $tipe === '1':

				$link = ($valid_link !== false ? $link : 'https://api.whatsapp.com/send?phone=' . $link);
				break;

			case $id === '6' && $tipe === '2':
				$link = ($valid_link !== false ? $link : 'https://chat.whatsapp.com/' . $link);
				break;

			case $id === '7' && $tipe === '1':
				$link = ($valid_link !== false ? $link : 'https://t.me/' . $link);
				break;

			case $id === '7' && $tipe === '2':
				$link = ($valid_link !== false ? $link : 'https://t.me/joinchat/' . $link);
				break;

			default:

		return $link;
	}

}
?>
