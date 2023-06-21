<?php

    defined('BASEPATH') || exit('No direct script access allowed');

    define('MAX_ANGGOTA_F115', 10);
    define('MAX_ANGGOTA_F101', 10);

    $this->load->model('keluarga_model');
    $anggota = $this->keluarga_model->list_anggota($individu['id_kk']);
    $anggota_ikut = $this->keluarga_model->list_anggota($individu['id_kk'], ['dengan_kk' => FALSE], TRUE);

    // include data F101
    include(FCPATH . "/template-surat/lampiran/f-1.01/data.php");

?>
