<?php
	header("Content-type: application/octet-stream");
	if (empty($_SESSION['filter']))
		$tahun = "_semua";
	else
		$tahun = "_".$_SESSION['filter'];
	header("Content-Disposition: attachment; filename=buku_ekspedisi".$tahun.".xls");
	header("Pragma: no-cache");
	header("Expires: 0");

	include("donjo-app/views/ekspedisi/ekspedisi_cetak.php");
?>
