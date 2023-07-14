<?php

    defined('BASEPATH') || exit('No direct script access allowed');

    define('MAX_ANGGOTA_F116', 10);
    define('MAX_ANGGOTA_F101', 10);

    $this->load->model('keluarga_model');
    $anggota = $this->keluarga_model->list_anggota($individu['id_kk'], ['dengan_kk' => TRUE], TRUE);
    $anggota_ikut = $this->keluarga_model->list_anggota($individu['id_kk'], ['dengan_kk' => FALSE], TRUE);

    switch (strtolower($input['alasan_permohonan'])) {
        case 'karena penambahan anggota keluarga (kelahiran, kedatangan)':
            $input['alasan_permohonan'] = 1;
            break;
    
        case 'karena pengurangan anggota keluarga (kematian, kepindahan)':
            $input['alasan_permohonan'] = 2;
            break;
        
        case 'lainnya':
            $input['lainnya'] = 3;
            break;
        
        default:
            $input['alasan_permohonan'] = null;
            break;
    }

    // include data F101
    include(FCPATH . "/template-surat/lampiran/f-1.01/data.php");
?>
