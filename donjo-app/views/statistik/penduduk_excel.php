<?php
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=Statistik_penduduk.xls");
	header("Pragma: no-cache");
	header("Expires: 0");

	include("donjo-app/views/statistik/penduduk_print.php");
?>
