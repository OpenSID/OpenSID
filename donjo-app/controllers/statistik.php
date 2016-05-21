<?php
/*
 * Berkas default dari halaman web utk publik
 * 
 * Copyright 2013 
 * Rizka Himawan <himawan.rizka@gmail.com>
 * Muhammad Khollilurrohman <adsakle1@gmail.com>
 * Asep Nur Ajiyati <asepnurajiyati@gmail.com>
 *
 * SID adalah software tak berbayar (Opensource) yang boleh digunakan oleh siapa saja selama bukan untuk kepentingan profit atau komersial.
 * Lisensi ini mengizinkan setiap orang untuk menggubah, memperbaiki, dan membuat ciptaan turunan bukan untuk kepentingan komersial
 * selama mereka mencantumkan asal pembuat kepada Anda dan melisensikan ciptaan turunan dengan syarat yang serupa dengan ciptaan asli.
 * Untuk mendapatkan SID RESMI, Anda diharuskan mengirimkan surat permohonan ataupun izin SID terlebih dahulu, 
 * aplikasi ini akan tetap bersifat opensource dan anda tidak dikenai biaya.
 * Bagaimana mendapatkan izin SID, ikuti link dibawah ini:
 * http://lumbungkomunitas.net/bergabung/pendaftaran/daftar-online/
 * Creative Commons Attribution-NonCommercial 3.0 Unported License
 * SID Opensource TIDAK BOLEH digunakan dengan tujuan profit atau segala usaha  yang bertujuan untuk mencari keuntungan. 
 * Pelanggaran HaKI (Hak Kekayaan Intelektual) merupakan tindakan  yang menghancurkan dan menghambat karya bangsa.
 */
?>

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Statistik extends CI_Controller{

function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('user_model');
		$this->load->model('laporan_penduduk_model');
		$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
		if($grup!=1 AND $grup!=2 AND $grup!=3) redirect('siteman');
		$this->load->model('header_model');
		$_SESSION['per_page']= 500;
	
	}	

	function index($lap=0,$p=1,$o=0){
	
		$data['p']        = $p;
		$data['o']        = $o;
		
		if(isset($_POST['per_page'])) 
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];
		
		$data['paging']  = $this->laporan_penduduk_model->paging($lap,$p,$o);
		$data['main']    = $this->laporan_penduduk_model->list_data($lap,$o, $data['paging']->offset, $data['paging']->per_page);
		$data['lap']=$lap;
		
		switch($lap){
			case 0: $data['stat'] = "Pendidikan dalam KK"; break;
			case 1: $data['stat'] = "Pekerjaan"; break;
			case 2: $data['stat'] = "Status Perkawinan"; break;
			case 3: $data['stat'] = "Agama"; break;
			case 4: $data['stat'] = "Jenis Kelamin"; break;
			case 5: $data['stat'] = "Warga Negara"; break;
			case 6: $data['stat'] = "Status"; break;
			case 7: $data['stat'] = "Golongan Darah"; break;
			case 9: $data['stat'] = "Cacat"; break;
			case 10: $data['stat'] = "Sakit Menahun"; break;
			case 11: $data['stat'] = "Jamkesmas"; break;
			//case 12: $data['stat'] = "Pendidikan Sedang Ditempuh"; break;
			case 13: $data['stat'] = "Umur (Detail)"; break;
			case 15: $data['stat'] = "Umur"; break;
			case 14: $data['stat'] = "Pendidikan Sedang Ditempuh"; break;
			case 21: $data['stat'] = "Klasifikasi Sosial"; break;
			case 22: $data['stat'] = "Penerima Raskin"; break;
			case 23: $data['stat'] = "Penerima BLT"; break;
			case 24: $data['stat'] = "Penerima BOS"; break;
			case 25: $data['stat'] = "Penerima PKH"; break;
			case 26: $data['stat'] = "Penerima Jampersal"; break;
			case 27: $data['stat'] = "Penerima Bedah Rumah"; break;
			default:$data['stat'] = "Pendidikan";
		}
		
		$nav['act']= 0;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		$this->load->view('statistik/nav',$nav);
		$this->load->view('statistik/penduduk',$data);
		$this->load->view('footer');
	}
		
	function clear(){
		unset($_SESSION['log']);
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		unset($_SESSION['sex']);
		unset($_SESSION['warganegara']);
		unset($_SESSION['cacat']);
		unset($_SESSION['menahun']);
		unset($_SESSION['golongan_darah']);
		unset($_SESSION['dusun']);
		unset($_SESSION['rw']);
		unset($_SESSION['rt']);
		unset($_SESSION['agama']);
		unset($_SESSION['umur_min']);
		unset($_SESSION['umur_max']);
		unset($_SESSION['pekerjaan_id']);
		unset($_SESSION['status']);
		unset($_SESSION['pendidikan_id']);
		unset($_SESSION['status_penduduk']);
		redirect('statistik');
	}
	
   function graph($lap=0){
	
		$data['main']    = $this->laporan_penduduk_model->list_data($lap);
		$data['lap']=$lap;
		
		
		switch($lap){
			case 0: $data['stat'] = "Pendidikan Telah Ditempuh"; break;
			case 1: $data['stat'] = "Pekerjaan"; break;
			case 2: $data['stat'] = "Status Perkawinan"; break;
			case 3: $data['stat'] = "Agama"; break;
			case 4: $data['stat'] = "Jenis Kelamin"; break;
			case 5: $data['stat'] = "Warga Negara"; break;
			case 6: $data['stat'] = "Status"; break;
			case 7: $data['stat'] = "Golongan Darah"; break;
			case 9: $data['stat'] = "Cacat"; break;
			case 10: $data['stat'] = "Sakit Menahun"; break;
			case 11: $data['stat'] = "Jamkesmas"; break;
			case 12: $data['stat'] = "Pendidikan dalam KK"; break;
			case 13: $data['stat'] = "Umur (Detail)"; break;
			case 15: $data['stat'] = "Umur"; break;
			case 14: $data['stat'] = "Pendidikan Sedang Ditempuh"; break;
			case 21: $data['stat'] = "Klasifikasi Sosial"; break;
			case 22: $data['stat'] = "Penerima Raskin"; break;
			case 23: $data['stat'] = "Penerima BLT"; break;
			case 24: $data['stat'] = "Penerima BOS"; break;
			case 25: $data['stat'] = "Penerima PKH"; break;
			case 26: $data['stat'] = "Penerima Jampersal"; break;
			case 27: $data['stat'] = "Penerima Bedah Rumah"; break;
			default:$data['stat'] = "Pendidikan";
		}
		
		$nav['act']= 0;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		$this->load->view('statistik/nav',$nav);
		$this->load->view('statistik/penduduk_graph',$data);
		$this->load->view('footer');
	}
		
   function pie($lap=0){
	
		$data['main']    = $this->laporan_penduduk_model->list_data($lap);
		$data['lap']=$lap;
		
		
		switch($lap){
			case 0: $data['stat'] = "Pendidikan Telah Ditempuh"; break;
			case 1: $data['stat'] = "Pekerjaan"; break;
			case 2: $data['stat'] = "Status Perkawinan"; break;
			case 3: $data['stat'] = "Agama"; break;
			case 4: $data['stat'] = "Jenis Kelamin"; break;
			case 5: $data['stat'] = "Warga Negara"; break;
			case 6: $data['stat'] = "Status"; break;
			case 7: $data['stat'] = "Golongan Darah"; break;
			case 9: $data['stat'] = "Cacat"; break;
			case 10: $data['stat'] = "Sakit Menahun"; break;
			case 11: $data['stat'] = "Jamkesmas"; break;
			case 12: $data['stat'] = "Pendidikan dalam KK"; break;
			case 13: $data['stat'] = "Umur (Detail)"; break;
			case 15: $data['stat'] = "Umur"; break;
			case 14: $data['stat'] = "Pendidikan Sedang Ditempuh"; break;
			case 21: $data['stat'] = "Klasifikasi Sosial"; break;
			case 22: $data['stat'] = "Penerima Raskin"; break;
			case 23: $data['stat'] = "Penerima BLT"; break;
			case 24: $data['stat'] = "Penerima BOS"; break;
			case 25: $data['stat'] = "Penerima PKH"; break;
			case 26: $data['stat'] = "Penerima Jampersal"; break;
			case 27: $data['stat'] = "Penerima Bedah Rumah"; break;
			default:$data['stat'] = "Pendidikan";
		}
		
		$nav['act']= 0;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);
		$this->load->view('statistik/nav',$nav);
		$this->load->view('statistik/penduduk_pie',$data);
		$this->load->view('footer');
	}
	      
    function cetak($lap=0){
		$data['lap']=$lap;
		switch($lap){
			case 0: $data['stat'] = "Pendidikan Telah Ditempuh"; break;
			case 1: $data['stat'] = "Pekerjaan"; break;
			case 2: $data['stat'] = "Status Perkawinan"; break;
			case 3: $data['stat'] = "Agama"; break;
			case 4: $data['stat'] = "Jenis Kelamin"; break;
			case 5: $data['stat'] = "Warga Negara"; break;
			case 6: $data['stat'] = "Status"; break;
			case 7: $data['stat'] = "Golongan Darah"; break;
			case 9: $data['stat'] = "Cacat"; break;
			case 10: $data['stat'] = "Sakit Menahun"; break;
			case 11: $data['stat'] = "Jamkesmas"; break;
			case 12: $data['stat'] = "Pendidikan dalam KK"; break;
			case 13: $data['stat'] = "Umur"; break;
			case 14: $data['stat'] = "Pendidikan Sedang Ditempuh"; break;
			case 21: $data['stat'] = "Klasifikasi Sosial"; break;
			case 22: $data['stat'] = "Penerima Raskin"; break;
			case 23: $data['stat'] = "Penerima BLT"; break;
			case 24: $data['stat'] = "Penerima BOS"; break;
			case 25: $data['stat'] = "Penerima PKH"; break;
			case 26: $data['stat'] = "Penerima Jampersal"; break;
			case 27: $data['stat'] = "Penerima Bedah Rumah"; break;
			default:$data['stat'] = "Pendidikan";
		}
		
		$data['config']  = $this->laporan_penduduk_model->get_config();
		$data['main']    = $this->laporan_penduduk_model->list_data($lap);
		$this->load->view('statistik/penduduk_print',$data);
	}

	function excel($lap=0){
		$data['lap']=$lap;
		switch($lap){
			case 0: $data['stat'] = "Pendidikan Telah Ditempuh"; break;
			case 1: $data['stat'] = "Pekerjaan"; break;
			case 2: $data['stat'] = "Status Perkawinan"; break;
			case 3: $data['stat'] = "Agama"; break;
			case 4: $data['stat'] = "Jenis Kelamin"; break;
			case 5: $data['stat'] = "Warga Negara"; break;
			case 6: $data['stat'] = "Status"; break;
			case 7: $data['stat'] = "Golongan Darah"; break;
			case 9: $data['stat'] = "Cacat"; break;
			case 10: $data['stat'] = "Sakit Menahun"; break;
			case 11: $data['stat'] = "Jamkesmas"; break;
			case 12: $data['stat'] = "Pendidikan dalam KK"; break;
			case 13: $data['stat'] = "Umur"; break;
			case 14: $data['stat'] = "Pendidikan Sedang Ditempuh"; break;
			case 21: $data['stat'] = "Klasifikasi Sosial"; break;
			case 22: $data['stat'] = "Penerima Raskin"; break;
			case 23: $data['stat'] = "Penerima BLT"; break;
			case 24: $data['stat'] = "Penerima BOS"; break;
			case 25: $data['stat'] = "Penerima PKH"; break;
			case 26: $data['stat'] = "Penerima Jampersal"; break;
			case 27: $data['stat'] = "Penerima Bedah Rumah"; break;
			default:$data['stat'] = "Pendidikan";
		}
		
		$data['config']  = $this->laporan_penduduk_model->get_config();
		$data['main']    = $this->laporan_penduduk_model->list_data($lap);
		$this->load->view('statistik/penduduk_excel',$data);
	}
	
	function warga($lap='',$data=''){
		$data['lap']=$lap;
		switch($lap){
			case 0: $data['stat'] = "Pendidikan Telah Ditempuh"; break;
			case 1: $data['stat'] = "Pekerjaan"; break;
			case 2: $data['stat'] = "Status Perkawinan"; break;
			case 3: $data['stat'] = "Agama"; break;
			case 4: $data['stat'] = "Jenis Kelamin"; break;
			case 5: $data['stat'] = "Warga Negara"; break;
			case 6: $data['stat'] = "Status"; break;
			case 7: $data['stat'] = "Golongan Darah"; break;
			case 9: $data['stat'] = "Cacat"; break;
			case 10: $data['stat'] = "Sakit Menahun"; break;
			case 11: $data['stat'] = "Jamkesmas"; break;
			case 12: $data['stat'] = "Pendidikan dalam KK"; break;
			case 13: $data['stat'] = "Umur"; break;
			case 14: $data['stat'] = "Pendidikan Sedang Ditempuh"; break;
			case 21: $data['stat'] = "Klasifikasi Sosial"; break;
			case 22: $data['stat'] = "Penerima Raskin"; break;
			case 23: $data['stat'] = "Penerima BLT"; break;
			case 24: $data['stat'] = "Penerima BOS"; break;
			case 25: $data['stat'] = "Penerima PKH"; break;
			case 26: $data['stat'] = "Penerima Jampersal"; break;
			case 27: $data['stat'] = "Penerima Bedah Rumah"; break;
			default:$data['stat'] = "Pendidikan";
		}
		
		$data['config']  = $this->laporan_penduduk_model->get_config();
		$data['main']    = $this->laporan_penduduk_model->list_data($lap);
		
		$_SESSION['per_page'] = 100;
		$_SESSION['data'] = $data;
		redirect("sid_penduduk/index/");
	}
	
	function rentang_umur(){
		$data['lap']=13;
		$data['main']    = $this->laporan_penduduk_model->list_data_rentang();

		$header = $this->header_model->get_data();
		$menu['act']='2';
		
		$this->load->view('header', $header);
		//$this->load->view('statistik/menu');
		$this->load->view('statistik/nav',$menu);
		$this->load->view('statistik/rentang_umur',$data);
		$this->load->view('footer');
	}
	
	function form_rentang($id=0){

		if($id==0){
			$data['form_action'] = site_url("statistik/rentang_insert");
			$data['rentang']= $this->laporan_penduduk_model->get_rentang_terakhir();
			$data['rentang']['nama']="";
			$data['rentang']['sampai']="";
		}
		else{
			$data['form_action'] = site_url("statistik/rentang_update/$id");
			$data['rentang']     = $this->laporan_penduduk_model->get_rentang($id);			
		}
		$this->load->view('statistik/ajax_rentang_form',$data);
		
	}
	
	function rentang_insert(){
		$data['insert'] = $this->laporan_penduduk_model->insert_rentang();
		redirect('statistik/rentang_umur');
	}
	
	function rentang_update($id=0){
		$this->laporan_penduduk_model->update_rentang($id);
		redirect('statistik/rentang_umur');
	}
	
	function rentang_delete($id=0){
		$this->laporan_penduduk_model->delete_rentang($id);
		redirect('statistik/rentang_umur');
	}	
	
	function delete_all_rentang(){
		$this->laporan_penduduk_model->delete_all_rentang();
		redirect('statistik/rentang_umur');
	}		
	
}
