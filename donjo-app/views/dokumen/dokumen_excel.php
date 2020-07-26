<style type="text/css">
	h3
	{
		margin: 0px; padding: 0px;
	}
</style>
<?php
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=Dokumen_".$kategori."_".date('Y-m-d').".xls");
	header("Pragma: no-cache");
	header("Expires: 0");

	$this->load->view("dokumen/".$template);
?>
