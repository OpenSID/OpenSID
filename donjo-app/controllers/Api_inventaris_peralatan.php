<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
* User: didikkurniawan
* Date: 10/1/16
* Time: 06:59
*/

class Api_inventaris_peralatan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
		session_start();
		$this->load->model('user_model');
		$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
		if($grup!=1 AND $grup!=2) {
			$_SESSION['request_uri'] = $_SERVER['REQUEST_URI'];
			redirect('siteman');
		}
		$this->load->model('inventaris_peralatan_model');
		$this->modul_ini = 16;
		$this->tab_ini = 2;
		$this->controller = 'Inventaris_peralatan';
    }
    public function add()
    {
            $data = $this->inventaris_peralatan_model->add(array(
                'nama_barang' => $this->input->post('nama_barang'),
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
                'visible' => 1
                ));
            echo json_encode($data);
    }

    public function add_mutasi()
    {
            $data = $this->inventaris_peralatan_model->add_mutasi(array(
                'id_inventaris_peralatan' => $this->input->post('id_inventaris_peralatan'),
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
            $data = $this->inventaris_peralatan_model->update($id,array(
                'nama_barang' => $this->input->post('nama_barang'),
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
                'updated_at' => date("m/d/Y")
                ));
            echo json_encode($data);
    }

    public function update_mutasi($id)
    {
            $data = $this->inventaris_peralatan_model->update_mutasi($id,array(
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
        json_encode($this->inventaris_peralatan_model->delete($id));
        redirect('inventaris_peralatan');

    }

    public function delete_mutasi($id)
    {
        json_encode($this->inventaris_peralatan_model->delete_mutasi($id));
        redirect('inventaris_peralatan/mutasi');
    }
}