<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<script type="text/javascript">
	let chart;
	const rawData = Object.values(<?= json_encode($stat) ?>);
	const type = '<?= $tipe == 1 ? 'column' : 'pie' ?>';
	const legend = Boolean(!<?= ($tipe) ?>);
	let categories = [];
	let data = [];
	let i = 1;
	let status_tampilkan = true;
	for (const stat of rawData) {
		if (stat.nama !== 'BELUM MENGISI' && stat.nama !== 'TOTAL' && stat.nama !== 'JUMLAH' && stat.nama != 'PENERIMA' && stat.nama != 'BUKAN PENERIMA') {
			let filteredData = [stat.nama, parseInt(stat.jumlah)];
			categories.push(i);
			data.push(filteredData);
			i++;
		}
	}

	function tampilkan_nol(tampilkan = false) {
		if (tampilkan) {
			$(".nol").parent().show();
		} else {
			$(".nol").parent().hide();
		}
	}

	function toggle_tampilkan() {
		$('#showData').click();
		tampilkan_nol(status_tampilkan);
		status_tampilkan = !status_tampilkan;
		if (status_tampilkan) $('#tampilkan').text('Tampilkan Nol');
		else $('#tampilkan').text('Sembunyikan Nol');
	}

	$(document).ready(function () {
		tampilkan_nol(false);
		chart = new Highcharts.Chart({
			chart: {
				renderTo: 'container'
			},
			title: 0,
			xAxis: {
				categories: categories,
			},
			plotOptions: {
				series: {
					colorByPoint: true
				},
				column: {
					pointPadding: -0.1,
					borderWidth: 0
				},
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					showInLegend: true
				}
			},
			legend: {
				enabled: legend
			},
			series: [{
				type: type,
				name: 'Jumlah Populasi',
				shadow: 1,
				border: 1,
				data: data
			}]
		});

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
</style>
<style>
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
	}
</style>

<div class="box box-danger">
	<div class="box-header with-border">
		<h3 class="box-title">Grafik <?= $heading ?></h3>
		<div class="box-tools pull-right">
			<div class="btn-group-xs">
				<a href="<?= site_url("first/statistik/$st/1") ?>" class="btn <?= ($tipe==1) ? 'btn-primary' : 'btn-default' ?> btn-xs">Bar Graph</a>
				<a href="<?= site_url("first/statistik/$st/0") ?>" class="btn <?= ($tipe==0) ? 'btn-primary' : 'btn-default' ?> btn-xs">Pie Cart</a>
			</div>
		</div>
	</div>
	<div class="box-body">
		<div id="container"></div>
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
				<?php if ($jenis_laporan == 'penduduk'):?>
					<th colspan="2">Laki-laki</th>
					<th colspan="2">Perempuan</th>
				<?php endif;?>
			</tr>
			<tr>
				<th style='text-align:right'>n</th><th style='text-align:right'>%</th>
				<?php if ($jenis_laporan == 'penduduk'):?>
					<th style='text-align:right'>n</th><th style='text-align:right'>%</th>
					<th style='text-align:right'>n</th><th style='text-align:right'>%</th>
				<?php endif;?>
			</tr>
			</thead>
			<tbody>
				<?php $i=0; $l=0; $p=0; $hide=""; $h=0; $jm1=1; $jm = count($stat);?>
				<?php foreach ($stat as $data):?>
					<?php $jm1++; ?>
					<?php $h++; ?>
					<?php if ($h > 12 AND $jm > 10): ?>
						<?php $hide = "lebih"; ?>
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
						<td class="angka <?php ($jm1 <= $jm - 2) and ($data['jumlah'] == 0) and print('nol')?>"><?=$data['jumlah']?></td>
						<td class="angka"><?=$data['persen']?></td>
						<?php if ($jenis_laporan == 'penduduk'):?>
							<td class="angka"><?=$data['laki']?></td>
							<td class="angka"><?=$data['persen1']?></td>
							<td class="angka"><?=$data['perempuan']?></td>
							<td class="angka"><?=$data['persen2']?></td>
						<?php endif;?>
					</tr>
					<?php $i += $data['jumlah'];?>
					<?php $l += $data['laki'];?>
					<?php $p += $data['perempuan'];?>
				<?php endforeach;?>
			</tbody>
		</table>
		<?php if ($hide=="lebih"):?>
			<div style='float: left;'>
				<button class='uibutton special' id='showData'>Selengkapnya...</button>
			</div>
		<?php endif;?>
		<div style="float: right;">
			<button id='tampilkan' onclick="toggle_tampilkan();" class="uibutton special">Tampilkan Nol</button>
		</div>
	</div>
	</div>
</div>

<?php if ($program_peserta):?>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<?php $detail = $program_peserta[0];?>
				<div class="box box-info">
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
								<form id="mainform" name="mainform" action="" method="post">
									<input type="hidden" name="id" value="<?php echo $this->uri->segment(3) ?>">
									<div class="row">
										<div class="col-sm-12">
													<div class="box-header with-border">
														<h3 class="box-title">Daftar Peserta Program <?= $heading ?></h3>
													</div>
										</div>
										<div class="col-sm-3">
											<div class="input-group input-group-sm pull-right">
												<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="<?=html_escape($cari_peserta)?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?=site_url("first/search_peserta")?>');$('#'+'mainform').submit();}">
												<div class="input-group-btn">
													<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?=site_url("first/search_peserta")?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
												</div>
											</div>
										</div>
										<div class="col-sm-12">
										<?php $peserta = $program_peserta[1];?>
											<div class="table-responsive">
												<table class="table table-bordered table-striped dataTable table-hover">
													<thead class="bg-gray disabled color-palette">
														<tr>
															<th rowspan="2" class="text-center">No</th>
															<th rowspan="2" class="text-center">Program</th>
															<th rowspan="2" nowrap class="text-center"><?= $detail["judul_peserta_info"]?></th>
															<th rowspan="2" class="text-center">Alamat</th>
														</tr>
													</thead>
													<tbody>
														<?php $nomer = $paging->offset;?>
														<?php if (is_array($peserta)): ?>
															<?php foreach ($peserta as $key=>$item): $nomer++;?>
																<tr>
																	<td class="text-center"><?= $nomer?></td>
																	<td nowrap><?= strtoupper($item['program_plus']);?></td>
																	<td nowrap><?= $item["peserta_info"]?></td>
																	<td><?= $item["kartu_alamat"];?></td>
																</tr>
															<?php endforeach; ?>
														<?php endif; ?>
													</tbody>
												</table>
											</div>
										</div>

									</div>
                  <div class="row">
                    <div class="col-sm-6">
                      <div class="dataTables_length">
                        <form id="paging" action="<?= site_url("first/statistik/$st/0")?>" method="post" class="form-horizontal">
                         <label>
                            Tampilkan
                            <select name="per_page" class="form-control input-sm" onchange="$('#mainform').submit();" id="per_page_input">
      	                      <option value="10" <?php selected($per_page, 10); ?> >10</option>
                              <option value="50" <?php selected($per_page, 50); ?> >50</option>
                              <option value="100" <?php selected($per_page, 100); ?> >100</option>
                            </select>
        	                  Dari
                            <strong><?= $paging->num_rows?></strong>
                            Total Data
                          </label>
                        </form>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="dataTables_paginate paging_simple_numbers">
                        <ul class="pagination">
                          <?php if ($paging->start_link): ?>
                            <li><a href="<?=site_url("first/statistik/$st/0/$paging->start_link")?>" aria-label="First"><span aria-hidden="true">Awal</span></a></li>
                          <?php endif; ?>
                          <?php if ($paging->prev): ?>
                            <li><a href="<?=site_url("first/statistik/$st/0/$paging->prev")?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
                          <?php endif; ?>
                          <?php for ($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
                            <li <?=jecho($p, $i, "class='active'")?>><a href="<?= site_url("first/statistik/$st/0/$i")?>"><?= $i?></a></li>
                          <?php endfor; ?>
                          <?php if ($paging->next): ?>
                            <li><a href="<?=site_url("first/statistik/$st/0/$paging->next")?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
                          <?php endif; ?>
                          <?php if ($paging->end_link): ?>
                            <li><a href="<?=site_url("first/statistik/$st/0/$paging->end_link")?>" aria-label="Last"><span aria-hidden="true">Akhir</span></a></li>
                          <?php endif; ?>
                        </ul>
                      </div>
                    </div>
                  </div>
								</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php endif;?>
