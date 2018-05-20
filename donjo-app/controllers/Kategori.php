<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Kategori extends CI_Controller{

	function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('user_model');
		$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
		if($grup!=1 AND $grup!=2 AND $grup!=3) {
			if(empty($grup))
				$_SESSION['request_uri'] = $_SERVER['REQUEST_URI'];
			else
				unset($_SESSION['request_uri']);
			redirect('siteman');
		}
		$this->load->model('header_model');
		$this->load->model('web_kategori_model');
		$this->modul_ini = 13;
	}

	function clear(){
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		$_SESSION['per_page'] = 20;
		redirect('kategori');
	}

	function index($p=1,$o=0){

		$data['p']        = $p;
		$data['o']        = $o;
		$data['tip']        = 2;

		if(isset($_SESSION['cari']))
			$data['cari'] = $_SESSION['cari'];
		else $data['cari'] = '';

		if(isset($_SESSION['filter']))
			$data['filter'] = $_SESSION['filter'];
		else $data['filter'] = '';

		if(isset($_POST['per_page']))
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];

		$data['paging']  = $this->web_kategori_model->paging($p,$o);
		$data['main']    = $this->web_kategori_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->web_kategori_model->autocomplete();

		$header = $this->header_model->get_data();
		$nav['act']=1;


		$this->load->view('header', $header);
		$this->load->view('web/nav',$nav);
		$this->load->view('kategori/table',$data);
		$this->load->view('footer');
	}

	function form($id=''){

		$data['tip']        = 2;
		if($id){
			$data['kategori']        = $this->web_kategori_model->get_kategori($id);
			$data['form_action'] = site_url("kategori/update/$id");
		}
		else{
			$data['kategori']        = null;
			$data['form_action'] = site_url("kategori/insert");
		}

		$header = $this->header_model->get_data();

		$nav['act']=1;
		$this->load->view('header', $header);
		$this->load->view('web/nav',$nav);
		$this->load->view('kategori/form',$data);
		$this->load->view('footer');
	}

	function sub_kategori($kategori=1){

		$data['tip']        = 2;
		$data['subkategori']    = $this->web_kategori_model->list_sub_kategori($kategori);
		$data['kategori'] = $kategori;
		$header = $this->header_model->get_data();
		$nav['act']=1;

		$this->load->view('header', $header);
		$this->load->view('web/nav',$nav);
		$this->load->view('kategori/sub_kategori_table',$data);
		$this->load->view('footer');
	}

	function ajax_add_sub_kategori($kategori='',$id=''){

		$data['kategori'] = $kategori;

		$data['link']        = $this->web_kategori_model->list_link();

		if($id){
			$data['subkategori']        = $this->web_kategori_model->get_kategori($id);
			$data['form_action'] = site_url("kategori/update_sub_kategori/$kategori/$id");
		}
		else{
			$data['subkategori']        = null;
			$data['form_action'] = site_url("kategori/insert_sub_kategori/$kategori");
		}

		$this->load->view('kategori/ajax_add_sub_kategori_form',$data);
	}

	function search(){
		$cari = $this->input->post('cari');
		if($cari!='')
			$_SESSION['cari']=$cari;
		else unset($_SESSION['cari']);
		redirect("kategori/index");
	}

	function filter(){
		$filter = $this->input->post('filter');
		if($filter!=0)
			$_SESSION['filter']=$filter;
		else unset($_SESSION['filter']);
		redirect('kategori');
	}

	function insert(){
		$this->web_kategori_model->insert($tip);
		redirect("kategori/index");
	}

	function update($id=''){
		$this->web_kategori_model->update($id);
		redirect("kategori/index");
	}

	function delete($id=''){
		$this->web_kategori_model->delete($id);
		redirect("kategori/index");
	}

	function delete_all($p=1,$o=0){
		$this->web_kategori_model->delete_all();
		redirect("kategori/index/$p/$o");
	}

	function kategori_lock($id=''){
		$this->web_kategori_model->kategori_lock($id,1);
		redirect("kategori/index/$p/$o");
	}

	function kategori_unlock($id=''){
		$this->web_kategori_model->kategori_lock($id,2);
		redirect("kategori/index/$p/$o");
	}

	function insert_sub_kategori($kategori=''){
		$this->web_kategori_model->insert_sub_kategori($kategori);
		redirect("kategori/sub_kategori/$kategori");
	}

	function update_sub_kategori($kategori='',$id=''){
		$this->web_kategori_model->update_sub_kategori($id);
		redirect("kategori/sub_kategori/$kategori");
	}

	function delete_sub_kategori($kategori='',$id=0){
		$this->web_kategori_model->delete($id);
		redirect("kategori/sub_kategori/$kategori");
	}

	function delete_all_sub_kategori($kategori=''){
		$this->web_kategori_model->delete_all();
		redirect("kategori/sub_kategori/$kategori");
	}

	function kategori_lock_sub_kategori($kategori='',$id=''){
		$this->web_kategori_model->kategori_lock($id,1);
		redirect("kategori/sub_kategori/$kategori");
	}

	function kategori_unlock_sub_kategori($kategori='',$id=''){
		$this->web_kategori_model->kategori_lock($id,2);
		redirect("kategori/sub_kategori/$kategori");
	}

	function urut($id=0, $arah=0, $kategori=''){
		if($_SESSION['grup']!=1) {
			session_error("Anda tidak mempunyai akses pada fitur ini");
			redirect('kategori'); // hanya untuk administrator
		}
		$this->web_kategori_model->urut($id,$arah,$kategori);
		if ($kategori != '')
			redirect("kategori/sub_kategori/$kategori");
		else
			redirect("kategori/index");
	}

}
