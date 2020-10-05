<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');
/*
 *  File ini:
 *
 * Controller untuk modul Analisis
 *
 * donjo-app/controllers/Analisis_respon.php
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

class Analisis_respon extends Admin_Controller {

	function __construct()
	{
		parent::__construct();
		UNSET($_SESSION['delik']);
		$this->load->model('analisis_respon_model');

		$_SESSION['submenu'] = "Input Data";
		$_SESSION['asubmenu'] = "analisis_respon";
		$this->modul_ini = 5;
	}

	public function clear()
	{
		unset($_SESSION['cari']);
		unset($_SESSION['dusun']);
		unset($_SESSION['rw']);
		unset($_SESSION['rt']);
		unset($_SESSION['isi']);
		$_SESSION['per_page'] = 50;
		redirect('analisis_respon');
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
			$_SESSION['error_msg'] = 'Tidak ada periode aktif. Entri data respon harus ada periode aktif.';
			redirect('analisis_periode');
		}
		unset($_SESSION['cari2']);
		$data['p']        = $p;
		$data['o']        = $o;

		if (isset($_SESSION['cari']))
			$data['cari'] = $_SESSION['cari'];
		else $data['cari'] = '';

		if (isset($_SESSION['isi']))
			$data['isi'] = $_SESSION['isi'];
		else $data['isi'] = '';

		if (isset($_SESSION['dusun']))
		{
			$data['dusun'] = $_SESSION['dusun'];
			$data['list_rw'] = $this->analisis_respon_model->list_rw($data['dusun']);

			if (isset($_SESSION['rw']))
			{
				$data['rw'] = $_SESSION['rw'];
				$data['list_rt'] = $this->analisis_respon_model->list_rt($data['dusun'], $data['rw']);
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

		if (isset($_POST['per_page']))
			$_SESSION['per_page'] = $_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];

		$data['list_dusun'] = $this->analisis_respon_model->list_dusun();
		$data['paging'] = $this->analisis_respon_model->paging($p, $o);
		$data['main'] = $this->analisis_respon_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->analisis_respon_model->autocomplete();
		$data['analisis_master'] = $this->analisis_respon_model->get_analisis_master();
		$data['analisis_periode'] = $this->analisis_respon_model->get_periode();
		$this->set_minsidebar(1);
		$this->render('analisis_respon/table', $data);
	}

	public function kuisioner($p=1, $o=0, $id='', $fs=0)
	{
		if ($fs == 1)
			$_SESSION['fullscreen'] = 1;

		if($fs == 2)
			unset($_SESSION['fullscreen']);

		if ($fs != 0)
			redirect("analisis_respon/kuisioner/$p/$o/$id");

		$data['p'] = $p;
		$data['o'] = $o;
		$data['id'] = $id;

		$data['analisis_master'] = $this->analisis_respon_model->get_analisis_master();
		$data['subjek'] = $this->analisis_respon_model->get_subjek($id);
		$data['list_jawab'] = $this->analisis_respon_model->list_indikator($id);
		$data['list_bukti'] = $this->analisis_respon_model->list_bukti($id);
		$data['list_anggota'] = $this->analisis_respon_model->list_anggota($id);
		$data['form_action'] = site_url("analisis_respon/update_kuisioner/$p/$o/$id");

		$this->set_minsidebar(1);		if (isset($_SESSION['fullscreen']))
			$data['layarpenuh']= 1;
		else
		{
			$data['layarpenuh']= 2;
		}

		$this->render('analisis_respon/form', $data);
	}

	public function update_kuisioner($p=1, $o=0, $id='')
	{
		$this->analisis_respon_model->update_kuisioner($id);
		redirect("analisis_respon/kuisioner/$p/$o/$id");
	}

	//CHILD--------------------
	public function kuisioner_child($p=1, $o=0, $id='', $idc='')
	{
		$data['p'] = $p;
		$data['o'] = $o;

		$data['list_jawab'] = $this->analisis_respon_model->list_indikator_child($idc);
		$data['form_action'] = site_url("analisis_respon/update_kuisioner_child/$p/$o/$id/$idc");

		$this->load->view('analisis_respon/form_ajax', $data);
	}

	public function update_kuisioner_child($p=1, $o=0, $id='', $idc='')
	{
		$per = $this->analisis_respon_model->get_periode_child();
		$this->analisis_respon_model->update_kuisioner($idc, $per);
		redirect("analisis_respon/kuisioner/$p/$o/$id");
	}

	public function aturan_unduh()
	{
		$data['main'] = $this->analisis_respon_model->aturan_unduh();
		$this->load->view('analisis_respon/import/aturan_unduh', $data);
	}

	public function data_ajax()
	{
		$this->load->view('analisis_respon/import/data_ajax');
	}

	public function data_unduh($p=0, $o=0)
	{
		$data['main'] = $this->analisis_respon_model->data_unduh($p, $o);
		$data['periode'] = $this->analisis_respon_model->get_aktif_periode();
		$data['indikator'] = $this->analisis_respon_model->indikator_unduh($p, $o);
		$this->load->view('analisis_respon/import/data_unduh', $data);
	}

	public function import($op=0){
		$data['form_action'] = site_url("analisis_respon/import_proses/$op");
		$this->load->view('analisis_respon/import/import', $data);
	}

	public function import_proses($op=0)
	{
		$this->analisis_respon_model->import_respon($op);
		redirect('analisis_respon');
	}

	public function search()
	{
		$cari = $this->input->post('cari');
		if ($cari != '')
			$_SESSION['cari'] = $cari;
		else unset($_SESSION['cari']);
		redirect('analisis_respon');
	}

	public function isi()
	{
		$isi = $this->input->post('isi');
		if ($isi != "")
			$_SESSION['isi'] = $isi;
		else unset($_SESSION['isi']);
		redirect('analisis_respon');
	}

	public function dusun()
	{
		unset($_SESSION['rw']);
		unset($_SESSION['rt']);
		$dusun = $this->input->post('dusun');
		if ($dusun != "")
			$_SESSION['dusun'] = $dusun;
		else unset($_SESSION['dusun']);
		redirect('analisis_respon');
	}

	public function rw()
	{
		unset($_SESSION['rt']);
		$rw = $this->input->post('rw');
		if ($rw != "")
			$_SESSION['rw'] = $rw;
		else unset($_SESSION['rw']);
		redirect('analisis_respon');
	}

	public function rt()
	{
		$rt = $this->input->post('rt');
		if ($rt != "")
			$_SESSION['rt'] = $rt;
		else unset($_SESSION['rt']);
		redirect('analisis_respon');
	}

	public function form_impor_bdt(){
		$data['form_action'] = site_url("analisis_respon/impor_bdt/");
		$this->load->view('analisis_respon/import/impor_bdt', $data);
	}

	public function impor_bdt()
	{
		$this->load->model('bdt_model');
		$this->bdt_model->impor();
	}

	public function unduh_form_bdt()
	{
		header("location:".base_url('assets/import/contoh-data-bdt2015.xls'));
	}
}
