<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Keuangan extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('keuangan_model');
		$this->load->model('header_model');
		$this->load->model('keuangan_grafik_model');
		$this->load->model('keuangan_grafik_manual_model');
		$this->modul_ini = 201;
	}

	public function setdata_laporan($tahun, $semester)
	{
		$sess = array(
			'set_tahun' => $tahun,
			'set_semester' => $semester
		);
		$this->session->set_userdata( $sess );
		echo json_encode(true);
	}

	public function laporan()
	{
		$data['tahun_anggaran'] = $this->keuangan_model->list_tahun_anggaran();

		if (!empty($data['tahun_anggaran']))
		{
			redirect("keuangan/grafik/rincian_realisasi");
		}
		else
		{
			$_SESSION['success'] = -1;
			$_SESSION['error_msg'] = "Data Laporan Keuangan Belum Tersedia";
			redirect("keuangan/impor_data");
		}
	}

	public function grafik($jenis)
	{
		$this->sub_modul_ini = 203;

		$data['tahun_anggaran'] = $this->keuangan_model->list_tahun_anggaran();
		$tahun = $this->session->userdata('set_tahun') ? $this->session->userdata('set_tahun') : $data['tahun_anggaran'][0];
		$semester = $this->session->userdata('set_semester') ? $this->session->userdata('set_semester') : 0;
		$sess = array(
			'set_tahun' => $tahun,
			'set_semester' => $semester
		);
		$this->session->set_userdata( $sess );
		$this->load->model('keuangan_grafik_model');
		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$smt = $this->session->userdata('set_semester');
		$thn = $this->session->userdata('set_tahun');

		switch ($jenis)
		{
			case 'grafik-RP-APBD':
				$this->grafik_rp_apbd($thn);
				break;
			case 'rincian_realisasi':
				$this->rincian_realisasi($thn, 'Akhir');
				break;
			case 'rincian_realisasi_smt1':
				$this->rincian_realisasi($thn, 'Semester1', $smt1=1);
				break;
			case 'rincian_realisasi_bidang':
				$this->rincian_realisasi($thn, 'Akhir Bidang');
				break;
			case 'rincian_realisasi_smt1_bidang':
				$this->rincian_realisasi($thn, 'Semester1 Bidang', $smt1-1);
				break;

			default:
				$this->grafik_rp_apbd($thn);
				break;
		}

		$this->load->view('footer');
	}

	private function rincian_realisasi($thn, $judul, $smt1=false)
	{
		$data = $this->keuangan_grafik_model->lap_rp_apbd($thn, $smt1);
		$data['tahun_anggaran'] = $this->keuangan_model->list_tahun_anggaran();
		$data['ta'] = $this->session->userdata('set_tahun');
		$data['sm'] = $smt1 ? '1' : '2';
		$_SESSION['submenu'] = "Laporan Keuangan " . $judul;
		$this->load->view('keuangan/rincian_realisasi', $data);
	}

	private function grafik_rp_apbd($thn)
	{
		$data = $this->keuangan_grafik_model->grafik_keuangan_tema($thn);
		$data['tahun_anggaran'] = $this->keuangan_model->list_tahun_anggaran();
		$_SESSION['submenu'] = "Grafik Keuangan";
		$this->load->view('keuangan/grafik_rp_apbd', $data);
	}

	public function impor_data()
	{
		$this->sub_modul_ini = 202;
		$data['main'] = $this->keuangan_model->list_data();
		$data['form_action'] = site_url("keuangan/proses_impor");
		$header = $this->header_model->get_data();
		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('keuangan/impor_data', $data);
		$this->load->view('footer');
	}

	public function proses_impor()
	{
		if (empty($_FILES['keuangan']['name']))
		{
			$this->session->success = -1;
			$this->session->error_msg = "Tidak ada file untuk diimpor";
			redirect('keuangan/impor_data');
		}
		if ($_POST['jenis_impor'] == 'update')
		{
			$this->keuangan_model->extract_update();
		}
		else
		{
			$this->keuangan_model->extract();
		}
		redirect('keuangan/impor_data');
	}

	public function cek_versi_database()
	{
		$nama = $_FILES['keuangan'];
		$file_parts = pathinfo($nama['name']);
		if ($file_parts['extension'] === 'zip')
		{
			$cek = $this->keuangan_model->cek_keuangan_master($nama);
			if ($cek == -1)
			{
				echo json_encode(2);
			}
			else if ($cek)
			{
				$output =array('id' => $cek->id, 'tahun_anggaran' => $cek->tahun_anggaran);
				echo json_encode($output);
			}
			else
			{
				echo json_encode(0);
			}
		}
		else
		{
			echo json_encode(1);
		}
	}

	// data tahun anggaran untuk keperluan dropdown pada plugin keuangan di text editor
	public function cek_tahun()
	{
		$data = $this->keuangan_model->list_tahun_anggaran();
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

	public function delete($id = '')
	{
		$this->redirect_hak_akses('h', 'keuangan');
		$outp = $this->keuangan_model->delete($id);
		redirect('keuangan/impor_data');
	}

	public function pilih_desa($id_master)
	{
		$data['desa_ganda'] = $this->keuangan_model->cek_desa($id_master);
		$data['id_master'] = $id_master;
		$this->load->view('keuangan/pilih_desa', $data);
	}

	public function bersihkan_desa($id_master)
	{
		$this->keuangan_model->bersihkan_desa($id_master, $this->input->post('kode_desa'));
		redirect('keuangan/impor_data');
	}

	// Manual Input Anggaran dan Realisasi APBDes

	public function laporan_manual()
	{
		$data['tahun_anggaran'] = $this->keuangan_model->list_tahun_anggaran_manual();

		if (!empty($data['tahun_anggaran']))
		{
			redirect("keuangan/grafik_manual/rincian_realisasi_bidang_manual");
		}
		else
		{
			$_SESSION['success'] = -1;
			$_SESSION['error_msg'] = "Data Laporan Keuangan Belum Tersedia";
			redirect("keuangan/manual_apbdes");
		}
	}

	public function grafik_manual($jenis)
	{
		$this->sub_modul_ini = 210;

		$data['tahun_anggaran'] = $this->keuangan_model->list_tahun_anggaran_manual();
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
		$thn = $this->session->userdata('set_tahun');

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
		$data['tahun_anggaran'] = $this->keuangan_model->list_tahun_anggaran_manual();
		$data['ta'] = $this->session->userdata('set_tahun');
		$_SESSION['submenu'] = "Laporan Keuangan " . $judul;
		$this->load->view('keuangan/rincian_realisasi_manual', $data);
	}

	private function grafik_rp_apbd_manual($thn)
	{
		$data = $this->keuangan_grafik_manual_model->grafik_keuangan_tema($thn);
		$data['tahun_anggaran'] = $this->keuangan_model->list_tahun_anggaran_manual();
		$_SESSION['submenu'] = "Grafik Keuangan";
		$this->load->view('keuangan/grafik_rp_apbd_manual', $data);
	}

	public function manual_apbdes()
	{
		$this->sub_modul_ini = 209;

		$data['lpendapatan'] = $this->keuangan_model->list_rek_pendapatan();
		$data['lbelanja'] = $this->keuangan_model->list_rek_belanja();
		$data['lbiaya'] = $this->keuangan_model->list_rek_biaya();
		$data['lakun'] = $this->keuangan_model->list_akun();
		$data['main']= $this->keuangan_model->list_apbdes();
		$data['main_pd']= $this->keuangan_model->list_pendapatan();
		$data['main_bl']= $this->keuangan_model->list_belanja();
		$data['main_by']= $this->keuangan_model->list_pembiayaan();
		$header = $this->header_model->get_data();

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('keuangan/manual_apbdes', $data);
		$this->load->view('footer');
	}

	public function data_anggaran(){
		$data = $this->keuangan_model->list_apbdes();
		echo json_encode($data);
	}

	public function data_pendapatan(){
		$data = $this->keuangan_model->list_pendapatan();
		echo json_encode($data);
	}

	public function data_belanja(){
		$data = $this->keuangan_model->list_belanja();
		echo json_encode($data);
	}

	public function data_pembiayaan(){
		$data = $this->keuangan_model->list_pembiayaan();
		echo json_encode($data);
	}

	public function get_anggaran(){
		$id = $this->input->get('id');
		$data = $this->keuangan_model->get_anggaran_by_kode($id);
		echo json_encode($data);
	}

	public function simpan_anggaran(){
		$Tahun = $this->input->post('Tahun');
		$Kd_Akun = $this->input->post('Kd_Akun');
		$Kd_Keg = $this->input->post('Kd_Keg');
		$Kd_Rincian = $this->input->post('Kd_Rincian');
		$Nilai_Anggaran = $this->input->post('Nilai_Anggaran');
		$Nilai_Realisasi = $this->input->post('Nilai_Realisasi');
		$data = $this->keuangan_model->simpan_anggaran($Tahun,$Kd_Akun,$Kd_Keg,$Kd_Rincian,$Nilai_Anggaran,$Nilai_Realisasi);
		echo json_encode($data);
	}

	public function update_anggaran(){
		$id = $this->input->post('id');
		$Tahun = $this->input->post('Tahun');
		$Kd_Akun = $this->input->post('Kd_Akun');
		$Kd_Keg = $this->input->post('Kd_Keg');
		$Kd_Rincian = $this->input->post('Kd_Rincian');
		$Nilai_Anggaran = $this->input->post('Nilai_Anggaran');
		$Nilai_Realisasi = $this->input->post('Nilai_Realisasi');
		$data = $this->keuangan_model->update_anggaran($id,$Tahun,$Kd_Akun,$Kd_Keg,$Kd_Rincian,$Nilai_Anggaran,$Nilai_Realisasi);
		echo json_encode($data);
	}

	public function delete_input($id = '')
	{
		$this->keuangan_model->delete_input($id);
		redirect("keuangan/manual_apbdes");
	}

	public function delete_all()
	{
		$this->keuangan_model->delete_all();
		redirect("keuangan/manual_apbdes");
	}

	public function salin_anggaran_tpl()
	{
		$thn_apbdes = $this->input->post('kode');
		$data = $this->keuangan_model->salin_anggaran_tpl($thn_apbdes);
		echo json_encode($data);
	}
}
