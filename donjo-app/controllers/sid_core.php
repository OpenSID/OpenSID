<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Sid_Core extends CI_Controller{

function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('user_model');
		$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
		if($grup!=1 AND $grup!=2) redirect('siteman');
		$this->load->model('header_model');
		$this->load->model('wilayah_model');
		$this->load->model('config_model');
		$this->modul_ini = 2;
	}

	function clear(){
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		redirect('sid_core');
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
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];
		$data['grup']	= $this->user_model->sesi_grup($_SESSION['sesi']);
		$data['paging']  = $this->wilayah_model->paging($p,$o);
		$data['main']    = $this->wilayah_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->wilayah_model->autocomplete();
		$data['total'] = $this->wilayah_model->total();

		$nav['act']= 0;
		$header = $this->header_model->get_data();

		$this->load->view('header',$header);
		$this->load->view('sid/nav',$nav);
		$this->load->view('sid/wilayah/wilayah',$data);
		$this->load->view('footer');
	}

	function cetak(){
		$data['desa'] = $this->header_model->get_data();
		$data['main']    = $this->wilayah_model->list_data(0,0,1000);
		$data['total'] = $this->wilayah_model->total();

		$this->load->view('sid/wilayah/wilayah_print',$data);
	}

	function excel(){

		$data['desa'] = $this->header_model->get_data();
		$data['main']    = $this->wilayah_model->list_data(0,0,1000);
		$data['total'] = $this->wilayah_model->total();

		$this->load->view('sid/wilayah/wilayah_excel',$data);
	}

	function form($id=''){

		$data['penduduk'] = $this->wilayah_model->list_penduduk();

		if($id){
			$temp = $this->wilayah_model->cluster_by_id($id);
			$data['dusun']    = $temp['dusun'];
			$data['individu'] = $this->wilayah_model->get_penduduk($temp['id_kepala']);
			if(empty($data['individu']))
				$data['individu'] = NULL;
			else{
				$ex = $data['individu'];
				$data['penduduk'] = $this->wilayah_model->list_penduduk_ex($ex['id']);
			}
			$data['form_action'] = site_url("sid_core/update/$id");
		}
		else{
			$data['dusun']          = null;
			$data['form_action'] = site_url("sid_core/insert");
		}

		$nav['act']= 0;
		$header = $this->header_model->get_data();

		$this->load->view('header',$header);
		$this->load->view('sid/nav',$nav);
		$this->load->view('sid/wilayah/wilayah_form',$data);
		$this->load->view('footer');
	}

	function search(){
		$cari = $this->input->post('cari');
		if($cari!='')
			$_SESSION['cari']=$cari;
		else unset($_SESSION['cari']);
		redirect('sid_core');
	}

	function insert($dusun=''){

		$this->wilayah_model->insert();
		redirect('sid_core');
	}

	function update($id=''){
		$this->wilayah_model->update($id);
		redirect('sid_core');
	}

	function delete($id=''){
		$this->wilayah_model->delete($id);
		redirect('sid_core');
	}

	function delete_all(){
		$this->wilayah_model->delete_all();
		redirect('sid_core');
	}

	function sub_rw($id_dusun=''){

		$dusun = $this->wilayah_model->cluster_by_id($id_dusun);
		$nama_dusun  = $dusun['dusun'];
		$data['dusun']  = $dusun['dusun'];
		$data['id_dusun']  = $id_dusun;
		$data['main']     = $this->wilayah_model->list_data_rw($id_dusun );
		$data['total']     = $this->wilayah_model->total_rw($nama_dusun );

		$nav['act']= 0;
		$header = $this->header_model->get_data();

		$this->load->view('header',$header);
		$this->load->view('sid/nav',$nav);
		$this->load->view('sid/wilayah/wilayah_rw',$data);
		$this->load->view('footer');
	}


	function cetak_rw($id_dusun=''){

		$dusun = $this->wilayah_model->cluster_by_id($id_dusun);
		$nama_dusun  = $dusun['dusun'];
		$data['dusun']  = $dusun['dusun'];
		$data['id_dusun']  = $id_dusun;
		$data['main']     = $this->wilayah_model->list_data_rw($id_dusun );
		$data['total']     = $this->wilayah_model->total_rw($nama_dusun );

		$this->load->view('sid/wilayah/wilayah_rw_print',$data);
	}

	function excel_rw($id_dusun=''){

		$dusun = $this->wilayah_model->cluster_by_id($id_dusun);
		$nama_dusun  = $dusun['dusun'];
		$data['dusun']  = $dusun['dusun'];
		$data['id_dusun']  = $id_dusun;
		$data['main']     = $this->wilayah_model->list_data_rw($id_dusun );
		$data['total']     = $this->wilayah_model->total_rw($nama_dusun );

		$this->load->view('sid/wilayah/wilayah_rw_excel',$data);
	}

	function form_rw($id_dusun='',$rw=''){

		$temp = $this->wilayah_model->cluster_by_id($id_dusun);
		$dusun    = $temp['dusun'];
		$data['dusun']    = $temp['dusun'];
		$data['id_dusun']    = $id_dusun;

		$data['penduduk'] = $this->wilayah_model->list_penduduk();

		if($rw){
			$data['rw']          = $rw;
			$temp = $this->wilayah_model->get_rw($dusun,$rw);
			$data['individu'] = $this->wilayah_model->get_penduduk($temp['id_kepala']);
			if(empty($data['individu']))
				$data['individu'] = NULL;
			else{
				$ex = $data['individu'];
				$data['penduduk'] = $this->wilayah_model->list_penduduk_ex($ex['id']);
			}
			$data['form_action'] = site_url("sid_core/update_rw/$id_dusun/$rw");
		}
		else{
			$data['rw']          = null;
			$data['form_action'] = site_url("sid_core/insert_rw/$id_dusun");
		}

		$nav['act']= 0;
		$header = $this->header_model->get_data();

		$this->load->view('header',$header);
		$this->load->view('sid/nav',$nav);
		$this->load->view('sid/wilayah/wilayah_form_rw',$data);
		$this->load->view('footer');
	}

	function insert_rw($dusun=''){
		$this->wilayah_model->insert_rw($dusun);
		redirect("sid_core/sub_rw/$dusun");
	}

	function update_rw($dusun='',$rw=''){
		$this->wilayah_model->update_rw($dusun,$rw);
		redirect("sid_core/sub_rw/$dusun");
	}

	function delete_rw($id_dusun='',$id=''){
		$this->wilayah_model->delete_rw($id);
		redirect("sid_core/sub_rw/$id_dusun");
	}

	function delete_all_rw($dusun=''){
		$this->wilayah_model->delete_all_rw();
		redirect("sid_core/sub_rw/$dusun");
	}

	function sub_rt($id_dusun='',$rw=''){

		$temp = $this->wilayah_model->cluster_by_id($id_dusun);
		$dusun    				= $temp['dusun'];
		$data['dusun']    		= $temp['dusun'];
		$data['id_dusun']    	= $id_dusun;

		$data['rw']       = $rw;
		$data['main']     = $this->wilayah_model->list_data_rt($dusun,$rw);
		$data['total']     = $this->wilayah_model->total_rt($dusun,$rw);

		$nav['act']= 0;
		$header = $this->header_model->get_data();

		$this->load->view('header',$header);
		$this->load->view('sid/nav',$nav);
		$this->load->view('sid/wilayah/wilayah_rt',$data);
		$this->load->view('footer');
	}

	function cetak_rt($id_dusun='',$rw=''){

		$temp = $this->wilayah_model->cluster_by_id($id_dusun);
		$dusun    = $temp['dusun'];
		$data['dusun']    = $temp['dusun'];
		$data['id_dusun']    = $id_dusun;

		$data['rw']       = $rw;
		$data['main']     = $this->wilayah_model->list_data_rt($dusun,$rw);
		$data['total']     = $this->wilayah_model->total_rt($dusun,$rw);

		$this->load->view('sid/wilayah/wilayah_rt_print',$data);
	}

	function excel_rt($id_dusun='',$rw=''){

		$temp = $this->wilayah_model->cluster_by_id($id_dusun);
		$dusun    = $temp['dusun'];
		$data['dusun']    = $temp['dusun'];
		$data['id_dusun']    = $id_dusun;

		$data['rw']       = $rw;
		$data['main']     = $this->wilayah_model->list_data_rt($dusun,$rw);
		$data['total']     = $this->wilayah_model->total_rt($dusun,$rw);

		$this->load->view('sid/wilayah/wilayah_rt_excel',$data);
	}

	function list_dusun_rt($dusun='',$rw=''){

		$data['dusun']    = $dusun;
		$data['rw']       = $rw;
		$data['main']     = $this->wilayah_model->list_data_rt($dusun,$rw);

		$nav['act']= 0;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		$this->load->view('sid/nav',$nav);
		$this->load->view('sid/wilayah/list_dusun_rt',$data);
		$this->load->view('footer');
	}

	function form_rt($id_dusun='',$rw='',$rt=''){

		$temp = $this->wilayah_model->cluster_by_id($id_dusun);
		$dusun    = $temp['dusun'];
		$data['dusun']    = $temp['dusun'];
		$data['id_dusun']    = $id_dusun;

		$data['rw']    = $rw;
		$data['penduduk'] = $this->wilayah_model->list_penduduk();

		if($rt){

			$temp2 = $this->wilayah_model->cluster_by_id($rt);
			$id_cluster=$temp2['id'];
			$data['rt'] =$temp2['rt'];
			$data['individu'] = $this->wilayah_model->get_penduduk($temp2['id_kepala']);
			if(empty($data['individu']))
				$data['individu'] = NULL;
			else{
				$ex = $data['individu'];
				$data['penduduk'] = $this->wilayah_model->list_penduduk_ex($ex['id']);
			}
			$data['form_action'] = site_url("sid_core/update_rt/$id_dusun/$rw/$id_cluster");
		}
		else{
			$data['rt']          = null;
			$data['form_action'] = site_url("sid_core/insert_rt/$id_dusun/$rw");
		}

		$nav['act']= 0;
		$header = $this->header_model->get_data();

		$this->load->view('header',$header);
		$this->load->view('sid/nav',$nav);
		$this->load->view('sid/wilayah/wilayah_form_rt',$data);
		$this->load->view('footer');
	}

	function insert_rt($dusun='',$rw=''){
		$this->wilayah_model->insert_rt($dusun,$rw);

		redirect("sid_core/sub_rt/$dusun/$rw");
	}

	function update_rt($dusun='',$rw='',$id_cluster=0){
		$this->wilayah_model->update_rt($id_cluster);
		redirect("sid_core/sub_rt/$dusun/$rw");
	}

	function delete_rt($id_cluster=''){
		$temp = $this->wilayah_model->cluster_by_id($id_cluster);
		$id_dusun=$temp['id'];
		$dusun=$temp['dusun'];
		$rw=$temp['rw'];
		$this->wilayah_model->delete_rt($id_cluster);
		echo "<script>self.history.back();self.history.back();</script>";
	}

	function delete_all_rt(){
		$temp = $this->wilayah_model->cluster_by_id($id_cluster);
		$id_dusun=$temp['id'];
		$dusun=$temp['dusun'];
		$rw=$temp['rw'];
		$this->wilayah_model->delete_all_rt();
		redirect("sid_core");
	}

	function ajax_wil_maps($id=0){
		$data['dusun'] = $this->wilayah_model->get_dusun_maps($id);
		$data['desa'] = $this->config_model->get_data();
		$data['form_action'] = site_url("sid_core/update_dusun_map/$id");

		$this->load->view("sid/wilayah/ajax_wil_dusun", $data);
	}

	function update_dusun_map($id=0){
		$this->wilayah_model->update_dusun_map($id);
		redirect("sid_core");
	}

	function ajax_rw_maps($dus=0,$id=0){
		$data['dusun'] = $this->wilayah_model->get_rw($dus,$id);
		$data['desa'] = $this->config_model->get_data();
		$data['form_action'] = site_url("sid_core/update_rw_map/$dus/$id");

		$this->load->view("sid/wilayah/ajax_wil_dusun", $data);
	}

	function update_rw_map($dus=0,$id=0){
		$this->wilayah_model->update_rw_map($dus,$id);
		redirect("sid_core/sub_rw/$dus");
	}

	function ajax_rt_maps($dus=0,$rw=0,$id=0){
		$data['dusun'] = $this->wilayah_model->get_rt($dus,$rw,$id);
		$data['desa'] = $this->config_model->get_data();
		$data['form_action'] = site_url("sid_core/update_rt_map/$dus/$rw/$id");

		$this->load->view("sid/wilayah/ajax_wil_dusun", $data);
	}

	function update_rt_map($dus=0,$rw=0,$id=0){
		$this->wilayah_model->update_rt_map($dus,$rw,$id);
		redirect("sid_core/sub_rt/$dus/$rw");
	}

	function warga($id=''){

	        $temp = $this->wilayah_model->cluster_by_id($id);
		$id_dusun=$temp['id'];
		$dusun=$temp['dusun'];

		$_SESSION['per_page'] = 100;
		$_SESSION['dusun'] = $dusun;
		redirect("penduduk/index/1/0");
	}

	function warga_kk($id=''){
	        $temp = $this->wilayah_model->cluster_by_id($id);
	        $id_dusun=$temp['id'];
		$dusun=$temp['dusun'];
		$_SESSION['per_page'] = 50;
		$_SESSION['dusun'] = $dusun;
		redirect("keluarga/index/1/0");
	}

	function warga_l($id=''){
	        $temp = $this->wilayah_model->cluster_by_id($id);
		$id_dusun=$temp['id'];
		$dusun=$temp['dusun'];

		$_SESSION['per_page'] = 100;
		$_SESSION['dusun'] = $dusun;
		$_SESSION['sex'] = 1;
		redirect("penduduk/index/1/0");
	}

	function warga_p($id=''){
	        $temp = $this->wilayah_model->cluster_by_id($id);
		$id_dusun=$temp['id'];
		$dusun=$temp['dusun'];

		$_SESSION['per_page'] = 100;
		$_SESSION['dusun'] = $dusun;
		$_SESSION['sex'] = 2;
		redirect("penduduk/index/1/0");
	}

	function migrate(){
		$this->wilayah_model->migrate();

		$this->dbforge->drop_table('tweb_dusun_x');
		$this->dbforge->drop_table('tweb_rw_x');
		$this->dbforge->drop_table('tweb_rt_x');
		$this->dbforge->drop_table('tweb_keluarga_x');
		$this->dbforge->drop_table('tweb_keluarga_x_pindah');
		$this->dbforge->drop_table('tweb_penduduk_x');
		$this->dbforge->drop_table('tweb_penduduk_x_pindah');

		redirect("penduduk/clear");
	}

	function pre_migrate(){
		$nav['act']= 3;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		$this->load->view('sid/nav',$nav);
		$this->load->view('sid/wilayah/mig');
		$this->load->view('footer');
	}


}
