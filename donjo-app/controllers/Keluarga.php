<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Keluarga extends CI_Controller{

function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('user_model');
		$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
		if($grup!=1 AND $grup!=2) {
			if(empty($grup))
				$_SESSION['request_uri'] = $_SERVER['REQUEST_URI'];
			else
				unset($_SESSION['request_uri']);
			redirect('siteman');
		}
		$this->load->model('header_model');
		$this->load->model('keluarga_model');
		$this->load->model('penduduk_model');
		$this->load->model('program_bantuan_model');
		$this->load->model('referensi_model');
		$this->modul_ini = 2;
	}

	function clear(){
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		$_SESSION['status_dasar'] = 1; // tampilkan KK aktif saja
		unset($_SESSION['dusun']);
		unset($_SESSION['rw']);
		unset($_SESSION['rt']);
		unset($_SESSION['sex']);
		unset($_SESSION['kelas']);
		unset($_SESSION['id_bos']);
		$_SESSION['per_page']=100;
		redirect('keluarga');
	}

	function index($p=1,$o=0){

		$data['p']        = $p;
		$data['o']        = $o;

		if(isset($_SESSION['cari']))
			$data['cari'] = $_SESSION['cari'];
		else $data['cari'] = '';

		if(isset($_SESSION['judul_statistik']))
			$data['judul_statistik'] = $_SESSION['judul_statistik'];
		else $data['judul_statistik'] = '';

		if(isset($_SESSION['status_dasar']))
			$data['status_dasar'] = $_SESSION['status_dasar'];
		else $data['status_dasar'] = '';

		if(isset($_SESSION['sex']))
			$data['sex'] = $_SESSION['sex'];
		else $data['sex'] = '';

		if(isset($_SESSION['id_bos']))
			$data['id_bos'] = $_SESSION['id_bos'];
		else $data['id_bos'] = '';

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

	// function sosial($p=1,$o=0){

	// 	$data['p']        = $p;
	// 	$data['o']        = $o;

	// 	if(isset($_SESSION['cari']))
	// 		$data['cari'] = $_SESSION['cari'];
	// 	else $data['cari'] = '';

	// 	if(isset($_SESSION['filter']))
	// 		$data['filter'] = $_SESSION['filter'];
	// 	else $data['filter'] = '';

	// 	if(isset($_SESSION['id_bos']))
	// 		$data['id_bos'] = $_SESSION['id_bos'];
	// 	else $data['id_bos'] = '';

	// 	if(isset($_POST['per_page']))
	// 		$_SESSION['per_page']=$_POST['per_page'];
	// 	$data['per_page'] = $_SESSION['per_page'];

	// 	if(isset($_SESSION['dusun'])){
	// 		$data['dusun'] = $_SESSION['dusun'];
	// 		$data['list_rw'] = $this->penduduk_model->list_rw($data['dusun']);

	// 	if(isset($_SESSION['rw'])){
	// 		$data['rw'] = $_SESSION['rw'];
	// 		$data['list_rt'] = $this->penduduk_model->list_rt($data['dusun'],$data['rw']);

	// 	if(isset($_SESSION['rt']))
	// 		$data['rt'] = $_SESSION['rt'];
	// 		else $data['rt'] = '';

	// 		}else $data['rw'] = '';

	// 	}else{
	// 		$data['dusun'] = '';
	// 		$data['rw'] = '';
	// 		$data['rt'] = '';
	// 	}

	// 	$data['paging']  = $this->keluarga_model->paging($p,$o);
	// 	$data['main']    = $this->keluarga_model->list_raskin();
	// 	$data['keyword'] = $this->keluarga_model->autocomplete();
	// 	$data['list_dusun'] = $this->penduduk_model->list_dusun();

	// 	$nav['act']= 1;
	// 	$header = $this->header_model->get_data();
	// 	$this->load->view('header',$header);
	// 	$this->load->view('sid/nav',$nav);
	// 	$this->load->view('sid/kependudukan/keluarga_sosial',$data);
	// 	$this->load->view('footer');
	// }

	function cetak($o=0){
		$data['main']    = $this->keluarga_model->list_data($o, 0, 10000);
		$this->load->view('sid/kependudukan/keluarga_print',$data);
	}

	function excel($o=0){
		$data['main']    = $this->keluarga_model->list_data($o, 0, 10000);
		$this->load->view('sid/kependudukan/keluarga_excel',$data);
	}

	function form($p=1,$o=0,$id=0,$new=1){
		// Reset kalau dipanggil dari luar pertama kali ($_POST kosong)
		if (empty($_POST) AND (!isset($_SESSION['dari_internal']) OR !$_SESSION['dari_internal']))
				unset($_SESSION['validation_error']);

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
			// Validasi dilakukan di keluarga_model sewaktu insert dan update
			if (isset($_SESSION['validation_error']) AND $_SESSION['validation_error']) {
				// Kalau dipanggil internal pakai data yang disimpan di $_SESSION
				if ($_SESSION['dari_internal']) {
					$data['penduduk'] = $_SESSION['post'];
					$data['dus_sel'] = $_SESSION['post']['dusun'];
					$data['rw_sel'] = $_SESSION['post']['rw'];
					$data['rt_sel'] = $_SESSION['post']['rt'];
				} else {
					$data['penduduk'] = $_POST;
				}
			} else
				$data['penduduk'] = null;
			$data['kk']          = null;
			$data['form_action'] = site_url("keluarga/insert_new");

		}else{
			$data['kk']          = null;
			$data['form_action'] = site_url("keluarga/insert");
		}

		$data['penduduk_lepas'] = $this->keluarga_model->list_penduduk_lepas();

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
		$data['cara_kb'] = $this->penduduk_model->list_cara_kb($data['penduduk']['id_sex']);
		$data['wajib_ktp'] = $this->referensi_model->list_wajib_ktp();
		$data['ktp_el'] = $this->referensi_model->list_ktp_el();
		$data['status_rekam'] = $this->referensi_model->list_status_rekam();
		$data['tempat_dilahirkan'] = $this->referensi_model->list_kode_array(TEMPAT_DILAHIRKAN);
		$data['jenis_kelahiran'] = $this->referensi_model->list_kode_array(JENIS_KELAHIRAN);
		$data['penolong_kelahiran'] = $this->referensi_model->list_kode_array(PENOLONG_KELAHIRAN);

		unset($_SESSION['dari_internal']);
		$header = $this->header_model->get_data();

		$this->load->view('header',$header);
		$this->load->view('sid/nav',$nav);
		$this->load->view('sid/kependudukan/keluarga_form',$data);
		$this->load->view('footer');
	}

	function form_a($p=1,$o=0, $id=0){
		// Reset kalau dipanggil dari luar pertama kali ($_POST kosong)
		if (empty($_POST) AND !$_SESSION['dari_internal'])
				unset($_SESSION['validation_error']);
		else unset($_SESSION['dari_internal']);

		$data['id_kk']  	 = $id;
		$data['kk']          = $this->keluarga_model->get_kepala_a($id);
		$data['form_action'] = site_url("keluarga/insert_a");
		$nav['act']= 2;

		$data['agama'] = $this->penduduk_model->list_agama();
		$data['pendidikan_kk'] = $this->penduduk_model->list_pendidikan_kk();
		$data['pendidikan_sedang'] = $this->penduduk_model->list_pendidikan_sedang();
		$data['pekerjaan'] = $this->penduduk_model->list_pekerjaan();
		$data['warganegara'] = $this->penduduk_model->list_warganegara();
		$data['hubungan'] = $this->penduduk_model->list_hubungan($data['kk']['status_kawin']);
		$data['kawin'] = $this->penduduk_model->list_status_kawin();
		$data['golongan_darah'] = $this->penduduk_model->list_golongan_darah();
		$data['cacat'] = $this->penduduk_model->list_cacat();
		$data['sakit_menahun'] = $this->referensi_model->list_data('tweb_sakit_menahun');
		$data['cara_kb'] = $this->penduduk_model->list_cara_kb($data['penduduk']['id_sex']);
		$data['wajib_ktp'] = $this->referensi_model->list_wajib_ktp();
		$data['ktp_el'] = $this->referensi_model->list_ktp_el();
		$data['status_rekam'] = $this->referensi_model->list_status_rekam();
		$data['tempat_dilahirkan'] = $this->referensi_model->list_kode_array(TEMPAT_DILAHIRKAN);
		$data['jenis_kelahiran'] = $this->referensi_model->list_kode_array(JENIS_KELAHIRAN);
		$data['penolong_kelahiran'] = $this->referensi_model->list_kode_array(PENOLONG_KELAHIRAN);

		// Validasi dilakukan di keluarga_model sewaktu insert dan update
		if ($_SESSION['validation_error']) {
			$data['id_kk'] = $_SESSION['id_kk'];
			$data['kk'] = $_SESSION['kk'];
			$data['penduduk'] = $_SESSION['post'];
		}

		$header = $this->header_model->get_data();

		$this->load->view('header',$header);
		$this->load->view('sid/nav',$nav);
		$this->load->view('sid/kependudukan/keluarga_form_a',$data);
		$this->load->view('footer');
	}

	function edit_nokk($p=1,$o=0,$id=0){
		$data['kk'] = $this->keluarga_model->get_keluarga($id);
		$data['program'] = $this->program_bantuan_model->list_program_keluarga($id);
		$data['keluarga_sejahtera'] = $this->referensi_model->list_data('tweb_keluarga_sejahtera');
		$data['form_action'] = site_url("keluarga/update_nokk/$id");
		$this->load->view('sid/kependudukan/ajax_edit_nokk',$data);
	}

	function form_old($p=1,$o=0,$id=0){

		$data['penduduk'] = $this->keluarga_model->list_penduduk_lepas();
		$data['form_action'] = site_url("keluarga/insert/$id");
		$this->load->view('sid/kependudukan/ajax_add_keluarga',$data);

	}

	function status_dasar(){
		$status_dasar = $this->input->post('status_dasar');
		if($status_dasar!="")
			$_SESSION['status_dasar']=$status_dasar;
		else unset($_SESSION['status_dasar']);
		redirect('keluarga');
	}

	function sex(){
		$sex = $this->input->post('sex');
		if($sex!="")
			$_SESSION['sex']=$sex;
		else unset($_SESSION['sex']);
		redirect('keluarga');
	}

	function dusun(){
		unset($_SESSION['rw']);
		unset($_SESSION['rt']);
		$dusun = $this->input->post('dusun');
		if($dusun!="")
			$_SESSION['dusun']=$dusun;
		else unset($_SESSION['dusun']);
		redirect('keluarga');
	}

	function rw(){
		unset($_SESSION['rt']);
		$rw = $this->input->post('rw');
		if($rw!="")
			$_SESSION['rw']=$rw;
		else unset($_SESSION['rw']);
		redirect('keluarga');
	}

	function rt(){
		$rt = $this->input->post('rt');
		if($rt!="")
			$_SESSION['rt']=$rt;
		else unset($_SESSION['rt']);
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
		if ($_SESSION['validation_error']) {
			$id_kk = $this->input->post('id_kk');
			$_SESSION['id_kk'] = $id_kk;
			$_SESSION['kk'] = $this->keluarga_model->get_kepala_a($id_kk);
			$_SESSION['dari_internal'] = true;
			redirect("keluarga/form_a/$p/0/$id_kk");
		} else {
			redirect('keluarga');
		}
	}

	function insert_new(){
		$this->keluarga_model->insert_new();
		if ($_SESSION['success'] == -1) {
			$_SESSION['dari_internal'] = true;
			redirect("keluarga/form");
		} else {
			redirect('keluarga');
		}
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

		$kk 			  = $this->keluarga_model->get_kepala_kk($id);
		if($kk)
			$data['kepala_kk'] = $kk;
		else
			$data['kepala_kk'] = NULL;
		$data['hubungan'] = $this->penduduk_model->list_hubungan($data['kepala_kk']['status_kawin_id']);
		$data['main']     = $this->keluarga_model->list_anggota($id);
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
			$data['kepala_kk'] = $this->keluarga_model->get_keluarga($id);

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
		$data = $this->keluarga_model->get_data_cetak_kk($id);
		$this->load->view("sid/kependudukan/cetak_kk_all", $data);

	}

	function cetak_kk_all(){
		$data = $this->keluarga_model->get_data_cetak_kk_all();
		$this->load->view("sid/kependudukan/cetak_kk_all", $data);
	}

	function doc_kk($id=0){
		$this->keluarga_model->unduh_kk($id);
	}

	function doc_kk_all($id=0){
		$this->keluarga_model->unduh_kk();
	}

	function coba2($id=0){

		//$data['desa']     = $this->keluarga_model->get_desa();

		//$data['id_kk']    = $id;
		//$data['main']     = $this->keluarga_model->list_anggota($id);
		//$data['kepala_kk']= $this->keluarga_model->get_kepala_kk($id);

		$this->keluarga_model->coba2();
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
		$alamat = $_POST['alamat'];
		$this->keluarga_model->pindah_proses($id,$id_cluster,$alamat);
		redirect("keluarga");
	}

	function ajax_penduduk_pindah($id=0){
		$data['alamat_wilayah'] = $this->keluarga_model->get_alamat_wilayah($id);
		$data['dusun'] = $this->penduduk_model->list_dusun();

		$data['form_action'] = site_url("keluarga/pindah_proses/$id");
		$this->load->view('sid/kependudukan/ajax_pindah_form',$data);
	}

	function ajax_penduduk_pindah_rw($dusun=''){
		$dusun = urldecode($dusun);
		$rw = $this->penduduk_model->list_rw($dusun);
		//$this->load->view("sid/kependudukan/ajax_penduduk_pindah_form_rw", $data);
		echo"<td>RW</td>
		<td><select name='rw' onchange=RWSel('".rawurlencode($dusun)."',this.value)>
		<option value=''>Pilih RW&nbsp;</option>";
		foreach($rw as $data){
			echo "<option>".$data['rw']."</option>";
		}echo"</select>
		</td>";
	}

	function ajax_penduduk_pindah_rt($dusun='',$rw=''){
		$dusun = urldecode($dusun);
		$rt = $this->penduduk_model->list_rt($dusun,$rw);
		//$this->load->view("sid/kependudukan/ajax_penduduk_pindah_form_rt", $data);
		echo "<td>RT</td>
		<td><select name='id_cluster'>
		<option value=''>Pilih RT&nbsp;</option>";
		foreach($rt as $data){
			echo "<option value=".$data['rt'].">".$data['rt']."</option>";
		}echo"</select>
		</td>";
	}

	function statistik($tipe=0,$nomor=0,$sex=null,$p=1,$o=0){
		$_SESSION['per_page'] = 50;
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		$_SESSION['status_dasar'] = 1; // tampilkan KK aktif saja
		unset($_SESSION['dusun']);
		unset($_SESSION['rw']);
		unset($_SESSION['rt']);
		unset($_SESSION['sex']);
		unset($_SESSION['kelas']);
		unset($_SESSION['id_bos']);

		// Untuk tautan TOTAL di laporan statistik, di mana arg-2 = sex dan arg-3 kosong
		if ($sex == NULL) {
			if ($nomor != 0) $_SESSION['sex'] = $nomor;
			else unset($_SESSION['sex']);
			unset($_SESSION['judul_statistik']);
			redirect('keluarga');
		}

		if($sex==0)
			unset($_SESSION['sex']);
		else
			$_SESSION['sex']=$sex;

		switch($tipe){
			case 'kelas_sosial': $_SESSION['kelas'] = $nomor;  $pre="KLASIFIKASI SOSIAL : "; break;
		}
		$judul= $this->keluarga_model->get_judul_statistik($tipe,$nomor,$sex);
		if($judul['nama']){
			$_SESSION['judul_statistik']=$pre.$judul['nama'];
		}else{
			unset($_SESSION['judul_statistik']);
		}
		redirect('keluarga');
	}


	function cetak_statistik($tipe=0){
		$data['main']    = $this->keluarga_model->list_data_statistik($tipe);
		$this->load->view('sid/kependudukan/keluarga_print',$data);
	}
}
