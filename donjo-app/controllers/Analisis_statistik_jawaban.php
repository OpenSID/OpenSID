<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');
/*
 *  File ini:
 *
 * Controller untuk modul Analisis
 *
 * donjo-app/controllers/Analisis_statistik_jawaban.php
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

class Analisis_statistik_jawaban extends Admin_Controller {

	private $_set_page;

	function __construct()
	{
		parent::__construct();
		$this->load->model('analisis_statistik_jawaban_model');
		$this->load->model('analisis_respon_model');

		$_SESSION['submenu'] = "Statistik Jawaban";
		$_SESSION['asubmenu'] = "analisis_statistik_jawaban";
		// TODO : Simpan di pengaturan aplikasi agar bisa disesuaikan oleh pengguna
		$this->_set_page = ['20', '50', '100'];
		$this->modul_ini = 5;
	}

	public function clear()
	{
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		unset($_SESSION['tipe']);
		unset($_SESSION['kategori']);
		unset($_SESSION['dusun']);
		unset($_SESSION['rw']);
		unset($_SESSION['rt']);
		$this->session->per_page = $this->_set_page[0];
		redirect('analisis_statistik_jawaban');
	}

	public function leave()
	{
		$id = $_SESSION['analisis_master'];
		unset($_SESSION['analisis_master']);
		redirect("analisis_master/menu/$id");
	}

	public function index($p=1, $o=0)
	{
		if (empty($this->analisis_respon_model->get_periode()))
		{
			$_SESSION['success'] = -1;
			$_SESSION['error_msg'] = 'Tidak ada periode aktif. Untuk laporan ini harus ada periode aktif.';
			redirect('analisis_periode');
		}
		unset($_SESSION['cari2']);
		$data['p'] = $p;
		$data['o'] = $o;

		if (isset($_SESSION['cari']))
			$data['cari'] = $_SESSION['cari'];
		else $data['cari'] = '';

		if (isset($_SESSION['filter']))
			$data['filter'] = $_SESSION['filter'];
		else $data['filter'] = '';
		if (isset($_SESSION['tipe']))
			$data['tipe'] = $_SESSION['tipe'];
		else $data['tipe'] = '';
		if (isset($_SESSION['kategori']))
			$data['kategori'] = $_SESSION['kategori'];
		else $data['kategori'] = '';
		if (isset($_POST['per_page']))
			$_SESSION['per_page'] = $_POST['per_page'];
		$data['per_page'] = $this->session->per_page;

		if (isset($_SESSION['dusun']))
		{
			$data['dusun'] = $_SESSION['dusun'];
			$data['list_rw'] = $this->analisis_statistik_jawaban_model->list_rw($data['dusun']);

			if (isset($_SESSION['rw']))
			{
				$data['rw'] = $_SESSION['rw'];
				$data['list_rt'] = $this->analisis_statistik_jawaban_model->list_rt($data['dusun'], $data['rw']);

				if (isset($_SESSION['rt']))
					$data['rt'] = $_SESSION['rt'];
				else $data['rt'] = '';
			}
			else $data['rw'] = '';
		}
		else
		{
			$data['dusun'] = '';
			$data['rw'] = '';
			$data['rt'] = '';
		}

		$data['func'] = 'index';
		$data['set_page'] = $this->_set_page;
		$data['paging'] = $this->analisis_statistik_jawaban_model->paging($p,$o);
		$data['main'] = $this->analisis_statistik_jawaban_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->analisis_statistik_jawaban_model->autocomplete();
		$data['analisis_master'] = $this->analisis_statistik_jawaban_model->get_analisis_master();
		$data['list_tipe'] = $this->analisis_statistik_jawaban_model->list_tipe();
		$data['list_kategori'] = $this->analisis_statistik_jawaban_model->list_kategori();
		$data['list_dusun'] = $this->analisis_statistik_jawaban_model->list_dusun();

		$this->set_minsidebar(1);
		$this->render('analisis_statistik_jawaban/table', $data);
	}

	public function grafik_parameter($id='')
	{
		if (isset($_SESSION['dusun']))
		{
			$data['dusun'] = $_SESSION['dusun'];
			$data['list_rw'] = $this->analisis_statistik_jawaban_model->list_rw($data['dusun']);

			if (isset($_SESSION['rw']))
			{
				$data['rw'] = $_SESSION['rw'];
				$data['list_rt'] = $this->analisis_statistik_jawaban_model->list_rt($data['dusun'],$data['rw']);
				if (isset($_SESSION['rt']))
					$data['rt'] = $_SESSION['rt'];
				else $data['rt'] = '';
			}
			else $data['rw'] = '';
		}
		else
		{
			$data['dusun'] = '';
			$data['rw'] = '';
			$data['rt'] = '';
		}
		$data['list_dusun'] = $this->analisis_statistik_jawaban_model->list_dusun();
		$ai = $this->analisis_statistik_jawaban_model->get_analisis_indikator($id);
		$data['analisis_statistik_jawaban'] = $this->analisis_statistik_jawaban_model->get_analisis_indikator($id);
		$data['analisis_master'] = $this->analisis_statistik_jawaban_model->get_analisis_master();
		$data['main'] = $this->analisis_statistik_jawaban_model->list_indikator($id);

		$this->set_minsidebar(1);
		$this->render('analisis_statistik_jawaban/parameter/grafik_table', $data);
	}

	public function subjek_parameter($id='',$par='')
	{
		if (isset($_SESSION['dusun']))
		{
			$data['dusun'] = $_SESSION['dusun'];
			$data['list_rw'] = $this->analisis_statistik_jawaban_model->list_rw($data['dusun']);

			if (isset($_SESSION['rw']))
			{
				$data['rw'] = $_SESSION['rw'];
				$data['list_rt'] = $this->analisis_statistik_jawaban_model->list_rt($data['dusun'], $data['rw']);
				if (isset($_SESSION['rt']))
					$data['rt'] = $_SESSION['rt'];
				else $data['rt'] = '';
			}
			else $data['rw'] = '';
		}
		else
		{
			$data['dusun'] = '';
			$data['rw'] = '';
			$data['rt'] = '';
		}
		$data['list_dusun'] = $this->analisis_statistik_jawaban_model->list_dusun();
		$ai = $this->analisis_statistik_jawaban_model->get_analisis_indikator($id);

		$data['analisis_statistik_pertanyaan'] = $this->analisis_statistik_jawaban_model->get_analisis_indikator($id);
		$data['analisis_statistik_jawaban'] = $this->analisis_statistik_jawaban_model->get_analisis_parameter($par);
		$data['analisis_master'] = $this->analisis_statistik_jawaban_model->get_analisis_master();
		$data['main'] = $this->analisis_statistik_jawaban_model->list_subjek($par);

		$this->set_minsidebar(1);
		$this->render('analisis_statistik_jawaban/parameter/subjek_table', $data);
	}

	public function cetak($o=0)
	{
		$data['main'] = $this->analisis_statistik_jawaban_model->list_data($o, 0, 10000);
		$this->load->view('analisis_statistik_jawaban/table_print', $data);
	}

	public function excel($o=0)
	{
		$data['main'] = $this->analisis_statistik_jawaban_model->list_data($o, 0, 10000);
		$this->load->view('analisis_statistik_jawaban/table_excel', $data);
	}

	public function cetak2($id='', $par='')
	{
		$data['analisis_statistik_pertanyaan'] = $this->analisis_statistik_jawaban_model->get_analisis_indikator($id);
		$data['analisis_statistik_jawaban'] = $this->analisis_statistik_jawaban_model->get_analisis_parameter($par);
		$data['main'] = $this->analisis_statistik_jawaban_model->list_subjek($par);
		$this->load->view('analisis_statistik_jawaban/parameter/table_print', $data);
	}

	public function excel2($id='', $par='')
	{
		$data['analisis_statistik_pertanyaan'] = $this->analisis_statistik_jawaban_model->get_analisis_indikator($id);
		$data['analisis_statistik_jawaban'] = $this->analisis_statistik_jawaban_model->get_analisis_parameter($par);
		$data['main'] = $this->analisis_statistik_jawaban_model->list_subjek($par);
		$this->load->view('analisis_statistik_jawaban/parameter/subjek_excel', $data);
	}

	public function search()
	{
		$cari = $this->input->post('cari');
		if ($cari != '')
			$_SESSION['cari'] = $cari;
		else unset($_SESSION['cari']);
		redirect('analisis_statistik_jawaban');
	}

	public function filter()
	{
		$filter = $this->input->post('filter');
		if ($filter != 0)
			$_SESSION['filter'] = $filter;
		else unset($_SESSION['filter']);
		redirect('analisis_statistik_jawaban');
	}

	public function tipe()
	{
		$filter = $this->input->post('tipe');
		if ($filter != 0)
			$_SESSION['tipe'] = $filter;
		else unset($_SESSION['tipe']);
		redirect('analisis_statistik_jawaban');
	}

	public function kategori()
	{
		$filter = $this->input->post('kategori');
		if ($filter != 0)
			$_SESSION['kategori'] = $filter;
		else unset($_SESSION['kategori']);
		redirect('analisis_statistik_jawaban');
	}

	public function dusun()
	{
		unset($_SESSION['rw']);
		unset($_SESSION['rt']);
		$dusun = $this->input->post('dusun');
		if ($dusun != "")
			$_SESSION['dusun'] = $dusun;
		else unset($_SESSION['dusun']);
		redirect('analisis_statistik_jawaban');
	}

	public function rw()
	{
		unset($_SESSION['rt']);
		$rw = $this->input->post('rw');
		if ($rw != "")
			$_SESSION['rw'] = $rw;
		else unset($_SESSION['rw']);
		redirect('analisis_statistik_jawaban');
	}

	public function rt()
	{
		$rt = $this->input->post('rt');
		if ($rt != "")
			$_SESSION['rt'] = $rt;
		else unset($_SESSION['rt']);
		redirect('analisis_statistik_jawaban');
	}

	public function dusun2($id='', $par='')
	{
		unset($_SESSION['rw']);
		unset($_SESSION['rt']);
		$dusun = $this->input->post('dusun');
		if ($dusun != "")
			$_SESSION['dusun'] = $dusun;
		else unset($_SESSION['dusun']);
		redirect("analisis_statistik_jawaban/subjek_parameter/$id/$par");
	}

	public function rw2($id='', $par='')
	{
		unset($_SESSION['rt']);
		$rw = $this->input->post('rw');
		if ($rw != "")
			$_SESSION['rw'] = $rw;
		else unset($_SESSION['rw']);
		redirect("analisis_statistik_jawaban/subjek_parameter/$id/$par");
	}

	public function rt2($id='', $par='')
	{
		$rt = $this->input->post('rt');
		if ($rt != "")
			$_SESSION['rt'] = $rt;
		else unset($_SESSION['rt']);
		redirect("analisis_statistik_jawaban/subjek_parameter/$id/$par");
	}

	public function dusun3($id='')
	{
		unset($_SESSION['rw']);
		unset($_SESSION['rt']);
		$dusun = $this->input->post('dusun');
		if ($dusun != "")
			$_SESSION['dusun'] = $dusun;
		else unset($_SESSION['dusun']);
		redirect("analisis_statistik_jawaban/grafik_parameter/$id");
	}

	public function rw3($id='')
	{
		unset($_SESSION['rt']);
		$rw = $this->input->post('rw');
		if ($rw != "")
			$_SESSION['rw'] = $rw;
		else unset($_SESSION['rw']);
		redirect("analisis_statistik_jawaban/grafik_parameter/$id");
	}

	public function rt3($id='')
	{
		$rt = $this->input->post('rt');
		if ($rt != "")
			$_SESSION['rt'] = $rt;
		else unset($_SESSION['rt']);
		redirect("analisis_statistik_jawaban/grafik_parameter/$id");
	}

	public function delete($p=1, $o=0, $id='')
	{
		$this->redirect_hak_akses('h', "analisis_statistik_jawaban/index/$p/$o");
		$this->analisis_statistik_jawaban_model->delete($id);
		redirect("analisis_statistik_jawaban/index/$p/$o");
	}

	public function delete_all($p=1, $o=0)
	{
		$this->redirect_hak_akses('h', "analisis_statistik_jawaban/index/$p/$o");
		$this->analisis_statistik_jawaban_model->delete_all();
		redirect("analisis_statistik_jawaban/index/$p/$o");
	}
}
