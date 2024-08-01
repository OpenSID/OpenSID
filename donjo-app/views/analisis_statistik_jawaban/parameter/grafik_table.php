
<!-- Pengaturan Grafik (Graph) Data Statistik-->
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
				<?php $i = 0;

                foreach ($main as $data) {
                    $i++; ?>
				 <?php if ($data['nilai'] != '-') {
                     echo "'{$data['jawaban']}',";
                 } ?>
				<?php
                }?>
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
				<?php foreach ($main as $data) {?>
				 <?php if ($data['jawaban'] != 'TOTAL') {?>
				 <?php if ($data['nilai'] != '-') {?>
						<?= $data['nilai']?>,
					<?php }?>
					<?php }?>
				<?php }?>]

			}]
		});
	};
</script>
<!-- Highcharts -->
<script src="<?= asset('js/highcharts/highcharts.js') ?>"></script>
<script src="<?= asset('js/highcharts/exporting.js') ?>"></script>
<script src="<?= asset('js/highcharts/highcharts-more.js') ?>"></script>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Statistik Jawaban</h1>
		<ol class="breadcrumb">
		<li><a href="<?=site_url('beranda')?>"><i class="fa fa-home"></i> Beranda</a></li>
			<li><a href="<?=site_url('analisis_master/clear')?>"> Master Analisis</a></li>
			<li><a href="<?= site_url('analisis_master/leave'); ?>"><?= $analisis_master['nama']; ?></a></li>
			<li><a href="<?=site_url('analisis_statistik_jawaban')?>">Laporan Per Indikator</a></li>
			<li class="active">Statistik Jawaban</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="mainform" name="mainform" method="post">
			<div class="row">
				<div class="col-md-3">
					<?php $this->load->view('analisis_master/left', $data); ?>
				</div>
				<div class="col-md-9">
					<div class="box box-info">
						<div class="box-body">
							<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
								<div class="col-sm-12">
									<select class="form-control input-sm " name="dusun" onchange="formAction('mainform','<?= site_url("analisis_statistik_jawaban/dusun3/{$analisis_statistik_jawaban['id']}")?>')">
										<option value="">Pilih <?= ucwords($this->setting->sebutan_dusun)?></option>
										<?php foreach ($list_dusun as $data): ?>
											<option value="<?= $data['dusun']?>" <?php if ($dusun == $data['dusun']): ?>selected<?php endif ?>><?= strtoupper($data['dusun'])?></option>
										<?php endforeach; ?>
									</select>
									<?php if ($dusun): ?>
										<select class="form-control input-sm" name="rw" onchange="formAction('mainform','<?= site_url("analisis_statistik_jawaban/rw3/{$analisis_statistik_jawaban['id']}")?>')" >
											<option value="">RW</option>
											<?php foreach ($list_rw as $data): ?>
												<option value="<?= $data['rw']?>" <?php if ($rw == $data['rw']): ?>selected<?php endif ?>><?= $data['rw']?></option>
											<?php endforeach; ?>
										</select>
									<?php endif; ?>
									<?php if ($rw): ?>
										<select class="form-control input-sm" name="rt" onchange="formAction('mainform','<?= site_url("analisis_statistik_jawaban/rt3/{$analisis_statistik_jawaban['id']}")?>')">
											<option value="">RT</option>
											<?php foreach ($list_rt as $data): ?>
												<option value="<?= $data['rt']?>" <?php if ($rt == $data['rt']): ?>selected<?php endif ?>><?= $data['rt']?></option>
											<?php endforeach; ?>
										</select>
									<?php endif; ?>
									<a href="<?= site_url('analisis_statistik_jawaban')?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left "></i> Kembali Ke Laporan Per Indikator</a>
								</div>
								<div class="col-sm-12">
									<h5 class="box-title"><b><?= $analisis_statistik_jawaban['pertanyaan']?></b></h5>
									<div class="table-responsive">
										<table class="table table-bordered dataTable table-hover table-striped">
											<thead>
												<tr>
													<th>No</th>
													<th>Jawaban</th>
													<th>Jumlah</th>
												</tr>
											</thead>
											<tbody>
												<?php foreach ($main as $data): ?>
													<tr>
														<td align="center" width="2"><?= $data['no']?></td>
														<td><?= $data['jawaban']?></td>
														<td><?= $data['nilai']?></td>
													</tr>
												<?php endforeach; ?>
											</tbody>
										</table>
										<div id="chart"></div>
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

