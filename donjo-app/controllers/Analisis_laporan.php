<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class Analisis_laporan extends Admin_Controller {

	function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('analisis_laporan_model');
		$this->load->model('analisis_respon_model');
		$this->load->model('header_model');
		$_SESSION['submenu'] = "Laporan Analisis";
		$_SESSION['asubmenu'] = "analisis_laporan";
		$this->modul_ini = 5;
	}

	public function clear()
	{
		unset($_SESSION['cari']);
		unset($_SESSION['klasifikasi']);
		unset($_SESSION['dusun']);
		unset($_SESSION['rw']);
		unset($_SESSION['rt']);
		unset($_SESSION['jawab']);
		$_SESSION['per_page'] = 50;
		redirect('analisis_laporan');
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

		if (isset($_SESSION['klasifikasi']))
			$data['klasifikasi'] = $_SESSION['klasifikasi'];
		else $data['klasifikasi'] = '';

		if (isset($_SESSION['dusun']))
		{
			$data['dusun'] = $_SESSION['dusun'];
			$data['list_rw'] = $this->analisis_laporan_model->list_rw($data['dusun']);

			if (isset($_SESSION['rw']))
			{
				$data['rw'] = $_SESSION['rw'];
				$data['list_rt'] = $this->analisis_laporan_model->list_rt($data['dusun'], $data['rw']);
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
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];

		$data['list_dusun'] = $this->analisis_laporan_model->list_dusun();
		$data['list_klasifikasi'] = $this->analisis_laporan_model->list_klasifikasi();
		$data['paging']  = $this->analisis_laporan_model->paging($p,$o);
		$data['main']    = $this->analisis_laporan_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->analisis_laporan_model->autocomplete();
		$data['analisis_master'] = $this->analisis_laporan_model->get_analisis_master();
		$data['analisis_periode'] = $this->analisis_laporan_model->get_periode();
		$header = $this->header_model->get_data();
		$nav['act'] = 5;
		$header['minsidebar'] = 1;
		$this->load->view('header', $header);
		$this->load->view('nav');
		$this->load->view('analisis_laporan/table', $data);
		$this->load->view('footer');
	}

	public function kuisioner($p=1, $o=0, $id=''){
		$data['p'] = $p;
		$data['o'] = $o;

		$data['analisis_master'] = $this->analisis_laporan_model->get_analisis_master();
		$data['subjek'] = $this->analisis_laporan_model->get_subjek($id);
		$data['total'] = $this->analisis_laporan_model->get_total($id);

		$this->load->model('analisis_respon_model');
		$data['list_bukti'] = $this->analisis_respon_model->list_bukti($id);
		$data['list_anggota'] = $this->analisis_respon_model->list_anggota($id);
		$data['list_jawab'] = $this->analisis_laporan_model->list_indikator($id);
		$data['form_action'] = site_url("analisis_laporan/update_kuisioner/$p/$o/$id");

		$header = $this->header_model->get_data();
		$nav['act'] = 5;
		$header['minsidebar'] = 1;
		$this->load->view('header', $header);
		$this->load->view('nav');
		$this->load->view('analisis_laporan/form', $data);
		$this->load->view('footer');
	}

	public function cetak($o=0)
	{
		$data['main'] = $this->analisis_laporan_model->list_data($o, 0, 10000);
		$this->load->view('analisis_laporan/table_print',$data);
	}

	public function excel($o=0)
	{
		$data['main'] = $this->analisis_laporan_model->list_data($o, 0, 10000);
		$this->load->view('analisis_laporan/table_excel',$data);
	}

	public function multi_jawab(){
		$data['form_action'] = site_url("analisis_laporan/multi_exec");
		$data['main'] = $this->analisis_laporan_model->multi_jawab(1, 1);
		$this->load->view('analisis_laporan/ajax_multi',$data);
	}

	public function multi_exec()
	{
		$idcb = $_POST['id_cb'];
		print_r($idcb);
		//redirect('analisis_laporan');
	}

	public function ajax_multi_jawab()
	{
		if (isset($_SESSION['jawab']))
    {
      $data['jawab'] = $_SESSION['jawab'];
    }
		else
    {
      $data['jawab'] = '';
    }
		$data['main']    		= $this->analisis_laporan_model->multi_jawab(1, 1);
		$data['form_action'] = site_url("analisis_laporan/multi_jawab_proses");
		$this->load->view("analisis_laporan/ajax_multi", $data);
	}

	public function multi_jawab_proses()
	{
		if (isset($_POST['id_cb']))
		{
			unset($_SESSION['jawab']);
			unset($_SESSION['jmkf']);
			$id_cb = $_POST['id_cb'];
			$cb = "";
			if (count($id_cb))
			{
				foreach ($id_cb as $id)
				{
					$cb .= $id.",";
				}
			}
			$_SESSION['jawab'] = $cb."7777777";

			$jmkf    		= $this->analisis_laporan_model->group_parameter();
			$_SESSION['jmkf'] = count($jmkf);
		}
		redirect('analisis_laporan');
	}

	public function dusun()
	{
		unset($_SESSION['rw']);
		unset($_SESSION['rt']);
		$dusun = $this->input->post('dusun');
		if ($dusun != "")
			$_SESSION['dusun'] = $dusun;
		else unset($_SESSION['dusun']);
		redirect('analisis_laporan');
	}

	public function rw()
	{
		unset($_SESSION['rt']);
		$rw = $this->input->post('rw');
		if ($rw != "")
			$_SESSION['rw']=$rw;
		else unset($_SESSION['rw']);
		redirect('analisis_laporan');
	}

	public function rt()
	{
		$rt = $this->input->post('rt');
		if ($rt != "")
			$_SESSION['rt'] = $rt;
		else unset($_SESSION['rt']);
		redirect('analisis_laporan');
	}

	public function klasifikasi()
	{
		$klasifikasi = $this->input->post('klasifikasi');
		if ($klasifikasi != "")
			$_SESSION['klasifikasi']=$klasifikasi;
		else unset($_SESSION['klasifikasi']);
		redirect('analisis_laporan');
	}

	public function search()
	{
		$cari = $this->input->post('cari');
		if ($cari != '')
			$_SESSION['cari'] = $cari;
		else unset($_SESSION['cari']);
		redirect('analisis_laporan');
	}

}
