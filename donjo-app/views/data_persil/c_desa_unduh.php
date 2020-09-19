<?php

	header("Content-type: application/xls");
	header("Content-Disposition: attachment; filename=Daftar C-Desa_".date('Y-m-d').".xls");
	header("Pragma: no-cache");
	header("Expires: 0");

	$this->load->view('data_persil/c_desa_cetak');
?>
