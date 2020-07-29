<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * View untuk modul Peta
 *
 * donjo-app/views/gis/penduduk_gis.php,
 *
 */

/**
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package	OpenSID
 * @author	Tim Pengembang OpenDesa
 * @copyright	Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright	Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license	http://www.gnu.org/licenses/gpl.html	GPL V3
 * @link 	https://github.com/OpenSID/OpenSID
 */
?>

<link rel="stylesheet" href="<?= base_url()?>assets/bootstrap/css/bootstrap.min.css" media="screen" type="text/css" />
<style type="text/css">
	.table, th {
		text-align: center;
	}
</style>
<div class="modal-body">
	<form id="mainform" name="mainform" action="" method="post">
		<input type="hidden" id="untuk_web" value="<?= $untuk_web?>">
		<div class="row">
			<div class="col-md-12">
				<h4 class="box-title text-center"><b>Data Kependudukan Menurut <?= ($stat);?></b></h4>
				<center>
					<a class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Grafik Data" onclick="grafikType();"><i class="fa fa-bar-chart"></i>&nbsp;&nbsp;Grafik Data&nbsp;&nbsp;</a>
					<a class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Pie Data" onclick="pieType();"><i class="fa fa-pie-chart"></i>&nbsp;&nbsp;Pie Data&nbsp;&nbsp;</a>
				</center>
				<hr>
				<div id="chart" hidden="true"> </div>
				<div class="table-responsive">
					<table class="table table-bordered dataTable table-hover nowrap">
						<thead>
							<tr>
								<th class="padat">No</th>
								<th nowrap>Jenis Kelompok</th>
								<?php if ($lap<20 OR ($lap>50 AND $program['sasaran']==1)): ?>
									<th nowrap colspan="2">Laki-Laki</th>
									<th nowrap colspan="2">Perempuan</th>
								<?php endif; ?>
								<th nowrap colspan="2">Jumlah</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($main as $data): ?>
								<?php if ($lap>50) $tautan_jumlah = site_url("program_bantuan/detail/1/$lap/1"); ?>
								<tr>
									<td class="text-center"><?= $data['no']?></td>
									<td class="text-left"><?= strtoupper($data['nama']);?></td>
									<?php if ($lap<20 OR ($lap>50 AND $program['sasaran']==1)): ?>
										<?php if ($lap<50) $tautan_jumlah = site_url("penduduk/statistik/$lap/$data[id]"); ?>
										<td class="text-right"><a href="<?= $tautan_jumlah?>/1"><?= $data['laki']?></a></td>
										<td class="text-right"><?= $data['persen1'];?></td>
										<td class="text-right"><a href="<?= $tautan_jumlah?>/2"><?= $data['perempuan']?></a></td>
										<td class="text-right"><?= $data['persen2'];?></td>
									<?php endif; ?>
									<td class="text-right">
										<?php if (in_array($lap, array(21, 22, 23, 24, 25, 26, 27))): ?>
											<a href="<?= site_url("keluarga/statistik/$lap/$data[id]")?>"><?= $data['jumlah']?></a>
										<?php else: ?>
											<?php if ($lap<50) $tautan_jumlah = site_url("penduduk/statistik/$lap/$data[id]"); ?>
											<a href="<?= $tautan_jumlah ?>/0"><?= $data['jumlah']?></a>
										<?php endif; ?>
									</td>
									<td class="text-right"><?= $data['persen'];?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</form>
</div>

<script type="text/javascript">
	$('document').ready(function() {
		// Nonaktfikan tautan di tabel statistik kependudukan untuk tampilan Web
		if ($('#untuk_web').val() == 1) {
			$('tbody a').removeAttr('href');
		}
	});

	var chart;

	function grafikType() {
		chart = new Highcharts.Chart({
			chart: {
				renderTo: 'chart',
				defaultSeriesType: 'column'
			},
			title: 0,
			xAxis: {
				title: {
					text: '<?= $stat?>'
				},
				categories: [
				<?php $i=0; foreach ($main as $data): $i++;?>
				<?php if ($data['jumlah'] != "-"): ?><?= "'$i',";?><?php endif; ?>
			<?php endforeach;?>
			]
		},
		yAxis: {
			title: {
				text: 'Jumlah Populasi'
			}
		},
		legend: {
			layout: 'vertical',
			enabled: false
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

		$('#chart').removeAttr('hidden');
	}

	function pieType() {
		chart = new Highcharts.Chart({
			chart: {
				renderTo: 'chart',
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false
			},
			title: 0,
			plotOptions: {
				index: {
					allowPointSelect: true,
					cursor: 'pointer',
					dataLabels:
					{
						enabled: true
					},
					showInLegend: true
				}
			},
			legend: {
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

		$('#chart').removeAttr('hidden');
	}
</script>
<script src="<?= base_url()?>assets/js/highcharts/exporting.js"></script>
<script src="<?= base_url()?>assets/js/highcharts/highcharts-more.js"></script>
