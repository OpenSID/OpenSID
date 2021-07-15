<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * Model untuk anjungan di modul admin Layanan Mandiri
 *
 * donjo-app/models/Anjungan_model.php
 *
 */

/**
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @package	OpenSID
 * @author	Tim Pengembang OpenDesa
 * @copyright	Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright	Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license	http://www.gnu.org/licenses/gpl.html	GPL V3
 * @link 	https://github.com/OpenSID/OpenSID
 */

class Anjungan_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function cek_anjungan()
	{
		if ($this->input->server('HTTP_USER_AGENT') == 'Anjungan OpenSID') return true;

		$ip = $this->input->ip_address();

		$data = $this->db
			->where('ip_address', $ip)
			->where('status', 1)
			->get('anjungan')
			->row_array();

		return $data;
	}

	public function list_data()
	{
		$data = $this->db->order_by('ip_address')
			->get('anjungan')
			->result_array();
		return $data;
	}

	public function insert()
	{
		$data = $this->validasi($this->input->post());
		$data['created_by'] = $this->session->user;
		$data['created_at'] = date('Y-m-d H:i:s');
		$outp = $this->db->insert('anjungan', $data);
		status_sukses($outp);
	}

	private function validasi($post)
	{
		$data['ip_address'] = bilangan_titik($post['ip_address']);
		$data['keterangan'] = htmlentities($post['keterangan']);
		$data['keyboard'] = bilangan($post['keyboard']);
		$data['status'] = bilangan($post['status']);
		$data['updated_by'] = $this->session->user;
		return $data;
	}

	public function delete($id)
	{
		$outp = $this->db->where('id', $id)->delete('anjungan');
		status_sukses($outp);
	}

	public function update($id)
	{
		$data = $this->validasi($this->input->post());
		$data['updated_at'] = date('Y-m-d H:i:s');
		$outp = $this->db->where('id', $id)
			->update('anjungan', $data);
		status_sukses($outp);
	}

	public function get_anjungan($id)
	{
		$data = $this->db->where('id', $id)
			->get('anjungan')->row_array();
		return $data;
	}

	/**
	 * @param $id id
	 * @param $val status : 1 = Unlock, 2 = Lock
	 */
	public function lock($id, $val)
	{
		$outp = $this->db
			->where('id', $id)
			->update('anjungan', ['status' => $val]);
		status_sukses($outp);
	}
}
