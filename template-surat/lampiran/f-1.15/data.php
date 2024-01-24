<?php

    defined('BASEPATH') || exit('No direct script access allowed');

    define('MAX_ANGGOTA_F115', 10);
    define('MAX_ANGGOTA_F101', 10);

    $this->load->model('keluarga_model');
    $anggota = $this->keluarga_model->list_anggota($individu['id_kk']);
    $anggota_ikut = $this->keluarga_model->list_anggota($individu['id_kk'], ['dengan_kk' => FALSE], TRUE);

    
    switch (strtolower($input['alasan_permohonan'])) {
        case 'karena membentuk rumah tangga baru':
            $input['alasan_permohonan'] = 1;
            break;
    
        case 'karena kartu keluarga hilang/rusak':
            $input['alasan_permohonan'] = 2;
            break;
        
        case 'lainnya':
            $input['alasan_permohonan'] = 3;
            break;
        
        default:
            $input['alasan_permohonan'] = null;
            break;
    }
    
    // include data F101
    include(FCPATH . "/template-surat/lampiran/f-1.01/data.php");

?>
