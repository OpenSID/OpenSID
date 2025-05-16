<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<script type="text/javascript">
	$(document).ready(function() {
		hiRes ();
	});

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
				<?php $i=0;foreach ($list_jawab as $data){$i++;?>
					<?php if ($data['nilai'] != "-"){echo "'$data[jawaban]',";}?>
				<?php }?>
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
				<?php foreach ($list_jawab as $data){?>
					<?php if ($data['jawaban'] != "TOTAL"){?>
						<?php if ($data['nilai'] != "-"){?>
							<?= $data['nilai']?>,
						<?php }?>
					<?php }?>
				<?php }?>
				]
			}]
		});
	};
</script>
<style>
	tr#total {
		background:#fffdc5;
		font-size:12px;
		white-space:nowrap;
		font-weight:bold;
	}
	h3 {
		margin-left: 10px;
	}
</style>
<div class="article-single">
	<div class="statistikstyle">
		<div class="container-page mb-20">
			<div class="relative">
				<div class="headingpage border-grey-soft bg-white flexleft" style="border-radius:5px 5px 0 0;">
					<div class="headingpage-image border-grey-soft flexcenter"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/check.svg") ?>" alt=""/></div>
					<h2><?= $indikator['pertanyaan'] ?></h2>
				</div>
				<div class="righthead flexright">
					<a href="<?= site_url("data_analisis?master={$indikator['id_master']}") ?>">
						<div class="mandiri-top-inner bg-color4 flexcenter">
							Kembali
						</div>
					</a>
					
				</div>
			</div>
			<div class="box-article mb-30 bg-white">
				<div class="relative-border p-15 border-grey-soft">
					<div class="head-content" style="text-align:center;">
						<div class="ui-layout-center" id="chart" style="padding: 5px;"></div>
						<div class="table-responsive">
							<table class="table table-striped">
								<thead>
									<tr>
										<td width='3%' style='text-align:center'><b>No.</b></td>
										<td style='text-align:center'><b>Jawaban</b></td>
										<td width='20%' style='text-align:center'><b>Jumlah Responden</b></td>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($list_jawab as $data): ?>
										<tr>
											<td style='text-align:center'><?= $data['no']; ?></td>
											<td style='text-align:left'><?= $data['jawaban']; ?></td>
											<td style='text-align:center'><?= $data['nilai']; ?></td>
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
</div>
