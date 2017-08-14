<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');?>

<?php

	$this->load->model('keluarga_model');
	$this->load->model('pamong_model');
	$suami = $this->get_data_suami($individu['id']);
	$desa = $this->keluarga_model->get_desa();
	// Gunakan data identitas desa, jika ada
	if ($desa['nip_kepala_desa']){
		$kepala_desa['pamong_nama'] = $desa['nama_kepala_desa'];
		$kepala_desa['pamong_nip'] = $desa['nip_kepala_desa'];
	} else
		$kepala_desa = $this->pamong_model->get_pamong_by_nama($desa['nama_kepala_desa']);

?>