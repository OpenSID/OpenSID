<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');

    // include data kode
    include(FCPATH . "/template-surat/lampiran/kode.php");

    $input['jenis_permohonan'] = array_search(strtolower($input['jenis_permohonan']), array_map('strtolower', $data["kode"]["jenis_permohonan"]));
    $input['alasan_pindah'] = array_search(strtolower($input['alasan_pindah']), array_map('strtolower', $data["kode"]["alasan_pindah"]));
    $input['klasifikasi_pindah'] = array_search(strtolower($input['klasifikasi_pindah']), array_map('strtolower', $data["kode"]["klasifikasi_pindah"]));
    $input['jenis_kepindahan'] = array_search(strtolower($input['jenis_kepindahan']), array_map('strtolower', $data["kode"]["jenis_kepindahan"]));
    $input['status_kk_bagi_yang_tidak_pindah'] = array_search(strtolower($input['status_kk_bagi_yang_tidak_pindah']), array_map('strtolower', $data["kode"]["status_kk_tidak_pindah_f103"]));
    $input['status_kk_bagi_yang_pindah'] = array_search(strtolower($input['status_kk_bagi_yang_pindah']), array_map('strtolower', $data["kode"]["status_kk_pindah_f103"]));
    $input['tipe_sponsor'] = array_search(strtolower($input['tipe_sponsor']), array_map('strtolower', $data["kode"]["tipe_sponsor"]));
?>
