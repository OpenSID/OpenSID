<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Surat extends CI_Controller{

	function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('user_model');
		$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
		if($grup!=1 AND $grup!=2 AND $grup!=3) redirect('siteman');
		$this->load->model('header_model');
		$this->load->model('penduduk_model');
		$this->load->model('surat_model');
		$this->load->model('surat_keluar_model');

	}
	

	function index($p=1,$o=0,$act=0,$id=''){
		$data['p'] = $p;
		$data['o'] = $o;
		$data['act'] = $act;
		
		if(isset($_POST['penduduk']))
			$data['penduduk_sel'] = $_POST['penduduk'];
		else
			$data['dus_sel'] = '';
			
		if(isset($_POST['rw']))
			$data['rw_sel'] = $_POST['rw'];
		else
			$data['rw_sel'] = '';
			
		if(isset($_POST['rt']))
			$data['rt_sel'] = $_POST['rt'];
		else
			$data['rt_sel'] = '';
			
		if($id){
			$data['penduduk']        = $this->penduduk_model->get_penduduk($id);
			$data['form_action'] = site_url("penduduk/update/$p/$o/$act/$id");
		}
		else{
			$data['penduduk']        = null;
			$data['form_action'] = site_url("penduduk/insert");
		}
		
		$header = $this->header_model->get_data();
		$data['dusun'] = $this->penduduk_model->list_dusun();
		$data['rw']    = $this->penduduk_model->list_rw($data['dus_sel']);
		$data['rt']    = $this->penduduk_model->list_rt($data['dus_sel'],$data['rw_sel']);
		$data['agama'] = $this->penduduk_model->list_agama();
		$data['pendidikan'] = $this->penduduk_model->list_pendidikan();
		$data['pekerjaan'] = $this->penduduk_model->list_pekerjaan();
		
		$data['menu_surat'] = $this->surat_model->list_surat();
		$data['menu_surat2'] = $this->surat_model->list_surat2();
		
		$this->load->view('header', $header);
		$nav['act']= 1;
		
		$this->load->view('surat/nav',$nav);
		$this->load->view('surat/format_surat',$data);
		$this->load->view('footer');
	}
	
	function form($url=''){
		$data['lap']="surat_ket_pengantar";
		if(isset($_POST['nik']))
			$data['individu']=$this->surat_model->get_penduduk($_POST['nik']);
		else
		$data['individu']=NULL;
		
		$data['menu_surat'] = $this->surat_model->list_surat();
		$data['penduduk'] = $this->surat_model->list_penduduk();
		$data['pamong'] = $this->surat_model->list_pamong();
		
		$data['form_action'] = site_url("surat/cetak/$url");
		$data['form_action2'] = site_url("surat/doc/$url");
		$nav['act']= 1;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('surat/nav',$nav);
		$this->load->view("surat/$url",$data);
		$this->load->view('footer');

	}
		
	function cetak($url=''){
		
		$f=1;
		$g=$_POST['pamong'];
		$u=$_SESSION['user'];
			$z=$_POST['nomor'];
		
		$data['menu_surat'] = $this->surat_model->list_surat();
		$id = $_POST['nik'];
		$data['input'] = $_POST;
		$data['tanggal_sekarang'] = tgl_indo(date("Y m d"));
		
		$data['data'] = $this->surat_model->get_data_surat($id);
		$data['desa'] = $this->surat_model->get_data_desa();
		$data['pamong'] = $this->surat_model->get_pamong($_POST['pamong']);
			
		$this->surat_keluar_model->log_surat($f,$id,$g,$u,$z);
		$this->load->view("surat/print_$url",$data);
	}
	
	function doc($url=''){
		$this->surat_model->coba($url);
	}	
	
	
function surat_ket_pengantar(){
		$data['lap']="surat_ket_pengantar";
		if(isset($_POST['nik']))
			$data['individu']=$this->surat_model->get_penduduk($_POST['nik']);
		else
		$data['individu']=NULL;
		
		$data['menu_surat'] = $this->surat_model->list_surat();
		$data['penduduk'] = $this->surat_model->list_penduduk();
		$data['pamong'] = $this->surat_model->list_pamong();
		
		$data['form_action'] = site_url("surat/print_surat_ket_pengantar");
		$data['form_action2'] = site_url("surat/doc/$url");
		$nav['act']= 1;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('surat/nav',$nav);
		$this->load->view('surat/surat_ket_pengantar',$data);
		$this->load->view('footer');

}
	
	
	
function doc_kp(){
	//$id = $_POST['nik'];
	$this->surat_model->coba(1);
}
	
function doc_kp2(){
	//$id = $_POST['nik'];
	$this->surat_model->coba(2);
}
	
function print_surat_ket_pengantar(){
	
	$f=1;
	$g=$_POST['pamong'];
	$u=$_SESSION['user'];
        $z=$_POST['nomor'];
	
	$data['menu_surat'] = $this->surat_model->list_surat();
	$id = $_POST['nik'];
	$data['input'] = $_POST;
	$data['tanggal_sekarang'] = tgl_indo(date("Y m d"));
	
	$data['data'] = $this->surat_model->get_data_surat($id);
	$data['desa'] = $this->surat_model->get_data_desa();
	$data['pamong'] = $this->surat_model->get_pamong($_POST['pamong']);
		
	$this->surat_keluar_model->log_surat($f,$id,$g,$u,$z);
	$this->load->view('surat/print_surat_ket_pengantar',$data);
}
	
function surat_ket_penduduk(){
		$data['lap']="surat_ket_penduduk";
		$data['menu_surat'] = $this->surat_model->list_surat();
		if(isset($_POST['nik']))
			$data['individu']=$this->surat_model->get_penduduk($_POST['nik']);
		else
		$data['individu']=NULL;
		//$id = $_POST['nik'];
		$data['penduduk'] = $this->surat_model->list_penduduk();
		$data['pamong'] = $this->surat_model->list_pamong();
		
		//$data['pribadi'] = $this->surat_model->get_data_pribadi($id);
		$data['form_action'] = site_url("surat/print_surat_ket_penduduk");
		$data['form_action2'] = site_url("surat/doc_kp2");
		$nav['act']= 1;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('surat/nav',$nav);
		$this->load->view('surat/surat_ket_penduduk',$data);
		$this->load->view('footer');
}

function print_surat_ket_penduduk(){
	
		$f=2;
	        $g=$_POST['pamong'];
	        $u=$_SESSION['user'];
	        $z=$_POST['nomor'];
	
		$data['menu_surat'] = $this->surat_model->list_surat();
		$id = $_POST['nik'];
		$data['input'] = $_POST;
		$data['tanggal_sekarang'] = tgl_indo(date("Y m d"));
		
		$data['data'] = $this->surat_model->get_data_surat($id);
		$data['desa'] = $this->surat_model->get_data_desa();
		//$data['pribadi'] = $this->surat_model->get_data_pribadi($id);
		$data['pamong'] = $this->surat_model->get_pamong($_POST['pamong']);
		
	        $this->surat_keluar_model->log_surat($f,$id,$g,$u,$z);
		$this->load->view('surat/print_surat_ket_penduduk',$data);
}

function surat_bio_penduduk(){
		$data['lap']="surat_bio_penduduk";
		$data['menu_surat'] = $this->surat_model->list_surat();
		if(isset($_POST['nik']))
			$data['individu']=$this->surat_model->get_penduduk($_POST['nik']);
		else
		$data['individu']=NULL;
		
		$data['penduduk'] = $this->surat_model->list_penduduk();
		$data['pamong'] = $this->surat_model->list_pamong();
		
		$data['form_action'] = site_url("surat/print_surat_bio_penduduk");
		$nav['act']= 1;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('surat/nav',$nav);
		$this->load->view('surat/surat_bio_penduduk',$data);
		$this->load->view('footer');
}
		
function print_surat_bio_penduduk(){
	
		$f=3;
	        $g=$_POST['pamong'];
	        $u=$_SESSION['user'];
	        $z=$_POST['nomor'];
	
		$data['menu_surat'] = $this->surat_model->list_surat();
		$id = $_POST['nik'];
		$data['input'] = $_POST;
		$data['tanggal_sekarang'] = tgl_indo(date("Y m d"));
		
		$data['data'] = $this->surat_model->get_data_surat($id);
		$data['desa'] = $this->surat_model->get_data_desa();
		$data['kk'] = $this->surat_model->get_data_kk($id);
		$data['pribadi'] = $this->surat_model->get_data_pribadi($id);
		$data['pamong'] = $this->surat_model->get_pamong($_POST['pamong']);
		
	    $this->surat_keluar_model->log_surat($f,$id,$g,$u,$z);
		
		$this->load->view('surat/print_surat_bio_penduduk',$data);
}
	
function surat_ket_catatan_kriminal(){
		$data['lap']="surat_ket_catatan_kriminal";
		$data['menu_surat'] = $this->surat_model->list_surat();
		if(isset($_POST['nik']))
			$data['individu']=$this->surat_model->get_penduduk($_POST['nik']);
		else
		$data['individu']=NULL;
		
		$data['penduduk'] = $this->surat_model->list_penduduk();
		$data['pamong'] = $this->surat_model->list_pamong();
		
		$data['form_action'] = site_url("surat/print_surat_ket_catatan_kriminal");
		
		$nav['act']= 1;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('surat/nav',$nav);
		$this->load->view('surat/surat_ket_catatan_kriminal',$data);
		$this->load->view('footer');
}
	
function print_surat_ket_catatan_kriminal(){
	
		$f=8;
	        $g=$_POST['pamong'];
	        $u=$_SESSION['user'];
		$z=$_POST['nomor'];
	
		$data['menu_surat'] = $this->surat_model->list_surat();
		$id = $_POST['nik'];
		$data['input'] = $_POST;
		$data['tanggal_sekarang'] = tgl_indo(date("Y m d"));
		
		$data['data'] = $this->surat_model->get_data_surat($id);
		$data['desa'] = $this->surat_model->get_data_desa();
		$data['pamong'] = $this->surat_model->get_pamong($_POST['pamong']);
		
	        $this->surat_keluar_model->log_surat($f,$id,$g,$u,$z);
	
		
		$this->load->view('surat/print_surat_ket_catatan_kriminal',$data);
}
	
	
function surat_ket_pindah_penduduk(){
		$data['lap']="surat_ket_pindah_penduduk";
		$data['menu_surat'] = $this->surat_model->list_surat();
		if(isset($_POST['nik'])){
			$kk=$this->surat_model->get_penduduk($_POST['nik']);
			$data['individu']=$kk;
			$data['anggota']=$this->surat_model->list_anggota($kk['no_kk'],$kk['nik']);
		}else{
		        $data['individu']=NULL;
		        $data['anggota']=NULL;
		}
		
		$data['penduduk'] = $this->surat_model->list_penduduk();
		$data['pamong'] = $this->surat_model->list_pamong();
		
		$data['form_action'] = site_url("surat/print_surat_ket_pindah_penduduk");
		$nav['act']= 1;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('surat/nav',$nav);
		$this->load->view('surat/surat_ket_pindah_penduduk',$data);
		$this->load->view('footer');
}
	
function print_surat_ket_pindah_penduduk(){
	
		$f=4;
	        $g=$_POST['pamong'];
	        $u=$_SESSION['user'];
		$z=$_POST['nomor'];
	
		$data['menu_surat'] = $this->surat_model->list_surat();
		$id = $_POST['nik'];
		$data['input'] = $_POST;
		$data['tanggal_sekarang'] = tgl_indo(date("Y m d"));
		$data['pengikut']=$this->surat_model->pengikut();
		
		$data['data'] = $this->surat_model->get_data_surat($id);
		$data['desa'] = $this->surat_model->get_data_desa();
		$data['pribadi'] = $this->surat_model->get_data_pribadi($_POST['nik']);
		$data['pamong'] = $this->surat_model->get_pamong($_POST['pamong']);
		
	        $this->surat_keluar_model->log_surat($f,$id,$g,$u,$z);
		
		$this->load->view('surat/print_surat_ket_pindah_penduduk',$data);
}
	
function surat_permohonan_penduduk(){
		$data['lap']="surat_permohonan_penduduk";
		$data['menu_surat'] = $this->surat_model->list_surat();
		$data['penduduk'] = $this->surat_model->list_penduduk();
		$data['pamong'] = $this->surat_model->list_pamong();
		$data['form_action'] = site_url("surat/print_surat_permohonan_penduduk");
		
		$nav['act']= 1;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('surat/nav',$nav);
		$this->load->view('surat/surat_permohonan_penduduk',$data);
		$this->load->view('footer');
}
	
function print_surat_permohonan_penduduk(){
	
		$f=5;
	        $g=$_POST['pamong'];
	        $u=$_SESSION['user'];
		$z=$_POST['nomor'];
	
		$data['menu_surat'] = $this->surat_model->list_surat();
		$id = $_POST['nik'];
		$data['input'] = $_POST;
		$data['tanggal_sekarang'] = tgl_indo(date("Y m d"));
		
		$data['data'] = $this->surat_model->get_data_surat($id);
		$data['desa'] = $this->surat_model->get_data_desa();
		$data['pamong'] = $this->surat_model->get_pamong($_POST['pamong']);
		
		$this->surat_keluar_model->log_surat($f,$id,$g,$u,$z);
		
		$this->load->view('surat/print_surat_permohonan_penduduk',$data);
}
	
function surat_ket_usaha(){
		$data['lap']="surat_ket_usaha";
		$data['menu_surat'] = $this->surat_model->list_surat();
		if(isset($_POST['nik']))
			$data['individu']=$this->surat_model->get_penduduk($_POST['nik']);
		else
		$data['individu']=NULL;
		
		$data['penduduk'] = $this->surat_model->list_penduduk();
		$data['pamong'] = $this->surat_model->list_pamong();
		
		
		$data['form_action'] = site_url("surat/print_surat_ket_usaha");
		
		$nav['act']= 1;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('surat/nav',$nav);
		$this->load->view('surat/surat_ket_usaha',$data);
		$this->load->view('footer');
}
	
function print_surat_ket_usaha(){
	
		$f=15;
	        $g=$_POST['pamong'];
	        $u=$_SESSION['user'];
		$z=$_POST['nomor'];
	
		$data['menu_surat'] = $this->surat_model->list_surat();
		$id = $_POST['nik'];
		$data['input'] = $_POST;
		$data['tanggal_sekarang'] = tgl_indo(date("Y m d"));
		
		$data['data'] = $this->surat_model->get_data_surat($id);
		$data['desa'] = $this->surat_model->get_data_desa();
		$data['pribadi'] = $this->surat_model->get_data_pribadi($_POST['nik']);
		$data['pamong'] = $this->surat_model->get_pamong($_POST['pamong']);
		
	        $this->surat_keluar_model->log_surat($f,$id,$g,$u,$z);
		$this->load->view('surat/print_surat_ket_usaha',$data);
}


function surat_ket_domisili_usaha(){
		$data['lap']="surat_ket_domisili_usaha";
		$data['menu_surat'] = $this->surat_model->list_surat();
		if(isset($_POST['nik']))
			$data['individu']=$this->surat_model->get_penduduk($_POST['nik']);
		else
		$data['individu']=NULL;
		
		$data['penduduk'] = $this->surat_model->list_penduduk();
		$data['pamong'] = $this->surat_model->list_pamong();
		
		
		$data['form_action'] = site_url("surat/print_surat_ket_domisili_usaha");
		
		$nav['act']= 1;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('surat/nav',$nav);
		$this->load->view('surat/surat_ket_domisili_usaha',$data);
		$this->load->view('footer');
}
	
function print_surat_ket_domisili_usaha(){
	
		$f=17;
	        $g=$_POST['pamong'];
	        $u=$_SESSION['user'];
		$z=$_POST['nomor'];
	
		$data['menu_surat'] = $this->surat_model->list_surat();
		$id = $_POST['nik'];
		$data['input'] = $_POST;
		$data['tanggal_sekarang'] = tgl_indo(date("Y m d"));
		
		$data['data'] = $this->surat_model->get_data_surat($id);
		$data['desa'] = $this->surat_model->get_data_desa();
		$data['pribadi'] = $this->surat_model->get_data_pribadi($_POST['nik']);
		$data['pamong'] = $this->surat_model->get_pamong($_POST['pamong']);
		
	        $this->surat_keluar_model->log_surat($f,$id,$g,$u,$z);
		$this->load->view('surat/print_surat_ket_domisili_usaha',$data);
}


function surat_ket_kehilangan(){
		$data['lap']="surat_ket_kehilangan";
		$data['menu_surat'] = $this->surat_model->list_surat();
		if(isset($_POST['nik']))
			$data['individu']=$this->surat_model->get_penduduk($_POST['nik']);
		else
		$data['individu']=NULL;
		
		$data['penduduk'] = $this->surat_model->list_penduduk();
		$data['pamong'] = $this->surat_model->list_pamong();
		
		
		$data['form_action'] = site_url("surat/print_surat_ket_kehilangan");
		
		$nav['act']= 1;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('surat/nav',$nav);
		$this->load->view('surat/surat_ket_kehilangan',$data);
		$this->load->view('footer');
}
	
function print_surat_ket_kehilangan(){
	
		$f=19;
	        $g=$_POST['pamong'];
	        $u=$_SESSION['user'];
		$z=$_POST['nomor'];
	
		$data['menu_surat'] = $this->surat_model->list_surat();
		$id = $_POST['nik'];
		$data['input'] = $_POST;
		$data['tanggal_sekarang'] = tgl_indo(date("Y m d"));
		
		$data['data'] = $this->surat_model->get_data_surat($id);
		$data['desa'] = $this->surat_model->get_data_desa();
		$data['pribadi'] = $this->surat_model->get_data_pribadi($_POST['nik']);
		$data['pamong'] = $this->surat_model->get_pamong($_POST['pamong']);
		
	        $this->surat_keluar_model->log_surat($f,$id,$g,$u,$z);
		$this->load->view('surat/print_surat_ket_kehilangan',$data);
}
	
function surat_permohonan_akta(){
		$data['lap']="surat_permohonan_akta";
		if(isset($_POST['nik']))
			$data['individu']=$this->surat_model->get_penduduk($_POST['nik']);
		else
		$data['individu']=NULL;
		
		$data['menu_surat'] = $this->surat_model->list_surat();
		$data['penduduk'] = $this->surat_model->list_penduduk();
		$data['pamong'] = $this->surat_model->list_pamong();
		
		$data['form_action'] = site_url("surat/print_surat_permohonan_akta");
		
		$nav['act']= 1;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('surat/nav',$nav);
		$this->load->view('surat/surat_permohonan_akta',$data);
		$this->load->view('footer');
}
	
function print_surat_permohonan_akta(){
	
		$f=20;
	        $g=$_POST['pamong'];
	        $u=$_SESSION['user'];
		$z=$_POST['nomor'];
	
		$data['menu_surat'] = $this->surat_model->list_surat();
		$id = $_POST['nik'];
		$data['input'] = $_POST;
		$data['tanggal_sekarang'] = tgl_indo(date("Y m d"));
		
		$data['data'] = $this->surat_model->get_data_surat($id);
		$data['desa'] = $this->surat_model->get_data_desa();
		$data['pamong'] = $this->surat_model->get_pamong($_POST['pamong']);
		
	        $this->surat_keluar_model->log_surat($f,$id,$g,$u,$z);
		$this->load->view('surat/print_surat_permohonan_akta',$data);
}
	
function surat_pernyataan_akta(){
		$data['lap']="surat_pernyataan_akta";
		$data['menu_surat'] = $this->surat_model->list_surat();
		if(isset($_POST['nik']))
			$data['individu']=$this->surat_model->get_penduduk($_POST['nik']);
		else
		$data['individu']=NULL;
		
		$data['penduduk'] = $this->surat_model->list_penduduk();
		$data['pamong'] = $this->surat_model->list_pamong();
		
		$data['form_action'] = site_url("surat/print_surat_pernyataan_akta");
		
		$nav['act']= 1;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('surat/nav',$nav);
		$this->load->view('surat/surat_pernyataan_akta',$data);
		$this->load->view('footer');
}
	
function print_surat_pernyataan_akta(){
	
		$f=21;
	        $g=$_POST['pamong'];
	        $u=$_SESSION['user'];
		$z=$_POST['nomor'];
	
		$data['menu_surat'] = $this->surat_model->list_surat();
		$id = $_POST['nik'];
		$data['input'] = $_POST;
		$data['tanggal_sekarang'] = tgl_indo(date("Y m d"));
		
		$data['data'] = $this->surat_model->get_data_pribadi($id);
		$data['desa'] = $this->surat_model->get_data_desa();
		$data['pamong'] = $this->surat_model->get_pamong($_POST['pamong']);
		$data['penduduk']=$this->surat_model->get_penduduk_ortu($_POST['nik']);
		
	        $this->surat_keluar_model->log_surat($f,$id,$g,$u,$z);
		
		$this->load->view('surat/print_surat_pernyataan_akta',$data);
}
	
function surat_ket_kurang_mampu(){
		$data['lap']="surat_ket_kurang_mampu";
		$data['menu_surat'] = $this->surat_model->list_surat();
		if(isset($_POST['nik']))
			$data['individu']=$this->surat_model->get_penduduk($_POST['nik']);
		else
		$data['individu']=NULL;
		
		$data['penduduk'] = $this->surat_model->list_penduduk();
		$data['pamong'] = $this->surat_model->list_pamong();
		
		$data['form_action'] = site_url("surat/print_surat_ket_kurang_mampu");
		
		$nav['act']= 1;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('surat/nav',$nav);
		$this->load->view('surat/surat_ket_kurang_mampu',$data);
		$this->load->view('footer');
}
	
function print_surat_ket_kurang_mampu(){
	
	        $f=12;
	        $g=$_POST['pamong'];
	        $u=$_SESSION['user'];
	        $z=$_POST['nomor'];
		$data['menu_surat'] = $this->surat_model->list_surat();
		$id = $_POST['nik'];
		$data['input'] = $_POST;
		$data['tanggal_sekarang'] = tgl_indo(date("Y m d"));
		
		$data['data'] = $this->surat_model->get_data_surat($id);
		
		$data['desa'] = $this->surat_model->get_data_desa();
		$data['pamong'] = $this->surat_model->get_pamong($_POST['pamong']);

		$this->surat_keluar_model->log_surat($f,$id,$g,$u,$z);
		
		$this->load->view('surat/print_surat_ket_kurang_mampu',$data);
}

function surat_ket_jamkesos(){
		$data['lap']="surat_ket_jamkesos";
		$data['menu_surat'] = $this->surat_model->list_surat();
		if(isset($_POST['nik']))
			$data['individu']=$this->surat_model->get_penduduk($_POST['nik']);
		else
		$data['individu']=NULL;
		
		$data['penduduk'] = $this->surat_model->list_penduduk();
		$data['pamong'] = $this->surat_model->list_pamong();
		
		
		$data['form_action'] = site_url("surat/print_surat_ket_jamkesos");
		
		$data['menu_surat'] = $this->surat_model->list_surat();
		$nav['act']= 1;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('surat/nav',$nav);
		$this->load->view('surat/surat_ket_jamkesos',$data);
		$this->load->view('footer');
}
	
function print_surat_ket_jamkesos(){
	
	        $f=16;
	        $g=$_POST['pamong'];
	        $u=$_SESSION['user'];
		$z=$_POST['nomor'];
		$data['menu_surat'] = $this->surat_model->list_surat();
		$id = $_POST['nik'];
		$data['input'] = $_POST;
		$data['tanggal_sekarang'] = tgl_indo(date("Y m d"));
		
		$data['data'] = $this->surat_model->get_data_surat($id);
		
		$data['desa'] = $this->surat_model->get_data_desa();
		$data['pamong'] = $this->surat_model->get_pamong($_POST['pamong']);
		$this->surat_keluar_model->log_surat($f,$id,$g,$u,$z);
		$this->load->view('surat/print_surat_ket_jamkesos',$data);
}
	
function surat_ket_kelahiran(){
		$data['lap']="surat_ket_kelahiran";
		$data['menu_surat'] = $this->surat_model->list_surat();
		if(isset($_POST['nik']))
			$data['individu']=$this->surat_model->get_penduduk($_POST['nik']);
		else
		$data['individu']=NULL;
		
		$data['penduduk'] = $this->surat_model->list_penduduk();
		$data['pamong'] = $this->surat_model->list_pamong();
		
		$data['form_action'] = site_url("surat/print_surat_ket_kelahiran");
		
		$nav['act']= 1;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('surat/nav',$nav);
		$this->load->view('surat/surat_ket_kelahiran',$data);
		$this->load->view('footer');
}
	
function print_surat_ket_kelahiran(){

	        $f=18;
	        $g=$_POST['pamong'];
	        $u=$_SESSION['user'];
	       $z=$_POST['nomor']; 	
		$data['menu_surat'] = $this->surat_model->list_surat();
		$id = $_POST['nik'];
		$data['input'] = $_POST;
		$data['tanggal_sekarang'] = tgl_indo(date("Y m d"));
		
		$data['ibu'] = $this->surat_model->get_data_pribadi($id);
		$data['ayah'] = $this->surat_model->get_data_suami($id);
		$this->surat_keluar_model->log_surat($f,$id,$g,$u,$z);
		$data['desa'] = $this->surat_model->get_data_desa();
		$data['pamong'] = $this->surat_model->get_pamong($_POST['pamong']);
		
		$this->load->view('surat/print_surat_ket_kelahiran',$data);
}
	
function surat_ket_kematian(){
		$data['lap']="surat_ket_kematian";
		$data['menu_surat'] = $this->surat_model->list_surat();
		if(isset($_POST['nik']))
			$data['individu']=$this->surat_model->get_penduduk($_POST['nik']);
		else
		$data['individu']=NULL;
		
		$data['penduduk'] = $this->surat_model->list_penduduk();
		$data['pamong'] = $this->surat_model->list_pamong();
		
		$data['form_action'] = site_url("surat/print_surat_ket_kematian");
		
		$nav['act']= 1;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('surat/nav',$nav);
		$this->load->view('surat/surat_ket_kematian',$data);
		$this->load->view('footer');
}
	
function print_surat_ket_kematian(){

	        $f=24;
	        $g=$_POST['pamong'];
	        $u=$_SESSION['user'];
		$z=$_POST['nomor']; 
		$data['menu_surat'] = $this->surat_model->list_surat();
		$id = $_POST['nik'];
		$data['input'] = $_POST;
		$data['tanggal_sekarang'] = tgl_indo(date("Y m d"));
		
		$data['data'] = $this->surat_model->get_data_surat($id);
		
		$data['desa'] = $this->surat_model->get_data_desa();
		$data['pamong'] = $this->surat_model->get_pamong($_POST['pamong']);
		$this->surat_keluar_model->log_surat($f,$id,$g,$u,$z);
		$this->load->view('surat/print_surat_ket_kematian',$data);
}



function surat_ket_nikah(){
		$data['lap']="surat_ket_nikah";
		$data['menu_surat'] = $this->surat_model->list_surat();
		if(isset($_POST['nik']))
			$data['individu']=$this->surat_model->get_penduduk($_POST['nik']);
		else
		$data['individu']=NULL;
		
		$data['penduduk'] = $this->surat_model->list_penduduk();
		$data['pamong'] = $this->surat_model->list_pamong();
		
		$data['form_action'] = site_url("surat/print_surat_ket_nikah");
		
		$nav['act']= 1;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('surat/nav',$nav);
		$this->load->view('surat/surat_ket_nikah',$data);
		$this->load->view('footer');
}
	
function print_surat_ket_nikah(){
	
		$f=26;
	        $g=$_POST['pamong'];
	        $u=$_SESSION['user'];
		$z=$_POST['nomor']; 
		$data['menu_surat'] = $this->surat_model->list_surat();
		$id = $_POST['nik'];
		$data['input'] = $_POST;
		$data['tanggal_sekarang'] = tgl_indo(date("Y m d"));
		
		$data['data'] = $this->surat_model->get_data_surat($id);
		
		$data['desa'] = $this->surat_model->get_data_desa();
		$data['pamong'] = $this->surat_model->get_pamong($_POST['pamong']);
		$this->surat_keluar_model->log_surat($f,$id,$g,$u,$z);
		$this->load->view('surat/print_surat_ket_nikah',$data);
}


function surat_ket_asalusul(){
		$data['lap']="surat_ket_asalusul";
		$data['menu_surat'] = $this->surat_model->list_surat();
		if(isset($_POST['nik']))
			$data['individu']=$this->surat_model->get_penduduk($_POST['nik']);
		else
		$data['individu']=NULL;
		
		$data['penduduk'] = $this->surat_model->list_penduduk();
		$data['pamong'] = $this->surat_model->list_pamong();
		
		$data['form_action'] = site_url("surat/print_surat_ket_asalusul");
		
		$nav['act']= 1;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('surat/nav',$nav);
		$this->load->view('surat/surat_ket_asalusul',$data);
		$this->load->view('footer');
}
	
function print_surat_ket_asalusul(){
	
		$f=27;
	        $g=$_POST['pamong'];
	        $u=$_SESSION['user'];
		$z=$_POST['nomor']; 
		$data['menu_surat'] = $this->surat_model->list_surat();
		$id = $_POST['nik'];
		$data['input'] = $_POST;
		$data['tanggal_sekarang'] = tgl_indo(date("Y m d"));
		
		$data['data'] = $this->surat_model->get_data_surat($id);
		$data['ayah']=$this->surat_model->get_data_ayah($_POST['nik']);
		$data['ibu']=$this->surat_model->get_data_ibu($_POST['nik']);
		$data['desa'] = $this->surat_model->get_data_desa();
		$data['pamong'] = $this->surat_model->get_pamong($_POST['pamong']);
		$this->surat_keluar_model->log_surat($f,$id,$g,$u,$z);
		$this->load->view('surat/print_surat_ket_asalusul',$data);
}

function surat_persetujuan_mempelai(){
		$data['lap']="surat_persetujuan_mempelai";
		$data['menu_surat'] = $this->surat_model->list_surat();
		if(isset($_POST['nik']))
			$data['individu']=$this->surat_model->get_penduduk($_POST['nik']);
		else
		$data['individu']=NULL;
		
		$data['laki'] = $this->surat_model->list_penduduk_laki();
		$data['perempuan'] = $this->surat_model->list_penduduk_perempuan();
		$data['pamong'] = $this->surat_model->list_pamong();
		
		$data['form_action'] = site_url("surat/print_surat_persetujuan_mempelai");
		
		$nav['act']= 1;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('surat/nav',$nav);
		$this->load->view('surat/surat_persetujuan_mempelai',$data);
		$this->load->view('footer');
}
	
function print_surat_persetujuan_mempelai(){
		
		$f=28;
	        //$g=$_POST['pamong'];
	        $u=$_SESSION['user'];
	       // $z=$_POST['nomor']; 	        	
		$data['menu_surat'] = $this->surat_model->list_surat();
		$suami = $_POST['suami'];
		$istri = $_POST['istri'];
		$data['input'] = $_POST;
		$data['tanggal_sekarang'] = tgl_indo(date("Y m d"));
		
		$data['suami'] = $this->surat_model->get_data_pribadi($suami);
		$data['istri'] = $this->surat_model->get_data_pribadi($istri);
		
		$data['desa'] = $this->surat_model->get_data_desa();
		//$data['pamong'] = $this->surat_model->get_pamong($_POST['pamong']);
		//$this->surat_keluar_model->log_surat($f,$id,$g,$u,$z);
		$this->load->view('surat/print_surat_persetujaun_mempelai',$data);
}

function surat_ket_orangtua(){
		$data['lap']="surat_ket_orangtua";
		$data['menu_surat'] = $this->surat_model->list_surat();
		if(isset($_POST['nik']))
			$data['individu']=$this->surat_model->get_penduduk($_POST['nik']);
		else
		$data['individu']=NULL;
		
		$data['penduduk'] = $this->surat_model->list_penduduk();
		$data['pamong'] = $this->surat_model->list_pamong();
		
		$data['form_action'] = site_url("surat/print_surat_ket_orangtua");
		
		$nav['act']= 1;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('surat/nav',$nav);
		$this->load->view('surat/surat_ket_orangtua',$data);
		$this->load->view('footer');
}
	
function print_surat_ket_orangtua(){

		$f=29;
	        $g=$_POST['pamong'];
	        $u=$_SESSION['user'];
	         $z=$_POST['nomor']; 	
		$data['menu_surat'] = $this->surat_model->list_surat();
		$id = $_POST['nik'];
		$data['input'] = $_POST;
		$data['tanggal_sekarang'] = tgl_indo(date("Y m d"));
		
		$data['data'] = $this->surat_model->get_data_surat($id);
		$data['ayah'] = $this->surat_model->get_data_ayah($id);
		$data['ibu'] = $this->surat_model->get_data_ibu($id);
		$data['pribadi'] = $this->surat_model->get_data_pribadi($id);		

		$data['desa'] = $this->surat_model->get_data_desa();
		$data['pamong'] = $this->surat_model->get_pamong($_POST['pamong']);
		$this->surat_keluar_model->log_surat($f,$id,$g,$u,$z);
		$this->load->view('surat/print_surat_ket_orangtua',$data);
}

function surat_izin_orangtua(){
		$data['lap']="surat_izin_orangtua";
		$data['menu_surat'] = $this->surat_model->list_surat();
		if(isset($_POST['nik']))
			$data['individu']=$this->surat_model->get_penduduk($_POST['nik']);
		else
		$data['individu']=NULL;
		
		$data['penduduk'] = $this->surat_model->list_penduduk();
		$data['pamong'] = $this->surat_model->list_pamong();
		
		$data['form_action'] = site_url("surat/print_surat_izin_orangtua");
		
		$nav['act']= 1;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('surat/nav',$nav);
		$this->load->view('surat/surat_izin_orangtua',$data);
		$this->load->view('footer');
}
	
function print_surat_izin_orangtua(){

		$f=30;
	        $g=$_POST['pamong'];
	        $u=$_SESSION['user'];
	         $z=$_POST['nomor']; 	
		$data['menu_surat'] = $this->surat_model->list_surat();
		$id = $_POST['nik'];
		$data['input'] = $_POST;
		$data['tanggal_sekarang'] = tgl_indo(date("Y m d"));
		
		$data['data'] = $this->surat_model->get_data_surat($id);
		$data['pribadi'] = $this->surat_model->get_data_pribadi($id);
		$data['ayah'] = $this->surat_model->get_data_ayah($id);
		$data['ibu'] = $this->surat_model->get_data_ibu($id);
		$data['desa'] = $this->surat_model->get_data_desa();
		$data['pamong'] = $this->surat_model->get_pamong($_POST['pamong']);
		$this->surat_keluar_model->log_surat($f,$id,$g,$u,$z);
		$this->load->view('surat/print_surat_izin_orangtua',$data);
}

function surat_ket_kematian_suami_istri(){
		$data['lap']="surat_ket_kematian_suami_istri";
		$data['menu_surat'] = $this->surat_model->list_surat();
		if(isset($_POST['nik']))
			$data['individu']=$this->surat_model->get_penduduk($_POST['nik']);
		else
		$data['individu']=NULL;
		
		$data['penduduk'] = $this->surat_model->list_penduduk();
		$data['pamong'] = $this->surat_model->list_pamong();
		
		$data['form_action'] = site_url("surat/print_surat_ket_kematian_suami_istri");
		
		$nav['act']= 1;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('surat/nav',$nav);
		$this->load->view('surat/surat_ket_kematian_suami_istri',$data);
		$this->load->view('footer');
}
	
function print_surat_ket_kematian_suami_istri(){

		$f=31;
	        $g=$_POST['pamong'];
	        $u=$_SESSION['user'];
	 	$z=$_POST['nomor']; 
		$data['menu_surat'] = $this->surat_model->list_surat();
		$id = $_POST['nik'];
		$data['input'] = $_POST;
		$data['tanggal_sekarang'] = tgl_indo(date("Y m d"));
		
		$data['data'] = $this->surat_model->get_data_surat($id);
		$data['pribadi'] = $this->surat_model->get_data_pribadi($id);
		
		$data['desa'] = $this->surat_model->get_data_desa();
		$data['pamong'] = $this->surat_model->get_pamong($_POST['pamong']);
		$this->surat_keluar_model->log_surat($f,$id,$g,$u,$z);
		$this->load->view('surat/print_surat_ket_kematian_suami_istri',$data);
}

function surat_kehendak_nikah(){
		$data['lap']="surat_kehendak_nikah";
		$data['menu_surat'] = $this->surat_model->list_surat();
		if(isset($_POST['nik']))
			$data['individu']=$this->surat_model->get_penduduk($_POST['nik']);
		else
		$data['individu']=NULL;
		
		$data['penduduk'] = $this->surat_model->list_penduduk();
		$data['pamong'] = $this->surat_model->list_pamong();
		
		$data['form_action'] = site_url("surat/print_surat_kehendak_nikah");
		
		$nav['act']= 1;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('surat/nav',$nav);
		$this->load->view('surat/surat_kehendak_nikah',$data);
		$this->load->view('footer');
}
	
function print_surat_kehendak_nikah(){

		$f=32;
	        $g=$_POST['pamong'];
	        $u=$_SESSION['user'];
	         $z=$_POST['nomor']; 		
		$id = $_POST['nik'];
		$data['input'] = $_POST;
		$data['tanggal_sekarang'] = tgl_indo(date("Y m d"));
		
		$data['menu_surat'] = $this->surat_model->list_surat();
		$data['data'] = $this->surat_model->get_data_surat($id);
		
		$data['desa'] = $this->surat_model->get_data_desa();
		$data['pamong'] = $this->surat_model->get_pamong($_POST['pamong']);
		$this->surat_keluar_model->log_surat($f,$id,$g,$u,$z);
		$this->load->view('surat/print_surat_kehendak_nikah',$data);
}

function surat_dispensasi_waktu(){
		$data['lap']="surat_dispensasi_waktu";
		if(isset($_POST['nik']))
			$data['individu']=$this->surat_model->get_penduduk($_POST['nik']);
		else
		$data['individu']=NULL;
		
		$data['menu_surat'] = $this->surat_model->list_surat();
		$data['penduduk'] = $this->surat_model->list_penduduk();
		$data['pamong'] = $this->surat_model->list_pamong();
		
		$data['form_action'] = site_url("surat/print_surat_dispensasi_waktu");
		
		$nav['act']= 1;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('surat/nav',$nav);
		$this->load->view('surat/surat_dispensasi_waktu',$data);
		$this->load->view('footer');
}
	
function print_surat_dispensasi_waktu(){

		$f=19;
	        $g=$_POST['pamong'];
	        $u=$_SESSION['user'];
	         $z=$_POST['nomor']; 		
		$id = $_POST['nik'];
		$data['input'] = $_POST;
		$data['tanggal_sekarang'] = tgl_indo(date("Y m d"));
		
		$data['menu_surat'] = $this->surat_model->list_surat();
		$data['data'] = $this->surat_model->get_data_surat($id);
		
		$data['desa'] = $this->surat_model->get_data_desa();
		$data['pamong'] = $this->surat_model->get_pamong($_POST['pamong']);
		$this->surat_keluar_model->log_surat($f,$id,$g,$u,$z);
		$this->load->view('surat/print_surat_dispensasi_waktu',$data);
}

function surat_ket_rujuk_cerai(){
		$data['lap']="surat_ket_rujuk_cerai";
		if(isset($_POST['nik']))
			$data['individu']=$this->surat_model->get_penduduk($_POST['nik']);
		else
		$data['individu']=NULL;
		
		$data['menu_surat'] = $this->surat_model->list_surat();
		$data['penduduk'] = $this->surat_model->list_penduduk();
		$data['pamong'] = $this->surat_model->list_pamong();
		
		$data['form_action'] = site_url("surat/print_surat_ket_rujuk_cerai");
		
		$nav['act']= 1;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('surat/nav',$nav);
		$this->load->view('surat/surat_ket_rujuk_cerai',$data);
		$this->load->view('footer');
}
	
function print_surat_ket_rujuk_cerai(){

		$f=38;
	        $g=$_POST['pamong'];
	        $u=$_SESSION['user'];
	         $z=$_POST['nomor']; 		
		$id = $_POST['nik'];
		$data['input'] = $_POST;
		$data['tanggal_sekarang'] = tgl_indo(date("Y m d"));
		$data['pribadi'] = $this->surat_model->get_data_pribadi($id);
		$data['data'] = $this->surat_model->get_data_surat($id);
		$this->surat_keluar_model->log_surat($f,$id,$g,$u,$z);
		$data['menu_surat'] = $this->surat_model->list_surat();
		$data['desa'] = $this->surat_model->get_data_desa();
		$data['pamong'] = $this->surat_model->get_pamong($_POST['pamong']);
		$this->load->view('surat/print_surat_ket_rujuk_cerai',$data);
}

function surat_permohonan_cerai(){
		$data['lap']="surat_permohonan_cerai";
		if(isset($_POST['nik']))
			$data['individu']=$this->surat_model->get_penduduk($_POST['nik']);
		else
		$data['individu']=NULL;
		
		$data['menu_surat'] = $this->surat_model->list_surat();
		$data['penduduk'] = $this->surat_model->list_penduduk();
		$data['pamong'] = $this->surat_model->list_pamong();
		
		$data['form_action'] = site_url("surat/print_surat_permohonan_cerai");
		
		$nav['act']= 1;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('surat/nav',$nav);
		$this->load->view('surat/surat_permohonan_cerai',$data);
		$this->load->view('footer');
}
	
function print_surat_permohonan_cerai(){

		$f=37;
	        $g=$_POST['pamong'];
	        $u=$_SESSION['user'];
	         $z=$_POST['nomor']; 		
		$id = $_POST['nik'];
		$data['input'] = $_POST;
		$data['tanggal_sekarang'] = tgl_indo(date("Y m d"));
		$data['istri'] = $this->surat_model->get_data_istri($id);
		$data['pribadi'] = $this->surat_model->get_data_pribadi($id);
		$data['data'] = $this->surat_model->get_data_surat($id);
		$this->surat_keluar_model->log_surat($f,$id,$g,$u,$z);
		$data['menu_surat'] = $this->surat_model->list_surat();
		$data['desa'] = $this->surat_model->get_data_desa();
		$data['pamong'] = $this->surat_model->get_pamong($_POST['pamong']);
		$this->load->view('surat/print_surat_permohonan_cerai',$data);
}

function surat_jalan(){
		$data['lap']="surat_jalan";
		$data['menu_surat'] = $this->surat_model->list_surat();
		if(isset($_POST['nik']))
			$data['individu']=$this->surat_model->get_penduduk($_POST['nik']);
		else
		$data['individu']=NULL;
		//$id = $_POST['nik'];
		$data['penduduk'] = $this->surat_model->list_penduduk();
		$data['pamong'] = $this->surat_model->list_pamong();
		
		//$data['pribadi'] = $this->surat_model->get_data_pribadi($id);
		$data['form_action'] = site_url("surat/print_surat_jalan");
		$nav['act']= 1;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('surat/nav',$nav);
		$this->load->view('surat/surat_jalan',$data);
		$this->load->view('footer');
}

function print_surat_jalan(){
	
		$f=11;
	        $g=$_POST['pamong'];
	        $u=$_SESSION['user'];
	        $z=$_POST['nomor'];
	
		$data['menu_surat'] = $this->surat_model->list_surat();
		$id = $_POST['nik'];
		$data['input'] = $_POST;
		$data['tanggal_sekarang'] = tgl_indo(date("Y m d"));
		
		$data['data'] = $this->surat_model->get_data_surat($id);
		$data['desa'] = $this->surat_model->get_data_desa();
		//$data['pribadi'] = $this->surat_model->get_data_pribadi($id);
		$data['pamong'] = $this->surat_model->get_pamong($_POST['pamong']);
		
	        $this->surat_keluar_model->log_surat($f,$id,$g,$u,$z);
		
		$this->load->view('surat/print_surat_jalan',$data);
}
function surat_ket_pergi_kawin(){
		$data['lap']="surat_ket_pergi_kawin";
		$data['menu_surat'] = $this->surat_model->list_surat();
		if(isset($_POST['nik']))
			$data['individu']=$this->surat_model->get_penduduk($_POST['nik']);
		else
		$data['individu']=NULL;
		//$id = $_POST['nik'];
		$data['penduduk'] = $this->surat_model->list_penduduk();
		$data['pamong'] = $this->surat_model->list_pamong();
		
		//$data['pribadi'] = $this->surat_model->get_data_pribadi($id);
		$data['form_action'] = site_url("surat/print_surat_pergi_kawin");
		$nav['act']= 1;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('surat/nav',$nav);
		$this->load->view('surat/surat_pergi_kawin',$data);
		$this->load->view('footer');
}

function print_surat_pergi_kawin(){
	
		$f=33;
	        $g=$_POST['pamong'];
	        $u=$_SESSION['user'];
	        $z=$_POST['nomor'];
	
		$data['menu_surat'] = $this->surat_model->list_surat();
		$id = $_POST['nik'];
		$data['input'] = $_POST;
		$data['tanggal_sekarang'] = tgl_indo(date("Y m d"));
		
		$data['data'] = $this->surat_model->get_data_surat($id);
		$data['desa'] = $this->surat_model->get_data_desa();
		//$data['pribadi'] = $this->surat_model->get_data_pribadi($id);
		$data['pamong'] = $this->surat_model->get_pamong($_POST['pamong']);
		
	        $this->surat_keluar_model->log_surat($f,$id,$g,$u,$z);
		
		$this->load->view('surat/print_surat_pergi_kawin',$data);
}
function surat_izin_keramaian(){
		$data['lap']="surat_izin_keramaian";
		$data['menu_surat'] = $this->surat_model->list_surat();
		if(isset($_POST['nik']))
			$data['individu']=$this->surat_model->get_penduduk($_POST['nik']);
		else
		$data['individu']=NULL;
		//$id = $_POST['nik'];
		$data['penduduk'] = $this->surat_model->list_penduduk();
		$data['pamong'] = $this->surat_model->list_pamong();
		
		//$data['pribadi'] = $this->surat_model->get_data_pribadi($id);
		$data['form_action'] = site_url("surat/print_surat_izin_keramaian");
		$nav['act']= 1;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('surat/nav',$nav);
		$this->load->view('surat/surat_izin_keramaian',$data);
		$this->load->view('footer');
}

function print_surat_izin_keramaian(){
	
		$f=13;
	        $g=$_POST['pamong'];
	        $u=$_SESSION['user'];
	        $z=$_POST['nomor'];
	
		$data['menu_surat'] = $this->surat_model->list_surat();
		$id = $_POST['nik'];
		$data['input'] = $_POST;
		$data['tanggal_sekarang'] = tgl_indo(date("Y m d"));
		
		$data['data'] = $this->surat_model->get_data_surat($id);
		$data['desa'] = $this->surat_model->get_data_desa();
		//$data['pribadi'] = $this->surat_model->get_data_pribadi($id);
		$data['pamong'] = $this->surat_model->get_pamong($_POST['pamong']);
		
	        $this->surat_keluar_model->log_surat($f,$id,$g,$u,$z);
		
		$this->load->view('surat/print_surat_izin_keramaian',$data);
}
function surat_lap_kehilangan(){
		$data['lap']="surat_lap_kehilangan";
		$data['menu_surat'] = $this->surat_model->list_surat();
		if(isset($_POST['nik']))
			$data['individu']=$this->surat_model->get_penduduk($_POST['nik']);
		else
		$data['individu']=NULL;
		//$id = $_POST['nik'];
		$data['penduduk'] = $this->surat_model->list_penduduk();
		$data['pamong'] = $this->surat_model->list_pamong();
		
		//$data['pribadi'] = $this->surat_model->get_data_pribadi($id);
		$data['form_action'] = site_url("surat/print_surat_kehilangan");
		$nav['act']= 1;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('surat/nav',$nav);
		$this->load->view('surat/surat_kehilangan',$data);
		$this->load->view('footer');
}

function print_surat_kehilangan(){
	
		$f=14;
	        $g=$_POST['pamong'];
	        $u=$_SESSION['user'];
	        $z=$_POST['nomor'];
	
		$data['menu_surat'] = $this->surat_model->list_surat();
		$id = $_POST['nik'];
		$data['input'] = $_POST;
		$data['tanggal_sekarang'] = tgl_indo(date("Y m d"));
		
		$data['data'] = $this->surat_model->get_data_surat($id);
		$data['desa'] = $this->surat_model->get_data_desa();
		//$data['pribadi'] = $this->surat_model->get_data_pribadi($id);
		$data['pamong'] = $this->surat_model->get_pamong($_POST['pamong']);
		
	        $this->surat_keluar_model->log_surat($f,$id,$g,$u,$z);
		
		$this->load->view('surat/print_surat_kehilangan',$data);
}


function surat_permohonan_duplikat_kelahiran(){
		$data['lap']="surat_permohonan_duplikat_kelahiran";
		$data['menu_surat'] = $this->surat_model->list_surat();
		if(isset($_POST['nik']))
			$data['individu']=$this->surat_model->get_penduduk($_POST['nik']);
		else
		$data['individu']=NULL;
		//$id = $_POST['nik'];
		$data['penduduk'] = $this->surat_model->list_penduduk();
		$data['pamong'] = $this->surat_model->list_pamong();
		
		//$data['pribadi'] = $this->surat_model->get_data_pribadi($id);
		$data['form_action'] = site_url("surat/print_surat_permohonan_duplikat_kelahiran");
		$nav['act']= 1;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('surat/nav',$nav);
		$this->load->view('surat/surat_permohonan_duplikat_kelahiran',$data);
		$this->load->view('footer');
}

function print_surat_permohonan_duplikat_kelahiran(){
	
		$f=22;
	        $g=$_POST['pamong'];
	        $u=$_SESSION['user'];
	        $z=$_POST['nomor'];
	
		$data['menu_surat'] = $this->surat_model->list_surat();
		$id = $_POST['nik'];
		$data['input'] = $_POST;
		$data['tanggal_sekarang'] = tgl_indo(date("Y m d"));
		
		$data['data'] = $this->surat_model->get_data_pribadi($id);
		$data['ibu'] = $this->surat_model->get_data_ibu($id);
		$data['ayah'] = $this->surat_model->get_data_ayah($id);
		$data['desa'] = $this->surat_model->get_data_desa();
		//$data['pribadi'] = $this->surat_model->get_data_pribadi($id);
		$data['pamong'] = $this->surat_model->get_pamong($_POST['pamong']);
		
	        $this->surat_keluar_model->log_surat($f,$id,$g,$u,$z);
		
		$this->load->view('surat/print_surat_permohonan_duplikat_kelahiran',$data);
}

function surat_ket_lahir_mati(){
		$data['lap']="surat_ket_lahir_mati";
		$data['menu_surat'] = $this->surat_model->list_surat();
		if(isset($_POST['nik']))
			$data['individu']=$this->surat_model->get_penduduk($_POST['nik']);
		else
		$data['individu']=NULL;
		//$id = $_POST['nik'];
		$data['penduduk'] = $this->surat_model->list_penduduk();
		$data['pamong'] = $this->surat_model->list_pamong();
		
		//$data['pribadi'] = $this->surat_model->get_data_pribadi($id);
		$data['form_action'] = site_url("surat/print_surat_ket_lahir_mati");
		$nav['act']= 1;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('surat/nav',$nav);
		$this->load->view('surat/surat_ket_lahir_mati',$data);
		$this->load->view('footer');
}

function print_surat_ket_lahir_mati(){
	
		$f=25;
	        $g=$_POST['pamong'];
	        $u=$_SESSION['user'];
	        $z=$_POST['nomor'];
	
		$data['menu_surat'] = $this->surat_model->list_surat();
		$id = $_POST['nik'];
		$data['input'] = $_POST;
		$data['tanggal_sekarang'] = tgl_indo(date("Y m d"));
		
		$data['data'] = $this->surat_model->get_data_surat($id);
		$data['desa'] = $this->surat_model->get_data_desa();
		//$data['pribadi'] = $this->surat_model->get_data_pribadi($id);
		$data['pamong'] = $this->surat_model->get_pamong($_POST['pamong']);
		
	        $this->surat_keluar_model->log_surat($f,$id,$g,$u,$z);
		
		$this->load->view('surat/print_surat_ket_lahir_mati',$data);
}



function surat_permohonan_duplikat_surat_nikah(){
		$data['lap']="surat_permohonan_duplikat_surat_nikah";
		$data['menu_surat'] = $this->surat_model->list_surat();
		if(isset($_POST['nik']))
			$data['individu']=$this->surat_model->get_penduduk($_POST['nik']);
		else
		$data['individu']=NULL;
		//$id = $_POST['nik'];
		$data['penduduk'] = $this->surat_model->list_penduduk();
		$data['pamong'] = $this->surat_model->list_pamong();
		
		//$data['pribadi'] = $this->surat_model->get_data_pribadi($id);
		$data['form_action'] = site_url("surat/print_surat_permohonan_duplikat_surat_nikah");
		$nav['act']= 1;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('surat/nav',$nav);
		$this->load->view('surat/surat_permohonan_duplikat_surat_nikah',$data);
		$this->load->view('footer');
}

function print_surat_permohonan_duplikat_surat_nikah(){
	
		$f=36;
	        $g=$_POST['pamong'];
	        $u=$_SESSION['user'];
	        $z=$_POST['nomor'];
	
		$data['menu_surat'] = $this->surat_model->list_surat();
		$id = $_POST['nik'];
		$data['input'] = $_POST;
		$data['tanggal_sekarang'] = tgl_indo(date("Y m d"));
		
		$data['data'] = $this->surat_model->get_data_surat($id);
		$data['desa'] = $this->surat_model->get_data_desa();
		//$data['pribadi'] = $this->surat_model->get_data_pribadi($id);
		$data['pamong'] = $this->surat_model->get_pamong($_POST['pamong']);
		
	        $this->surat_keluar_model->log_surat($f,$id,$g,$u,$z);
		
		$this->load->view('surat/print_surat_permohonan_duplikat_surat_nikah',$data);
}

function surat_wali(){
		$data['lap']="surat_wali";
		$data['menu_surat'] = $this->surat_model->list_surat();
		if(isset($_POST['nik']))
			$data['individu']=$this->surat_model->get_penduduk($_POST['nik']);
		else
		$data['individu']=NULL;
		//$id = $_POST['nik'];
		$data['penduduk'] = $this->surat_model->list_penduduk();
		$data['pamong'] = $this->surat_model->list_pamong();
		
		//$data['pribadi'] = $this->surat_model->get_data_pribadi($id);
		$data['form_action'] = site_url("surat/print_surat_ket_wali");
		$nav['act']= 1;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('surat/nav',$nav);
		$this->load->view('surat/surat_ket_wali',$data);
		$this->load->view('footer');
}

function print_surat_ket_wali(){
	
		$f=34;
	        $g=$_POST['pamong'];
	        $u=$_SESSION['user'];
	        $z=$_POST['nomor'];
	
		$data['menu_surat'] = $this->surat_model->list_surat();
		$id = $_POST['nik'];
		$data['input'] = $_POST;
		$data['tanggal_sekarang'] = tgl_indo(date("Y m d"));
		
		$data['data'] = $this->surat_model->get_data_surat($id);
		$data['desa'] = $this->surat_model->get_data_desa();
		//$data['pribadi'] = $this->surat_model->get_data_pribadi($id);
		$data['pamong'] = $this->surat_model->get_pamong($_POST['pamong']);
		
	        $this->surat_keluar_model->log_surat($f,$id,$g,$u,$z);
		
		$this->load->view('surat/print_surat_ket_wali',$data);
}
function surat_wali_hakim(){
		$data['lap']="surat_wali_hakim";
		$data['menu_surat'] = $this->surat_model->list_surat();
		if(isset($_POST['nik']))
			$data['individu']=$this->surat_model->get_penduduk($_POST['nik']);
		else
		$data['individu']=NULL;
		//$id = $_POST['nik'];
		$data['penduduk'] = $this->surat_model->list_penduduk();
		$data['pamong'] = $this->surat_model->list_pamong();
		
		//$data['pribadi'] = $this->surat_model->get_data_pribadi($id);
		$data['form_action'] = site_url("surat/print_surat_ket_wali_hakim");
		$nav['act']= 1;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('surat/nav',$nav);
		$this->load->view('surat/surat_ket_wali_hakim',$data);
		$this->load->view('footer');
}

function print_surat_ket_wali_hakim(){
	
		$f=35;
	        $g=$_POST['pamong'];
	        $u=$_SESSION['user'];
	        $z=$_POST['nomor'];
	
		$data['menu_surat'] = $this->surat_model->list_surat();
		$id = $_POST['nik'];
		$data['input'] = $_POST;
		$data['tanggal_sekarang'] = tgl_indo(date("Y m d"));
		
		$data['data'] = $this->surat_model->get_data_surat($id);
		$data['desa'] = $this->surat_model->get_data_desa();
		//$data['pribadi'] = $this->surat_model->get_data_pribadi($id);
		$data['pamong'] = $this->surat_model->get_pamong($_POST['pamong']);
		
	        $this->surat_keluar_model->log_surat($f,$id,$g,$u,$z);
		
		$this->load->view('surat/print_surat_ket_wali_hakim',$data);
}
function surat_ket_ktp_dlm_proses(){
		$data['lap']="surat_ket_ktp_dlm_proses";
		$data['menu_surat'] = $this->surat_model->list_surat();
		if(isset($_POST['nik']))
			$data['individu']=$this->surat_model->get_penduduk($_POST['nik']);
		else
		$data['individu']=NULL;
		//$id = $_POST['nik'];
		$data['penduduk'] = $this->surat_model->list_penduduk();
		$data['pamong'] = $this->surat_model->list_pamong();
		
		//$data['pribadi'] = $this->surat_model->get_data_pribadi($id);
		$data['form_action'] = site_url("surat/print_surat_ket_ktp_dalam_proses");
		$nav['act']= 1;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('surat/nav',$nav);
		$this->load->view('surat/surat_ket_ktp_dalam_proses',$data);
		$this->load->view('footer');
}

function print_surat_ket_ktp_dalam_proses(){
	
		$f=9;
	        $g=$_POST['pamong'];
	        $u=$_SESSION['user'];
	        $z=$_POST['nomor'];
	
		$data['menu_surat'] = $this->surat_model->list_surat();
		$id = $_POST['nik'];
		$data['input'] = $_POST;
		$data['tanggal_sekarang'] = tgl_indo(date("Y m d"));
		
		$data['data'] = $this->surat_model->get_data_surat($id);
		$data['desa'] = $this->surat_model->get_data_desa();
		//$data['pribadi'] = $this->surat_model->get_data_pribadi($id);
		$data['pamong'] = $this->surat_model->get_pamong($_POST['pamong']);
		
	        $this->surat_keluar_model->log_surat($f,$id,$g,$u,$z);
		
		$this->load->view('surat/print_surat_ket_ktp_dalam_proses',$data);
}
function surat_pindah_antar_kab_prov(){
		$data['lap']="surat_pindah_antar_kab_prov";
		$data['menu_surat'] = $this->surat_model->list_surat();
		if(isset($_POST['nik']))
			$data['individu']=$this->surat_model->get_penduduk($_POST['nik']);
		else
		$data['individu']=NULL;
		//$id = $_POST['nik'];
		$data['penduduk'] = $this->surat_model->list_penduduk();
		$data['pamong'] = $this->surat_model->list_pamong();
		
		//$data['pribadi'] = $this->surat_model->get_data_pribadi($id);
		$data['form_action'] = site_url("surat/print_surat_pindah_antar_kab_prov");
		$nav['act']= 1;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('surat/nav',$nav);
		$this->load->view('surat/surat_pindah_antar_kab_prov',$data);
		$this->load->view('footer');
}

function print_surat_pindah_antar_kab_prov(){
	
		$f=7;
	        $g=$_POST['pamong'];
	        $u=$_SESSION['user'];
	        $z=$_POST['nomor'];
	
		$data['menu_surat'] = $this->surat_model->list_surat();
		$id = $_POST['nik'];
		$data['input'] = $_POST;
		$data['tanggal_sekarang'] = tgl_indo(date("Y m d"));
		
		$data['data'] = $this->surat_model->get_data_surat($id);
		$data['desa'] = $this->surat_model->get_data_desa();
		//$data['pribadi'] = $this->surat_model->get_data_pribadi($id);
		$data['pamong'] = $this->surat_model->get_pamong($_POST['pamong']);
		
	        $this->surat_keluar_model->log_surat($f,$id,$g,$u,$z);
		
		$this->load->view('surat/print_surat_pindah_antar_kab_prov',$data);
}

function surat_ket_beda_nama(){
		$data['lap']="surat_ket_beda_nama";
		$data['menu_surat'] = $this->surat_model->list_surat();
		if(isset($_POST['nik']))
			$data['individu']=$this->surat_model->get_penduduk($_POST['nik']);
		else
		$data['individu']=NULL;
		//$id = $_POST['nik'];
		$data['penduduk'] = $this->surat_model->list_penduduk();
		$data['pamong'] = $this->surat_model->list_pamong();
		
		//$data['pribadi'] = $this->surat_model->get_data_pribadi($id);
		$data['form_action'] = site_url("surat/print_surat_ket_beda_nama");
		$nav['act']= 1;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('surat/nav',$nav);
		$this->load->view('surat/surat_ket_beda_nama',$data);
		$this->load->view('footer');
}

function print_surat_ket_beda_nama(){
	
		$f=10;
	        $g=$_POST['pamong'];
	        $u=$_SESSION['user'];
	        $z=$_POST['nomor'];
	
		$data['menu_surat'] = $this->surat_model->list_surat();
		$id = $_POST['nik'];
		$data['input'] = $_POST;
		$data['tanggal_sekarang'] = tgl_indo(date("Y m d"));
		
		$data['data'] = $this->surat_model->get_data_surat($id);
		$data['desa'] = $this->surat_model->get_data_desa();
		$data['pribadi'] = $this->surat_model->get_data_pribadi($id);
		$data['pamong'] = $this->surat_model->get_pamong($_POST['pamong']);
		
	        $this->surat_keluar_model->log_surat($f,$id,$g,$u,$z);
		
		$this->load->view('surat/print_surat_ket_beda_nama',$data);
}

function surat_ket_jual_beli(){
		$data['lap']="surat_ket_jual_beli";
		$data['menu_surat'] = $this->surat_model->list_surat();
		if(isset($_POST['nik']))
			$data['individu']=$this->surat_model->get_penduduk($_POST['nik']);
		else
		$data['individu']=NULL;
		//$id = $_POST['nik'];
		$data['penduduk'] = $this->surat_model->list_penduduk();
		$data['pamong'] = $this->surat_model->list_pamong();
		
		//$data['pribadi'] = $this->surat_model->get_data_pribadi($id);
		$data['form_action'] = site_url("surat/print_surat_ket_jual_beli");
		$nav['act']= 1;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('surat/nav',$nav);
		$this->load->view('surat/surat_ket_jual_beli',$data);
		$this->load->view('footer');
}

function print_surat_ket_jual_beli(){
	
		$f=6;
	        $g=$_POST['pamong'];
	        $u=$_SESSION['user'];
	        $z=$_POST['nomor'];
	
		$data['menu_surat'] = $this->surat_model->list_surat();
		$id = $_POST['nik'];
		$data['input'] = $_POST;
		$data['tanggal_sekarang'] = tgl_indo(date("Y m d"));
		
		$data['data'] = $this->surat_model->get_data_surat($id);
		$data['desa'] = $this->surat_model->get_data_desa();
		$data['pribadi'] = $this->surat_model->get_data_pribadi($id);
		$data['pamong'] = $this->surat_model->get_pamong($_POST['pamong']);
		
	        $this->surat_keluar_model->log_surat($f,$id,$g,$u,$z);
		
		$this->load->view('surat/print_surat_ket_jual_beli',$data);
}


function surat_ubah_sesuaikan(){
		$data['lap']="surat_ubah_sesuaikan";
		if(isset($_POST['nik']))
			$data['individu']=$this->surat_model->get_penduduk($_POST['nik']);
		else
		$data['individu']=NULL;
		
		$data['menu_surat'] = $this->surat_model->list_surat();
		$data['penduduk'] = $this->surat_model->list_penduduk();
		$data['pamong'] = $this->surat_model->list_pamong();
		
		$data['form_action'] = site_url("surat/print_surat_ubah_sesuaikan");
		$nav['act']= 1;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$this->load->view('surat/nav',$nav);
		$this->load->view('surat/surat_ubah_sesuaikan',$data);
		
		$this->load->view('footer');
}

function print_surat_ubah_sesuaikan(){
	
	$f=39;
	$g=$_POST['pamong'];
	$u=$_SESSION['user'];
        $z=$_POST['nomor'];
	
	$data['menu_surat'] = $this->surat_model->list_surat();
	$id = $_POST['nik'];
	$data['input'] = $_POST;
	$data['tanggal_sekarang'] = tgl_indo(date("Y m d"));
	
	$data['data'] = $this->surat_model->get_data_surat($id);
	$data['desa'] = $this->surat_model->get_data_desa();
	$data['pamong'] = $this->surat_model->get_pamong($_POST['pamong']);
		
	$this->surat_keluar_model->log_surat($f,$id,$g,$u,$z);
	$this->load->view('surat/print_surat_ubah_sesuaikan',$data);
}

}
