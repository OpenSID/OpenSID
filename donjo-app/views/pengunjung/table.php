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
					text: '<?= $_SESSION['lblx']?>'
				},
        categories: [
					<?php foreach ($main as $data): ?>
					['<?php if($_SESSION['lblx']=='Bulan'){
								echo getBulan($data['tgl']);
							}else{
								echo tgl_indo2($data['tgl']);
							}
						?>', ],
				<?php endforeach;?>
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
				<?php foreach ($main as $data): ?>
					['<?php if($_SESSION['lblx']=='Bulan'){
								echo getBulan($data['tgl']);
							}else{
								echo tgl_indo2($data['tgl']);
							}
						?>',<?= $data['Total']?>],
				<?php endforeach;?>]
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
		<form id="mainform" name="mainform" action="" method="post">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-info">
						<div class="box-header with-border">
							<div class="row">
								<div class="col-sm-12">
									<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
										<div class="row">
											<div class="col-sm-6">
												<a href="<?=site_url("pengunjung/dialog_cetak/$lap")?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Laporan" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Cetak Laporan"><i class="fa fa-print "></i>Cetak</a>
												<a href="<?=site_url("pengunjung/dialog_unduh/$lap")?>" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh Laporan" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Unduh Laporan"><i class="fa fa-print "></i>Unduh</a>
											</div>
											<div class="col-sm-6">
												<div class="box-tools">
													<div class="input-group input-group-sm pull-right">
														<select class="form-control input-sm " name="filter" onchange="formAction('mainform', '<?=site_url('pengunjung/filter')?>')">
															<option value=""<?php if ($filter==''): ?>selected<?php endif ?>>Semua</option>
															<option value="1"<?php if ($filter==1): ?>selected<?php endif ?>>Minggu Ini</option>
															<option value="2"<?php if ($filter==2): ?>selected<?php endif ?>>Bulan  Ini</option>
															<option value="3"<?php if ($filter==3): ?>selected<?php endif ?>>Tahun Ini</option>
														</select>
													</div>
												</div>
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
										  <h3><?= $hari_ini; ?><sup style="font-size: 20px"></sup></h3>
										  <p>Hari Ini</p>
										</div>
										<div class="icon">
										  <i class="ion ion-stats-bars"></i>
										</div>
									  </div>
								</div>
								<div class="col-lg-2 col-xs-6">
									<div class="small-box bg-purple">
										<div class="inner">
										  <h3><?= $kemarin;?><sup style="font-size: 20px"></sup></h3>
										  <p>Kemarin</p>
										</div>
										<div class="icon">
										  <i class="ion ion-stats-bars"></i>
										</div>
									  </div>
								</div>
								<div class="col-lg-2 col-xs-6">
									<div class="small-box bg-green">
										<div class="inner">
										  <h3><?= $minggu_ini;?><sup style="font-size: 20px"></sup></h3>
										  <p>Minggu Ini</p>
										</div>
										<div class="icon">
										  <i class="ion ion-stats-bars"></i>
										</div>
									  </div>
								</div>
								<div class="col-lg-2 col-xs-6">
									<div class="small-box bg-yellow">
										<div class="inner">
										  <h3><?= $bulan_ini;?><sup style="font-size: 20px"></sup></h3>
										  <p>Bulan Ini</p>
										</div>
										<div class="icon">
										  <i class="ion ion-stats-bars"></i>
										</div>
									  </div>
								</div>
								<div class="col-lg-2 col-xs-6">
									<div class="small-box bg-gray">
										<div class="inner">
										  <h3><?= $tahun_ini;?><sup style="font-size: 20px"></sup></h3>
										  <p>Tahun Ini</p>
										</div>
										<div class="icon">
										  <i class="ion ion-stats-bars"></i>
										</div>
									  </div>
								</div>
								<div class="col-lg-2 col-xs-6">
									<div class="small-box bg-blue">
										<div class="inner">
										  <h3><?= $jumlah;?><sup style="font-size: 20px"></sup></h3>
										  <p>Jumlah</p>
										</div>
										<div class="icon">
										  <i class="ion ion-stats-bars"></i>
										</div>
									  </div>
								</div>
							</div>
							<div class="box-header">
								<hr>
								<h4 class="text-center"><strong>STATISTIK PENGUNJUNG WEBSITE <?= $_SESSION['judul']; ?><strong></h4>
								<hr>
							</div>
							<div class="row">
								<div class="col-md-12">
								  <div class="nav-tabs-custom">
									<ul class="nav nav-tabs">
									  <li class="active"><a href="#tab_1" data-toggle="tab">Data</a></li>
									  <li><a href="#tab_2" data-toggle="tab">Grafik</a></li>
									</ul>
									<div class="tab-content">
										<!-- Tabel Data -->
										<div class="tab-pane active" id="tab_1">
											<div class="row">
												<div class="col-sm-12">
													<div class="table-responsive">
														<table class="table table-bordered table-striped table-hover nowrap">
															<thead class="bg-gray">
																<tr>
																	<th class="text-center" width='5%'>No</th>
																	<th class="text-center"><?= $_SESSION['lblx']?></th>
																	<th class="text-center">Pengunjung (Orang)</th>
																</tr>
															</thead>
															<tbody>
																<?php $no = 1; $total = 0; foreach ($main as $data):
																		$total = $total + $data['Total'];
																?>
																<tr>
																	<td class="text-center"><?= $no++;?></td>
																	<td class="text-center">
																	<?php
																		if($_SESSION['lblx']=='Bulan'){
																			echo getBulan($data['tgl']);
																		}else{
																			echo tgl_indo2($data['tgl']);
																		}
																	?>
																	</td>
																	<td class="text-center"><?= $data['Total'];?></td>
																</tr>
															  <?php endforeach;?>
															</tbody>
															<tfoot class="bg-gray disabled color-palette">
																<tr>
																	<th colspan="2" class="text-center">Total</th>
																	<th class="text-center"><?= $total?></th>
																</tr>
															</tfoot>
														</table>
													</div>
												</div>
											</div>
										</div>
										<div class="tab-pane" id="tab_2">
											<!-- Ini Grafik -->
											<div id="chart"> </div>
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

