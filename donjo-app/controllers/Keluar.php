<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Keluar extends CI_Controller{

	function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('user_model');
		$this->load->model('surat_keluar_model');
		$this->load->model('surat_model');
		$this->grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
		if($this->grup!=1 AND $this->grup!=2 AND $this->grup!=3) {
			if(empty($grup))
				$_SESSION['request_uri'] = $_SERVER['REQUEST_URI'];
			else
				unset($_SESSION['request_uri']);
			redirect('siteman');
		}
		$this->load->model('header_model');
        $this->load->helper('download');
		$this->modul_ini = 4;
	}

	function clear(){
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		unset($_SESSION['nik']);
		$_SESSION['per_page'] = 20;
		redirect('keluar');
	}

	function index($p=1,$o=0){

		$data['p']        = $p;
		$data['o']        = $o;

		if(isset($_SESSION['cari']))
			$data['cari'] = $_SESSION['cari'];
		else $data['cari'] = '';

		if(isset($_SESSION['filter']))
			$data['filter'] = $_SESSION['filter'];
		else $data['filter'] = '';

		if(isset($_POST['per_page']))
			$_SESSION['per_page'] = $_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];

		$data['paging']  = $this->surat_keluar_model->paging($p,$o);
		$data['main']    = $this->surat_keluar_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->surat_keluar_model->autocomplete();

		$header = $this->header_model->get_data();

		$nav['act']= 2;
		$this->load->view('header', $header);
		$this->load->view('surat/nav',$nav);
		$this->load->view('surat/surat_keluar',$data);
		$this->load->view('footer');
	}

	function delete($p=1,$o=0,$id=''){
		session_error_clear();
		if($this->grup!=1) {
			session_error('Anda tidak mempunyai izin melakukan ini');
			redirect("keluar/index/$p/$o"); // Batasi hanya admin yang boleh hapus
		}
		$this->surat_keluar_model->delete($id);
		redirect("keluar/index/$p/$o");
	}

	function search(){
		$cari = $this->input->post('cari');
		if($cari!='')
			$_SESSION['cari']=$cari;
		else unset($_SESSION['cari']);
		redirect('keluar');
	}

	function perorangan($nik=0,$p=1,$o=0){
		if(isset($_POST['nik'])){
			//$data['individu']=$this->surat_model->get_penduduk($_POST['nik']);
			$nik=$_POST['nik'];
		}
		if($nik<>0){
			$data['individu']=$this->surat_model->get_penduduk($nik);
		}else{
			$data['individu']=null;
		}

		$data['p']        	= $p;
		$data['o']      	= $o;

		if(isset($_POST['per_page']))
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];


		$data['paging']  = $this->surat_keluar_model->paging_perorangan($nik,$p,$o);
		$data['main']    = $this->surat_keluar_model->list_data_surat($nik,$o, $data['paging']->offset, $data['paging']->per_page);

		$data['penduduk'] = $this->surat_model->list_penduduk();
		$data['form_action'] = site_url("sid_surat_keluar/perorangan/$nik");
		$data['nik']['no']=$nik;
		$nav['act']= 2;
		$header = $this->header_model->get_data();

		$this->load->view('header',$header);

		$this->load->view('surat/nav',$nav);
		$this->load->view('surat/surat_keluar_perorangan',$data);
		$this->load->view('footer');
	}

	function graph(){
		$data['form_action'] = site_url("sid_cetak_surat/print_surat_ket_pengantar");
		$nav['act']= 2;
		$header = $this->header_model->get_data();

		$data['stat']  = $this->surat_keluar_model->grafik();
		$this->load->view('header',$header);

		$this->load->view('surat/nav',$nav);
		$this->load->view('surat/surat_keluar_graph',$data);
		$this->load->view('footer');

	}

	function filter(){
		$filter = $this->input->post('nik');
		if($filter!=0)
			$_SESSION['filter']=$filter;
		else unset($_SESSION['filter']);
		redirect('keluar/perorangan');
	}

	function nik(){
		$nik = $this->input->post('nik');
		if($nik!=0)
			$_SESSION['nik']=$_POST['nik'];
		else unset($_SESSION['nik']);
		redirect('keluar/perorangan');
	}

    function cetak_surat_keluar($id){
        $berkas = $this->db->select('nama_surat')->where('id', $id)->get('log_surat')->row();
        ambilBerkas($berkas->nama_surat, 'keluar');
    }
    function unduh_lampiran($id){
        $berkas = $this->db->select('lampiran')->where('id', $id)->get('log_surat')->row();
        ambilBerkas($berkas->lampiran, 'keluar');
    }
}
