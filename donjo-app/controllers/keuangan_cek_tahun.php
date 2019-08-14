<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Keuangan_cek_tahun extends CI_Controller {

	public function cek_tahun()
	{
		$this->load->model('keuangan_grafik_model');
		$data = $this->keuangan_grafik_model->cek_tahun();
		foreach ($data as $key => $d)
        {
            $data[$key]['text'] = $d['tahun_anggaran'];
            $data[$key]['value'] = $d['tahun_anggaran'];
        }
		echo json_encode($data);
	}

}