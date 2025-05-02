<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<script type="text/javascript">
	let chart;
	const rawData = Object.values(<?= json_encode($stat) ?>);
	const type = '<?= $default_chart_type ?? 'pie' ?>';
	const legend = Boolean(<?= (bool)$tipe ?>);
	let categories = [];
	let data = [];
	let index = 1;  // Ganti nama variabel 'i' menjadi 'index' untuk menghindari konflik
	let status_tampilkan = true;

	// Proses data mentah untuk menyiapkan array categories dan data
	for (const stat of rawData) {
		if (stat.nama !== 'TOTAL' && stat.nama !== 'JUMLAH' && stat.nama !== 'PENERIMA') {
			categories.push(index);
			data.push([stat.nama, parseInt(stat.jumlah)]);
			index++;
		}
	}

	// Fungsi untuk menampilkan atau menyembunyikan baris dengan nilai nol
	function tampilkan_nol(tampilkan = false) {
		if (tampilkan) {
			$(".nol").parent().show();
		} else {
			$(".nol").parent().hide();
		}
	}

	// Toggle visibilitas baris dengan nilai nol
	function toggle_tampilkan() {
		$('#showData').click();
		tampilkan_nol(status_tampilkan);
		status_tampilkan = !status_tampilkan;
		$('#tampilkan').text(status_tampilkan ? 'Tampilkan Nol' : 'Sembunyikan Nol');
	}

	// Ganti tipe chart antara pie dan column
	function switchType(obj){
		const chartType = chart.series[0].type;
		chart.series[0].update({
			type: chartType === 'pie' ? 'column' : 'pie'
		});
		$(obj).toggleClass('btn-primary btn-default')
		$(obj).siblings().toggleClass('btn-primary btn-default')
	}

	// Inisialisasi chart
	$(document).ready(function () {
		tampilkan_nol(false);

		const chartOptions = {
			chart: {
				renderTo: 'container'
			},
			title: {
				text: null
			},
			yAxis: {
				showEmpty: false
			},
			xAxis: {
				categories: categories
			},
			plotOptions: {
				series: {
					colorByPoint: true
				},
				column: {
					pointPadding: -0.1,
					borderWidth: 0,
					showInLegend: false
				},
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					showInLegend: true,
					innerSize: 70
				}
			},
			legend: {
				enabled: legend
			},
			series: [{
				type: type,
				name: 'Jumlah Populasi',
				shadow: true,
				borderWidth: 1,
				data: data
			}]
		};

		if (<?=$this->setting->statistik_chart_3d?>) {
			chartOptions.chart.options3d = {
				enabled: true,
				alpha: 45
			};
			chartOptions.plotOptions.column.depth = 45;
			chartOptions.plotOptions.pie.depth = 45;
		}

		chart = new Highcharts.Chart(chartOptions);

		// Tampilkan data tambahan ketika tombol diklik
		$('#showData').click(function () {
			$('tr.lebih').show();
			$('#showData').hide();
			tampilkan_nol(false);
		});
	});
</script>
<style>
	tr.lebih {
		display: none;
	}
	
	.input-sm
	{
		padding: 4px 4px;
	}
	@media (max-width:780px)
	{
		.btn-group-vertical
		{
			display: block;
		}
	}
	.table-responsive
	{
		min-height:275px;
	}
</style>
<div class="article-single">
	<div class="statistikstyle">
		<div class="container-page mb-20">
			<div class="headingpage border-grey-soft bg-gradient-hor flexleft">
				<div class="headingpage-image border-grey-soft flexcenter"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/statistik.svg") ?>" alt=""/></div>
				<div class="articlehome-head flexleft"><h1>STATISTIK</h1></div>
			</div>
			<div class="box-article mb-30 bg-white" style="border-radius:0 0 5px 5px;">
				<div class="relative-border p-15 border-grey-soft" style="border-radius:0 0 5px 5px;">
					<div class="gridview">
						<div class="sidebarright">
							<img style="width:100%;height:1px;display:block;" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/empty-pic.png") ?>"/>
							<ul>
								<?php $this->load->view($folder_themes .'/partials/kependudukan/navigasi') ?>
							</ul>
						</div>
						<div class="head-content">
							<div style="text-align:center;"><h1>
								<?php if (IS_PREMIUM && IS_240803) : ?>
								<?= $judul; ?>
								<?php else : ?>
								Jumlah Penduduk berdasarkan Tingkat <?= $heading ?><br>di <?= ucwords($this->setting->sebutan_desa); ?> <?= ucwords(($desa['nama_desa']) ? ' ' . $desa['nama_desa'] : ''); ?><br>Tahun <?= date('Y') ?>
								<?php endif; ?>
								</h1></div>
								<?php if(isset($list_tahun)): ?>
								<form method="get" class="form-inline text-center">
									<select class="form-control input-sm select2" id="tahun" name="tahun">
										<option selected value="">Semua Tahun</option>
										<?php foreach ($list_tahun as $item_tahun): ?>
										<option <?= $item_tahun == ($selected_tahun ?? NULL) ? 'selected' : '' ?> value="<?= $item_tahun ?>">
										<?= $item_tahun ?></option>
										<?php endforeach ?>
									</select>
								</form>
								<?php endif ?>
							<div class="relative-row flexcenter mt-20 mb-20">
								<a class="tombol bg-color1 flexcenter" onclick="switchType();" style="margin:0 3px;">Ubah Grafik</a>
								<?php if (IS_PREMIUM && IS_241010) : ?>
								<a href="<?= site_url("data-statistik/{$slug_aktif}/cetak/cetak") ?>?tahun=<?= $selected_tahun ?>"
									class="tombol bg-color3 flexcenter" style="margin:0 3px;"
									title="Cetak Laporan" target="_blank">
									<i class="fa fa-print "></i>&nbsp;Cetak
								</a>
								<a href="<?= site_url("data-statistik/{$slug_aktif}/cetak/unduh") ?>?tahun=<?= $selected_tahun ?>"
									class="tombol bg-color5 flexcenter" style="margin:0 3px;"
									title="Unduh Laporan" target="_blank">
									<i class="fa fa-print "></i>&nbsp;Unduh
								</a>
								<?php endif ?>
							</div>
							<div id="container"></div>
							<div class="head-module-center border-grey-soft flexcenter" style="margin-bottom:10px;margin-top:15px;">
								<h2 align="center">
									<?php if (IS_PREMIUM && IS_240803) : ?>
									<?= $judul; ?>
									<?php else : ?>
									Jumlah dan Persentase Penduduk berdasarkan Tingkat <?= $heading ?><br>di <?= ucwords($this->setting->sebutan_desa); ?> <?= ucwords(($desa['nama_desa']) ? ' ' . $desa['nama_desa'] : ''); ?><br>Tahun <?= date('Y') ?>
									<?php endif; ?>
								</h2>
							</div>
							<div class="table-statistik">
								<div class="table-responsive">
									<table class="table table-striped">
										<thead>
										<tr>
											<th rowspan="2">No</th>
											<th rowspan="2" style='text-align:left;'>Kelompok</th>
											<th colspan="2" style='text-align:center'>Jumlah</th>
											<?php if ($this->uri->segment(2) != 'status-kehamilan') : ?>
											<th colspan="2" style='text-align:center'>Laki-laki</th>
											<?php endif;?>
											<th colspan="2" style='text-align:center'>Perempuan</th>
										</tr>
										<tr>
											<th style='text-align:center'>Jiwa</th><th style='text-align:center'>%</th>
											<?php if ($this->uri->segment(2) != 'status-kehamilan') : ?>
											<th style='text-align:center'>Jiwa</th><th style='text-align:center'>%</th>
											<?php endif;?>
											<th style='text-align:center'>Jiwa</th><th style='text-align:center'>%</th>
										</tr>
										</thead>
										<tbody>
											<?php $i=0; $l=0; $p=0; $hide=""; $h=0; $jm1=1; $jm = count($stat ?? []);?>
											<?php foreach ($stat as $data):?>
											<?php $jm1++; if (1):?>
											<?php $h++; if ($h > 12 AND $jm > 10): $hide="lebih"; ?>
											<?php endif;?>
											<tr class="<?=$hide?>">
												<td class="angka">
													<?php if ($jm1 > $jm - 2):?>
													<?=$data['no']?>
													<?php else:?>
													<?=$h?>
													<?php endif;?>
												</td>
												<td><?=$data['nama']?></td>
												<td class="angka <?php ($jm1 <= $jm - 2) and ($data['jumlah'] == 0) and print('nol')?>" style='text-align:center'><?=ribuan($data['jumlah'])?></td>
												<td class="angka" style='text-align:center'><?=$data['persen']?></td>
												<?php if ($this->uri->segment(2) != 'status-kehamilan') : ?>
												<td class="angka" style='text-align:center'><?=ribuan($data['laki'])?></td>
												<td class="angka" style='text-align:center'><?=$data['persen1']?></td>
												<?php endif;?>
												<td class="angka" style='text-align:center'><?=ribuan($data['perempuan'])?></td>
												<td class="angka" style='text-align:center'><?=$data['persen2']?></td>
											</tr>
											<?php $i += $data['jumlah'];?>
											<?php $l += $data['laki']; $p += $data['perempuan'];?>
											<?php endif;?>
											<?php endforeach;?>
										</tbody>
									</table>
									<?php if (IS_PREMIUM || (!IS_PREMIUM && IS_240810)) : ?>
										<i class="fa fa-calendar" aria-hidden="true"></i> Diperbarui : <?= tgl_indo2($last_update); ?>
									<?php endif;?>
									<div class="flexcenter">
										<?php if($hide=="lebih"):?>
											<button class='tombol bg-grey-dark' id='showData' style='margin:0 2px;'>Selengkapnya...</button>
										<?php endif;?>
										<button id='tampilkan' onclick="toggle_tampilkan();" class="tombol bg-grey-dark" style='margin:0 2px;'>Tampilkan Nol</button>
									</div>
								</div>
							</div>
							<?php if ($this->setting->daftar_penerima_bantuan && (!IS_PREMIUM ? $bantuan : in_array($st, array('bantuan_keluarga', 'bantuan_penduduk')))):?>
								<section class="content mt-20">
									<div class="row">
										<div class="col-md-12">
											<input id="stat" type="hidden" value="<?=$st?>">
											<div class="">
												<div class="head-module-center border-grey-soft flexcenter" style="margin-bottom:10px;margin-top:15px;"><h2>Daftar <?= $heading ?></h2></div>
												<div class="table-responsive">
													<table class="table table-striped table-bordered" id="peserta_program">
														<thead>
															<tr>
																<th>No</th>
																<th>Program</th>
																<th>Nama Peserta</th>
																<th>Alamat</th>
															</tr>
														</thead>
														<tfoot>
														</tfoot>
													</table>
												</div>
											</div>
										</div>
									</div>
								</section>
								<script type="text/javascript">
									$(document).ready(function() {
										$('#tahun').change(function(){
												const current_url = window.location.href.split('?')[0]
												window.location.href = `${current_url}?tahun=${$(this).val()}`;
											})
											var url = "<?= site_url('first/ajax_peserta_program_bantuan') ?>?tahun=<?= $selected_tahun ?? '' ?>";
											table = $('#peserta_program').DataTable({
											'processing': true,
											'serverSide': true,
											"pageLength": 10,
											'order': [[2, 'asc']],
											"ajax": {
												"url": url,
												"type": "POST",
												"data": {stat: $('#stat').val()}
											},
										//Set column definition initialisation properties.
										"columnDefs": [
										{
												"targets": [ 0, 3 ], //first column / numbering column
												"orderable": false, //set not orderable
											},
											],
											'language': {
												'url': BASE_URL + '/assets/bootstrap/js/dataTables.indonesian.lang'
											},
											'drawCallback': function (){
												$('.dataTables_paginate > .pagination').addClass('pagination-sm no-margin');
											}
										});
									} );
								</script>
							<?php endif;?>
						</div>
					</div>
					<div class="small-screen"><?php $this->load->view($folder_themes .'/partials/kependudukan/navigasi') ?></div>
				</div>
			</div>
		</div>
	</div>
</div>
