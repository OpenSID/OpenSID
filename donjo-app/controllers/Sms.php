<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 *  File ini:
 *
 * Controller untuk modul SMS
 *
 * donjo-app/controllers/Sms.php
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

class Sms extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('sms_model');

		$this->load->model('penduduk_model');
		$this->load->model('referensi_model');
		$this->modul_ini = 10;
		$this->sub_modul_ini = 39;
	}

	public function clear()
	{
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		unset($_SESSION['cari1']);
		unset($_SESSION['sex1']);
		unset($_SESSION['dusun1']);
		unset($_SESSION['rw1']);
		unset($_SESSION['rt1']);
		unset($_SESSION['agama1']);
		unset($_SESSION['pekerjaan1']);
		unset($_SESSION['status1']);
		unset($_SESSION['pendidikan1']);
		unset($_SESSION['status_penduduk1']);
		unset($_SESSION['TextDecoded1']);
		unset($_SESSION['grup1']);
		redirect('sms');
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
		$data['paging'] = $this->sms_model->paging($p, $o);
		$data['main'] = $this->sms_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->sms_model->autocomplete();

		$this->render('sms/manajemen_sms_table', $data);

		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		unset($_SESSION['cari1']);
		unset($_SESSION['sex1']);
		unset($_SESSION['dusun1']);
		unset($_SESSION['rw1']);
		unset($_SESSION['rt1']);
		unset($_SESSION['agama1']);
		unset($_SESSION['pekerjaan1']);
		unset($_SESSION['status1']);
		unset($_SESSION['pendidikan1']);
		unset($_SESSION['status_penduduk1']);
		unset($_SESSION['TextDecoded1']);
		unset($_SESSION['grup1']);
	}

	public function setting($p = 1, $o = 0)
	{
		$this->sub_modul_ini = 41;

		$data['main'] = $this->sms_model->get_autoreply();
		$data['form_action'] = site_url("sms/insert_autoreply");

		$this->render('sms/setting', $data);
	}

	public function insert_autoreply()
	{
		$this->sms_model->insert_autoreply();
		redirect('sms/setting');
	}

	public function outbox($p = 1, $o = 0)
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
		$data['paging'] = $this->sms_model->paging_terkirim($p, $o);
		$data['main'] = $this->sms_model->list_data_terkirim($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->sms_model->autocomplete();

		$this->render('sms/create_sms', $data);

		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		unset($_SESSION['cari1']);
		unset($_SESSION['sex1']);
		unset($_SESSION['dusun1']);
		unset($_SESSION['rw1']);
		unset($_SESSION['rt1']);
		unset($_SESSION['agama1']);
		unset($_SESSION['pekerjaan1']);
		unset($_SESSION['status1']);
		unset($_SESSION['pendidikan1']);
		unset($_SESSION['status_penduduk1']);
		unset($_SESSION['TextDecoded1']);
		unset($_SESSION['grup1']);
	}

	public function sentitem($p = 1, $o = 0)
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
		$data['paging'] = $this->sms_model->paging_terkirim($p, $o);
		$data['main'] = $this->sms_model->list_data_terkirim($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->sms_model->autocomplete();

		$this->render('sms/berita_terkirim', $data);

		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		unset($_SESSION['cari1']);
		unset($_SESSION['sex1']);
		unset($_SESSION['dusun1']);
		unset($_SESSION['rw1']);
		unset($_SESSION['rt1']);
		unset($_SESSION['agama1']);
		unset($_SESSION['pekerjaan1']);
		unset($_SESSION['status1']);
		unset($_SESSION['pendidikan1']);
		unset($_SESSION['status_penduduk1']);
		unset($_SESSION['TextDecoded1']);
		unset($_SESSION['grup1']);
	}

	public function pending($p = 1, $o = 0)
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
		$data['paging'] = $this->sms_model->paging_tertunda($p, $o);
		$data['main'] = $this->sms_model->list_data_tertunda($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->sms_model->autocomplete();

		$this->render('sms/pesan_tertunda', $data);

		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		unset($_SESSION['cari1']);
		unset($_SESSION['sex1']);
		unset($_SESSION['dusun1']);
		unset($_SESSION['rw1']);
		unset($_SESSION['rt1']);
		unset($_SESSION['agama1']);
		unset($_SESSION['pekerjaan1']);
		unset($_SESSION['status1']);
		unset($_SESSION['pendidikan1']);
		unset($_SESSION['status_penduduk1']);
		unset($_SESSION['TextDecoded1']);
		unset($_SESSION['grup1']);
	}

	public function form($p = 1, $o = 0, $tipe = 0, $id = 0)
	{
		$data['p'] = $p;
		$data['o'] = $o;

		if ($id)
		{
			$data['sms'] = $this->sms_model->get_sms($tipe, $id);
			$data['form_action'] = site_url("sms/insert/$tipe");
			$data['tipe']['tipe'] = $tipe;
			$data['grup'] = $this->sms_model->list_grup();
			$data['kontak'] = $this->sms_model->list_kontak();
			$this->load->view('sms/ajax_sms_form', $data);
		}
		else
		{
			$data['sms'] = null;
			$data['form_action'] = site_url("sms/insert/$tipe");
			$data['tipe']['tipe'] = $tipe;
			$data['grup'] = $this->sms_model->list_grup();
			$data['kontak'] = $this->sms_model->list_kontak();
			$this->load->view('sms/ajax_sms_form_kirim', $data);
		}
	}

	public function carikontak($tipe = 0)
	{
		if (isset($_POST['TextDecoded']))
			$data['text']['TextDecoded'] = $_POST['TextDecoded'];

		$data['text']['TextDecoded'] = null;
		$data['form_action'] = site_url("sms/formaftercari/0/0/$tipe");
		$data['kontak'] = $this->sms_model->list_kontak();
		$this->load->view('sms/ajax_sms_form_cari', $data);
	}

	public function formaftercari($tipe = 0)
	{
		$data['sms']['DestinationNumber'] = $_POST['kontak'];
		$data['sms']['TextDecoded'] = $_POST['text'];
		$data['form_action'] = site_url("sms/insert/$tipe");

		$data['tipe']['tipe'] = $tipe;
		$data['grup'] = $this->sms_model->list_grup();
		$this->load->view('sms/ajax_sms_form', $data);
	}

	public function send_broadcast()
	{
		$data['input'] = $_POST;

		if (isset($_SESSION['cari1']))
			$data['cari1'] = $_SESSION['cari1'];
		else $data['cari1'] = '';

		if (isset($_SESSION['sex1']))
			$data['sex1'] = $_SESSION['sex1'];
		else $data['sex1'] = '';

		if (isset($_SESSION['dusun1']))
		{
			$data['dusun1'] = $_SESSION['dusun1'];
			$data['list_rw1'] = $this->penduduk_model->list_rw($data['dusun1']);

			if (isset($_SESSION['rw1']))
			{
				$data['rw1'] = $_SESSION['rw1'];
				$data['list_rt1'] = $this->penduduk_model->list_rt($data['dusun1'], $data['rw11']);

			if (isset($_SESSION['rt1']))
				$data['rt1'] = $_SESSION['rt1'];
			else $data['rt1'] = '';
			}
			else $data['rw1'] = '';
		}
		else $data['dusun1'] = '';

		if (isset($_SESSION['agama1']))
			$data['agama1'] = $_SESSION['agama1'];
		else $data['agama1'] = '';

		if (isset($_SESSION['pekerjaan1']))
			$data['pekerjaan1'] = $_SESSION['pekerjaan1'];
		else $data['pekerjaan1'] = '';

		if (isset($_SESSION['status1']))
			$data['status1'] = $_SESSION['status1'];
		else $data['status1'] = '';

		if (isset($_SESSION['pendidikan1']))
			$data['pendidikan1'] = $_SESSION['pendidikan1'];
		else $data['pendidikan1'] = '';

		if (isset($_SESSION['status_penduduk1']))
			$data['status_penduduk1'] = $_SESSION['status_penduduk1'];
		else $data['status_penduduk1'] = '';

		if (isset($_SESSION['TextDecoded1']))
			$data['TextDecoded1'] = $_SESSION['TextDecoded1'];
		else $data['TextDecoded1'] = '';

		if (isset($_SESSION['grup1']))
			$data['grup'] = $_SESSION['grup1'];
		else $data['grup1'] = '';

		$data['insert'] = $this->sms_model->send_broadcast($data);
		redirect('sms/outbox');
	}

	public function broadcast_proses()
	{
		$post = $this->input->post();
		$adv_search['umur_min1'] = bilangan($post['umur_min1']);
		$adv_search['umur_max1'] = bilangan($post['umur_max1']);
		$adv_search['sex1'] = $post['sex1'];
		$adv_search['pekerjaan1'] = $post['pekerjaan1'];
		$adv_search['status1'] = $post['status1'];
		$adv_search['agama1'] = $post['agama1'];
		$adv_search['pendidikan1'] = $post['pendidikan1'];
		$adv_search['status_penduduk1'] = $post['status_penduduk1'];
		$adv_search['dusun1'] = $post['dusun1'];
		$adv_search['grup1'] = $post['grup1'];
		$adv_search['TextDecoded1'] = htmlentities($post['TextDecoded1']);
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
				UNSET($adv_search[$col[$i]]);
			else
				$_SESSION[$col[$i]] = $adv_search[$col[$i]];
		}

		redirect('sms/send_broadcast');
	}

	public function broadcast()
	{
		$data['dusun'] = $this->penduduk_model->list_dusun();
		$data['agama'] = $this->referensi_model->list_data('tweb_penduduk_agama');
		$data['pendidikan'] = $this->penduduk_model->list_pendidikan_kk();
		$data['pekerjaan'] = $this->penduduk_model->list_pekerjaan();
		$data['grup'] = $this->sms_model->list_grup_kontak();
		$data['form_action'] = site_url("sms/broadcast_proses");
		$this->load->view('sms/ajax_broadcast_form', $data);
	}

	public function ajax_penduduk_rw($dusun = '')
	{
		$rw = $this->penduduk_model->list_rw($dusun);
		echo"<div class='form-group'>
			<label for='rw'>RW</label>
			<select class='form-control input-sm' name='rw' onchange=RWSel('".rawurlencode($dusun)."',this.value)>
				<option value=''>Pilih RW</option>";
				foreach ($rw as $data)
				{
					echo "<option>".$data['rw']."</option>";
				}
			echo"</select>
		</div>";
	}

	public function ajax_penduduk_rt($dusun = '', $rw = '')
	{
		$rt = $this->penduduk_model->list_rt($dusun, $rw);
		echo"<div class='form-group'>
			<label for='rt'>RT</label>
			<select class='form-control input-sm' name='rt'>
				<option value=''>Pilih RT</option>";
				foreach ($rt as $data)
				{
					echo "<option value=".$data['rt'].">".$data['rt']."</option>";
				}
			echo"</select>
		</div>";
	}

	public function search()
	{
		$cari = $this->input->post('cari');
		if ($cari != '')
			$_SESSION['cari'] = $cari;
		else unset($_SESSION['cari']);

		redirect('sms');
	}

	public function search_kontak()
	{
		$cari = $this->input->post('cari_kontak');
		if ($cari != '')
			$_SESSION['cari_kontak'] = $cari;
		else unset($_SESSION['cari_kontak']);
		redirect('sms/kontak');
	}

	public function search_grup()
	{
		$cari = $this->input->post('cari_grup');
		if ($cari != '')
			$_SESSION['cari_grup'] = $cari;
		else unset($_SESSION['cari_grup']);
		redirect('sms/group');
	}

	public function search_anggota($id = 0)
	{
		$cari = $this->input->post('cari_anggota');

		if ($cari != '')
			$_SESSION['cari_anggota'] = $cari;
		else unset($_SESSION['cari_anggota']);
		redirect("sms/anggota/$id");
	}

	public function filter()
	{
		$filter = $this->input->post('filter');
		if ($filter != 0)
			$_SESSION['filter'] = $filter;
		else unset($_SESSION['filter']);
		redirect('sms');
	}

	public function insert($tipe = 0)
	{
		$this->sms_model->insert();
		if ($tipe == 1) redirect('sms');
		elseif ($tipe == 2) redirect('sms/sentitem');
		elseif ($tipe == 3) redirect('sms/pending');
		else redirect('sms/outbox');
	}

	public function update($id = '', $p = 1, $o = 0)
	{
		$this->sms_model->update($id);
		redirect("sms/index/$p/$o");
	}

	public function delete($p = 1, $o = 0, $tipe = 0, $id = '')
	{
		$this->redirect_hak_akses('h', 'sms/outbox');
		$this->sms_model->delete($tipe, $id);
		if ($tipe == 1) redirect('sms');
		elseif ($tipe == 2) redirect('sms/sentitem');
		elseif ($tipe == 3) redirect('sms/pending');
		else redirect('sms/outbox');
	}

	public function delete_all($p = 1, $o = 0, $tipe = 0)
	{
		$this->redirect_hak_akses('h', 'sms/outbox');
		$this->sms_model->delete_all($tipe);
		if ($tipe == 1) redirect('sms');
		elseif ($tipe == 2) redirect('sms/sentitem');
		elseif ($tipe == 3) redirect('sms/pending');
		else redirect('sms/outbox');
	}

	public function sms_lock($id = '')
	{
		$this->sms_model->sms_lock($id, 0);
		redirect("sms/index/$p/$o");
	}

	public function sms_unlock($id = '')
	{
		$this->sms_model->sms_lock($id, 1);
		redirect("sms/index/$p/$o");
	}

	public function kontak($p = 1, $o = 0)
	{
		$this->sub_modul_ini = 40;

		$data['p'] = $p;
		$data['o'] = $o;

		if (isset($_SESSION['cari_kontak']))
			$data['cari_kontak'] = $_SESSION['cari_kontak'];
		else $data['cari_kontak'] = '';

		if (isset($_SESSION['filter']))
			$data['filter'] = $_SESSION['filter'];
		else $data['filter'] = '';

		if (isset($_POST['per_page']))
			$_SESSION['per_page'] = $_POST['per_page'];

		$data['per_page'] = $_SESSION['per_page'];
		$data['paging'] = $this->sms_model->paging_kontak($p, $o);
		$data['main'] = $this->sms_model->list_data_kontak($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->sms_model->autocomplete();

		$this->render('sms/kontak', $data);

		unset($_SESSION['cari_kontak']);
	}

	public function form_kontak($id = 0)
	{
		if ($id == 0)
		{
			$data['nama'] = $this->sms_model->list_nama();
			$data['form_action'] = site_url("sms/kontak_insert");
			$this->load->view('sms/ajax_kontak_form', $data);
		}
		else
		{
			$data['form_action'] = site_url("sms/kontak_update");
			$data['kontak'] = $this->sms_model->get_kontak($id);
			$this->load->view('sms/ajax_kontak_form_edit', $data);
		}
	}

	public function kontak_insert()
	{
		$data = $_POST;
		$this->sms_model->insert_kontak($data);
		redirect('sms/kontak');
	}

	public function kontak_update()
	{
		$data = $_POST;
		$this->sms_model->update_kontak($data);
		redirect('sms/kontak');
	}

	public function kontak_delete($id = 0)
	{
		$this->redirect_hak_akses('h', 'sms/kontak');
		$data['hapus'] = $this->sms_model->delete_kontak($id);
		redirect('sms/kontak');
	}

	public function delete_all_kontak()
	{
		$this->redirect_hak_akses('h', 'sms/kontak');
		$this->sms_model->delete_all_kontak();
		redirect('sms/kontak');
	}

	public function group($p = 1, $o = 0)
	{
		$this->sub_modul_ini = 40;

		$data['p'] = $p;
		$data['o'] = $o;

		if (isset($_SESSION['cari_grup']))
			$data['cari_grup'] = $_SESSION['cari_grup'];
		else $data['cari_grup'] = '';

		if (isset($_POST['per_page']))
			$_SESSION['per_page'] = $_POST['per_page'];

		$data['per_page'] = $_SESSION['per_page'];
		$data['paging'] = $this->sms_model->paging_grup($p, $o);
		$data['main'] = $this->sms_model->list_data_grup($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->sms_model->autocomplete();

		$this->render('sms/group', $data);

		unset($_SESSION['cari_grup']);
	}

	public function form_grup($id = 0)
	{
		if ($id == "0")
		{
			$data['form_action'] = site_url("sms/grup_insert");
			$data['grup']['nama_grup'] = "";
		}
		else
		{
			$data['form_action'] = site_url("sms/grup_update");
			$data['grup'] = $this->sms_model->get_grup($id);
		}
		$this->load->view('sms/ajax_grup_form', $data);
	}

	public function grup_insert()
	{
		$data['input'] = $_POST;
		$data['insert'] = $this->sms_model->insert_grup($data);
		redirect('sms/group');
	}

	public function grup_update()
	{
		$data['input'] = $_POST;
		$data['update'] = $this->sms_model->update_grup($data);
		redirect('sms/group');
	}

	public function grup_delete($id = 0)
	{
		$this->redirect_hak_akses('h', 'sms/group');
		$data['hapus'] = $this->sms_model->delete_grup($id);
		redirect('sms/group');
	}

	public function delete_all_grup()
	{
		$this->redirect_hak_akses('h', 'sms/group');
		$this->sms_model->delete_all_grup();
		redirect('sms/group');
	}

	public function anggota($id = 0, $p = 1, $o = 0)
	{
		$this->sub_modul_ini = 40;

		$data['p'] = $p;
		$data['o'] = $o;

		if (isset($_SESSION['cari_anggota']))
			$data['cari_anggota'] = $_SESSION['cari_anggota'];
		else $data['cari_anggota'] = '';

		if (isset($_POST['per_page']))
			$_SESSION['per_page'] = $_POST['per_page'];

		$data['per_page'] = $_SESSION['per_page'];
		$data['paging'] = $this->sms_model->paging_anggota($id, $p, $o);
		$data['main'] = $this->sms_model->list_data_anggota($id, $o, $data['paging']->offset, $data['paging']->per_page);
		$data['grup']['nama_grup'] = $id;
		$data['keyword'] = $this->sms_model->autocomplete();

		$this->render('sms/group_detail', $data);

		unset($_SESSION['cari_anggota']);
	}

	public function form_anggota($id = 0)
	{
		$data['form_action'] = site_url("sms/anggota_insert/$id");
		$data['main'] = $this->sms_model->list_data_nama($id);
		$data['id_grup'] = $id;
		$this->load->view('sms/ajax_anggota_form', $data);
	}

	public function anggota_insert($grup)
	{
		$data['insert'] = $this->sms_model->insert_anggota($grup);
		redirect("sms/anggota/$grup");
	}

	public function anggota_delete($id = 0)
	{
		$this->redirect_hak_akses('h', $this->controller);
		$data['hapus'] = $this->sms_model->delete_anggota($id);
		echo "<script>self.history.back();</script>";
	}

	public function delete_all_anggota($grup = 0)
	{
		$this->redirect_hak_akses('h', $this->controller);
		$this->sms_model->delete_all_anggota($grup);
		echo "<script>self.history.back();</script>";
	}

}
