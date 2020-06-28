<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends Admin_Controller {

	private $_header;

	public function __construct()
	{
		parent::__construct();
		$this->load->model(['config_model', 'header_model','theme_model']);
		$this->_header = $this->header_model->get_data();
		$this->modul_ini = 11;
		$this->sub_modul_ini = 43;
	}

	public function index()
	{
		$data['list_tema'] = $this->theme_model->list_all();
		$data['judul'] = 'Pengaturan Aplikasi';
		$data['list_setting'] = 'list_setting';
		$this->setting_model->load_options();

		$this->load->view('header', $this->_header);
		$this->load->view('nav');
		$this->load->view('setting/setting_form', $data);
		$this->load->view('footer');
	}

	public function update()
	{
		$this->setting_model->update($this->input->post());
		redirect($_SERVER['HTTP_REFERER']);
	}

	public function info_sistem()
	{
		$this->sub_modul_ini = 46;

		$this->load->view('header', $this->_header);
		$this->load->view('nav');
		$this->load->view('setting/info_php');
		$this->load->view('footer');
	}

	/* Pengaturan web */
	public function web()
	{
		$this->modul_ini = 13;
		$this->sub_modul_ini = 211;

		$data['list_tema'] = $this->theme_model->list_all();
		$data['judul'] = 'Pengaturan Halaman Web';
		$data['list_setting'] = 'list_setting_web';
		$this->setting_model->load_options();

		$this->load->view('header', $this->_header);
		$this->load->view('nav');
		$this->load->view('setting/setting_form', $data);
		$this->load->view('footer');
	}

	public function qrcode($aksi = '', $file = '')
	{
		if ($aksi == 'clear')
		{
			$this->session->unset_userdata('qrcode');
			redirect('setting/qrcode');
		}

		if ($aksi == 'download')
		{
			$this->load->helper('download');
			force_download(LOKASI_MEDIA.''.$file.'.png', NULL);
			redirect('setting/qrcode');
		}

		$this->modul_ini = 11;
		$this->sub_modul_ini = 212;

		$data['qrcode'] = $this->session->qrcode ?: $qrcode = ['changeqr' => '1', 'sizeqr' => '6', 'backqr' => '#ffffff'];
		$data['list_changeqr'] = ['Otomatis (Logo Desa)', 'Pilih Manual'];
		$data['list_sizeqr'] = ['25', '50', '75', '100', '125', '150', '200', '225', '250'];
		$data['form_action'] = site_url("setting/qrcode_generate");

		$this->load->view('header', $this->_header);
		$this->load->view('nav');
		$this->load->view('setting/setting_qr', $data);
		$this->load->view('footer');
	}

	public function qrcode_generate()
	{
		$pathqr = LOKASI_MEDIA;
		$post = $this->input->post();
		$namaqr = str_replace(' ', '_', nama_terbatas($post['namaqr'])); // Nama file gambar yg dinormalkan
		$changeqr = $post['changeqr'];

		if($changeqr == '1')
		{
			$desa = $this->config_model->get_data();

			$logoqr = gambar_desa($desa['logo']);
		}
		else
		{
			$logoqr = $post['logoqr'];
		}

		$qrcode = [
			'namaqr' => $namaqr,
			'isiqr' => $post['isiqr'], // Isi / arti dr qrcode
			'changeqr' => $changeqr, // Pilihan input logo
			'logoqr' => $logoqr, // Logo yg disisipkan
			'sizeqr' => bilangan($post['sizeqr']), // Ukuran qrcode
			'backqr' => '#ffffff', // Code warna default asli (#ffffff / putih)
			'foreqr' => $post['foreqr'], // Code warna asli
			'pathqr' => $pathqr.''.$namaqr.'.png'
		];

		$this->session->qrcode = $qrcode;

		if ($post)
		{
			$this->session->success = 1;
			$data = qrcode_generate($pathqr, $namaqr, $qrcode['isiqr'], $qrcode['logoqr'], $qrcode['sizeqr'], $qrcode['backqr'], $qrcode['foreqr']);
			echo json_encode($data);
		}
		else
		{
			$this->session->success = -1;
		}
	}

}
