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
						<?php
							if($tipe == 2){
								if($tipex==1){
									$this->load->view($folder_themes.'/partials/statistik/statistik_sos');
								}
							}elseif($tipe == 3){
								$this->load->view($folder_themes.'/partials/statistik/wilayah');
							}elseif($tipe == 4){
								$this->load->view($folder_themes.'/partials/statistik/dpt');
							}else{
								$this->load->view($folder_themes.'/partials/statistik/statistik');
							}
						?>
						</div>
						<aside class="col-lg-4 justify-content-end">
							<div class="widget">
								<?php $this->load->view($folder_themes .'/partials/widget') ?>
							</div>	
						</aside>
					</div>
				</div>
			</div>
		</main>
	</section>
	<?php $this->load->view($folder_themes . '/commons/footer') ?>
</body>
</html>