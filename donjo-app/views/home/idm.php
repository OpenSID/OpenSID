<?php  if ($halaman_statis): ?>
	<style>
		.content {padding: 0px !important}
		.small-box .icon {
			padding-top: 10px;
		}
	</style>
	<link rel="stylesheet" href="<?= base_url()?>assets/css/AdminLTE.css" />
	<?php if (is_file($this->theme_folder."/".$this->theme.'/css/first.css')): ?>
		<link rel="stylesheet" href="<?= base_url().$this->theme_folder.'/'.$this->theme.'/css/first.css' ?>" />
	<?php endif; ?>
	<?php if (is_file("desa/themes/".$this->theme.'/assets/css/desa-web.css')): ?>
		<link type='text/css' href="<?= base_url().$this->theme_folder.'/'.$this->theme.'/assets/css/desa-web.css' ?>" rel='stylesheet' />
	<?php endif; ?>
	<?php if (is_file("desa/css/".$this->theme."/desa-web.css")): ?>
		<link type='text/css' href="<?= base_url()?>desa/css/<?= $this->theme ?>/desa-web.css" rel='Stylesheet' />
	<?php endif; ?>
<?php endif; ?>

<script type="text/javascript">

$(document).ready(function () {

	Highcharts.chart('container', {
    chart: {
        type: 'pie',
        options3d: {
            enabled: true,
            alpha: 45
        }
    },
    title: {
        text: 'Indeks Desa Membangun (IDM)'
    },
    subtitle: {
        text: 'SKOR : IKS, IKE, IKL'
    },

    plotOptions: {
			series: {
				colorByPoint: true
			},
			pie: {
				allowPointSelect: true,
				cursor: 'pointer',
				showInLegend: true,
				depth: 45,
				innerSize: 70,
				dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.y:,.2f} / {point.percentage:.1f} %'
            }
			}
    },
    series: [{
        name: 'SKOR',
				shadow: 1,
				border: 1,
        data: [
					['IKS', <?= $idm->ROW[35]->SKOR ?>],
					['IKE', <?= $idm->ROW[48]->SKOR ?>],
					['IKL', <?= $idm->ROW[52]->SKOR ?>]
        ]
    }]
});


});
</script>

<style>
	.status-idm-kiri {
		padding-right: 5px;
	}
	.status-idm-kanan {
		padding-left: 5px;
	}
	.tabel-skor {
		padding-right: 0px;
	}
	.table-striped > tbody >tr.judul {
		background-color: lightgrey !important;
	}
	tr.judul > td,
	tr.judul > th {
	background-color: inherit !important;
	}
	.table > thead > tr > th,
	.table > tbody > tr > th,
	.table > tfoot > tr > th,
	.table > thead > tr > td,
	.table > tbody > tr > td,
	.table > tfoot > tr > td {
		font-size: 12px;
		padding: 5px;
	}
	tr.lebih {
		display: none;
	}
	.small-box .icon {
		font-size: 75px;
	}
	.small-box h3 {
		font-size: 30px;
	}
	.input-sm {
		padding: 4px 4px;
	}

	@media (max-width:780px) {
		.btn-group-vertical {
			display: block;
		}
	}

	.table-responsive {
		min-height:275px;
	}

	#container {
		height: 400px;
	}

	.highcharts-figure, .highcharts-data-table table {
		min-width: 310px;
		max-width: 800px;
		margin: 1em auto;
	}

	.highcharts-data-table table {
		font-family: Verdana, sans-serif;
		border-collapse: collapse;
		border: 1px solid #EBEBEB;
		margin: 10px auto;
		text-align: center;
		width: 100%;
		max-width: 500px;
	}
	.highcharts-data-table caption {
		padding: 1em 0;
		font-size: 1.2em;
		color: #555;
	}
	.highcharts-data-table th {
		font-weight: 600;
		padding: 0.5em;
	}
	.highcharts-data-table td, .highcharts-data-table th, .highcharts-data-table caption {
		padding: 0.5em;
	}
	.highcharts-data-table thead tr, .highcharts-data-table tr:nth-child(even) {
		background: #f8f8f8;
	}
	.highcharts-data-table tr:hover {
		background: #f1f7ff;
	}
</style>

<div class="content-wrapper">
	<?php if (empty($halaman_statis)): ?>
		<section class="content-header">
			<h1>Status IDM <?= ucwords($this->setting->sebutan_desa)?></h1>
			<ol class="breadcrumb">
				<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
				<li class="active">Status IDM <?= ucwords($this->setting->sebutan_desa)?></li>
			</ol>
		</section>
	<?php endif; ?>
	<section class="content">
		<div class="box box-info">
			<div class="box-body">
				<div class="row">
					<div class="col-sm-12">
						<?php if ($idm->error_msg): ?>
							<div class="alert alert-danger">
								<?= $idm->error_msg ?>
							</div>
						<?php endif; ?>
						<div class="row">
							<div class="col-lg-3 col-xs-6 status-idm-kiri">
								<div class="small-box bg-blue">
									<div class="inner">
										<h3><?= number_format($idm->SUMMARIES->SKOR_SAAT_INI, 4) ?></sup></h3>
										<p>SKOR IDM SAAT INI</p>
									</div>
									<div class="icon">
										<i class="ion ion-arrow-graph-up-right"></i>
									</div>
								</div>
							</div>
							<div class="col-lg-3 col-xs-6 status-idm-kiri status-idm-kanan">
								<div class="small-box bg-yellow">
									<div class="inner">
										<h3><?= $idm->SUMMARIES->STATUS ?><sup style="font-size: 20px"></sup></h3>
										<p>STATUS IDM</p>
									</div>
									<div class="icon">
										<i class="ion-ios-pulse-strong"></i>
									</div>
								</div>
							</div>
							<div class="col-lg-3 col-xs-6 status-idm-kiri status-idm-kanan">
								<div class="small-box bg-green">
									<div class="inner">
										<h3><?= $idm->SUMMARIES->TARGET_STATUS ?><sup style="font-size: 20px"></sup></h3>
										<p>TARGET STATUS</p>
									</div>
									<div class="icon">
										<i class="ion ion-stats-bars"></i>
									</div>
								</div>
							</div>
							<div class="col-lg-3 col-xs-6 status-idm-kanan">
								<div class="small-box bg-red">
									<div class="inner">
										<h3><?= number_format($idm->SUMMARIES->SKOR_MINIMAL, 4) ?><sup style="font-size: 20px"></sup></h2>
										<p>SKOR IDM MINIMAL</p>
									</div>
									<div class="icon">
										<i class="ion ion-ios-pie"></i>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-4 tabel-skor">
										<!-- Tabel Data -->
										<div class="table-responsive">
											<table class="table table-bordered table-striped dataTable table-hover">
												<tbody>
													<tr>
															<tr>
																<th class="horizontal">PROVINSI</th>
																<td> : <?= $idm->IDENTITAS[0]->nama_provinsi ?></td>
															</tr>
															<tr>
																<th class="horizontal">KABUPATEN</th>
																<td nowrap> : <?= $idm->IDENTITAS[0]->nama_kab_kota ?></td>
															</tr>
															<tr>
																<th class="horizontal"><?= strtoupper($this->setting->sebutan_kecamatan) ?></th>
																<td> : <?= $idm->IDENTITAS[0]->nama_kecamatan ?></td>
															</tr>
															<tr>
																<th class="horizontal"><?= strtoupper($this->setting->sebutan_desa) ?></th>
																<td> : <?= $idm->IDENTITAS[0]->nama_desa ?></td>
															</tr>
													</tr>
												</tbody>
											</table>
										</div>
									</div>

									<div class="col-md-8">
										<figure class="highcharts-figure">
											<div id="container"></div>
										</figure>
									</div>

								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-sm-12">
						<div class="table-responsive">
							<table class="table table-bordered table-striped dataTable table-hover">
								<thead class="bg-gray color-palette">
									<tr>
										<th rowspan="2" class="padat">NO</th>
										<th rowspan="2" >INDIKATOR IDM</th>
										<th rowspan="2" >SKOR</th>
										<th rowspan="2" >KETERANGAN</th>
										<th rowspan="2" nowrap>KEGIATAN YANG DAPAT DILAKUKAN</th>
										<th rowspan="2" >+NILAI</th>
										<th colspan="6" class="text-center">YANG DAPAT MELAKSANAKAN KEGIATAN</th>
									</tr>
									<tr>
										<th>PUSAT</th>
										<th>PROVINSI</th>
										<th>KABUPATEN</th>
										<th>DESA</th>
										<th>CSR</th>
										<th>LAINNYA</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($idm->ROW as $data): ?>
										<tr class="<?php empty($data->NO) and print('judul'); ?> ">
											<td class="text-center"><?= $data->NO ?></td>
											<td style="min-width: 150px;"><?= $data->INDIKATOR ?></td>
											<td class="padat"><?= $data->SKOR ?></td>
											<td style="min-width: 250px;"><?= $data->KETERANGAN ?></td>
											<td><?= $data->KEGIATAN ?></td>
											<td><?= $data->NILAI ?></td>
											<td><?= $data->PUSAT ?></td>
											<td><?= $data->PROV ?></td>
											<td><?= $data->KAB ?></td>
											<td><?= $data->DESA ?></td>
											<td><?= $data->CSR ?></td>
											<td><?= $data->SKOR[INDIKATOR['IKS 2020']] ?></td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
