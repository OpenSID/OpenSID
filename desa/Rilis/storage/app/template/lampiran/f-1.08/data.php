<?php if (!defined('BASEPATH')) {
    exit ('No direct script access allowed');
}

    // include data kode
    include(STORAGEPATH . "app/template/lampiran/kode.php");
    
    $input['alasan_pindah'] = array_search(strtolower($input['alasan_pindah']), array_map('strtolower', $data["kode"]["alasan_pindah"]));
    $input['klasifikasi_pindah'] = array_search(strtolower($input['klasifikasi_pindah']), array_map('strtolower', $data["kode"]["klasifikasi_pindah"]));
    $input['jenis_kepindahan'] = array_search(strtolower($input['jenis_kepindahan']), array_map('strtolower', $data["kode"]["jenis_kepindahan"]));
    $input['status_kk_bagi_yang_tidak_pindah'] = array_search(strtolower($input['status_kk_bagi_yang_tidak_pindah']), array_map('strtolower', $data["kode"]["status_kk_tidak_pindah_f108"]));
    $input['status_kk_bagi_yang_pindah'] = array_search(strtolower($input['status_kk_bagi_yang_pindah']), array_map('strtolower', $data["kode"]["status_kk_pindah"]));
?>
