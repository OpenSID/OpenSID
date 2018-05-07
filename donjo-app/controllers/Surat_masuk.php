<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class Surat_masuk extends CI_Controller{

	function __construct(){
		parent::__construct();
		session_start();
		// Untuk bisa menggunakan helper force_download()
		$this->load->helper('download');
		$this->load->model('user_model');
		$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
		if($grup!=(1 or 2)) {
			if(empty($grup))
				$_SESSION['request_uri'] = $_SERVER['REQUEST_URI'];
			else
				unset($_SESSION['request_uri']);
			redirect('siteman');
		}
		$this->load->model('surat_masuk_model');
		$this->load->model('config_model');
		$this->load->model('pamong_model');
		$this->load->model('header_model');
		$this->modul_ini = 15;
		$this->tab_ini = 2;
	}

	function clear($id=0){
		$_SESSION['per_page']=20;
		$_SESSION['surat']=$id;
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		redirect('surat_masuk');
	}

	function index($p=1,$o=2){
		$data['p']        = $p;
		$data['o']        = $o;

		if(isset($_SESSION['cari']))
			$data['cari'] = $_SESSION['cari'];
		else $data['cari'] = '';
		if(isset($_SESSION['filter']))
			$data['filter'] = $_SESSION['filter'];
		else $data['filter'] = '';
		if(isset($_POST['per_page']))
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];
		$data['paging']  = $this->surat_masuk_model->paging($p,$o);
		$data['main']    = $this->surat_masuk_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['pamong'] = $this->pamong_model->list_semua();
		$data['tahun_penerimaan'] = $this->surat_masuk_model->list_tahun_penerimaan();
		$data['keyword'] = $this->surat_masuk_model->autocomplete();
		$header = $this->header_model->get_data();

		$this->load->view('header', $header);
		$this->load->view('sekretariat/nav',$nav);
		$this->load->view('surat_masuk/table',$data);
		$this->load->view('footer');
	}

	function form($p=1,$o=0,$id=''){

		$data['pengirim'] = $this->surat_masuk_model->autocomplete_pengirim();
		$data['p'] = $p;
		$data['o'] = $o;

		if($id){
			$data['surat_masuk'] = $this->surat_masuk_model->get_surat_masuk($id);
			$data['form_action'] = site_url("surat_masuk/update/$p/$o/$id");
		}
		else{
			$data['surat_masuk'] = null;
			$data['form_action'] = site_url("surat_masuk/insert");
		}
		$data['ref_disposisi'] = $this->surat_masuk_model->get_pengolah_disposisi();

		$header = $this->header_model->get_data();

		// Buang unique id pada link nama file
		$berkas = explode('__sid__', $data['surat_masuk']['berkas_scan']);
		$namaFile = $berkas[0];
		$ekstensiFile = explode('.', end($berkas));
		$ekstensiFile = end($ekstensiFile);
		$data['surat_masuk']['berkas_scan'] = $namaFile.'.'.$ekstensiFile;

		$this->load->view('header', $header);
		$nav['act']=$this->tab_ini;
		$this->load->view('sekretariat/nav',$nav);
		$this->load->view('surat_masuk/form',$data);
		$this->load->view('footer');
	}

	function form_upload($p=1,$o=0,$url=''){
		$data['form_action'] = site_url("surat_masuk/upload/$p/$o/$url");
		$this->load->view('surat_masuk/ajax-upload',$data);
	}

	function search(){
		$cari = $this->input->post('cari');
		if($cari!='')
			$_SESSION['cari']=$cari;
		else unset($_SESSION['cari']);
		redirect('surat_masuk');
	}

	function filter(){
		$filter = $this->input->post('filter');
		if($filter!=0) $_SESSION['filter']=$filter;
		else unset($_SESSION['filter']);
		redirect('surat_masuk');
	}

	function insert(){
		$this->surat_masuk_model->insert();
		redirect('surat_masuk');
	}

	function update($p=1,$o=0,$id=''){
		$this->surat_masuk_model->update($id);
		redirect("surat_masuk/index/$p/$o");
	}

	function upload($p=1,$o=0,$url=''){
		$this->surat_masuk_model->upload($url);
		redirect("surat_masuk/index/$p/$o");
	}

	function delete($p=1,$o=0,$id=''){
		$this->surat_masuk_model->delete($id);
		redirect("surat_masuk/index/$p/$o");
	}

	function delete_all($p=1,$o=0){
		$this->surat_masuk_model->delete_all();
		redirect("surat_masuk/index/$p/$o");
	}

	function cetak($o=0){
		$data['input'] = $_POST;
		$data['desa'] = $this->config_model->get_data();
		$data['main'] = $this->surat_masuk_model->list_data($o,0, 10000);
		$this->load->view('surat_masuk/surat_masuk_print',$data);
	}

	function excel($o=0){
		$data['input'] = $_POST;
		$data['desa'] = $this->config_model->get_data();
		$data['main'] = $this->surat_masuk_model->list_data($o,0, 10000);
		$this->load->view('surat_masuk/surat_masuk_excel',$data);
	}

	function disposisi($id){
		$data['input'] = $_POST;
		$data['desa'] = $this->config_model->get_data();
		$data['ref_disposisi'] = $this->surat_masuk_model->get_pengolah_disposisi();
		$data['surat'] = $this->surat_masuk_model->get_surat_masuk($id);
		$this->load->view('surat_masuk/disposisi',$data);
	}

	/**
	 * Unduh berkas scan berdasarkan kolom surat_masuk.id
	 * @param   integer  $idSuratMasuk  Id berkas scan pada koloam surat_masuk.id
	 * @return  void
	 */
	public function unduh_berkas_scan($idSuratMasuk)
	{
		// Ambil nama berkas dari database
		$berkas = $this->surat_masuk_model->getNamaBerkasScan($idSuratMasuk);
        ambilBerkas($berkas, 'surat_masuk', '__sid__');
	}
}
