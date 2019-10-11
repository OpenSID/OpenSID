<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Hom_sid extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('header_model');
		$this->load->model('config_model');
		$this->load->model('program_bantuan_model');
		$this->load->model('surat_model');
		$this->modul_ini = 1;
	}

	public function index()
	{
		// Pengambilan data penduduk untuk ditampilkan widget Halaman Dashboard (modul Home SID)
		$data['penduduk'] = $this->header_model->penduduk_total();
		$data['keluarga'] = $this->header_model->keluarga_total();
		$data['bantuan'] = $this->header_model->bantuan_total();
		$data['kelompok'] = $this->header_model->kelompok_total();
		$data['rtm'] = $this->header_model->rtm_total();
		$data['dusun'] = $this->header_model->dusun_total();
		$data['jumlah_surat'] = $this->surat_model->surat_total();
		// Menampilkan menu dan sub menu aktif
		$nav['act'] = 1;
		$nav['act_sub'] = 16;
		$header = $this->header_model->get_data();

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('home/desa', $data);
		$this->load->view('footer');
	}

	public function donasi()
	{
		// Menampilkan menu dan sub menu aktif
		$nav['act'] = 1;
		$nav['act_sub'] = 19;
		$header = $this->header_model->get_data();

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('home/donasi');
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
