<?php

/*
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package    OpenSID
 * @author     Tim Pengembang OpenDesa
 * @copyright  Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright  Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html	GPL V3
 *
 * @link       https://github.com/OpenSID/OpenSID
 */

class Web_sosmed_model extends CI_Model
{
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
		return $this->db->get('media_sosial')->result_array();
	}

	public function get_id($sosmed)
	{
		$list_sosmed = $this->list_sosmed();

		foreach ($list_sosmed as $list) {
			$nama = str_replace(' ', '-', strtolower($list['nama']));

			if ($nama === $sosmed) {
				return $list['id'];
			}
		}
	}

	public function update($sosmed)
	{
		$id = $this->get_id($sosmed);

		$data = $this->input->post();
		$link = trim(strip_tags($this->input->post('link')));

		switch ($id) {
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

	/**
	 * Tampilkan link sosmed
	 * Ditampilkan sesuai jenis sosmed
	 */
	public function link_sosmed(int $id = 0, string $link = '', int $tipe = 1): string
	{
		if (empty($link)) {
			return $link;
		}

		// validasi link
		$valid_link = filter_var($link, FILTER_VALIDATE_URL);

		switch (true) {
			case $id === 1 && $tipe === 1:
				$link = ($valid_link !== false ? $valid_link : 'https://web.facebook.com/' . $link);
				break;

			case $id === 1 && $tipe === 2:
				$link = ($valid_link !== false ? $valid_link : 'https://web.facebook.com/groups/' . $link);
				break;

			case $id === 2:
				$link = ($valid_link !== false ? $valid_link : 'https://twitter.com/' . $link);
				break;

			case $id === 4:
				$link = ($valid_link !== false ? $valid_link : 'https://www.youtube.com/channel/' . $link);
				break;

			case $id === 5:
				$link = ($valid_link !== false ? $valid_link : 'https://www.instagram.com/' . $link . '/');
				break;

			case $id === 6 && $tipe === 1:
				$link = ($valid_link !== false ? $valid_link : 'https://api.whatsapp.com/send?phone=' . $link);
				break;

			case $id === 6 && $tipe === 2:
				$link = ($valid_link !== false ? $valid_link : 'https://chat.whatsapp.com/' . $link);
				break;

			case $id === 7 && $tipe === 1:
				$link = ($valid_link !== false ? $valid_link : 'https://t.me/' . $link);
				break;

			case $id === 7 && $tipe === 2:
				$link = ($valid_link !== false ? $valid_link : 'https://t.me/joinchat/' . $link);
				break;

			default:
				break;
		}

		return $link;
	}
}
