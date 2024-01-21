<link rel="stylesheet" href="<?= asset('css/bagan.css') ?>">

<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box-body">
					<figure class="highcharts-figure">
					<div id="container"></div>
						<p class="highcharts-description"></p>
					</figure>
				</div>
			</div>
		</div>
	</section>
</div>

<?php include APPPATH . 'views/bagan/chart_bagan.php'; ?>

