<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Laporan_Keluarga extends CI_Controller{

function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('user_model');
		$this->load->model('analisis_laporan_keluarga_model');
		$this->load->model('keluarga_model');
		$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
		if($grup!=1 AND $grup!=2 AND $grup!=3) redirect('siteman');
		$this->load->model('header_model');
		$this->load->model('header_model');
		$_SESSION['tahun'] = 2014;
		$_SESSION['bulan']=date("m");
	}
	

	function index($lap=0,$p=1,$o=0){
	
		$data['p']        = $p;
		$data['o']        = $o;
		
		if(isset($_POST['per_page'])) 
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];

		if(isset($_SESSION['jenis']))
			{$data['jenis'] = $_SESSION['jenis'];}
		else {$data['jenis'] = '1';
			$_SESSION['jenis']='1';}
			
		if(isset($_SESSION['bulan'])){
			$data['bulan'] = $_SESSION['bulan'];}
		else {$data['bulan'] = date("m");
		        $_SESSION['bulan']=date("m");}
		
		if(isset($_SESSION['tahun'])){
			$data['tahun'] = $_SESSION['tahun'];}
		else {$data['tahun'] = date("Y");
		$_SESSION['tahun'] = date("Y");
		}
		
		$data['thn']=$data['tahun'];
		$data['bln']=$data['bulan'];
		$data['paging']  = $this->analisis_laporan_keluarga_model->paging($lap,$p,$o);
		$data['main']    = $this->analisis_laporan_keluarga_model->list_data($lap,$o, $data['paging']->offset, $data['paging']->per_page);
		$data['list_jenis'] = $this->analisis_laporan_keluarga_model->list_jenis();
		
		$data['lap']=$lap;
		
		switch($lap){
			case 0: $data['stat'] = "Aspek1"; break;
			case 1: $data['stat'] = "Aspek2"; break;
			case 2: $data['stat'] = "Aspek3"; break;
			case 3: $data['stat'] = "Aspek4"; break;
			case 4: $data['stat'] = "Aspek5"; break;
			case 5: $data['stat'] = "Aspek6"; break;
			case 6: $data['stat'] = "Aspek7"; break;
			default:$data['stat'] = "Aspek1";
		}
		
		$nav['act']= 0;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('analisis/nav',$nav);
		$this->load->view('analisis/laporan/keluarga',$data);
		$this->load->view('footer');
	}
		
	function detail($stat=0,$jwb=0,$thn='',$bln='',$lap=0,$p=1,$o=0){
	
		$data['p']        	= $p;
		$data['o']        	= $o;
		$data['stat']		= $stat;
		$data['jwb']		= $jwb;
		$data['thn']		= $thn;	
		$data['bln']		= $bln;	
		$data['lap']		= $lap;				
		
		if(empty($thn)){
			$thn=$_SESSION['tahun'];}
		if(empty($bln)){
			$bln=$_SESSION['bulan'];}
		
		if(isset($_SESSION['dusun'])){
			$data['dusun'] = $_SESSION['dusun'];
			$data['list_rw'] = $this->analisis_laporan_keluarga_model->list_rw($data['dusun']);
			
		if(isset($_SESSION['rw'])){
			$data['rw'] = $_SESSION['rw'];
			$data['list_rt'] = $this->analisis_laporan_keluarga_model->list_rt($data['dusun'],$data['rw']);
						
		if(isset($_SESSION['rt']))
			$data['rt'] = $_SESSION['rt'];
			else $data['rt'] = '';
				
			}else $data['rw'] = '';
			
		}else{
			$data['dusun'] = '';
			$data['rw'] = '';
			$data['rt'] = '';
		}
		
		$data['thn']        = $thn;
		$data['bln']        = $bln;
		
		if(isset($_POST['per_page'])) 
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];

		
		$data['main']    = $this->analisis_laporan_keluarga_model->list_data_detail($stat,$jwb,$thn,$bln,$lap,$o, $data['paging']->offset, $data['paging']->per_page);
		$data['tanya']    = $this->analisis_laporan_keluarga_model->get_master_analisis_keluarga($stat);
		$data['jawab']    = $this->analisis_laporan_keluarga_model->get_sub_analisis_keluarga($jwb);
		$data['list_dusun'] = $this->analisis_laporan_keluarga_model->list_dusun();

		$nav['act']= 0;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('analisis/nav',$nav);
		$this->load->view('analisis/laporan/keluarga_detail',$data);
		$this->load->view('footer');
	}
	
	function lap_detail_a($p=1,$o=0){
	
		$data['p']        = $p;
		$data['o']        = $o;
		
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
	
	    if(isset($_SESSION['cari'])){
			$data['cari'] = $_SESSION['cari'];}
		else {$data['cari'] = '';
		$_SESSION['cari']='';}
		
		if(isset($_POST['per_page'])) 
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];
		
		$data['paging']  = $this->analisis_laporan_keluarga_model->pagingx($p,$o);
		//$data['main']    = $this->keluarga_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		//$data['keyword'] = $this->analisis_laporan_keluarga_model->autocomplete();
		$data['analisis'] = $this->analisis_laporan_keluarga_model->list_analisis_keluargax($o, $data['paging']->offset, $data['paging']->per_page);
		$data['pertanyaan'] = $this->analisis_laporan_keluarga_model->list_pertanyaan();

		$nav['act']= 0;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('analisis/nav',$nav);
		$this->load->view('analisis/laporan/lap_detail_a',$data);
		$this->load->view('footer');
	}
	function lap_detail_b($p=1,$o=0){
	
		$data['p']        = $p;
		$data['o']        = $o;
		
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
	
	        if(isset($_SESSION['cari'])){
			$data['cari'] = $_SESSION['cari'];}
		else {$data['cari'] = '';
		$_SESSION['cari']='';}
		
		if(isset($_POST['per_page'])) 
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];
		
		$data['paging']  = $this->analisis_laporan_keluarga_model->pagingx($p,$o);
		//$data['main']    = $this->keluarga_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		//$data['keyword'] = $this->analisis_laporan_keluarga_model->autocomplete();
		$data['analisis'] = $this->analisis_laporan_keluarga_model->list_analisis_keluargay($o, $data['paging']->offset, $data['paging']->per_page);
		$data['pertanyaan'] = $this->analisis_laporan_keluarga_model->list_pertanyaan();

		$nav['act']= 0;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('analisis/nav',$nav);
		$this->load->view('analisis/laporan/lap_detail_b',$data);
		$this->load->view('footer');
	}
	function graph_laporan(){

		$data['config']  = $this->analisis_laporan_keluarga_model->get_config();
		$data['grafik'] = $this->analisis_laporan_keluarga_model->list_graph();
		$data['form_action_kembali'] = site_url("laporan_keluarga");
		$nav['act']= 0;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('analisis/nav',$nav);
		$this->load->view('analisis/laporan/graph_keluarga_laporan',$data);
		$this->load->view('footer');
	}
	function tanya($id=''){
		$data['config']  = $this->analisis_laporan_keluarga_model->get_config();
		$data['grafik'] = $this->analisis_laporan_keluarga_model->list_graph_tanya($id);
		$data['tanya'] = $this->analisis_laporan_keluarga_model->get_tanya($id);
		$data['form_action_kembali'] = site_url("laporan_keluarga/");
		
		$nav['act']= 0;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('analisis/nav',$nav);
		$this->load->view('analisis/laporan/graph_keluarga_laporan_tanya',$data);
		$this->load->view('footer');
	}
	function jawaban($id='',$id2=''){
		$data['config']  = $this->analisis_laporan_keluarga_model->get_config();
		$data['grafik'] = $this->analisis_laporan_keluarga_model->list_graph_jawab($id,$id2);
		$data['jawab'] = $this->analisis_laporan_keluarga_model->get_jawab($id2);
		$data['tanya'] = $this->analisis_laporan_keluarga_model->get_tanya($id);
		$data['form_action_kembali'] = site_url("laporan_keluarga");
		
		$nav['act']= 0;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('analisis/nav',$nav);
		$this->load->view('analisis/laporan/graph_keluarga_laporan_jawab',$data);
		$this->load->view('footer');
	}

	function graph($lap=0){
	
		$data['main']    = $this->analisis_laporan_keluarga_model->list_data($lap);
		$data['lap']=$lap;
		
		switch($lap){
		        case 0: $data['stat'] = "air bersih"; break;
			case 1: $data['stat'] = "layanan kesehatan"; break;
			case 2: $data['stat'] = "fasilitas mck"; break;
			case 3: $data['stat'] = "penerangan rumah"; break;
			case 4: $data['stat'] = "lantai"; break;
			case 5: $data['stat'] = "polamakan"; break;
			case 6: $data['stat'] = "sekolah anak"; break;
			default:$data['stat'] = "air bersih";
		}
		
		$nav['act']= 0;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('analisis/nav',$nav);
		$this->load->view('analisis/laporan/penduduk_graph',$data);
		$this->load->view('footer');
	}
	    
    function cetak($lap=0){
		
		$data['config']  = $this->analisis_laporan_keluarga_model->get_config();
		$data['main']    = $this->analisis_laporan_keluarga_model->list_data($lap);
		$this->load->view('analisis/analisis/laporan/keluarga_print',$data);
	}	

	function jenis(){
		$jenis = $this->input->post('jenis');
		if($jenis!=0)
			$_SESSION['jenis']=$jenis;
		else unset($_SESSION['jenis']);
		redirect('laporan_keluarga');
	}
	function tahun(){
		$tahun = $this->input->post('tahun');
		if($tahun!=0)
			$_SESSION['tahun']=$tahun;
		else unset($_SESSION['tahun']);
		redirect('laporan_keluarga');
	}
	function bulan(){
		$bulan = $this->input->post('bulan');
		if($bulan!=0)
			$_SESSION['bulan']=$bulan;
		else unset($_SESSION['bulan']);
		redirect('laporan_keluarga');
	}
	
	function search_a(){
		$cari = $this->input->post('cari');
		if($cari!='')
			$_SESSION['cari']=$cari;
		else unset($_SESSION['cari']);
		redirect('laporan_keluarga/lap_detail_a');
	}
	
	function jenis_a(){
		$jenis = $this->input->post('jenis');
		if($jenis!=0)
			$_SESSION['jenis']=$jenis;
		else unset($_SESSION['jenis']);
		redirect('laporan_keluarga/lap_detail_a');
	}
	
	function bulan_a(){
		$bulan = $this->input->post('bulan');
		if($bulan!=0)
			$_SESSION['bulan']=$bulan;
		else unset($_SESSION['bulan']);
		redirect('laporan_keluarga/lap_detail_a');
	}
	
	function tahun_a(){
		$tahun = $this->input->post('tahun');
		if($tahun!=0)
			$_SESSION['tahun']=$tahun;
		else unset($_SESSION['tahun']);
		redirect('laporan_keluarga/lap_detail_a');
	}
		function search_b(){
		$cari = $this->input->post('cari');
		if($cari!='')
			$_SESSION['cari']=$cari;
		else unset($_SESSION['cari']);
		redirect('laporan_keluarga/lap_detail_b');
	}
	
	function jenis_b(){
		$jenis = $this->input->post('jenis');
		if($jenis!=0)
			$_SESSION['jenis']=$jenis;
		else unset($_SESSION['jenis']);
		redirect('laporan_keluarga/lap_detail_b');
	}
	
	function bulan_b(){
		$bulan = $this->input->post('bulan');
		if($bulan!=0)
			$_SESSION['bulan']=$bulan;
		else unset($_SESSION['bulan']);
		redirect('laporan_keluarga/lap_detail_b');
	}
	
	function tahun_b(){
		$tahun = $this->input->post('tahun');
		if($tahun!=0)
			$_SESSION['tahun']=$tahun;
		else unset($_SESSION['tahun']);
		redirect('laporan_keluarga/lap_detail_b');
	}

	function dusun($stat='',$jwb='',$thn='',$bln='',$lap='',$p='',$o=''){
		$data['p']        	= $p;
		$data['o']        	= $o;
		$data['stat']		= $stat;
		$data['jwb']		= $jwb;
		$data['thn']		= $thn;	
		$data['bln']		= $bln;	
		$data['lap']		= $lap;	
		$dusun = $this->input->post('dusun');
		if($dusun!=""){
			$_SESSION['dusun']=$dusun;
			unset($_SESSION['rw']);
			unset($_SESSION['rt']);}
		else {
			unset($_SESSION['dusun']);
			unset($_SESSION['rw']);
			unset($_SESSION['rt']);}
		redirect("laporan_keluarga/detail/$stat/$jwb/$thn/$bln/$lap/$p/$o");
	}
	
	function rw($stat='',$jwb='',$thn='',$bln='',$lap='',$p='',$o=''){
		$data['p']        	= $p;
		$data['o']        	= $o;
		$data['stat']		= $stat;
		$data['jwb']		= $jwb;
		$data['thn']		= $thn;	
		$data['bln']		= $bln;	
		$data['lap']		= $lap;	
		$rw = $this->input->post('rw');
		if($rw!=""){
			$_SESSION['rw']=$rw;unset($_SESSION['rt']);}
		else {unset($_SESSION['rw']);unset($_SESSION['rt']);}
		redirect("laporan_keluarga/detail/$stat/$jwb/$thn/$bln/$lap/$p/$o");
	}
	
	function rt($stat='',$jwb='',$thn='',$bln='',$lap='',$p='',$o=''){
		$data['p']        	= $p;
		$data['o']        	= $o;
		$data['stat']		= $stat;
		$data['jwb']		= $jwb;
		$data['thn']		= $thn;	
		$data['bln']		= $bln;	
		$data['lap']		= $lap;	
		$rt = $this->input->post('rt');
		if($rt!="")
			$_SESSION['rt']=$rt;
		else unset($_SESSION['rt']);
		redirect("laporan_keluarga/detail/$stat/$jwb/$thn/$bln/$lap/$p/$o");
	}
	
	function cetak_detail($stat=0,$jwb=0,$thn='',$bln='',$lap=0,$p=1,$o=0){
	
		$data['p']        	= $p;
		$data['o']        	= $o;
		$data['stat']		= $stat;
		$data['jwb']		= $jwb;
		$data['thn']		= $thn;	
		$data['bln']		= $bln;	
		$data['lap']		= $lap;				
		
		if(empty($thn)){
			$thn=$_SESSION['tahun'];}
		if(empty($bln)){
			$bln=$_SESSION['bulan'];}

		$data['dusun'] = $_SESSION['dusun'];
		$data['rw'] = $_SESSION['rw'];
		$data['rt'] = $_SESSION['rt'];

		$data['thn']        = $thn;
		$data['bln']        = $bln;
		
		if(isset($_POST['per_page'])) 
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];

		
		$data['main']    = $this->analisis_laporan_keluarga_model->list_data_detail($stat,$jwb,$thn,$bln,$lap,$o, $data['paging']->offset, $data['paging']->per_page);
		$data['tanya']    = $this->analisis_laporan_keluarga_model->get_master_analisis_keluarga($stat);
		$data['jawab']    = $this->analisis_laporan_keluarga_model->get_sub_analisis_keluarga($jwb);


		$this->load->view('analisis/analisis/laporan/keluarga_detail_cetak',$data);


	}
	
	function rentang_analisis(){
		//$data['lap']=13;
		$data['jenis']=$_SESSION['jenis'];
		$data['main']    = $this->analisis_laporan_keluarga_model->list_data_rentang();
		$data['list_jenis'] = $this->analisis_laporan_keluarga_model->list_jenis();
		$header = $this->header_model->get_data();
		$menu['act']='0';
		
		$this->load->view('header', $header);
		$this->load->view('analisis/menu',$menu);
		$this->load->view('analisis/nav',$menu);
		$this->load->view('analisis/laporan/lap_rentang_analisis',$data);
		$this->load->view('footer');
	}
	
	function detail_rentang($id=0){
	
		$data['id']        	= $id;
		
		if(isset($_SESSION['dusun'])){
			$data['dusun'] = $_SESSION['dusun'];
			$data['list_rw'] = $this->analisis_laporan_keluarga_model->list_rw($data['dusun']);
			
		if(isset($_SESSION['rw'])){
			$data['rw'] = $_SESSION['rw'];
			$data['list_rt'] = $this->analisis_laporan_keluarga_model->list_rt($data['dusun'],$data['rw']);
						
		if(isset($_SESSION['rt']))
			$data['rt'] = $_SESSION['rt'];
			else $data['rt'] = '';
				
			}else $data['rw'] = '';
			
		}else{
			$data['dusun'] = '';
			$data['rw'] = '';
			$data['rt'] = '';
		}
		
		$data['rentang'] = $this->analisis_laporan_keluarga_model->get_rentang($id);
		$data['main']    = $this->analisis_laporan_keluarga_model->list_detail_rentang($id);
		$data['list_dusun'] = $this->analisis_laporan_keluarga_model->list_dusun();

		$nav['act']= 0;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('analisis/nav',$nav);
		$this->load->view('analisis/laporan/rentang_analisis_detail',$data);
		$this->load->view('footer');
	}
	
	function dusun_rentang($id=0){
		$data['id'] = $id;
		$dusun = $this->input->post('dusun');
		if($dusun!=""){
			$_SESSION['dusun']=$dusun;
			unset($_SESSION['rw']);
			unset($_SESSION['rt']);}
		else {
			unset($_SESSION['dusun']);
			unset($_SESSION['rw']);
			unset($_SESSION['rt']);}
		redirect("laporan_keluarga/detail_rentang/$id");
	}
	
	function rw_rentang($id=0){
		$data['id'] = $id;
		$rw = $this->input->post('rw');
		if($rw!=""){
			$_SESSION['rw']=$rw;unset($_SESSION['rt']);}
		else {unset($_SESSION['rw']);unset($_SESSION['rt']);}
		redirect("laporan_keluarga/detail_rentang/$id");
	}
	
	function rt_rentang($id=0){
		$data['id'] = $id;
		$rt = $this->input->post('rt');
		if($rt!="")
			$_SESSION['rt']=$rt;
		else unset($_SESSION['rt']);
		redirect("laporan_keluarga/detail_rentang/$id");
	}
	
	function detail_rentang_cetak($id=0){
	
		$data['id']        	= $id;
		
		if(isset($_SESSION['dusun'])){
			$data['dusun'] = $_SESSION['dusun'];
			$data['list_rw'] = $this->analisis_laporan_keluarga_model->list_rw($data['dusun']);
			
		if(isset($_SESSION['rw'])){
			$data['rw'] = $_SESSION['rw'];
			$data['list_rt'] = $this->analisis_laporan_keluarga_model->list_rt($data['dusun'],$data['rw']);
						
		if(isset($_SESSION['rt']))
			$data['rt'] = $_SESSION['rt'];
			else $data['rt'] = '';
				
			}else $data['rw'] = '';
			
		}else{
			$data['dusun'] = '';
			$data['rw'] = '';
			$data['rt'] = '';
		}
		
		$data['rentang'] = $this->analisis_laporan_keluarga_model->get_rentang($id);
		$data['main']    = $this->analisis_laporan_keluarga_model->list_detail_rentang($id);
		$data['list_dusun'] = $this->analisis_laporan_keluarga_model->list_dusun();


		$this->load->view('analisis/analisis/laporan/rentang_analisis_detail_cetak',$data);

	}
	
	function rentang_tahun(){
		$tahun = $this->input->post('tahun');
		if($tahun!=0)
			$_SESSION['tahun']=$tahun;
		else unset($_SESSION['tahun']);
		redirect('laporan_keluarga/rentang_analisis');
	}
	
	function jenis_rentang(){
		$jenis = $this->input->post('jenis');
		if($jenis!=0)
			$_SESSION['jenis']=$jenis;
		redirect('laporan_keluarga/rentang_analisis');
	}
	
	function jenis_keluarga(){
		$jenis = $this->input->post('jenis');
		if($jenis!=0)
			$_SESSION['jenis']=$jenis;
		redirect('laporan_keluarga');
	}

}
