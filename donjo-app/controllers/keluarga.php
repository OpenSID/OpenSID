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

class Keluarga extends CI_Controller{

function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('user_model');
		$this->load->model('keluarga_model');
		$this->load->model('penduduk_model');
		$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
		if($grup!=1 AND $grup!=2) redirect('siteman');
		$this->load->model('header_model');
	}
	
	function clear(){
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		unset($_SESSION['dusun']);
		unset($_SESSION['rw']);
		unset($_SESSION['rt']);
		unset($_SESSION['raskin']);
		unset($_SESSION['id_blt']);
		unset($_SESSION['id_bos']);
		unset($_SESSION['id_pkh']);
		unset($_SESSION['id_jampersal']);
		unset($_SESSION['id_bedah_rumah']);
		$_SESSION['per_page']=100;
		redirect('keluarga');
	}
	
	function index($p=1,$o=0){
	
		$data['p']        = $p;
		$data['o']        = $o;
		
		if(isset($_SESSION['cari']))
			$data['cari'] = $_SESSION['cari'];
		else $data['cari'] = '';
		
		if(isset($_SESSION['filter']))
			$data['filter'] = $_SESSION['filter'];
		else $data['filter'] = '';
	
		if(isset($_SESSION['raskin']))
			$data['raskin'] = $_SESSION['raskin'];
		else $data['raskin'] = '';

		if(isset($_SESSION['id_blt']))
			$data['id_blt'] = $_SESSION['id_blt'];
		else $data['id_blt'] = '';
		
		if(isset($_SESSION['id_bos']))
			$data['id_bos'] = $_SESSION['id_bos'];
		else $data['id_bos'] = '';
		
		if(isset($_SESSION['id_pkh']))
			$data['id_pkh'] = $_SESSION['id_pkh'];
		else $data['id_pkh'] = '';
		
		if(isset($_SESSION['id_jampersal']))
			$data['id_jampersal'] = $_SESSION['id_jampersal'];
		else $data['id_jampersal'] = '';

		if(isset($_SESSION['id_bedah_rumah']))
			$data['id_bedah_rumah'] = $_SESSION['id_bedah_rumah'];
		else $data['id_bedah_rumah'] = '';

		if(isset($_POST['per_page'])) 
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];
		
		if(isset($_SESSION['dusun'])){
			$data['dusun'] = $_SESSION['dusun'];
			$data['list_rw'] = $this->penduduk_model->list_rw($data['dusun']);
			
		if(isset($_SESSION['rw'])){
			$data['rw'] = $_SESSION['rw'];
			$data['list_rt'] = $this->penduduk_model->list_rt($data['dusun'],$data['rw']);
						
		if(isset($_SESSION['rt']))
			$data['rt'] = $_SESSION['rt'];
			else $data['rt'] = '';
				
			}else $data['rw'] = '';
			
		}else{
			$data['dusun'] = '';
			$data['rw'] = '';
			$data['rt'] = '';
		}
		$data['grup']	= $this->user_model->sesi_grup($_SESSION['sesi']);
		$data['paging']  = $this->keluarga_model->paging($p,$o);
		$data['main']    = $this->keluarga_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->keluarga_model->autocomplete();
		$data['list_dusun'] = $this->penduduk_model->list_dusun();

		$nav['act']= 1;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);

		$this->load->view('sid/nav',$nav);
		$this->load->view('sid/kependudukan/keluarga',$data);
		$this->load->view('footer');
	}
	
	function sosial($p=1,$o=0){
	
		$data['p']        = $p;
		$data['o']        = $o;
		
		if(isset($_SESSION['cari']))
			$data['cari'] = $_SESSION['cari'];
		else $data['cari'] = '';
		
		if(isset($_SESSION['filter']))
			$data['filter'] = $_SESSION['filter'];
		else $data['filter'] = '';
	
		if(isset($_SESSION['raskin']))
			$data['raskin'] = $_SESSION['raskin'];
		else $data['raskin'] = '';
		
		if(isset($_SESSION['id_blt']))
			$data['id_blt'] = $_SESSION['id_blt'];
		else $data['id_blt'] = '';
	
		if(isset($_SESSION['id_pkh']))
			$data['id_pkh'] = $_SESSION['id_pkh'];
		else $data['id_pkh'] = '';		

		if(isset($_SESSION['id_bos']))
			$data['id_bos'] = $_SESSION['id_bos'];
		else $data['id_bos'] = '';
		
		if(isset($_SESSION['id_jampersal']))
			$data['id_jampersal'] = $_SESSION['id_jampersal'];
		else $data['id_jampersal'] = '';

		if(isset($_SESSION['id_bedah_rumah']))
			$data['id_bedah_rumah'] = $_SESSION['id_bedah_rumah'];
		else $data['id_bedah_rumah'] = '';

		if(isset($_POST['per_page'])) 
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];
		
		if(isset($_SESSION['dusun'])){
			$data['dusun'] = $_SESSION['dusun'];
			$data['list_rw'] = $this->penduduk_model->list_rw($data['dusun']);
			
		if(isset($_SESSION['rw'])){
			$data['rw'] = $_SESSION['rw'];
			$data['list_rt'] = $this->penduduk_model->list_rt($data['dusun'],$data['rw']);
						
		if(isset($_SESSION['rt']))
			$data['rt'] = $_SESSION['rt'];
			else $data['rt'] = '';
				
			}else $data['rw'] = '';
			
		}else{
			$data['dusun'] = '';
			$data['rw'] = '';
			$data['rt'] = '';
		}
		
		$data['paging']  = $this->keluarga_model->paging($p,$o);
		$data['main']    = $this->keluarga_model->list_raskin();
		$data['keyword'] = $this->keluarga_model->autocomplete();
		$data['list_dusun'] = $this->penduduk_model->list_dusun();

		$nav['act']= 1;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);

		$this->load->view('sid/nav',$nav);
		$this->load->view('sid/kependudukan/keluarga_sosial',$data);
		$this->load->view('footer');
	}	
	
	function raskin_graph($p=1,$o=0){
	
		$data['p']        = $p;
		$data['o']        = $o;
		
		if(isset($_SESSION['cari']))
			$data['cari'] = $_SESSION['cari'];
		else $data['cari'] = '';
		
		if(isset($_SESSION['filter']))
			$data['filter'] = $_SESSION['filter'];
		else $data['filter'] = '';
	
		if(isset($_SESSION['raskin']))
			$data['raskin'] = $_SESSION['raskin'];
		else $data['raskin'] = '';

		if(isset($_SESSION['id_blt']))
			$data['id_blt'] = $_SESSION['id_blt'];
		else $data['id_blt'] = '';

		if(isset($_SESSION['id_bos']))
			$data['id_bos'] = $_SESSION['id_bos'];
		else $data['id_bos'] = '';

		if(isset($_SESSION['id_pkh']))
			$data['id_pkh'] = $_SESSION['id_pkh'];
		else $data['id_pkh'] = '';

		if(isset($_SESSION['id_jampersal']))
			$data['id_jampersal'] = $_SESSION['id_jampersal'];
		else $data['id_jampersal'] = '';

		if(isset($_SESSION['id_bedah_rumah']))
			$data['id_bedah_rumah'] = $_SESSION['id_bedah_rumah'];
		else $data['id_bedah_rumah'] = '';
	
		if(isset($_POST['per_page'])) 
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];
		
		if(isset($_SESSION['dusun'])){
			$data['dusun'] = $_SESSION['dusun'];
			$data['list_rw'] = $this->penduduk_model->list_rw($data['dusun']);
			
		if(isset($_SESSION['rw'])){
			$data['rw'] = $_SESSION['rw'];
			$data['list_rt'] = $this->penduduk_model->list_rt($data['dusun'],$data['rw']);
						
		if(isset($_SESSION['rt']))
			$data['rt'] = $_SESSION['rt'];
			else $data['rt'] = '';
				
			}else $data['rw'] = '';
			
		}else{
			$data['dusun'] = '';
			$data['rw'] = '';
			$data['rt'] = '';
		}
		
		$data['paging']  = $this->keluarga_model->paging($p,$o);
		$data['main']    = $this->keluarga_model->list_raskin();
		$data['keyword'] = $this->keluarga_model->autocomplete();
		$data['list_dusun'] = $this->penduduk_model->list_dusun();

		$nav['act']= 1;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);

		$this->load->view('sid/nav',$nav);
		$this->load->view('sid/kependudukan/keluarga_raskin',$data);
		$this->load->view('footer');
	}	
		
	function jamkesmas_graph($p=1,$o=0){
	
		$data['p']        = $p;
		$data['o']        = $o;
		
		if(isset($_SESSION['cari']))
			$data['cari'] = $_SESSION['cari'];
		else $data['cari'] = '';
		
		if(isset($_SESSION['filter']))
			$data['filter'] = $_SESSION['filter'];
		else $data['filter'] = '';
	
		if(isset($_SESSION['raskin']))
			$data['raskin'] = $_SESSION['raskin'];
		else $data['raskin'] = '';

		if(isset($_SESSION['id_blt']))
			$data['id_blt'] = $_SESSION['id_blt'];
		else $data['id_blt'] = '';

		if(isset($_SESSION['id_bos']))
			$data['id_bos'] = $_SESSION['id_bos'];
		else $data['id_bos'] = '';

		if(isset($_SESSION['id_pkh']))
			$data['id_pkh'] = $_SESSION['id_pkh'];
		else $data['id_pkh'] = '';

		if(isset($_SESSION['id_jampersal']))
			$data['id_jampersal'] = $_SESSION['id_jampersal'];
		else $data['id_jampersal'] = '';

		if(isset($_SESSION['id_bedah_rumah']))
			$data['id_bedah_rumah'] = $_SESSION['id_bedah_rumah'];
		else $data['id_bedah_rumah'] = '';
	
		if(isset($_POST['per_page'])) 
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];
		
		if(isset($_SESSION['dusun'])){
			$data['dusun'] = $_SESSION['dusun'];
			$data['list_rw'] = $this->penduduk_model->list_rw($data['dusun']);
			
		if(isset($_SESSION['rw'])){
			$data['rw'] = $_SESSION['rw'];
			$data['list_rt'] = $this->penduduk_model->list_rt($data['dusun'],$data['rw']);
						
		if(isset($_SESSION['rt']))
			$data['rt'] = $_SESSION['rt'];
			else $data['rt'] = '';
				
			}else $data['rw'] = '';
			
		}else{
			$data['dusun'] = '';
			$data['rw'] = '';
			$data['rt'] = '';
		}
		
		$data['paging']  = $this->keluarga_model->paging($p,$o);
		$data['main']    = $this->keluarga_model->list_raskin();
		$data['keyword'] = $this->keluarga_model->autocomplete();
		$data['list_dusun'] = $this->penduduk_model->list_dusun();

		$nav['act']= 1;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);

		$this->load->view('sid/nav',$nav);
		$this->load->view('sid/kependudukan/keluarga_jamkesmas',$data);
		$this->load->view('footer');
	}	
	
	function pentagon(){
		$data['main']    = $this->keluarga_model->list_raskin();
		$this->load->view('sid/kependudukan/pentagon/pentagon',$data);
	}	
	
	function cetak($o=0){
		$data['main']    = $this->keluarga_model->list_data($o, 0, 10000);
		$this->load->view('sid/kependudukan/keluarga_print',$data);
	}
	
	function excel($o=0){
		$data['main']    = $this->keluarga_model->list_data($o, 0, 10000);
		$this->load->view('sid/kependudukan/keluarga_excel',$data);
	}
		
	function form($p=1,$o=0,$id=0,$new=1){
		
		if($new==1){
			
			if(isset($_POST['dusun'])){
				$data['dus_sel'] = $_POST['dusun'];
			}else
				$data['dus_sel'] = '';
				
			if(isset($_POST['rw'])){
				$data['rw_sel'] = $_POST['rw'];
				
			}else
				$data['rw_sel'] = '';
				
			if(isset($_POST['rt']))
				$data['rt_sel'] = $_POST['rt'];
			else
				$data['rt_sel'] = '';
				
				$data['new'] = $new;
		}else{
			$data['new'] = 0;
			$data['dus_sel'] = '';
			$data['rw_sel'] = '';
			$data['rt_sel'] = '';
		}
		
		if($id > 0){
			$data['kk']          = $this->keluarga_model->get_keluarga($id);
			$data['form_action'] = site_url("keluarga/update/$id");
		}elseif($new>0){
			$data['kk']          = null;
			$data['form_action'] = site_url("keluarga/insert_new");
		
		}else{
			$data['kk']          = null;
			$data['form_action'] = site_url("keluarga/insert");
		}
		
		$data['penduduk'] = $this->keluarga_model->list_penduduk_lepas();
		
		$nav['act']= 1;
		
		$data['dusun'] = $this->penduduk_model->list_dusun();
		$data['rw']    = $this->penduduk_model->list_rw($data['dus_sel']);
		$data['rt']    = $this->penduduk_model->list_rt($data['dus_sel'],$data['rw_sel']);
		$data['agama'] = $this->penduduk_model->list_agama();
		$data['pendidikan_sedang'] = $this->penduduk_model->list_pendidikan_sedang();
		$data['pendidikan_kk'] = $this->penduduk_model->list_pendidikan_kk();
		$data['pekerjaan'] = $this->penduduk_model->list_pekerjaan();
		$data['warganegara'] = $this->penduduk_model->list_warganegara();
		$data['hubungan'] = $this->penduduk_model->list_hubungan();
		$data['kawin'] = $this->penduduk_model->list_status_kawin();
		$data['golongan_darah'] = $this->penduduk_model->list_golongan_darah();
		$data['cacat'] = $this->penduduk_model->list_cacat();
		
		
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);

		$this->load->view('sid/nav',$nav);
		$this->load->view('sid/kependudukan/keluarga_form',$data);
		$this->load->view('footer');
	}
		
	function form_a($p=1,$o=0, $id=0){
		
		$data['id_kk']  	 = $id;
		$data['kk']          = $this->keluarga_model->get_kepala_a($id);
		$data['form_action'] = site_url("keluarga/insert_a");
		
		$nav['act']= 2;
		
		$data['agama'] = $this->penduduk_model->list_agama();
		$data['pendidikan_kk'] = $this->penduduk_model->list_pendidikan_kk();
		$data['pendidikan'] = $this->penduduk_model->list_pendidikan();
		$data['pekerjaan'] = $this->penduduk_model->list_pekerjaan();
		$data['warganegara'] = $this->penduduk_model->list_warganegara();
		$data['hubungan'] = $this->penduduk_model->list_hubungan();
		$data['kawin'] = $this->penduduk_model->list_status_kawin();
		$data['golongan_darah'] = $this->penduduk_model->list_golongan_darah();
		$data['cacat'] = $this->penduduk_model->list_cacat();
		
		
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);

		$this->load->view('sid/nav',$nav);
		$this->load->view('sid/kependudukan/keluarga_form_a',$data);
		$this->load->view('footer');
	}
	
	function edit_nokk($p=1,$o=0,$id=0){
	
		$data['kk']          = $this->keluarga_model->get_keluarga($id);
		$data['form_action'] = site_url("keluarga/update_nokk/$id");
		$this->load->view('sid/kependudukan/ajax_edit_nokk',$data);
		
	}
	
	function form_old($p=1,$o=0,$id=0){
	
		$data['penduduk'] = $this->keluarga_model->list_penduduk_lepas();
		$data['form_action'] = site_url("keluarga/insert/$id");
		$this->load->view('sid/kependudukan/ajax_add_keluarga',$data);
		
	}
	
	function dusun($s=0){
		$dusun = $this->input->post('dusun');
		if($dusun!="")
			$_SESSION['dusun']=$dusun;
		else unset($_SESSION['dusun']);
		if($s==1)
			redirect('keluarga/sosial');
		elseif($s==2)
			redirect('keluarga/raskin_graph');
		else
			redirect('keluarga');
	}
		
	function rw($s=0){
		$rw = $this->input->post('rw');
		if($rw!="")
			$_SESSION['rw']=$rw;
		else unset($_SESSION['rw']);
		if($s==1)
			redirect('keluarga/sosial');
		elseif($s==2)
			redirect('keluarga/raskin_graph');
		else
			redirect('keluarga');
	}
	
	function rt($s=0){
		$rt = $this->input->post('rt');
		if($rt!="")
			$_SESSION['rt']=$rt;
		else unset($_SESSION['rt']);
		if($s==1)
			redirect('keluarga/sosial');
		elseif($s==2)
			redirect('keluarga/raskin_graph');
		else
			redirect('keluarga');
	}
	
	function raskin(){
		$raskin = $this->input->post('raskin');
		if($raskin!="")
			$_SESSION['raskin']=$raskin;
		else unset($_SESSION['raskin']);
		redirect('keluarga');
	}

	function blt(){
		$id_blt = $this->input->post('id_blt');
		if($id_blt!="")
			$_SESSION['id_blt']=$id_blt;
		else unset($_SESSION['id_blt']);
		redirect('keluarga');
	}

	function bos(){
		$id_bos = $this->input->post('id_bos');
		if($id_bos!="")
			$_SESSION['id_bos']=$id_bos;
		else unset($_SESSION['id_bos']);
		redirect('keluarga');
	}
	
	function search(){
		$cari = $this->input->post('cari');
		if($cari!='')
			$_SESSION['cari']=$cari;
		else unset($_SESSION['cari']);
		redirect('keluarga');
	}
	
	function insert(){
		$this->keluarga_model->insert();
		redirect('keluarga');
	}
	
	function insert_a(){
		$this->keluarga_model->insert_a();
		redirect('keluarga');
	}
	
	function insert_new(){
		$this->keluarga_model->insert_new();
		redirect('keluarga');
	}
	
	function update($id=''){
		$this->keluarga_model->update($id);
		redirect('keluarga');
	}
	
	function update_nokk($id=''){
		$this->keluarga_model->update_nokk($id);
		redirect('keluarga');
	}
	
	function delete($p=1,$o=0,$id=''){
		$this->keluarga_model->delete($id);
		redirect('keluarga');
	}
	
	function delete_all($p=1,$o=0){
		$this->keluarga_model->delete_all();
		redirect('keluarga');
	}	
	
	function anggota($p=1,$o=0,$id=0){
	
		$data['p']        = $p;
		$data['o']        = $o;
		$data['kk']       = $id;
		
		$data['main']     = $this->keluarga_model->list_anggota($id);
		$data['kepala_kk']= $this->keluarga_model->get_kepala_kk($id);

		$nav['act']= 1;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);

		$this->load->view('sid/nav',$nav);
		$this->load->view('sid/kependudukan/keluarga_anggota',$data);
		$this->load->view('footer');
	}
	    
	function ajax_add_anggota($p=1,$o=0,$id=0){
	
		$data['p']        = $p;
		$data['o']        = $o;

		$data['hubungan'] = $this->keluarga_model->list_hubungan();
		$data['main']     = $this->keluarga_model->list_anggota($id);
		$kk 			  = $this->keluarga_model->get_kepala_kk($id);
		if($kk)
			$data['kepala_kk'] = $kk;
		else
			$data['kepala_kk'] = NULL;
		$data['penduduk'] = $this->keluarga_model->list_penduduk_lepas();
		
		$data['form_action'] = site_url("keluarga/add_anggota/$p/$o/$id");
		
		$this->load->view("sid/kependudukan/ajax_add_anggota_form", $data);
	}
		    
	function edit_anggota($p=1,$o=0,$id_kk=0,$id=0){
	
		$data['p']        = $p;
		$data['o']        = $o;

		$data['hubungan'] = $this->keluarga_model->list_hubungan();
		$data['main']     = $this->keluarga_model->get_anggota($id);
		
		$kk 			  = $this->keluarga_model->get_kepala_kk($id);
		if($kk)
			$data['kepala_kk'] = $kk;
		else
			$data['kepala_kk'] = NULL;
			
		$data['form_action'] = site_url("keluarga/update_anggota/$p/$o/$id_kk/$id");
		
		$this->load->view("sid/kependudukan/ajax_edit_anggota_form", $data);
	}
	
	function kartu_keluarga($p=1,$o=0,$id=0){
	
		$data['p']        = $p;
		$data['o']        = $o;
		$data['id_kk']    = $id;

		$data['hubungan'] = $this->keluarga_model->list_hubungan();
		$data['main']     = $this->keluarga_model->list_anggota($id);
		$kk 		  = $this->keluarga_model->get_kepala_kk($id);
		$data['desa']     = $this->keluarga_model->get_desa();
		
		if($kk)
			$data['kepala_kk'] = $kk;
			
		else
			$data['kepala_kk'] = NULL;
			
		$data['penduduk'] = $this->keluarga_model->list_penduduk_lepas();
		$nav['act']= 1;
	
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);

		$this->load->view('sid/nav',$nav);
		$data['form_action'] = site_url("keluarga/print");
		
		$this->load->view("sid/kependudukan/kartu_keluarga", $data);
		$this->load->view('footer');
		
	}
		
	function cetak_kk($id=0){
	
		$data['id_kk']    = $id;

		$data['main']     = $this->keluarga_model->list_anggota($id);
		$kk 		  	  = $this->keluarga_model->get_kepala_kk($id);
		$data['desa']     = $this->keluarga_model->get_desa();
		$data['kepala_kk'] = $kk;
		$nav['act']= 1;
		$header = $this->header_model->get_data();
		$this->load->view("sid/kependudukan/cetak_kk", $data);
		
	}
		
	function add_anggota($p=1,$o=0,$id=0){
		$this->keluarga_model->add_anggota($id);
		redirect("keluarga/index/$p/$o");
	}
	
	function update_anggota($p=1,$o=0,$id_kk=0,$id=0){
		$this->keluarga_model->update_anggota($id);
		redirect("keluarga/anggota/$p/$o/$id_kk");
	}
	
	function delete_anggota($p=1,$o=0,$kk=0,$id=''){
		$this->keluarga_model->rem_anggota($kk,$id);
		redirect("keluarga/anggota/$p/$o/$kk");
	}
	
	function delete_all_anggota($p=1,$o=0,$kk=0){
		$this->keluarga_model->rem_all_anggota($kk);
		redirect("keluarga/anggota/$p/$o/$kk");
	}	
	
	
	function pindah_proses($id=0){
		$id_cluster = $_POST['id_cluster'];
		$this->keluarga_model->pindah_proses($id,$id_cluster);
		redirect("keluarga");
	}
	
	function ajax_penduduk_pindah($id=0){
	
		$data['dusun'] = $this->penduduk_model->list_dusun();
		
		$data['form_action'] = site_url("keluarga/pindah_proses/$id");
		$this->load->view('sid/kependudukan/ajax_pindah_form',$data);
	}
	
	function ajax_penduduk_pindah_rw($dusun=''){
		$rw = $this->penduduk_model->list_rw($dusun);
		//$this->load->view("sid/kependudukan/ajax_penduduk_pindah_form_rw", $data);
		echo"<td>RW</td>
		<td><select name='rw' onchange=RWSel('".$dusun."',this.value)>
		<option value=''>Pilih RW&nbsp;</option>";
		foreach($rw as $data){
			echo "<option>".$data['rw']."</option>";
		}echo"</select>
		</td>";
	}
	
	function ajax_penduduk_pindah_rt($dusun='',$rw=''){
		$rt = $this->penduduk_model->list_rt($dusun,$rw);
		//$this->load->view("sid/kependudukan/ajax_penduduk_pindah_form_rt", $data);
		echo "<td>RT</td>
		<td><select name='rt'>
		<option value=''>Pilih RT&nbsp;</option>";
		foreach($rt as $data){
			echo "<option value=".$data['rt'].">".$data['rt']."</option>";
		}echo"</select>
		</td>";
	}
	
	function statistik($tipe=0,$nomor=0,$p=1,$o=0){
		$data['p']        = $p;
		$data['o']        = $o;
		$data['tipe']        = $tipe;
		if(isset($_SESSION['cari']))
			$data['cari'] = $_SESSION['cari'];
		else $data['cari'] = '';
		
		if(isset($_SESSION['filter']))
			$data['filter'] = $_SESSION['filter'];
		else $data['filter'] = '';
	
		if(isset($_SESSION['raskin']))
			$data['raskin'] = $_SESSION['raskin'];
		else $data['raskin'] = '';

		if(isset($_SESSION['id_blt']))
			$data['id_blt'] = $_SESSION['id_blt'];
		else $data['id_blt'] = '';
		
		if(isset($_SESSION['id_bos']))
			$data['id_bos'] = $_SESSION['id_bos'];
		else $data['id_bos'] = '';
		
		if(isset($_SESSION['id_pkh']))
			$data['id_pkh'] = $_SESSION['id_pkh'];
		else $data['id_pkh'] = '';
		
		if(isset($_SESSION['id_jampersal']))
			$data['id_jampersal'] = $_SESSION['id_jampersal'];
		else $data['id_jampersal'] = '';

		if(isset($_SESSION['id_bedah_rumah']))
			$data['id_bedah_rumah'] = $_SESSION['id_bedah_rumah'];
		else $data['id_bedah_rumah'] = '';

		if(isset($_POST['per_page'])) 
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];
		
		if(isset($_SESSION['dusun'])){
			$data['dusun'] = $_SESSION['dusun'];
			$data['list_rw'] = $this->penduduk_model->list_rw($data['dusun']);
			
		if(isset($_SESSION['rw'])){
			$data['rw'] = $_SESSION['rw'];
			$data['list_rt'] = $this->penduduk_model->list_rt($data['dusun'],$data['rw']);
						
		if(isset($_SESSION['rt']))
			$data['rt'] = $_SESSION['rt'];
			else $data['rt'] = '';
				
			}else $data['rw'] = '';
			
		}else{
			$data['dusun'] = '';
			$data['rw'] = '';
			$data['rt'] = '';
		}
		switch($tipe){
			//case 0: $_SESSION['raskin'] = 0;$_SESSION['kelas'] = 0; break;
			case 21: $_SESSION['kelas']  = $nomor; $pre="KELAS SOSIAL : ";break;
			case 22: $_SESSION['raskin'] = $nomor; $pre="RASKIN : ";break;	
			case 23: $_SESSION['id_blt'] = $nomor; $pre="BLT : ";break;
			case 24: $_SESSION['id_bos'] = $nomor; $pre="BOS : ";break;
			case 25: $_SESSION['id_pkh'] = $nomor; $pre="PKH : ";break;
			case 26: $_SESSION['id_jampersal'] = $nomor; $pre="JAMPERSAL : ";break;
			case 27: $_SESSION['id_bedah_rumah'] = $nomor;$pre="BEDAH RUMAH : "; break;		
		}
		$data['grup']	= $this->user_model->sesi_grup($_SESSION['sesi']);
		$data['paging']  = $this->keluarga_model->paging_statistik($p,$o);
		$data['main']    = $this->keluarga_model->list_data_statistik($tipe,$o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->keluarga_model->autocomplete();
		$data['list_dusun'] = $this->penduduk_model->list_dusun();

		$judul= $this->keluarga_model->get_judul_statistik($tipe,$nomor);
		if($judul['nama']){
			$_SESSION['judul_statistik']=$pre.$judul['nama'];
		}else{
			unset($_SESSION['judul_statistik']);
		}

		$nav['act']= 1;
		$header = $this->header_model->get_data();
		$this->load->view('header',$header);

		$this->load->view('sid/nav',$nav);
		$this->load->view('sid/kependudukan/keluarga_statistik',$data);
		$this->load->view('footer');

		//redirect('keluarga');
	}
	
	function cetak_statistik($tipe=0){
		$data['main']    = $this->keluarga_model->list_data_statistik($tipe);
		$this->load->view('sid/kependudukan/keluarga_print',$data);
	}
}
