<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mailbox_web extends Web_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('mailbox_model');
		$this->load->model('mandiri_model');
		
		if (!isset($_SESSION['mandiri'])) {
			redirect('first');
		}
	}

	public function index()
	{
		redirect('first/mandiri/1/3');
	}

	public function form()
	{
		if (!empty($subjek = $this->input->post('subjek'))) {
			$data['subjek'] = $subjek;
		}

		$data['individu'] = $this->mandiri_model->get_pendaftar_mandiri($_SESSION['nik']);
		$data['form_action'] = site_url("mailbox_web/kirim_pesan");
		$data['views_partial_layout'] = "web/mandiri/mailbox_form";

		$this->load->view('web/mandiri/layout.mandiri.php', $data);
	}

	public function kirim_pesan()
	{
		$this->mailbox_model->insert_web();
		$this->session->unset_userdata('success');
		redirect('first/mandiri/1/3/2');
	}

	public function baca_pesan($kat = 1, $id)
	{		
		$this->mailbox_model->baca($id, 1);
		
		$data['kat'] = $kat;
		$data['owner'] = $kat == 1 ? 'Pengirim' : 'Penerima';
		$data['pesan'] = $this->mailbox_model->get_pesan($id);
		$data['tipe_mailbox'] = $this->mailbox_model->get_kat_nama($kat);  
		$data['views_partial_layout'] = "web/mandiri/mailbox_detail";

		$this->load->view('web/mandiri/layout.mandiri.php', $data);
	}

	public function baca($id = '', $baca)
	{
		$this->mailbox_model->baca($id, $baca);
		redirect("first/mandiri/1/3");
	}
}
