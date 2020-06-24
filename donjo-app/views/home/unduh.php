<?php
	header("Content-type: application/xls");
	header("Content-Disposition: attachment; filename="."pamong_".date("Y-m-d").".xls");
	header("Pragma: no-cache");
	header("Expires: 0");

	include("donjo-app/views/home/cetak.php");
?>
