<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Penduduk extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('penduduk_model');
		$this->load->model('keluarga_model');
		$this->load->model('wilayah_model');
		$this->load->model('referensi_model');
		$this->load->model('web_dokumen_model');
		$this->load->model('header_model');
		$this->load->model('config_model');
		$this->modul_ini = 2;
		$this->sub_modul_ini = 21;
	}

	private function clear_session()
	{
		unset($_SESSION['log']);
		unset($_SESSION['cari']);
		unset($_SESSION['filter']); // ini status_penduduk
		$_SESSION['status_dasar'] = 1; // default status dasar = hidup
		unset($_SESSION['sex']);
		unset($_SESSION['warganegara']);
		unset($_SESSION['cacat']);
		unset($_SESSION['menahun']);
		unset($_SESSION['golongan_darah']);
		unset($_SESSION['agama']);
		unset($_SESSION['umur_min']);
		unset($_SESSION['umur_max']);
		unset($_SESSION['pekerjaan_id']);
		unset($_SESSION['status']);
		unset($_SESSION['pendidikan_sedang_id']);
		unset($_SESSION['pendidikan_kk_id']);
		unset($_SESSION['umurx']);
		unset($_SESSION['status_penduduk']);
		unset($_SESSION['judul_statistik']);
		unset($_SESSION['hamil']);
		unset($_SESSION['cara_kb_id']);
		unset($_SESSION['akta_kelahiran']);
		unset($_SESSION['status_ktp']);
		unset($_SESSION['id_asuransi']);
		unset($_SESSION['status_covid']);
		unset($_SESSION['penerima_bantuan']);
	}

	public function clear()
	{
		$this->clear_session();
		parent::clear_cluster_session();
		$_SESSION['per_page'] = 50;
		redirect('penduduk');
	}

	public function index($p = 1, $o = 0)
	{
		$data['p'] = $p;
		$data['o'] = $o;

		$list_session = array('cari', 'judul_statistik', 'filter', 'status_dasar', 'umur_min', 'umur_max', 'sex', 'agama', 'cacat', 'cara_kb_id', 'akta_kelahiran', 'pekerjaan_id', 'status', 'pendidikan_sedang_id', 'pendidikan_kk_id', 'status_penduduk', 'status_ktp', 'id_asuransi', 'status_covid', 'penerima_bantuan');
		foreach ($list_session as $session)
		{
			$data[$session] = $this->session->userdata($session) ?: '';
		}

		if (isset($_SESSION['dusun']))
		{
			$data['dusun'] = $_SESSION['dusun'];
			$data['list_rw'] = $this->penduduk_model->list_rw($data['dusun']);

			if (isset($_SESSION['rw']))
			{
				$data['rw'] = $_SESSION['rw'];
				$data['list_rt'] = $this->penduduk_model->list_rt($data['dusun'], $data['rw']);

				if (isset($_SESSION['rt']))
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

		if (isset($_POST['per_page']))
			$_SESSION['per_page'] = $_POST['per_page'];

		$data['per_page'] = $_SESSION['per_page'];
		$data['paging'] = $this->penduduk_model->paging($p, $o);
		$data['main'] = $this->penduduk_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['list_dusun'] = $this->penduduk_model->list_dusun();
		$data['list_status_dasar'] = $this->referensi_model->list_data('tweb_status_dasar');
		$data['list_status_penduduk'] = $this->referensi_model->list_data('tweb_penduduk_status');
		$data['list_jenis_kelamin'] = $this->referensi_model->list_data('tweb_penduduk_sex');
		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('sid/kependudukan/penduduk', $data);
		$this->load->view('footer');
	}

	public function form($p = 1, $o = 0, $id = '')
	{
		// Reset kalau dipanggil dari luar pertama kali ($_POST kosong)
		if (empty($_POST) AND (!isset($_SESSION['dari_internal']) OR !$_SESSION['dari_internal']))
			unset($_SESSION['validation_error']);

		$data['p'] = $p;
		$data['o'] = $o;

		if ($id)
		{
			$data['id'] = $id;
			// Validasi dilakukan di penduduk_model sewaktu insert dan update
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
				// penduduk_model->get_penduduk mengambil sebagai 'id_sex',
				// tapi di penduduk_form memakai 'sex' sesuai dengan nama kolom
				$data['penduduk']['id_sex'] = $data['penduduk']['sex'];
			}
			else
			{
				$data['penduduk'] = $this->penduduk_model->get_penduduk($id);
				$_SESSION['nik_lama'] = $data['penduduk']['nik'];
			}
			$data['form_action'] = site_url("penduduk/update/1/$o/$id");
		}
		else
		{
			// Validasi dilakukan di penduduk_model sewaktu insert dan update
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
			}
			else
				$data['penduduk'] = null;
			$data['form_action'] = site_url("penduduk/insert");
		}

		$header = $this->header_model->get_data();

		$data['dusun'] = $this->wilayah_model->list_dusun();
		$data['rw'] = $this->wilayah_model->list_rw($data['penduduk']['dusun']);
		$data['rt'] = $this->wilayah_model->list_rt($data['penduduk']['dusun'], $data['penduduk']['rw']);
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
		$header['minsidebar'] = 1;
		unset($_SESSION['dari_internal']);

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('sid/kependudukan/penduduk_form', $data);
		$this->load->view('footer');
	}

	public function detail($p = 1, $o = 0, $id = '')
	{
		$data['p'] = $p;
		$data['o'] = $o;
		$data['list_dokumen'] = $this->penduduk_model->list_dokumen($id);
		$data['penduduk'] = $this->penduduk_model->get_penduduk($id);
		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('sid/kependudukan/penduduk_detail', $data);
		$this->load->view('footer');
	}

	public function dokumen($id = '')
	{
		$data['list_dokumen'] = $this->penduduk_model->list_dokumen($id);
		$data['penduduk'] = $this->penduduk_model->get_penduduk($id);

		$header = $this->header_model->get_data();

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('sid/kependudukan/penduduk_dokumen', $data);
		$this->load->view('footer');
	}

	public function dokumen_form($id = 0, $id_dokumen = 0)
	{
		$data['penduduk'] = $this->penduduk_model->get_penduduk($id);

		if ($data['penduduk']['kk_level'] === '1') //Jika Kepala Keluarga
		{
			$data['kk'] = $this->keluarga_model->list_anggota($data['penduduk']['id_kk']);
		}

		if ($id_dokumen)
		{
			$data['dokumen'] = $this->web_dokumen_model->get_dokumen($id_dokumen);

			// Ambil data anggota KK
			if ($data['penduduk']['kk_level'] === '1') //Jika Kepala Keluarga
			{
				$data['dokumen_anggota'] = $this->web_dokumen_model->get_dokumen_di_anggota_lain($id_dokumen);

				if (count($data['dokumen_anggota'])>0)
				{
					$id_pend_anggota = array();
					foreach ($data['dokumen_anggota'] as $item_dokumen)
						$id_pend_anggota[] = $item_dokumen['id_pend'];

					foreach ($data['kk'] as $key => $value)
					{
						if (in_array($value['id'], $id_pend_anggota))
							$data['kk'][$key]['checked'] = 'checked';
					}
				}
			}

			$data['form_action'] = site_url("penduduk/dokumen_update/$id_dokumen");
		}
		else
		{
			$data['dokumen'] = NULL;
			$data['form_action'] = site_url("penduduk/dokumen_insert");
		}
		$this->load->view('sid/kependudukan/dokumen_form', $data);
	}

	public function dokumen_list($id = 0)
	{
		$data['list_dokumen'] = $this->penduduk_model->list_dokumen($id);
		$data['penduduk'] = $this->penduduk_model->get_penduduk($id);
		$this->load->view('sid/kependudukan/dokumen_ajax', $data);
	}

	public function dokumen_insert()
	{
		$this->web_dokumen_model->insert();
		$id = $_POST['id_pend'];
		redirect("penduduk/dokumen/$id");
	}

	public function dokumen_update($id = '')
	{
		$this->web_dokumen_model->update($id);
		$id = $_POST['id_pend'];
		redirect("penduduk/dokumen/$id");
	}

	public function delete_dokumen($id_pend = 0, $id = '')
	{
		$this->redirect_hak_akses('h', "penduduk/dokumen/$id_pend");
		$this->web_dokumen_model->delete($id);
		redirect("penduduk/dokumen/$id_pend");
	}

	public function delete_all_dokumen($id_pend = 0)
	{
		$this->redirect_hak_akses('h', "penduduk/dokumen/$id_pend");
		$this->web_dokumen_model->delete_all();
		redirect("penduduk/dokumen/$id_pend");
	}

	public function cetak_biodata($id = '')
	{
		$header = $this->header_model->get_data();
		$data['desa'] = $header['desa'];
		$data['penduduk'] = $this->penduduk_model->get_penduduk($id);
		$this->load->view('sid/kependudukan/cetak_biodata', $data);
	}

	public function search()
	{
		$cari = $this->input->post('cari');
		if ($cari != '')
			$_SESSION['cari'] = $cari;
		else unset($_SESSION['cari']);
		redirect('penduduk');
	}

	public function filter()
	{
		$filter = $this->input->post('filter');
		if ($filter != "")
			$_SESSION['filter'] = $filter;
		else unset($_SESSION['filter']);
		redirect('penduduk');
	}

	public function status_dasar()
	{
		$status_dasar = $this->input->post('status_dasar');
		if ($status_dasar != "")
			$_SESSION['status_dasar'] = $status_dasar;
		else unset($_SESSION['status_dasar']);
		redirect('penduduk');
	}

	public function sex()
	{
		$sex = $this->input->post('sex');
		if ($sex != "")
			$_SESSION['sex'] = $sex;
		else unset($_SESSION['sex']);
		redirect('penduduk');
	}

	public function agama()
	{
		$agama = $this->input->post('agama');
		if ($agama != "")
			$_SESSION['agama'] = $agama;
		else unset($_SESSION['agama']);
		redirect('penduduk');
	}

	public function dusun()
	{
		unset($_SESSION['rw']);
		unset($_SESSION['rt']);
		$dusun = $this->input->post('dusun');
		if ($dusun != "")
			$_SESSION['dusun'] = $dusun;
		else unset($_SESSION['dusun']);
		redirect('penduduk');
	}

	public function rw()
	{
		unset($_SESSION['rt']);
		$rw = $this->input->post('rw');
		if ($rw != "")
			$_SESSION['rw'] = $rw;
		else unset($_SESSION['rw']);
		redirect('penduduk');
	}

	public function rt()
	{
		$rt = $this->input->post('rt');
		if ($rt != "")
			$_SESSION['rt'] = $rt;
		else unset($_SESSION['rt']);
		redirect('penduduk');
	}

	public function insert()
	{
		$id = $this->penduduk_model->insert();
		if ($_SESSION['success'] == -1)
		{
			$_SESSION['dari_internal'] = true;
			redirect("penduduk/form");
		}
		else
		{
			redirect("penduduk/detail/1/0/$id");
		}
	}

	public function update($p = 1, $o = 0, $id = '')
	{
		$this->penduduk_model->update($id);
		if ($_SESSION['success'] == -1)
		{
			$_SESSION['dari_internal'] = true;
			redirect("penduduk/form/$p/$o/$id");
		}
		else
		{
			redirect("penduduk/detail/1/0/$id");
		}
	}

	public function delete($p = 1, $o = 0, $id = '')
	{
		$this->redirect_hak_akses('h', "penduduk/index/$p/$o");
		$this->penduduk_model->delete($id);
		redirect("penduduk/index/$p/$o");
	}

	public function delete_all($p = 1, $o = 0)
	{
		$this->redirect_hak_akses('h', "penduduk/index/$p/$o");
		$this->penduduk_model->delete_all();
		redirect("penduduk/index/$p/$o");
	}

	public function ajax_adv_search()
	{
		$list_session = array('umur_min', 'umur_max', 'pekerjaan_id', 'status', 'agama', 'pendidikan_sedang_id', 'pendidikan_kk_id', 'status_penduduk');

		foreach ($list_session as $session)
		{
			$data[$session] = $this->session->userdata($session) ?: '';
		}

		$data['list_agama'] = $this->referensi_model->list_data('tweb_penduduk_agama');
		$data['list_pendidikan'] = $this->referensi_model->list_data('tweb_penduduk_pendidikan');
		$data['list_pendidikan_kk'] = $this->referensi_model->list_data('tweb_penduduk_pendidikan_kk');
		$data['list_pekerjaan'] = $this->referensi_model->list_data('tweb_penduduk_pekerjaan');
		$data['list_status_kawin'] = $this->referensi_model->list_data('tweb_penduduk_kawin');
		$data['list_status_penduduk'] = $this->referensi_model->list_data('tweb_penduduk_status');
		$data['form_action'] = site_url("penduduk/adv_search_proses");

		$this->load->view("sid/kependudukan/ajax_adv_search_form", $data);
	}

	public function adv_search_proses()
	{
		$_SESSION['filter'] = $_POST['status_penduduk'];

		$adv_search = $_POST;
		$i = 0;
		while ($i++ < count($adv_search))
		{
			$col[$i] = key($adv_search);
			next($adv_search);
		}
		$i = 0;
		while ($i++ < count($col))
		{
			if ($adv_search[$col[$i]] == "")
			{
				UNSET($adv_search[$col[$i]]);
				UNSET($_SESSION[$col[$i]]);
			}
			else
			{
				$_SESSION[$col[$i]] = $adv_search[$col[$i]];
			}
		}

		redirect('penduduk');
	}

	public function ajax_penduduk_pindah_rw($dusun = '')
	{
		$dusun = urldecode($dusun);
		$rw = $this->penduduk_model->list_rw($dusun);
		echo"<div class='form-group'><label>RW</label>
		<select class='form-control input-sm' name='rw' onchange=RWSel('".rawurlencode($dusun)."',this.value)>
		<option value=''>Pilih RW</option>";
		foreach ($rw as $data):
			echo "<option>".$data['rw']."</option>";
		endforeach;
		echo "</select></div>";
	}

	public function ajax_penduduk_pindah_rt($dusun = '', $rw = '')
	{
		$dusun = urldecode($dusun);
		$rt = $this->penduduk_model->list_rt($dusun, $rw);
		echo"<div class='form-group'><label>RT</label>
		<select class='form-control input-sm' name='id_cluster'>
		<option value=''>Pilih RT</option>";
		foreach ($rt as $data):
			echo "<option value=".$data['id'].">".$data['rt']."</option>";
		endforeach;
		echo "</select></div>";
	}

	public function ajax_penduduk_cari_rw($dusun = '')
	{
		$rw = $this->penduduk_model->list_rw($dusun);

		echo"<td>RW</td>
		<td><select name='rw' onchange=RWSel('".$dusun."',this.value)>
		<option value=''>Pilih RW&nbsp;</option>";
		foreach($rw as $data)
		{
			echo "<option>".$data['rw']."</option>";
		}
		echo"</select>
		</td>";
	}

	public function ajax_penduduk_maps($p = 1, $o = 0, $id = '', $edit = '')
	{
		$data['p'] = $p;
		$data['o'] = $o;
		$data['id'] = $id;
		$data['edit'] = $edit;

		$data['penduduk'] = $this->penduduk_model->get_penduduk_map($id);
		$data['desa'] = $this->config_model->get_data();
		$sebutan_desa = ucwords($this->setting->sebutan_desa);
		$data['wil_atas'] = $this->config_model->get_data();
		$data['dusun_gis'] = $this->wilayah_model->list_dusun();
		$data['rw_gis'] = $this->wilayah_model->list_rw_gis();
		$data['rt_gis'] = $this->wilayah_model->list_rt_gis();
		$data['form_action'] = site_url("penduduk/update_maps/$p/$o/$id/$edit");
		$header = $this->header_model->get_data();

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view("sid/kependudukan/ajax_penduduk_maps", $data);
		$this->load->view('footer');
	}

	public function update_maps($p = 1, $o = 0, $id = '', $edit = '')
	{
		$this->penduduk_model->update_position($id);
		if ($edit == 1)
			redirect("penduduk/form/$p/$o/$id");
		else
			redirect("penduduk");
	}

	public function edit_status_dasar($p = 1, $o = 0, $id = 0)
	{
		$data['nik'] = $this->penduduk_model->get_penduduk($id);
		$data['form_action'] = site_url("penduduk/update_status_dasar/$p/$o/$id");
		$data['list_ref_pindah'] = $this->referensi_model->list_data('ref_pindah');
		$data['list_status_dasar'] = $this->referensi_model->list_data('tweb_status_dasar', '9'); //Kecuali status dasar 'TIDAK VALID'
		$this->load->view('sid/kependudukan/ajax_edit_status_dasar', $data);
	}

	public function update_status_dasar($p = 1, $o = 0, $id = '')
	{
		$this->penduduk_model->update_status_dasar($id);
		redirect("penduduk/index/$p/$o");
	}

	public function kembalikan_status($p = 1, $o = 0, $id = '')
	{
		$this->penduduk_model->kembalikan_status($id);
		redirect("penduduk/index/$p/$o");
	}

	public function cetak($o = 0)
	{
		$data['main'] = $this->penduduk_model->list_data($o, 0, 10000);
		$this->load->view('sid/kependudukan/penduduk_print', $data);
	}

	public function excel($o = 0)
	{
		$data['main'] = $this->penduduk_model->list_data($o, 0, 10000);
		$this->load->view('sid/kependudukan/penduduk_excel', $data);
	}

	public function statistik($tipe = 0, $nomor = 0, $sex = NULL)
	{
		$_SESSION['per_page'] = 50;
		$this->clear_session();
		// Untuk tautan TOTAL di laporan statistik, di mana arg-2 = sex dan arg-3 kosong
		// kecuali untuk laporan wajib KTP
		if ($sex == NULL AND $tipe <> 18)
		{
			if ($nomor != 0) $_SESSION['sex'] = $nomor;
			else unset($_SESSION['sex']);
			unset($_SESSION['judul_statistik']);
			redirect('penduduk');
		}

		if ($sex == 0)
			unset($_SESSION['sex']);
		else
			$_SESSION['sex'] = $sex;

		switch ($tipe)
		{
			case '0': $_SESSION['pendidikan_kk_id'] = $nomor; $pre = "PENDIDIKAN DALAM KK : "; break;
			case 1: $_SESSION['pekerjaan_id'] = $nomor; $pre = "PEKERJAAN : "; break;
			case 2: $_SESSION['status'] = $nomor; $pre = "STATUS PERKAWINAN : "; break;
			case 3: $_SESSION['agama'] = $nomor; $pre = "AGAMA : "; break;
			case 4: $_SESSION['sex'] = $nomor; $pre = "JENIS KELAMIN : "; break;
			case 5: $_SESSION['warganegara'] = $nomor;  $pre = "WARGANEGARA : "; break;
			case 6: $_SESSION['status_penduduk'] = $nomor; $pre = "STATUS PENDUDUK : "; break;
			case 7: $_SESSION['golongan_darah'] = $nomor; $pre = "GOLONGAN DARAH : "; break;
			case 9: $_SESSION['cacat'] = $nomor; $pre = "CACAT : "; break;
			case 10: $_SESSION['menahun'] = $nomor;  $pre = "SAKIT MENAHUN : "; break;
			case 13: $_SESSION['umurx'] = $nomor;  $pre = "UMUR "; break;
			case 14: $_SESSION['pendidikan_sedang_id'] = $nomor; $pre = "PENDIDIKAN SEDANG DITEMPUH : "; break;
			case 16: $_SESSION['cara_kb_id'] = $nomor; $pre = "CARA KB : "; break;
			case 17:
				$_SESSION['akta_kelahiran'] = $nomor;
				if ($nomor <> BELUM_MENGISI) $_SESSION['umurx'] = $nomor;
				$pre = "AKTA KELAHIRAN : ";
				break;
			case 18:
				if ($sex == NULL)
				{
					$_SESSION['status_ktp'] = 0;
					$_SESSION['sex'] = ($nomor == 0) ? NULL : $nomor;
					$sex = $_SESSION['sex'];
					unset($nomor);
				}
				else
					$_SESSION['status_ktp'] = $nomor;
				$pre = "KEPEMILIKAN WAJIB KTP : ";
				break;
			case 19:
				$_SESSION['id_asuransi'] = $nomor; $pre = "JENIS ASURANSI : ";
				break;
			case 'covid':
				$_SESSION['status_covid'] = $nomor; $pre = "STATUS COVID : ";
				break;
			case 'bantuan_penduduk':
				$_SESSION['penerima_bantuan'] = $nomor; $pre = "PENERIMA BANTUAN (PENDUDUK) : ";
				break;
		}
		$judul = $this->penduduk_model->get_judul_statistik($tipe, $nomor, $sex);
		// Laporan wajib KTP berbeda - menampilkan sebagian dari penduduk, jadi selalu perlu judul
		if ($judul['nama'] or $tipe = 18)
		{
			$_SESSION['judul_statistik'] = $pre.$judul['nama'];
		}
		else
		{
			unset($_SESSION['judul_statistik']);
		}
		redirect('penduduk');
	}

	public function lap_statistik($id_cluster = 0, $tipe = 0, $nomor = 0)
	{
		$this->clear_session();
		$cluster = $this->penduduk_model->get_cluster($id_cluster);
		switch ($tipe)
		{
			case 1:
				$_SESSION['sex'] = '1';
				$_SESSION['dusun'] = $cluster['dusun'];
				$_SESSION['rw'] = $cluster['rw'];
				$_SESSION['rt'] = $cluster['rt'];
				$pre = "JENIS KELAMIN LAKI-LAKI  ";
				break;
			case 2:
				$_SESSION['sex'] = '2';
				$_SESSION['dusun'] = $cluster['dusun'];
				$_SESSION['rw'] = $cluster['rw'];
				$_SESSION['rt'] = $cluster['rt'];
				$pre = "JENIS KELAMIN PEREMPUAN ";
				break;
			case 3:
				$_SESSION['umur_min'] = '0';
				$_SESSION['umur_max'] = '0';
				$_SESSION['dusun'] = $cluster['dusun'];
				$_SESSION['rw'] = $cluster['rw'];
				$_SESSION['rt'] = $cluster['rt'];
				$pre = "BERUMUR <1 ";
				break;
			case 4:
				$_SESSION['umur_min'] = '1';
				$_SESSION['umur_max'] = '5';
				$_SESSION['dusun'] = $cluster['dusun'];
				$_SESSION['rw'] = $cluster['rw'];
				$_SESSION['rt'] = $cluster['rt'];
				$pre = "BERUMUR 1-5 ";
				break;
			case 5:
				$_SESSION['umur_min'] = '6';
				$_SESSION['umur_max'] = '12';
				$_SESSION['dusun'] = $cluster['dusun'];
				$_SESSION['rw'] = $cluster['rw'];
				$_SESSION['rt'] = $cluster['rt'];
				$pre = "BERUMUR 6-12 ";
				break;
			case 6:
				$_SESSION['umur_min'] = '13';
				$_SESSION['umur_max'] = '15';
				$_SESSION['dusun'] = $cluster['dusun'];
				$_SESSION['rw'] = $cluster['rw'];
				$_SESSION['rt'] = $cluster['rt'];
				$pre = "BERUMUR 13-16 ";
				break;
			case 7:
				$_SESSION['umur_min'] = '16';
				$_SESSION['umur_max'] = '18';
				$_SESSION['dusun'] = $cluster['dusun'];
				$_SESSION['rw'] = $cluster['rw'];
				$_SESSION['rt'] = $cluster['rt'];
				$pre = "BERUMUR 16-18 ";
				break;
			case 8:
				$_SESSION['umur_min'] = '61';
				$_SESSION['dusun'] = $cluster['dusun'];
				$_SESSION['rw'] = $cluster['rw'];
				$_SESSION['rt'] = $cluster['rt'];
				$pre = "BERUMUR >60";
				break;
			case 91: case 92: case 93: case 94:
			case 95: case 96: case 97:
				$kode_cacat = $tipe - 90;
				$_SESSION['cacat'] = $kode_cacat;
				$_SESSION['dusun'] = $cluster['dusun'];
				$_SESSION['rw'] = $cluster['rw'];
				$_SESSION['rt'] = $cluster['rt'];
				$stat = $this->penduduk_model->get_judul_statistik(9, $kode_cacat, NULL);
				$pre = $stat['nama'];
				break;
			case 10:
				$_SESSION['menahun'] = '90';
				$_SESSION['sex'] = '1';
				$_SESSION['dusun'] = $cluster['dusun'];
				$_SESSION['rw'] = $cluster['rw'];
				$_SESSION['rt'] = $cluster['rt'];
				$pre = "SAKIT MENAHUN LAKI-LAKI ";
				break;
			case 11:
				$_SESSION['menahun'] = '90';
				$_SESSION['sex'] = '2';
				$_SESSION['dusun'] = $cluster['dusun'];
				$_SESSION['rw'] = $cluster['rw'];
				$_SESSION['rt'] = $cluster['rt'];
				$pre = "SAKIT MENAHUN PEREMPUAN ";
				break;
			case 12:
				$_SESSION['hamil'] = '1';
				$_SESSION['dusun'] = $cluster['dusun'];
				$_SESSION['rw'] = $cluster['rw'];
				$_SESSION['rt'] = $cluster['rt'];
				$pre = "HAMIL ";
				break;
		}

		if ($pre)
		{
			$_SESSION['judul_statistik'] = $pre;
		}
		else
		{
			unset($_SESSION['judul_statistik']);
		}
		redirect("penduduk");
	}

	public function autocomplete()
	{
		$data = $this->penduduk_model->autocomplete($this->input->post('cari'));
		echo json_encode($data);
	}

}
