<?php
	$tgl =  date('d_m_Y');

	header("Content-type: application/xls");
	header("Content-Disposition: attachment; filename=statistik_penduduk_{$filename}_{$tgl}.xls");
	header("Pragma: no-cache");
	header("Expires: 0");

	include("donjo-app/views/statistik/penduduk_cetak.php");
?>
