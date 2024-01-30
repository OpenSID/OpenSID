<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini:
 *
 * View untuk modul Statistik Kependudukan
 *
 * donjo-app/views/statistik/penduduk.php,
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

<div class="content-wrapper">
	<section class="content-header">
		<h1>Statistik Kependudukan</h1>
		<ol class="breadcrumb">
			<li><a href="<?=site_url('beranda'); ?>"><i class="fa fa-home"></i> Beranda</a></li>
			<li class="active">Statistik Kependudukan <?= $dusun; ?></li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="mainform" name="mainform" method="post">
			<div class="row">
				<div class="col-md-4">
					<?php $this->load->view('statistik/side_menu.php'); ?>
				</div>
				<div class="col-md-8">
					<div class="box box-info">
						<div class="box-header with-border">
							<a href="<?=site_url('statistik/dialog/cetak'); ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Laporan" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Cetak Laporan"><i class="fa fa-print "></i>Cetak
							</a>
							<a href="<?=site_url('statistik/dialog/unduh'); ?>" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh Laporan" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Unduh Laporan"><i class="fa fa-print "></i>Unduh
							</a>
							<a class="btn btn-social btn-flat bg-orange btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block grafikType" title="Grafik Data" id="grafikType" onclick="grafikType();">
								<i class="fa fa-bar-chart"></i>Grafik Data
							</a>
							<a class="btn btn-social btn-flat btn-primary btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block pieType" title="Pie Data" id="pieType" onclick="pieType();">
								<i class="fa fa-pie-chart"></i>Pie Data
							</a>
							<?php if ((int) $lap == 13): ?>
								<a href="<?=site_url('statistik/rentang_umur'); ?>" class="btn btn-social btn-flat bg-olive btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Rentang Umur">
									<i class="fa fa-arrows-h"></i>Rentang Umur
								</a>
							<?php endif; ?>
							<a href="<?= site_url("{$this->controller}/clear/{$lap}") ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i>Bersihkan</a>
						</div>
						<div class="box-body">
							<?php if ((int) $lap < 50): ?>
								<h4 class="box-title text-center"><b>Data Kependudukan Menurut <?= ($stat); ?></b></h4>
							<?php else: ?>
								<h4 class="box-title text-center"><b>Data Peserta Program <?= ($program['nama']); ?></b></h4>
							<?php endif; ?>
							<div id="chart" hidden="true"> </div>
						</div>
						<hr>
						<div class="box-body">
							<?php if ($lap != 'kelas_sosial' && $lap != 'bdt') : ?>
								<div class="row">
									<div class="col-sm-12 form-inline">
										<form id="mainform" method="post">
											<?php if ($lap == 'bantuan_penduduk' || $lap == 'bantuan_keluarga'): ?>
												<select class="form-control input-sm " name="status" onchange="formAction('mainform','<?= site_url('statistik/filter/status') ?>')">
													<option value="" selected>Pilih Status</option>
													<option value="1" <?= selected($status, '1'); ?>>Ya</option>
													<option value="0" <?= selected($status, '0'); ?>>Tidak</option>
												</select>
												<select class="form-control input-sm" name="tahun" onchange="formAction('mainform','<?= site_url('statistik/filter/tahun') ?>')">
													<option value="">Pilih Tahun</option>
													<?php for ($t = date('Y'); $t >= $tahun_bantuan_pertama; $t--): ?>
													<option value=<?= $t ?> <?= selected($tahun, $t); ?>><?= $t ?></option>
													<?php endfor; ?>
												</select>
											<?php endif; ?>
											<select class="form-control input-sm " name="dusun" onchange="formAction('mainform','<?= site_url('statistik/dusun'); ?>')">
												<option value="">Pilih <?= ucwords($this->setting->sebutan_dusun); ?></option>
												<?php foreach ($list_dusun as $data): ?>
													<option value="<?= $data['dusun']; ?>" <?= selected($dusun, $data['dusun']); ?>><?= set_ucwords($data['dusun']); ?></option>
												<?php endforeach; ?>
											</select>
											<?php if ($dusun): ?>
												<select class="form-control input-sm" name="rw" onchange="formAction('mainform','<?= site_url('statistik/rw'); ?>')" >
													<option value="">Pilih RW</option>
													<?php foreach ($list_rw as $data): ?>
														<option value="<?= $data['rw']; ?>" <?= selected($rw, $data['rw']); ?>><?= set_ucwords($data['rw']); ?></option>
													<?php endforeach; ?>
												</select>
											<?php endif; ?>
											<?php if ($rw): ?>
												<select class="form-control input-sm" name="rt" onchange="formAction('mainform','<?= site_url('statistik/rt'); ?>')">
													<option value="">Pilih RT</option>
													<?php foreach ($list_rt as $data): ?>
														<option value="<?= $data['rt']; ?>" <?= selected($rt, $data['rt']); ?>><?= set_ucwords($data['rt']); ?></option>
													<?php endforeach; ?>
												</select>
											<?php endif; ?>
										</form>
									</div>
								</div>
							<?php endif; ?>
								<div class="table-responsive">
									<table class="table table-bordered dataTable table-striped table-hover tabel-daftar">
										<thead class="bg-gray color-palette">
											<tr>
												<th>No</th>
												<?php if ($order_by == 2): ?>
													<th><a href="<?= site_url("statistik/order_by/{$lap}/1"); ?>"><?= $judul_kelompok; ?><i class='fa fa-sort-asc fa-sm'></i></a></th>
												<?php elseif ($order_by == 1): ?>
													<th><a href="<?= site_url("statistik/order_by/{$lap}/2"); ?>"><?= $judul_kelompok; ?><i class='fa fa-sort-desc fa-sm'></i></a></th>
												<?php else: ?>
													<th><a href="<?= site_url("statistik/order_by/{$lap}/1"); ?>"><?= $judul_kelompok; ?><i class='fa fa-sort fa-sm'></i></a></th>
												<?php endif; ?>
												<?php if ($order_by == 6): ?>
													<th colspan="2"><a href="<?= site_url("statistik/order_by/{$lap}/5"); ?>">Jumlah<i class='fa fa-sort-asc fa-sm'></i></a></th>
												<?php elseif ($order_by == 5): ?>
													<th colspan="2"><a href="<?= site_url("statistik/order_by/{$lap}/6"); ?>">Jumlah<i class='fa fa-sort-desc fa-sm'></i></a></th>
												<?php else: ?>
													<th colspan="2"><a href="<?= site_url("statistik/order_by/{$lap}/5"); ?>">Jumlah<i class='fa fa-sort fa-sm'></i></a></th>
												<?php endif; ?>
												<?php if ($order_by == 4): ?>
													<th colspan="2"><a href="<?= site_url("statistik/order_by/{$lap}/3"); ?>">Laki-Laki<i class='fa fa-sort-asc fa-sm'></i></a></th>
												<?php elseif ($order_by == 3): ?>
													<th colspan="2"><a href="<?= site_url("statistik/order_by/{$lap}/4"); ?>">Laki-Laki<i class='fa fa-sort-desc fa-sm'></i></a></th>
												<?php else: ?>
													<th colspan="2"><a href="<?= site_url("statistik/order_by/{$lap}/3"); ?>">Laki-Laki<i class='fa fa-sort fa-sm'></i></a></th>
												<?php endif; ?>
												<?php if ($order_by == 8): ?>
													<th colspan="2"><a href="<?= site_url("statistik/order_by/{$lap}/7"); ?>">Perempuan<i class='fa fa-sort-asc fa-sm'></i></a></th>
												<?php elseif ($order_by == 7): ?>
													<th colspan="2"><a href="<?= site_url("statistik/order_by/{$lap}/8"); ?>">Perempuan<i class='fa fa-sort-desc fa-sm'></i></a></th>
												<?php else: ?>
													<th colspan="2"><a href="<?= site_url("statistik/order_by/{$lap}/7"); ?>">Perempuan<i class='fa fa-sort fa-sm'></i></a></th>
												<?php endif; ?>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($main as $data): ?>
												<?php if ((int) $lap > 50) {
                                                    $tautan_jumlah = site_url("program_bantuan/detail/{$lap}/1");
                                                } ?>
												<tr>
													<td class="padat"><?= $data['no']; ?></td>
													<td class="text-left"><?= strtoupper($data['nama']); ?></td>
													<td class="text-right">
														<a href="<?= $tautan_data . $data['id'] ?>/0" target="_blank"><?= $data['jumlah']; ?></a>
													</td>
													<td class="text-right"><?= $data['persen']; ?></td>
													<td class="text-right"><a href="<?= $tautan_data . $data['id'] ?>/1" target="_blank"><?= $data['laki']; ?></a></td>
													<td class="text-right"><?= $data['persen1']; ?></td>
													<td class="text-right"><a href="<?= $tautan_data . $data['id'] ?>/2" target="_blank"><?= $data['perempuan']; ?></a></td>
													<td class="text-right"><?= $data['persen2']; ?></td>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								</div>
								<?php if (in_array($lap, ['bantuan_keluarga', 'bantuan_penduduk'])):?>
									<p class="text-muted text-justify text-red"><b>Catatan:</b>
										<br>
										1. Angka masing-masing program menghitung semua peserta, aktif maupun yang tidak
										<br>
										2. Jumlah PENERIMA menghitung peserta aktif saja, dan setiap peserta terhitung satu sekali saja, meskipun menerima lebih dari satu jenis bantuan.
										<br>
										3. Jumlah BUKAN PENERIMA dan TOTAL menghitung peserta aktif saja.
									</p>
									<br><br>
								<?php endif; ?>
								<?php if ((int) $lap > 50): ?>
									<p class="text-muted text-justify text-red"><b>Catatan:</b>
										<br>
										1. Jumlah PESERTA termasuk peserta yang mungkin tidak aktif lagi.<br>
										2. Jumlah BUKAN PESERTA dan TOTAL menghitung peserta aktif saja.
									</p>
								<?php endif; ?>
							</div>

							<?php if ($bantuan):?>
								<?php $this->load->view('statistik/peserta_bantuan'); ?>
							<?php endif; ?>

						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>
<script type="text/javascript">
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
				<?php $i = 0;

foreach ($main as $data): $i++; ?>
				<?php if ($data['jumlah'] != '-'): ?><?= "'{$i}',"; ?><?php endif; ?>
			<?php endforeach; ?>
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
				<?php if (! in_array($data['nama'], ['TOTAL', 'JUMLAH', 'PENERIMA'])): ?>
					<?php if ($data['jumlah'] != '-'): ?>
						['<?= strtoupper($data['nama']); ?>',<?= $data['jumlah']; ?>],
					<?php endif; ?>
				<?php endif; ?>
				<?php endforeach; ?>]
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
					<?php if (! in_array($data['nama'], ['TOTAL', 'JUMLAH', 'PENERIMA'])): ?>
						<?php if ($data['jumlah'] != '-'): ?>
							["<?= strtoupper($data['nama']); ?>",<?= $data['jumlah']; ?>],
						<?php endif; ?>
					<?php endif; ?>
				<?php endforeach; ?>
				]
			}]
		});

		$('#chart').removeAttr('hidden');
	}
</script>
