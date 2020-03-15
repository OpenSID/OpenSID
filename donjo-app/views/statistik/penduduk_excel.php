<?php
	header("Content-type: application/xls");
	header("Content-Disposition: attachment; filename=Statistik_penduduk_{$filename}.xls");
	header("Pragma: no-cache");
	header("Expires: 0");

	include("donjo-app/views/statistik/penduduk_print.php");
?>
