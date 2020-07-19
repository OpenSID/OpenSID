
<!-- Pengaturan Grafik (Graph) Data Statistik-->
<script type="text/javascript">
<?php if ($jenis_grafik == 'bar'): ?>
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
				text: 'Data Statistik <?= $stat?>'
			},
			xAxis:
			{
				title:
				{
					text: '<?= $stat?>'
				},
        categories: [
					<?php $i=0; foreach ($main as $data): $i++;?>
					  <?php if ($data['jumlah'] != "-"): ?><?= "'$i',";?><?php endif; ?>
					<?php endforeach;?>
				]
			},
			yAxis:
			{
				title:
				{
					text: 'Jumlah Populasi'
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
				  <?php if (!in_array($data['nama'], array("TOTAL", "JUMLAH", "PENERIMA"))): ?>
					  <?php if ($data['jumlah'] != "-"): ?>
							['<?= strtoupper($data['nama'])?>',<?= $data['jumlah']?>],
						<?php endif; ?>
					<?php endif; ?>
				<?php endforeach;?>]
			}]
		});
	});
<?php else: ?>
	$(document).ready(function ()
	{
		chart = new Highcharts.Chart({
			chart:
			{
				renderTo: 'chart',
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false
			},
			title:
			{
				text: 'Data Statistik <?= $stat?>'
			},
			subtitle:
			{
				text: 'Berdasarkan <?= $stat?>'
			},
			plotOptions:
			{
				index:
				{
					allowPointSelect: true,
					cursor: 'pointer',
					dataLabels:
					{
						enabled: true
					},
					showInLegend: true
				}
			},
			legend:
			{
				layout: 'vertical',
				backgroundColor: '#FFFFFF',
				align: 'right',
				verticalAlign: 'top',
				x: -30,
				y: 0,
				floating: true,
				shadow: true,
        enabled:true
			},
			series: [{
				type: 'pie',
				name: 'Populasi',
				data: [
					<?php foreach ($main as $data): ?>
						<?php if (!in_array($data['nama'], array("TOTAL", "JUMLAH", "PENERIMA"))): ?>
							<?php if ($data['jumlah'] != "-"): ?>
								["<?= strtoupper($data['nama'])?>",<?= $data['jumlah']?>],
							<?php endif; ?>
						<?php endif; ?>
					<?php endforeach;?>
				]
			}]
		});
	});
<?php endif; ?>
</script>
<!-- Highcharts -->
<script src="<?= base_url()?>assets/js/highcharts/exporting.js"></script>
<script src="<?= base_url()?>assets/js/highcharts/highcharts-more.js"></script>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Statistik Kependudukan (Grafik)</h1>
		<ol class="breadcrumb">
			<li><a href="<?=site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Statistik Kependudukan (Grafik)</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="mainform" name="mainform" action="" method="post">
			<div class="row">
				<div class="col-md-3">
          <?php $this->load->view('statistik/laporan/side-menu.php')?>
				</div>
				<div class="col-md-9">
					<div class="box box-info">
						<div class="box-body">
							<div id="chart"> </div>
							<div class="col-sm-12">
								<?php if ($lap < 50): ?>
									<h4 class="box-title"><b>Data Kependudukan menurut <?= ($stat);?></b></h4>
								<?php else: ?>
									<h4 class="box-title"><b>Data Peserta Program <?= ($program['nama'])?></b></h4>
								<?php endif; ?>
								<?php if($lap <= 20 AND $lap <> 'kelas_sosial' AND $lap <> 'bantuan_keluarga' AND $lap <> 'bantuan_penduduk') : ?>
									<div class="row">
										<div class="col-sm-12 form-inline">
											<form action="" id="mainform" method="post">
												<select class="form-control input-sm " name="dusun" onchange="formAction('mainform','<?= site_url('statistik/dusun/'.$lap.'/'.$jenis_grafik)?>')">
													<option value="">Pilih <?= ucwords($this->setting->sebutan_dusun)?></option>
													<?php foreach ($list_dusun AS $data): ?>
														<option value="<?= $data['dusun']?>" <?php $dusun == $data['dusun'] and print('selected') ?>><?= strtoupper($data['dusun'])?></option>
													<?php endforeach; ?>
												</select>
												<?php if ($dusun): ?>
													<select class="form-control input-sm" name="rw" onchange="formAction('mainform','<?= site_url('statistik/rw/'.$lap.'/'.$jenis_grafik)?>')" >
														<option value="">RW</option>
														<?php foreach ($list_rw AS $data): ?>
															<option value="<?= $data['rw']?>" <?php $rw == $data['rw'] and print('selected') ?>><?= $data['rw']?></option>
														<?php endforeach; ?>
													</select>
												<?php endif; ?>
												<?php if ($rw): ?>
													<select class="form-control input-sm" name="rt" onchange="formAction('mainform','<?= site_url('statistik/rt/'.$lap.'/'.$jenis_grafik)?>')">
														<option value="">RT</option>
														<?php foreach ($list_rt AS $data): ?>
															<option value="<?= $data['rt']?>" <?php $rt == $data['rt'] and print('selected') ?>><?= $data['rt']?></option>
														<?php endforeach; ?>
													</select>
												<?php endif; ?>
											</form>
										</div>
									</div>
								<?php endif ?>
								<div class="table-responsive">
									<table class="table table-bordered dataTable table-hover nowrap">
										<thead>
											<tr>
												<th width='5%'>No</th>
												<th width='50%'>Jenis Kelompok</th>
												<th width='15%'colspan="2">Jumlah</th>
												<?php if ($jenis_laporan == 'penduduk'): ?>
													<th width='15%' colspan="2">Laki-Laki</th>
													<th width='15%' colspan="2">Perempuan</th>
												<?php endif; ?>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($main as $data): ?>
												<?php if ($lap>50) $tautan_jumlah = site_url("program_bantuan/detail/1/$lap/1"); ?>
												<tr>
													<td><?= $data['no']?></td>
													<td><?= strtoupper($data['nama']);?></td>
													<td>
														<?php if (in_array($lap, array(21, 22, 23, 24, 25, 26, 27, 'kelas_sosial', 'bantuan_keluarga'))): ?>
															<a href="<?= site_url("keluarga/statistik/$lap/$data[id]")?>/0" <?php if ($data['id']=='JUMLAH'): ?>class="disabled"<?php endif; ?>><?= $data['jumlah']?></a>
														<?php else: ?>
															<?php if ($lap<50) $tautan_jumlah = site_url("penduduk/statistik/$lap/$data[id]"); ?>
															<a href="<?= $tautan_jumlah ?>/0" <?php if ($data['id']=='JUMLAH'): ?> class="disabled"<?php endif; ?>><?= $data['jumlah']?></a>
														<?php endif; ?>
													</td>
													<td><?= $data['persen'];?></td>
													<?php if (in_array($lap, array(21, 22, 23, 24, 25, 26, 27, 'kelas_sosial', 'bantuan_keluarga'))):
															$tautan_jumlah = site_url("keluarga/statistik/$lap/$data[id]");
															elseif ($lap<50): $tautan_jumlah = site_url("penduduk/statistik/$lap/$data[id]");endif;
													?>
													<?php if ($jenis_laporan == 'penduduk'): ?>
														<td><a href="<?= $tautan_jumlah?>/1" <?php if ($data['id']=='JUMLAH'): ?>class="disabled"<?php endif; ?>><?= $data['laki']?></a></td>
														<td><?= $data['persen1'];?></td>
														<td><a href="<?= $tautan_jumlah?>/2" <?php if ($data['id']=='JUMLAH'): ?>class="disabled"<?php endif; ?>><?= $data['perempuan']?></a></td>
														<td><?= $data['persen2'];?></td>
													<?php endif; ?>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								</div>
							</div>

							<?php if (in_array($lap, array('bantuan_keluarga', 'bantuan_penduduk'))):?>
								<?php $this->load->view('statistik/peserta_bantuan'); ?>
              <?php endif;?>

						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>
