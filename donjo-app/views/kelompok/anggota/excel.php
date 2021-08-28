<?php
	$tgl =  date('d_m_Y');
	$nk = $kelompok['nama'];
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=data_anggota_kelompok_$nk_$tgl.xls");
	header("Pragma: no-cache");
	header("Expires: 0");

	include("donjo-app/views/kelompok/anggota/cetak.php");
?>
