<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
* User: didikkurniawan
* Date: 10/1/16
* Time: 06:59
*/

class Api_inventaris_asset extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('inventaris_asset_model');
	}

	public function add()
	{
		$data = $this->inventaris_asset_model->add(array(
			'nama_barang' => $this->input->post('nama_barang_save'),
			'kode_barang' => $this->input->post('kode_barang'),
			'register' => $this->input->post('nomor_register'),
			'jenis' => $this->input->post('jenis_asset'),
			'judul_buku' => $this->input->post('judul'),
			'spesifikasi_buku' => $this->input->post('spesifikasi'),
			'asal_daerah' => $this->input->post('asal_kesenian'),
			'pencipta' => $this->input->post('pencipta_kesenian'),
			'bahan' => $this->input->post('bahan_kesenian'),
			'jenis_hewan' => $this->input->post('jenis_hewan'),
			'ukuran_hewan' => $this->input->post('ukuran_hewan'),
			'jenis_tumbuhan' => $this->input->post('jenis_tumbuhan'),
			'ukuran_tumbuhan' => $this->input->post('ukuran_tumbuhan'),
			'jumlah' => $this->input->post('jumlah'),
			'tahun_pengadaan' => $this->input->post('tahun'),
			'asal' => $this->input->post('asal_usul'),
			'harga' => $this->input->post('harga'),
			'keterangan' => $this->input->post('keterangan'),
			'visible' => 1,
			'created_by' => $this->session->user,
			'updated_by' => $this->session->user
			));
		if ($data) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
		redirect("inventaris_asset");
	}

	public function add_mutasi()
	{
		$data = $this->inventaris_asset_model->add_mutasi(array(
			'id_inventaris_asset' => $this->input->post('id_inventaris_asset'),
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
		redirect("inventaris_asset/mutasi");
	}

	public function update($id)
	{
		$data = $this->inventaris_asset_model->update($id,array(
			'nama_barang' => $this->input->post('nama_barang_save'),
			'kode_barang' => $this->input->post('kode_barang'),
			'register' => $this->input->post('register'),
			'jenis' => $this->input->post('jenis_asset'),
			'judul_buku' => $this->input->post('judul'),
			'spesifikasi_buku' => $this->input->post('spesifikasi'),
			'asal_daerah' => $this->input->post('asal_kesenian'),
			'pencipta' => $this->input->post('pencipta_kesenian'),
			'bahan' => $this->input->post('bahan_kesenian'),
			'jenis_hewan' => $this->input->post('jenis_hewan'),
			'ukuran_hewan' => $this->input->post('ukuran_hewan'),
			'jenis_tumbuhan' => $this->input->post('jenis_tumbuhan'),
			'ukuran_tumbuhan' => $this->input->post('ukuran_tumbuhan'),
			'jumlah' => $this->input->post('jumlah'),
			'tahun_pengadaan' => $this->input->post('tahun'),
			'asal' => $this->input->post('asal_usul'),
			'harga' => $this->input->post('harga'),
			'keterangan' => $this->input->post('keterangan'),
			'updated_at' => date('Y-m-d H:i:s')
			));
		if ($data) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
		redirect("inventaris_asset");
	}

	public function update_mutasi($id)
	{
		$data = $this->inventaris_asset_model->update_mutasi($id, array(
			'jenis_mutasi' => $this->input->post('mutasi'),
			'tahun_mutasi' => $this->input->post('tahun_mutasi'),
			'harga_jual' => $this->input->post('harga_jual') || null,
			'sumbangkan' => $this->input->post('sumbangkan') || null,
			'keterangan' => $this->input->post('keterangan'),
			'updated_at' => date('Y-m-d H:i:s')
			));
		if ($data) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
		redirect("inventaris_asset/mutasi");
	}

	public function delete($id)
	{
		$this->redirect_hak_akses('h', 'inventaris_asset');
		$data = $this->inventaris_asset_model->delete($id);
		if ($data) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
		redirect('inventaris_asset');
	}

	public function delete_mutasi($id)
	{
		$this->redirect_hak_akses('h', 'inventaris_asset/mutasi');
		$data = $this->inventaris_asset_model->delete_mutasi($id);
		if ($data) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
		redirect('inventaris_asset/mutasi');
	}
}