<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Gallery extends CI_Controller{

	function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('user_model');
		$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
		if($grup!=1 AND $grup!=2 AND $grup!=3 AND $grup!=4) {
			if(empty($grup))
				$_SESSION['request_uri'] = $_SERVER['REQUEST_URI'];
			else
				unset($_SESSION['request_uri']);
			redirect('siteman');
		}
		$this->load->model('header_model');
		$this->load->model('web_gallery_model');
		$this->modul_ini = 13;
	}

	function clear(){
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		redirect('gallery');
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

		$data['paging']  = $this->web_gallery_model->paging($p,$o);
		$data['main']    = $this->web_gallery_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->web_gallery_model->autocomplete();

		$header = $this->header_model->get_data();
		$nav['act']=3;

		$this->load->view('header', $header);
		$this->load->view('web/nav',$nav);
		$this->load->view('gallery/table',$data);
		$this->load->view('footer');
	}

	function form($p=1,$o=0,$id=''){

		$data['p'] = $p;
		$data['o'] = $o;

		if($id){
			$data['gallery']        = $this->web_gallery_model->get_gallery($id);
			$data['form_action'] = site_url("gallery/update/$id/$p/$o");
		}
		else{
			$data['gallery']        = null;
			$data['form_action'] = site_url("gallery/insert");
		}

		$header = $this->header_model->get_data();

		$nav['act']=3;
		$this->load->view('header', $header);
		$this->load->view('web/nav',$nav);
		$this->load->view('gallery/form',$data);
		$this->load->view('footer');
	}


	function search($gallery=''){
		$cari = $this->input->post('cari');
		if($cari!='')
			$_SESSION['cari']=$cari;
		else unset($_SESSION['cari']);
		if ($gallery != '') {
			redirect("gallery/sub_gallery/$gallery");
		} else {
			redirect('gallery');
		}
	}

	function filter($gallery=''){
		$filter = $this->input->post('filter');
		if($filter!=0)
			$_SESSION['filter']=$filter;
		else unset($_SESSION['filter']);
		if ($gallery != '') {
			redirect("gallery/sub_gallery/$gallery");
		} else {
			redirect('gallery');
		}
	}

	function insert(){
		$this->web_gallery_model->insert();
		redirect('gallery');
	}

	function update($id='',$p=1,$o=0){
		$this->web_gallery_model->update($id);
		redirect("gallery/index/$p/$o");
	}

	function delete($p=1,$o=0,$id=''){
		$_SESSION['success']=1;
		$this->web_gallery_model->delete_gallery($id);
		redirect("gallery/index/$p/$o");
	}

	function delete_all($p=1,$o=0){
		$_SESSION['success']=1;
		$this->web_gallery_model->delete_all_gallery();
		redirect("gallery/index/$p/$o");
	}

	function gallery_lock($id='',$gallery=''){
		$this->web_gallery_model->gallery_lock($id,1);
		if ($gallery != '') {
			redirect("gallery/sub_gallery/$gallery/$p");
		} else {
			redirect("gallery/index/$p/$o");
		}
	}

	function gallery_unlock($id='',$gallery=''){
		$this->web_gallery_model->gallery_lock($id,2);
		if ($gallery != '') {
			redirect("gallery/sub_gallery/$gallery/$p");
		} else {
			redirect("gallery/index/$p/$o");
		}
	}

	function slider_on($id='',$gallery=''){
		$this->web_gallery_model->gallery_slider($id,1);
		if ($gallery != '') {
			redirect("gallery/sub_gallery/$gallery/$p");
		} else {
			redirect("gallery/index/$p/$o");
		}
	}

	function slider_off($id='',$gallery=''){
		$this->web_gallery_model->gallery_slider($id,0);
		if ($gallery != '') {
			redirect("gallery/sub_gallery/$gallery/$p");
		} else {
			redirect("gallery/index/$p/$o");
		}
	}

	function sub_gallery($gal=0,$p=1){
		$data['p']        = 1;
		$data['o']        = 0;

		if(isset($_SESSION['cari']))
			$data['cari'] = $_SESSION['cari'];
		else $data['cari'] = '';

		if(isset($_SESSION['filter']))
			$data['filter'] = $_SESSION['filter'];
		else $data['filter'] = '';

		if(isset($_POST['per_page']))
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];

		$data['paging']  = $this->web_gallery_model->paging2($gal,$p);
		//$data['main']    = $this->web_gallery_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['sub_gallery']    = $this->web_gallery_model->list_sub_gallery($gal,$data['paging']->offset, $data['paging']->per_page);
		$data['gallery'] = $gal;
		$data['sub']  = $this->web_gallery_model->get_gallery($gal);
		$header = $this->header_model->get_data();
		$nav['act']=3;

		$this->load->view('header', $header);
		$this->load->view('web/nav',$nav);
		$this->load->view('gallery/sub_gallery_table',$data);
		$this->load->view('footer');
	}

	function form_sub_gallery($gallery=0,$id=0){

		if($id){
			$data['gallery']        = $this->web_gallery_model->get_gallery($id);
			$data['form_action'] = site_url("gallery/update_sub_gallery/$gallery/$id");
		}
		else{
			$data['gallery']        = null;
			$data['form_action'] = site_url("gallery/insert_sub_gallery/$gallery");
		}
		$data['album']=$gallery;


		$header = $this->header_model->get_data();

		$nav['act']=3;
		$this->load->view('header', $header);
		$this->load->view('web/nav',$nav);
		$this->load->view('gallery/form_sub_gallery',$data);
		$this->load->view('footer');

	}

	function insert_sub_gallery($gallery=''){
		$this->web_gallery_model->insert_sub_gallery($gallery);
		redirect("gallery/sub_gallery/$gallery");
	}

	function update_sub_gallery($gallery='',$id=''){
		$this->web_gallery_model->update_sub_gallery($id);
		redirect("gallery/sub_gallery/$gallery");
	}

	function delete_sub_gallery($gallery='',$id=''){
		$_SESSION['success']=1;
		$this->web_gallery_model->delete($id);
		redirect("gallery/sub_gallery/$gallery");
	}

	function delete_all_sub_gallery($gallery=''){
		$_SESSION['success']=1;
		$this->web_gallery_model->delete_all();
		redirect("gallery/sub_gallery/$gallery");
	}

	function gallery_lock_sub_gallery($gallery='',$id=''){
		$this->web_gallery_model->gallery_lock($id,1);
		redirect("gallery/sub_gallery/$gallery");
	}

	function gallery_unlock_sub_gallery($gallery='',$id=''){
		$this->web_gallery_model->gallery_lock($id,2);
		redirect("gallery/sub_gallery/$gallery");
	}
}
