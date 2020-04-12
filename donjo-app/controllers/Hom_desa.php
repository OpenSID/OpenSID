<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Hom_desa extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('header_model');
		$this->load->model('config_model');
		$this->load->model('wilayah_model');
		$this->modul_ini = 200;
		$this->sub_modul_ini = 17;
	}

	public function index()
	{
		redirect('hom_desa/konfigurasi');
	}

	public function konfigurasi()
	{
		$this->load->model('provinsi_model');
		$data['main'] = $this->config_model->get_data();
		$data['list_provinsi'] = $this->provinsi_model->list_data();
		$header = $this->header_model->get_data();
		
		$this->load->view('header',$header);
		$this->load->view('nav',$nav);
		$this->load->view('home/konfigurasi',$data);
		$this->load->view('footer');
	}

  public function konfigurasi_form()
	{
		$this->load->model('provinsi_model');
		$header = $this->header_model->get_data();
		$data['main'] = $this->config_model->get_data();

		$this->load->view('header',$header);
		$this->load->view('nav',$nav);
		// Buat row data desa di konfigurasi_form apabila belum ada data desa
		if ($data['main']) $data['form_action'] = site_url("hom_desa/update/".$data['main']['id']);
			else $data['form_action'] = site_url("hom_desa/insert/");
		$data['list_provinsi'] = $this->provinsi_model->list_data();
		$this->load->view('home/konfigurasi_form',$data);
		$this->load->view('footer');
	}

	public function insert()
	{
		$this->config_model->insert();
		redirect('hom_desa/konfigurasi');
	}

	public function update($id='')
	{
		$this->config_model->update($id);
		redirect("hom_desa/konfigurasi");
	}

	public function ajax_kantor_maps()
	{
		$data_desa = $this->config_model->get_data();

		$data['wil_ini'] = $data_desa;
    $data['wil_atas']['lat'] = -1.0546279422758742;
    $data['wil_atas']['lng'] = 116.71875000000001;
    $data['wil_atas']['zoom'] = 4;
		$sebutan_desa = ucwords($this->setting->sebutan_desa);
		$data['wil_atas'] = $this->config_model->get_data();
		$data['dusun_gis'] = $this->wilayah_model->list_dusun();
		$data['rw_gis'] = $this->wilayah_model->list_rw_gis();
		$data['rt_gis'] = $this->wilayah_model->list_rt_gis();
    $data['nama_wilayah'] = ucwords($this->setting->sebutan_desa." ".$data_desa['nama_desa']);
    $data['wilayah'] = ucwords($this->setting->sebutan_desa." ".$data_desa['nama_desa']);
    $data['breadcrumb'] = array(
    	array('link' => site_url("hom_desa/konfigurasi"), 'judul' => "Identitas ".ucwords($this->setting->sebutan_desa)),
    );
		$data['form_action'] = site_url("hom_desa/update_kantor_maps/");

    $header = $this->header_model->get_data();
		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view("sid/wilayah/ajax_kantor_maps", $data);
		$this->load->view('footer');
	}

	public function ajax_wilayah_maps()
	{
		$data_desa = $this->config_model->get_data();
		$data['wil_ini'] = $data_desa;
		$data['wil_atas']['lat'] = -1.0546279422758742;
		$data['wil_atas']['lng'] = 116.71875000000001;
		$data['wil_atas']['zoom'] = 4;
		$sebutan_desa = ucwords($this->setting->sebutan_desa);
		$data['wil_atas'] = $this->config_model->get_data();
		$data['dusun_gis'] = $this->wilayah_model->list_dusun();
		$data['rw_gis'] = $this->wilayah_model->list_rw_gis();
		$data['rt_gis'] = $this->wilayah_model->list_rt_gis();
		$data['nama_wilayah'] = ucwords($this->setting->sebutan_desa." ".$data_desa['nama_desa']);
		$data['wilayah'] = ucwords($this->setting->sebutan_desa." ".$data_desa['nama_desa']);
		$data['breadcrumb'] = array(
			array('link' => site_url("hom_desa/konfigurasi"), 'judul' => "Identitas ".ucwords($this->setting->sebutan_desa)),
		);
		$data['form_action'] = site_url("hom_desa/update_wilayah_maps/");
		$header = $this->header_model->get_data();
		
		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view("sid/wilayah/ajax_wilayah_maps", $data);
		$this->load->view('footer');
	}

	public function update_kantor_maps()
	{
		$this->config_model->update_kantor();
		redirect("hom_desa/konfigurasi");
	}

	public function update_wilayah_maps()
	{
		$this->config_model->update_wilayah();
		redirect("hom_desa/konfigurasi");
	}

}
