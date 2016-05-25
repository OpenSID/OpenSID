<?php
class Main extends CI_Controller {

	function __construct(){
		parent::__construct();
		session_start();
	}
	
	function index(){
		if(isset($_SESSION['siteman'])){	
			$this->load->model('user_model');
			$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
			switch($grup){
				case 1: redirect('hom_desa'); break;
				case 2: redirect('hom_desa'); break;
				case 3: redirect('web'); break;
				default:if(isset($_SESSION['siteman'])){redirect('siteman');}else{redirect('first');}
			}
		}else{
			redirect('first');
		}
	
	}
}
