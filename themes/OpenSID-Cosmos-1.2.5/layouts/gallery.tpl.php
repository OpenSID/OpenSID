<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<?php $this->load->view($folder_themes . '/commons/meta') ?>
	<?php $this->load->view($folder_themes . '/commons/source_css') ?>
	<?php $this->load->view($folder_themes . '/commons/source_js') ?>
</head>
<body>
	<div id="loader-wrapper">
		<div id="loader"></div>
		<div class="loader-section section-left"></div>
		<div class="loader-section section-right"></div>
	</div>
	<?php $this->load->view($folder_themes . '/commons/header') ?>
	<?php $this->load->view($folder_themes . '/commons/nav') ?>

	<section id="main-content">
		<main>
			<div class="container">
				<div class="col-12 px-0">
					<div class="row m-0 justify-content-between">
						<div class="col-lg-8 bg-white justify-content-start">
							<h2 class="judul-artikel">
								<i class="fa fa-camera"></i>	ALBUM FOTO
							</h2>
							<div class="mt-3 album">
								<?php $this->load->view($folder_themes .'/partials/gallery') ?>
							</div>
							<?php $this->load->view($folder_themes .'/commons/paging') ?>
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
</body>
</html>