<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Permohonan_surat extends Web_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('penduduk_model');
		$this->load->model('keluarga_model');
		$this->load->model('surat_model');
		$this->load->model('keluar_model');
		$this->load->model('config_model');
		$this->load->model('referensi_model');
		$this->load->model('penomoran_surat_model');
		$this->load->model('permohonan_surat_model');
	}

	public function form($id_permohonan='')
	{
		$data = $this->input->post();
		$data_permohonan = array('data_permohonan' => array(
			'keterangan' => $data['keterangan'],
			'no_hp_aktif' => $data['no_hp_aktif'],
			'syarat' => $data['syarat']));
		$this->session->set_userdata($data_permohonan);

		if ($id_permohonan)
		{
			$data['permohonan'] = $this->permohonan_surat_model->get_permohonan($id_permohonan);
			$data['isian_form'] = json_encode($this->permohonan_surat_model->ambil_isi_form($data['permohonan']['isian_form']));
			$data['id_surat'] = $data['permohonan']['id_surat'];
		}
		$surat = $this->db->where('id', $data['id_surat'])
			->get('tweb_surat_format')
			->row_array();
		$data['url'] = $surat['url_surat'];
		$url = $data['url'];

		$data['list_dokumen'] = $this->penduduk_model->list_dokumen($_SESSION['id']);
		$data['individu'] = $this->surat_model->get_penduduk($_SESSION['id']);
		$data['anggota'] = $this->keluarga_model->list_anggota($data['individu']['id_kk']);
		$data['penduduk'] = $this->penduduk_model->get_penduduk($_SESSION['id']);
		$this->get_data_untuk_form($url, $data);

		$data['surat_url'] = rtrim($_SERVER['REQUEST_URI'], "/clear");
		$data['form_action'] = site_url("surat/cetak/$url");
		$data['views_partial_layout'] = "surat/form_surat.php";
		$data['data'] = $data;
		$this->load->view('web/mandiri/layout.mandiri.php', $data);
	}

	public function kirim($id_permohonan='')
	{
		$post = $this->input->post();
		$data = array();
		$data['id_pemohon'] = $post['nik'];
		$data['id_surat'] = $post['id_surat'];
		$data['isian_form'] = json_encode($post);
		$data_permohonan = $this->session->userdata('data_permohonan');
		$data['keterangan'] = $data_permohonan['keterangan'];
		$data['no_hp_aktif'] = $data_permohonan['no_hp_aktif'];
		$data['syarat'] = json_encode($data_permohonan['syarat']);
		if ($id_permohonan)
		{
			$data['status'] = 0; // kembalikan ke status 'sedang diperiksa'
			$this->permohonan_surat_model->update($id_permohonan, $data);
		}
		else
			$this->permohonan_surat_model->insert($data);
		redirect('first/mandiri/1/21');
	}

	private function get_data_untuk_form($url, &$data)
	{
		$this->load->model('pamong_model');
		$data['surat_terakhir'] = $this->surat_model->get_last_nosurat_log($url);
		$data['surat'] = $this->surat_model->get_surat($url);
		$data['input'] = $this->input->post();
		$data['input']['nomor'] = $data['surat_terakhir']['no_surat_berikutnya'];
		$data['format_nomor_surat'] = $this->penomoran_surat_model->format_penomoran_surat($data);
		$data['lokasi'] = $this->config_model->get_data();
		$data['pamong'] = $this->surat_model->list_pamong();
		$pamong_ttd = $this->pamong_model->get_ttd();
		$pamong_ub = $this->pamong_model->get_ub();
		$data_form = $this->surat_model->get_data_form($url);
		if (is_file($data_form))
			include($data_form);
	}

	public function batalkan($id)
	{
		$this->permohonan_surat_model->update_status($id, array('status' => 9));
		redirect('first/mandiri/1/21');
	}

}