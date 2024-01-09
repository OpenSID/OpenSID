<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini:
 *
 * View untuk modul Statistik Kependudukan
 *
 * donjo-app/views/statistik/penduduk_grafik_web.php,
 */

/*
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
 * @copyright	  Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright	  Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license	http://www.gnu.org/licenses/gpl.html	GPL V3
 *
 * @see 	https://github.com/OpenSID/OpenSID
 */
?>

<script type="text/javascript">
	const rawData_<?=$lap?> = Object.values(<?= json_encode($stat, JSON_THROW_ON_ERROR) ?>);
	const type_<?=$lap?> = '<?= $tipe == 1 ? 'column' : 'pie' ?>';
	const legend_<?=$lap?> = Boolean(!<?= ($tipe) ?>);
	let categories_<?=$lap?> = [];
	let data_<?=$lap?> = [];
	let i_<?=$lap?> = 1;
	let status_tampilkan_<?=$lap?> = true;
	for (const stat of rawData_<?=$lap?>) {
		if (stat.nama !== 'TOTAL' && stat.nama !== 'JUMLAH' && stat.nama != 'PENERIMA') {
			let filteredData = [stat.nama, parseInt(stat.jumlah)];
			categories_<?=$lap?>.push(i_<?=$lap?>);
			data_<?=$lap?>.push(filteredData);
			i_<?=$lap?>++;
		}
	}

	function tampilkan_nol(tampilkan = false) {
		if (tampilkan) {
			$(".nol").parent().show();
		} else {
			$(".nol").parent().hide();
		}
	}

	function toggle_tampilkan_<?=$lap?>() {
		$('#showData').click();
		tampilkan_nol(status_tampilkan_<?=$lap?>);
		status_tampilkan_<?=$lap?> = !status_tampilkan_<?=$lap?>;
		if (status_tampilkan_<?=$lap?>) $('#tampilkan').text('Tampilkan Nol');
		else $('#tampilkan').text('Sembunyikan Nol');
	}

	function switchType_<?=$lap?>() {
		var chartType = chart_<?=$lap?>.series[0].type;
		chart_<?=$lap?>.series[0].update({
			type: (chartType === 'pie') ? 'column' : 'pie'
		});

		$("#barType_<?=$lap?>").html((chartType === 'pie') ? 'Pie Cart' : 'Bar Graph');
	}

	$(document).ready(function () {
		tampilkan_nol(false);
		if (<?=$this->setting->statistik_chart_3d?>) {
			chart_<?=$lap?> = new Highcharts.Chart({
				chart: {
					renderTo: 'container_<?=$lap?>',
					options3d: {
						enabled: true,
						alpha: 45
					}
				},
				title: 0,
				yAxis: {
					showEmpty: false,
				},
				xAxis: {
					categories: categories_<?=$lap?>,
				},
				plotOptions: {
					series: {
						colorByPoint: true
					},
					column: {
						pointPadding: -0.1,
						borderWidth: 0,
						showInLegend: false,
						depth: 45
					},
					pie: {
						allowPointSelect: true,
						cursor: 'pointer',
						showInLegend: true,
						depth: 45,
						innerSize: 70
					}
				},
				legend: {
					enabled: legend_<?=$lap?>
				},
				series: [{
					type: type_<?=$lap?>,
					name: 'Jumlah Populasi',
					shadow: 1,
					border: 1,
					data: data_<?=$lap?>
				}]
			});
		} else {
			chart_<?=$lap?> = new Highcharts.Chart({
				chart: {
					renderTo: 'container_<?=$lap?>'
				},
				title: 0,
				yAxis: {
					showEmpty: false,
				},
				xAxis: {
					categories: categories_<?=$lap?>,
				},
				plotOptions: {
					series: {
						colorByPoint: true
					},
					column: {
						pointPadding: -0.1,
						borderWidth: 0,
						showInLegend: false,
					},
					pie: {
						allowPointSelect: true,
						cursor: 'pointer',
						showInLegend: true,
					}
				},
				legend: {
					enabled: legend_<?=$lap?>
				},
				series: [{
					type: type_<?=$lap?>,
					name: 'Jumlah Populasi',
					shadow: 1,
					border: 1,
					data: data_<?=$lap?>
				}]
			});
		}

		$('#showData').click(function () {
			$('tr.lebih').show();
			$('#showData').hide();
			tampilkan_nol(false);
		});

	});
</script>
<!-- TODO: Pindahkan ke external css -->
<style>
	tr.lebih {
		display: none;
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
</style>

<div class="box box-danger">
	<div class="box-header with-border">
		<h3 class="box-title"> Grafik <?= $heading ?></h3>
		<div class="box-tools pull-right">
			<div class="btn-group-xs">
				<a class="btn btn-primary btn-xs" id="barType_<?=$lap?>" onclick="switchType_<?=$lap?>();">Bar Graph</a>
			</div>
		</div>
	</div>
	<div class="box-body">
		<div id="container_<?=$lap?>"></div>
		<div id="contentpane">
			<div class="ui-layout-north panel top"></div>
		</div>
	</div>
</div>

<div class="box box-danger">
	<div class="box-header with-border">
		<h3 class="box-title">Tabel <?= $heading ?></h3>
	</div>
	<div class="box-body">
		<div class="table-responsive">
		<table class="table table-striped">
			<thead>
			<tr>
				<th rowspan="2">No</th>
				<th rowspan="2" style='text-align:left;'>Kelompok</th>
				<th colspan="2">Jumlah</th>
				<th colspan="2">Laki-laki</th>
				<th colspan="2">Perempuan</th>
			</tr>
			<tr>
				<th style='text-align:right'>n</th><th style='text-align:right'>%</th>
				<th style='text-align:right'>n</th><th style='text-align:right'>%</th>
				<th style='text-align:right'>n</th><th style='text-align:right'>%</th>
			</tr>
			</thead>
			<tbody>
				<?php $i = 0;
$l           = 0;
$p           = 0;
$hide        = '';
$h           = 0;
$jm1         = 1;
$jm          = count($stat); ?>
				<?php foreach ($stat as $data):?>
					<?php $jm1++; ?>
					<?php $h++; ?>
					<?php if ($h > 12 && $jm > 10): ?>
						<?php $hide = 'lebih'; ?>
					<?php endif; ?>
					<tr class="<?=$hide?>">
						<td class="angka">
							<?php if ($jm1 > $jm - 2):?>
								<?=$data['no']?>
							<?php else:?>
								<?=$h?>
							<?php endif; ?>
						</td>
						<td><?=$data['nama']?></td>
						<td class="angka <?php if (($jm1 <= $jm - 2) && ($data['jumlah'] == 0)) {
                            echo 'nol';
                        } ?>"><?=$data['jumlah']?></td>
						<td class="angka"><?=$data['persen']?></td>
						<td class="angka"><?=$data['laki']?></td>
						<td class="angka"><?=$data['persen1']?></td>
						<td class="angka"><?=$data['perempuan']?></td>
						<td class="angka"><?=$data['persen2']?></td>
					</tr>
					<?php $i += $data['jumlah']; ?>
					<?php $l += $data['laki']; ?>
					<?php $p += $data['perempuan']; ?>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php if ($hide == 'lebih'):?>
			<div style='float: left;'>
				<button class='uibutton special' id='showData'>Selengkapnya...</button>
			</div>
		<?php endif; ?>
		<div style="float: right;">
			<button id='tampilkan' onclick="toggle_tampilkan_<?=$lap?>();" class="uibutton special">Tampilkan Nol</button>
		</div>
	</div>
	</div>
</div>
