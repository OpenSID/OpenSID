<style>
	table.form.detail th{
		padding:5px;
		background:#fafafa;
		border-right:1px solid #eee;
	}
	table.form.detail td{
		padding:5px;
	}
</style>
<div id="pageC">
	<table class="inner">
		<tr style="vertical-align:top">
			<td class="side-menu">
				<legend>Menu Surat Keluar</legend>
				<div class="lmenu">
					<ul>
						<li ><a href="<?= site_url('keluar')?>">Surat Keluar</a></li>
						<li ><a href="<?= site_url('keluar/perorangan')?>">Rekam Surat Perorangan</a></li>
						<li class="selected"><a href="<?= site_url('keluar/graph')?>">Grafik surat keluar</a></li>
					</ul>
				</div>
			</td>
			<td style="background:#fff;padding:5px;">
				<div class="content-header">
				</div>
				<div id="contentpane">
					<div class="ui-layout-north panel">
						<h3>Grafik Surat Keluar</h3></div>
					<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
						<table class="form">
							<div class="block">
								<head>
									<script type="text/javascript">
										$(function () {
											var chart;

											$(document).ready(function () {

												// Build the chart
												chart = new Highcharts.Chart({
													chart: {
														renderTo: 'container',
														plotBackgroundColor: null,
														plotBorderWidth: null,
														plotShadow: false
													},
													title: {
														text: 'Surat Keluar'
													},
													tooltip: {
														pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b><br />Total: <b>{point.y}</b>',
															percentageDecimals: 1
													},
													plotOptions: {
														pie: {
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
																<?php if ($data['jumlah'] != "-"): ?>
																	['<?= $data['nama']?>',<?= $data['jumlah']?>],
																<?php endif; ?>
															<?php endforeach; ?>
														]
													}]
												});
											});
										});
									</script>
								</head>
								<body>
									<script type="text/javascript" src="<?= base_url()?>assets/js/highcharts/highcharts.js"></script>
									<div id="container" style="min-width: 500px; height: 500px; margin: 0 auto"></div>
								</body>
							</div>
						</table>
					</div>

					<div class="ui-layout-south panel bottom">
						<div class="left">
						</div>
						<div class="right">
							<div class="uibutton-group">
							</div>
						</div>
					</div>
				</div>
			</td>
		</tr>
	</table>
</div>
