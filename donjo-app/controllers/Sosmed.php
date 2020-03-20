<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Sosmed extends Admin_Controller 
{

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('web_sosmed_model');
		$this->modul_ini = 13;
	}

	public function index()
	{
		$data['main'] = $this->web_sosmed_model->get_sosmed(1);
		$id = $data['main']['id'];
		$data['form_action'] = site_url("sosmed/update/1");
		$nav['act'] = 13;
		$nav['act_sub'] = 53;
		
		// Isi nilai true jika menggunakan minisidebar
		$this->render_view('sosmed/facebook', $data, $nav);
	}

	public function twitter()
	{
		$data['main'] = $this->web_sosmed_model->get_sosmed(2);
		$id = $data['main']['id'];
		$data['form_action'] = site_url("sosmed/update/2/$id");

		$nav['act'] = 13;
		$nav['act_sub'] = 53;
		
		// Isi nilai true jika menggunakan minisidebar
		$this->render_view('sosmed/twitter', $data, $nav);
	}

	public function instagram()
	{
		$data['main'] = $this->web_sosmed_model->get_sosmed(5);
		$data['form_action'] = site_url("sosmed/update/5");

		$nav['act'] = 13;
		$nav['act_sub'] = 53;
		
		// Isi nilai true jika menggunakan minisidebar
		$this->render_view('sosmed/instagram', $data, $nav);
	}

	public function google()
	{
		$data['main'] = $this->web_sosmed_model->get_sosmed(3);
		$data['form_action'] = site_url("sosmed/update/3");

		$nav['act'] = 13;
		$nav['act_sub'] = 53;
		
		// Isi nilai true jika menggunakan minisidebar
		$this->render_view('sosmed/google', $data, $nav);
	}

	public function youtube()
	{
		$data['main'] = $this->web_sosmed_model->get_sosmed(4);
		$data['form_action'] = site_url("sosmed/update/4");

		$nav['act'] = 13;
		$nav['act_sub'] = 53;
		
		// Isi nilai true jika menggunakan minisidebar
		$this->render_view('sosmed/youtube', $data, $nav);
	}

	public function whatsapp()
	{
		$data['main'] = $this->web_sosmed_model->get_sosmed(6);
		$data['form_action'] = site_url("sosmed/update/6");
		
		$nav['act'] = 13;
		$nav['act_sub'] = 53;
		
		// Isi nilai true jika menggunakan minisidebar
		$this->render_view('sosmed/whatsapp', $data, $nav);
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
