<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Dpt extends CI_Controller{

	function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('user_model');
		$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
		if($grup!=1 AND $grup!=2) redirect('siteman');

		$this->load->model('penduduk_model');
		$this->load->model('dpt_model');
		$this->load->model('header_model');
		$this->modul_ini = 2;
	}

	function clear(){
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		unset($_SESSION['sex']);
		unset($_SESSION['warganegara']);
		unset($_SESSION['cacat']);
		unset($_SESSION['menahun']);
		unset($_SESSION['cacatx']);
		unset($_SESSION['menahunx']);
		unset($_SESSION['golongan_darah']);
		unset($_SESSION['dusun']);
		unset($_SESSION['rw']);
		unset($_SESSION['rt']);
		unset($_SESSION['agama']);
		unset($_SESSION['umur_min']);
		unset($_SESSION['umur_max']);
		unset($_SESSION['pekerjaan_id']);
		unset($_SESSION['status']);
		unset($_SESSION['pendidikan_id']);
		unset($_SESSION['pendidikan_sedang_id']);
		unset($_SESSION['pendidikan_kk_id']);
		unset($_SESSION['umurx']);
		unset($_SESSION['status_penduduk']);
		unset($_SESSION['judul_statistik']);
		unset($_SESSION['hamil']);
		unset($_SESSION['cara_kb_id']);
		unset($_SESSION['akta_kelahiran']);
		$_SESSION['per_page'] = 50;
		redirect('dpt');
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

		if(isset($_SESSION['sex']))
			$data['sex'] = $_SESSION['sex'];
		else $data['sex'] = '';

		if(isset($_SESSION['dusun'])){
			$data['dusun'] = $_SESSION['dusun'];
			$data['list_rw'] = $this->penduduk_model->list_rw($data['dusun']);

		if(isset($_SESSION['rw'])){
			$data['rw'] = $_SESSION['rw'];
			$data['list_rt'] = $this->penduduk_model->list_rt($data['dusun'],$data['rw']);

		if(isset($_SESSION['rt']))
			$data['rt'] = $_SESSION['rt'];
			else $data['rt'] = '';

			}else $data['rw'] = '';

		}else{
			$data['dusun'] = '';
			$data['rw'] = '';
			$data['rt'] = '';
		}

		if(isset($_SESSION['agama']))
			$data['agama'] = $_SESSION['agama'];
		else $data['agama'] = '';

    if(isset($_SESSION['cacat']))
			$data['cacat'] = $_SESSION['cacat'];
		else $data['cacat'] = '';

    if(isset($_SESSION['cara_kb_id']))
			$data['cara_kb_id'] = $_SESSION['cara_kb_id'];
		else $data['cara_kb_id'] = '';

    if(isset($_SESSION['akta_kelahiran']))
			$data['akta_kelahiran'] = $_SESSION['akta_kelahiran'];
		else $data['akta_kelahiran'] = '';

		if(isset($_SESSION['pekerjaan_id']))
			$data['pekerjaan_id'] = $_SESSION['pekerjaan_id'];
		else $data['pekerjaan_id'] = '';

		if(isset($_SESSION['status']))
			$data['status'] = $_SESSION['status'];
		else $data['status'] = '';

		if(isset($_SESSION['pendidikan_sedang_id']))
			$data['pendidikan_sedang_id'] = $_SESSION['pendidikan_sedang_id'];
		else $data['pendidikan_sedang_id'] = '';

		if(isset($_SESSION['pendidikan_kk_id']))
			$data['pendidikan_kk_id'] = $_SESSION['pendidikan_kk_id'];
		else $data['pendidikan_kk_id'] = '';

		if(isset($_SESSION['status_penduduk']))
			$data['status_penduduk'] = $_SESSION['status_penduduk'];
		else $data['status_penduduk'] = '';

		if(isset($_POST['per_page']))
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];

		$data['grup']	= $this->user_model->sesi_grup($_SESSION['sesi']);
		$data['paging']  = $this->dpt_model->paging($p,$o);
		$data['main']    = $this->dpt_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->dpt_model	->autocomplete();
		$data['list_agama'] = $this->penduduk_model->list_agama();
		$data['list_dusun'] = $this->penduduk_model->list_dusun();

		$header = $this->header_model->get_data();

		$nav['act']= 5;
		$this->load->view('header', $header);

		$this->load->view('sid/nav',$nav);
		$this->load->view('dpt/dpt',$data);
		$this->load->view('footer');
	}

	function search(){
		$cari = $this->input->post('cari');
		if($cari!='')
			$_SESSION['cari']=$cari;
		else unset($_SESSION['cari']);
		redirect('dpt');
	}

	function sex($p=1,$o=0){
		$sex = $this->input->post('sex');
		if($sex!="")
			$_SESSION['sex']=$sex;
		else unset($_SESSION['sex']);
		redirect("dpt/index/$p/$o");
	}

	function agama(){
		$agama = $this->input->post('agama');
		if($agama!="")
			$_SESSION['agama']=$agama;
		else unset($_SESSION['agama']);
		redirect('dpt');
	}

	function warganegara(){
		$warganegara = $this->input->post('warganegara');
		if($warganegara!="")
			$_SESSION['warganegara']=$warganegara;
		else unset($_SESSION['warganegara']);
		redirect('dpt');
	}

	function dusun($p=1,$o=0){
		unset($_SESSION['rw']);
		unset($_SESSION['rt']);
		$dusun = $this->input->post('dusun');
		if($dusun!="")
			$_SESSION['dusun']=$dusun;
		else unset($_SESSION['dusun']);
		redirect("dpt/index/$p/$o");
	}

	function rw($p=1,$o=0){
		unset($_SESSION['rt']);
		$rw = $this->input->post('rw');
		if($rw!="")
			$_SESSION['rw']=$rw;
		else unset($_SESSION['rw']);
		redirect("dpt/index/$p/$o");
	}

	function rt($p=1,$o=0){
		$rt = $this->input->post('rt');
		if($rt!="")
			$_SESSION['rt']=$rt;
		else unset($_SESSION['rt']);
		redirect("dpt/index/$p/$o");
	}

	function ajax_adv_search(){
		if(isset($_SESSION['cari']))
			$data['cari'] = $_SESSION['cari'];
		else $data['cari'] = '';

		if(isset($_SESSION['judul_statistik']))
			$data['judul_statistik'] = $_SESSION['judul_statistik'];
		else $data['judul_statistik'] = '';

		if(isset($_SESSION['filter']))
			$data['filter'] = $_SESSION['filter'];
		else $data['filter'] = '';

		if(isset($_SESSION['sex']))
			$data['sex'] = $_SESSION['sex'];
		else $data['sex'] = '';

		if(isset($_SESSION['umur_min']))
			$data['umur_min'] = $_SESSION['umur_min'];
		else $data['umur_min'] = '';

		if(isset($_SESSION['umur_max']))
			$data['umur_max'] = $_SESSION['umur_max'];
		else $data['umur_max'] = '';

		if(isset($_SESSION['agama']))
			$data['agama'] = $_SESSION['agama'];
		else $data['agama'] = '';

		if(isset($_SESSION['tahun']))
			$data['tahun'] = $_SESSION['tahun'];
		else $data['tahun'] = date("Y");

    if(isset($_SESSION['cacat']))
			$data['cacat'] = $_SESSION['cacat'];
		else $data['cacat'] = '';

		if(isset($_SESSION['pekerjaan_id']))
			$data['pekerjaan_id'] = $_SESSION['pekerjaan_id'];
		else $data['pekerjaan_id'] = '';

		if(isset($_SESSION['status']))
			$data['status'] = $_SESSION['status'];
		else $data['status'] = '';

		if(isset($_SESSION['pendidikan_sedang_id']))
			$data['pendidikan_sedang_id'] = $_SESSION['pendidikan_sedang_id'];
		else $data['pendidikan_sedang_id'] = '';

		if(isset($_SESSION['pendidikan_kk_id']))
			$data['pendidikan_kk_id'] = $_SESSION['pendidikan_kk_id'];
		else $data['pendidikan_kk_id'] = '';

		if(isset($_SESSION['status_penduduk']))
			$data['status_penduduk'] = $_SESSION['status_penduduk'];
		else $data['status_penduduk'] = '';

		$data['list_agama'] = $this->penduduk_model->list_agama();
		$data['pendidikan'] = $this->penduduk_model->list_pendidikan();
		$data['pendidikan_kk'] = $this->penduduk_model->list_pendidikan_kk();
		$data['pekerjaan'] = $this->penduduk_model->list_pekerjaan();
		$data['status_kawin'] = $this->penduduk_model->list_status_kawin();
		$data['form_action'] = site_url("dpt/adv_search_proses");

		$this->load->view("sid/kependudukan/ajax_adv_search_form", $data);
	}

	function adv_search_proses(){

		$adv_search = $_POST;
		$i=0;
		while($i++ < count($adv_search)){
			$col[$i] = key($adv_search);
				next($adv_search);
		}
		$i=0;
		while($i++ < count($col)){
			if($adv_search[$col[$i]]==""){
				UNSET($adv_search[$col[$i]]);
				UNSET($_SESSION[$col[$i]]);
			}else{
				$_SESSION[$col[$i]]=$adv_search[$col[$i]];
			}
		}

		redirect("dpt/index/1/$o");
	}

	function ajax_penduduk_cari_rw($dusun=''){
		$rw = $this->penduduk_model->list_rw($dusun);

		echo"<td>RW</td>
		<td><select name='rw' onchange=RWSel('".$dusun."',this.value)>
		<option value=''>Pilih RW&nbsp;</option>";
		foreach($rw as $data){
			echo "<option>".$data['rw']."</option>";
		}echo"</select>
		</td>";
	}

	function ajax_penduduk_cari_rt($dusun='',$rw=''){
		$rt = $this->penduduk_model->list_rt($dusun,$rw);

		echo "<td>RT</td>
		<td><select name='rt'>
		<option value=''>Pilih RT&nbsp;</option>";
		foreach($rt as $data){
			echo "<option value=".$data['rt'].">".$data['rt']."</option>";
		}echo"</select>
		</td>";
	}

	function cetak($o=0){
		$data['main'] = $this->dpt_model->list_data($o,0, 10000);
		$this->load->view('dpt/dpt_print',$data);
	}

	function excel($o=0){
		$data['main'] = $this->dpt_model->list_data($o,0, 10000);
		$this->load->view('dpt/dpt_excel',$data);
	}

}
