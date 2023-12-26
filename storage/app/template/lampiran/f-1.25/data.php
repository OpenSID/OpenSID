<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');

    // include data kode
    include(FCPATH . "/template-surat/lampiran/kode.php");

    $input['alasan_pindah'] = array_search(strtolower($input['alasan_pindah']), array_map('strtolower', $data["kode"]["alasan_pindah"]));
    $input['jenis_kepindahan'] = array_search(strtolower($input['jenis_kepindahan']), array_map('strtolower', $data["kode"]["jenis_kepindahan"]));
    $input['status_kk_bagi_yang_tidak_pindah'] = array_search(strtolower($input['status_kk_bagi_yang_tidak_pindah']), array_map('strtolower', $data["kode"]["status_kk_tidak_pindah"]));
    $input['status_kk_bagi_yang_pindah'] = array_search(strtolower($input['status_kk_bagi_yang_pindah']), array_map('strtolower', $data["kode"]["status_kk_pindah"]));

    switch (strtoupper($input['klasifikasi_pindah'])) {
        case 'DALAM SATU DESA/KELURAHAN':
            $input['judul_format'] = 'Dalam Satu Desa/Kelurahan';
            $input['kode_format'] = 'F-1.23';
            break;
        
        case 'ANTAR DESA/KELURAHAN':
            $input['kode_format'] = 'Antar Desa/Kelurahan Dalam Satu Kecamatan';
            $input['kode_format'] = 'F-1.25';
            break;
        
        case 'ANTAR KECAMATAN':
            $input['judul_format'] = 'Antar Kecamatan Dalam Satu Kabupaten/Kota';
            $input['kode_format'] = 'F-1.29';
            break;
        
        case 'ANTAR KAB/KOTA' || 'ANTAR PROVINSI':
            $input['judul_format'] = 'Antar Kabupaten/Kota atau Antar Provinsi';
            $input['kode_format'] = 'F-1.34';
            break;
        
        default:
            $input['judul_format'] = null;
            $input['kode_format'] = null;
            break;
    }
