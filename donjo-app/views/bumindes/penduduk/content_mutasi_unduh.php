<?php
  header("Content-type: application/octet-stream");
  header("Content-Disposition: attachment; filename=Mutasi_".date('Y-m-d').".xls");
  header("Pragma: no-cache");
  header("Expires: 0");

  include("donjo-app/views/bumindes/penduduk/content_mutasi_cetak.php");
?>
