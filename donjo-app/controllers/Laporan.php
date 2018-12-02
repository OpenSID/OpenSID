<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Laporan extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('user_model');
		$grup = $this->user_model->sesi_grup($_SESSION['sesi']);
		if ($grup != 1 AND $grup != 2 AND $grup != 3)
		{
			if (empty($grup))
				$_SESSION['request_uri'] = $_SERVER['REQUEST_URI'];
			else
				unset($_SESSION['request_uri']);
			redirect('siteman');
		}
		$this->load->model('header_model');
		$this->load->model('laporan_bulanan_model');
		$this->load->model('pamong_model');

		//Initialize Session ------------
		$_SESSION['success'] = 0;
		$_SESSION['cari'] = '';
		//-------------------------------

		$this->load->model('header_model');
		$this->modul_ini = 3;
	}

	public function clear()
	{
		$_SESSION['bulanku'] = date("n");
		$_SESSION['tahunku'] = date("Y");
		$_SESSION['per_page'] = 200;
		redirect('laporan');
	}

	public function index($lap = 0, $p = 1, $o = 0)
	{
		$data['p'] = $p;
		$data['o'] = $o;

		if (isset($_POST['per_page']))
			$_SESSION['per_page'] = $_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];

		if (isset($_SESSION['bulanku']))
			$data['bulanku'] = $_SESSION['bulanku'];
		else
		{
			$data['bulanku'] = date("n");
			$_SESSION['bulanku'] = $data['bulanku'];
		}

		if (isset($_SESSION['tahunku']))
			$data['tahunku'] = $_SESSION['tahunku'];
		else
		{
			$data['tahunku'] = date("Y");
			$_SESSION['tahunku'] = $data['tahunku'];
		}

		$data['bulan'] = $data['bulanku'];
		$data['tahun'] = $data['tahunku'];
		$data['config'] = $this->laporan_bulanan_model->configku();
		$data['pamong'] = $this->pamong_model->list_data(true);
		$data['penduduk_awal'] = $this->laporan_bulanan_model->penduduk_awal();
		$data['penduduk_akhir'] = $this->laporan_bulanan_model->penduduk_akhir();
		$data['kelahiran'] = $this->laporan_bulanan_model->kelahiran();
		$data['kematian'] = $this->laporan_bulanan_model->kematian();
		$data['pendatang'] = $this->laporan_bulanan_model->pendatang();
		$data['pindah'] = $this->laporan_bulanan_model->pindah();
		$data['hilang'] = $this->laporan_bulanan_model->hilang();
		$data['lap'] = $lap;
		$nav['act'] = 3;
		$nav['act_sub'] = 28;
		$header = $this->header_model->get_data();

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('laporan/bulanan', $data);
		$this->load->view('footer');
	}

	public function cetak($lap = 0)
	{
		$data['input'] = $_POST;
		$data['config'] = $this->laporan_bulanan_model->configku();
		$data['bulan'] = $_SESSION['bulanku'];
		$data['tahun'] = $_SESSION['tahunku'];
		$data['bln'] = $this->laporan_bulanan_model->bulan($data['bulan']);
		$data['penduduk_awal'] = $this->laporan_bulanan_model->penduduk_awal();
		$data['penduduk_akhir'] = $this->laporan_bulanan_model->penduduk_akhir();
		$data['kelahiran'] = $this->laporan_bulanan_model->kelahiran();
		$data['kematian'] = $this->laporan_bulanan_model->kematian();
		$data['pendatang'] = $this->laporan_bulanan_model->pendatang();
		$data['pindah'] = $this->laporan_bulanan_model->pindah();
		$data['hilang'] = $this->laporan_bulanan_model->hilang();
		$data['lap'] = $lap;
		$this->load->view('laporan/bulanan_print', $data);
	}

	public function excel($lap = 0)
	{
		$data['input'] = $_POST;
		$data['config'] = $this->laporan_bulanan_model->configku();
		$data['bulan'] = $_SESSION['bulanku'];
		$data['tahun'] = $_SESSION['tahunku'];
		$data['bln'] = $this->laporan_bulanan_model->bulan($data['bulan']);
		$data['penduduk_awal'] = $this->laporan_bulanan_model->penduduk_awal();
		$data['penduduk_akhir'] = $this->laporan_bulanan_model->penduduk_akhir();
		$data['kelahiran'] = $this->laporan_bulanan_model->kelahiran();
		$data['kematian'] = $this->laporan_bulanan_model->kematian();
		$data['pendatang'] = $this->laporan_bulanan_model->pendatang();
		$data['pindah'] = $this->laporan_bulanan_model->pindah();
		$data['hilang'] = $this->laporan_bulanan_model->hilang();
		$data['lap'] = $lap;
		$this->load->view('laporan/bulanan_excel', $data);
	}

	public function bulan()
	{
		$bulanku = $this->input->post('bulan');
		if ($bulanku != "")
			$_SESSION['bulanku'] = $bulanku;
		else unset($_SESSION['bulanku']);

		$tahunku = $this->input->post('tahun');
		if ($tahunku != "")
			$_SESSION['tahunku'] = $tahunku;
		else unset($_SESSION['tahunku']);
		redirect('laporan');
	}
}
