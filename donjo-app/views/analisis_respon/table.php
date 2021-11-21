<script>
	$(function() {
		var keyword = <?= $keyword; ?> ;
		$("#cari").autocomplete({
			source: keyword,
			maxShowItems: 10,
		});
	});
</script>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Data Sensus - <?= $analisis_master['nama']?></h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('analisis_master/clear')?>"> Master Analisis</a></li>
			<li><a href="<?= site_url('analisis_master/leave'); ?>"><?= $analisis_master['nama']; ?></a></li>
			<li class="active">Data Sensus</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-4 col-lg-3">
				<?php $this->load->view('analisis_master/left', $data); ?>
			</div>
			<div class="col-md-8 col-lg-9">
				<div class="box box-info">
				<div class="box-header with-border">
						<a href="<?= site_url('analisis_respon/data_ajax')?>" class="btn btn-social btn-flat bg-purple btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh data respon" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Unduh Data Respon">
							<i class="fa fa-download"></i>Unduh
						</a>
						<?php if ($this->CI->cek_hak_akses('u')): ?>
							<a href="<?= site_url('analisis_respon/import')?>" class="btn btn-social btn-flat bg-navy btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Impor Data Respon" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Impor Data Respon">
								<i class="fa fa-upload"></i>Impor
							</a>
						<?php endif; ?>
						<?php if ($analisis_master['format_impor'] == 1 && $this->CI->cek_hak_akses('u')): ?>
							<a href="<?= site_url('analisis_respon/form_impor_bdt')?>" class="btn btn-social btn-flat bg-olive btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Impor Data BDT 2015" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Impor Data BDT 2015">
								<i class="fa fa-upload"></i>Impor BDT 2015
							</a>
						<?php endif; ?>
						<a href="<?= site_url('analisis_master/leave'); ?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left "></i> Kembali Ke <?= $analisis_master['nama']?></a>
					</div>
					<div class="box-header with-border">
						<div class="table-responsive">
							<table class="table table-bordered table-striped table-hover" >
								<tr>
									<td width="150">Nama Analisis</td>
									<td width="1">:</td>
									<td><a href="<?= site_url()?>analisis_master/menu/<?= $_SESSION['analisis_master']?>"><?= $analisis_master['nama']?></a></td>
								</tr>
								<tr>
									<td>Subjek Analisis</td>
									<td>:</td>
									<td><?= $asubjek?></td>
								</tr>
								<tr>
									<td>Periode</td>
									<td>:</td>
									<td><?= $analisis_periode->nama ?></td>
								</tr>
								<?php if ($analisis_master['gform_id'] != null && $analisis_master['gform_id'] != ''): ?>
								<tr>
									<td>Sinkronisasi Terakhir</td>
									<td>:</td>
									<td><?= tgl_indo2($analisis_master['gform_last_sync']) ?></td>
								</tr>
								<?php endif; ?>
							</table>
						</div>
					</div>
					<div class="box-body">
						<div class="table-responsive">
							<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
								<form id="mainform" name="mainform" method="post">
									<div class="row">
										<div class="col-sm-8">
											<select class="form-control input-sm" name="isi" onchange="formAction('mainform', '<?= site_url('analisis_respon/isi')?>')">
												<option value=""> --- Semua --- </option>
												<option value="1" <?= selected($isi, 1); ?>>Sudah Terinput</option>
												<option value="2" <?= selected($isi, 2); ?>>Belum Terinput</option>
											</select>
											<select class="form-control input-sm " name="dusun" onchange="formAction('mainform','<?= site_url('analisis_respon/dusun')?>')">
												<option value="">Pilih <?= ucwords($this->setting->sebutan_dusun)?></option>
												<?php foreach ($list_dusun as $data): ?>
													<option value="<?= $data['dusun']?>" <?php if ($dusun == $data['dusun']): ?>selected<?php endif ?>><?= strtoupper($data['dusun'])?></option>
												<?php endforeach; ?>
											</select>
											<?php if ($dusun): ?>
												<select class="form-control input-sm" name="rw" onchange="formAction('mainform','<?= site_url('analisis_respon/rw')?>')" >
													<option value="">RW</option>
													<?php foreach ($list_rw as $data): ?>
														<option value="<?= $data['rw']?>" <?php if ($rw == $data['rw']): ?>selected<?php endif ?>><?= $data['rw']?></option>
													<?php endforeach; ?>
												</select>
											<?php endif; ?>
											<?php if ($rw): ?>
												<select class="form-control input-sm" name="rt" onchange="formAction('mainform','<?= site_url('analisis_respon/rt')?>')">
													<option value="">RT</option>
													<?php foreach ($list_rt as $data): ?>
														<option value="<?= $data['rt']?>" <?php if ($rt == $data['rt']): ?>selected<?php endif ?>><?= $data['rt']?></option>
													<?php endforeach; ?>
												</select>
											<?php endif; ?>
										</div>
										<div class="col-sm-4">
											<div class="input-group input-group-sm pull-right">
												<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="<?=html_escape($cari)?>" onkeypress="if (event.keyCode == 13):$('#'+'mainform').attr('action', '<?= site_url('analisis_respon/search')?>');$('#'+'mainform').submit();endif">
												<div class="input-group-btn">
													<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?= site_url('analisis_respon/search')?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
												</div>
											</div>
										</div>
									</div>
									<div class="table-responsive">
										<table class="table table-bordered dataTable table-striped table-hover tabel-daftar">
											<thead class="bg-gray disabled color-palette">
												<tr>
													<th>No</th>
													<th>Aksi</th>
													<?php if ($o == 2): ?>
														<th><a href="<?= site_url("analisis_respon/index/{$p}/1")?>"><?= $nomor?> <i class='fa fa-sort-asc fa-sm'></i></a></th>
													<?php elseif ($o == 1): ?>
														<th><a href="<?= site_url("analisis_respon/index/{$p}/2")?>"><?= $nomor?> <i class='fa fa-sort-desc fa-sm'></i></a></th>
													<?php else: ?>
														<th><a href="<?= site_url("analisis_respon/index/{$p}/1")?>"><?= $nomor?> <i class='fa fa-sort fa-sm'></i></a></th>
													<?php endif; ?>
													<?php if ($o == 4): ?>
														<th><a href="<?= site_url("analisis_respon/index/{$p}/3")?>"><?= $nama?> <i class='fa fa-sort-asc fa-sm'></i></a></th>
													<?php elseif ($o == 3): ?>
														<th><a href="<?= site_url("analisis_respon/index/{$p}/4")?>"><?= $nama?> <i class='fa fa-sort-desc fa-sm'></i></a></th>
													<?php else: ?>
														<th><a href="<?= site_url("analisis_respon/index/{$p}/3")?>"><?= $nama?> <i class='fa fa-sort fa-sm'></i></a></th>
													<?php endif; ?>
													<?php if (in_array($analisis_master['subjek_tipe'], [1, 2, 3, 4])): ?>
														<th>L/P</th>
													<?php endif; ?>
													<?php if (in_array($analisis_master['subjek_tipe'], [1, 2, 3, 4, 7, 8])): ?>
														<th><?= ucwords($this->setting->sebutan_dusun) ?></th>
														<th>RW</th>
														<?php if ($analisis_master['subjek_tipe'] != 7): ?>
															<th>RT</th>
														<?php endif; ?>
													<?php endif; ?>
													<th>Status</th>
												</tr>
											</thead>
											<tbody>
												<?php if ($main): ?>
													<?php foreach ($main as $data): ?>
														<tr>
															<td class="padat"><?= $data['no']; ?></td>
															<td class="aksi">
																<a href="<?= site_url("analisis_respon/kuisioner/{$p}/{$o}/{$data['id']}")?>" class="btn bg-purple btn-flat btn-sm" title="Input Data"><i class='fa fa-check-square-o'></i></a>
																<?php if ($data['bukti_pengesahan']): ?>
																	<a href="<?= base_url(LOKASI_PENGESAHAN . $data['bukti_pengesahan'])?>" class="btn bg-olive btn-flat btn-sm" title="Unduh Bukti Pengesahan" target="_blank"><i class="fa fa-paperclip"></i></a>
																<?php endif; ?>
															</td>
															<td nowrap><?= $data['nid']?></td>
															<td nowrap><?= $data['nama']?></td>
															<?php if (in_array($analisis_master['subjek_tipe'], [1, 2, 3, 4])): ?>
																<td align="center"><?= $data['jk']?></td>
															<?php endif; ?>
															<?php if (in_array($analisis_master['subjek_tipe'], [1, 2, 3, 4, 7, 8])): ?>
																<td><?= $data['dusun']?></td>
																<td><?= $data['rw']?></td>
																<?php if ($analisis_master['subjek_tipe'] != 7): ?>
																	<td><?= $data['rt']?></td>
																<?php endif; ?>
															<?php endif; ?>
															<td align="center"><?= $data['set']?></td>
														</tr>
													<?php endforeach; ?>
												<?php else: ?>
													<tr>
														<td colspan="9" class="text-center">Data Tidak Tersedia</td>
													</tr>
												<?php endif; ?>
											</tbody>
										</table>
									</div>
								</form>
								<div class="row">
									<div class="col-sm-6">
										<div class="dataTables_length">
											<form id="paging" action="<?= site_url('analisis_respon')?>" method="post" class="form-horizontal">
												<label>
													Tampilkan
													<select name="per_page" class="form-control input-sm" onchange="$('#paging').submit()">
														<option value="20" <?= selected($per_page, 20); ?>>20</option>
														<option value="50" <?= selected($per_page, 50); ?>>50</option>
														<option value="100" <?= selected($per_page, 100); ?>>100</option>
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
													<li><a href="<?= site_url("analisis_respon/index/{$paging->start_link}/{$o}")?>" aria-label="First"><span aria-hidden="true">Awal</span></a></li>
												<?php endif; ?>
												<?php if ($paging->prev): ?>
													<li><a href="<?= site_url("analisis_respon/index/{$paging->prev}/{$o}")?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
												<?php endif; ?>
												<?php for ($i = $paging->start_link; $i <= $paging->end_link; $i++): ?>
													<li <?=jecho($p, $i, "class='active'")?>><a href="<?= site_url("analisis_respon/index/{$i}/{$o}")?>"><?= $i?></a></li>
												<?php endfor; ?>
												<?php if ($paging->next): ?>
													<li><a href="<?= site_url("analisis_respon/index/{$paging->next}/{$o}")?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
												<?php endif; ?>
												<?php if ($paging->end_link): ?>
													<li><a href="<?= site_url("analisis_respon/index/{$paging->end_link}/{$o}")?>" aria-label="Last"><span aria-hidden="true">Akhir</span></a></li>
												<?php endif; ?>
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<?php $this->load->view('global/confirm_delete'); ?>
