<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');
	define('MAX_ANGGOTA_F101', 10);
	$this->load->model('keluarga_model');
	$this->load->model('pamong_model');
	$this->load->model('config_model');
	$anggota = $this->keluarga_model->list_anggota($individu['id_kk']);
	$desa = $this->config_model->get_data();
	$kepala_desa = $this->pamong_model->get_pamong_by_nama($desa['nama_kepala_desa']);
?>