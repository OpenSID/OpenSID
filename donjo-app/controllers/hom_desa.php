<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Hom_Desa extends CI_Controller{

	function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('user_model');
		$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
		if($grup!=1 AND $grup!=2) redirect('siteman');
		$this->load->model('header_model');
		$this->load->model('config_model');
	}
	
	function index(){
		$_SESSION['delik'] = 1;
		$nav['act']= 0;
		$header = $this->header_model->get_data();
		$data['main'] = $this->config_model->get_data();
		$this->load->view('header',$header);
		$this->load->view('home/nav',$nav);
		$this->load->view('home/konfigurasi_form',$data);
		$this->load->view('footer');
	}
	
	function about(){
		$nav['act']= 2;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		$this->load->view('home/nav',$nav);
		$this->load->view('home/desa');
		$this->load->view('footer');
	}
	
	function insert(){
		$this->config_model->insert();
		redirect('hom_desa');
	}
	
	function update($id=''){
		$this->config_model->update($id);
		redirect("hom_desa");
	}
		
	function ajax_kantor_maps(){
		$data['desa'] = $this->config_model->get_data();
		$data['form_action'] = site_url("hom_desa/update_kantor_maps/");
		$this->load->view("home/ajax_kantor_desa_maps", $data);
	}
	
	function ajax_wilayah_maps(){
		$data['desa'] = $this->config_model->get_data();
		$data['form_action'] = site_url("hom_desa/update_wilayah_maps/");
		$this->load->view("home/ajax_wilayah_desa_maps", $data);
	}
	
	function update_kantor_maps(){
		$this->config_model->update_kantor();
		redirect("hom_desa");
	}
	
	function update_wilayah_maps(){
		$this->config_model->update_wilayah();
		redirect("hom_desa");
	}
	
	function kosong_pend(){
		$this->config_model->kosong_pend();
		redirect("hom_desa");
	}
		
	function undelik(){
		if(isset($_SESSION['delik'])){
			unset($_SESSION['delik']);
		}
		redirect("analisis_master/clear");
	}
		
}
