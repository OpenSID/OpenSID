<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Hom_sid extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('header_model');
		$this->load->model('program_bantuan_model');
		$this->load->model('surat_model');
		$this->load->library('release');
		$this->modul_ini = 1;
	}

	public function index()
	{
		if ($this->release->hasInternetConnection())
		{
			$this->release->set_api_url('https://api.github.com/repos/opensid/opensid/releases/latest')
				->set_interval(7)
				->set_cache_folder(FCPATH.'desa');

			$data['update_available'] = $this->release->is_available();
			$data['current_version'] = $this->release->get_current_version();
			$data['latest_version'] = $this->release->get_latest_version();
			$data['release_name'] = $this->release->get_release_name();
			$data['release_body'] = $this->release->get_release_body();
		}

		// Pengambilan data penduduk untuk ditampilkan widget Halaman Dashboard (modul Home SID)
		$data['penduduk'] = $this->header_model->penduduk_total();
		$data['keluarga'] = $this->header_model->keluarga_total();
		$data['bantuan'] = $this->header_model->bantuan_total();
		$data['kelompok'] = $this->header_model->kelompok_total();
		$data['rtm'] = $this->header_model->rtm_total();
		$data['dusun'] = $this->header_model->dusun_total();
		$data['jumlah_surat'] = $this->surat_model->surat_total();
		$header = $this->header_model->get_data();

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('home/desa', $data);
		$this->load->view('footer');
	}

	public function dialog_pengaturan()
	{
		$data['list_program_bantuan'] = $this->program_bantuan_model->list_program();
		$data['sasaran'] = unserialize(SASARAN);
		$data['form_action'] = site_url("hom_sid/ubah_program_bantuan");
		$this->load->view('home/pengaturan_form', $data);
	}

	public function ubah_program_bantuan()
	{
		$this->db->where('key','dashboard_program_bantuan')->update('setting_aplikasi', array('value'=>$this->input->post('program_bantuan')));
		redirect('hom_sid');
	}
}
