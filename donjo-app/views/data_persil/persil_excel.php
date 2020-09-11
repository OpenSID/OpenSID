<?php

	$title = ($mode == 'persil') ? 'Persil':'C-DESA';
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=".$title."_".date('Y-m-d').".xls");
	header("Pragma: no-cache");
	header("Expires: 0");

	$this->load->view('data_persil/persil_print', $data);
?>
