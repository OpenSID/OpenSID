<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Keuangan_manual extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('keuangan_manual_model');
		$this->load->model('header_model');
		$this->load->model('keuangan_grafik_manual_model');
		$this->modul_ini = 201;
	}

	// Manual Input Anggaran dan Realisasi APBDes
	public function setdata_laporan($tahun, $semester)
	{
		$sess_manual = array(
			'set_tahun' => $tahun,
			'set_semester' => $semester
		);
		$this->session->set_userdata( $sess_manual );
		echo json_encode(true);
	}

	public function laporan_manual()
	{
		$data['tahun_anggaran'] = $this->keuangan_manual_model->list_tahun_anggaran_manual();

		if (!empty($data['tahun_anggaran']))
		{
			redirect("keuangan_manual/grafik_manual/rincian_realisasi_bidang_manual");
		}
		else
		{
			$this->session->success = -1;
			$this->session->error_msg = "Data Laporan Keuangan Belum Tersedia";
			redirect("keuangan_manual/manual_apbdes");
		}
	}

	public function grafik_manual($jenis)
	{
		$this->sub_modul_ini = 210;

		$data['tahun_anggaran'] = $this->keuangan_manual_model->list_tahun_anggaran_manual();
		$tahun = $this->session->userdata('set_tahun') ? $this->session->userdata('set_tahun') : $data['tahun_anggaran'][0];
		$sess_manual = array(
			'set_tahun' => $tahun,
		);
		$this->session->set_userdata( $sess_manual );
		$this->load->model('keuangan_grafik_manual_model');
		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$thn = $this->session->set_tahun;

		switch ($jenis)
		{
			case 'rincian_realisasi_bidang_manual':
				$this->rincian_realisasi_manual($thn, 'Akhir Bidang Manual');
				break;
			case 'grafik-RP-APBD-manual':
				$this->grafik_rp_apbd_manual($thn);
				break;

			default:
				$this->grafik_rp_apbd_manual($thn);
				break;
		}

		$this->load->view('footer');
	}

	private function rincian_realisasi_manual($thn, $judul)
	{
		$data = $this->keuangan_grafik_manual_model->lap_rp_apbd($thn);
		$data['tahun_anggaran'] = $this->keuangan_manual_model->list_tahun_anggaran_manual();
		$data['ta'] = $this->session->userdata('set_tahun');
		$this->session->submenu = "Laporan Keuangan " . $judul;
		$this->load->view('keuangan/rincian_realisasi_manual', $data);
	}

	private function grafik_rp_apbd_manual($thn)
	{
		$data = $this->keuangan_grafik_manual_model->grafik_keuangan_tema($thn);
		$data['tahun_anggaran'] = $this->keuangan_manual_model->list_tahun_anggaran_manual();
		$this->session->submenu = "Grafik Keuangan";
		$this->load->view('keuangan/grafik_rp_apbd_manual', $data);
	}

	public function manual_apbdes()
	{
		$this->sub_modul_ini = 209;

		$data['lpendapatan'] = $this->keuangan_manual_model->list_rek_pendapatan();
		$data['lbelanja'] = $this->keuangan_manual_model->list_rek_belanja();
		$data['lbiaya'] = $this->keuangan_manual_model->list_rek_biaya();
		$data['lakun'] = $this->keuangan_manual_model->list_akun();
		$data['main']= $this->keuangan_manual_model->list_apbdes();
		$data['main_pd']= $this->keuangan_manual_model->list_pendapatan();
		$data['main_bl']= $this->keuangan_manual_model->list_belanja();
		$data['main_by']= $this->keuangan_manual_model->list_pembiayaan();
		$header = $this->header_model->get_data();

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('keuangan/manual_apbdes', $data);
		$this->load->view('footer');
	}

	public function data_anggaran()
	{
		$data = $this->keuangan_manual_model->list_apbdes();
		echo json_encode($data);
	}

	public function data_pendapatan()
	{
		$data = $this->keuangan_manual_model->list_pendapatan();
		echo json_encode($data);
	}

	public function data_belanja()
	{
		$data = $this->keuangan_manual_model->list_belanja();
		echo json_encode($data);
	}

	public function data_pembiayaan()
	{
		$data = $this->keuangan_manual_model->list_pembiayaan();
		echo json_encode($data);
	}

	public function get_anggaran()
	{
		$id = $this->input->get('id');
		$data = $this->keuangan_manual_model->get_anggaran($id);
		echo json_encode($data);
	}

	public function simpan_anggaran()
	{
		$Tahun = $this->input->post('Tahun');
		$Kd_Akun = $this->input->post('Kd_Akun');
		$Kd_Keg = $this->input->post('Kd_Keg');
		$Kd_Rincian = $this->input->post('Kd_Rincian');
		$Nilai_Anggaran = $this->input->post('Nilai_Anggaran');
		$Nilai_Realisasi = $this->input->post('Nilai_Realisasi');
		$data = $this->keuangan_manual_model->simpan_anggaran($Tahun, $Kd_Akun, $Kd_Keg, $Kd_Rincian, $Nilai_Anggaran, $Nilai_Realisasi);
		echo json_encode($data);
	}

	public function update_anggaran()
	{
		$id = $this->input->post('id');
		$Tahun = $this->input->post('Tahun');
		$Kd_Akun = $this->input->post('Kd_Akun');
		$Kd_Keg = $this->input->post('Kd_Keg');
		$Kd_Rincian = $this->input->post('Kd_Rincian');
		$Nilai_Anggaran = $this->input->post('Nilai_Anggaran');
		$Nilai_Realisasi = $this->input->post('Nilai_Realisasi');
		$data = $this->keuangan_manual_model->update_anggaran($id, $Tahun, $Kd_Akun, $Kd_Keg, $Kd_Rincian, $Nilai_Anggaran, $Nilai_Realisasi);
		echo json_encode($data);
	}

	public function delete_input($id = '')
	{
		$this->keuangan_manual_model->delete_input($id);
		redirect("keuangan_manual/manual_apbdes");
	}

	public function delete_all()
	{
		$this->keuangan_manual_model->delete_all();
		redirect("keuangan_manual/manual_apbdes");
	}

	public function salin_anggaran_tpl()
	{
		$thn_apbdes = $this->input->post('kode');
		$data = $this->keuangan_manual_model->salin_anggaran_tpl($thn_apbdes);
		echo json_encode($data);
	}

	// data tahun anggaran untuk keperluan dropdown pada plugin keuangan di text editor
	public function cek_tahun_manual()
	{
		$data = $this->keuangan_manual_model->list_tahun_anggaran_manual();
		$list_tahun = array();
		foreach ($data as $tahun)
		{
			$list_tahun[] = array(
				'text' => $tahun,
				'value' => $tahun
			);
		}
		echo json_encode($list_tahun);
	}
}
