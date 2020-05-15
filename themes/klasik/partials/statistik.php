<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<script type="text/javascript">
	let chart;
	const rawData = Object.values(<?= json_encode($stat) ?>);
	const type = '<?= $tipe == 1 ? 'column' : 'pie' ?>';
	const legend = Boolean(!<?= ($tipe) ?>);
	let categories = [];
	let data = [];
	let i = 1;
	let status_tampilkan = true;
	for (const stat of rawData) {
		if (stat.nama !== 'BELUM MENGISI' && stat.nama !== 'TOTAL' && stat.nama !== 'JUMLAH' && stat.nama != 'PENERIMA') {
			let filteredData = [stat.nama, parseInt(stat.jumlah)];
			categories.push(i);
			data.push(filteredData);
			i++;
		}
	}

	function tampilkan_nol(tampilkan = false) {
		if (tampilkan) {
			$(".nol").parent().show();
		} else {
			$(".nol").parent().hide();
		}
	}

	function toggle_tampilkan() {
		$('#showData').click();
		tampilkan_nol(status_tampilkan);
		status_tampilkan = !status_tampilkan;
		if (status_tampilkan) $('#tampilkan').text('Tampilkan Nol');
		else $('#tampilkan').text('Sembunyikan Nol');
	}

	$(document).ready(function () {
		tampilkan_nol(false);
		chart = new Highcharts.Chart({
			chart: {
				renderTo: 'container'
			},
			title: 0,
			xAxis: {
				categories: categories,
			},
			plotOptions: {
				series: {
					colorByPoint: true
				},
				column: {
					pointPadding: -0.1,
					borderWidth: 0
				},
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					showInLegend: true
				}
			},
			legend: {
				enabled: legend
			},
			series: [{
				type: type,
				name: 'Jumlah Populasi',
				shadow: 1,
				border: 1,
				data: data
			}]
		});

		$('#showData').click(function () {
			$('tr.lebih').show();
			$('#showData').hide();
			tampilkan_nol(false);
		});

	});
</script>
<style>
	tr.lebih {
		display: none;
	}
</style>
<style>
	.input-sm
	{
		padding: 4px 4px;
	}
	@media (max-width:780px)
	{
		.btn-group-vertical
		{
			display: block;
		}
	}
	.table-responsive
	{
		min-height:275px;
	}
	}
</style>

<div class="box box-danger">
	<div class="box-header with-border">
		<h3 class="box-title">Grafik <?= $heading ?></h3>
		<div class="box-tools pull-right">
			<div class="btn-group-xs">
				<a href="<?= site_url("first/statistik/$st/1") ?>" class="btn <?= ($tipe==1) ? 'btn-primary' : 'btn-default' ?> btn-xs">Bar Graph</a>
				<a href="<?= site_url("first/statistik/$st/0") ?>" class="btn <?= ($tipe==0) ? 'btn-primary' : 'btn-default' ?> btn-xs">Pie Cart</a>
			</div>
		</div>
	</div>
	<div class="box-body">
		<div id="container"></div>
		<div id="contentpane">
			<div class="ui-layout-north panel top"></div>
		</div>
	</div>
</div>

<div class="box box-danger">
	<div class="box-header with-border">
		<h3 class="box-title">Tabel <?= $heading ?></h3>
	</div>
	<div class="box-body">
		<div class="table-responsive">
		<table class="table table-striped">
			<thead>
			<tr>
				<th rowspan="2">No</th>
				<th rowspan="2" style='text-align:left;'>Kelompok</th>
				<th colspan="2">Jumlah</th>
				<?php if ($jenis_laporan == 'penduduk'):?>
					<th colspan="2">Laki-laki</th>
					<th colspan="2">Perempuan</th>
				<?php endif;?>
			</tr>
			<tr>
				<th style='text-align:right'>n</th><th style='text-align:right'>%</th>
				<?php if ($jenis_laporan == 'penduduk'):?>
					<th style='text-align:right'>n</th><th style='text-align:right'>%</th>
					<th style='text-align:right'>n</th><th style='text-align:right'>%</th>
				<?php endif;?>
			</tr>
			</thead>
			<tbody>
				<?php $i=0; $l=0; $p=0; $hide=""; $h=0; $jm1=1; $jm = count($stat);?>
				<?php foreach ($stat as $data):?>
					<?php $jm1++; ?>
					<?php $h++; ?>
					<?php if ($h > 12 AND $jm > 10): ?>
						<?php $hide = "lebih"; ?>
					<?php endif;?>
					<tr class="<?=$hide?>">
						<td class="angka">
							<?php if ($jm1 > $jm - 2):?>
								<?=$data['no']?>
							<?php else:?>
								<?=$h?>
							<?php endif;?>
						</td>
						<td><?=$data['nama']?></td>
						<td class="angka <?php ($jm1 <= $jm - 2) and ($data['jumlah'] == 0) and print('nol')?>"><?=$data['jumlah']?></td>
						<td class="angka"><?=$data['persen']?></td>
						<?php if ($jenis_laporan == 'penduduk'):?>
							<td class="angka"><?=$data['laki']?></td>
							<td class="angka"><?=$data['persen1']?></td>
							<td class="angka"><?=$data['perempuan']?></td>
							<td class="angka"><?=$data['persen2']?></td>
						<?php endif;?>
					</tr>
					<?php $i += $data['jumlah'];?>
					<?php $l += $data['laki'];?>
					<?php $p += $data['perempuan'];?>
				<?php endforeach;?>
			</tbody>
		</table>
		<?php if ($hide=="lebih"):?>
			<div style='float: left;'>
				<button class='uibutton special' id='showData'>Selengkapnya...</button>
			</div>
		<?php endif;?>
		<div style="float: right;">
			<button id='tampilkan' onclick="toggle_tampilkan();" class="uibutton special">Tampilkan Nol</button>
		</div>
	</div>
	</div>
</div>

<?php if (in_array($st, array('bantuan_keluarga', 'bantuan_penduduk'))):?>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<input id="stat" type="hidden" value="<?=$st?>">
				<div class="box box-info">
					<div class="box-header with-border" style="margin-bottom: 15px;">
						<h3 class="box-title"><?= $heading ?></h3>
					</div>
					<div style="margin-right: 1rem; margin-left: 1rem;">
						<div class="table-responsive">
							<table class="table table-striped table-bordered" id="peserta_program">
								<thead>
									<tr>
				      		  <th>No</th>
										<th>Program</th>
				      		  <th>Nama Peserta</th>
				      		  <th>Alamat</th>
									</tr>
								</thead>
					      <tfoot>
					      </tfoot>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<script type="text/javascript">
		$(document).ready(function() {

		  var url = "<?= site_url('first/ajax_peserta_program_bantuan')?>";
		    table = $('#peserta_program').DataTable({
		      'processing': true,
		      'serverSide': true,
		      "pageLength": 10,
		      'order': [],
		      "ajax": {
		        "url": url,
		        "type": "POST",
		        "data": {stat: $('#stat').val()}
		      },
		      //Set column definition initialisation properties.
		      "columnDefs": [
		        {
		          "targets": [ 0, 3 ], //first column / numbering column
		          "orderable": false, //set not orderable
		        },
		      ],
		      'language': {
		        'url': BASE_URL + '/assets/bootstrap/js/dataTables.indonesian.lang'
		      },
		      'drawCallback': function (){
		          $('.dataTables_paginate > .pagination').addClass('pagination-sm no-margin');
		      }
		    });

		} );
	</script>

<?php endif;?>
