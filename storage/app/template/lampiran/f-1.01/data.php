<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');

    define('MAX_ANGGOTA_F101', 10);

    $this->load->model('keluarga_model');

    $anggota = $this->keluarga_model->list_anggota($individu['id_kk'], ['dengan_kk' => TRUE], TRUE);

    $individu['jumlah_anggota'] = str_pad(count($anggota), 2, "0", STR_PAD_LEFT);

    $kepala_keluarga = collect($anggota)->firstWhere('kk_level', 1);
?>
