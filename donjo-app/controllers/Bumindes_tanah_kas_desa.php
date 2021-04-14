<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * File ini:
 *
 * Controller untuk Modul Bumindes Tanah Kas Desa
 *
 * donjo-app/controllers/Bumindes_tanah_kas_desa.php
 *
 */

/*
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

 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.

 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package OpenSID
 * @author  Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license http://www.gnu.org/licenses/gpl.html  GPL V3
 * @link  https://github.com/OpenSID/OpenSID
 */

class Bumindes_tanah_kas_desa extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->library('session');
		$this->load->model(['header_model', 'tanah_kas_desa_model', 'pamong_model', 'cdesa_model']);
		$this->controller = 'bumindes_tanah_kas_desa';
		$this->modul_ini = 301;
		$this->sub_modul_ini = 305;
	}

	public function index()
	{
		$this->tables("tanah_kas");
	}

	public function tables($page="tanah_kas")
	{
		$data = [
			'selected_nav' => $page,
			'main_content' => "bumindes/pembangunan/tanah_kas_desa/content_tanah_kas_desa",
			'subtitle' => ["Buku Tanah kas Desa",0],
			'main' => $this->tanah_kas_desa_model->list_tanah_kas_desa(),
		];

		$this->set_minsidebar(1);
		$this->render('bumindes/pembangunan/main', $data);
	}

	public function view_tanah_kas_desa($id)
	{
		$data = [
			'main' 		   => $this->tanah_kas_desa_model->view_tanah_kas_desa_by_id($id),
			'main_content' => "bumindes/pembangunan/tanah_kas_desa/form_tanah_kas_desa",
			'subtitle'	   => ["bumindes_tanah_kas_desa/tables/tanah_kas","Buku Tanah Kas Desa","Rincian Data"],
			'selected_nav' => 'tanah_kas',
			'view_mark'	   => TRUE,
		];

		$this->set_minsidebar(1);
		$this->render('bumindes/pembangunan/main', $data);
	}

	public function form($id = ''){
		if ($id)
		{
			$data = [
				'main' 		   => $this->tanah_kas_desa_model->view_tanah_kas_desa_by_id($id),
				'main_content' => "bumindes/pembangunan/tanah_kas_desa/form_tanah_kas_desa",
				'subtitle'	   => ["bumindes_tanah_kas_desa/tables/tanah_kas","Buku Tanah Kas Desa","Ubah Data"],
				'selected_nav' => 'tanah_kas',
				'form_action'  => site_url("bumindes_tanah_kas_desa/update_tanah_kas_desa"), 
			];
		}
		else
		{
			$data = [
				'main' 		   => NULL,
				'main_content' => "bumindes/pembangunan/tanah_kas_desa/form_tanah_kas_desa",
				'subtitle'	   => ["bumindes_tanah_kas_desa/tables/tanah_kas","Buku Tanah Kas Desa","Isi Data"],
				'selected_nav' => 'tanah_kas',
				'form_action'  => site_url("bumindes_tanah_kas_desa/add_tanah_kas_desa"),
			];

		}

		$this->set_minsidebar(1);
		$this->render('bumindes/pembangunan/main', $data);		
	}

	public function add_tanah_kas_desa()
	{
		$data = $this->tanah_kas_desa_model->add_tanah_kas_desa();

		if ($data) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
		redirect("bumindes_tanah_kas_desa/tables/tanah_kas");
	}

	public function update_tanah_kas_desa()
	{		
		$data = $this->tanah_kas_desa_model->update_tanah_kas_desa();
		if ($data) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
		redirect("bumindes_tanah_kas_desa/tables/tanah_kas");
	}

	public function delete_tanah_kas_desa($id)
	{
		$this->redirect_hak_akses('h', 'bumindes_tanah_kas_desa/tables/tanah_kas');
		$data = $this->tanah_kas_desa_model->delete_tanah_kas_desa($id);
		if ($data) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
		redirect('bumindes_tanah_kas_desa/tables/tanah_kas');
	}

	public function ajax_cetak_tanah_kas_desa($aksi = '')
	{
		// pengaturan data untuk dialog cetak/unduh
		$data = [			
			'aksi' => $aksi,
			'form_action' => site_url("bumindes_tanah_kas_desa/cetak_tanah_kas_desa/$aksi"),			
			'isi' => "bumindes/pembangunan/tanah_kas_desa/ajax_dialog_tanah_kas_desa",
		];

		$this->load->view('global/dialog_cetak', $data);
	}

	public function cetak_tanah_kas_desa($aksi)
	{
		$data = [	
			'aksi' => $aksi,		
			'config' => $this->header['desa'],
			'pamong_ketahui' => $this->pamong_model->get_ttd(),
			'pamong_ttd' => $this->pamong_model->get_ub(),
			'main' => $this->tanah_kas_desa_model->cetak_tanah_kas_desa(),
			'bulan' => $this->session->filter_bulan,
			'tahun' => $this->session->filter_tahun,
			'tgl_cetak' => $_POST['tgl_cetak'],	
			'file' => "Buku Tanah Kas Desa",
			'isi' => "bumindes/pembangunan/tanah_kas_desa/tanah_kas_desa_cetak",
			'letak_ttd' => ['1', '1', '8'],
		];		
		$this->load->view('global/format_cetak', $data);		
	}
}
