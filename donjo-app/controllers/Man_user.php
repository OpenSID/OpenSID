<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Man_user extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('header_model');
		$this->load->model('referensi_model');
		$this->modul_ini = 11;
		$this->sub_modul_ini = 44;
	}

	public function clear()
	{
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		redirect('man_user');
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

		$data['paging'] = $this->user_model->paging($p, $o);
		$data['main'] = $this->user_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->user_model->autocomplete();
		$header = $this->header_model->get_data();

		$data['user_group'] = $this->referensi_model->list_data("user_grup");

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('man_user/manajemen_user_table', $data);
		$this->load->view('footer');
	}

	public function form($p = 1, $o = 0, $id = '')
	{
		$data['p'] = $p;
		$data['o'] = $o;

		if ($id)
		{
			$data['user'] = $this->user_model->get_user($id);
			$data['form_action'] = site_url("man_user/update/$p/$o/$id");
		}
		else
		{
			$data['user'] = NULL;
			$data['form_action'] = site_url("man_user/insert");
		}

		$data['user_group'] = $this->referensi_model->list_data("user_grup");
		$header = $this->header_model->get_data();

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('man_user/manajemen_user_form', $data);
		$this->load->view('footer');
	}

	public function search()
	{
		$cari = $this->input->post('cari');
		if ($cari != '')
			$_SESSION['cari'] = $cari;
		else unset($_SESSION['cari']);
		redirect('man_user');
	}

	public function filter()
	{
		$filter = $this->input->post('filter');
		if ($filter != 0)
			$_SESSION['filter'] = $filter;
		else unset($_SESSION['filter']);
		redirect('man_user');
	}

	public function insert()
	{
		$this->set_form_validation();

		if ($this->form_validation->run() !== true)
		{
			$this->session->success = -1;
			$this->session->error_msg = trim(validation_errors());
			redirect("man_user/form/$p/$o");
		}
		else
		{
			$this->user_model->insert();
			redirect('man_user');
		}
	}

	private function set_form_validation()
	{
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('password', 'Kata Sandi Baru', 'required|callback_syarat_sandi');
		$this->form_validation->set_message('syarat_sandi','Harus 6 sampai 20 karakter dan sekurangnya berisi satu angka dan satu huruf besar dan satu huruf kecil');
	}

	// Kata sandi harus 6 sampai 20 karakter dan sekurangnya berisi satu angka dan satu huruf besar dan satu huruf kecil
	public function syarat_sandi($str)
	{
		// radiisi berarti tidak sandi tidak diubah
		if (preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,20}$/', $str) or $str == 'radiisi')
			return TRUE;
		else
			return FALSE;
	}

	public function update($p = 1, $o = 0, $id = '')
	{
		$this->set_form_validation();

		if ($this->form_validation->run() !== true)
		{
			$this->session->success = -1;
			$this->session->error_msg = trim(validation_errors());
			redirect("man_user/form/$p/$o/$id");
		}
		else
		{
			$this->user_model->update($id);
			redirect("man_user/index/$p/$o");
		}
	}

	public function delete($p = 1, $o = 0, $id = '')
	{
		$this->redirect_hak_akses('h', "man_user/index/$p/$o");
		$this->user_model->delete($id);
		redirect("man_user/index/$p/$o");
	}

	public function delete_all($p = 1, $o = 0)
	{
		$this->redirect_hak_akses('h', "man_user/index/$p/$o");
		$this->user_model->delete_all();
		redirect("man_user/index/$p/$o");
	}

	public function user_lock($id = '')
	{
		$this->user_model->user_lock($id, 0);
		redirect("man_user/index/$p/$o");
	}

	public function user_unlock($id = '')
	{
		$this->user_model->user_lock($id, 1);
		redirect("man_user/index/$p/$o");
	}

}
