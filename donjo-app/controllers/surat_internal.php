<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class Surat_internal extends CI_Controller{

	function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('user_model');
		$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
		if($grup!=1 AND $grup!=2 AND $grup!=3 AND $grup!=4) {
			if(empty($grup))
				$_SESSION['request_uri'] = $_SERVER['REQUEST_URI'];
			else
				unset($_SESSION['request_uri']);
			redirect('siteman');
		}
		$this->load->model('header_model');
		$this->load->model('web_dokumen_model');
		$this->modul_ini = 15;
		$this->controller = 'surat_internal';
	}

	function clear(){
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		redirect($this->controller);
	}
	//redirect('surat/form/surat_perjalanan_dinas');
	function index($kat=4, $p=1,$o=0){

		$data['p']        = $p;
		$data['o']        = $o;
		$data['kat']			= $kat;

		if(isset($_SESSION['cari']))
			$data['cari'] = $_SESSION['cari'];
		else $data['cari'] = '';

		if(isset($_SESSION['filter']))
			$data['filter'] = $_SESSION['filter'];
		else $data['filter'] = '';

		if(isset($_POST['per_page']))
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];

		$data['kat_nama'] = $this->web_dokumen_model->kat_nama($kat);
		$data['paging']  = $this->web_dokumen_model->paging($kat, $p, $o);
		$data['main']    = $this->web_dokumen_model->list_data($kat, $o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->web_dokumen_model->autocomplete();
		$data['createSurat'] = site_url('surat/form/surat_perjalanan_dinas');
		$header = $this->header_model->get_data();
		$this->_set_tab($kat);
		$this->load->view('header', $header);
		$this->load->view('sekretariat/nav',$nav);
		$this->load->view('dokumen/surat_internal',$data);
		$this->load->view('footer');
	}

	function search(){
		$cari = $this->input->post('cari');
		$kat = $this->input->post('kategori');
		if($cari!='')
			$_SESSION['cari']=$cari;
		else unset($_SESSION['cari']);
		redirect($this->controller."/index/$kat");
	}

	function filter(){
		$filter = $this->input->post('filter');
		$kat = $this->input->post('kategori');
		if($filter!=0)
			$_SESSION['filter']=$filter;
		else unset($_SESSION['filter']);
		redirect($this->controller."/index/$kat");
	}

	function delete($kat=1,$p=1,$o=0,$id=''){
		$_SESSION['success']=1;
		$this->web_dokumen_model->delete($id);
		redirect($this->controller."/index/$kat/$p/$o");
	}

	function delete_all($kat=1,$p=1,$o=0){
		$_SESSION['success']=1;
		$this->web_dokumen_model->delete_all();
		redirect($this->controller."/index/$kat/$p/$o");
	}


	private function _set_tab($kat){
		switch ($kat) {
			case '2':
				$this->tab_ini = 3;
				break;

			case '3':
				$this->tab_ini = 4;
				break;
			case '4':
				$this->tab_ini = 1;
				break;
			default:
				$this->tab_ini = 3;
				break;
		}
	}

}
