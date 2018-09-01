<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Laporan_penduduk extends CI_Controller{

function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('user_model');
		$this->load->model('analisis_laporan_penduduk_model');
		$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
		if($grup!=1 AND $grup!=2 AND $grup!=3) {
			if(empty($grup))
				$_SESSION['request_uri'] = $_SERVER['REQUEST_URI'];
			else
				unset($_SESSION['request_uri']);
			redirect('siteman');
		}
		$this->load->model('header_model');
		$this->load->model('header_model');
	}


	function index($lap=0,$p=1,$o=0){

		$data['p']        = $p;
		$data['o']        = $o;

		if(isset($_POST['per_page']))
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];

		if(isset($_SESSION['bulan'])){
			$data['bulan'] = $_SESSION['bulan'];}
		else {$data['bulan'] = date("m");
		        $_SESSION['bulan']=date("m");}

		if(isset($_SESSION['tahun'])){
			$data['tahun'] = $_SESSION['tahun'];}
		else {$data['tahun'] = date("Y");
		$_SESSION['tahun'] = date("Y");
		}

		$data['paging']  = $this->analisis_laporan_penduduk_model->paging($lap,$p,$o);
		$data['main']    = $this->analisis_laporan_penduduk_model->list_data($lap,$o, $data['paging']->offset, $data['paging']->per_page);
		//$data['keyword'] = $this->laporan_penduduk_model->autocomplete();
		$data['lap']=$lap;

		switch($lap){
			case 0: $data['stat'] = "Pendidikan"; break;
			case 1: $data['stat'] = "Pekerjaan"; break;
			case 2: $data['stat'] = "Status Perkawinan"; break;
			case 3: $data['stat'] = "Agama"; break;
			case 4: $data['stat'] = "Jenis Kelamin"; break;
			case 5: $data['stat'] = "Warga Negara"; break;
			case 6: $data['stat'] = "Hubungan"; break;
			default:$data['stat'] = "Status";
		}

		$nav['act']= 5;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);

		$this->load->view('analisis/nav',$nav);
		$this->load->view('analisis/laporan/penduduk',$data);
		$this->load->view('footer');
	}


	function graph_laporan(){

		$data['config']  = $this->analisis_laporan_penduduk_model->get_config();
		$data['grafik'] = $this->analisis_laporan_penduduk_model->list_graph();
		$data['form_action_kembali'] = site_url("analisis/laporan_penduduk");
		$nav['act']= 5;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);

		$this->load->view('analisis/nav',$nav);
		$this->load->view('analisis/laporan/graph_penduduk_laporan',$data);
		$this->load->view('footer');
	}
	function tanya($id=''){
		$data['config']  = $this->analisis_laporan_penduduk_model->get_config();
		$data['grafik'] = $this->analisis_laporan_penduduk_model->list_graph_tanya($id);
		$data['tanya'] = $this->analisis_laporan_penduduk_model->get_tanya($id);
		$data['form_action_kembali'] = site_url("analisis/laporan_penduduk/");

		$nav['act']= 5;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);

		$this->load->view('analisis/nav',$nav);
		$this->load->view('analisis/laporan/graph_penduduk_laporan_tanya',$data);
		$this->load->view('footer');
	}
	function jawaban($id='',$id2=''){
		$data['config']  = $this->analisis_laporan_penduduk_model->get_config();
		$data['grafik'] = $this->analisis_laporan_penduduk_model->list_graph_jawab($id,$id2);
		$data['jawab'] = $this->analisis_laporan_penduduk_model->get_jawab($id2);
		$data['tanya'] = $this->analisis_laporan_penduduk_model->get_tanya($id);
		$data['form_action_kembali'] = site_url("analisis/laporan_penduduk");

		$nav['act']= 5;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);

		$this->load->view('analisis/nav',$nav);
		$this->load->view('analisis/laporan/graph_penduduk_laporan_jawab',$data);
		$this->load->view('footer');
	}
	 function detail($stat=0,$jwb=0,$thn='',$bln='',$jns=0,$lap=0,$p=1,$o=0){

		$data['p']        = $p;
		$data['o']        = $o;
		if(empty($thn)){
			$thn=$_SESSION['tahun'];}
		if(empty($bln)){
			$bln=$_SESSION['bulan'];}
		$data['thn']        = $thn;
		$data['bln']        = $bln;
		if($jns==1){
			$data['jk']        = " LAKI-LAKI";}
		if($jns==2){
			$data['jk']        = " PEREMPUAN ";}
		if($jns==0){
			$data['jk']        = "";}

		if(isset($_POST['per_page']))
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];


		$data['main']    = $this->analisis_laporan_penduduk_model->list_data_detail($stat,$jwb,$thn,$bln,$jns,$lap,$o, $data['paging']->offset, $data['paging']->per_page);
		$data['tanya']    = $this->analisis_laporan_penduduk_model->get_master_analisis_penduduk($stat);
		$data['jawab']    = $this->analisis_laporan_penduduk_model->get_sub_analisis_penduduk($jwb);

		$nav['act']= 5;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);

		$this->load->view('analisis/nav',$nav);
		$this->load->view('analisis/laporan/penduduk_detail',$data);
		$this->load->view('footer');
	}
function lap_detail_a($p=1,$o=0){

		$data['p']        = $p;
		$data['o']        = $o;

		if(isset($_SESSION['cari']))
			$data['cari'] = $_SESSION['cari'];
		else $data['cari'] = '';

		if(isset($_SESSION['filter']))
			$data['filter'] = $_SESSION['filter'];
		else $data['filter'] = '';

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

		$data['paging']  = $this->analisis_laporan_penduduk_model->pagingy($p,$o);
		//$data['main']    = $this->keluarga_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		//$data['keyword'] = $this->analisis_laporan_penduduk_model->autocomplete();
		$data['analisis'] = $this->analisis_laporan_penduduk_model->list_data_a($o, $data['paging']->offset, $data['paging']->per_page);
		$data['pertanyaan'] = $this->analisis_laporan_penduduk_model->list_pertanyaan_penduduk();

		$nav['act']= 5;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);

		$this->load->view('analisis/nav',$nav);
		$this->load->view('analisis/laporan/lap_detail_a_penduduk',$data);
		$this->load->view('footer');
	}
	function lap_detail_b($p=1,$o=0){

		$data['p']        = $p;
		$data['o']        = $o;

		if(isset($_SESSION['cari']))
			$data['cari'] = $_SESSION['cari'];
		else $data['cari'] = '';

		if(isset($_SESSION['filter']))
			$data['filter'] = $_SESSION['filter'];
		else $data['filter'] = '';

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

		$data['paging']  = $this->analisis_laporan_penduduk_model->pagingy($p,$o);
		//$data['main']    = $this->keluarga_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		//$data['keyword'] = $this->analisis_laporan_penduduk_model->autocomplete();
		$data['analisis'] = $this->analisis_laporan_penduduk_model->list_data_b($o, $data['paging']->offset, $data['paging']->per_page);
		$data['pertanyaan'] = $this->analisis_laporan_penduduk_model->list_pertanyaan_penduduk();

		$nav['act']= 5;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);

		$this->load->view('analisis/nav',$nav);
		$this->load->view('analisis/laporan/lap_detail_b_penduduk',$data);
		$this->load->view('footer');
	}
    function cetak($lap=0){

		$data['config']  = $this->analisis_laporan_penduduk_model->get_config();
		$data['main']    = $this->analisis_laporan_penduduk_model->list_data($lap);
		$this->load->view('analisis/analisis/laporan/penduduk_print',$data);
	}
	function jenis(){
		$jenis = $this->input->post('jenis');
		if($jenis!=0)
			$_SESSION['jenis']=$jenis;
		else unset($_SESSION['jenis']);
		redirect('analisis/laporan_penduduk');
	}
	function tahun(){
		$tahun = $this->input->post('tahun');
		if($tahun!=0)
			$_SESSION['tahun']=$tahun;
		else unset($_SESSION['tahun']);
		redirect('analisis/laporan_penduduk');
	}
	function bulan(){
		$bulan = $this->input->post('bulan');
		if($bulan!=0)
			$_SESSION['bulan']=$bulan;
		else unset($_SESSION['bulan']);
		redirect('analisis/laporan_penduduk');
	}
	function search_a(){
		$cari = $this->input->post('cari');
		if($cari!='')
			$_SESSION['cari']=$cari;
		else unset($_SESSION['cari']);
		redirect('analisis/laporan_penduduk/lap_detail_a');
	}

	function jenis_a(){
		$jenis = $this->input->post('jenis');
		if($jenis!=0)
			$_SESSION['jenis']=$jenis;
		else unset($_SESSION['jenis']);
		redirect('analisis/laporan_penduduk/lap_detail_a');
	}

	function bulan_a(){
		$bulan = $this->input->post('bulan');
		if($bulan!=0)
			$_SESSION['bulan']=$bulan;
		else unset($_SESSION['bulan']);
		redirect('analisis/laporan_penduduk/lap_detail_a');
	}

	function tahun_a(){
		$tahun = $this->input->post('tahun');
		if($tahun!=0)
			$_SESSION['tahun']=$tahun;
		else unset($_SESSION['tahun']);
		redirect('analisis/laporan_penduduk/lap_detail_a');
	}
		function search_b(){
		$cari = $this->input->post('cari');
		if($cari!='')
			$_SESSION['cari']=$cari;
		else unset($_SESSION['cari']);
		redirect('analisis/laporan_penduduk/lap_detail_b');
	}

	function jenis_b(){
		$jenis = $this->input->post('jenis');
		if($jenis!=0)
			$_SESSION['jenis']=$jenis;
		else unset($_SESSION['jenis']);
		redirect('analisis/laporan_penduduk/lap_detail_b');
	}

	function bulan_b(){
		$bulan = $this->input->post('bulan');
		if($bulan!=0)
			$_SESSION['bulan']=$bulan;
		else unset($_SESSION['bulan']);
		redirect('analisis/laporan_penduduk/lap_detail_b');
	}

	function tahun_b(){
		$tahun = $this->input->post('tahun');
		if($tahun!=0)
			$_SESSION['tahun']=$tahun;
		else unset($_SESSION['tahun']);
		redirect('analisis/laporan_penduduk/lap_detail_b');
	}
}
