


<? if(!defined('BASEPATH')) exit('No direct script access allowed');

class Master_keluarga extends CI_Controller{

	function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('user_model');
		$this->load->model('master_analisis_keluarga_model');
		$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
		if($grup < 1) redirect('siteman');
		$this->load->model('header_model');
		
	}
		
	function clear(){
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		redirect('master_keluarga');
	}

	function index(){
			
		if(isset($_SESSION['cari']))
			$data['cari'] = $_SESSION['cari'];
		else $data['cari'] = '';
		
		if(isset($_SESSION['filter']))
			$data['filter'] = $_SESSION['filter'];
		else $data['filter'] = '';
		
		if(isset($_SESSION['jenis']))
			$data['jenis'] = $_SESSION['jenis'];
		else $data['jenis'] = '';
		
	
		$data['main'] = $this->master_analisis_keluarga_model->list_data();
		$data['list_jenis'] = $this->master_analisis_keluarga_model->list_jenis();
		$data['keyword'] = $this->master_analisis_keluarga_model->autocomplete();
		$nav['act']= 2;
		$header = $this->header_model->get_data();
		
		$this->load->view('header',$header);
		
		$this->load->view('analisis/nav',$nav);
		$this->load->view('analisis/master_analisis_keluarga',$data);
		$this->load->view('footer');
	}
		
	function form($id=''){
		
		if($id){
			$data['master_analisis_keluarga']          = $this->master_analisis_keluarga_model->get_data($id);
			$data['form_action'] = site_url("master_keluarga/update/$id");
		}
		else{
			$data['master_analisis_keluarga']          = null;
			$data['form_action'] = site_url("master_keluarga/insert");
		}
		
		$header = $this->header_model->get_data();
		
		$this->load->view('header',$header);
		
		$nav['act']= 0;
		$this->load->view('analisis/nav',$nav);
		$this->load->view('analisis/master_analisis_keluarga_form',$data);
		$this->load->view('footer');
	}
				
	function sub_analisis_keluarga($id=''){
		$data['form_action'] = site_url("master_keluarga/sub_insert/$id");
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		
		$nav['act']= 0;
		$this->load->view('analisis/nav',$nav);
		$this->load->view('analisis/sub_analisis_keluarga_form',$data);
		$this->load->view('footer');
	}
	
	function sub_analisis_keluarga_form($id=''){
	if($id){
			$data['sub_analisis_keluarga']= $this->master_analisis_keluarga_model->get_data_sub($id);
			$idmaster=$data['sub_analisis_keluarga']['id_master'];
			$data['form_action'] = site_url("master_keluarga/sub_analisis_keluarga_update/$id");
			$data['form_action_back'] = site_url("master_keluarga/sub_analisis_keluarga_list/$idmaster");
		}
		else{
			$data['sub_analisis_keluarga']          = null;
			$data['form_action'] = site_url("sub_analisis_keluarga_insert");
		}
		
		$header = $this->header_model->get_data();
		
		$this->load->view('header',$header);
		
		$nav['act']= 0;
		$this->load->view('analisis/nav',$nav);
		$this->load->view('analisis/sub_analisis_keluarga_form_edit',$data);
		$this->load->view('footer');
	}
					
	function sub_analisis_keluarga_list($id=''){
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		$data['master'] = $this->master_analisis_keluarga_model->get_data($id);
		$data['main'] = $this->master_analisis_keluarga_model->list_data_sub($id);
		
		
		$nav['act']= 0;
		$this->load->view('analisis/nav',$nav);
		$this->load->view('analisis/sub_analisis_keluarga_list',$data);
		$this->load->view('footer');
	}
	
	function sub_analisis_keluarga_update($id=''){
        	$data['sub_analisis_keluarga']= $this->master_analisis_keluarga_model->get_data_sub($id);
		$idmaster=$data['sub_analisis_keluarga']['id_master'];
		$this->master_analisis_keluarga_model->sub_update($id);
		redirect("master_keluarga/sub_analisis_keluarga_list/$idmaster");
	}
	
	function search(){
		$cari = $this->input->post('cari');
		if($cari!='')
			$_SESSION['cari']=$cari;
		else unset($_SESSION['cari']);
		redirect('master_keluarga');
	}
	
	function filter(){
		$filter = $this->input->post('filter');
		if($filter!=0)
			$_SESSION['filter']=$filter;
		else unset($_SESSION['filter']);
		redirect('master_keluarga');
	}
	
	function jenis(){
		$jenis = $this->input->post('jenis');
		if($jenis!=0)
			$_SESSION['jenis']=$jenis;
		else unset($_SESSION['jenis']);
		redirect('master_keluarga');
	}
	
	function insert(){
		$this->master_analisis_keluarga_model->insert();
		redirect('master_keluarga');
	}
	
	function update($id=''){
		$this->master_analisis_keluarga_model->update($id);
		redirect('master_keluarga');
	}
		
	function sub_insert($id=0){
		$this->master_analisis_keluarga_model->insert_sub($id);
		redirect('master_keluarga');
	}
	
	function sub_update($id=0){
		$this->master_analisis_keluarga_model->update_sub($id);
		redirect('master_keluarga');
	}
	
	function sub_delete($id=0){
        	$data['sub_analisis_keluarga']= $this->master_analisis_keluarga_model->get_data_sub($id);
		$idmaster=$data['sub_analisis_keluarga']['id_master'];
		$this->master_analisis_keluarga_model->delete_sub($id);
		redirect("master_keluarga/sub_analisis_keluarga_list/$idmaster");
	}

	function delete($id=''){
		$this->master_analisis_keluarga_model->delete($id);
		redirect('master_keluarga');
	}
	
	function delete_all(){
		$this->master_analisis_keluarga_model->delete_all();
		redirect('master_keluarga');
	}	
	
	function lock($id=''){
		$this->master_analisis_keluarga_model->locking($id,2);
		redirect('master_keluarga');
	}
	
	function unlock($id=''){
		$this->master_analisis_keluarga_model->locking($id,1);
		redirect('master_keluarga');
	}
	
	function pat(){
		$this->master_analisis_keluarga_model->pat();
	}
	
}
