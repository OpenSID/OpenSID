<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Bumindes_pembangunan extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->library('session');
		$this->load->model(['header_model','pembangunan_model','kader_pemberdayaan_model','pamong_model','penduduk_model']);

		$this->modul_ini = 301;
	}

	public function index()
	{
		$this->tables("tanah");
	}

	public function tables($page="tanah", $page_number=1, $offset=0)
	{
		$this->sub_modul_ini = 305;

		
		if (isset($_SESSION['cari']))
			$data['cari'] = $_SESSION['cari'];
		else $data['cari'] = '';

		if (isset($_SESSION['filter']))
			$data['filter'] = $_SESSION['filter'];
		else $data['filter'] = '';

		if (isset($_POST['per_page']))
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];
		$data = array_merge($data, $this->load_data_tables($page, $page_number, $offset));

		$header = $this->header_model->get_data();

		$this->load->view('header', $header);
		$this->load->view('nav');
		$this->load->view('bumindes/pembangunan/main', $data);
		$this->load->view('footer');
	}
	public function form($id = '')
	{
		if ($id)
		{
		$cek_data = $this->kader_pemberdayaan_model->get_kader($id);
		$data['kursus'] = $this->kader_pemberdayaan_model->carikursus();
		$data['pembangunan'] = $cek_data;
		$data['form_action'] = site_url("bumindes_pembangunan/ubahkursus/$id");
		}
		else
		{
			
			$data['kursus'] = $this->kader_pemberdayaan_model->carikursus();
			$data['form_action'] = site_url("bumindes_pembangunan/tambah");
		}

		$data['list_sasaran'] = unserialize(SASARAN);
		$this->set_minsidebar(1);

		$this->render('bumindes/kader_pemberdayaan/form', $data);
	}
	public function get_autocomplete(){
		$postData = $this->input->post();
		$data = $this->kader_pemberdayaan_model->getUsers($postData);
		echo json_encode($data);
    }
	public function get_fill(){
		$sql = $this->db->query("SELECT nama FROM tweb_penduduk_kursus WHERE nama LIKE '%".$_GET['query']."%'"); 
$json = [];
foreach($sql->result_array() as $row) {
	 $json[] = $row['nama'];
}
$tes = $json;
$hsl = array_unique($tes);
$res = [];
foreach($hsl as $e) {
array_push($res, ...explode(",", $e));
}
$n = array_unique($res);
echo json_encode($n);
    }
	public function get_fill_keahlian(){
		$sql = $this->db->query("SELECT nama FROM tweb_penduduk_keahlian WHERE nama LIKE '%".$_GET['query']."%'"); 
$json = [];
foreach($sql->result_array() as $row) {
	 $json[] = $row['nama'];
}
$tes = $json;
$hsl = array_unique($tes);
$res = [];
foreach($hsl as $e) {
array_push($res, ...explode(",", $e));
}
$n = array_unique($res);
echo json_encode($n);
    }
	public function tambah()
	{
		$this->kader_pemberdayaan_model->insert();
		redirect('bumindes_pembangunan/tables/kader_pemberdayaan');
	}

	public function ubahkursus($id)
	{
		$this->kader_pemberdayaan_model->ubahkursus($id);
		redirect('bumindes_pembangunan/tables/kader_pemberdayaan');
	}
	public function delete($id='')
	{
	
		$this->kader_pemberdayaan_model->delete($id);
		redirect('bumindes_pembangunan/tables/kader_pemberdayaan');
	}
	public function delete_all($id='',$didik = '')
	{
	
		$this->kader_pemberdayaan_model->delete_all();
		redirect('bumindes_pembangunan/tables/kader_pemberdayaan');
	}
	public function cetakkader()
	{
	
		$p = 1;
		$o = 0;
		
			$judul = 'pendataan';
			$per_page = $this->input->post('per_page');
			if (isset($per_page))
				$this->session->per_page = $per_page;
			$sasaran = $this->input->post('sasaran');
			if (isset($sasaran))
				$this->session->sasaran = $sasaran;
			$data['func'] = 'index';
			$data['set_page'] = $this->_set_page;
		$data['paging'] = $this->kader_pemberdayaan_model->paging($p, $o);
		$data['pembangunan'] = $this->kader_pemberdayaan_model->list_data($data['paging']->offset, $data['paging']->per_page);
	   	$data['input'] = $post;
		$data['judul'] = $judul;
		$data['config'] = $this->header['desa'];
		$data['pamong_ketahui'] = $this->pamong_model->get_ttd();
		$data['pamong_ttd'] = $this->pamong_model->get_ub();
		$data['main'] = $this->penduduk_model->list_data($o, NULL, NULL);
		$data['bulan'] = $this->session->filter_bulan;
		$data['tahun'] = $this->session->filter_tahun;
		$data['tgl_cetak'] = $_POST['tgl_cetak'];
		$data['file'] = "Buku Kader Pemberdayaan Masyarakat";
		$data['isi'] = "bumindes/kader_pemberdayaan/cetak";
		$data['letak_ttd'] = ['2', '2', '9'];
	
	    $this->load->view('global/format_cetak', $data);
		
	}
		private function load_data_tables($page=null, $page_number=1, $offset=0)
	{
		$data['selected_nav'] = $page;
		switch (strtolower($page))
		{
			case 'tanah':
				$data = array_merge($data, $this->load_tanah_data_tables($page_number, $offset));
				break;

			case 'tanah_kas':
				$data = array_merge($data, $this->load_tanah_kas_data_tables($page_number, $offset));
				break;
	       case 'kader_pemberdayaan':
					$data = array_merge($data, $this->load_kader_pemberdayaan_tables($page_number, $offset));
					break;
			default:
				$data = array_merge($data, $this->load_tanah_data_tables($page_number, $offset));
				break;
		}
		return $data;
	}

	private function load_tanah_data_tables($page_number=1, $offset=0)
	{
		$data['main_content'] = "bumindes/pembangunan/content_tanah";
		$data['subtitle'] = "Buku Tanah di Desa";

		return $data;
	}

	private function load_tanah_kas_data_tables($page_number=1, $offset=0)
	{
		$data['main_content'] = "bumindes/pembangunan/content_tanah_kas";
		$data['subtitle'] = "Buku Tanah Kas Desa";

		return $data;
	}
	private function load_kader_pemberdayaan_tables($page_number=1, $offset=0)
	{
		$per_page = $this->input->post('per_page');
		if (isset($per_page))
			$this->session->per_page = $per_page;
		$sasaran = $this->input->post('sasaran');
		if (isset($sasaran))
			$this->session->sasaran = $sasaran;
		$data['func'] = 'index';
		$data['set_page'] = $this->_set_page;
		$data['paging'] = $this->kader_pemberdayaan_model->paging($page_number, $offset);
		$data['pembangunan'] = $this->kader_pemberdayaan_model->list_data($data['paging']->offset, $data['paging']->per_page);
		$data['main_content'] = "bumindes/kader_pemberdayaan/main";
		$data['subtitle'] = "Buku Kader Pemberdayaan";

		return $data;
	}
}
