<?php $this->load->view('print/headjs.php'); ?>
	<style type="text/css">
		#body
		{
			page-break-after: always;
		}
	</style>
	<body>
		<div id="container">
			<link href="<?= base_url()?>assets/css/report.css" rel="stylesheet" type="text/css">
			<?php
                foreach ($all_kk as $kk):
                    $this->load->view('sid/kependudukan/cetak_kk', $kk);
                endforeach;
?>
			<div id="aside"></div>
		</div>
	</body>
</html>
