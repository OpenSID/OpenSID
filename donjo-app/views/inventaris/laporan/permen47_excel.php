<?php
  $tgl =  date('d_m_Y');
  header("Content-type: application/octet-stream");
  header("Content-Disposition: attachment; filename=permen47_print".$tgl.".xls");
  header("Pragma: no-cache");
  header("Expires: 0");

  include("donjo-app/views/inventaris/laporan/permen47_print.php");
?>
