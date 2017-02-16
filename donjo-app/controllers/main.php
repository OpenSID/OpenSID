<?php
class Main extends CI_Controller {
	function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('header_model');
		$this->load->model('user_model');
		$this->load->model('config_model');
	}
	function index(){
		$out = $this->config_model->install();
		if($out == 1){
			if(isset($_SESSION['siteman'])){
				$this->load->model('user_model');
				if(isset($_SESSION['sesi'])){
					$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
					switch($grup){
						case 1: redirect('hom_desa'); break;
						case 2: redirect('hom_desa'); break;
						case 3: redirect('web'); break;
						case 4: redirect('web'); break;
						default:if(isset($_SESSION['siteman'])){redirect('siteman');}else{redirect('first');}
					}
				}
			}else{
				redirect('first');
			}
		}else{
			redirect('main/initial');
		}
	}
	function initial(){
		$this->load->view('install');
	}
	function install(){
		$out = $this->config_model->initial();	
		$this->load->view('init',$out);
	}
	function init($out=null){
		$this->load->view('init',$out);		
	}
	function auth(){
		$this->user_model->login();
		$header = $this->header_model->get_config();
		$this->load->view('siteman',$header);
	}
	function logout(){
		$this->config_model->opt();
		$this->user_model->logout();
		$header = $this->header_model->get_config();
		$this->load->view('siteman',$header);
	}
}