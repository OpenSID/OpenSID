<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<script type="text/javascript">
	$(document).ready(function() {
		hiRes ();
	});

	var chart;
	function hiRes () {
		chart = new Highcharts.Chart({
			chart: {
				renderTo: 'chart',
				border:0,
				defaultSeriesType: 'column'
			},
			title: {
				text: ''
			},
			xAxis: {
				title: {
					text:''
				},
				categories: [
				<?php $i=0;foreach ($list_jawab as $data){$i++;?>
					<?php if ($data['nilai'] != "-"){echo "'$data[jawaban]',";}?>
				<?php }?>
				]
			},
			yAxis: {
				title: {
					text: 'Jumlah Populasi'
				}
			},
			legend: {
				layout: 'vertical',
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
				shadow:1,
				border:0,
				data: [
				<?php foreach ($list_jawab as $data){?>
					<?php if ($data['jawaban'] != "TOTAL"){?>
						<?php if ($data['nilai'] != "-"){?>
							<?= $data['nilai']?>,
						<?php }?>
					<?php }?>
				<?php }?>
				]
			}]
		});
	};
</script>
<style>
	tr#total {
		background:#fffdc5;
		font-size:12px;
		white-space:nowrap;
		font-weight:bold;
	}
	h3 {
		margin-left: 10px;
	}
</style>
<div class="box box-danger">
	<div class="box-header with-border">
		<h3 class="box-title"><?= $indikator; ?></h3>
	</div>
	<div class="box-body">
		<div class="ui-layout-center" id="chart" style="padding: 5px;"></div>
	</div>
	<div class="box-body">
		<table class="table table-striped">
			<thead>
				<tr>
					<th width="30%">No</th>
					<th>Jawaban</th>
					<th>Jumlah Responden</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($list_jawab as $data): ?>
					<tr>
						<td><?= $data['no']; ?></td>
						<td><?= $data['jawaban']; ?></td>
						<td><?= $data['nilai']; ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<div>
			<a href="<?= site_url('data_analisis'); ?>" class="uibutton icon prev">Kembali</a>
		</div>
	</div>
</div>
