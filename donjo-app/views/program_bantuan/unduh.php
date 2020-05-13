<?php
	header("Content-type: application/xls");
	header("Content-Disposition: attachment; filename="."laporan_bantuan_".urlencode($peserta[0]["nama"])."_".date("Y-m-d").".xls");
	header("Pragma: no-cache");
	header("Expires: 0");

	include("donjo-app/views/program_bantuan/cetak.php");
?>
