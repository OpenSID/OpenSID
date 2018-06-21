<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class Laporan_inventaris extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('user_model');
		$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
		if ($grup != 1 AND $grup != 2)
		{
			$_SESSION['request_uri'] = $_SERVER['REQUEST_URI'];
			redirect('siteman');
		}
		$this->load->model('header_model');
		$this->load->model('inventaris_laporan_model');
		$this->load->model('referensi_model');
		$this->load->model('config_model');
		$this->load->model('surat_model');
		$this->modul_ini = 16;
		$this->tab_ini = 7;
		$this->controller = 'inventaris_laporan';
	}

	function clear()
	{
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		redirect('inventaris');
	}

	function index()
	{
		$data['main'] = $this->inventaris_laporan_model->list_inventaris();
		$data['total'] = $this->inventaris_laporan_model->sum_inventaris();
		$data['pamong'] = $this->surat_model->list_pamong();
		$header = $this->header_model->get_data();

		$data['inventaris_tanah_pribadi'] = $this->inventaris_laporan_model->inventaris_tanah_pribadi();
		$data['inventaris_tanah_pemerintah'] = $this->inventaris_laporan_model->inventaris_tanah_pemerintah();
		$data['inventaris_tanah_provinsi'] = $this->inventaris_laporan_model->inventaris_tanah_provinsi();
		$data['inventaris_tanah_kabupaten'] = $this->inventaris_laporan_model->inventaris_tanah_kabupaten();
		$data['inventaris_tanah_sumbangan'] = $this->inventaris_laporan_model->inventaris_tanah_sumbangan();

		$data['inventaris_peralatan_pribadi'] = $this->inventaris_laporan_model->inventaris_peralatan_pribadi();
		$data['inventaris_peralatan_pemerintah'] = $this->inventaris_laporan_model->inventaris_peralatan_pemerintah();
		$data['inventaris_peralatan_provinsi'] = $this->inventaris_laporan_model->inventaris_peralatan_provinsi();
		$data['inventaris_peralatan_kabupaten'] = $this->inventaris_laporan_model->inventaris_peralatan_kabupaten();
		$data['inventaris_peralatan_sumbangan'] = $this->inventaris_laporan_model->inventaris_peralatan_sumbangan();

		$data['inventaris_gedung_pribadi'] = $this->inventaris_laporan_model->inventaris_gedung_pribadi();
		$data['inventaris_gedung_pemerintah'] = $this->inventaris_laporan_model->inventaris_gedung_pemerintah();
		$data['inventaris_gedung_provinsi'] = $this->inventaris_laporan_model->inventaris_gedung_provinsi();
		$data['inventaris_gedung_kabupaten'] = $this->inventaris_laporan_model->inventaris_gedung_kabupaten();
		$data['inventaris_gedung_sumbangan'] = $this->inventaris_laporan_model->inventaris_gedung_sumbangan();

		$data['inventaris_jalan_pribadi'] = $this->inventaris_laporan_model->inventaris_jalan_pribadi();
		$data['inventaris_jalan_pemerintah'] = $this->inventaris_laporan_model->inventaris_jalan_pemerintah();
		$data['inventaris_jalan_provinsi'] = $this->inventaris_laporan_model->inventaris_jalan_provinsi();
		$data['inventaris_jalan_kabupaten'] = $this->inventaris_laporan_model->inventaris_jalan_kabupaten();
		$data['inventaris_jalan_sumbangan'] = $this->inventaris_laporan_model->inventaris_jalan_sumbangan();

		$data['inventaris_asset_pribadi'] = $this->inventaris_laporan_model->inventaris_asset_pribadi();
		$data['inventaris_asset_pemerintah'] = $this->inventaris_laporan_model->inventaris_asset_pemerintah();
		$data['inventaris_asset_provinsi'] = $this->inventaris_laporan_model->inventaris_asset_provinsi();
		$data['inventaris_asset_kabupaten'] = $this->inventaris_laporan_model->inventaris_asset_kabupaten();
		$data['inventaris_asset_sumbangan'] = $this->inventaris_laporan_model->inventaris_asset_sumbangan();

		$data['inventaris_kontruksi_pribadi'] = $this->inventaris_laporan_model->inventaris_kontruksi_pribadi();
		$data['inventaris_kontruksi_pemerintah'] = $this->inventaris_laporan_model->inventaris_kontruksi_pemerintah();
		$data['inventaris_kontruksi_provinsi'] = $this->inventaris_laporan_model->inventaris_kontruksi_provinsi();
		$data['inventaris_kontruksi_kabupaten'] = $this->inventaris_laporan_model->inventaris_kontruksi_kabupaten();
		$data['inventaris_kontruksi_sumbangan'] = $this->inventaris_laporan_model->inventaris_kontruksi_sumbangan();
		$this->load->view('header', $header);
		$this->load->view('inventaris/nav',$nav);
		$this->load->view('inventaris/laporan/table',$data);
		$this->load->view('footer');
	}

	function view($id)
	{
		$data['main'] = $this->inventaris_laporan_model->view($id);
		$header = $this->header_model->get_data();
		$this->load->view('header', $header);
		$this->load->view('inventaris/nav',$nav);
		$this->load->view('inventaris/laporan/view_inventaris',$data);
		$this->load->view('footer');
	}

	function view_mutasi($id)
	{
		$data['main'] = $this->inventaris_laporan_model->view_mutasi($id);
		$header = $this->header_model->get_data();
		$this->load->view('header', $header);
		$this->load->view('inventaris/nav',$nav);
		$this->load->view('inventaris/laporan/view_mutasi',$data);
		$this->load->view('footer');
	}

	function edit($id)
	{
		$data['main'] = $this->inventaris_laporan_model->view($id);
		$header = $this->header_model->get_data();
		$this->load->view('header', $header);
		$this->load->view('inventaris/nav',$nav);
		$this->load->view('inventaris/laporan/edit_inventaris',$data);
		$this->load->view('footer');
	}

	function edit_mutasi($id)
	{
		$data['main'] = $this->inventaris_laporan_model->edit_mutasi($id);
		$header = $this->header_model->get_data();
		$this->load->view('header', $header);
		$this->load->view('inventaris/nav',$nav);
		$this->load->view('inventaris/laporan/edit_mutasi',$data);
		$this->load->view('footer');
	}

	function form()
	{
		$header = $this->header_model->get_data();
		$this->load->view('header', $header);
		$this->load->view('inventaris/nav',$nav);
		$this->load->view('inventaris/laporan/form_tambah',$data);
		$this->load->view('footer');
	}

	function form_mutasi($id)
	{
		$data['main'] = $this->inventaris_laporan_model->view($id);
		$header = $this->header_model->get_data();
		$this->load->view('header', $header);
		$this->load->view('inventaris/nav',$nav);
		$this->load->view('inventaris/laporan/form_mutasi',$data);
		$this->load->view('footer');
	}

	function mutasi()
	{
		$data['main'] = $this->inventaris_laporan_model->list_mutasi_inventaris();
		$header = $this->header_model->get_data();
		$this->load->view('header', $header);
		$this->load->view('inventaris/nav',$nav);
		$this->load->view('inventaris/laporan/table_mutasi',$data);
		$this->load->view('footer');
	}

	function cetak($tahun, $penandatangan)
	{
		$data['header'] = $this->header_model->get_config();
		$data['total'] = $this->inventaris_laporan_model->sum_print($tahun);
		$data['tahun'] = $tahun;
		$data['pamong'] = $this->inventaris_laporan_model->pamong($penandatangan);

		$data['cetak_inventaris_tanah_pribadi'] = $this->inventaris_laporan_model->cetak_inventaris_tanah_pribadi($tahun);
		$data['cetak_inventaris_tanah_pemerintah'] = $this->inventaris_laporan_model->cetak_inventaris_tanah_pemerintah($tahun);
		$data['cetak_inventaris_tanah_provinsi'] = $this->inventaris_laporan_model->cetak_inventaris_tanah_provinsi($tahun);
		$data['cetak_inventaris_tanah_kabupaten'] = $this->inventaris_laporan_model->cetak_inventaris_tanah_kabupaten($tahun);
		$data['cetak_inventaris_tanah_sumbangan'] = $this->inventaris_laporan_model->cetak_inventaris_tanah_sumbangan($tahun);

		$data['cetak_inventaris_peralatan_pribadi'] = $this->inventaris_laporan_model->cetak_inventaris_peralatan_pribadi($tahun);
		$data['cetak_inventaris_peralatan_pemerintah'] = $this->inventaris_laporan_model->cetak_inventaris_peralatan_pemerintah($tahun);
		$data['cetak_inventaris_peralatan_provinsi'] = $this->inventaris_laporan_model->cetak_inventaris_peralatan_provinsi($tahun);
		$data['cetak_inventaris_peralatan_kabupaten'] = $this->inventaris_laporan_model->cetak_inventaris_peralatan_kabupaten($tahun);
		$data['cetak_inventaris_peralatan_sumbangan'] = $this->inventaris_laporan_model->cetak_inventaris_peralatan_sumbangan($tahun);

		$data['cetak_inventaris_gedung_pribadi'] = $this->inventaris_laporan_model->cetak_inventaris_gedung_pribadi($tahun);
		$data['cetak_inventaris_gedung_pemerintah'] = $this->inventaris_laporan_model->cetak_inventaris_gedung_pemerintah($tahun);
		$data['cetak_inventaris_gedung_provinsi'] = $this->inventaris_laporan_model->cetak_inventaris_gedung_provinsi($tahun);
		$data['cetak_inventaris_gedung_kabupaten'] = $this->inventaris_laporan_model->cetak_inventaris_gedung_kabupaten($tahun);
		$data['cetak_inventaris_gedung_sumbangan'] = $this->inventaris_laporan_model->cetak_inventaris_gedung_sumbangan($tahun);

		$data['cetak_inventaris_jalan_pribadi'] = $this->inventaris_laporan_model->cetak_inventaris_jalan_pribadi($tahun);
		$data['cetak_inventaris_jalan_pemerintah'] = $this->inventaris_laporan_model->cetak_inventaris_jalan_pemerintah($tahun);
		$data['cetak_inventaris_jalan_provinsi'] = $this->inventaris_laporan_model->cetak_inventaris_jalan_provinsi($tahun);
		$data['cetak_inventaris_jalan_kabupaten'] = $this->inventaris_laporan_model->cetak_inventaris_jalan_kabupaten($tahun);
		$data['cetak_inventaris_jalan_sumbangan'] = $this->inventaris_laporan_model->cetak_inventaris_jalan_sumbangan($tahun);

		$data['cetak_inventaris_asset_pribadi'] = $this->inventaris_laporan_model->cetak_inventaris_asset_pribadi($tahun);
		$data['cetak_inventaris_asset_pemerintah'] = $this->inventaris_laporan_model->cetak_inventaris_asset_pemerintah($tahun);
		$data['cetak_inventaris_asset_provinsi'] = $this->inventaris_laporan_model->cetak_inventaris_asset_provinsi($tahun);
		$data['cetak_inventaris_asset_kabupaten'] = $this->inventaris_laporan_model->cetak_inventaris_asset_kabupaten($tahun);
		$data['cetak_inventaris_asset_sumbangan'] = $this->inventaris_laporan_model->cetak_inventaris_asset_sumbangan($tahun);

		$data['cetak_inventaris_kontruksi_pribadi'] = $this->inventaris_laporan_model->cetak_inventaris_kontruksi_pribadi($tahun);
		$data['cetak_inventaris_kontruksi_pemerintah'] = $this->inventaris_laporan_model->cetak_inventaris_kontruksi_pemerintah($tahun);
		$data['cetak_inventaris_kontruksi_provinsi'] = $this->inventaris_laporan_model->cetak_inventaris_kontruksi_provinsi($tahun);
		$data['cetak_inventaris_kontruksi_kabupaten'] = $this->inventaris_laporan_model->cetak_inventaris_kontruksi_kabupaten($tahun);
		$data['cetak_inventaris_kontruksi_sumbangan'] = $this->inventaris_laporan_model->cetak_inventaris_kontruksi_sumbangan($tahun);
		$this->load->view('inventaris/laporan/inventaris_print',$data);
	}

	function download($tahun, $penandatangan)
	{
		$data['header'] = $this->header_model->get_config();
		$data['total'] = $this->inventaris_laporan_model->sum_print($tahun);
		$data['tahun'] = $tahun;
		$data['pamong'] = $this->inventaris_laporan_model->pamong($penandatangan);

		$data['cetak_inventaris_tanah_pribadi'] = $this->inventaris_laporan_model->cetak_inventaris_tanah_pribadi($tahun);
		$data['cetak_inventaris_tanah_pemerintah'] = $this->inventaris_laporan_model->cetak_inventaris_tanah_pemerintah($tahun);
		$data['cetak_inventaris_tanah_provinsi'] = $this->inventaris_laporan_model->cetak_inventaris_tanah_provinsi($tahun);
		$data['cetak_inventaris_tanah_kabupaten'] = $this->inventaris_laporan_model->cetak_inventaris_tanah_kabupaten($tahun);
		$data['cetak_inventaris_tanah_sumbangan'] = $this->inventaris_laporan_model->cetak_inventaris_tanah_sumbangan($tahun);

		$data['cetak_inventaris_peralatan_pribadi'] = $this->inventaris_laporan_model->cetak_inventaris_peralatan_pribadi($tahun);
		$data['cetak_inventaris_peralatan_pemerintah'] = $this->inventaris_laporan_model->cetak_inventaris_peralatan_pemerintah($tahun);
		$data['cetak_inventaris_peralatan_provinsi'] = $this->inventaris_laporan_model->cetak_inventaris_peralatan_provinsi($tahun);
		$data['cetak_inventaris_peralatan_kabupaten'] = $this->inventaris_laporan_model->cetak_inventaris_peralatan_kabupaten($tahun);
		$data['cetak_inventaris_peralatan_sumbangan'] = $this->inventaris_laporan_model->cetak_inventaris_peralatan_sumbangan($tahun);

		$data['cetak_inventaris_gedung_pribadi'] = $this->inventaris_laporan_model->cetak_inventaris_gedung_pribadi($tahun);
		$data['cetak_inventaris_gedung_pemerintah'] = $this->inventaris_laporan_model->cetak_inventaris_gedung_pemerintah($tahun);
		$data['cetak_inventaris_gedung_provinsi'] = $this->inventaris_laporan_model->cetak_inventaris_gedung_provinsi($tahun);
		$data['cetak_inventaris_gedung_kabupaten'] = $this->inventaris_laporan_model->cetak_inventaris_gedung_kabupaten($tahun);
		$data['cetak_inventaris_gedung_sumbangan'] = $this->inventaris_laporan_model->cetak_inventaris_gedung_sumbangan($tahun);

		$data['cetak_inventaris_jalan_pribadi'] = $this->inventaris_laporan_model->cetak_inventaris_jalan_pribadi($tahun);
		$data['cetak_inventaris_jalan_pemerintah'] = $this->inventaris_laporan_model->cetak_inventaris_jalan_pemerintah($tahun);
		$data['cetak_inventaris_jalan_provinsi'] = $this->inventaris_laporan_model->cetak_inventaris_jalan_provinsi($tahun);
		$data['cetak_inventaris_jalan_kabupaten'] = $this->inventaris_laporan_model->cetak_inventaris_jalan_kabupaten($tahun);
		$data['cetak_inventaris_jalan_sumbangan'] = $this->inventaris_laporan_model->cetak_inventaris_jalan_sumbangan($tahun);

		$data['cetak_inventaris_asset_pribadi'] = $this->inventaris_laporan_model->cetak_inventaris_asset_pribadi($tahun);
		$data['cetak_inventaris_asset_pemerintah'] = $this->inventaris_laporan_model->cetak_inventaris_asset_pemerintah($tahun);
		$data['cetak_inventaris_asset_provinsi'] = $this->inventaris_laporan_model->cetak_inventaris_asset_provinsi($tahun);
		$data['cetak_inventaris_asset_kabupaten'] = $this->inventaris_laporan_model->cetak_inventaris_asset_kabupaten($tahun);
		$data['cetak_inventaris_asset_sumbangan'] = $this->inventaris_laporan_model->cetak_inventaris_asset_sumbangan($tahun);

		$data['cetak_inventaris_kontruksi_pribadi'] = $this->inventaris_laporan_model->cetak_inventaris_kontruksi_pribadi($tahun);
		$data['cetak_inventaris_kontruksi_pemerintah'] = $this->inventaris_laporan_model->cetak_inventaris_kontruksi_pemerintah($tahun);
		$data['cetak_inventaris_kontruksi_provinsi'] = $this->inventaris_laporan_model->cetak_inventaris_kontruksi_provinsi($tahun);
		$data['cetak_inventaris_kontruksi_kabupaten'] = $this->inventaris_laporan_model->cetak_inventaris_kontruksi_kabupaten($tahun);
		$data['cetak_inventaris_kontruksi_sumbangan'] = $this->inventaris_laporan_model->cetak_inventaris_kontruksi_sumbangan($tahun);
		$this->load->view('inventaris/laporan/inventaris_excel',$data);
	}

}
