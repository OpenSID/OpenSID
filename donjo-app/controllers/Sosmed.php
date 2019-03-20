<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Sosmed extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('header_model');
		$this->load->model('web_sosmed_model');
		$this->modul_ini = 13;
	}

	public function index()
	{
		$data['main'] = $this->web_sosmed_model->get_sosmed(1);
		$id = $data['main']['id'];
		$data['form_action'] = site_url("sosmed/update/1");
		$header = $this->header_model->get_data();
		$nav['act'] = 13;
		$nav['act_sub'] = 53;

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('sosmed/facebook', $data);
		$this->load->view('footer');
	}

	public function twitter()
	{
		$data['main'] = $this->web_sosmed_model->get_sosmed(2);
		$id = $data['main']['id'];
		$data['form_action'] = site_url("sosmed/update/2/$id");
		$header = $this->header_model->get_data();

		$nav['act'] = 13;
		$nav['act_sub'] = 53;
		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('sosmed/twitter', $data);
		$this->load->view('footer');
	}

	public function instagram()
	{
		$data['main'] = $this->web_sosmed_model->get_sosmed(5);
		$data['form_action'] = site_url("sosmed/update/5");
		$header = $this->header_model->get_data();

		$nav['act'] = 13;
		$nav['act_sub'] = 53;
		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('sosmed/instagram', $data);
		$this->load->view('footer');
	}

	public function google()
	{
		$data['main'] = $this->web_sosmed_model->get_sosmed(3);
		$data['form_action'] = site_url("sosmed/update/3");
		$header = $this->header_model->get_data();

		$nav['act'] = 13;
		$nav['act_sub'] = 53;
		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('sosmed/google', $data);
		$this->load->view('footer');
	}

	public function youtube()
	{
		$data['main'] = $this->web_sosmed_model->get_sosmed(4);
		$data['form_action'] = site_url("sosmed/update/4");
		$header = $this->header_model->get_data();

		$nav['act'] = 13;
		$nav['act_sub'] = 53;
		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('sosmed/youtube', $data);
		$this->load->view('footer');
	}

	public function whatsapp()
	{
		$data['main'] = $this->web_sosmed_model->get_sosmed(6);
		$data['form_action'] = site_url("sosmed/update/6");
		$header = $this->header_model->get_data();
		$nav['act'] = 13;
		$nav['act_sub'] = 53;
		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('sosmed/whatsapp', $data);
		$this->load->view('footer');
	}

	public function update($id = '')
	{
		$this->web_sosmed_model->update($id);
		switch ($id)
		{
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
			case '6':
				redirect("sosmed/whatsapp");
				break;
			default:
				redirect("sosmed");
				break;
		}
	}

}
