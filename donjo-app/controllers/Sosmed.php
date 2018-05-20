<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Sosmed extends CI_Controller{

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
		$this->load->model('web_sosmed_model');
		$this->modul_ini = 13;
	}

	function index(){
		$data['main']    = $this->web_sosmed_model->get_sosmed(1);
		$id=$data['main']['id'];
		$data['form_action'] = site_url("sosmed/update/1");
		$header = $this->header_model->get_data();
		$nav['act']=6;

		$this->load->view('header', $header);
		$this->load->view('web/nav',$nav);
		$this->load->view('sosmed/facebook',$data);
		$this->load->view('footer');
	}

	function twitter(){
		$data['main']    = $this->web_sosmed_model->get_sosmed(2);
		$id=$data['main']['id'];
		$data['form_action'] = site_url("sosmed/update/2/$id");
		$header = $this->header_model->get_data();

		$nav['act']=6;
		$this->load->view('header', $header);
		$this->load->view('web/nav',$nav);
		$this->load->view('sosmed/twitter',$data);
		$this->load->view('footer');
	}

	function instagram(){
		$data['main']    = $this->web_sosmed_model->get_sosmed(5);
		$data['form_action'] = site_url("sosmed/update/5");
		$header = $this->header_model->get_data();

		$nav['act']=6;
		$this->load->view('header', $header);
		$this->load->view('web/nav',$nav);
		$this->load->view('sosmed/instagram',$data);
		$this->load->view('footer');
	}

	function google(){
		$data['main']    = $this->web_sosmed_model->get_sosmed(3);
		$data['form_action'] = site_url("sosmed/update/3");
		$header = $this->header_model->get_data();

		$nav['act']=6;
		$this->load->view('header', $header);
		$this->load->view('web/nav',$nav);
		$this->load->view('sosmed/google',$data);
		$this->load->view('footer');
	}

	function youtube(){
		$data['main']    = $this->web_sosmed_model->get_sosmed(4);
		$data['form_action'] = site_url("sosmed/update/4");
		$header = $this->header_model->get_data();

		$nav['act']=6;
		$this->load->view('header', $header);
		$this->load->view('web/nav',$nav);
		$this->load->view('sosmed/youtube',$data);
		$this->load->view('footer');
	}

	function update($id=''){
		$this->web_sosmed_model->update($id);
		switch ($id) {
			case '1':
				redirect("sosmed");
				break;
			case '2':
				redirect("sosmed/twitter");
				break;
			case '3':
				redirect("sosmed/google");
				break;
			case '4':
				redirect("sosmed/youtube");
				break;
			case '5':
				redirect("sosmed/instagram");
				break;
			default:
				redirect("sosmed");
				break;
		}
	}

}
