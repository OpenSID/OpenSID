<?php
/**
 * File ini:
 *
 * Controller halaman beranda untuk dashboard Admin
 *
 * donjo-app/controllers/Hom_sid.php
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

defined('BASEPATH') OR exit('No direct script access allowed');

class Hom_sid extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('program_bantuan_model');
		$this->load->model('surat_model');
		$this->load->model('notif_model');
		$this->load->library('release');
		$this->modul_ini = 1;
	}

	public function index()
	{
		if ($this->release->has_internet_connection())
		{
			$this->release->set_api_url('https://api.github.com/repos/opensid/opensid/releases/latest')
				->set_interval(7)
				->set_cache_folder(FCPATH.'desa');

			$data['update_available'] = $this->release->is_available();
			$data['current_version'] = $this->release->get_current_version();
			$data['latest_version'] = $this->release->get_latest_version();
			$data['release_name'] = $this->release->get_release_name();
			$data['release_body'] = $this->release->get_release_body();
		}

		// Pengambilan data penduduk untuk ditampilkan widget Halaman Dashboard (modul Home SID)
		$data['penduduk'] = $this->header_model->penduduk_total();
		$data['keluarga'] = $this->header_model->keluarga_total();
		$data['bantuan'] = $this->header_model->bantuan_total();
		$data['kelompok'] = $this->header_model->kelompok_total();
		$data['rtm'] = $this->header_model->rtm_total();
		$data['dusun'] = $this->header_model->dusun_total();
		$data['jumlah_surat'] = $this->surat_model->surat_total();

		$this->render('home/desa', $data);
	}

	public function dialog_pengaturan()
	{
		$data['list_program_bantuan'] = $this->program_bantuan_model->list_program();
		$data['sasaran'] = unserialize(SASARAN);
		$data['form_action'] = site_url("hom_sid/ubah_program_bantuan");
		$this->load->view('home/pengaturan_form', $data);
	}

	public function ubah_program_bantuan()
	{
		$this->db->where('key','dashboard_program_bantuan')->update('setting_aplikasi', array('value'=>$this->input->post('program_bantuan')));
		redirect('hom_sid');
	}
}
