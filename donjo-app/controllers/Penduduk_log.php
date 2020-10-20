<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 *  File ini:
 *
 * Controller untuk modul Kependudukan > Penduduk
 *
 * donjo-app/controllers/Penduduk_log.php
 *
 */
/*
 *  File ini bagian dari:
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

class Penduduk_log extends Admin_Controller {

	private $set_page;
	private $list_session;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('referensi_model');
		$this->load->model('penduduk_model');
		$this->load->model('penduduk_log_model');

		$this->modul_ini = 2;
		$this->sub_modul_ini = 21;
		$this->set_page = ['20', '50', '100'];
		$this->list_session = ['status_dasar', 'sex', 'agama', 'dusun', 'rw', 'rt', 'cari'];
	}

	public function clear()
	{
		$this->session->unset_userdata($this->list_session);
		$this->session->per_page = 20;
		redirect('penduduk_log');
	}

	public function index($p = 1, $o = 0)
	{
		$data['p'] = $p;
		$data['o'] = $o;

		foreach ($this->list_session as $list)
		{
			if (in_array($list, ['dusun', 'rw', 'rt']))
				$$list = $this->session->$list;
			else
				$data[$list] = $this->session->$list ?: '';
		}

		if (isset($dusun))
		{
			$data['dusun'] = $dusun;
			$data['list_rw'] = $this->penduduk_model->list_rw($dusun);

			if (isset($rw))
			{
				$data['rw'] = $rw;
				$data['list_rt'] = $this->penduduk_model->list_rt($dusun, $rw);

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

		// Hanya tampilkan penduduk yang status dasarnya bukan 'HIDUP'
		$this->session->log = 1;

		$per_page = $this->input->post('per_page');
		if (isset($per_page))
			$this->session->per_page = $per_page;

		$data['func'] = 'index';
		$data['per_page'] = $this->session->per_page;
		$data['set_page'] = $this->set_page;
		$data['paging'] = $this->penduduk_log_model->paging($p, $o);
		$data['main'] = $this->penduduk_log_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->penduduk_model->autocomplete();
		$data['list_status_dasar'] = $this->referensi_model->list_data('tweb_status_dasar');
		$data['list_sex'] = $this->referensi_model->list_data('tweb_penduduk_sex');
		$data['list_agama'] = $this->referensi_model->list_data('tweb_penduduk_agama');
		$data['list_dusun'] = $this->penduduk_model->list_dusun();
		$this->set_minsidebar(1);
		$this->render('penduduk_log/penduduk_log', $data);
	}

	public function filter($filter)
	{
		$value = $this->input->post($filter);
		if ($value != '')
			$this->session->$filter = $value;
		else $this->session->unset_userdata($filter);
		redirect('penduduk_log');
	}

	public function dusun()
	{
		$this->session->unset_userdata(['rw', 'rt']);
		$dusun = $this->input->post('dusun');
		if ($dusun != "")
			$this->session->dusun = $dusun;
		else $this->session->unset_userdata('dusun');
		redirect('penduduk_log');
	}

	public function rw()
	{
		$this->session->unset_userdata('rt');
		$rw = $this->input->post('rw');
		if ($rw != "")
			$this->session->rw = $rw;
		else $this->session->unset_userdata('rw');
		redirect('penduduk_log');
	}

	public function rt()
	{
		$rt = $this->input->post('rt');
		if ($rt != "")
			$this->session->rt = $rt;
		else $this->session->unset_userdata('rt');
		redirect('penduduk_log');
	}

	public function edit($p = 1, $o = 0, $id = 0)
	{
		$data['log_status_dasar'] = $this->penduduk_log_model->get_log($id);
		$data['list_ref_pindah'] = $this->referensi_model->list_data('ref_pindah');
		$data['form_action'] = site_url("penduduk_log/update/$p/$o/$id");
		$this->load->view('penduduk_log/ajax_edit', $data);
	}

	public function update($p = 1, $o = 0, $id = '')
	{
		$this->penduduk_log_model->update($id);
		redirect("penduduk_log/index/$p/$o");
	}

	public function kembalikan_status($id_log)
	{
		unset($_SESSION['success']);
		$this->penduduk_log_model->kembalikan_status($id_log);
		redirect("penduduk_log");
	}

	public function kembalikan_status_all()
	{
		$this->penduduk_log_model->kembalikan_status_all();
		redirect("penduduk_log");
	}

	public function cetak($o = 0, $aksi = '', $privasi_nik = 0)
	{
		$data['main'] = $this->penduduk_log_model->list_data($o, 0);
		if ($privasi_nik == 1) $data['privasi_nik'] = true;
		$this->load->view("penduduk_log/penduduk_log_$aksi", $data);
	}

	public function ajax_cetak($o = 0, $aksi = '')
	{
		$data["o"] = $o;
		$data['aksi'] = $aksi;
		$data['form_action'] = site_url("penduduk_log/cetak/$o/$aksi");
		$data['form_action_privasi'] = site_url("penduduk_log/cetak/$o/$aksi/1");
		$this->load->view("sid/kependudukan/ajax_cetak_bersama", $data);
	}
}
