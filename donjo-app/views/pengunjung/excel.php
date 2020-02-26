<?php
	header("Content-type: application/xls");
	header("Content-Disposition: attachment; filename={$filename}.xls");
	header("Pragma: no-cache");
	header("Expires: 0");

	include("donjo-app/views/pengunjung/print.php");
?>
