<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
* User: didikkurniawan
* Date: 10/1/16
* Time: 06:59
*/

class Api_inventaris_kontruksi extends Admin_Controller
{
	function __construct(){
		parent::__construct();
		$this->load->model('inventaris_kontruksi_model');
	}

	public function add()
	{
		$this->redirect_hak_akses('u');
		$data = $this->inventaris_kontruksi_model->add(array(
			'nama_barang' => $this->input->post('nama_barang'),
			'kondisi_bangunan' => $this->input->post('fisik_bangunan'),
			'kontruksi_bertingkat' => $this->input->post('tingkat'),
			'kontruksi_beton' => $this->input->post('bahan'),
			'luas_bangunan' => $this->input->post('luas_bangunan'),
			'letak' => $this->input->post('alamat'),
			'no_dokument' => $this->input->post('no_bangunan'),
			'tanggal_dokument' => $this->input->post('tanggal_bangunan'),
			'tanggal' => $this->input->post('tanggal_mulai'),
			'status_tanah' => $this->input->post('status_tanah'),
			'kode_tanah' => $this->input->post('kode_tanah'),
			'asal' => $this->input->post('asal'),
			'harga' => $this->input->post('harga'),
			'keterangan' => $this->input->post('keterangan'),
			'visible' => 1,
			'created_by' => $this->session->user,
			'updated_by' => $this->session->user
			));
		if ($data) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
		redirect("inventaris_kontruksi");
	}

	public function update($id)
	{
		$this->redirect_hak_akses('u');
		$data = $this->inventaris_kontruksi_model->update($id, array(
			'nama_barang' => $this->input->post('nama_barang'),
			'kondisi_bangunan' => $this->input->post('fisik_bangunan'),
			'kontruksi_bertingkat' => $this->input->post('tingkat'),
			'kontruksi_beton' => $this->input->post('bahan'),
			'luas_bangunan' => $this->input->post('luas_bangunan'),
			'letak' => $this->input->post('alamat'),
			'no_dokument' => $this->input->post('no_bangunan'),
			'tanggal_dokument' => $this->input->post('tanggal_bangunan'),
			'tanggal' => $this->input->post('tanggal_mulai'),
			'status_tanah' => $this->input->post('status_tanah'),
			'kode_tanah' => $this->input->post('kode_tanah'),
			'asal' => $this->input->post('asal'),
			'harga' => $this->input->post('harga'),
			'keterangan' => $this->input->post('keterangan'),
			'updated_at' => date('Y-m-d H:i:s')
			));
		if ($data) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
		redirect("inventaris_kontruksi");
	}

	public function delete($id)
	{
		$this->redirect_hak_akses('h', 'inventaris_kontruksi');
		$data = $this->inventaris_kontruksi_model->delete($id);
		if ($data) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
		redirect('inventaris_kontruksi');
	}
}