<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Keluarga extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('header_model');
		$this->load->model('keluarga_model');
		$this->load->model('penduduk_model');
		$this->load->model('wilayah_model');
		$this->load->model('program_bantuan_model');
		$this->load->model('referensi_model');
		$this->load->model('config_model');
		$this->modul_ini = 2;
		$this->sub_modul_ini = 22;
	}

	public function clear()
	{
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		$_SESSION['status_dasar'] = 1; // tampilkan KK aktif saja
		unset($_SESSION['dusun']);
		unset($_SESSION['rw']);
		unset($_SESSION['rt']);
		unset($_SESSION['sex']);
		unset($_SESSION['kelas']);
		unset($_SESSION['id_bos']);
		unset($_SESSION['judul_statistik']);
		unset($_SESSION['bantuan_keluarga']);
		$_SESSION['per_page'] = 100;
		redirect('keluarga');
	}

	public function index($p=1, $o=0)
	{
		$data['p'] = $p;
		$data['o'] = $o;

		if (isset($_SESSION['cari']))
			$data['cari'] = $_SESSION['cari'];
		else $data['cari'] = '';

		if (isset($_SESSION['judul_statistik']))
			$data['judul_statistik'] = $_SESSION['judul_statistik'];
		else $data['judul_statistik'] = '';

		if (isset($_SESSION['status_dasar']))
			$data['status_dasar'] = $_SESSION['status_dasar'];
		else $data['status_dasar'] = '';

		if (isset($_SESSION['sex']))
			$data['sex'] = $_SESSION['sex'];
		else $data['sex'] = '';

		if (isset($_SESSION['id_bos']))
			$data['id_bos'] = $_SESSION['id_bos'];
		else $data['id_bos'] = '';

		if (isset($_POST['per_page']))
			$_SESSION['per_page'] = $_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];
		if (isset($_SESSION['dusun']))
		{
			$data['dusun'] = $_SESSION['dusun'];
			$data['list_rw'] = $this->penduduk_model->list_rw($data['dusun']);

			if (isset($_SESSION['rw']))
			{
				$data['rw'] = $_SESSION['rw'];
				$data['list_rt'] = $this->penduduk_model->list_rt($data['dusun'],$data['rw']);
				if(isset($_SESSION['rt']))
					$data['rt'] = $_SESSION['rt'];
				else $data['rt'] = '';
			}
			else $data['rw'] = '';
		}
		else
		{
			$data['dusun'] = '';
			$data['rw'] = '';
			$data['rt'] = '';
		}
		$data['paging'] = $this->keluarga_model->paging($p,$o);
		$data['main'] = $this->keluarga_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['list_dusun'] = $this->penduduk_model->list_dusun();
		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;

		$this->load->view('header',$header);
		$this->load->view('nav',$nav);
		$this->load->view('sid/kependudukan/keluarga', $data);
		$this->load->view('footer');
	}

	public function autocomplete()
	{
		$data = $this->keluarga_model->autocomplete($this->input->post('cari'));
		echo json_encode($data);
	}

	public function cetak($o=0)
	{
		$data['main'] = $this->keluarga_model->list_data($o, 0, 10000);
		$this->load->view('sid/kependudukan/keluarga_print', $data);
	}

	public function excel($o=0)
	{
		$data['main'] = $this->keluarga_model->list_data($o, 0, 10000);
		$this->load->view('sid/kependudukan/keluarga_excel', $data);
	}

	/*
	 * Masukkan KK baru
	 */
	public function form($p=1, $o=0)
	{
		// Reset kalau dipanggil dari luar pertama kali ($_POST kosong)
		if (empty($_POST) AND (!isset($_SESSION['dari_internal']) OR !$_SESSION['dari_internal']))
				unset($_SESSION['validation_error']);

		$data['kk_baru'] = true;

		// Validasi dilakukan di keluarga_model sewaktu insert dan update
		if (isset($_SESSION['validation_error']) AND $_SESSION['validation_error'])
		{
			// Kalau dipanggil internal pakai data yang disimpan di $_SESSION
			if ($_SESSION['dari_internal'])
			{
				$data['penduduk'] = $_SESSION['post'];
			}
			else
			{
				$data['penduduk'] = $_POST;
			}
			// Di penduduk_isian_form memakai 'sex' sesuai dengan nama kolom
			// tapi pengisian nilai sebelumnya menggunakan 'id_sex'
			$data['penduduk']['id_sex'] = $data['penduduk']['sex'];
		}
		else
			$data['penduduk'] = null;
		$data['kk'] = null;
		$data['form_action'] = site_url("keluarga/insert_new");
		$data['penduduk_lepas'] = $this->keluarga_model->list_penduduk_lepas();
		$data['dusun'] = $this->penduduk_model->list_dusun();
		$data['rw'] = $this->penduduk_model->list_rw($data['penduduk']['dusun']);
		$data['rt'] = $this->penduduk_model->list_rt($data['penduduk']['dusun'], $data['penduduk']['rw']);
		$data['agama'] = $this->penduduk_model->list_agama();
		$data['pendidikan_sedang'] = $this->penduduk_model->list_pendidikan_sedang();
		$data['pendidikan_kk'] = $this->penduduk_model->list_pendidikan_kk();
		$data['pekerjaan'] = $this->penduduk_model->list_pekerjaan();
		$data['warganegara'] = $this->penduduk_model->list_warganegara();
		$data['hubungan'] = $this->penduduk_model->list_hubungan();
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
		$data['pilihan_asuransi'] = $this->referensi_model->list_data('tweb_penduduk_asuransi');

		unset($_SESSION['dari_internal']);
		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;

		$this->load->view('header', $header);
		$this->load->view('nav',$nav);
		$this->load->view('sid/kependudukan/keluarga_form', $data);
		$this->load->view('footer');
	}

	// Tambah anggota keluarga dari penduduk baru
	public function form_a($p=1, $o=0, $id=0)
	{
		// Reset kalau dipanggil dari luar pertama kali ($_POST kosong)
		if (empty($_POST) AND !$_SESSION['dari_internal'])
				unset($_SESSION['validation_error']);
		else unset($_SESSION['dari_internal']);

		$data['id_kk'] = $id;
		$data['kk'] = $this->keluarga_model->get_kepala_a($id);
		$data['form_action'] = site_url("keluarga/insert_a");
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
		$data['pilihan_asuransi'] = $this->referensi_model->list_data('tweb_penduduk_asuransi');

		// Validasi dilakukan di keluarga_model sewaktu insert dan update
		if ($_SESSION['validation_error'])
		{
			$data['id_kk'] = $_SESSION['id_kk'];
			$data['kk'] = $_SESSION['kk'];
			$data['penduduk'] = $_SESSION['post'];
		}

		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;
		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('sid/kependudukan/keluarga_form_a', $data);
		$this->load->view('footer');
	}

	public function edit_nokk($p=1, $o=0, $id=0)
	{
		$data['kk'] = $this->keluarga_model->get_keluarga($id);
		$data['dusun'] = $this->wilayah_model->list_dusun();
		$data['rw'] = $this->wilayah_model->list_rw($data['kk']['dusun']);
		$data['rt'] = $this->wilayah_model->list_rt($data['kk']['dusun'], $data['kk']['rw']);
		$data['program'] = $this->program_bantuan_model->list_program_keluarga($id);
		$data['keluarga_sejahtera'] = $this->referensi_model->list_data('tweb_keluarga_sejahtera');
		$data['form_action'] = site_url("keluarga/update_nokk/$id");
		$this->load->view('sid/kependudukan/ajax_edit_nokk', $data);
	}

	public function form_old($p=1, $o=0, $id=0)
	{
		$data['penduduk'] = $this->keluarga_model->list_penduduk_lepas();
		$data['form_action'] = site_url("keluarga/insert/$id");
		$this->load->view('sid/kependudukan/ajax_add_keluarga', $data);
	}

	public function status_dasar()
	{
		$status_dasar = $this->input->post('status_dasar');
		if ($status_dasar != "")
			$_SESSION['status_dasar'] = $status_dasar;
		else unset($_SESSION['status_dasar']);
		redirect('keluarga');
	}

	public function sex()
	{
		$sex = $this->input->post('sex');
		if ($sex != "")
			$_SESSION['sex'] = $sex;
		else unset($_SESSION['sex']);
		redirect('keluarga');
	}

	public function dusun()
	{
		unset($_SESSION['rw']);
		unset($_SESSION['rt']);
		$dusun = $this->input->post('dusun');
		if ($dusun != "")
			$_SESSION['dusun'] = $dusun;
		else unset($_SESSION['dusun']);
		redirect('keluarga');
	}

	public function rw()
	{
		unset($_SESSION['rt']);
		$rw = $this->input->post('rw');
		if ($rw != "")
			$_SESSION['rw'] = $rw;
		else unset($_SESSION['rw']);
		redirect('keluarga');
	}

	public function rt()
	{
		$rt = $this->input->post('rt');
		if ($rt != "")
			$_SESSION['rt'] = $rt;
		else unset($_SESSION['rt']);
		redirect('keluarga');
	}

	public function search()
	{
		$cari = $this->input->post('cari');
		if ($cari != '')
			$_SESSION['cari'] = $cari;
		else unset($_SESSION['cari']);
		redirect('keluarga');
	}

	/*
	 * Tambah KK dengan memilih dari penduduk yg sudah ada
	 */
	public function insert()
	{
		$this->keluarga_model->insert();
		redirect('keluarga');
	}

	public function insert_a()
	{
		$id_kk = $this->input->post('id_kk');
		$this->keluarga_model->insert_a();
		if ($_SESSION['validation_error'])
		{
			$_SESSION['id_kk'] = $id_kk;
			$_SESSION['kk'] = $this->keluarga_model->get_kepala_a($id_kk);
			$_SESSION['dari_internal'] = true;
			redirect("keluarga/form_a/1/0/$id_kk");
		}
		else
		{
			redirect("keluarga/kartu_keluarga/1/0/$id_kk");
		}
	}

	/*
	 * Tambah KK dengan mengisi form penduduk kepala keluarga baru
	 */
	public function insert_new()
	{
		$this->keluarga_model->insert_new();
		if ($_SESSION['success'] == -1)
		{
			$_SESSION['dari_internal'] = true;
			redirect("keluarga/form");
		}
		else
		{
			redirect('keluarga');
		}
	}

	public function update_nokk($id='')
	{
		$this->keluarga_model->update_nokk($id);
		redirect('keluarga');
	}

	public function delete($p=1, $o=0, $id='')
	{
		$this->redirect_hak_akses('h', 'keluarga');
		$this->keluarga_model->delete($id);
		redirect('keluarga');
	}

	public function delete_all($p=1, $o=0)
	{
		$this->redirect_hak_akses('h', 'keluarga');
		$this->keluarga_model->delete_all();
		redirect('keluarga');
	}

	public function anggota($p=1, $o=0, $id=0)
	{
		$data['p'] = $p;
		$data['o'] = $o;
		$data['kk'] = $id;

		$data['main'] = $this->keluarga_model->list_anggota($id);
		$data['kepala_kk'] = $this->keluarga_model->get_kepala_kk($id);
		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;

		$this->load->view('header', $header);
		$this->load->view('nav',$nav);
		$this->load->view('sid/kependudukan/keluarga_anggota', $data);
		$this->load->view('footer');
	}

	public function ajax_add_anggota($p=1, $o=0, $id=0)
	{
		$data['p'] = $p;
		$data['o'] = $o;

		$kk = $this->keluarga_model->get_kepala_kk($id);
		if ($kk)
			$data['kepala_kk'] = $kk;
		else
			$data['kepala_kk'] = NULL;
		$data['hubungan'] = $this->penduduk_model->list_hubungan($data['kepala_kk']['status_kawin_id']);
		$data['main'] = $this->keluarga_model->list_anggota($id);
		$data['penduduk'] = $this->keluarga_model->list_penduduk_lepas();

		$data['form_action'] = site_url("keluarga/add_anggota/$p/$o/$id");

		$this->load->view("sid/kependudukan/ajax_add_anggota_form", $data);
	}

	public function edit_anggota($p=1, $o=0, $id_kk=0, $id=0)
	{
		$data['p'] = $p;
		$data['o'] = $o;

		$data['hubungan'] = $this->keluarga_model->list_hubungan();
		$data['main'] = $this->keluarga_model->get_anggota($id);

		$kk = $this->keluarga_model->get_kepala_kk($id);
		if ($kk)
			$data['kepala_kk'] = $kk;
		else
			$data['kepala_kk'] = NULL;

		$data['form_action'] = site_url("keluarga/update_anggota/$p/$o/$id_kk/$id");

		$this->load->view("sid/kependudukan/ajax_edit_anggota_form", $data);
	}

	public function kartu_keluarga($p=1, $o=0, $id=0)
	{
		$data['p'] = $p;
		$data['o'] = $o;
		$data['id_kk'] = $id;

		$data['hubungan'] = $this->keluarga_model->list_hubungan();
		$data['main'] = $this->keluarga_model->list_anggota($id);
		$kk = $this->keluarga_model->get_kepala_kk($id);
		$data['desa'] = $this->config_model->get_data();
		if ($kk)
			$data['kepala_kk'] = $kk;
		else
			$data['kepala_kk'] = $this->keluarga_model->get_keluarga($id);

		$data['penduduk'] = $this->keluarga_model->list_penduduk_lepas();
		$data['form_action'] = site_url("keluarga/print");
		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view("sid/kependudukan/kartu_keluarga", $data);
		$this->load->view('footer');
	}

	public function cetak_kk($id=0)
	{
		$data = $this->keluarga_model->get_data_cetak_kk($id);
		$this->load->view("sid/kependudukan/cetak_kk_all", $data);
	}

	public function cetak_kk_all()
	{
		$data = $this->keluarga_model->get_data_cetak_kk_all();
		$this->load->view("sid/kependudukan/cetak_kk_all", $data);
	}

	public function doc_kk($id=0)
	{
		$this->keluarga_model->unduh_kk($id);
	}

	public function doc_kk_all($id=0)
	{
		$this->keluarga_model->unduh_kk();
	}

	public function add_anggota($p=1, $o=0, $id=0)
	{
		$this->keluarga_model->add_anggota($id);
		redirect("keluarga/anggota/$p/$o/$id");
	}

	public function update_anggota($p=1, $o=0, $id_kk=0, $id=0)
	{
		$this->keluarga_model->update_anggota($id);
		redirect("keluarga/anggota/$p/$o/$id_kk");
	}

	public function delete_anggota($p=1, $o=0, $kk=0, $id='')
	{
		$this->keluarga_model->rem_anggota($kk,$id);
		redirect("keluarga/anggota/$p/$o/$kk");
	}

	public function delete_all_anggota($p=1, $o=0, $kk=0)
	{
		$this->keluarga_model->rem_all_anggota($kk);
		redirect("keluarga/anggota/$p/$o/$kk");
	}

	public function statistik($tipe=0, $nomor=0, $sex=null, $p=1, $o=0)
	{
		$_SESSION['per_page'] = 50;
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		$_SESSION['status_dasar'] = 1; // tampilkan KK aktif saja
		unset($_SESSION['dusun']);
		unset($_SESSION['rw']);
		unset($_SESSION['rt']);
		unset($_SESSION['sex']);
		unset($_SESSION['kelas']);
		unset($_SESSION['bantuan_keluarga']);
		unset($_SESSION['id_bos']);

		// Untuk tautan TOTAL di laporan statistik, di mana arg-2 = sex dan arg-3 kosong
		if ($sex == NULL)
		{
			if ($nomor != 0) $_SESSION['sex'] = $nomor;
			else unset($_SESSION['sex']);
			unset($_SESSION['judul_statistik']);
			redirect('keluarga');
		}

		if ($sex == 0)
			unset($_SESSION['sex']);
		else
			$_SESSION['sex'] = $sex;

		switch ($tipe)
		{
			case 'kelas_sosial':
				$_SESSION['kelas'] = $nomor;
				$pre = "KLASIFIKASI SOSIAL : ";
				break;
			case 'bantuan_keluarga':
				$_SESSION['bantuan_keluarga'] = $nomor;
				$pre = "PENERIMA BANTUAN (KELUARGA): ";
				break;
		}
		$judul = $this->keluarga_model->get_judul_statistik($tipe,$nomor,$sex);
		if ($judul['nama'])
		{
			$_SESSION['judul_statistik'] = $pre.$judul['nama'];
		}
		else
		{
			unset($_SESSION['judul_statistik']);
		}
		redirect('keluarga');
	}

	public function cetak_statistik($tipe=0)
	{
		$data['main'] = $this->keluarga_model->list_data_statistik($tipe);
		$this->load->view('sid/kependudukan/keluarga_print', $data);
	}
}
