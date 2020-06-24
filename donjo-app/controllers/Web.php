<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Web extends Admin_Controller {

	private $_header;
	private $_set_page;

	public function __construct()
	{
		parent::__construct();

		// Jika offline_mode dalam level yang menyembunyikan website,
		// tidak perlu menampilkan halaman website
		if ($this->setting->offline_mode >= 2)
		{
			redirect('hom_desa');
			exit;
		}

		$this->load->model(['header_model', 'web_artikel_model', 'web_kategori_model']);
		$this->_header = $this->header_model->get_data();
		$this->_set_page = ['20', '50', '100'];
		$this->modul_ini = 13;
		$this->sub_modul_ini = 47;
	}

	public function clear()
	{
		$this->session->unset_userdata(['cari, status']);
		$this->session->per_page = $this->_set_page[0];
		redirect('web');
	}

	public function index($cat = 0, $p = 1, $o = 0)
	{
		$cat = $this->session->kategori;

		if(!$cat) $cat = 0;

		$data['p'] = $p;
		$data['o'] = $o;

		$data['cat'] = $cat;
		$data['cari'] = $this->session->cari ?: '';
		$data['status'] = $this->session->status ?: '';

		$per_page = $this->input->post('per_page');
		if (isset($per_page))
			$this->session->per_page = $per_page;

		$data['func'] = "index/$cat";
		$data['per_page'] = $this->session->per_page;
		$data['set_page'] = $this->_set_page;

		$paging = $this->web_artikel_model->paging($cat, $p, $o);
		$data['main'] = $this->web_artikel_model->list_data($cat, $o, $paging->offset, $paging->per_page);
		$data['keyword'] = $this->web_artikel_model->autocomplete($cat);
		$data['list_kategori'] = $this->web_artikel_model->list_kategori();
		$data['kategori'] = $this->web_artikel_model->get_kategori($cat);
		$data = $this->security->xss_clean($data);
		$data['paging'] = $paging;
		$this->_header['minsidebar'] =1;

		$this->load->view('header', $this->_header);
		$this->load->view('nav');
		$this->load->view('web/artikel/table', $data);
		$this->load->view('footer');
	}

	public function tab($cat = 0)
	{
		$this->session->kategori = $cat;

		redirect('web');
	}

	public function form($cat = 1, $p = 1, $o = 0, $id = 0)
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
		$this->_header['minsidebar'] = 1;

		$this->load->view('header', $this->_header);
		$this->load->view('nav');
		$this->load->view('web/artikel/form',$data);
		$this->load->view('footer');
	}

	public function filter($filter, $cat = 1)
	{
		$value = $this->input->post($filter);
		if ($value != '')
			$this->session->$filter = $value;
		else $this->session->unset_userdata($filter);
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

		$this->load->view('header', $this->_header);
		$this->load->view('nav');
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

		$this->load->view('header', $this->_header);
		$this->load->view('nav');
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
		if ($cat == 999)
			$this->web_artikel_model->reset($cat);

		redirect("web/index/$cat");
	}

}
