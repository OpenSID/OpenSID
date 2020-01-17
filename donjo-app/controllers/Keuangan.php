<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Keuangan extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('keuangan_model');
		$this->load->model('header_model');
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
		$sess = array(
			'set_tahun' => $data['tahun_anggaran'][0],
			'set_semester' => 1
		);
		$this->session->set_userdata( $sess );
		$data['id_keuangan_master'] = $this->keuangan_model->data_id_keuangan_master();

		$data['pendapatan_desa'] = $this->keuangan_model->pendapatan_desa($data['id_keuangan_master']);
		$data['realisasi_pendapatan_desa'] = $this->keuangan_model->realisasi_pendapatan_desa($data['id_keuangan_master']);
		// print_r($data['pendapatan_desa']);die();
		$header = $this->header_model->get_data();
		$nav['act_sub'] = 203;
		$header['minsidebar'] = 1;
		if (!empty($data['tahun_anggaran']))
		{
			$this->load->view('header', $header);
			$this->load->view('nav', $nav);
			$this->load->view('keuangan/laporan',$data);
			$this->load->view('footer');
		}
		else
		{
			$_SESSION['success'] = -1;
			$_SESSION['error_msg'] = "Data Laporan Keuangan Belum Tersedia";
			redirect("keuangan/impor_data");
		}
	}

	public function anggaran($tahun, $smt)
	{
		$sess = array(
			'set_tahun' => $tahun,
			'set_semester' => $smt
		);
		$this->session->set_userdata( $sess );

		$data['data_anggaran'] = $this->keuangan_model->data_anggaran_tahun($tahun);
		$data['data_realisasi'] = $this->keuangan_model->data_grafik_utama($tahun);
		echo json_encode($data);
	}

	public function grafik($jenis)
	{
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
		$nav['act_sub'] = 203;
		$header['minsidebar'] = 1;
		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$smt = $this->session->userdata('set_semester');
		$thn = $this->session->userdata('set_tahun');

		switch ($jenis) {
			case 'grafik-R-PD':
				$this->grafik_r_pd($thn, $smt);
				break;
			case 'grafik-RP-APBD':
				$this->grafik_rp_apbd($thn, $smt);
				break;
			case 'grafik-R-BD':
				$this->grafik_r_bd($thn, $smt);
				break;
			case 'grafik-R-PEMDES';
				$this->grafik_r_pemdes($thn, $smt);
				break;
			case 'rincian_realisasi':
				$this->rincian_realisasi($thn, $smt);
				break;
			case 'rincian_realisasi_smt1':
				$this->rincian_realisasi_smt1($thn, $smt);
				break;

			default:
				$this->grafik_r_pd($thn, $smt);
				break;
		}

		$this->load->view('footer');
	}

	private function rincian_realisasi($thn, $smt)
	{
		$data = $this->keuangan_grafik_model->lap_rp_apbd($smt, $thn);
		$data['tahun_anggaran'] = $this->keuangan_model->list_tahun_anggaran();
		$this->load->view('keuangan/rincian_realisasi', $data);
	}

	private function rincian_realisasi_smt1($thn, $smt)
	{
		$data = $this->keuangan_grafik_model->lap_rp_apbd_smt1($smt, $thn);
		$data['tahun_anggaran'] = $this->keuangan_model->list_tahun_anggaran();
		$this->load->view('keuangan/rincian_realisasi_smt1', $data);
	}

	public function cetak($jenis)
	{
		$data['tahun_anggaran'] = $this->keuangan_model->list_tahun_anggaran();
		$tahun = $this->session->userdata('set_tahun') ? $this->session->userdata('set_tahun') : $data['tahun_anggaran'][0];
		$semester = $this->session->userdata('set_semester') ? $this->session->userdata('set_semester') : 0;
		$sess = array(
			'set_tahun' => $tahun,
			'set_semester' => $semester
		);
		$this->session->set_userdata( $sess );
		$this->load->model('keuangan_grafik_model');
		$smt = $this->session->userdata('set_semester');
		$thn = $this->session->userdata('set_tahun');

		switch ($jenis) {
			case 'cetak_rincian_realisasi':
				$this->cetak_rincian_realisasi($thn, $smt);
				break;
			case 'cetak_rincian_realisasi_smt1':
				$this->cetak_rincian_realisasi_smt1($thn, $smt);
				break;
			default:
				$this->cetak_rincian_realisasi($thn, $smt);
				break;
		}
	}

	private function cetak_rincian_realisasi($thn, $smt)
	{
		$data = $this->keuangan_grafik_model->lap_rp_apbd($smt, $thn);
		$data['tahun_anggaran'] = $this->keuangan_model->list_tahun_anggaran();
		$data['ta'] = $this->session->userdata('set_tahun');
		$header = $this->header_model->get_data();
		$data['desa'] = $header['desa'];
		$this->load->view('keuangan/cetak_tabel_laporan_rp_apbd.php', $data);
	}

	private function cetak_rincian_realisasi_smt1($thn, $smt)
	{
		$data = $this->keuangan_grafik_model->lap_rp_apbd_smt1($smt, $thn);
		$data['tahun_anggaran'] = $this->keuangan_model->list_tahun_anggaran();
		$data['ta'] = $this->session->userdata('set_tahun');
		$header = $this->header_model->get_data();
		$data['desa'] = $header['desa'];
		$this->load->view('keuangan/cetak_tabel_laporan_rp_apbd.php', $data);
	}

	private function grafik_r_pd($thn, $smt)
	{
		$data = $this->keuangan_grafik_model->r_pd($smt, $thn);
		$jp = array();
		foreach ($data['jenis_pendapatan'] as $b)
		{
			$jp[] = "'". $b['Nama_Jenis']. "'";
		}
		$anggaran = array();
		foreach ($data['anggaran'] as $a)
		{
      if(!empty($a['Pagu']) || !is_null($a['Pagu']))
      {
        $anggaran[] =  $a['Pagu'];
      }
      else
      {
        $anggaran[] =  0;
      }
		}
		$realisasi = array();
		foreach ($data['realisasi'] as $r)
		{
			if(!empty($r['realisasi']) || !is_null($r['realisasi']))
			{
				$realisasi[] =  $r['realisasi'];
			}
			else
			{
				$realisasi[] =  0;
			}
		}
		$data_chart = array(
			'type' => $jenis,
			'smt' => $smt,
			'thn' => $thn,
			'jp' => $jp,
			'anggaran' => $anggaran,
			'realisasi' => $realisasi,
			'tahun_anggaran' => $this->keuangan_model->list_tahun_anggaran()
		);
		$this->load->view('keuangan/grafik_r_pd', $data_chart);
	}

	private function grafik_rp_apbd($thn, $smt)
	{
		$data = $this->keuangan_grafik_model->rp_apbd($smt, $thn);
		$jenisbelanja = array();
		foreach ($data['jenis_belanja'] as $j)
		{
			$jenisbelanja[] = "'". $j['Nama_Akun']. "'";
		}
		$anggaran = array();
		foreach ($data['anggaran'] as $p)
		{
			$anggaran[] = $p['AnggaranStlhPAK'];
		}
		$realisasi = array();
		foreach ($data['realisasi'] as $s)
		{
			if(!empty($s['realisasi']) || !is_null($s['realisasi']))
			{
				$realisasi[] =  $s['realisasi'];
			}
			else
			{
				$realisasi[] =  0;
			}
		}
		$data_chart = array(
			'type' => $jenis,
			'smt' => $smt,
			'thn' => $thn,
			'jenisbelanja' => $jenisbelanja,
			'anggaran' => $anggaran,
			'realisasi' => $realisasi,
			'tahun_anggaran' => $this->keuangan_model->list_tahun_anggaran()
		);
		$this->load->view('keuangan/grafik_rp_apbd', $data_chart);
	}

	private function grafik_r_bd($thn, $smt)
	{
		$data = $this->keuangan_grafik_model->r_bd($smt, $thn);
		$bidang = array();
		foreach ($data['bidang'] as $b)
		{
			$bidang[] = "'". $b['Nama_Bidang']. "'";
		}
		$anggaran = array();
		foreach ($data['anggaran'] as $a)
		{
      if(!empty($a['Pagu']) || !is_null($a['Pagu']))
      {
        $anggaran[] =  $a['Pagu'];
      }
      else
      {
        $anggaran[] =  0;
      }
		}
		$realisasi = array();
		foreach ($data['realisasi'] as $r)
		{
			if(!empty($r['realisasi']) || !is_null($r['realisasi']))
			{
				$realisasi[] =  $r['realisasi'];
			}
			else
			{
				$realisasi[] =  0;
			}
		}
		$data_chart = array(
			'type' => $jenis,
			'smt' => $smt,
			'thn' => $thn,
			'bidang' => $bidang,
			'anggaran' => $anggaran,
			'realisasi' => $realisasi,
			'tahun_anggaran' => $this->keuangan_model->list_tahun_anggaran()
		);
		$this->load->view('keuangan/grafik_r_bd', $data_chart);
	}

	private function grafik_r_pemdes($thn, $smt)
	{
		$data = $this->keuangan_grafik_model->r_pembiayaan($smt, $thn);
		$pembiayaan = array();
		foreach ($data['pembiayaan'] as $d)
		{
			$pembiayaan[] = "'". $d['Nama_Kelompok']. "'";
		}
		$anggaran = array();
		foreach ($data['anggaran'] as $a)
		{
			$anggaran[] = $a['Pagu'];
		}
		$realisasi = array();
		foreach ($data['realisasi'] as $r)
		{
			if(!empty($r['Nilai']) || !is_null($r['Nilai']))
			{
				$realisasi[] =  $r['Nilai'];
			}
			else
			{
				$realisasi[] =  0;
			}
		}
		$data_chart = array(
			'type' => $jenis,
			'smt' => $smt,
			'thn' => $thn,
			'pembiayaan' => $pembiayaan,
			'anggaran' => $anggaran,
			'realisasi' => $realisasi,
			'tahun_anggaran' => $this->keuangan_model->list_tahun_anggaran()
		);
		$this->load->view('keuangan/grafik_r_pemdes', $data_chart);
	}

	public function impor_data()
	{
		$data['main'] = $this->keuangan_model->list_data();
		$data['form_action'] = site_url("keuangan/proses_impor");
		$header = $this->header_model->get_data();
		$nav['act_sub'] = 202;
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
		$_SESSION['success'] = 1;
		$outp = $this->keuangan_model->delete($id);
		if (!$outp) $_SESSION['success'] = -1;
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
}
