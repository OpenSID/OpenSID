<?php

define('MAX_ANGGOTA_F115', 10);

$this->load->model('keluarga_model');
$this->load->model('pamong_model');
$anggota = $this->keluarga_model->list_anggota($individu['id_kk']);
$desa = $this->keluarga_model->get_desa();
$kepala_desa = $this->pamong_model->get_pamong_by_nama($desa['nama_kepala_desa']);

?>