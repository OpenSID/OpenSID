<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class Dokumen_sekretariat extends CI_Controller{

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
		$this->controller = 'dokumen_sekretariat';
	}

	function clear(){
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		redirect('dokumen_sekretariat');
	}

	function index($kat=1, $p=1,$o=0){

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

		$header = $this->header_model->get_data();
		$this->_set_tab($kat);
		$this->load->view('header', $header);
		$this->load->view('sekretariat/nav',$nav);
		$this->load->view('dokumen/table',$data);
		$this->load->view('footer');
	}

	function form($kat=1,$p=1,$o=0,$id=''){

		$data['p'] = $p;
		$data['o'] = $o;
		$data['kat'] = $kat;
		$data['list_kategori'] = $this->web_dokumen_model->list_kategori();

		if($id){
			$data['dokumen']     = $this->web_dokumen_model->get_dokumen($id);
			$data['form_action'] = site_url("dokumen_sekretariat/update/$kat/$id/$p/$o");
		}
		else{
			$data['dokumen']     = null;
			$data['form_action'] = site_url("dokumen_sekretariat/insert");
		}

		$header = $this->header_model->get_data();

		$this->_set_tab($kat);
		$this->load->view('header', $header);
		$this->load->view('sekretariat/nav',$nav);
		$this->load->view('dokumen/form',$data);
		$this->load->view('footer');
	}

	function search(){
		$cari = $this->input->post('cari');
		$kat = $this->input->post('kategori');
		if($cari!='')
			$_SESSION['cari']=$cari;
		else unset($_SESSION['cari']);
		redirect("dokumen_sekretariat/index/$kat");
	}

	function filter(){
		$filter = $this->input->post('filter');
		$kat = $this->input->post('kategori');
		if($filter!=0)
			$_SESSION['filter']=$filter;
		else unset($_SESSION['filter']);
		redirect("dokumen_sekretariat/index/$kat");
	}

	function insert(){
		$_SESSION['success']=1;
		$kat = $this->input->post('kategori');
		$outp = $this->web_dokumen_model->insert();
		if (!$outp) $_SESSION['success']=-1;
		redirect("dokumen_sekretariat/index/$kat");
	}

	function update($kat,$id='',$p=1,$o=0){
		$_SESSION['success']=1;
		$kategori = $this->input->post('kategori');
		if (!empty($kategori))
			$kat = $this->input->post('kategori');
		$outp = $this->web_dokumen_model->update($id);
		if (!$outp) $_SESSION['success']=-1;
		redirect("dokumen_sekretariat/index/$kat/$p/$o");
	}

	function delete($kat=1,$p=1,$o=0,$id=''){
		$_SESSION['success']=1;
		$this->web_dokumen_model->delete($id);
		redirect("dokumen_sekretariat/index/$kat/$p/$o");
	}

	function delete_all($kat=1,$p=1,$o=0){
		$_SESSION['success']=1;
		$this->web_dokumen_model->delete_all();
		redirect("dokumen_sekretariat/index/$kat/$p/$o");
	}

	function dokumen_lock($kat=1,$id=''){
		$this->web_dokumen_model->dokumen_lock($id,1);
		redirect("dokumen_sekretariat/index/$kat/$p/$o");
	}

	function dokumen_unlock($kat=1,$id=''){
		$this->web_dokumen_model->dokumen_lock($id,2);
		redirect("dokumen_sekretariat/index/$kat/$p/$o");
	}

	function dialog_cetak($kat=1)
	{
		redirect("dokumen/dialog_cetak/$kat");
	}

	function dialog_excel($kat=1)
	{
		redirect("dokumen/dialog_excel/$kat");
	}


	private function _set_tab($kat){
		switch ($kat) {
			case '2':
				$this->tab_ini = 3;
				break;

			case '3':
				$this->tab_ini = 4;
				break;

			default:
				$this->tab_ini = 3;
				break;
		}
	}
}
