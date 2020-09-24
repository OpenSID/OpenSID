<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 *  File ini:
 *
 * Controller untuk modul Layanan Mandiri
 *
 * donjo-app/controllers/Mandiri.php
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

class Mandiri extends Admin_Controller {

	private $kembali;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('mandiri_model');

		$this->modul_ini = 14;
		$this->sub_modul_ini = 56;
		$this->kembali = $_SERVER['HTTP_REFERER'];
	}

	public function clear()
	{
		unset($_SESSION['cari']);
		redirect('mandiri');
	}

	public function index($p = 1, $o = 0)
	{
		$data['p'] = $p;
		$data['o'] = $o;

		if (isset($_SESSION['cari']))
			$data['cari'] = $_SESSION['cari'];
		else $data['cari'] = '';

		if (isset($_SESSION['filter']))
			$data['filter'] = $_SESSION['filter'];
		else $data['filter'] = '';

		if (isset($_POST['per_page']))
			$_SESSION['per_page'] = $_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];

		$data['paging'] = $this->mandiri_model->paging($p, $o);
		$data['main'] = $this->mandiri_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->mandiri_model->autocomplete();

		$this->render('mandiri/mandiri', $data);
	}

	public function search()
	{
		$cari = $this->input->post('cari');
		if ($cari != '')
			$_SESSION['cari']=$cari;
		else unset($_SESSION['cari']);
		redirect('mandiri');
	}

	public function ajax_pin($id_pend = '')
	{
		if ($id_pend)
		{
			$data['penduduk'] = $this->mandiri_model->get_penduduk($id_pend);
			$data['id_pend'] = $id_pend;
			$data['form_action'] = site_url("mandiri/update/$id_pend");
		}
		else
		{
			$data['penduduk'] = $this->mandiri_model->list_penduduk();
			$data['id_pend'] = NULL;
			$data['form_action'] = site_url("mandiri/insert");
		}
		$this->load->view('mandiri/ajax_pin', $data);
	}

	public function insert()
	{
		$pin = $this->mandiri_model->insert();

		status_sukses($pin); //Tampilkan Pesan

		$_SESSION['pin'] = $pin;
		redirect('mandiri');
	}

	public function update($id_pend)
	{
		$pin = $this->mandiri_model->update($id_pend);

		status_sukses($pin); //Tampilkan Pesan

		$_SESSION['pin'] = $pin;
		redirect('mandiri');
	}

	public function delete($id = '')
	{
		$this->redirect_hak_akses('h', $this->kembali);
		$this->mandiri_model->delete($id);
		redirect($this->kembali);
	}
}
