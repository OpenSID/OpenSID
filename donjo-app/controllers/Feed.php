<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Feed extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('header_model');
		$this->load->model('feed_model');
		$this->load->model('config_model');
	}

	public function index()
	{
		$header = $this->header_model->get_data();
		$data["data_config"] = $this->config_model->get_data();
		$data["feeds"] = $this->feed_model->list_feeds();
		$this->load->view("feed", $data);
	}
}