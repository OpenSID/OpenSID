<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
* User: didikkurniawan
* Date: 10/1/16
* Time: 06:59
*/

class Api_inventaris_peralatan extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('inventaris_peralatan_model');
	}

	public function add()
	{
		$data = $this->inventaris_peralatan_model->add(array(
			'nama_barang' => $this->input->post('nama_barang_save'),
			'kode_barang' => $this->input->post('kode_barang'),
			'register' => $this->input->post('register'),
			'merk' => $this->input->post('merk'),
			'ukuran' => $this->input->post('ukuran'),
			'bahan' => $this->input->post('bahan'),
			'tahun_pengadaan' => $this->input->post('tahun_pengadaan'),
			'no_pabrik' => $this->input->post('no_pabrik'),
			'no_rangka' => $this->input->post('no_rangka'),
			'no_mesin' => $this->input->post('no_mesin'),
			'no_polisi' => $this->input->post('no_polisi'),
			'no_bpkb' => $this->input->post('no_bpkb'),
			'asal' => $this->input->post('asal'),
			'harga' => $this->input->post('harga'),
			'keterangan' => $this->input->post('keterangan'),
			'visible' => 1,
			'created_by' => $this->session->user,
			'updated_by' => $this->session->user
			));
		if ($data) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
		redirect("inventaris_peralatan");
	}

	public function add_mutasi()
	{
		$data = $this->inventaris_peralatan_model->add_mutasi(array(
			'id_inventaris_peralatan' => $this->input->post('id_inventaris_peralatan'),
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
		redirect("inventaris_peralatan/mutasi");
	}

	public function update($id)
	{
		$data = $this->inventaris_peralatan_model->update($id, array(
			'nama_barang' => $this->input->post('nama_barang_save'),
			'kode_barang' => $this->input->post('kode_barang'),
			'register' => $this->input->post('register'),
			'merk' => $this->input->post('merk'),
			'ukuran' => $this->input->post('ukuran'),
			'bahan' => $this->input->post('bahan'),
			'tahun_pengadaan' => $this->input->post('tahun_pengadaan'),
			'no_pabrik' => $this->input->post('no_pabrik'),
			'no_rangka' => $this->input->post('no_rangka'),
			'no_mesin' => $this->input->post('no_mesin'),
			'no_polisi' => $this->input->post('no_polisi'),
			'no_bpkb' => $this->input->post('no_bpkb'),
			'asal' => $this->input->post('asal'),
			'harga' => $this->input->post('harga'),
			'keterangan' => $this->input->post('keterangan'),
			'updated_at' => date('Y-m-d H:i:s')
			));
		if ($data) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
		redirect("inventaris_peralatan");
	}

	public function update_mutasi($id)
	{
		$data = $this->inventaris_peralatan_model->update_mutasi($id, array(
			'jenis_mutasi' => $this->input->post('mutasi'),
			'tahun_mutasi' => $this->input->post('tahun_mutasi'),
			'harga_jual' => $this->input->post('harga_jual') || null,
			'sumbangkan' => $this->input->post('sumbangkan') || null,
			'keterangan' => $this->input->post('keterangan'),
			'updated_at' => date('Y-m-d H:i:s')
			));
		if ($data) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
		redirect("inventaris_peralatan/mutasi");
	}

	public function delete($id)
	{
		$this->redirect_hak_akses('h', 'inventaris_peralatan');
		$data = $this->inventaris_peralatan_model->delete($id);
		if ($data) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
		redirect('inventaris_peralatan');
	}

	public function delete_mutasi($id)
	{
		$this->redirect_hak_akses('h', 'inventaris_peralatan/mutasi');
		$data = $this->inventaris_peralatan_model->delete_mutasi($id);
		if ($data) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
		redirect('inventaris_peralatan/mutasi');
	}
}