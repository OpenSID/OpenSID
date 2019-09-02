<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

	class Asuransi extends Admin_Controller {
		
		public function __construct()
		{
			parent::__construct();
			session_start();
			$this->load->model('asuransi_model');
			$this->load->model('header_model');
		}

		public function index()
		{
			$data['main'] = $this->asuransi_model->get_all();
			$header = $this->header_model->get_data();
			$nav['act'] = 2;
			$nav['act_sub'] = 201;
			$header['minsidebar'] = 1;
			$this->load->view('header', $header);
			$this->load->view('nav', $nav);
			$this->load->view('asuransi/tabel', $data);
			$this->load->view('footer');
		}

		public function form()
		{
			$data['form_action'] = site_url("asuransi/create");
			$this->load->view('asuransi/form', $data);
		}

		public  function create()
		{
			$data = [
				'nama' => $this->input->post('nama_asuransi')
			];
			$this->asuransi_model->create($data);
			redirect('asuransi');
		}

		public function edit()
		{
			$id = $this->uri->segment(3);
			$data['asuransi'] = $this->asuransi_model->get_data($id);
			$data['form_action'] = site_url("asuransi/ubah");
			
			//$this->load->view('header', $header);
			//$this->load->view('nav', $nav);
			$this->load->view('asuransi/form', $data);
			//$this->load->view('footer');
		}

		public function ubah()
		{
			$id = $this->input->post('id_asuransi');
			$nama = $this->input->post('nama_asuransi');

			$ubah = $this->asuransi_model->update($id,$nama);
			redirect('asuransi');
		}

		public function delete()
		{
			$id = $this->uri->segment(3);
			$this->asuransi_model->delete($id);
			redirect('asuransi');
		}

		public function delete_all()
		{
			$this->asuransi_model->delete_all();
			redirect('asuransi');
		}

	}
?>