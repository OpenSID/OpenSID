<div class="content-wrapper">
	<section class="content-header">
		<h1>Pendapat</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Pendapat</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="box box-info">
			<div class="box-header with-border">
				<h3 class="box-title">Tingkat Kepuasan Pengguna Layanan Mandiri <?= $main['judul']; ?></h3>
				<div class="box-tools pull-right">
					<div class="btn-group">
						<button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
							Tampilkan : <i class="fa fa-calendar"></i></button>
							<ul class="dropdown-menu" role="menu">
								<li><a href="<?= site_url('pendapat/detail/1'); ?>">Hari Ini</a></li>
								<li><a href="<?= site_url('pendapat/detail/2'); ?>">Kemarin</a></li>
								<li><a href="<?= site_url('pendapat/detail/3'); ?>">Minggu Ini</a></li>
								<li><a href="<?= site_url('pendapat/detail/4'); ?>">Bulan Ini</a></li>
								<li><a href="<?= site_url('pendapat/detail/5'); ?>">Tahun Ini</a></li>
								<li><a href="<?= site_url('pendapat/detail/6'); ?>">Semua</a></li>
							</ul>
						</div>
					</div>
				</div>
				<div class="box-body">
					<div class="row">
						<div class="col-md-12">
							<?php foreach ($list_pendapat as $key => $value) : ?>
								<?php $key = "pilihan_{$key}" ?>
								<div class="col-sm-3 col-xs-6">
									<div class="description-block border-left border-right">
										<img src="<?= base_url(PENDAPAT . underscore($value, true, true) . '.png'); ?>">
										<h5 class="description-header"><?= persen(${$key} / $main['total']); ?></h5>
										<span class="description-text"><?= $value; ?></span>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
					<hr/>
					<strong><center>GRAFIK DAN TABEL</center></strong>
					<hr/>
					<div class="row">
						<div class="col-md-6">
							<div id="chart"></div>
						</div>
						<div class="col-md-6">
							<div class="table-responsive">
								<table class="table table-bordered dataTable table-striped table-hover tabel-daftar" id="tabel-data">
									<thead class="bg-gray disabled color-palette">
										<tr>
											<th>No</th>
											<th>Pengguna</th>
											<th>Tanggal</th>
											<th>Pendapat</th>
										</tr>
									</thead>
									<tbody>
										<?php if ($detail): ?>
											<?php $total = 0; ?>
											<?php foreach ($detail as $key => $item): ?>
												<tr>
													<td class="padat"><?= ($key + 1); ?></td>
													<td class="padat">
														<a href="penduduk/detail/1/0/<?=$item['pengguna']?>"><?= $item['nama']; ?></a>
													</td>
													<td class="padat"><?= tgl_indo2($item['tanggal']); ?></td>
													<td class="padat"><?= $list_pendapat[$item['pilihan']]; ?></td>
												</tr>
											<?php endforeach; ?>
											<?php else: ?>
												<tr>
													<td class="text-center" colspan="4">Data Tidak Tersedia</td>
												</tr>
											<?php endif; ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
	<script type="text/javascript">
		var chart;
		$(document).ready(function() {
			chart = new Highcharts.Chart( {
				chart: {
					renderTo: 'chart',
					defaultSeriesType: 'column'
				},
				title: {
					text: ''
				},
				xAxis: {
					title: {
						text: '<b>Pilihan</b>'
					},
					categories: [
					<?php foreach ($list_pendapat as $key => $value): ?>
						['<?= $value; ?>', ],
					<?php endforeach; ?>
					]
				},
				yAxis: {
					title: {
						text: 'Pengguna (Orang)'
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
				series: [ {
					shadow:1,
					border:1,
					data: [
					<?php foreach ($list_pendapat as $key => $value): ?>
						<?php $jml = "pilihan_{$key}"; ?>
						['<?= $key; ?>', <?= ${$jml}; ?>],
					<?php endforeach; ?>
					]
				}]
			});

			$('#tabel-data').DataTable({
				'processing': true,
				'pageLength': 10,
				'order': [],
				'columnDefs': [
					{
						'searchable': false,
						'targets': 0
					},
					{
						'orderable': false,
						'targets': 0
					}
				],
				'language': {
					'url': BASE_URL + '/assets/bootstrap/js/dataTables.indonesian.lang'
				},
			});
		});
	</script>
	<script src="<?= base_url()?>assets/js/highcharts/exporting.js"></script>
	<script src="<?= base_url()?>assets/js/highcharts/highcharts-more.js"></script>
