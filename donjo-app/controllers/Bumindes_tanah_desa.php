<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * File ini:
 *
 * Controller untuk Modul Bumindes Tanah Desa
 *
 * donjo-app/controllers/Bumindes_tanah_desa.php
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

class Bumindes_tanah_desa extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->library('session');
		$this->load->model(['header_model', 'tanah_desa_model', 'pamong_model', 'cdesa_model']);
		$this->controller = 'bumindes_tanah_desa';
		$this->modul_ini = 301;
		$this->sub_modul_ini = 305;
		$this->set_page = ['20', '50', '100'];
	}

	public function index()
	{
		$this->tables("tanah");
	}

	public function tables($page="tanah")
	{
		$data = [
			'selected_nav' => $page,
			'main' => $this->tanah_desa_model->list_tanah_desa(),	
			'main_content' => "bumindes/pembangunan/tanah_di_desa/content_tanah_di_desa",
			'subtitle' => ["Buku Tanah di Desa",0],
		];

		$this->set_minsidebar(1);
		$this->render('bumindes/pembangunan/main', $data);
	}

	public function view_tanah_desa($id)
	{		
		$data = [
			'main' 		   => $this->tanah_desa_model->view_tanah_desa_by_id($id),
			'main_content' => "bumindes/pembangunan/tanah_di_desa/form_tanah_di_desa",
			'subtitle'	   => ["bumindes_tanah_desa/tables/tanah","Buku Tanah di Desa","Rincian Data"],
			'selected_nav' => 'tanah',
			'view_mark'	   => TRUE,
		];
		$this->set_minsidebar(1);
		$this->render('bumindes/pembangunan/main', $data);
	}

	public function form($id = ''){
		if ($id)
		{
			$data = [
				'main' 		   => $this->tanah_desa_model->view_tanah_desa_by_id($id),
				'main_content' => "bumindes/pembangunan/tanah_di_desa/form_tanah_di_desa",				
				'subtitle' 	   => ["bumindes_tanah_desa/tables/tanah","Buku Tanah di Desa","Ubah Data"],
				'selected_nav' => 'tanah',
				'form_action'  => site_url("bumindes_tanah_desa/update_tanah_desa"), 
			];
		}
		else
		{
			$data = [
				'main' 		   => NULL,
				'main_content' => "bumindes/pembangunan/tanah_di_desa/form_tanah_di_desa",
				'subtitle'	   => ["bumindes_tanah_desa/tables/tanah","Buku Tanah di Desa","Isi Data"],
				'selected_nav' => 'tanah',
				'form_action'  => site_url("bumindes_tanah_desa/add_tanah_desa"),
			];

		}

		$this->set_minsidebar(1);
		$this->render('bumindes/pembangunan/main', $data);		
	}

	public function add_tanah_desa()
	{
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('no_sertif','No. Sertifikat','required|trim|numeric');

		if ($this->form_validation->run() != false)
		{
			$data = $this->tanah_desa_model->add_tanah_desa();
			if ($data) $this->session->success = 1;
			else $this->session->success = -1;
		}
		else
		{
			$this->session->success = -1;
			$this->session->error_msg = trim(strip_tags(validation_errors()));
		}

		redirect("bumindes_tanah_desa/tables/tanah");
	}

	public function update_tanah_desa()
	{		
		$data = $this->tanah_desa_model->update_tanah_desa();
		if ($data) $this->session->success = 1;
		else $this->session->success = -1;
		redirect("bumindes_tanah_desa/tables/tanah");
	}

	public function delete_tanah_desa($id)
	{
		$this->redirect_hak_akses('h', 'bumindes_tanah_desa/tables/tanah');
		$data = $this->tanah_desa_model->delete_tanah_desa($id);
		if ($data) $this->session->success = 1;
		else $this->session->success = -1;
		redirect('bumindes_tanah_desa/tables/tanah');
	}

	public function ajax_cetak($aksi = '')
	{
		// pengaturan data untuk dialog cetak/unduh
		$data = [			
			'aksi' => $aksi,
			'form_action' => site_url("bumindes_tanah_desa/cetak_tanah_desa/$aksi"),			
			'isi' => "bumindes/pembangunan/tanah_di_desa/ajax_dialog_tanah_di_desa",
		];

		$this->load->view('global/dialog_cetak', $data);
	}

	public function cetak_tanah_desa($aksi = '')
	{
		$data = [	
			'aksi' => $aksi,		
			'config' => $this->header['desa'],
			'pamong_ketahui' => $this->pamong_model->get_ttd(),
			'pamong_ttd' => $this->pamong_model->get_ub(),
			'main' => $this->tanah_desa_model->cetak_tanah_desa(),
			'bulan' => $this->session->filter_bulan,
			'tahun' => $this->session->filter_tahun,
			'tgl_cetak' => $_POST['tgl_cetak'],	
			'file' => "Buku Tanah di Desa",
			'isi' => "bumindes/pembangunan/tanah_di_desa/tanah_di_desa_cetak",
			'letak_ttd' => ['1', '1', '7'],
		];					
		$this->load->view('global/format_cetak', $data);		
	}
}
