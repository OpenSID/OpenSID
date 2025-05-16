<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<link rel="stylesheet" href="<?= asset('css/bagan.css') ?>">
<div class="article-single">
	<div class="container-page mb-20">
		<div class="headingpage border-grey-soft bg-white flexleft" style="border-radius:5px 5px 0 0;">
			<div class="headingpage-image border-grey-soft flexcenter"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/arsip.svg") ?>" alt=""/></div>
			<h2>Struktur Organisasi dan Tata Kerja <?= setting('sebutan_pemerintah_desa') ?></h2>
		</div>
		<div class="box-article bg-white" style="border-radius:0 0 5px 5px;">
			<div class="box-statis border-grey-soft" style="border-radius:0 0 5px 5px;">
				<center>
                    <figure class="highcharts-figure" style="max-width: 100%;">
                      <div id="container"></div>
                      <p class="highcharts-description"></p>
                    </figure>
                </center>
			</div>
		</div>
	</div>
</div>
<?php include APPPATH . 'views/bagan/chart_bagan.php'; ?>
