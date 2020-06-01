<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Web extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();

		// Jika offline_mode dalam level yang menyembunyikan website,
		// tidak perlu menampilkan halaman website
		if ($this->setting->offline_mode >= 2)
		{
			redirect('hom_desa');
			exit;
		}

		$this->load->model('header_model');
		$this->load->model('web_artikel_model');
		$this->load->model('web_kategori_model');
		$this->modul_ini = 13;
		$this->sub_modul_ini = 47;
	}

	public function clear()
	{
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		redirect('web');
	}

	public function pager($cat = 1)
	{
		if (isset($_POST['per_page']))
			$_SESSION['per_page'] = $_POST['per_page'];
		redirect("web/index/$cat");
	}

	public function index($cat = 1, $p = 1, $o = 0)
	{
		$data['p'] = $p;
		$data['o'] = $o;
		$data['cat'] = $cat;

		if (isset($_SESSION['cari']))
			$data['cari'] = $_SESSION['cari'];
		else $data['cari'] = '';

		if (isset($_SESSION['filter']))
			$data['filter'] = $_SESSION['filter'];
		else $data['filter'] = '';

		if (isset($_POST['per_page']))
			$_SESSION['per_page'] = $_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];

		$paging = $this->web_artikel_model->paging($cat, $p, $o);
		$data['main'] = $this->web_artikel_model->list_data($cat, $o, $paging->offset, $paging->per_page);
		$data['keyword'] = $this->web_artikel_model->autocomplete();
		$data['list_kategori'] = $this->web_artikel_model->list_kategori();
		$data['kategori'] = $this->web_artikel_model->get_kategori($cat);
		$data['cat'] = $cat;

		$header = $this->header_model->get_data();
		$header['minsidebar'] =1;
		$data = $this->security->xss_clean($data);
		$data['paging'] = $paging;

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('web/artikel/table', $data);
		$this->load->view('footer');
	}

	public function form($cat = 1, $p = 1, $o = 0, $id = '')
	{
		if (!empty($id) and !$this->web_artikel_model->boleh_ubah($id, $_SESSION['user']))
			redirect("web/index/$cat/$p/$o");

		$data['p'] = $p;
		$data['o'] = $o;
		$data['cat'] = $cat;

		if ($id)
		{
			$data['artikel'] = $this->web_artikel_model->get_artikel($id);
			$data['form_action'] = site_url("web/update/$cat/$id/$p/$o");
		}
		else
		{
			$data['artikel'] = null;
			$data['form_action'] = site_url("web/insert/$cat");
		}

		$data['kategori'] = $this->web_artikel_model->get_kategori($cat);
		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('web/artikel/form',$data);
		$this->load->view('footer');
	}

	public function search($cat = 1)
	{
		$cari = $this->input->post('cari');
		if ($cari != '')
			$_SESSION['cari'] = $cari;
		else unset($_SESSION['cari']);
		redirect("web/index/$cat");
	}

	public function filter($cat = 1)
	{
		$filter = $this->input->post('filter');
		if ($filter != 0)
			$_SESSION['filter'] = $filter;
		else unset($_SESSION['filter']);
		redirect("web/index/$cat");
	}

	public function insert($cat = 1)
	{
		$this->web_artikel_model->insert($cat);
		redirect("web/index/$cat");
	}

	public function update($cat = 0, $id = '', $p = 1, $o = 0){
		if (!$this->web_artikel_model->boleh_ubah($id, $_SESSION['user']))
			redirect("web/index/$cat/$p/$o");

		$this->web_artikel_model->update($cat, $id);
		if ($this->session->success == -1)
			redirect("web/form/$cat/$p/$o/$id");
		else
			redirect("web/index/$cat");
	}

	public function delete($cat = 1, $p = 1, $o = 0, $id = '')
	{
		$this->redirect_hak_akses('h', "web/index/$cat/$p/$o");
		$this->web_artikel_model->delete($id);
		redirect("web/index/$cat/$p/$o");
	}

	// Hapus kategori
	public function hapus($cat = 1, $p = 1, $o = 0)
	{
		$this->redirect_hak_akses('h', "web/index/1/$p/$o", 'kategori');
		$this->web_artikel_model->hapus($cat);
		redirect("web/index/1/$p/$o");
	}

	public function delete_all($cat = 1, $p = 1, $o = 0)
	{
		$this->redirect_hak_akses('h', "web/index/$p/$o");
		$this->web_artikel_model->delete_all();
		redirect("web/index/$p/$o");
	}

	public function ubah_kategori_form($id = 0)
	{
		if (!$this->web_artikel_model->boleh_ubah($id, $_SESSION['user']))
			redirect("web/index");

		$data['list_kategori'] = $this->web_kategori_model->list_kategori("kategori");
		$data['form_action'] = site_url("web/update_kategori/$id");
		$data['kategori_sekarang'] = $this->web_artikel_model->get_kategori_artikel($id);
		$this->load->view('web/artikel/ajax_ubah_kategori_form', $data);
	}

	public function update_kategori($id = 0)
	{
		if (!$this->web_artikel_model->boleh_ubah($id, $_SESSION['user']))
			redirect("web/index/$cat");

		$cat = $_POST['kategori'];
		$this->web_artikel_model->update_kategori($id, $cat);
		redirect("web/index/$cat");
	}

	public function artikel_lock($cat = 1, $id = 0)
	{
		// Kontributor tidak boleh mengubah status aktif artikel
		if ($_SESSION['grup'] == 4)
		{
			session_error("Anda tidak mempunyai akses pada fitur ini");
			redirect("web/index/$cat");
		}

		$this->web_artikel_model->artikel_lock($id, 1);
		redirect("web/index/$cat");
	}

	public function artikel_unlock($cat = 1, $id = 0)
	{
		// Kontributor tidak boleh mengubah status aktif artikel
		if ($_SESSION['grup'] == 4)
		{
			session_error("Anda tidak mempunyai akses pada fitur ini");
			redirect("web/index/$cat");
		}

		$this->web_artikel_model->artikel_lock($id, 2);
		redirect("web/index/$cat");
	}

	public function komentar_lock($cat = 1, $id = 0)
	{
		// Kontributor tidak boleh mengubah status komentar artikel
		if ($_SESSION['grup'] == 4)
		{
			session_error("Anda tidak mempunyai akses pada fitur ini");
			redirect("web/index/$cat");
		}

		$this->web_artikel_model->komentar_lock($id, 0);
		redirect("web/index/$cat");
	}

	public function komentar_unlock($cat = 1, $id = 0)
	{
		// Kontributor tidak boleh mengubah status komentar artikel
		if ($_SESSION['grup'] == 4)
		{
			session_error("Anda tidak mempunyai akses pada fitur ini");
			redirect("web/index/$cat");
		}

		$this->web_artikel_model->komentar_lock($id, 1);
		redirect("web/index/$cat");
	}

	public function ajax_add_kategori($cat = 1, $p = 1, $o = 0)
	{
		$data['form_action'] = site_url("web/insert_kategori/$cat/$p/$o");
		$this->load->view('web/artikel/ajax_add_kategori_form', $data);
	}

	public function insert_kategori($cat = 1, $p = 1, $o = 0)
	{
		redirect_hak_akses('u', "web/index/$cat/$p/$o", 'kategori');
		$this->web_artikel_model->insert_kategori();
		redirect("web/index/$cat/$p/$o");
	}

	public function headline($cat = 1, $p = 1, $o = 0, $id = 0)
	{
		// Kontributor tidak boleh melakukan ini
		if ($_SESSION['grup'] == 4)
		{
			session_error("Anda tidak mempunyai akses pada fitur ini");
			redirect("web/index/$cat/$p/$o");
		}

		$this->web_artikel_model->headline($id);
		redirect("web/index/$cat/$p/$o");
	}

	public function slide($cat = 1, $p = 1, $o = 0, $id = 0)
	{
		// Kontributor tidak boleh melakukan ini
		if ($_SESSION['grup'] == 4)
		{
			session_error("Anda tidak mempunyai akses pada fitur ini");
			redirect("web/index/$cat/$p/$o");
		}

		$this->web_artikel_model->slide($id);
		redirect("web/index/$cat/$p/$o");
	}

	public function slider()
	{
		$this->sub_modul_ini = 54;
		$header = $this->header_model->get_data();

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('slider/admin_slider.php');
		$this->load->view('footer');
	}

	public function update_slider()
	{
		// Kontributor tidak boleh melakukan ini
		if ($_SESSION['grup'] == 4)
		{
			session_error("Anda tidak mempunyai akses pada fitur ini");
			redirect("web/slider");
		}

		$this->setting_model->update_slider();
		redirect("web/slider");
	}

	public function teks_berjalan()
	{
		$this->sub_modul_ini = 64;
		$header = $this->header_model->get_data();

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('web/admin_teks_berjalan.php');
		$this->load->view('footer');
	}

	public function update_teks_berjalan()
	{
		// Kontributor tidak boleh melakukan ini
		if ($_SESSION['grup'] == 4)
		{
			session_error("Anda tidak mempunyai akses pada fitur ini");
			redirect("web/teks_berjalan");
		}

		$this->setting_model->update_teks_berjalan();
		redirect("web/teks_berjalan");
	}

	public function reset($cat = 999)
	{
		if($cat == 999)
			$this->web_artikel_model->reset($cat);

		redirect("web/index/$cat");
	}

}
