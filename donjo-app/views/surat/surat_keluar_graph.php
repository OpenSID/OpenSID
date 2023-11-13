<script type="text/javascript">
	$(function ()
	{
		var chart;
		$(document).ready(function ()
		{
			// Build the chart
			chart = new Highcharts.Chart({
				chart: {
					renderTo: 'container',
					plotBackgroundColor: null,
					plotBorderWidth: null,
					plotShadow: false
				},
				title:
				{
					text: 'Surat Keluar'
				},
				tooltip:
				{
					pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b><br />Total: <b>{point.y}</b>',
					percentageDecimals: 1
				},
				plotOptions:
				{
					pie:
					{
						allowPointSelect: true,
						cursor: 'pointer',
						dataLabels: {
							enabled: false
						},
						showInLegend: true
					}
				},
				series: [{
					type: 'pie',
					name: 'Persentase',
					data: [
						<?php foreach ($stat as $data): ?>
							<?php if ($data['jumlah'] != '-'): ?>
								['<?= $data['nama']?>',<?= $data['jumlah']?>],
							<?php endif; ?>
						<?php endforeach; ?>
					]
				}]
			});
		});
	});
</script>
<!-- Highcharts -->
<script src="<?= asset('js/highcharts/highcharts.js') ?>"></script>
<script src="<?= asset('js/highcharts/exporting.js') ?>"></script>
<script src="<?= asset('js/highcharts/highcharts-more.js') ?>"></script>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Grafik Surat Keluar</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('beranda')?>"><i class="fa fa-home"></i> Beranda</a></li>
			<li><a href="<?= site_url('keluar')?>"> Daftar Surat Keluar</a></li>
			<li class="active">Grafik Surat Keluar</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div id="container"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
