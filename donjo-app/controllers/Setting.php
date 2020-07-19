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
		switch ($aksi)
		{
			case 'clear':
				$this->session->unset_userdata('qrcode');
				redirect('setting/qrcode');

			case 'hapus':
				unlink(LOKASI_MEDIA.''.$file.'.png');
				redirect('setting/qrcode/clear');

			case 'unduh':
				$this->load->helper('download');
				force_download(LOKASI_MEDIA.$file.'.png', NULL);
				redirect('setting/qrcode');

			default:
				$this->modul_ini = 11;
				$this->sub_modul_ini = 212;

				$data['qrcode'] = $this->session->qrcode ?: $qrcode = ['changeqr' => '1', 'sizeqr' => '6', 'backqr' => '#ffffff'];
				$data['list_changeqr'] = ['Otomatis (Logo Desa)', 'Manual'];
				$data['list_sizeqr'] = ['25', '50', '75', '100', '125', '150', '175', '200', '225', '250'];

				$this->load->view('header', $this->_header);
				$this->load->view('nav');
				$this->load->view('setting/setting_qr', $data);
				$this->load->view('footer');
				break;
		}
	}

	public function qrcode_generate()
	{
		$pathqr = LOKASI_MEDIA; // Lokasi default simpan file qrcode
		$post = $this->input->post();
		$namaqr = $post['namaqr']; // Nama file gambar asli
		$namaqr1 = str_replace(' ', '_', nama_terbatas($namaqr)); // Nama file gambar yg akan disimpan
		$changeqr = $post['changeqr'];

		// $logoqr = yg akan ditampilkan, url
		// $logoqr1 = yg akan disimpan, directory
		if ($changeqr == '1')
		{
			$desa = $this->config_model->get_data();
			// Ambil absolute path, bukan url
			$logoqr1 = gambar_desa($desa['logo'], false, $file = true);
		}
		else
		{
			$logoqr = $post['logoqr'];
			// Ubah url (http) menjadi absolute path ke file di lokasi media
			$lokasi_media = preg_quote(LOKASI_MEDIA, '/');
			$file_logoqr = preg_split('/'.$lokasi_media.'/', $logoqr)[1];
			$logoqr1 = APPPATH.'../'.LOKASI_MEDIA.$file_logoqr;
		}

		$qrcode = [
			'namaqr' => $namaqr, // Nama file
			'namaqr1' => $namaqr1, // Nama file untuk download
			'isiqr' => $post['isiqr'], // Isi / arti dr qrcode
			'changeqr' => $changeqr, // Pilihan jenis sisipkan logo
			'logoqr' => $logoqr,
			'sizeqr' => bilangan($post['sizeqr']), // Ukuran qrcode
			'backqr' => '#ffffff', // Code warna default (#ffffff / putih)
			'foreqr' => $post['foreqr'], // Code warna asli
			'pathqr' => base_url(LOKASI_MEDIA.''.$namaqr1.'.png') // Tampilkan gambar qrcode
		];

		$this->session->qrcode = $qrcode;

		if ($post)
		{
			$this->session->success = 1;
			$data = qrcode_generate($pathqr, $namaqr1, $qrcode['isiqr'], $logoqr1, $qrcode['sizeqr'], $qrcode['backqr'], $qrcode['foreqr']);
			echo json_encode($data);
		}
		else
		{
			$this->session->success = -1;
		}
	}

}
