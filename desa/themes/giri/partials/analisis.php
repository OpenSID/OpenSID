<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<script src="<?= asset('js/highcharts/highcharts.js') ?>"></script>
<script src="<?= asset('js/highcharts/highcharts-more.js') ?>"></script>
<script src="<?= asset('js/highcharts/exporting.js') ?>"></script>
<script type="text/javascript">
	$(document).ready(function() {hiRes ();});
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
				<?php $i=0;foreach($list_jawab as $data){$i++;?>
					<?php if($data['nilai'] != "-"){echo "'$data[jawaban]',";}?>
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
				<?php foreach($list_jawab as $data){?>
					<?php if($data['jawaban'] != "TOTAL"){?>
						<?php if($data['nilai'] != "-"){?>
							<?= $data['nilai']?>,
						<?php }?>
					<?php }?>
					<?php }?>]
				}]
			});
	};
</script>
<!-- TODO: Pindahkan ke external css -->
<style>
	tr#total{
		background:#fffdc5;
		font-size:12px;
		white-space:nowrap;
		font-weight:bold;
	}
	h3{ margin-left: 10px; }
</style>

<h3><?= $indikator['pertanyaan'] ?></h3><br>
<div class="middin-center" style="padding: 5px;">
	<div id="contentpane">
		<div class="ui-layout-center" id="chart" style="padding: 5px;"></div>
		<table class="table table-striped">
			<thead>
				<tr>
					<th width="1%">No</th>
					<th>Jawaban</th>
					<th width="20%" nowrap>Jumlah Responden</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($list_jawab as $data): ?>
					<tr>
						<td class="text-center"><?= $data['no']?></td>
						<td><?= $data['jawaban']?></td>
						<td class="text-center"><?= $data['nilai']?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<div>
			<a href="<?= site_url("data_analisis?master={$indikator['id_master']}"); ?>" class="btn btn-primary">Kembali</a>
		</div>
	</div>
</div>
