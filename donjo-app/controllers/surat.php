<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Surat extends CI_Controller{

	function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('user_model');
		$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
		if($grup!=1 AND $grup!=2 AND $grup!=3) redirect('siteman');
		$this->load->model('header_model');
		$this->load->model('penduduk_model');
		$this->load->model('surat_model');
		$this->load->model('surat_keluar_model');

	}

	function index(){
		$header = $this->header_model->get_data();
		$data['menu_surat'] = $this->surat_model->list_surat();
		$data['menu_surat2'] = $this->surat_model->list_surat2();

		$header['modul'] = 4;
		$this->load->view('header', $header);
		$nav['act']= 1;

		$this->load->view('surat/nav',$nav);
		$this->load->view('surat/format_surat',$data);
		$this->load->view('footer');
	}

	function panduan(){
		$header = $this->header_model->get_data();
		$header['modul'] = 4;
		$this->load->view('header', $header);
		$nav['act']= 4;

		$this->load->view('surat/nav',$nav);
		$this->load->view('surat/panduan');
		$this->load->view('footer');
	}

	function form($url='',$clear=''){

		// Ada surat yang memakai SESSION
		if ($clear != '') {
			unset($_SESSION['id_suami']);
			unset($_SESSION['id_istri']);
		}

		$data['url']=$url;
		if(isset($_POST['nik'])){
			$data['individu']=$this->surat_model->get_penduduk($_POST['nik']);
			$data['anggota']=$this->surat_model->list_anggota($data['individu']['id_kk'],$data['individu']['nik']);
		}else{
			$data['individu']=NULL;
			$data['anggota']=NULL;
		}

		$data['penduduk'] = $this->surat_model->list_penduduk();
		$data['pamong'] = $this->surat_model->list_pamong();
		$data['perempuan'] = $this->surat_model->list_penduduk_perempuan();
		$data['kode'] = $this->surat_model->get_daftar_kode_surat($url);

		if ($url == 'surat_persetujuan_mempelai') {
			// Perlu disimpan di SESSION karena belum ketemu cara
			// memanggil flexbox memakai ajax atau menyimpan data
			// TODO: cari pengganti flexbox yang sudah tidak di-support lagi
			if($_POST['id_suami'] != ''){
				$data['suami']=$this->surat_model->get_penduduk($_POST['id_suami']);
				$_SESSION['id_suami'] = $_POST['id_suami'];
			}elseif (isset($_SESSION['id_suami'])){
				$data['suami']=$this->surat_model->get_penduduk($_SESSION['id_suami']);
			}else{
				unset($data['suami']);
			}
			if($_POST['id_istri'] != ''){
				$data['istri']=$this->surat_model->get_penduduk($_POST['id_istri']);
				$_SESSION['id_istri'] = $_POST['id_istri'];
			}elseif (isset($_SESSION['id_istri'])){
				$data['istri']=$this->surat_model->get_penduduk($_SESSION['id_istri']);
			}else{
				$data['istri']=NULL;
			}
			$data['laki'] = $this->surat_model->list_penduduk_laki();
		}

		$data['surat_url'] = rtrim($_SERVER['REQUEST_URI'], "/clear");
		$data['form_action'] = site_url("surat/cetak/$url");
		$data['form_action2'] = site_url("surat/doc/$url");
		$nav['act']= 1;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);

		$this->load->view('surat/nav',$nav);
		$this->load->view("surat/form_surat",$data);
		$this->load->view('footer');

	}

	function cetak($url=''){
		$f=$url;
		$g=$_POST['pamong'];
		$u=$_SESSION['user'];
		$z=$_POST['nomor'];

		//$data['menu_surat'] = $this->surat_model->list_surat();
		$id = $_POST['nik'];
		// surat_persetujuan_mempelai id-nya suami atau istri
		if (!$id) $id = $_POST['id_suami'];
		if (!$id) $id = $_POST['id_istri'];
		$data['input'] = $_POST;
		$data['tanggal_sekarang'] = tgl_indo(date("Y m d"));

		$data['data'] = $this->surat_model->get_data_surat($id);

		$data['pribadi'] = $this->surat_model->get_data_pribadi($id);
		$data['kk'] = $this->surat_model->get_data_kk($id);
		$data['ayah'] = $this->surat_model->get_data_ayah($id);
		$data['ibu'] = $this->surat_model->get_data_ibu($id);

		$data['desa'] = $this->surat_model->get_data_desa();
		$data['pamong'] = $this->surat_model->get_pamong($_POST['pamong']);

		$data['pengikut']=$this->surat_model->pengikut();
		$this->surat_keluar_model->log_surat($f,$id,$g,$u,$z);

		$data['url']=$url;
		$this->load->view("surat/print_surat",$data);
	}

	function doc($url=''){
		$format = $this->surat_model->get_surat($url);
		$f = $format['id'];
		$g=$_POST['pamong'];
		$u=$_SESSION['user'];
		$z=$_POST['nomor'];

		$id = $_POST['nik'];
		// surat_persetujuan_mempelai id-nya suami atau istri
		if (!$id) $id = $_POST['id_suami'];
		if (!$id) $id = $_POST['id_istri'];
		$sql = "SELECT nik FROM tweb_penduduk WHERE id=?";
		$query = $this->db->query($sql,$id);
		$hasil  = $query->row_array();
		$nik = $hasil['nik'];

		$nama_surat = $this->surat_keluar_model->nama_surat_arsip($url, $nik, $z);
		$this->surat_model->coba($url, $nama_surat);
		$this->surat_keluar_model->log_surat($f,$id,$g,$u,$z,$nama_surat);

	}
}