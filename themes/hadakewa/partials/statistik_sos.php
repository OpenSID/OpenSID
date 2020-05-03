<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<script type="text/javascript">
	let chart;
	const rawData = Object.values(<?= json_encode($main) ?>);
	let categories = [];
	let data = [];

	for (const tempData of rawData) {
		categories.push(tempData.nama);
		data.push(tempData.jumlah);
	}

	$(document).ready(function () {
		chart = new Highcharts.Chart({
			chart: {
				renderTo: 'container',
				defaultSeriesType: 'column'
			},
			title: {},
			xAxis: {
				title: {
					text: 'Kelas Sosial'
				},
				categories: categories
			},
			yAxis: {
				title: {
					text: 'Populasi'
				}
			},
			legend: {
				layout: 'vertical',
				backgroundColor: '#FFFFFF',
				align: 'left',
				verticalAlign: 'top',
				x: 100,
				y: 70,
				floating: true,
				shadow: true,
				enabled: false
			},
			tooltip: {
				formatter: function () {
					return '' +
						this.x + ': ' + this.y + '';
				}
			},
			plotOptions: {
				series: {
					colorByPoint: true
				},
				column: {
					pointPadding: 0.2,
					borderWidth: 0
				}
			},
			series: [{
				name: 'Populasi',
				data: data
			}]
		});


	});
</script>

<div class="box box-danger">
	<div class="box-header with-border">
		<h3 class="box-title">Grafik Statistik Kependudukan berdasarkan Indeks Kemiskinan</h3>
	</div>
	<div class="box-body">
		<div id="container"></div>
		<div id="contentpane">
			<div class="ui-layout-north panel top"></div>
			<div class="ui-layout-center" id="chart" style="padding: 5px"></div>
		</div>
	</div>
</div>
<div class="box box-danger">
	<div class="box-header with-border">
		<h3 class="box-title">Tabel Statistik Kependudukan berdasarkan Indeks Kemiskinan</h3>
	</div>
	<div class="box-body">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>#</th>
					<th>Kelompok</th>
					<th>Jumlah</th>
				</tr>
			</thead>
			<tbody>
				<?php $total = 0; $i = 1 ?>
				<?php foreach($main as $data) : ?>
					<tr>
						<td class="angka"><?= $i ?></td>
						<td><?= $data['nama'] ?></td>
						<td><?= $data['jumlah'] ?></td>
					</tr>
					<?php $total += $data['jumlah'] ?>
				<?php endforeach ?>
			</tbody>
			<tfoot>
				<tr>
					<th colspan="2" class="angka">JUMLAH</th>
					<td><?= $total ?></td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>