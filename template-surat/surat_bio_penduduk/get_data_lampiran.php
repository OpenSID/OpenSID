<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');
	define('MAX_ANGGOTA_F101', 10);
	$this->load->model('keluarga_model');
	$this->load->model('pamong_model');
	$anggota = $this->keluarga_model->list_anggota($individu['id_kk']);

	$id = $this->input->post('pamong_id');
	$kepala_desa = $this->pamong_model->get_pamong($id);
?>
