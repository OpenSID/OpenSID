<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
* User: didikkurniawan
* Date: 10/1/16
* Time: 06:59
*/

class Api_inventaris_kontruksi extends CI_Controller
{
    function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('user_model');
		$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
		if($grup!=1 AND $grup!=2) {
			$_SESSION['request_uri'] = $_SERVER['REQUEST_URI'];
			redirect('siteman');
		}
		$this->load->model('inventaris_kontruksi_model');
		$this->modul_ini = 16;
		$this->tab_ini = 6;
		$this->controller = 'inventaris_kontruksi';
	}

    public function add()
    {
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
                'visible' => 1
                ));
            echo json_encode($data);
    }

    public function add_mutasi()
    {
            $data = $this->inventaris_kontruksi_model->add_mutasi(array(
                'id_inventaris_kontruksi' => $this->input->post('id_inventaris_kontruksi'),
                'jenis_mutasi' => $this->input->post('jenis_mutasi'),
                'tahun_mutasi' => $this->input->post('tahun_mutasi'),
                'harga_jual' => $this->input->post('harga_jual'),
                'sumbangkan' => $this->input->post('sumbangkan'),
                'keterangan' => $this->input->post('keterangan'),
                'visible' => 1
                ));
            echo json_encode($data);
    }

    public function update($id)
    {
            $data = $this->inventaris_kontruksi_model->update($id,array(
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
                'updated_at' => date("m/d/Y")
                ));
            echo json_encode($data);
    }

    public function update_mutasi($id)
    {
            $data = $this->inventaris_kontruksi_model->update_mutasi($id,array(
                'jenis_mutasi' => $this->input->post('jenis_mutasi'),
                'tahun_mutasi' => $this->input->post('tahun_mutasi'),
                'harga_jual' => $this->input->post('harga_jual'),
                'sumbangkan' => $this->input->post('sumbangkan'),
                'keterangan' => $this->input->post('keterangan'),
                'updated_at' => date("m/d/Y")
                ));
            echo json_encode($data);
    }

    public function delete($id)
    {
        json_encode($this->inventaris_kontruksi_model->delete($id));

        redirect('inventaris_kontruksi');
    }

    public function delete_mutasi($id)
    {
        json_encode($this->inventaris_kontruksi_model->delete_mutasi($id));

        redirect('inventaris_kontruksi/mutasi');
    }
}