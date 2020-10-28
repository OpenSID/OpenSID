<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * Controller untuk modul Statistik Kependudukan
 *
 * donjo-app/controllers/statistik.php
 *
 */

/**
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package	OpenSID
 * @author	Tim Pengembang OpenDesa
 * @copyright	Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright	Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license	http://www.gnu.org/licenses/gpl.html	GPL V3
 * @link 	https://github.com/OpenSID/OpenSID
 */

class Statistik extends Admin_Controller {

	private $_list_session;

	public function __construct()
	{
		parent::__construct();
		$this->load->model(['wilayah_model', 'laporan_penduduk_model', 'pamong_model', 'program_bantuan_model', 'config_model', 'referensi_model']);

		$this->_list_session = ['lap', 'order_by', 'dusun', 'rw', 'rt'];
		$this->modul_ini = 3;
		$this->sub_modul_ini = 27;
	}

	public function index()
	{
		$data = $this->get_cluster_session();
		$data['lap'] = $this->session->lap;
		$data['order_by'] = $this->session->order_by;
		$data['main'] = $this->laporan_penduduk_model->list_data($data['lap'], $data['order_by']);
		$data['list_dusun'] = $this->laporan_penduduk_model->list_dusun();
		$data['heading'] = $this->laporan_penduduk_model->judul_statistik($data['lap']);
		$data['stat_penduduk'] = $this->referensi_model->list_ref(STAT_PENDUDUK);
		$data['stat_keluarga'] = $this->referensi_model->list_ref(STAT_KELUARGA);
		$data['stat_kategori_bantuan'] = $this->referensi_model->list_ref(STAT_BANTUAN);
		$data['stat_bantuan'] = $this->program_bantuan_model->list_program(0);
		$data['judul_kelompok'] = "Jenis Kelompok";
		$this->get_data_stat($data, $data['lap']);

		$this->render('statistik/penduduk', $data);
	}

	public function clear($lap = '0', $order_by = '1')
	{
		$this->session->unset_userdata($this->_list_session);
		$this->order_by($lap, $order_by);
	}

	public function order_by($lap = '0', $order_by = '1')
	{
		$this->session->lap = $lap;
		$this->session->order_by = $order_by;

		redirect('statistik');
	}

	private function get_data_stat(&$data, $lap)
	{
		$data['stat'] = $this->laporan_penduduk_model->judul_statistik($lap);
		if ($lap > 50)
		{
			// Untuk program bantuan, $lap berbentuk '50<program_id>'
			$program_id = preg_replace('/^50/', '', $lap);
			$data['program'] = $this->program_bantuan_model->get_sasaran($program_id);
			$data['judul_kelompok'] = $data['program']['judul_sasaran'];
			$data['kategori'] = 'bantuan';
		}
		elseif (in_array($lap, array('bantuan_penduduk', 'bantuan_keluarga')))
		{
			$data['kategori'] = 'bantuan';
		}
		elseif ($lap > 20 OR "$lap" == 'kelas_sosial')
		{
			$data['kategori'] = 'keluarga';
		}
		else
		{
			$data['kategori'] = 'penduduk';
		}
	}

	public function dialog($aksi = '')
	{
		$data['aksi'] = $aksi;
		$data['lap'] = $this->session->lap;
		$data['pamong'] = $this->pamong_model->list_data();
		$data['form_action'] = site_url("statistik/daftar/$aksi/$data[lap]");

		$this->load->view("statistik/ajax_daftar", $data);
	}

	/*
	* $aksi = cetak/unduh
	*/
	public function daftar($aksi = '', $lap = '')
	{
		foreach ($this->_list_session as $list)
		{
			$data[$list] = $this->session->$list;
		}

		$post = $this->input->post();
		$data['aksi'] = $aksi;
		$data['stat'] = $this->laporan_penduduk_model->judul_statistik($lap);
		$data['config'] = $this->header['desa'];
		$data['main'] = $this->laporan_penduduk_model->list_data($lap);
		$data['pamong_ttd'] = $this->pamong_model->get_data($post['pamong_ttd']);
		$data['laporan_no'] = $post['laporan_no'];

		$data['file'] = "Statistik penduduk"; // nama file
		$data['isi'] = "statistik/penduduk_cetak";
		$data['letak_ttd'] = ['1', '1', '1'];

		$this->load->view('global/format_cetak', $data);
	}

	public function rentang_umur()
	{
		$data['lap'] = 13;
		$data['main'] = $this->laporan_penduduk_model->list_data_rentang();
		$data['stat_penduduk'] = $this->referensi_model->list_ref(STAT_PENDUDUK);
		$data['stat_keluarga'] = $this->referensi_model->list_ref(STAT_KELUARGA);
		$data['stat_kategori_bantuan'] = $this->referensi_model->list_ref(STAT_BANTUAN);
		$data['stat_bantuan'] = $this->program_bantuan_model->list_program(0);
		$data['judul_kelompok'] = "Jenis Kelompok";
		$this->get_data_stat($data, $data['lap']);

		$this->render('statistik/rentang_umur', $data);
	}

	public function form_rentang($id = 0)
	{
		if ($id == 0)
		{
			$data['form_action'] = site_url("statistik/rentang_insert");
			$data['rentang'] = $this->laporan_penduduk_model->get_rentang_terakhir();
			$data['rentang']['nama'] = "";
			$data['rentang']['sampai'] = "";
		}
		else
		{
			$data['form_action'] = site_url("statistik/rentang_update/$id");
			$data['rentang'] = $this->laporan_penduduk_model->get_rentang($id);
		}
		$this->load->view('statistik/ajax_rentang_form', $data);
	}

	public function rentang_insert()
	{
		$data['insert'] = $this->laporan_penduduk_model->insert_rentang();
		redirect('statistik/rentang_umur');
	}

	public function rentang_update($id = 0)
	{
		$this->laporan_penduduk_model->update_rentang($id);
		redirect('statistik/rentang_umur');
	}

	public function rentang_delete($id = 0)
	{
		$this->redirect_hak_akses('h');
		$this->laporan_penduduk_model->delete_rentang($id);
		redirect('statistik/rentang_umur');
	}

	public function delete_all_rentang()
	{
		$this->redirect_hak_akses('h');
		$this->laporan_penduduk_model->delete_all_rentang();
		redirect('statistik/rentang_umur');
	}

	public function dusun($lap = 0)
	{
		if ($lap) $this->session->lap = $lap;

		$this->session->unset_userdata(['rw', 'rt']);
		$dusun = $this->input->post('dusun');
		if ($dusun != "")
			$this->session->dusun = $dusun;
		else $this->session->unset_userdata('dusun');

		redirect('statistik');
	}

	public function rw($lap = 0)
	{
		if ($lap) $this->session->lap = $lap;

		$this->session->unset_userdata('rt');
		$rw = $this->input->post('rw');
		if ($rw != "")
			$this->session->rw = $rw;
		else $this->session->unset_userdata('rw');
		redirect('statistik');
	}

	public function rt($lap = 0)
	{
		if ($lap) $this->session->lap = $lap;

		$rt = $this->input->post('rt');
		if ($rt != "")
			$this->session->rt = $rt;
		else $this->session->unset_userdata('rt');
		redirect('statistik');
	}

	private function get_cluster_session()
	{
		foreach ($this->_list_session as $list)
		{
			if (in_array($list, ['dusun', 'rw', 'rt']))
				$$list = $this->session->$list;
		}

		if (isset($dusun))
		{
			$data['dusun'] = $dusun;
			$data['list_rw'] = $this->wilayah_model->list_rw($dusun);

			if (isset($rw))
			{
				$data['rw'] = $rw;
				$data['list_rt'] = $this->wilayah_model->list_rt($dusun, $rw);

				if (isset($rt))
					$data['rt'] = $rt;
				else $data['rt'] = '';
			}
			else $data['rw'] = '';
		}
		else
		{
			$data['dusun'] = $data['rw'] = $data['rt'] = '';
		}

		return $data;
	}

	public function load_chart_gis($lap = 0)
	{
		$data = $this->get_cluster_session();
		$data['main'] = $this->laporan_penduduk_model->list_data($lap);
		$data['lap'] = $lap;
		$this->get_data_stat($data, $lap);
		$this->load->view('gis/penduduk_gis', $data);
	}

	public function chart_gis_desa($lap = 0, $desa = '' )
	{
		($desa) ? $this->session->set_userdata('desa', ununderscore($desa)) : $this->session->unset_userdata('desa');
		$this->session->unset_userdata('dusun');
		$this->session->unset_userdata('rw');
		$this->session->unset_userdata('rt');

		redirect("statistik/load_chart_gis/$lap");
	}

	public function chart_gis_dusun($lap = 0, $dusun = '' )
	{
		($dusun) ? $this->session->set_userdata('dusun', ununderscore($dusun)) : $this->session->unset_userdata('dusun');
		$this->session->unset_userdata('rw');
		$this->session->unset_userdata('rt');

		redirect("statistik/load_chart_gis/$lap");
	}

	public function chart_gis_rw($lap = 0, $dusun = '', $rw = '' )
	{
		($dusun) ? $this->session->set_userdata('dusun', ununderscore($dusun)) : $this->session->unset_userdata('dusun');
		($rw) ? $this->session->set_userdata('rw', ununderscore($rw)) : $this->session->unset_userdata('rw');
		$this->session->unset_userdata('rt');

		redirect("statistik/load_chart_gis/$lap");
	}

	public function chart_gis_rt($lap = 0, $dusun = '', $rw = '', $rt = '' )
	{
		($dusun) ? $this->session->set_userdata('dusun', ununderscore($dusun)) : $this->session->unset_userdata('dusun');
		($rw) ? $this->session->set_userdata('rw', ununderscore($rw)) : $this->session->unset_userdata('rw');
		($rt) ? $this->session->set_userdata('rt', ununderscore($rt)) : $this->session->unset_userdata('rt');

		redirect("statistik/load_chart_gis/$lap");
	}

	public function ajax_peserta_program_bantuan()
	{
		$peserta = $this->program_bantuan_model->get_peserta_bantuan();
		$data = array();
		$no = $_POST['start'];

		foreach ($peserta as $baris)
		{
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $baris['program'];
			$row[] = $baris['peserta'];
			$row[] = $baris['alamat'];
			$data[] = $row;
		}

		$output = array(
			"recordsTotal" => $this->program_bantuan_model->count_peserta_bantuan_all(),
			"recordsFiltered" => $this->program_bantuan_model->count_peserta_bantuan_filtered(),
			'data' => $data
		);
		echo json_encode($output);
	}
}
