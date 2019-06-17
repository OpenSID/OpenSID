<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<script src="<?php echo base_url('assets/js/highcharts/highcharts.js')?>"></script>
<script type="text/javascript">
$(document).ready(function() {
	new Highcharts.Chart({
		chart: {
			renderTo: 'container',
			defaultSeriesType: 'column'
		},
		title: {
			text: 'Statistik Kelas Sosial'
		},
		xAxis: {
			title: {
				text: 'Kelas Sosial'
			},
			categories: [
			<?php  $i=0;foreach($main as $data){$i++;?>
			  <?php echo "'$data[nama]',";?>
			<?php }?>
			]
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
			enabled:false
		},
		tooltip: {
			formatter: function() {
				return ''+
					this.x +': '+ this.y +'';
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
			data: [
			<?php  foreach($main as $data){?>
				<?php echo $data['jumlah'].",";?>
			<?php }?>]

		}]
	});

});
</script>

<div class="stat">
	<h2 class="judul-artikel">Statistik Kependudukan berdasarkan Indeks Kemiskinan</h2>

	<h5 class="font-weight-bold">Grafik Data</h5>
	<div>
		<div id="container"></div>
		<div id="contentpane">
			<div class="ui-layout-north panel top"></div>
			<div class="ui-layout-center" id="chart" style="padding: 5px;"></div>
		</div>
	</div>

	<h5 class="font-weight-bold">Tabel Data</h5>
	<div>
		<div class="table-responsive">
		<table class="table table-bordered table-striped">
		<thead>
			<tr>
				<th>#</th>
				<th>Kelompok</th>
				<th>Jumlah</th>
			</tr>
		</thead>
		<tbody>
		<?php $i=0; ?>
		<?php foreach($main as $data): ?>
			<tr>
				<td class="text-right"><?= $data['id'] ?></td>
				<td><?= $data['nama'] ?></td>
				<td class="text-right"><?= $data['jumlah'] ?></td>
			</tr>
			<?php $i = $i+$data['jumlah']; ?>
		<?php endforeach; ?>
		</tbody>
		<tfoot>
			<tr>
				<th colspan="2" class="text-right">JUMLAH</th>
				<th>$i</th>
			</tr>
			</tfoot>
		</table>
		</div>
	</div>
</div>
