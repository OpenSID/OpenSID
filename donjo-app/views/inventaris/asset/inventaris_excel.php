<?php
  $tgl =  date('d_m_Y');
  header("Content-type: application/octet-stream");
  header("Content-Disposition: attachment; filename=Inventaris_Asset_Lainnya_".$tgl.".xls");
  header("Pragma: no-cache");
  header("Expires: 0");

  include("donjo-app/views/inventaris/asset/inventaris_print.php");
?>
