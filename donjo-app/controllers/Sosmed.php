<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 *  File ini:
 *
 * Controller untuk modul Sosial Media Web
 *
 * donjo-app/controllers/Sosmed.php
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

class Sosmed extends Admin_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->model('web_sosmed_model');
		$this->modul_ini = 13;
		$this->sub_modul_ini = 53;
	}

	public function index()
	{
		$sosmed = $this->session->userdata('sosmed');

		if(!$sosmed) $sosmed = 'facebook';

		$data['media'] = $sosmed;
		$data['main'] = $this->web_sosmed_model->get_sosmed($sosmed);
		$data['list_sosmed'] = $this->web_sosmed_model->list_sosmed();
		$data['form_action'] = site_url("sosmed/update/$sosmed");

		$this->session->unset_userdata('sosmed');

		$this->render('sosmed/sosmed', $data);
	}

	public function tab($sosmed)
	{
		$this->session->set_userdata('sosmed', $sosmed);

		redirect('sosmed');
	}

	public function update($sosmed)
	{
		$this->web_sosmed_model->update($sosmed);
		$redirect = (!empty($sosmed)) ? "sosmed/tab/$sosmed" : "sosmed";
		redirect($redirect);
	}
}
