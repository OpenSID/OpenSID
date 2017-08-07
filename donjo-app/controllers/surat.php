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
		$this->load->model('keluarga_model');
		$this->load->model('surat_model');
		$this->load->model('surat_keluar_model');
		$this->load->model('config_model');
		$this->modul_ini = 4;
	}

	function index(){
		$header = $this->header_model->get_data();
		$data['menu_surat'] = $this->surat_model->list_surat();
		$data['menu_surat2'] = $this->surat_model->list_surat2();
		$data['surat_favorit'] = $this->surat_model->list_surat_fav();

		// Reset untuk surat yang menggunakan session variable
		unset($_SESSION['id_pria']);
		unset($_SESSION['id_wanita']);
		unset($_SESSION['post']);


		$this->load->view('header', $header);
		$nav['act']= 1;

		$this->load->view('surat/nav',$nav);
		$this->load->view('surat/format_surat',$data);
		$this->load->view('footer');
	}

	function panduan(){
		$header = $this->header_model->get_data();

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
		if(!empty($_POST['nik'])){
			$data['individu']=$this->surat_model->get_penduduk($_POST['nik']);
			$data['anggota']=$this->keluarga_model->list_anggota($data['individu']['id_kk']);
		}else{
			$data['individu']=NULL;
			$data['anggota']=NULL;
		}
		$this->get_data_untuk_form($url,$data);

		$data['surat_terakhir'] = $this->surat_model->get_last_nosurat_log($url);
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
		$log_surat['url_surat']=$url;
		$log_surat['pamong_nama']=$_POST['pamong'];
		$log_surat['id_user']=$_SESSION['user'];
		$log_surat['no_surat']=$_POST['nomor'];

		//$data['menu_surat'] = $this->surat_model->list_surat();
		$id = $_POST['nik'];
		// surat_persetujuan_mempelai id-nya suami atau istri
		if (!$id) $id = $_POST['id_suami'];
		if (!$id) $id = $_POST['id_istri'];
		$log_surat['id_pend']=$id;
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
		$data['anggota']=$this->keluarga_model->list_anggota($data['kk']['id_kk']);
		$this->surat_keluar_model->log_surat($log_surat);

		$data['url']=$url;
		$this->load->view("surat/print_surat",$data);
	}

	function doc($url=''){
		$format = $this->surat_model->get_surat($url);
		$log_surat['url_surat']=$format['id'];
		$log_surat['pamong_nama']=$_POST['pamong'];
		$log_surat['id_user']=$_SESSION['user'];
		$log_surat['no_surat']=$_POST['nomor'];

		$id = $_POST['nik'];
		switch ($url) {
			case 'surat_persetujuan_mempelai':
				// surat_persetujuan_mempelai id-nya suami atau istri
				if (!$id) $id = $_POST['id_suami'];
				if (!$id) $id = $_POST['id_istri'];
				break;
			case 'surat_ket_nikah':
				// id-nya calon pasangan pria atau wanita
				if (!$id) $id = $_POST['id_pria'];
				if (!$id) $id = $_POST['id_wanita'];
				break;
			default:
				# code...
				break;
		}
		if($id){
			$log_surat['id_pend']=$id;
		} else {
			// Surat untuk non-warga
			$log_surat['nama_non_warga'] = $_POST['nama_non_warga'];
			$log_surat['nik_non_warga'] = $_POST['nik_non_warga'];
		}
		$sql = "SELECT nik FROM tweb_penduduk WHERE id=?";
		$query = $this->db->query($sql,$id);
		$hasil  = $query->row_array();
		$nik = $hasil['nik'];

		$nama_surat = $this->surat_keluar_model->nama_surat_arsip($url, $nik, $_POST['nomor']);
		$lampiran = '';
		$this->surat_model->buat_surat($url, $nama_surat, $lampiran);
		$log_surat['nama_surat']=$nama_surat;
		$log_surat['lampiran']=$lampiran;
		$this->surat_keluar_model->log_surat($log_surat);

		// === Untuk debug format surat html2pdf
		// $data = $this->surat_model->get_data_untuk_surat($url);
		// $this->load->view("surat/format_lembaga/f125",$data);

	}

	function search(){
		$cari = $this->input->post('nik');
		if($cari!='')
			redirect("surat/form/$cari");
		else
			redirect('surat');
	}

	private function get_data_untuk_form($url,&$data) {
		$data['lokasi'] = $this->config_model->get_data();
		$data['penduduk'] = $this->surat_model->list_penduduk();
		$data['pamong'] = $this->surat_model->list_pamong();
		$data['perempuan'] = $this->surat_model->list_penduduk_perempuan();
		$data['kode'] = $this->surat_model->get_daftar_kode_surat($url);

		$data_form = $this->surat_model->get_data_form($url);
		if(is_file($data_form))
		  include($data_form);

		switch ($url) {
			case 'surat_persetujuan_mempelai':
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
				break;
			case 'surat_pernyataan_akta':
				$data['laki'] = $this->surat_model->list_penduduk_laki();
				break;
			case 'surat_ket_nikah':
				// Perlu disimpan di SESSION karena belum ketemu cara
				// memanggil flexbox memakai ajax atau menyimpan data
				// TODO: cari pengganti flexbox yang sudah tidak di-support lagi
				$_SESSION['post'] = $_POST;
				if($this->input->post('calon_pria')==2) unset($_SESSION['id_pria']);
				if($_POST['id_pria'] != '' AND $_POST['id_pria'] !='*'){
					$data['pria']=$this->surat_model->get_penduduk($_POST['id_pria']);
					$_SESSION['id_pria'] = $_POST['id_pria'];
				}elseif ($_POST['id_pria'] !='*' AND isset($_SESSION['id_pria'])){
					$data['pria']=$this->surat_model->get_penduduk($_SESSION['id_pria']);
				}else{
					unset($data['pria']);
					unset($_SESSION['id_pria']);
				}
				$data['calon_wanita_berbeda'] = true;
				if($this->input->post('calon_wanita')==2) unset($_SESSION['id_wanita']);
				if($_POST['id_wanita'] != '' AND $_POST['id_wanita'] !='*'){
					if($_POST['id_wanita'] == $_SESSION['id_wanita'])
						$data['calon_wanita_berbeda'] = false;
					$data['wanita']=$this->surat_model->get_penduduk($_POST['id_wanita']);
					$_SESSION['id_wanita'] = $_POST['id_wanita'];
				}elseif ($_POST['id_wanita'] !='*' AND isset($_SESSION['id_wanita'])){
					$data['wanita']=$this->surat_model->get_penduduk($_SESSION['id_wanita']);
				}else{
					unset($data['wanita']);
					unset($_SESSION['id_wanita']);
				}
				if($_POST['id_wanita'] =='*'){
					unset($_SESSION['post']['nama_wali']);
					unset($_SESSION['post']['bin_wali']);
					unset($_SESSION['post']['tempatlahir_wali']);
					unset($_SESSION['post']['tanggallahir_wali']);
					unset($_SESSION['post']['wn_wali']);
					unset($_SESSION['post']['pek_wali']);
					unset($_SESSION['post']['alamat_wali']);
					unset($_SESSION['post']['hub_wali']);
				}
				$status_kawin_pria = array(
					"BELUM KAWIN" => "Jejaka",
					"KAWIN" => "Beristri",
					"CERAI HIDUP" => "Duda",
					"CERAI MATI" => "Duda");
				$status_kawin_wanita = array(
					"BELUM KAWIN" => "Perawan",
					"KAWIN" => "Bersuami",
					"CERAI HIDUP" => "Janda",
					"CERAI MATI" => "Janda");
				$data['warganegara'] = $this->penduduk_model->list_warganegara();
				$data['agama'] = $this->penduduk_model->list_agama();
				$data['pekerjaan'] = $this->penduduk_model->list_pekerjaan();
				$data['laki'] = $this->surat_model->list_penduduk_laki();
				$data['nomor'] = $this->input->post('nomor_main');
				if (isset($_SESSION['id_pria'])) {
					$id = $_SESSION['id_pria'];
					$data['ayah_pria'] = $this->surat_model->get_data_ayah($id);
					$data['ibu_pria'] = $this->surat_model->get_data_ibu($id);
				}
				if (isset($data['pria'])) {
					$data['pria']['status_kawin_pria'] = $status_kawin_pria[$data['pria']['status_kawin']];
				}
				if (isset($_SESSION['id_wanita'])) {
					$id = $_SESSION['id_wanita'];
					$data['ayah_wanita'] = $this->surat_model->get_data_ayah($id);
					$data['ibu_wanita'] = $this->surat_model->get_data_ibu($id);
				}
				if (isset($data['wanita'])) {
					$data['wanita']['status_kawin_wanita'] = $status_kawin_wanita[$data['wanita']['status_kawin']];
				}
				break;
		}
	}

}
