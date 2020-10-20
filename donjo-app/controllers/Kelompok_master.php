<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * File ini:
 *
 * Controller untuk modul Kelompok
 *
 * donjo-app/controllers/Kelompok_master.php
 *
 */
/*
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

class Kelompok_master extends Admin_Controller {

	private $_set_page;
	private $_list_session;

	public function __construct()
	{
		parent::__construct();
		$this->load->model(['kelompok_master_model']);
		$this->modul_ini = 2;
		$this->sub_modul_ini = 24;
		$this->_set_page = ['20', '50', '100'];
		$this->_list_session = ['cari', 'filter'];
	}

	public function clear()
	{
		$this->session->unset_userdata($this->_list_session);
		$this->session->per_page = $this->_set_page[0];
		redirect('kelompok_master');
	}

	public function index($p = 1, $o = 0)
	{
		$data['p'] = $p;
		$data['o'] = $o;

		foreach ($this->_list_session as $list)
		{
			$data[$list] = $this->session->$list ?: '';
		}

		$per_page = $this->input->post('per_page');
		if (isset($per_page))
			$this->session->per_page = $per_page;

		$data['func'] = 'index';
		$data['set_page'] = $this->_set_page;
		$data['paging'] = $this->kelompok_master_model->paging($p, $o);
		$data['main'] = $this->kelompok_master_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->kelompok_master_model->autocomplete();

		$this->set_minsidebar(1);
		$this->render('kelompok_master/table', $data);
	}

	public function form($id = '')
	{
		if ($id)
		{
			$data['kelompok_master'] = $this->kelompok_master_model->get_kelompok_master($id);
			$data['form_action'] = site_url("kelompok_master/update/$id");
		}
		else
		{
			$data['kelompok_master'] = NULL;
			$data['form_action'] = site_url("kelompok_master/insert");
		}

		$this->set_minsidebar(1);
		$this->render('kelompok_master/form', $data);
	}

	public function filter($filter)
	{
		$value = $this->input->post($filter);
		if ($value != "")
			$this->session->$filter = $value;
		else $this->session->unset_userdata($filter);
		redirect('kelompok_master');
	}

	public function insert()
	{
		$this->kelompok_master_model->insert();
		redirect('kelompok_master');
	}

	public function update($id = '')
	{
		$this->kelompok_master_model->update($id);
		redirect('kelompok_master');
	}

	public function delete($id = '')
	{
		$this->redirect_hak_akses('h');
		$this->kelompok_master_model->delete($id);
		redirect('kelompok_master');
	}

	public function delete_all()
	{
		$this->redirect_hak_akses('h');
		$this->kelompok_master_model->delete_all();
		redirect('kelompok_master');
	}

}
