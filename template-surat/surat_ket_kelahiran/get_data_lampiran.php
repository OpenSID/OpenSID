<?php

    defined('BASEPATH') || exit('No direct script access allowed');

    // View untuk Permohonan Akta Kelahiran
    $tampil_data_anak      = true;
    $tampil_data_orang_tua = true;
    $tampil_data_pelapor   = true;
    $tampil_data_saksi     = true;

    // Pilih model yang digunakan untuk menampilkan data
    $format_f201 = 1;

    include(FCPATH . "/template-surat/lampiran/f-2.01/data.php");

?>