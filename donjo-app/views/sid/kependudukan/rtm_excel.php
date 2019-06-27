<?php
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=rtm_".date("Y-m-d").".xls");
	header("Pragma: no-cache");
	header("Expires: 0");

  include("donjo-app/views/sid/kependudukan/rtm_print.php");
?>
