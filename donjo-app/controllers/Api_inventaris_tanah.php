<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
* User: didikkurniawan
* Date: 10/1/16
* Time: 06:59
*/

class Api_inventaris_tanah extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('inventaris_tanah_model');
	}

	public function add()
	{
		$data = $this->inventaris_tanah_model->add(array(
			'nama_barang' => $this->input->post('nama_barang_save'),
			'kode_barang' => $this->input->post('kode_barang'),
			'register' => $this->input->post('register'),
			'luas' => $this->input->post('luas'),
			'tahun_pengadaan' => $this->input->post('tahun_pengadaan'),
			'letak' => $this->input->post('letak'),
			'hak' => $this->input->post('hak'),
			'no_sertifikat' => $this->input->post('no_sertifikat'),
			'tanggal_sertifikat' => $this->input->post('tanggal_sertifikat'),
			'penggunaan' => $this->input->post('penggunaan'),
			'asal' => $this->input->post('asal'),
			'harga' => $this->input->post('harga'),
			'keterangan' => $this->input->post('keterangan'),
			'visible' => 1,
			'created_by' => $this->session->user,
			'updated_by' => $this->session->user
			));
		if ($data) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
		redirect("inventaris_tanah");
	}

	public function add_mutasi()
	{
		$data = $this->inventaris_tanah_model->add_mutasi(array(
			'id_inventaris_tanah' => $this->input->post('id_inventaris_tanah'),
			'jenis_mutasi' => $this->input->post('mutasi'),
			'tahun_mutasi' => $this->input->post('tahun_mutasi'),
			'harga_jual' => $this->input->post('harga_jual'),
			'sumbangkan' => $this->input->post('sumbangkan'),
			'keterangan' => $this->input->post('keterangan'),
			'visible' => 1,
			'created_by' => $this->session->user,
			'updated_by' => $this->session->user
			));
		if ($data) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
		redirect("inventaris_tanah/mutasi");
	}

	public function update($id)
	{
		$data = $this->inventaris_tanah_model->update($id, array(
			'nama_barang' => $this->input->post('nama_barang_save'),
			'kode_barang' => $this->input->post('kode_barang'),
			'register' => $this->input->post('register'),
			'luas' => $this->input->post('luas'),
			'tahun_pengadaan' => $this->input->post('tahun_pengadaan'),
			'letak' => $this->input->post('letak'),
			'hak' => $this->input->post('hak'),
			'no_sertifikat' => $this->input->post('no_sertifikat'),
			'tanggal_sertifikat' => $this->input->post('tanggal_sertifikat'),
			'penggunaan' => $this->input->post('penggunaan'),
			'asal' => $this->input->post('asal'),
			'harga' => $this->input->post('harga'),
			'keterangan' => $this->input->post('keterangan'),
			'updated_at' => date('Y-m-d H:i:s'),
			'visible' => 1
			));
		if ($data) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
		redirect("inventaris_tanah");
	}

	public function update_mutasi($id)
	{
		$data = $this->inventaris_tanah_model->update_mutasi($id, array(
			'jenis_mutasi' => $this->input->post('mutasi'),
			'tahun_mutasi' => $this->input->post('tahun_mutasi'),
			'harga_jual' => $this->input->post('harga_jual') || null,
			'sumbangkan' => $this->input->post('sumbangkan') || null,
			'keterangan' => $this->input->post('keterangan'),
			'updated_at' => date('Y-m-d H:i:s'),
			'visible' => 1
			));
		if ($data) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
		redirect("inventaris_tanah/mutasi");
	}

	public function delete($id)
	{
		$this->redirect_hak_akses('h', 'inventaris_tanah');
		$data = $this->inventaris_tanah_model->delete($id);
		if ($data) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
		redirect('inventaris_tanah');
	}

	public function delete_mutasi($id)
	{
		$this->redirect_hak_akses('h', "inventaris_tanah/mutasi");
		$data = $this->inventaris_tanah_model->delete_mutasi($id);
		if ($data) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
		redirect("inventaris_tanah/mutasi");
	}
}