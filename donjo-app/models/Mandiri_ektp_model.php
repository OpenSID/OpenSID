<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*
 *  File ini:
 *
 * Model untuk modul Layanan Mandiri
 *
 * donjo-app/models/Mandiri_ektp_model.php
 *
 */
/*
 *  File ini bagian dari:
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

class Mandiri_ektp_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('anjungan_model');
		$this->cek_anjungan = $this->anjungan_model->cek_anjungan();
	}

	#Login Layanan Mandiri E-KTP

	public function siteman()
	{
		$masuk = $this->input->post();
		$pin = hash_pin(bilangan($masuk['pin']));
		$tag = bilangan(bilangan($masuk['tag']));

		$data = $this->db
						->select('pm.*, p.nama, p.nik, p.tag_id_card, p.foto, p.kk_level, p.id_kk, k.no_kk')
						->from('tweb_penduduk_mandiri pm')
						->join('tweb_penduduk p', 'pm.id_pend = p.id', 'left')
						->join('tweb_keluarga k', 'p.id_kk = k.id', 'left')
						->where('p.tag_id_card', $tag)
						->get()
						->row();

		switch (true)
		{
			case ($data && $this->cek_anjungan && $tag == $data->tag_id_card):
				$session = [
					'mandiri' => 1,
					'is_login' => $data
				];
				$this->session->set_userdata($session);
				break;

			case ($data && ! $this->cek_anjungan && $tag == $data->tag_id_card && $pin == $data->pin):
				$session = [
					'mandiri' => 1,
					'is_login' => $data
				];
				$this->session->set_userdata($session);
				break;

			case ($this->session->mandiri_try > 2):
				$this->session->mandiri_try = $this->session->mandiri_try - 1;
				break;

			default:
				$this->session->mandiri_wait = 1;
				break;
		}
	}

}
