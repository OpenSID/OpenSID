<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class web extends CI_Controller{

	function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('user_model');
		$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
		if($grup!=1 AND $grup!=2 AND $grup!=3) redirect('siteman');
		$this->load->model('header_model');
		$this->load->model('web_artikel_model');

	}
	
	function clear(){
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		redirect('web');
	}
	
	function index($cat=1,$p=1,$o=0){
	
		$data['p']        = $p;
		$data['o']        = $o;
		$data['cat']	  = $cat;
		
		if(isset($_SESSION['cari']))
			$data['cari'] = $_SESSION['cari'];
		else $data['cari'] = '';
		
		if(isset($_SESSION['filter']))
			$data['filter'] = $_SESSION['filter'];
		else $data['filter'] = '';
	
		if(isset($_POST['per_page'])) 
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];
		
		$data['paging']  = $this->web_artikel_model->paging($cat,$p,$o);
		$data['main']    = $this->web_artikel_model->list_data($cat,$o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->web_artikel_model->autocomplete();
		$data['list_kategori'] = $this->web_artikel_model->list_kategori();
		$data['kategori'] = $this->web_artikel_model->get_kategori($cat);
		$data['cat'] = $cat;

		$header = $this->header_model->get_data();
		$nav['act']=0;
		
		$this->load->view('header', $header);
		$this->load->view('web/nav',$nav);
		$this->load->view('web/artikel/table',$data);
		$this->load->view('footer');
	}
	
	function form($cat=1,$p=1,$o=0,$id=''){
	
		$data['p'] = $p;
		$data['o'] = $o;
		$data['cat'] = $cat;
		
		if($id){
			$data['artikel']        = $this->web_artikel_model->get_artikel($id);
			$data['form_action'] = site_url("web/update/$cat/$id/$p/$o");
		}
		else{
			$data['artikel']        = null;
			$data['form_action'] = site_url("web/insert/$cat");
		}
			
		$data['kategori'] = $this->web_artikel_model->get_kategori($cat);
		
		$header = $this->header_model->get_data();
		
		$nav['act']=0;
		$this->load->view('header', $header);
		//$this->load->view('web/spacer');
		$this->load->view('web/nav',$nav);
		$this->load->view('web/artikel/form',$data);
		$this->load->view('footer');
	}

	function search($cat=1){
		$cari = $this->input->post('cari');
		if($cari!='')
			$_SESSION['cari']=$cari;
		else unset($_SESSION['cari']);
		redirect("web/index/$cat");
	}
	
	function filter($cat=1){
		$filter = $this->input->post('filter');
		if($filter!=0)
			$_SESSION['filter']=$filter;
		else unset($_SESSION['filter']);
		redirect("web/index/$cat");
	}
	
	function insert($cat=1){
		$this->web_artikel_model->insert($cat);
		redirect("web/index/$cat");
	}
	
	function update($cat=0,$id='',$p=1,$o=0){
		$this->web_artikel_model->update($id);
		redirect("web/index/$cat/$p/$o");
	}
	
	function delete($cat=1,$p=1,$o=0,$id=''){
		$this->web_artikel_model->delete($id);
		redirect("web/index/$cat/$p/$o");
	}
		
	function hapus($cat=1,$p=1,$o=0){
		$this->web_artikel_model->hapus($cat);
		redirect("web/index/1/$p/$o");
	}
	
	function delete_all($p=1,$o=0){
		$this->web_artikel_model->delete_all();
		redirect("web/index/$p/$o");
	}
	
	function artikel_lock($cat=1,$id=0){
		$this->web_artikel_model->artikel_lock($id,1);
		redirect("web/index/$cat");
	}

	function artikel_unlock($cat=1,$id=0){
		$this->web_artikel_model->artikel_lock($id,2);
		redirect("web/index/$cat");
	}
	
	function ajax_add_kategori($cat=1,$p=1,$o=0){
	
		$data['form_action'] = site_url("web/insert_kategori/$cat/$p/$o");
		$this->load->view('web/artikel/ajax_add_kategori_form',$data);
	}

	function insert_kategori($cat=1,$p=1,$o=0){
		$this->web_artikel_model->insert_kategori();
		redirect("web/index/$cat/$p/$o");
	}
	
	function headline($cat=1,$p=1,$o=0,$id=0){
		$this->web_artikel_model->headline($id);
		redirect("web/index/$cat/$p/$o");
	}

	function slide($cat=1,$p=1,$o=0,$id=0){
		$this->web_artikel_model->slide($id);
		redirect("web/index/$cat/$p/$o");
	}
}
