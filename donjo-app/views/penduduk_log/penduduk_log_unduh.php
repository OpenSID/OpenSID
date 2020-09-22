<?php
  header("Content-type: application/octet-stream");
  header("Content-Disposition: attachment; filename=Log_Penduduk_".date('Y-m-d').".xls");
  header("Pragma: no-cache");
  header("Expires: 0");

  include("donjo-app/views/penduduk_log/penduduk_log_cetak.php");
?>
