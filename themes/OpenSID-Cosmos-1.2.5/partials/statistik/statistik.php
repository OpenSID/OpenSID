<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<script src="<?= base_url('assets/js/highcharts/highcharts.js')?>"></script>
<script src="<?= base_url('assets/js/highcharts/highcharts-more.js')?>"></script>
<script src="<?= base_url('assets/js/highcharts/exporting.js')?>"></script>
<?php if($tipe==1) : ?>
<script type="text/javascript">
$(document).ready(function() {
	new Highcharts.Chart({
		chart: { renderTo: 'container'},
		title:0,
				xAxis: {
					categories: [
					<?php  $i=0;foreach($stat as $data){$i++;?>
					  <?php if($data['jumlah'] != "-" AND $data['nama']!= "TOTAL" AND $data['nama']!= "JUMLAH"){echo "'$i',";}?>
					<?php }?>
					]
				},
			plotOptions: {
				series: {
					colorByPoint: true
				},
				column: {
					pointPadding: -0.1,
					borderWidth: 0
				}
			},
				legend: {
					enabled:false
				},
		series: [{
			type: 'column',
			name: 'Jumlah Populasi',
			shadow:1,
			border:1,
			data: [
					<?php  foreach($stat as $data){?>
						<?php if($data['jumlah'] != "-" AND $data['nama']!= "TOTAL" AND $data['nama']!= "JUMLAH"){?>
							['<?= $data['nama']?>',<?= $data['jumlah']?>],
						<?php }?>
					<?php }?>
			]
		}]
	});
});
</script>

<?php else : ?>

<script type="text/javascript">
 $(document).ready(function () {
	// Build the chart
	new Highcharts.Chart({
		chart: {
			renderTo: 'container'
		},
		title:0,
		plotOptions: {
			pie: {
				allowPointSelect: true,
				cursor: 'pointer',
				showInLegend: true
			}
		},
		series: [{
			type: 'pie',
			name: 'Jumlah Populasi',
			shadow:1,
			border:1,
			data: [
					<?php  foreach($stat as $data):?>
						<?php if($data['jumlah'] != "-" AND $data['nama']!= "TOTAL" AND $data['nama']!= "JUMLAH") : ?>
							['<?= $data['nama']?>',<?= $data['jumlah']?>],
						<?php endif; ?>
					<?php endforeach; ?>
			]
		}]
	});
});
</script>
<?php endif; ?>

<script>
$(function(){
	$('#showData').click(function(){
		$('tr.tr-lebih').removeClass('d-none');
		$('#showData').hide();
	});
});
</script>

<div class="stat">
	<h2 class="judul-artikel">Demografi Berdasar <?= $heading ?></h2>
	<div class="col-12 px-0 mb-4 mt-3">
		<div class="row justify-content-between align-content-center">
			<div class="col-7"><h5 class="font-weight-bold">Grafik Data</h5></div>
			<div class="col-5">
				<div class="box-stats d-flex justify-content-end">
					<div class="btn-group btn-group-sm">
						<?php $jenis_btn = ($tipe==1) ? "btn-primary":"btn-default"; ?>
						<a href="<?= site_url('first/statistik/'.$st.'/1') ?>" class="btn btn-sm <?= $jenis_btn ?>">Bar Graph</a>
						<?php $jenis_btn = ($tipe==0) ? "btn-primary":"btn-default"; ?>
						<a href="<?= site_url('first/statistik/'.$st.'/0') ?>" class="btn btn-sm <?= $jenis_btn ?>">Pie Chart</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div>
		<div id="container"></div>
		<div id="contentpane">
			<div class="ui-layout-north panel top"></div>
		</div>
	</div>

	<h5 class="font-weight-bold mt-4">
		Tabel Data
	</h5>
	<div class="table-responsive">
		<table class="table table-bordered table-striped">
			<thead>
			<tr>
				<th rowspan="2" class="align-middle">No</th>
				<th rowspan="2" class="align-middle">Kelompok</th>
				<th colspan="2" class="text-center">Jumlah</th>
				<?php if($jenis_laporan == 'penduduk'): ?>
					<th colspan="2" class="text-center">Laki-laki</th>
					<th colspan="2" class="text-center">Perempuan</th>
				<?php endif; ?>
			</tr>
			<tr>
				<th class="text-right">n</th>
				<th class="text-right">%</th>
				<?php if($jenis_laporan == 'penduduk'):?>	
					<th class="text-right">n</th>
					<th class="text-right">%</th>
					<th class="text-right">n</th>
					<th class="text-right">%</th>
				<?php endif ?>
			</tr>
			</thead>
			<tbody>
			<?php $i=0; $l=0; $p=0; ?>
			<?php $hide=''; $not_hide=0; ?>
			<?php $jml = count($stat); ?>
			<?php foreach($stat as $data) : ?>
				<?php $not_hide++; ?>
				<?php if($not_hide > 10 && $jml > 11) : ?>
					<?php $hide = 'tr-lebih d-none' ?>
				<?php endif ?>
				<tr class="<?= $hide ?>">
					<td class="text-right"><?= $data['no'] ?></td>
					<td><?= $data['nama'] ?></td>
					<td class="text-right"><?= $data['jumlah'] ?></td>
					<td class="text-right"><?= $data['persen'] ?></td>
					<?php if($jenis_laporan == 'penduduk') : ?>
						<td class="text-right"><?= $data['laki'] ?></td>
						<td class="text-right"><?= $data['persen1'] ?></td>
						<td class="text-right"><?= $data['perempuan'] ?></td>
						<td class="text-right"><?= $data['persen2'] ?></td>
					<?php endif ?>
				</tr>
				<?php 
					$i = $i+$data['jumlah'];
					$l = $l+$data['laki'];
					$p = $p+$data['perempuan'];
				?>
			<?php endforeach ?>
			</tbody>
		</table>

		<?php if($hide == "tr-lebih d-none") : ?>
		<button class='btn btn-sm btn-success' id='showData'>Selengkapnya...</button>
		<?php endif ?>
	</div>
</div>
