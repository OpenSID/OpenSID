<!-- Pengaturan Grafik (Graph) Data Statistik-->
<script type="text/javascript">
	var chart;
	$(document).ready(function()
	{
		chart = new Highcharts.Chart(
		{
			chart:
			{
				renderTo: 'chart',
				defaultSeriesType: 'column'
			},
			title:
			{
				text: ''
			},
			xAxis:
			{
				title:
				{
					text: '<?= ucwords($main['lblx'])?>'
				},
        categories: [
					<?php foreach ($main['pengunjung']as $data): ?>
					['<?= ($main['lblx'] == 'Bulan') ? getBulan($data['Tanggal']) . ' ' . date('Y') : tgl_indo2($data['Tanggal']); ?>', ],
				<?php endforeach; ?>
					]
			},
			yAxis:
			{
				title:
				{
					text: 'Pengunjung (Orang)'
				}
			},
			legend:
			{
				layout: 'vertical',
        enabled:false
			},
			plotOptions:
			{
				series:
				{
          colorByPoint: true
        },
      column:
			{
				pointPadding: 0,
				borderWidth: 0
			}
		},
		series: [
		{
			shadow:1,
			border:1,
			data: [
				<?php foreach ($main['pengunjung']as $data): ?>
					['<?= ($main['lblx'] == 'Bulan') ? getBulan($data['Tanggal']) . ' ' . date('Y') : tgl_indo2($data['Tanggal']); ?>',<?= $data['Jumlah']?>],
				<?php endforeach; ?>]
			}]
		});
	});
</script>
<!-- Highcharts -->
<script src="<?= base_url()?>assets/js/highcharts/exporting.js"></script>
<script src="<?= base_url()?>assets/js/highcharts/highcharts-more.js"></script>

<div class="content-wrapper">
	<section class="content-header">
		<h1>Statistik Pengunjung Website</h1>
		<ol class="breadcrumb">
			<li><a href="<?=site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Statistik Pengunjung Website</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="mainform" name="mainform" method="post">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-info">
						<div class="box-header with-border">
							<div class="row">
								<div class="col-sm-12">
									<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
										<div class="row">
											<div class="col-sm-12">
												<a href="<?=site_url('pengunjung/cetak')?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Laporan" target="_blank"><i class="fa fa-print "></i>Cetak</a>
												<a href="<?=site_url('pengunjung/unduh')?>" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh Laporan" target="_blank"><i class="fa fa-print "></i>Unduh</a>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-lg-2 col-xs-6">
									<div class="small-box bg-red">
										<div class="inner">
										  <h3><?= ribuan($hari_ini); ?><sup style="font-size: 20px"></sup></h3>
										  <p>Hari Ini</p>
										</div>
										<div class="icon">
											<i class="ion ion-stats-bars"></i>
										</div>
											<a href="<?=site_url('pengunjung/detail/1')?>" class="small-box-footer">Detail <i class="fa fa-arrow-circle-right"></i></a>
									  </div>
								</div>
								<div class="col-lg-2 col-xs-6">
									<div class="small-box bg-purple">
										<div class="inner">
										  <h3><?= ribuan($kemarin); ?><sup style="font-size: 20px"></sup></h3>
										  <p>Kemarin</p>
										</div>
										<div class="icon">
											<i class="ion ion-stats-bars"></i>
										</div>
											<a href="<?=site_url('pengunjung/detail/2')?>" class="small-box-footer">Detail <i class="fa fa-arrow-circle-right"></i></a>
									  </div>
								</div>
								<div class="col-lg-2 col-xs-6">
									<div class="small-box bg-green">
										<div class="inner">
										  <h3><?= ribuan($minggu_ini); ?><sup style="font-size: 20px"></sup></h3>
										  <p>Minggu Ini</p>
										</div>
										<div class="icon">
										  <i class="ion ion-stats-bars"></i>
										</div>
											<a href="<?=site_url('pengunjung/detail/3')?>" class="small-box-footer">Detail <i class="fa fa-arrow-circle-right"></i></a>
									  </div>
								</div>
								<div class="col-lg-2 col-xs-6">
									<div class="small-box bg-yellow">
										<div class="inner">
										  <h3><?= ribuan($bulan_ini); ?><sup style="font-size: 20px"></sup></h3>
										  <p>Bulan Ini</p>
										</div>
										<div class="icon">
										  <i class="ion ion-stats-bars"></i>
										</div>
											<a href="<?=site_url('pengunjung/detail/4')?>" class="small-box-footer">Detail <i class="fa fa-arrow-circle-right"></i></a>
									  </div>
								</div>
								<div class="col-lg-2 col-xs-6">
									<div class="small-box bg-gray">
										<div class="inner">
										  <h3><?= ribuan($tahun_ini); ?><sup style="font-size: 20px"></sup></h3>
										  <p>Tahun Ini</p>
										</div>
										<div class="icon">
										  <i class="ion ion-stats-bars"></i>
										</div>
											<a href="<?=site_url('pengunjung/detail/5')?>" class="small-box-footer">Detail <i class="fa fa-arrow-circle-right"></i></a>
									  </div>
								</div>
								<div class="col-lg-2 col-xs-6">
									<div class="small-box bg-blue">
										<div class="inner">
										  <h3><?= ribuan($jumlah); ?><sup style="font-size: 20px"></sup></h3>
										  <p>Jumlah</p>
										</div>
										<div class="icon">
										  <i class="ion ion-stats-bars"></i>
										</div>
											<a href="<?=site_url('pengunjung/detail/')?>" class="small-box-footer">Detail <i class="fa fa-arrow-circle-right"></i></a>
									  </div>
								</div>
							</div>
							<div class="box-header">
								<hr>
								<h4 class="text-center"><strong>Statistik Pengunjung Website <?= $main['judul'] ?><strong></h4>
								<hr>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-8">
											<div class="box box-info">
												<!-- Ini Grafik -->
												<br>
												<div id="chart"> </div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="box box-info">
											<!-- Tabel Data -->
												<div class="table-responsive">
													<table class="table table-bordered table-striped table-hover nowrap">
														<thead class="bg-gray">
															<tr>
																<th class="text-center" width='5%'>No</th>
																<th class="text-center"><?= $main['lblx']?></th>
																<th class="text-center">Pengunjung (Orang)</th>
															</tr>
														</thead>
														<tbody>
														<?php $no = 1; ?>
														<?php foreach ($main['pengunjung'] as $data): ?>
															<tr>
																<td class="text-center"><?= $no++; ?></td>
																<td class="text-center">
																	<?= ($main['lblx'] == 'Bulan') ? getBulan($data['Tanggal']) . ' ' . date('Y') : tgl_indo2($data['Tanggal']); ?>
																</td>
																<td class="text-center"><?= ribuan($data['Jumlah']); ?></td>
															</tr>
														<?php endforeach; ?>
														</tbody>
														<tfoot class="bg-gray disabled color-palette">
															<tr>
																<th colspan="2" class="text-center">Total</th>
																<th class="text-center"><?= ribuan($main['Total']); ?></th>
															</tr>
														</tfoot>
													</table>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>
