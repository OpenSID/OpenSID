<?php
$tgl =  date('d_m_Y');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Laporan_bulanan_$tgl.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<?php include ("donjo-app/views/laporan/bulanan_print.php"); ?>
