<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 *  File ini:
 *
 * Controller untuk modul Teks Berjalan di Web
 *
 * donjo-app/controllers/Teks_berjalan.php
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

class Teks_berjalan extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('teks_berjalan_model');
		$this->load->model('web_artikel_model');
		$this->modul_ini = 13;
		$this->sub_modul_ini = 64;
	}

	public function index()
	{
		$data['main'] = $this->teks_berjalan_model->list_data();

		$this->render('web/teks_berjalan/table', $data);
	}

	public function form($id = '')
	{
		$data['list_artikel'] = $this->web_artikel_model->list_data(999, 6, 0);

		if ($id)
		{
			$data['teks'] = $this->teks_berjalan_model->get_teks($id);
			$data['form_action'] = site_url("teks_berjalan/update/$id");
		}
		else
		{
			$data['teks'] = null;
			$data['form_action'] = site_url("teks_berjalan/insert");
		}

		$this->render('web/teks_berjalan/form', $data);
	}

	public function insert()
	{
		$this->teks_berjalan_model->insert();
		redirect("teks_berjalan");
	}

	public function update($id = '')
	{
		$this->teks_berjalan_model->update($id);
		redirect("teks_berjalan");
	}

	public function delete($id = '')
	{
		$this->redirect_hak_akses('h', "teks_berjalan");
		$this->teks_berjalan_model->delete($id);
		redirect("teks_berjalan");
	}

	public function delete_all()
	{
		$this->redirect_hak_akses('h', "teks_berjalan");
		$this->teks_berjalan_model->delete_all();
		redirect("teks_berjalan");
	}

	public function urut($id = 0, $arah = 0)
	{
		$urut = $this->teks_berjalan_model->urut($id, $arah);
 		redirect("teks_berjalan/index/$page");
	}

	public function lock($id = 0, $val = 1)
	{
		$this->teks_berjalan_model->lock($id, $val);
		redirect("teks_berjalan");
	}
}
