<?php
	header("Content-type: application/octet-stream");
	if (empty($_SESSION['filter']))
		$tahun = "_semua";
	else
		$tahun = "_".$_SESSION['filter'];
	header("Content-Disposition: attachment; filename=surat_keluar".$tahun.".xls");
	header("Pragma: no-cache");
	header("Expires: 0");

	include("donjo-app/views/surat_keluar/surat_keluar_print.php");
?>
