<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Hom_sid extends Admin_Controller {

	private $_header;

	public function __construct()
	{
		parent::__construct();
		$this->load->model(['header_model', 'program_bantuan_model', 'surat_model']);
		$this->_header = $this->header_model->get_data();
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

		$this->load->view('header', $this->_header);
		$this->load->view('nav');
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
