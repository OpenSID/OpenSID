<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');
class Analisis_statistik_jawaban extends CI_Controller{
	function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('analisis_statistik_jawaban_model');
		$this->load->model('user_model');
		$this->load->model('header_model');
		$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
		if($grup!=1) {
			if(empty($grup))
				$_SESSION['request_uri'] = $_SERVER['REQUEST_URI'];
			else
				unset($_SESSION['request_uri']);
			redirect('siteman');
		}
		$_SESSION['submenu'] = "Statistik Jawaban";
		$_SESSION['asubmenu'] = "analisis_statistik_jawaban";
		$this->modul_ini = 5;
	}
	function clear(){
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		unset($_SESSION['tipe']);
		unset($_SESSION['kategori']);
		unset($_SESSION['dusun']);
		unset($_SESSION['rw']);
		unset($_SESSION['rt']);
		redirect('analisis_statistik_jawaban');
	}
	function leave(){
		$id=$_SESSION['analisis_master'];
		unset($_SESSION['analisis_master']);
		redirect("analisis_master/menu/$id");
	}
	function index($p=1,$o=0){
		unset($_SESSION['cari2']);
		$data['p']        = $p;
		$data['o']        = $o;

		if(isset($_SESSION['cari']))
			$data['cari'] = $_SESSION['cari'];
		else $data['cari'] = '';

		if(isset($_SESSION['filter']))
			$data['filter'] = $_SESSION['filter'];
		else $data['filter'] = '';
		if(isset($_SESSION['tipe']))
			$data['tipe'] = $_SESSION['tipe'];
		else $data['tipe'] = '';
		if(isset($_SESSION['kategori']))
			$data['kategori'] = $_SESSION['kategori'];
		else $data['kategori'] = '';
		if(isset($_POST['per_page']))
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];

		if(isset($_SESSION['dusun'])){
			$data['dusun'] = $_SESSION['dusun'];
			$data['list_rw'] = $this->analisis_statistik_jawaban_model->list_rw($data['dusun']);

		if(isset($_SESSION['rw'])){
			$data['rw'] = $_SESSION['rw'];
			$data['list_rt'] = $this->analisis_statistik_jawaban_model->list_rt($data['dusun'],$data['rw']);

		if(isset($_SESSION['rt']))
			$data['rt'] = $_SESSION['rt'];
			else $data['rt'] = '';

			}else $data['rw'] = '';

		}else{
			$data['dusun'] = '';
			$data['rw'] = '';
			$data['rt'] = '';
		}

		$data['paging']  = $this->analisis_statistik_jawaban_model->paging($p,$o);
		$data['main']    = $this->analisis_statistik_jawaban_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->analisis_statistik_jawaban_model->autocomplete();
		$data['analisis_master'] = $this->analisis_statistik_jawaban_model->get_analisis_master();
		$data['list_tipe'] = $this->analisis_statistik_jawaban_model->list_tipe();
		$data['list_kategori'] = $this->analisis_statistik_jawaban_model->list_kategori();
		$data['list_dusun'] = $this->analisis_statistik_jawaban_model->list_dusun();
		$header = $this->header_model->get_data();

		$this->load->view('header', $header);
		$this->load->view('analisis_master/nav');
		$this->load->view('analisis_statistik_jawaban/table',$data);
		$this->load->view('footer');
	}
	function form($p=1,$o=0,$id=''){
		$data['p'] = $p;
		$data['o'] = $o;

		if($id){
			$data['analisis_statistik_jawaban']        = $this->analisis_statistik_jawaban_model->get_analisis_indikator($id);
			$data['form_action'] = site_url("analisis_statistik_jawaban/update/$p/$o/$id");
		}

		else{
			$data['analisis_statistik_jawaban']        = null;
			$data['form_action'] = site_url("analisis_statistik_jawaban/insert");
		}

		$data['list_kategori'] = $this->analisis_statistik_jawaban_model->list_kategori();
		$header = $this->header_model->get_data();
		$data['analisis_master'] = $this->analisis_statistik_jawaban_model->get_analisis_master();

		$this->load->view('header', $header);
		$this->load->view('analisis_master/nav');
		$this->load->view('analisis_statistik_jawaban/form',$data);
		$this->load->view('footer');
	}
	function parameter($id=''){
		$ai  = $this->analisis_statistik_jawaban_model->get_analisis_indikator($id);
		if($ai['id_tipe']==3 OR $ai['id_tipe']==4)
		redirect('analisis_statistik_jawaban');

		$data['analisis_statistik_jawaban']        = $this->analisis_statistik_jawaban_model->get_analisis_indikator($id);
		$data['analisis_master'] = $this->analisis_statistik_jawaban_model->get_analisis_master();
		$data['main']        = $this->analisis_statistik_jawaban_model->list_indikator($id);

		$header = $this->header_model->get_data();

		$this->load->view('header', $header);
		$this->load->view('analisis_master/nav');
		$this->load->view('analisis_statistik_jawaban/parameter/table',$data);
		$this->load->view('footer');
	}
	function grafik_parameter($id=''){
		if(isset($_SESSION['dusun'])){
			$data['dusun'] = $_SESSION['dusun'];
			$data['list_rw'] = $this->analisis_statistik_jawaban_model->list_rw($data['dusun']);

		if(isset($_SESSION['rw'])){
			$data['rw'] = $_SESSION['rw'];
			$data['list_rt'] = $this->analisis_statistik_jawaban_model->list_rt($data['dusun'],$data['rw']);

		if(isset($_SESSION['rt']))
			$data['rt'] = $_SESSION['rt'];
			else $data['rt'] = '';

			}else $data['rw'] = '';

		}else{
			$data['dusun'] = '';
			$data['rw'] = '';
			$data['rt'] = '';
		}
		$data['list_dusun'] = $this->analisis_statistik_jawaban_model->list_dusun();

		$ai  = $this->analisis_statistik_jawaban_model->get_analisis_indikator($id);

		//redirect('analisis_statistik_jawaban');

		$data['analisis_statistik_jawaban']        = $this->analisis_statistik_jawaban_model->get_analisis_indikator($id);
		$data['analisis_master'] = $this->analisis_statistik_jawaban_model->get_analisis_master();
		$data['main']        = $this->analisis_statistik_jawaban_model->list_indikator($id);

		$header = $this->header_model->get_data();

		$this->load->view('header', $header);
		$this->load->view('analisis_master/nav');
		$this->load->view('analisis_statistik_jawaban/parameter/grafik_table',$data);
		$this->load->view('footer');
	}
	function subjek_parameter($id='',$par=''){
		if(isset($_SESSION['dusun'])){
			$data['dusun'] = $_SESSION['dusun'];
			$data['list_rw'] = $this->analisis_statistik_jawaban_model->list_rw($data['dusun']);

		if(isset($_SESSION['rw'])){
			$data['rw'] = $_SESSION['rw'];
			$data['list_rt'] = $this->analisis_statistik_jawaban_model->list_rt($data['dusun'],$data['rw']);

		if(isset($_SESSION['rt']))
			$data['rt'] = $_SESSION['rt'];
			else $data['rt'] = '';

			}else $data['rw'] = '';

		}else{
			$data['dusun'] = '';
			$data['rw'] = '';
			$data['rt'] = '';
		}
		$data['list_dusun'] = $this->analisis_statistik_jawaban_model->list_dusun();

		$ai  = $this->analisis_statistik_jawaban_model->get_analisis_indikator($id);
		//if($ai['id_tipe']==3 OR $ai['id_tipe']==4)
		//	redirect('analisis_statistik_jawaban');

		$data['analisis_statistik_pertanyaan']  = $this->analisis_statistik_jawaban_model->get_analisis_indikator($id);
		$data['analisis_statistik_jawaban']   	= $this->analisis_statistik_jawaban_model->get_analisis_parameter($par);
		$data['analisis_master'] 				= $this->analisis_statistik_jawaban_model->get_analisis_master();
		$data['main']        					= $this->analisis_statistik_jawaban_model->list_subjek($par);

		$header = $this->header_model->get_data();

		$this->load->view('header', $header);
		$this->load->view('analisis_master/nav');
		$this->load->view('analisis_statistik_jawaban/parameter/subjek_table',$data);
		$this->load->view('footer');
	}
	function cetak($o=0){
		$data['main']    = $this->analisis_statistik_jawaban_model->list_data($o,0, 10000);
		$this->load->view('analisis_statistik_jawaban/table_print',$data);
	}
	function excel($o=0){
		$data['main']    = $this->analisis_statistik_jawaban_model->list_data($o,0, 10000);
		$this->load->view('analisis_statistik_jawaban/table_excel',$data);
	}

	function cetak2($id='',$par=''){
		$data['analisis_statistik_pertanyaan']  = $this->analisis_statistik_jawaban_model->get_analisis_indikator($id);
		$data['analisis_statistik_jawaban']   	= $this->analisis_statistik_jawaban_model->get_analisis_parameter($par);
		$data['main']    = $this->analisis_statistik_jawaban_model->list_subjek($par);
		$this->load->view('analisis_statistik_jawaban/parameter/table_print',$data);
	}
	function excel2($id='',$par=''){
		$data['analisis_statistik_pertanyaan']  = $this->analisis_statistik_jawaban_model->get_analisis_indikator($id);
		$data['analisis_statistik_jawaban']   	= $this->analisis_statistik_jawaban_model->get_analisis_parameter($par);
		$data['main']    = $this->analisis_statistik_jawaban_model->list_subjek($par);
		$this->load->view('analisis_statistik_jawaban/parameter/subjek_excel',$data);
	}

	function search(){
		$cari = $this->input->post('cari');
		if($cari!='')
			$_SESSION['cari']=$cari;
		else unset($_SESSION['cari']);
		redirect('analisis_statistik_jawaban');
	}
	function filter(){
		$filter = $this->input->post('filter');
		if($filter!=0)
			$_SESSION['filter']=$filter;
		else unset($_SESSION['filter']);
		redirect('analisis_statistik_jawaban');
	}
	function tipe(){
		$filter = $this->input->post('tipe');
		if($filter!=0)
			$_SESSION['tipe']=$filter;
		else unset($_SESSION['tipe']);
		redirect('analisis_statistik_jawaban');
	}
	function kategori(){
		$filter = $this->input->post('kategori');
		if($filter!=0)
			$_SESSION['kategori']=$filter;
		else unset($_SESSION['kategori']);
		redirect('analisis_statistik_jawaban');
	}
	function dusun(){
		unset($_SESSION['rw']);
		unset($_SESSION['rt']);
		$dusun = $this->input->post('dusun');
		if($dusun!="")
			$_SESSION['dusun']=$dusun;
		else unset($_SESSION['dusun']);
		redirect('analisis_statistik_jawaban');
	}
	function rw(){
		unset($_SESSION['rt']);
		$rw = $this->input->post('rw');
		if($rw!="")
			$_SESSION['rw']=$rw;
		else unset($_SESSION['rw']);
		redirect('analisis_statistik_jawaban');
	}
	function rt(){
		$rt = $this->input->post('rt');
		if($rt!="")
			$_SESSION['rt']=$rt;
		else unset($_SESSION['rt']);
		redirect('analisis_statistik_jawaban');
	}
	function dusun2($id='',$par=''){
		unset($_SESSION['rw']);
		unset($_SESSION['rt']);
		$dusun = $this->input->post('dusun');
		if($dusun!="")
			$_SESSION['dusun']=$dusun;
		else unset($_SESSION['dusun']);
		redirect("analisis_statistik_jawaban/subjek_parameter/$id/$par");
	}
	function rw2($id='',$par=''){
		unset($_SESSION['rt']);
		$rw = $this->input->post('rw');
		if($rw!="")
			$_SESSION['rw']=$rw;
		else unset($_SESSION['rw']);
		redirect("analisis_statistik_jawaban/subjek_parameter/$id/$par");
	}
	function rt2($id='',$par=''){
		$rt = $this->input->post('rt');
		if($rt!="")
			$_SESSION['rt']=$rt;
		else unset($_SESSION['rt']);
		redirect("analisis_statistik_jawaban/subjek_parameter/$id/$par");
	}
	function dusun3($id=''){
		unset($_SESSION['rw']);
		unset($_SESSION['rt']);
		$dusun = $this->input->post('dusun');
		if($dusun!="")
			$_SESSION['dusun']=$dusun;
		else unset($_SESSION['dusun']);
		redirect("analisis_statistik_jawaban/grafik_parameter/$id");
	}
	function rw3($id=''){
		unset($_SESSION['rt']);
		$rw = $this->input->post('rw');
		if($rw!="")
			$_SESSION['rw']=$rw;
		else unset($_SESSION['rw']);
		redirect("analisis_statistik_jawaban/grafik_parameter/$id");
	}
	function rt3($id=''){
		$rt = $this->input->post('rt');
		if($rt!="")
			$_SESSION['rt']=$rt;
		else unset($_SESSION['rt']);
		redirect("analisis_statistik_jawaban/grafik_parameter/$id");
	}
	function insert(){
		$this->analisis_statistik_jawaban_model->insert();
		redirect('analisis_statistik_jawaban');
	}
	function update($p=1,$o=0,$id=''){
		$this->analisis_statistik_jawaban_model->update($id);
		redirect("analisis_statistik_jawaban/index/$p/$o");
	}
	function delete($p=1,$o=0,$id=''){
		$this->analisis_statistik_jawaban_model->delete($id);
		redirect("analisis_statistik_jawaban/index/$p/$o");
	}
	function delete_all($p=1,$o=0){
		$this->analisis_statistik_jawaban_model->delete_all();
		redirect("analisis_statistik_jawaban/index/$p/$o");
	}
	function p_insert($in=''){
		$this->analisis_statistik_jawaban_model->p_insert($in);
		redirect("analisis_statistik_jawaban/parameter/$in");
	}
	function p_update($in='',$id=''){
		$this->analisis_statistik_jawaban_model->p_update($id);
		redirect("analisis_statistik_jawaban/parameter/$in");
	}
	function p_delete($in='',$id=''){
		$this->analisis_statistik_jawaban_model->p_delete($id);
		redirect("analisis_statistik_jawaban/parameter/$in");
	}
	function p_delete_all(){
		$this->analisis_statistik_jawaban_model->p_delete_all();
		redirect("analisis_statistik_jawaban/parameter/$in");
	}
}
