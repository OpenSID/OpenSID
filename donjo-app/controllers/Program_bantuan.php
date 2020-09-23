<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * Controller untuk modul Program Bantuan
 *
 * donjo-app/controllers/Program_bantuan.php
 *
 */

/**
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:

 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.

 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package	OpenSID
 * @author	Tim Pengembang OpenDesa
 * @copyright	Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright	Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license	http://www.gnu.org/licenses/gpl.html	GPL V3
 * @link 	https://github.com/OpenSID/OpenSID
 */

class Program_bantuan extends Admin_Controller {

	private $_set_page;

	public function __construct()
	{
		parent::__construct();
		$this->load->model(['program_bantuan_model', 'config_model']);
		$this->modul_ini = 6;
		$this->_set_page = ['20', '50', '100'];
	}

	public function clear()
	{
		$this->session->per_page = $this->_set_page[0];
		$this->session->unset_userdata('sasaran');
		redirect('program_bantuan');
	}

	public function filter($filter)
	{
		$value = $this->input->post($filter);
		if ($value != '')
			$this->session->$filter = $value;
		else $this->session->unset_userdata($filter);
		redirect('program_bantuan');
	}

	public function index($p = 1)
	{
		$per_page = $this->input->post('per_page');
		if (isset($per_page))
			$this->session->per_page = $per_page;

		$data = $this->program_bantuan_model->get_program($p, FALSE);
		$data['list_sasaran'] = unserialize(SASARAN);
		$data['func'] = 'index';
		$data['per_page'] = $this->session->per_page;
		$data['set_page'] = $this->_set_page;
		$data['set_sasaran'] = $this->session->sasaran;

		$this->render('program_bantuan/program', $data);
	}

	public function form($program_id = 0)
	{
		$data['program'] = $this->program_bantuan_model->get_program(1, $program_id);
		$sasaran = $data['program'][0]['sasaran'];
		$nik = $this->input->post('nik');

		if (isset($nik))
		{
			$data['individu'] = $this->program_bantuan_model->get_peserta($nik, $sasaran);
			$data['individu']['program'] = $this->program_bantuan_model->get_peserta_program($sasaran, $data['individu']['id_peserta']);
		}
		else
		{
			$data['individu'] = NULL;
		}

		$data['form_action'] = site_url("program_bantuan/add_peserta/".$program_id);

		$this->render('program_bantuan/form', $data);
	}

	public function panduan()
	{
		$this->render('program_bantuan/panduan', $data);
	}

	public function detail($program_id = 0, $p = 1)
	{
		$per_page = $this->input->post('per_page');
		if (isset($per_page))
			$this->session->per_page = $per_page;

		$data['cari'] = $this->session->cari ?: '';
		$data['program'] = $this->program_bantuan_model->get_program($p, $program_id);
		$data['keyword'] = $this->program_bantuan_model->autocomplete($program_id, $this->input->post('cari'));
		$data['paging'] = $data['program'][0]['paging'];
		$data['p'] = $p;
		$data['func'] = "detail/$program_id";
		$data['per_page'] = $this->session->per_page;
		$data['set_page'] = $this->_set_page;
		$this->set_minsidebar(1);

		$this->render('program_bantuan/detail', $data);
	}

	// $id = program_peserta.id
	public function peserta($cat = 0, $id = 0)
	{
		$data = $this->program_bantuan_model->get_peserta_program($cat, $id);

		$this->render('program_bantuan/peserta', $data);
	}

	// $id = program_peserta.id
	public function data_peserta($id = 0)
	{
		$data['peserta'] = $this->program_bantuan_model->get_program_peserta_by_id($id);

		switch ($data['peserta']['sasaran'])
		{
			case '1':
			case '2':
				$peserta_id = $data['peserta']['kartu_id_pend'];
				break;
			case '3':
			case '4':
				$peserta_id = $data['peserta']['peserta'];
				break;
		}
		$data['individu'] = $this->program_bantuan_model->get_peserta($peserta_id, $data['peserta']['sasaran']);
		$data['individu']['program'] = $this->program_bantuan_model->get_peserta_program($data['peserta']['sasaran'], $data['peserta']['peserta']);
		$data['detail'] = $this->program_bantuan_model->get_data_program($data['peserta']['program_id']);
		$this->set_minsidebar(1);

		$this->render('program_bantuan/data_peserta', $data);
	}

	public function add_peserta($program_id = 0)
	{
		$this->program_bantuan_model->add_peserta($program_id);

		$redirect = ($this->session->userdata('aksi') != 1) ? $_SERVER['HTTP_REFERER'] : "program_bantuan/detail/$program_id";

		$this->session->unset_userdata('aksi');

		redirect($redirect);
	}

	public function aksi($aksi = '', $program_id = 0)
	{
		$this->session->set_userdata('aksi', $aksi);

		redirect("program_bantuan/form/$program_id");
	}

	public function hapus_peserta($program_id = 0, $peserta_id = '')
	{
		$this->redirect_hak_akses('h', "program_bantuan/detail/$program_id");
		$this->program_bantuan_model->hapus_peserta($peserta_id);
		redirect("program_bantuan/detail/$program_id");
	}

	public function delete_all($program_id = 0)
	{
		$this->redirect_hak_akses('h', "program_bantuan/detail/$program_id");
		$this->program_bantuan_model->delete_all();
		redirect("program_bantuan/detail/$program_id");
	}

	// $id = program_peserta.id
	public function edit_peserta($id = 0)
	{
		$this->program_bantuan_model->edit_peserta($id);
		$program_id = $this->input->post('program_id');
		redirect("program_bantuan/detail/$program_id");
	}

	// $id = program_peserta.id
	public function edit_peserta_form($id = 0)
	{
		$data = $this->program_bantuan_model->get_program_peserta_by_id($id);
		$data['form_action'] = site_url("program_bantuan/edit_peserta/$id");
		$this->load->view('program_bantuan/edit_peserta', $data);
	}

	public function create()
	{
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('cid', 'Sasaran', 'required');
		$this->form_validation->set_rules('nama', 'Nama Program', 'required');
		$this->form_validation->set_rules('sdate', 'Tanggal awal', 'required');
		$this->form_validation->set_rules('edate', 'Tanggal akhir', 'required');
		$this->form_validation->set_rules('asaldana', 'Asal Dana', 'required');
		$this->form_validation->set_rules('status', 'Status', 'required');

		$data['asaldana'] = unserialize(ASALDANA);

		if ($this->form_validation->run() === FALSE)
		{
			$this->render('program_bantuan/create', $data);
		}
		else
		{
			$this->program_bantuan_model->set_program();
			redirect("program_bantuan");
		}
	}

	// $id = program.id
	public function edit($id = 0)
	{
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('cid', 'Sasaran', 'required');
		$this->form_validation->set_rules('nama', 'Nama Program', 'required');
		$this->form_validation->set_rules('sdate', 'Tanggal awal', 'required');
		$this->form_validation->set_rules('edate', 'Tanggal akhir', 'required');
		$this->form_validation->set_rules('asaldana', 'Asal Dana', 'required');
		$this->form_validation->set_rules('status', 'Status', 'required');

		$data['asaldana'] = unserialize(ASALDANA);
		$data['program'] = $this->program_bantuan_model->get_program(1, $id);
		$data['jml'] = $this->program_bantuan_model->jml_peserta_program($id);

		if ($this->form_validation->run() === FALSE)
		{
			$this->render('program_bantuan/edit', $data);
		}
		else
		{
			$this->program_bantuan_model->update_program($id);
			redirect("program_bantuan");
		}
	}

	// $id = program.id
	public function update($id)
	{
		$this->program_bantuan_model->update_program($id);
		redirect("program_bantuan/detail/$id");
	}

	// $id = program.id
	public function hapus($id)
	{
		$this->redirect_hak_akses('h', "program_bantuan");
		$this->program_bantuan_model->hapus_program($id);
		redirect("program_bantuan");
	}

	/*
	 * $aksi = cetak/unduh
	 */
	public function daftar($program_id = 0, $aksi = '')
	{
		if ($program_id > 0)
		{
			$temp = $this->session->per_page;
			$this->session->per_page = 1000000000; // Angka besar supaya semua data terunduh
			$data["sasaran"] = unserialize(SASARAN);

			$data['config'] = $this->config_model->get_data();
			$data['peserta'] = $this->program_bantuan_model->get_program(1, $program_id);
			$data['aksi'] = $aksi;
			$this->session->per_page = $temp;

			$this->load->view("program_bantuan/$aksi", $data);
		}
	}

	public function search($program_id = 0)
	{
		$cari = $this->input->post('cari');

		if ($cari != '')
			$this->session->cari = $cari;
		else $this->session->unset_userdata('cari');

		redirect("program_bantuan/detail/$program_id");
	}
}
