<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 *  File ini:
 *
 * Controller untuk modul Widget di Web
 *
 * donjo-app/controllers/Web_widget.php
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

class Web_widget extends Admin_Controller {

	private $set_page;
	private $list_session;

	public function __construct()
	{
		parent::__construct();
		// Jika offline_mode dalam level yang menyembunyikan website,
		// tidak perlu menampilkan halaman website
		if ($this->setting->offline_mode >= 2)
		{
			redirect('hom_sid');
			exit;
		}

		$this->load->model(['web_widget_model']);
		$this->modul_ini = 13;
		$this->sub_modul_ini = 48;
		$this->set_page = ['20', '50', '100'];
		$this->list_session = ['cari', 'filter'];
	}

	public function clear()
	{
		$this->session->unset_userdata($this->list_session);
		$this->session->per_page = $this->set_page[0];
		redirect('web_widget');
	}

	public function index($page = 0, $o = 0)
	{
		$per_page = $this->input->post('per_page');
		if (isset($per_page))
			$this->session->per_page = $per_page;

		$data['cari'] = $this->session->cari ?: '';
		$data['filter'] = $this->session->filter ?: '';
		$data['func'] = 'index';
		$data['set_page'] = $this->set_page;
		$data['per_page'] = $this->session->per_page;
		$data['paging'] = $this->web_widget_model->paging($page, $o);
		$data['p'] = $data['paging']->page;
		$data['o'] = $o;

		$data['main'] = $this->web_widget_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->web_widget_model->autocomplete($this->input->post('cari'));

		$this->session->page = $data['p'];
		$this->session->urut_range = array(
			'min' => $data['main'][0]['urut'],
			'max' => $data['main'][count($data['main'])-1]['urut']
		);

		$this->render('web/artikel/widget', $data);
	}

	public function form($p = 1, $o = 0, $id = '')
	{
		$data['p'] = $p;
		$data['o'] = $o;

		$data['list_widget'] = $this->web_widget_model->list_widget_baru();

		if ($id)
		{
			$data['widget'] = $this->web_widget_model->get_widget($id);
			$data['form_action'] = site_url("web_widget/update/$id/$p/$o");
		}
		else
		{
			$data['widget'] = null;
			$data['form_action'] = site_url("web_widget/insert");
		}

		$this->render('web/artikel/widget-form', $data);
	}

	public function filter($filter)
	{
		$value = $this->input->post($filter);
		if ($value != '')
			$this->session->$filter = $value;
		else $this->session->unset_userdata($filter);
		redirect('web_widget');
	}

	public function admin($widget)
	{
		$this->set_minsidebar(1);
		$data['form_action'] = site_url("web_widget/update_setting/".$widget);
		$data['setting'] = $this->web_widget_model->get_setting($widget);

		$this->render('widgets/admin_'.$widget, $data);
	}

	public function update_setting($widget)
	{
		$setting = $this->input->post('setting');
		$this->web_widget_model->update_setting($widget, $setting);
		redirect("web_widget/admin/$widget");
	}

	public function insert()
	{
		$this->web_widget_model->insert();
		redirect("web_widget");
	}

	public function update($id = '', $p = 1, $o = 0)
	{
		$this->web_widget_model->update($id);
		redirect("web_widget");
	}

	public function delete($p = 1, $o = 0, $id = '')
	{
		$this->redirect_hak_akses('h', "web_widget");
		$this->web_widget_model->delete($id);
		redirect("web_widget");
	}

	public function delete_all($p = 1,$o = 0)
	{
		$this->redirect_hak_akses('h', "web_widget");
		$this->web_widget_model->delete_all();
		redirect("web_widget");
	}

	public function urut($id = 0, $arah = 0)
	{
		$urut = $this->web_widget_model->urut($id, $arah);
		$range = $this->session->urut_range;
		$page = $this->session->page;

		if ($urut <= 0);
		elseif ($urut < $range['min'])
		{
			$page--;
		}
		elseif ($urut > $range['max'])
		{
			$page++;
		}

 		redirect("web_widget/index/$page");
	}

	public function lock($id = 0)
	{
		$this->web_widget_model->lock($id, 1);
		redirect("web_widget");
	}

	public function unlock($id = 0)
	{
		$this->web_widget_model->lock($id, 2);
		redirect("web_widget");
	}
}
