<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');

    // include data kode
    include(FCPATH . "/template-surat/lampiran/kode.php");

    $input['status_kk_bagi_yang_pindah'] = array_search(strtolower($input['status_kk_bagi_yang_pindah']), array_map('strtolower', $data["kode"]["status_kk_pindah"]));

    switch (strtoupper($input['klasifikasi_pindah'])) {
        case 'DALAM SATU DESA/KELURAHAN':
            $input['judul_format'] = 'Antar Desa/Kelurahan Dalam Satu Kecamatan';
            $input['kode_format'] = 'F-1.27';
            break;

        case 'ANTAR DESA/KELURAHAN':
            $input['judul_format'] = 'Antar Desa/Kelurahan Dalam Satu Kecamatan';
            $input['kode_format'] = 'F-1.27';
            break;
        
        case 'ANTAR KECAMATAN':
            $input['judul_format'] = 'Antar Kecamatan Dalam Satu Kabupaten/Kota';
            $input['kode_format'] = 'F-1.31';
            break;
        
        case 'ANTAR KAB/KOTA' || 'ANTAR PROVINSI':
            $input['judul_format'] = 'Antar Kabupaten/Kota atau Antar Provinsi';
            $input['kode_format'] = 'F-1.39';
            break;
        
        default:
            $input['judul_format'] = null;
            $input['kode_format'] = null;
            break;
    }
?>
