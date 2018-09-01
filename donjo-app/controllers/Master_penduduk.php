<? if(!defined('BASEPATH')) exit('No direct script access allowed');

class Master_penduduk extends CI_Controller{

	function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('user_model');
		$this->load->model('master_analisis_penduduk_model');
		$_SESSION['request_uri'] = $_SESSION['REQUEST_URI'];
		$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
		if($grup < 1) {
			if(empty($grup))
				$_SESSION['request_uri'] = $_SERVER['REQUEST_URI'];
			else
				unset($_SESSION['request_uri']);
			redirect('siteman');
		}
		$this->load->model('header_model');

	}

	function clear(){
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		redirect('analisis/master_penduduk');
	}

	function index(){

		if(isset($_SESSION['cari']))
			$data['cari'] = $_SESSION['cari'];
		else $data['cari'] = '';

		if(isset($_SESSION['filter']))
			$data['filter'] = $_SESSION['filter'];
		else $data['filter'] = '';

		$data['main'] = $this->master_analisis_penduduk_model->list_data();
		$data['keyword'] = $this->master_analisis_penduduk_model->autocomplete();
		$nav['act']= 3;
		$header = $this->header_model->get_data();

		$this->load->view('header',$header);

		$this->load->view('analisis/nav',$nav);
		$this->load->view('analisis/master_analisis_penduduk',$data);
		$this->load->view('footer');
	}

	function form($id=''){

		if($id){
			$data['master_analisis_penduduk']          = $this->master_analisis_penduduk_model->get_data($id);
			$data['form_action'] = site_url("analisis/master_penduduk/update/$id");
		}
		else{
			$data['master_analisis_penduduk']          = null;
			$data['form_action'] = site_url("analisis/master_penduduk/insert");
		}

		$header = $this->header_model->get_data();

		$this->load->view('header',$header);

		$nav['act']= 3;
		$this->load->view('analisis/nav',$nav);
		$this->load->view('analisis/master_analisis_penduduk_form',$data);
		$this->load->view('footer');
	}

	function sub_analisis_penduduk($id=''){
		$data['form_action'] = site_url("analisis/master_penduduk/sub_insert/$id");
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);

		$nav['act']= 3;
		$this->load->view('analisis/nav',$nav);
		$this->load->view('analisis/sub_analisis_penduduk_form',$data);
		$this->load->view('footer');
	}

	function sub_analisis_penduduk_list($id=''){
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		$data['master'] = $this->master_analisis_penduduk_model->get_data($id);
		$data['main'] = $this->master_analisis_penduduk_model->list_data_sub($id);


		$nav['act']= 3;
		$this->load->view('analisis/nav',$nav);
		$this->load->view('analisis/sub_analisis_penduduk_list',$data);
		$this->load->view('footer');
	}

	function sub_analisis_penduduk_form($id=''){
	if($id){
			$data['sub_analisis_penduduk']          = $this->master_analisis_penduduk_model->get_data_sub($id);
			$idmaster=$data['sub_analisis_penduduk']['id_master'];
			$data['form_action'] = site_url("analisis/master_penduduk/sub_update/$id");
			$data['form_action_back'] = site_url("analisis/master_penduduk/sub_analisis_penduduk_list/$idmaster");
		}
		else{
			$data['sub_analisis_penduduk']          = null;
			$data['form_action'] = site_url("analisis/master_penduduk/sub_analisis_penduduk_insert");
		}

		$header = $this->header_model->get_data();

		$this->load->view('header',$header);

		$nav['act']= 3;
		$this->load->view('analisis/nav',$nav);
		$this->load->view('analisis/sub_analisis_penduduk_form_edit',$data);
		$this->load->view('footer');
	}


	function search(){
		$cari = $this->input->post('cari');
		if($cari!='')
			$_SESSION['cari']=$cari;
		else unset($_SESSION['cari']);
		redirect('analisis/master_penduduk');
	}

	function filter(){
		$filter = $this->input->post('filter');
		if($filter!=0)
			$_SESSION['filter']=$filter;
		else unset($_SESSION['filter']);
		redirect('analisis/master_penduduk');
	}

	function insert(){
		$this->master_analisis_penduduk_model->insert();
		redirect('analisis/master_penduduk');
	}

	function update($id=''){
		$this->master_analisis_penduduk_model->update($id);
		redirect('analisis/master_penduduk');
	}

	function sub_insert($id=0){
		$this->master_analisis_penduduk_model->insert_sub($id);
		redirect('analisis/master_penduduk');
	}

	function sub_update($id=0){
        	$data['sub_analisis_penduduk']          = $this->master_analisis_penduduk_model->get_data_sub($id);
        	$idmaster=$data['sub_analisis_penduduk']['id_master'];
		$this->master_analisis_penduduk_model->update_sub($id);
		redirect("analisis/master_penduduk/sub_analisis_penduduk_list/$idmaster");
	}

	function sub_delete($id=0){
        	$data['sub_analisis_penduduk']= $this->master_analisis_penduduk_model->get_data_sub($id);
        	$idmaster=$data['sub_analisis_penduduk']['id_master'];
		$this->master_analisis_penduduk_model->delete_sub($id);
		redirect("analisis/master_penduduk/sub_analisis_penduduk_list/$idmaster");
	}

	function delete($id=''){
		$this->master_analisis_penduduk_model->delete($id);
		redirect('analisis/master_penduduk');
	}

	function delete_all(){
		$this->master_analisis_penduduk_model->delete_all();
		redirect('analisis/master_penduduk');
	}

	function lock($id=''){
		$this->master_analisis_penduduk_model->locking($id,2);
		redirect('analisis/master_penduduk');
	}

	function unlock($id=''){
		$this->master_analisis_penduduk_model->locking($id,1);
		redirect('analisis/master_penduduk');
	}

}
