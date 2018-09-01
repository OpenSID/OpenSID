<?php
  header("Content-type: application/octet-stream");
  header("Content-Disposition: attachment; filename=Persil_".date('Y-m-d').".xls");
  header("Pragma: no-cache");
  header("Expires: 0");

  include("donjo-app/views/data_persil/persil_print.php");
?>
