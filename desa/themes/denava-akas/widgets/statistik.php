<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<div class="module-stat">
	<style type="text/css">
		.highcharts-xaxis-labels tspan {font-size: 8px;}
	</style>
	<div class="head-widget flexleft bg-grey-dark2"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/statistik.svg") ?>" alt=""/><a href="<?= site_url()?>data-statistik/jenis-kelamin"> <font color="#FFFFFF"><?= $judul_widget ?></font></a></div>
	<script type="text/javascript">
		$(function () {
			var chart_widget;
			$(document).ready(function () {
					// Build the chart
					chart_widget = new Highcharts.Chart({
						chart: {
							renderTo: 'container_widget',
							plotBackgroundColor: null,
							plotBorderWidth: null,
							plotShadow: false
						},
						title: {
							text: 'Jumlah Penduduk'
						},
						yAxis: {
							title: {
								text: 'Jumlah'
							}
						},
						xAxis: {
							categories:
							[
							<?php foreach($stat_widget as $data): ?>
								<?php if ($data['jumlah'] != NULL AND $data['nama']!= "JUMLAH"): ?>
									['<?= ribuan($data['jumlah'])?> <br> <?= $data['nama']?>'],
								<?php endif; ?>
							<?php endforeach; ?>
							]
						},
						legend: {
							enabled:false
						},
						plotOptions: {
							series: {
								colorByPoint: true
							},
							column: {
								pointPadding: 0,
								borderWidth: 0
							}
						},
						series: [{
							type: 'bar',
							name: 'Populasi',
							data: [
							<?php foreach ($stat_widget as $data): ?>
								<?php if ($data['jumlah'] != NULL AND $data['nama']!= "JUMLAH"): ?>
									['<?= $data['nama']?>',<?= $data['jumlah']?>],
								<?php endif; ?>
							<?php endforeach; ?>
							]
						}]
					});
				});
		});
	</script>
	<div class="widget-height bg-white border-grey-soft">
		<div class="p-10">
			<div id="container_widget" class="widget-statheight" style="width: 100%;margin: 0 auto"></div>
		</div>
	</div>
</div>
