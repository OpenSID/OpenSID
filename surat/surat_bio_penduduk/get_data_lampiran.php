<?php

define('MAX_ANGGOTA_F101', 10);

$this->load->model('keluarga_model');
$anggota = $this->keluarga_model->list_anggota($individu['id_kk']);

?>