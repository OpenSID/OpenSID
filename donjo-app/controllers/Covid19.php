<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Covid19 extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->library('session');
		$this->load->model('header_model');
		$this->load->model('covid19_model');

		$this->modul_ini = 206;
	}

	public function index()
	{
		$this->data_pemudik(1);
	}

	public function data_pemudik($page = 1)
	{
		$this->sub_modul_ini = 207;

		if (isset($_POST['per_page'])) 
			$this->session->set_userdata('per_page', $_POST['per_page']);
		else 
			$this->session->set_userdata('per_page', 10);
		
		$data = $this->covid19_model->get_list_pemudik($page);
		$data['per_page'] = $this->session->userdata('per_page');

		$header = $this->header_model->get_data();
		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('covid19/data_pemudik', $data);
		$this->load->view('footer');
	}

	public function form_pemudik()
	{
		$this->sub_modul_ini = 207;
		
		$d = new DateTime('NOW');
		$data['tanggal_datang'] = $d->format('Y-m-d H:i:s');

		$data['list_penduduk'] = $this->covid19_model->get_penduduk_not_in_pemudik();

		if (isset($_POST['terdata']))
		{
			$data['individu'] = $this->covid19_model->get_penduduk_by_id($_POST['terdata']);
		}
		else
		{
			$data['individu'] = NULL;
		}

		$data['select_tujuan_mudik'] = $this->covid19_model->list_tujuan_mudik();
		$data['select_status_covid'] = $this->covid19_model->list_status_covid();


		$nav['act'] = 206;
		$header = $this->header_model->get_data();
		$this->load->view('header', $header);
		$this->load->view('nav', $nav);

		$data['form_action'] = site_url("covid19/add_pemudik");
		$this->load->view('covid19/form_pemudik', $data);
		$this->load->view('footer');
	}

	public function add_pemudik()
	{
		$this->covid19_model->add_pemudik($_POST);
		redirect("covid19");
	}
	
	public function hapus_pemudik($id_pemudik)
	{
		$this->redirect_hak_akses('h', "covid19");
		$this->covid19_model->delete_pemudik_by_id($id_pemudik);
		redirect("covid19");
	}

	public function edit_pemudik_form($id = 0)
	{
		$data = $this->covid19_model->get_pemudik_by_id($id);	
		$data['select_tujuan_mudik'] = $this->covid19_model->list_tujuan_mudik();
		$data['select_status_covid'] = $this->covid19_model->list_status_covid();
		
		$data['form_action'] = site_url("covid19/edit_pemudik/$id");
		$this->load->view('covid19/edit_pemudik', $data);
	}

	public function edit_pemudik($id)
	{
		$this->covid19_model->update_pemudik_by_id($_POST, $id);
		redirect("covid19");
	}
	
	public function detil_pemudik($id)
	{
		$nav['act'] = 206;
		$header = $this->header_model->get_data();
		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$data['terdata'] = $this->covid19_model->get_pemudik_by_id($id);
		$data['individu'] = $this->covid19_model->get_penduduk_by_id($data['terdata']['id_terdata']);

		$data['terdata']['judul_terdata_nama'] = 'NIK';
		$data['terdata']['judul_terdata_info'] = 'Nama Terdata';
		$data['terdata']['terdata_nama'] = $data['individu']['nik'];
		$data['terdata']['terdata_info'] = $data['individu']['nama'];

		$this->load->view('covid19/detil_pemudik', $data);
		$this->load->view('footer');
	}

	public function unduhsheet()
	{
		$this->session->set_userdata('per_page', 0); // Unduh semua data
		$data = $this->covid19_model->get_list_pemudik(1);
		$data['desa'] = $this->header_model->get_data();
		$this->session->set_userdata('per_page', 10); // Kembalikan ke paginasi default

		$this->load->view('covid19/unduh-sheet', $data);
	}

	public function pantau($page=1, $filter_tgl=null, $filter_nik=null)
	{
		$this->sub_modul_ini = 208;

		if (isset($_POST['per_page'])) 
			$this->session->set_userdata('per_page', $_POST['per_page']);
		else 
			$this->session->set_userdata('per_page', 10);
		$data['per_page'] = $this->session->userdata('per_page');
		$data['page'] = $page;
		
		// get list pemudik
		$data['pemudik_array'] = $this->covid19_model->get_list_pemudik_wajib_pantau(true);
		// get list pemudik end

		// get list pemantauan
		$pantau_pemudik = $this->covid19_model->get_list_pantau_pemudik($page, $filter_tgl, $filter_nik);
		$data['unique_nik'] = $this->covid19_model->get_unique_nik_pantau_pemudik();
		$data['unique_date'] = $this->covid19_model->get_unique_date_pantau_pemudik();
		$data['filter_tgl'] = isset($filter_tgl) ? $filter_tgl : '0';
		$data['filter_nik'] = isset($filter_nik) ? $filter_nik : '0';

		$data['paging'] = $pantau_pemudik["paging"];
		$data['pantau_pemudik_array'] = $pantau_pemudik["query_array"];
		// get list pemantauan end
		
		// datetime now
		$d = new DateTime('NOW');
		$data['datetime_now'] = $d->format('Y-m-d H:i:s');

		$data['this_url'] = site_url("covid19/pantau");
		$data['form_action'] = site_url("covid19/add_pantau");


		$url_delete_front = "covid19/hapus_pantau";
		$url_delete_rare = "$page";
		$data['url_delete_front'] = $url_delete_front;
		$data['url_delete_rare'] = $url_delete_rare;

		$header = $this->header_model->get_data();
		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('covid19/pantau_pemudik', $data);
		$this->load->view('footer');
	}
	
	public function add_pantau()
	{
		$this->covid19_model->add_pantau_pemudik($_POST);
		$url = "covid19/pantau/".$_POST["page"]."/".$_POST["data_h_plus"];
		redirect($url);
	}

	public function hapus_pantau($id_pantau_pemudik, $page=NULL, $h_plus=NULL)
	{
		$this->redirect_hak_akses('h', "covid19");
		$this->covid19_model->delete_pantau_pemudik_by_id($id_pantau_pemudik);

		$url = "covid19/pantau";
		$url .= (isset($page) ? "/$page" : "");
		$url .= (isset($h_plus) ? "/$h_plus" : "");
		redirect($url);
	}

	public function unduhpantau($filter_tgl, $filter_nik)
	{
		$this->session->set_userdata('per_page', 0); // Unduh semua data
		$data = $this->covid19_model->get_list_pantau_pemudik(1, $filter_tgl, $filter_nik);
		$data['desa'] = $this->header_model->get_data();
		$this->session->set_userdata('per_page', 10); // Kembalikan ke paginasi default

		$this->load->view('covid19/unduh-pantau', $data);
	}
	
}
