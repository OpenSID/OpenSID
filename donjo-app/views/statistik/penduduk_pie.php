
<!-- Pengaturan Grafik Chart Pie Data Statistik-->
<script type="text/javascript">
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
				text: 'Data Statistik Kependudukan'
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
						<?php if ($data['nama'] != "TOTAL" and $data['nama'] != "JUMLAH"): ?>
							<?php if ($data['jumlah'] != "-"): ?>
								['<?= strtoupper($data['nama'])?>',<?= $data['jumlah']?>],
							<?php endif; ?>
						<?php endif; ?>
					<?php endforeach;?>
				]
			}]
		});
	});
</script>
<!-- Highcharts -->
<script src="<?= base_url()?>assets/js/highcharts/highcharts.js"></script>
<script src="<?= base_url()?>assets/js/highcharts/exporting.js"></script>
<script src="<?= base_url()?>assets/js/highcharts/highcharts-more.js"></script>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Statistik Kependudukan (Pie)</h1>
		<ol class="breadcrumb">
			<li><a href="<?=site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Statistik Kependudukan (Pie)</li>
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
							<div id="chart"></div>
							<div class="col-sm-12">
								<?php if ($lap < 50): ?>
									<h4 class="box-title"><b>Data Kependudukan menurut <?= ($stat);?></b></h4>
								<?php else: ?>
									<h4 class="box-title"><b>Data Peserta Program <?= ($program['nama'])?></b></h4>
								<?php endif; ?>
								<div class="table-responsive">
									<table class="table table-bordered dataTable table-hover nowrap">
										<thead>
											<tr>
												<th width='5%'>No</th>
												<th width='50%'>Jenis Kelompok</th>
												<?php if ($lap<20 OR ($lap>50 AND $program['sasaran']==1)): ?>
													<th width='15%' colspan="2">Laki-Laki</th>
													<th width='15%' colspan="2">Perempuan</th>
												<?php endif; ?>
												<th width='15%'colspan="2">Jumlah</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($main as $data): ?>
											<?php if ($lap>50) $tautan_jumlah = site_url("program_bantuan/detail/1/$lap/1"); ?>
												<tr>
													<td><?= $data['no']?></td>
													<td><?= strtoupper($data['nama']);?></td>
													<?php if ($lap<20 OR ($lap>50 AND $program['sasaran']==1)): ?>
														<?php if ($lap<50) $tautan_jumlah = site_url("penduduk/statistik/$lap/$data[id]"); ?>
														<td><a href="<?= $tautan_jumlah?>/1"><?= $data['laki']?></a></td>
														<td><?= $data['persen1'];?></td>
														<td><a href="<?= $tautan_jumlah?>/2"><?= $data['perempuan']?></a></td>
														<td><?= $data['persen2'];?></td>
													<?php endif; ?>
													<td>
														<?php if ($lap==21 OR $lap==22 OR $lap==23 OR $lap==24 OR $lap==25 OR $lap==26 OR $lap==27): ?>
															<a href="<?= site_url("keluarga/statistik/$lap/$data[id]")?>"><?= $data['jumlah']?></a>
														<?php else: ?>
															<?php if ($lap<50) $tautan_jumlah = site_url("penduduk/statistik/$lap/$data[id]"); ?>
															<a href="<?= $tautan_jumlah ?>/0"><?= $data['jumlah']?></a>
														<?php endif; ?>
													</td>
													<td><?= $data['persen'];?></td>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>

