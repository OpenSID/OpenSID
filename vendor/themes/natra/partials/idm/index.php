<link rel="stylesheet" href="<?= base_url()?>assets/css/AdminLTE.css" />
<!-- Font Awesome -->
<link rel="stylesheet" href="<?= asset('bootstrap/css/font-awesome.min.css') ?>">
<!-- Ionicons -->
<link rel="stylesheet" href="<?= asset('bootstrap/css/ionicons.min.css') ?>">
<?php if (is_file($this->theme_folder . '/' . $this->theme . '/css/first.css')): ?>
	<link rel="stylesheet" href="<?= base_url() . $this->theme_folder . '/' . $this->theme . '/css/first.css' ?>" />
<?php endif; ?>
<?php if (is_file($this->theme_folder . '/' . $this->theme . '/assets/css/desa-web.css')): ?>
	<link type='text/css' href="<?= base_url() . $this->theme_folder . '/' . $this->theme . '/assets/css/desa-web.css' ?>" rel='stylesheet' />
<?php endif; ?>
<?php if (is_file('desa/css/' . $this->theme . '/desa-web.css')): ?>
	<link type='text/css' href="<?= base_url()?>desa/css/<?= $this->theme ?>/desa-web.css" rel='Stylesheet' />
<?php endif; ?>
<style>
    .small-box .icon {
        top: -15px;
        font-size: 85px;
    }
</style>

<div class="content-wrapper">
	<?php if (empty($halaman_statis)): ?>
	<section class="content-header">
			<h1>Status IDM <?= ucwords($this->setting->sebutan_desa) . ' ' . $tahun; ?></h1>
			<ol class="breadcrumb">
				<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
				<li class="active">Status IDM <?= ucwords($this->setting->sebutan_desa); ?></li>
			</ol>
		</section>
	<?php endif; ?>
	<section class="content" id="maincontent">
		<div class="box box-info">
			<div class="box-body">
				<?php if ($idm->error_msg): ?>
					<div class="alert alert-danger">
						<?= $idm->error_msg ?>
					</div>
				<?php else: ?>
					<div class="row">
						<div class="col-lg-6 col-xs-12">
							<div class="row">
								<div class="col-lg-6 col-xs-12">
									<div class="small-box bg-blue">
										<div class="inner">
											<h3><?= number_format($idm->SUMMARIES->SKOR_SAAT_INI, 4) ?></h3>
											<p>SKOR IDM SAAT INI</p>
										</div>
										<div class="icon">
											<i class="ion ion-stats-bars"></i>
										</div>
									</div>
								</div>
								<div class="col-lg-6 col-xs-12">
									<div class="small-box bg-yellow">
										<div class="inner">
											<h3><?= $idm->SUMMARIES->STATUS ?></h3>
											<p>STATUS IDM</p>
										</div>
										<div class="icon">
											<i class="ion-ios-pulse-strong"></i>
										</div>
									</div>
								</div>
								<div class="col-lg-6 col-xs-12">
									<div class="small-box bg-red">
										<div class="inner">
											<h3><?= number_format($idm->SUMMARIES->SKOR_MINIMAL, 4) ?></h2>
											<p>SKOR IDM MINIMAL</p>
										</div>
										<div class="icon">
											<i class="ion ion-ios-pie"></i>
										</div>
									</div>
								</div>
								<div class="col-lg-6 col-xs-12">
									<div class="small-box bg-green">
										<div class="inner">
											<h3><?= $idm->SUMMARIES->TARGET_STATUS ?></h3>
											<p>TARGET STATUS</p>
										</div>
										<div class="icon">
											<i class="ion ion-arrow-graph-up-right"></i>
										</div>
									</div>
								</div>

								<div class="col-lg-12 col-xs-12">
									<div class="table-responsive">
										<table class="table table-bordered table-striped dataTable table-hover">
											<tbody>
												<tr>
													<td width="30%">PROVINSI</td>
													<td width="1">:</td>
													<td><?= $idm->IDENTITAS[0]->nama_provinsi ?></td>
												</tr>
												<tr>
													<td>KABUPATEN</td>
													<td> : </td>
													<td><?= $idm->IDENTITAS[0]->nama_kab_kota ?></td>
												</tr>
												<tr>
													<td><?= strtoupper($this->setting->sebutan_kecamatan) ?></td>
													<td> : </td>
													<td><?= $idm->IDENTITAS[0]->nama_kecamatan ?></td>
												</tr>
												<tr>
													<td><?= strtoupper($this->setting->sebutan_desa) ?></td>
													<td> : </td>
													<td><?= $idm->IDENTITAS[0]->nama_desa ?></td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>

						<div class="col-lg-6 col-xs-12">
							<figure class="highcharts-figure">
								<div id="container"></div>
							</figure>
						</div>
					</div>

					<div class="row"><hr></div>

						<div class="col-md-8">
							<figure class="highcharts-figure">
								<div id="container"></div>
							</figure>
						</div>
					</div>

					<div class="row">
						<div class="col-sm-12">
							<div class="table-responsive">
								<table class="table table-bordered dataTable table-striped table-hover tabel-daftar">
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
											<tr class="<?php empty($data->NO) && print 'judul'; ?> ">
												<td class="text-center"><?= $data->NO ?></td>
												<td style="min-width: 150px;"><?= $data->INDIKATOR ?></td>
												<td class="padat"><?= $data->SKOR ?></td>
												<td style="min-width: 250px;"><?= $data->KETERANGAN ?></td>
												<td><?= $data->KEGIATAN ?></td>
												<td class="padat"><?= $data->NILAI ?></td>
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
				<?php endif; ?>
			</div>
		</div>
	</section>
</div>

<?php if (! $idm->error_msg): ?>
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
				text: 'Indeks Desa Membangun (IDM) <?= $tahun; ?>'
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
<?php endif; ?>