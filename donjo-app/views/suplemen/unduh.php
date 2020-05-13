<?php
	header("Content-type: application/xls");
	header("Content-Disposition: attachment; filename="."laporan_suplemen_".urlencode($suplemen["nama"])."_".date("Y-m-d").".xls");
	header("Pragma: no-cache");
	header("Expires: 0");

	include("donjo-app/views/suplemen/cetak.php");
?>
