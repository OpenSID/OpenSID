<?php
  $tgl =  date('d_m_Y');
  header("Content-type: application/octet-stream");
  header("Content-Disposition: attachment; filename=Inventaris_Peralatan_".$tgl.".xls");
  header("Pragma: no-cache");
  header("Expires: 0");

  include("donjo-app/views/inventaris/jalan/inventaris_print.php");
?>
