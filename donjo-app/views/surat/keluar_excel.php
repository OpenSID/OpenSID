<?php
  header("Content-type: application/octet-stream");
  header("Content-Disposition: attachment; filename=data_arsip_layanan_desa_".date('Y-m-d').".xls");
  header("Pragma: no-cache");
  header("Expires: 0");

  include("donjo-app/views/surat/keluar_print.php");

?>
