<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view($folder_themes . '/commons/header') ?>
<?php $this->load->view($folder_themes . '/commons/nav') ?>
<?php
	$views_partial_layout = '';
	switch ($m) {
		case 1:
			$views_partial_layout = "/partials/mandiri/biodata.php";
			break;
		case 2:
			$views_partial_layout = "/partials/mandiri/layanan.php";
			break;
		case 4:
			$views_partial_layout = "/partials/mandiri/bantuan.php";
			break;
		default:
			$views_partial_layout = "/partials/mandiri/lapor.php";
	}
?>
<section id="main-content">
	<main>
		<div class="container">
			<div class="col-12 px-0">
				<div class="row m-0 justify-content-between">
					<div class="col-lg-8 bg-white justify-content-start">
						<?php $this->load->view($folder_themes . $views_partial_layout); ?>
					</div>
					<aside class="col-lg-4 justify-content-end">
						<div class="widget">
							<?= $this->load->view($folder_themes .'/partials/widget') ?>
						</div>	
					</aside>
				</div>
			</div>
		</div>
	</main>
</section>
<?= $this->load->view($folder_themes .'/commons/footer') ?>
