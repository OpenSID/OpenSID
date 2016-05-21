<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Analisis extends CI_Controller{

        function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('user_model');
		$this->load->model('analisis_model');
		$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
		if($grup!=1 AND $grup!=2 AND $grup!=3) redirect('siteman');
		$this->load->model('header_model');
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
		
		if(isset($_SESSION['rentang1']))
			$data['rentang1'] = $_SESSION['rentang1'];
		else $data['rentang1'] = '';
		
		if(isset($_SESSION['rentang2']))
			$data['rentang2'] = $_SESSION['rentang2'];
		else $data['rentang2'] = '';
		
		if(isset($_SESSION['jenis'])){
			$data['jenis'] = $_SESSION['jenis'];}
		else {$data['jenis'] = '1';
		$_SESSION['jenis']='1';}
			
		if(isset($_SESSION['bulan'])){
			$data['bulan'] = $_SESSION['bulan'];}
		else {$data['bulan'] = date("m");
		$_SESSION['bulan'] = date("m");}
		
		if(isset($_SESSION['tahun'])){
			$data['tahun'] = $_SESSION['tahun'];}
		else {$data['tahun'] = date("Y");
		$_SESSION['tahun'] = date("Y");}
	
		if(isset($_POST['per_page'])) 
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];
		
		if(isset($_SESSION['dusun'])){
			$data['dusun'] = $_SESSION['dusun'];
			$data['list_rw'] = $this->analisis_model->list_rw($data['dusun']);
			
		if(isset($_SESSION['rw'])){
			$data['rw'] = $_SESSION['rw'];
			$data['list_rt'] = $this->analisis_model->list_rt($data['dusun'],$data['rw']);
						
		if(isset($_SESSION['rt']))
			$data['rt'] = $_SESSION['rt'];
			else $data['rt'] = '';
				
			}else $data['rw'] = '';
			
		}else{
			$data['dusun'] = '';
			$data['rw'] = '';
			$data['rt'] = '';
		}
		
		$data['paging']  = $this->analisis_model->pagingx($p,$o);
		//$data['main']    = $this->analisis_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->analisis_model->autocomplete();
		$data['analisis'] = $this->analisis_model->list_analisis_keluargax($o, $data['paging']->offset, $data['paging']->per_page);
		$data['pertanyaan'] = $this->analisis_model->list_pertanyaan();
		$data['list_dusun'] = $this->analisis_model->list_dusun();
		//$data['list_jenis'] = $this->analisis_model->list_jenis();

		$nav['act']= 0;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		$this->load->view('analisis/nav',$nav);
		$this->load->view('analisis/keluarga',$data);
		$this->load->view('footer');
	}
	
	function keluarga($p=1,$o=0){
	
		$data['p']        = $p;
		$data['o']        = $o;
		
		if(isset($_SESSION['cari']))
			$data['cari'] = $_SESSION['cari'];
		else $data['cari'] = '';
		
		if(isset($_SESSION['filter']))
			$data['filter'] = $_SESSION['filter'];
		else $data['filter'] = '';
		
		if(isset($_SESSION['rentang1']))
			$data['rentang1'] = $_SESSION['rentang1'];
		else $data['rentang1'] = '';
		
		if(isset($_SESSION['rentang2']))
			$data['rentang2'] = $_SESSION['rentang2'];
		else $data['rentang2'] = '';
		
		if(isset($_SESSION['jenis'])){
			$data['jenis'] = $_SESSION['jenis'];}
		else {$data['jenis'] = '1';
		$_SESSION['jenis']='1';}
			
		if(isset($_SESSION['bulan'])){
			$data['bulan'] = $_SESSION['bulan'];}
		else {$data['bulan'] = date("m");
		$_SESSION['bulan'] = date("m");}
		
		if(isset($_SESSION['tahun'])){
			$data['tahun'] = $_SESSION['tahun'];}
		else {$data['tahun'] = date("Y");
		$_SESSION['tahun'] = date("Y");}
	
		if(isset($_POST['per_page'])) 
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];
		
		if(isset($_SESSION['dusun'])){
			$data['dusun'] = $_SESSION['dusun'];
			$data['list_rw'] = $this->analisis_model->list_rw($data['dusun']);
			
		if(isset($_SESSION['rw'])){
			$data['rw'] = $_SESSION['rw'];
			$data['list_rt'] = $this->analisis_model->list_rt($data['dusun'],$data['rw']);
						
		if(isset($_SESSION['rt']))
			$data['rt'] = $_SESSION['rt'];
			else $data['rt'] = '';
				
			}else $data['rw'] = '';
			
		}else{
			$data['dusun'] = '';
			$data['rw'] = '';
			$data['rt'] = '';
		}
		
		$data['paging']  = $this->analisis_model->pagingx($p,$o);
		//$data['main']    = $this->analisis_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->analisis_model->autocomplete();
		$data['analisis'] = $this->analisis_model->list_analisis_keluargax($o, $data['paging']->offset, $data['paging']->per_page);
		$data['pertanyaan'] = $this->analisis_model->list_pertanyaan();
		$data['list_dusun'] = $this->analisis_model->list_dusun();
		$data['list_jenis'] = $this->analisis_model->list_jenis();
		
		$nav['act']= 0;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('analisis/nav',$nav);
		$this->load->view('analisis/keluarga',$data);
		$this->load->view('footer');
	}
		
	function form($p=1,$o=0,$id=''){

		$data['kk']          = $this->analisis_model->get_keluarga($id);
		$data['form_action'] = site_url("analisis/insert/$id");
		
		if(isset($_SESSION['bulan']))
			$data['bulan'] = $_SESSION['bulan'];
		else $data['bulan'] = date("m");
		
		if(isset($_SESSION['tahun']))
			$data['tahun'] = $_SESSION['tahun'];
		else $data['tahun'] = date("Y");
				
		$data['analisis'] = $this->analisis_model->list_analisis_master($id,$data['tahun'],$_SESSION['jenis']);
		

		$nav['act']= 0;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('analisis/nav',$nav);
		$this->load->view('analisis/keluarga_form',$data);
		$this->load->view('footer');
	}
	
	function cetak($o=0){
		$data['main']    = $this->analisis_model->list_analisis_keluargax($o, 0, 10000);
		$this->load->view('/analisis/rtm_print',$data);
	}
	
	function excel($o=0){
		$data['main']    = $this->analisis_model->list_analisis_keluargax($o, 0, 10000);
		$this->load->view('/analisis/rtm_excel',$data);
	}

	function ajax_update_klasifikasi($p=1,$o=0){
		
		$data['form_action'] = site_url("analisis/update_klasifikasi");
		$data['analisis'] = $_POST['id_cb'];	
		
		$this->load->view('analisis/analisis/ajax_update_klasifikasi_form',$data);
	}
	function update_klasifikasi($p=1,$o=0){
		$this->analisis_model->update_klasifikasi();
		redirect("analisis");
	}
	function rincian($p=1,$o=0,$id=''){
	    if(isset($_SESSION['jenis'])){
			$data['jenis'] = $_SESSION['jenis'];}
		else {
		$data['jenis'] = "1";
		$_SESSION['jenis']="1";}
	
        if(isset($_SESSION['bulan']))
			$_SESSION['bulan'] = $_SESSION['bulan'];
		else $_SESSION['bulan'] = date("m");
		
		if(isset($_SESSION['tahun']))
			$_SESSION['tahun'] = $_SESSION['tahun'];
		else $_SESSION['tahun'] = date("Y");
		
		$data['kk']          = $this->analisis_model->get_keluarga($id);
		$data['kepala']      = $this->analisis_model->get_anggota($data['kk']['nik_kepala']);
		$data['form_action'] = site_url("analisis/insert/$id");
        $data['nilai']		=$this->analisis_model->list_nilai_keluarga();
		$data['analisis'] = $this->analisis_model->list_analisis_rincian($id);
		
		
		$nav['act']= 0;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('analisis/nav',$nav);
		$this->load->view('analisis/keluarga_rincian',$data);
		$this->load->view('footer');
	}
	function cetak_keluarga($id=''){
		$data['kk']          = $this->analisis_model->get_keluarga($id);
		$data['kepala']      = $this->analisis_model->get_anggota($data['kk']['nik_kepala']);
		$data['form_action'] = site_url("analisis/insert/$id");
        $data['nilai']		=$this->analisis_model->list_nilai_keluarga();
		$data['analisis'] = $this->analisis_model->list_analisis_rincian($id);
		
		$this->load->view('analisis/keluarga_rincian',$data);
	}
	
	function graph_rincian($id=''){

		$data['kk']          = $this->analisis_model->get_keluarga($id);
		$data['kepala']          = $this->analisis_model->get_anggota($data['kk']['nik_kepala']);
		$data['form_action'] = site_url("analisis/insert/$id");
		$data['form_action_kembali'] = site_url("analisis/rincian/0/1/$id");
		$data['grafik'] = $this->analisis_model->list_line_graph($id);
		
		$nav['act']= 0;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('analisis/nav',$nav);
		$this->load->view('analisis/graph_keluarga_rincian',$data);
		$this->load->view('footer');
	}
	
	function bar_rincian($id=''){

		$data['kk']          = $this->analisis_model->get_keluarga($id);
		$data['kepala']          = $this->analisis_model->get_anggota($data['kk']['nik_kepala']);
		$data['form_action'] = site_url("analisis/insert/$id");
		$data['form_action_kembali'] = site_url("analisis/rincian/0/1/$id");

		
		$data['grafik'] = $this->analisis_model->list_bar_graph($id);
		
		$nav['act']= 0;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('analisis/nav',$nav);
		$this->load->view('analisis/bar_keluarga_rincian',$data);
		$this->load->view('footer');
	}
	
	function search(){
		$cari = $this->input->post('cari');
		if($cari!='')
			$_SESSION['cari']=$cari;
		else unset($_SESSION['cari']);
		redirect('analisis');
	}
	
	function jenis(){
		$jenis = $this->input->post('jenis');
		if($jenis!=0)
			$_SESSION['jenis']=$jenis;
		else unset($_SESSION['jenis']);
		redirect('analisis');
	}
	
	function bulan(){
		$bulan = $this->input->post('bulan');
		if($bulan!=0)
			$_SESSION['bulan']=$bulan;
		else unset($_SESSION['bulan']);
		redirect('analisis');
	}
	
	function rentang_search(){
		$rentang1 = $this->input->post('rentang1');
		$rentang2 = $this->input->post('rentang2');
		if($rentang1)
			$_SESSION['rentang1']=$rentang1;
		else unset($_SESSION['rentang1']);
		if($rentang2)
			$_SESSION['rentang2']=$rentang2;
		else unset($_SESSION['rentang2']);
		$this->analisis_model->update_klasifikasi();
		redirect('analisis');
	}
	
	function ajax_rentang_search(){
		$data['form_action'] = site_url("analisis/rentang_search");
		$data['rentang1']=$_SESSION[rentang1];
		$data['rentang2']=$_SESSION[rentang2];		
	
		$this->load->view('analisis/analisis/ajax_rentang_search_form',$data);
	}
	
	function tahun(){
		$tahun = $this->input->post('tahun');
		if($tahun!=0)
			$_SESSION['tahun']=$tahun;
		else unset($_SESSION['tahun']);
		redirect('analisis');
	}
	
	function dusun(){
		$dusun = $this->input->post('dusun');
		if($dusun!=""){
			$_SESSION['dusun']=$dusun;
			unset($_SESSION['rw']);
			unset($_SESSION['rt']);}
		else {
			unset($_SESSION['dusun']);
			unset($_SESSION['rw']);
			unset($_SESSION['rt']);}
		redirect("analisis");
	}
	
	function rw(){
		$rw = $this->input->post('rw');
		if($rw!=""){
			$_SESSION['rw']=$rw;unset($_SESSION['rt']);}
		else {unset($_SESSION['rw']);unset($_SESSION['rt']);}
		redirect("analisis");
	}
	
	function rt(){
		$rt = $this->input->post('rt');
		if($rt!="")
			$_SESSION['rt']=$rt;
		else unset($_SESSION['rt']);
		redirect("analisis");
	}
	
	function insert($id=0){
		$this->analisis_model->insert_analisis($id);
		$this->analisis_model->insert_hasil_analisis($id);
		redirect('analisis');
	}
	
	function update($id=''){
		$this->analisis_model->update_analisis($id);
		redirect('analisis');
	}
	
	function delete($p=1,$o=0,$id=''){
		$this->analisis_model->delete($id);
		redirect('analisis');
	}
	
	function delete_all($p=1,$o=0){
		$this->analisis_model->delete_all();
		redirect('analisis');
	}	

	function clear_analisis_all(){
		$this->analisis_model->clear_analisis_all();
		redirect('analisis');
	}	

	function clear_analisis($id=''){
		$this->analisis_model->clear_analisis($id);
		redirect('analisis');
	}
	function rentang_analisis(){
		//$data['lap']=13;
		$data['jenis']=$_SESSION['jenis'];
		$data['main']    = $this->analisis_model->list_data_rentang();
		$data['list_jenis'] = $this->analisis_model->list_jenis();
		$data['kluster']    = $this->analisis_model->get_kluster();
		$header = $this->header_model->get_data();
		$menu['act']='0';
		
		$this->load->view('header', $header);
		
		$this->load->view('analisis/nav',$menu);
		$this->load->view('analisis/rentang_analisis',$data);
		$this->load->view('footer');
	}
	
	function form_rentang($id=0){

		if($id==0){
			$data['form_action'] = site_url("analisis/rentang_insert");
			$data['rentang']= $this->analisis_model->get_rentang_terakhir();
			$data['rentang']['nama']="";
			$data['rentang']['sampai']="";
		}
		else{
			$data['form_action'] = site_url("analisis/rentang_update/$id");
			$data['rentang']     = $this->analisis_model->get_rentang($id);			
		}
		$this->load->view('analisis/analisis/laporan/ajax_rentang_form',$data);
		
	}
	
	function rentang_insert(){
		$data['insert'] = $this->analisis_model->insert_rentang();
		redirect('analisis/rentang_analisis');
	}
	
	function rentang_update($id=0){
		$this->analisis_model->update_rentang($id);
		redirect('analisis/rentang_analisis');
	}
	
	function rentang_delete($id=0){
		$this->analisis_model->delete_rentang($id);
		redirect('analisis/rentang_analisis');
	}	
	
	function delete_all_rentang($id=0){
		$this->analisis_model->delete_all_rentang();
		redirect('analisis/rentang_analisis');
	}	
	
	
	
	function jenis_analisis(){
		//$data['lap']=13;
		$data['main']    = $this->analisis_model->list_data_jenis();
		$data['list_kluster']    = $this->analisis_model->list_kluster();
		$data['kluster']    = $this->analisis_model->get_kluster();

		$header = $this->header_model->get_data();
		$menu['act']='0';
		
		$this->load->view('header', $header);
		
		$this->load->view('analisis/nav',$menu);
		$this->load->view('analisis/jenis_analisis',$data);
		$this->load->view('footer');
	}
	
	function kluster(){
		$this->analisis_model->update_kluster();
		redirect("analisis/jenis_analisis");
	}
	
	function form_jenis($id=0){

		if($id==0){
			$data['form_action'] = site_url("analisis/jenis_insert");
		}
		else{
			$data['form_action'] = site_url("analisis/jenis_update/$id");
			$data['rentang']     = $this->analisis_model->get_jenis_analisis($id);			
		}
		$this->load->view('analisis/analisis/ajax_jenis_form',$data);
		
	}
	
	function jenis_insert(){
		$data['insert'] = $this->analisis_model->insert_jenis();
		redirect('analisis/jenis_analisis');
	}
	
	function jenis_update($id=0){
		$this->analisis_model->update_jenis($id);
		redirect('analisis/jenis_analisis');
	}
	
	function jenis_delete($id=0){
		$this->analisis_model->delete_jenis($id);
		redirect('analisis/jenis_analisis');
	}	
	
	function delete_all_jenis($id=0){
		$this->analisis_model->delete_all_jenis();
		redirect('analisis/jenis_analisis');
	}	
	
	function jenis_rentang(){
		$jenis = $this->input->post('jenis');
		if($jenis!=0)
			$_SESSION['jenis']=$jenis;
		redirect('analisis/rentang_analisis');
	}
	
	function jenis_keluarga(){
		$jenis = $this->input->post('jenis');
		if($jenis!=0)
			$_SESSION['jenis']=$jenis;
		redirect('analisis/keluarga');
	}
	
	function ubah_hasil_klasifikasi($id=0){
		$data['form_action'] = site_url("analisis/hasil_klasifikasi_update/$id");
		$data['klasifikasi']     = $this->analisis_model->get_hasil_analisis($id);			
		$data['list_klasifikasi']     = $this->analisis_model->list_hasil_analisis();			
		$this->load->view('analisis/analisis/ajax_hasil_analisis_form',$data);
	}
	
	function hasil_klasifikasi_update($id=0){
		$this->analisis_model->update_hasil_klasifikasi($id);
		redirect('analisis');
	}
	
	function verifikasi($p=1,$o=0){
		$data['p']        = $p;
		$data['o']        = $o;
		$data['paging']  = $this->analisis_model->pagingx($p,$o);
		$this->analisis_model->verifikasi($o, $data['paging']->offset, $data['paging']->per_page);
		redirect('analisis');
	}
}
