
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
						<?php if ($data['nama'] != "TOTAL" and $data['nama'] != "JUMLAH" and $data['nama'] != "PENERIMA" and $data['nama'] != "BUKAN PENERIMA"): ?>
							<?php if ($data['jumlah'] != "-"): ?>
								["<?= strtoupper($data['nama'])?>",<?= $data['jumlah']?>],
							<?php endif; ?>
						<?php endif; ?>
					<?php endforeach;?>
				]
			}]
		});
	});
</script>
<!-- Highcharts -->
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
								<?php if (($lap <= 20 OR $lap == 'bantuan_penduduk') AND $lap <> 'kelas_sosial' AND $lap <> 'bantuan_keluarga') : ?>
									<div class="row">
										<div class="col-sm-12 form-inline">
											<form action="" id="mainform" method="post">
												<select class="form-control input-sm " name="dusun" onchange="formAction('mainform','<?= site_url('statistik/dusun/0/'.$lap)?>')">
													<option value="">Pilih <?= ucwords($this->setting->sebutan_dusun)?></option>
													<?php foreach ($list_dusun AS $data): ?>
														<option value="<?= $data['dusun']?>" <?php $dusun == $data['dusun'] and print('selected') ?>><?= strtoupper($data['dusun'])?></option>
													<?php endforeach; ?>
												</select>
												<?php if ($dusun): ?>
													<select class="form-control input-sm" name="rw" onchange="formAction('mainform','<?= site_url('statistik/rw/0/'.$lap)?>')" >
														<option value="">RW</option>
														<?php foreach ($list_rw AS $data): ?>
															<option value="<?= $data['rw']?>" <?php $rw == $data['rw'] and print('selected') ?>><?= $data['rw']?></option>
														<?php endforeach; ?>
													</select>
												<?php endif; ?>
												<?php if ($rw): ?>
													<select class="form-control input-sm" name="rt" onchange="formAction('mainform','<?= site_url('statistik/rt/0/'.$lap)?>')">
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
												<?php if ($jenis_laporan == 'penduduk'): ?>
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
													<td>
														<?php if ($lap==21 OR $lap==22 OR $lap==23 OR $lap==24 OR $lap==25 OR $lap==26 OR $lap==27 OR "$lap"=='kelas_sosial' OR "$lap"=='bantuan_keluarga'): ?>
															<a href="<?= site_url("keluarga/statistik/$lap/$data[id]")?>/0" <?php if ($data['id']=='JUMLAH'): ?>class="disabled"<?php endif; ?>><?= $data['jumlah']?></a>
														<?php else: ?>
															<?php if ($lap<50) $tautan_jumlah = site_url("penduduk/statistik/$lap/$data[id]"); ?>
															<a href="<?= $tautan_jumlah ?>/0" <?php if ($data['id']=='JUMLAH'): ?> class="disabled"<?php endif; ?>><?= $data['jumlah']?></a>
														<?php endif; ?>
													</td>
													<td><?= $data['persen'];?></td>
													<?php if ($lap==21 OR $lap==22 OR $lap==23 OR $lap==24 OR $lap==25 OR $lap==26 OR $lap==27 OR "$lap"=='kelas_sosial' OR "$lap"=='bantuan_keluarga'):
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

							<?php if ($program_peserta):?>
									<div class="row">
										<div class="col-md-12">
											<?php $detail = $program_peserta[0];?>
											<div class="box box-info">
												<div class="box-body">
													<div class="row">
														<div class="col-sm-12">
															<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
															<form id="mainform" name="mainform" action="" method="post">
															<input type="hidden" name="id" value="<?php echo $this->uri->segment(4) ?>">
																<div class="row">
																	<div class="col-sm-12">
																		<div class="row">
																			<div class="col-sm-9">
																				<div class="box-header with-border">
																					<h3 class="box-title">Daftar Peserta Program <?=$heading?></h3>
																				</div>
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
																						<th rowspan="2" nowrap class="text-center"><?= $detail["judul_peserta"]?></th>
																						<?php if (!empty($detail['judul_peserta_plus'])): ?>
																							<th rowspan="2" nowrap class="text-center"><?= $detail["judul_peserta_plus"]?></th>
																						<?php endif ;?>
																						<th rowspan="2" nowrap class="text-center"><?= $detail["judul_peserta_info"]?></th>
																						<th colspan="6" class="text-center">Identitas di Kartu Peserta</th>
																					</tr>
																					<tr>
																						<th class="text-center">Nama</th>
																						<th class="text-center">Alamat</th>
																					</tr>
																				</thead>
																				<tbody>
																					<?php $nomer = $paging->offset;?>
																					<?php if (is_array($peserta)): ?>
																						<?php foreach ($peserta as $key=>$item): $nomer++;?>
																							<tr>
																								<td class="text-center"><?= $nomer?></td>
																								<td nowrap><?= strtoupper($item['program_plus']);?></td>
																								<?php $id_peserta = ($detail['sasaran'] == 4) ? $item['peserta'] : $item['nik'] ?>
																								<td nowrap class="text-center"><a href="<?= site_url("program_bantuan/peserta/$detail[sasaran]/$id_peserta/")?>" title="Daftar program untuk peserta"><?= $item["peserta_nama"] ?></a></td>
																								<?php if (!empty($item['peserta_plus'])): ?>
																									<td nowrap><?= $item["peserta_plus"]?></td>
																								<?php endif; ?>
																								<td nowrap><?= $item["peserta_info"]?></td>
																								<td><?= $item["kartu_nama"];?></td>
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
																			<form id="paging" action="<?= site_url("program_bantuan/detail/1/$detail[id]")?>" method="post" class="form-horizontal">
																			 <label>
																					Tampilkan
																					<select name="per_page" class="form-control input-sm" onchange="$('#mainform').submit();" id="per_page_input">
																						<option value="20" <?php selected($per_page, 20); ?> >20</option>
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
																</div>
															</form>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
							<?php endif;?>
							
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>
