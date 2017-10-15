<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class First extends CI_Controller{

	function __construct() {
		parent::__construct();	
		session_start();
			
		mandiri_timeout();
		
		$this->load->model('header_model');
		$this->load->model('config_model');
		$this->load->model('first_keluarga_m');
		$this->load->model('first_m');
		$this->load->model('first_artikel_m');
		$this->load->model('first_gallery_m');
		$this->load->model('first_menu_m');
		$this->load->model('first_penduduk_m');	
		$this->load->model('penduduk_model');	
		$this->load->model('surat_model');	
		$this->load->model('surat_keluar_model');		

	}
	
	function auth(){
		if($_SESSION['mandiri_wait']!=1){
			$this->first_m->siteman();
		}
		if($_SESSION['mandiri']==1)
			redirect('first/mandiri/1/1');
		else
			redirect('first');
	}
	
	function mobile($user="",$pass=""){
		$token = $this->first_m->m_siteman();
		return $token;
	}
	
	function logout(){
		$this->first_m->logout();
		redirect('first');
	}
	
	function ganti(){
		$this->first_m->ganti();
		redirect('first');
	}
	
	function index($p=1){
		$data['p'] = $p;
		$data['desa'] = $this->first_m->get_data();
		$data['menu_atas'] = $this->first_menu_m->list_menu_atas();
		$data['menu_kiri'] = $this->first_menu_m->list_menu_kiri();
		$data['headline'] = $this->first_artikel_m->get_headline();
		$data['teks_berjalan'] = $this->first_artikel_m->get_teks_berjalan();
		
		$data['paging']  = $this->first_artikel_m->paging($p);
		$data['artikel'] = $this->first_artikel_m->artikel_show(0,$data['paging']->offset,$data['paging']->per_page);
		
		$data['arsip'] = $this->first_artikel_m->arsip_show();
		$data['komen'] = $this->first_artikel_m->komentar_show();
		$data['agenda'] = $this->first_artikel_m->agenda_show();
		$data['slide'] = $this->first_artikel_m->slide_show();
		
		$data['stat'] = $this->first_penduduk_m->list_data(4);
		$data['sosmed'] = $this->first_artikel_m->list_sosmed();
		$data['w_gal']  = $this->first_gallery_m->gallery_widget();
		$data['w_cos']  = $this->first_artikel_m->cos_widget();
		$data['data_config'] = $this->config_model->get_data();
;
		$this->load->view('layouts/main.tpl.php',$data);		
	}
	
        function cetak_biodata($id=''){
		
		$data['desa'] = $this->header_model->get_data();
		$data['penduduk'] = $this->penduduk_model->get_penduduk($id);
		$this->load->view('sid/kependudukan/cetak_biodata',$data);
	}

	function mandiri($p=1,$m=0){
		if($_SESSION['mandiri']!=1){
			redirect('first');
		}else{
			$data['p'] = $p;
			$data['desa'] = $this->first_m->get_data();
			$data['menu_atas'] = $this->first_menu_m->list_menu_atas();
			$data['menu_kiri'] = $this->first_menu_m->list_menu_kiri();
			$data['headline'] = $this->first_artikel_m->get_headline();
			$data['teks_berjalan'] = $this->first_artikel_m->get_teks_berjalan();
			
			//$data['paging']  = $this->first_artikel_m->paging($p);
			//$data['artikel'] = $this->first_artikel_m->artikel_show(0,$data['paging']->offset,$data['paging']->per_page);
			
			$data['penduduk'] = $this->penduduk_model->get_penduduk($_SESSION['id']);
			$data['arsip'] = $this->first_artikel_m->arsip_show();
			$data['komen'] = $this->first_artikel_m->komentar_show();
			$data['agenda'] = $this->first_artikel_m->agenda_show();
			$data['slide'] = $this->first_artikel_m->slide_show();
			
			$data['stat'] = $this->first_penduduk_m->list_data(4);
			$data['sosmed'] = $this->first_artikel_m->list_sosmed();
			$data['w_gal']  = $this->first_gallery_m->gallery_widget();
			$data['w_cos']  = $this->first_artikel_m->cos_widget();
			$data['data_config'] = $this->config_model->get_data();
				
			$data['list_dokumen'] = $this->penduduk_model->list_dokumen($_SESSION['id']);
			$data['list_kelompok'] = $this->penduduk_model->list_kelompok($_SESSION['id']);
			
			//if($m == 2)
				$data['surat_keluar']    = $this->surat_keluar_model->list_data_surat($_SESSION['id']);

			//$data['menu_surat2'] = $this->surat_model->list_surat2();
			$data['m'] = $m;
			$this->load->view('layouts/mandiri.php',$data);		
		}
	}
	
	function artikel($id="",$p=1) {
		$id = explode("-",$id);
		$id = $id[0];
		$data['p'] = $p;
		$data['desa'] = $this->first_m->get_data();
		
		$data['paging']  = $this->first_artikel_m->paging($p);
		$data['artikel'] = $this->first_artikel_m->list_artikel(0,$data['paging']->offset,$data['paging']->per_page);

		$data['teks_berjalan'] = $this->first_artikel_m->get_teks_berjalan();
		$data['menu_atas'] = $this->first_menu_m->list_menu_atas();
		$data['menu_kiri'] = $this->first_menu_m->list_menu_kiri();
		$data['komentar'] = $this->first_artikel_m->list_komentar($id);
		$data['sosmed'] = $this->first_artikel_m->list_sosmed();
		$data['single_artikel'] = $this->first_artikel_m->get_artikel($id);
		$data['arsip'] = $this->first_artikel_m->arsip_show();
		$data['komen'] = $this->first_artikel_m->komentar_show();
		$data['agenda'] = $this->first_artikel_m->agenda_show();
		$data['slide'] = $this->first_artikel_m->slide_show();
		$data['stat'] = $this->first_penduduk_m->list_data(5);
		$data['w_gal']  = $this->first_gallery_m->gallery_widget();
		$data['w_cos']  = $this->first_artikel_m->cos_widget();
		
		$data['data_config'] = $this->config_model->get_data();
		$this->load->view('layouts/artikel.tpl.php',$data);
	}
	
	function arsip($p=1) {
		$data['p'] = $p;
		$data['paging']  = $this->first_artikel_m->paging_arsip($p);
	
		$data['teks_berjalan'] = $this->first_artikel_m->get_teks_berjalan();
		$data['desa'] = $this->first_m->get_data();
		$data['menu_atas'] = $this->first_menu_m->list_menu_atas();
		$data['menu_kiri'] = $this->first_menu_m->list_menu_kiri();
		$data['sosmed'] = $this->first_artikel_m->list_sosmed();
		$data['farsip'] = $this->first_artikel_m->full_arsip($data['paging']->offset,$data['paging']->per_page);
		$data['arsip'] = $this->first_artikel_m->arsip_show();
		$data['komen'] = $this->first_artikel_m->komentar_show();
		$data['agenda'] = $this->first_artikel_m->agenda_show();
		$data['slide'] = $this->first_artikel_m->slide_show();
		$data['stat'] = $this->first_penduduk_m->list_data(5);
		$data['w_gal']  = $this->first_gallery_m->gallery_widget();
		$data['w_cos']  = $this->first_artikel_m->cos_widget();
		$data['data_config'] = $this->config_model->get_data();


		$this->load->view('layouts/arsip.tpl.php',$data);				
	}
	
	function gallery($p=1){
		$data['p'] = $p;
		
		$data['desa'] = $this->first_m->get_data();

		$data['teks_berjalan'] = $this->first_artikel_m->get_teks_berjalan();
		$data['paging']  = $this->first_artikel_m->paging($p);
		$data['artikel'] = $this->first_artikel_m->artikel_show(0,$data['paging']->offset,$data['paging']->per_page);
		
		$data['menu_atas'] = $this->first_menu_m->list_menu_atas();
		$data['menu_kiri'] = $this->first_menu_m->list_menu_kiri();
		$data['arsip'] = $this->first_artikel_m->arsip_show();
		$data['komen'] = $this->first_artikel_m->komentar_show();
		$data['agenda'] = $this->first_artikel_m->agenda_show();
		$data['slide'] = $this->first_artikel_m->slide_show();
		$data['sosmed'] = $this->first_artikel_m->list_sosmed();
		
		$data['paging']  = $this->first_gallery_m->paging($p);
		$data['gallery'] = $this->first_gallery_m->gallery_show($data['paging']->offset,$data['paging']->per_page);
		
		$data['stat'] = $this->first_penduduk_m->list_data(6);
		$data['w_gal']  = $this->first_gallery_m->gallery_widget();
		$data['w_cos']  = $this->first_artikel_m->cos_widget();
		$data['data_config'] = $this->config_model->get_data();
		$this->load->view('layouts/gallery.tpl.php',$data);				
	}

	function sub_gallery($gal=0,$p=1){
		$data['p'] = $p;
		$data['gal'] = $gal;
		$data['desa'] = $this->first_m->get_data();

		$data['paging']  = $this->first_gallery_m->paging($p);
		$data['gallery'] = $this->first_gallery_m->gallery_show($data['paging']->offset,$data['paging']->per_page);

		$data['teks_berjalan'] = $this->first_artikel_m->get_teks_berjalan();
		$data['menu_atas'] = $this->first_menu_m->list_menu_atas();
		$data['menu_kiri'] = $this->first_menu_m->list_menu_kiri();
		
		$data['paging']  = $this->first_gallery_m->paging2($gal,$p);
		$data['gallery'] = $this->first_gallery_m->sub_gallery_show($gal,$data['paging']->offset,$data['paging']->per_page);
		
		$data['parrent'] = $this->first_gallery_m->get_parrent($gal);
		$data['arsip'] = $this->first_artikel_m->arsip_show();
		$data['komen'] = $this->first_artikel_m->komentar_show();
		$data['agenda'] = $this->first_artikel_m->agenda_show();
		$data['slide'] = $this->first_artikel_m->slide_show();
		$data['sosmed'] = $this->first_artikel_m->list_sosmed();
		
		$data['stat'] = $this->first_penduduk_m->list_data(4);
		$data['w_gal']  = $this->first_gallery_m->gallery_widget();
		$data['w_cos']  = $this->first_artikel_m->cos_widget();
		$data['data_config'] = $this->config_model->get_data();
		$data['mode']= 1;
		$this->load->view('layouts/sub_gallery.tpl.php',$data);				
	}
		
	function statistik($stat="",$tipe=0){
		
		switch($stat){
			case "pendidikan-dalam-kk":$data['heading']="Pendidikan";break;
			case "pekerjaan":$data['heading']="Pekerjaan";break;
			case "status-perkawinan":$data['heading']="Status Perkawinan";break;
			case "agama":$data['heading']="Agama";break;
			case "jenis-kelamin":$data['heading']="Jenis Kelamin";break;
			case "golongan-darah":$data['heading']="Golongan Darah";break;
			case "kelompok-umur":$data['heading']="Kelompok Umur";break;
			case "warga-negara":$data['heading']="Warga Negara";break;
			case "wilayah":redirect("first/wilayah");break;
			case "pendidikan-ditempuh":$data['heading']="Pendidikan Sedang Ditempuh";break;
			
			default:$data['heading']="";redirect("first");break;
		}
				
		$data['teks_berjalan'] = $this->first_artikel_m->get_teks_berjalan();
		$data['slide'] = $this->first_artikel_m->slide_show();
		$data['desa'] = $this->first_m->get_data();
		$data['menu_atas'] = $this->first_menu_m->list_menu_atas();
		$data['menu_kiri'] = $this->first_menu_m->list_menu_kiri();
		$data['stat'] = $this->first_penduduk_m->list_data($stat);
		$data['tipe'] = $tipe;

		$data['sosmed'] = $this->first_artikel_m->list_sosmed();
		$data['arsip'] = $this->first_artikel_m->arsip_show();
		$data['w_cos']  = $this->first_artikel_m->cos_widget();
		
		$data['data_config'] = $this->config_model->get_data();
		$data['st'] = $stat;
		
		$this->load->view('layouts/stat.tpl.php',$data);				
	}
	
	function data_analisis($stat="",$sb=0,$per=0){
		
		$data['teks_berjalan'] = $this->first_artikel_m->get_teks_berjalan();
		$data['slide'] = $this->first_artikel_m->slide_show();
		$data['desa'] = $this->first_m->get_data();
		$data['menu_atas'] = $this->first_menu_m->list_menu_atas();
		$data['menu_kiri'] = $this->first_menu_m->list_menu_kiri();
		
		if($stat == ""){
			$data['list_indikator'] = $this->first_penduduk_m->list_indikator();
			$data['list_jawab'] = null;
			$data['indikator'] = null;
		}else{
			$data['list_indikator'] = "";
			$data['list_jawab'] = $this->first_penduduk_m->list_jawab($stat,$sb,$per);
			$data['indikator'] = $this->first_penduduk_m->get_indikator($stat);
			
		}
		$data['sosmed'] = $this->first_artikel_m->list_sosmed();
		$data['arsip'] = $this->first_artikel_m->arsip_show();
		$data['w_cos']  = $this->first_artikel_m->cos_widget();
		
		$data['data_config'] = $this->config_model->get_data();
		
		$this->load->view('layouts/analisis.tpl.php',$data);				
	}
	
	
	function wilayah(){
	
		$data['teks_berjalan'] = $this->first_artikel_m->get_teks_berjalan();
		$data['main']    = $this->first_penduduk_m->wilayah();
		$data['heading']="Populasi Per Wilayah";
		$data['desa'] = $this->first_m->get_data();
		$data['menu_atas'] = $this->first_menu_m->list_menu_atas();
		$data['menu_kiri'] = $this->first_menu_m->list_menu_kiri();

		$data['slide'] = $this->first_artikel_m->slide_show();
		$data['sosmed'] = $this->first_artikel_m->list_sosmed();
		$data['arsip'] = $this->first_artikel_m->arsip_show();
		$data['w_cos']  = $this->first_artikel_m->cos_widget();

		$data['tipe'] = 3;
		
		$data['total'] = $this->first_penduduk_m->total();
		$data['st'] = 1;
		$data['data_config'] = $this->config_model->get_data();
		$this->load->view('layouts/stat.tpl.php',$data);	
	}

	
	function statistik_k($tipex=0) {
	
		$data['tipe'] = 2;
		$data['tipex'] = $tipex;
	
		$data['desa'] = $this->first_m->get_data();


		$data['teks_berjalan'] = $this->first_artikel_m->get_teks_berjalan();
		$data['menu_atas'] = $this->first_menu_m->list_menu_atas();
		$data['menu_kiri'] = $this->first_menu_m->list_menu_kiri();

		$data['slide'] = $this->first_artikel_m->slide_show();
		$data['sosmed'] = $this->first_artikel_m->list_sosmed();
		$data['arsip'] = $this->first_artikel_m->arsip_show();
		$data['w_cos']  = $this->first_artikel_m->cos_widget();
		$data['stat'] = $this->first_penduduk_m->list_data(4);

		$data['main'] = $this->first_keluarga_m->list_raskin($tipex);
		$data['data_config'] = $this->config_model->get_data();
		$this->load->view('layouts/stat.tpl.php',$data);
	}
	
	function agenda($stat=0) {
		$data['desa'] = $this->first_m->get_data();
		$data['menu_atas'] = $this->first_menu_m->list_menu_atas();
		$data['menu_kiri'] = $this->first_menu_m->list_menu_kiri();
		$data['artikel'] = $this->first_artikel_m->agenda_show();
		$data['arsip'] = $this->first_artikel_m->arsip_show();
		$data['komen'] = $this->first_artikel_m->komentar_show();
		$data['agenda'] = $this->first_artikel_m->agenda_show();
		$data['sosmed'] = $this->first_artikel_m->list_sosmed();
		$data['stat'] = $this->first_penduduk_m->list_data(4);
		$data['data_config'] = $this->config_model->get_data();
		
		$this->load->view('layouts/main.tpl.php',$data);				
	}
	
	function kategori($kat=0,$p=0){
		
		$data['p'] = $p;
		$data['desa'] = $this->first_m->get_data();
		$data['menu_atas'] = $this->first_menu_m->list_menu_atas();
		$data['menu_kiri'] = $this->first_menu_m->list_menu_kiri();
		$data['headline'] = null;
		
		$data['teks_berjalan'] = $this->first_artikel_m->get_teks_berjalan();
		$data['paging']  = $this->first_artikel_m->paging_kat($p,$kat);
		$data['artikel'] = $this->first_artikel_m->list_artikel($data['paging']->offset,$data['paging']->per_page,$kat);

		$data['arsip'] = $this->first_artikel_m->arsip_show();
		$data['komen'] = $this->first_artikel_m->komentar_show();
		$data['agenda'] = $this->first_artikel_m->agenda_show();
		$data['slide'] = $this->first_artikel_m->slide_show();
		$data['stat'] = $this->first_penduduk_m->list_data(4);
		$data['sosmed'] = $this->first_artikel_m->list_sosmed();
		$data['w_gal']  = $this->first_gallery_m->gallery_widget();
		$data['w_cos']  = $this->first_artikel_m->cos_widget();
		
		$data["judul_kategori"] = $this->first_artikel_m->get_kategori($kat);
		
		$data['data_config'] = $this->config_model->get_data();
		$this->load->view('layouts/main.tpl.php',$data);
	}	
	
	function add_comment($id=0) {
		$this->first_artikel_m->insert_comment($id);
		$data['data_config'] = $this->config_model->get_data();
		if($id!=775)
		redirect("first/artikel/$id");
		else{
		$_SESSION['sukses']=1;	
		redirect("first/mandiri/1/3");	
		}
	}
	
	function randomap($id=0) {
		$this->penduduk_model->randomap();
	}
	
}
