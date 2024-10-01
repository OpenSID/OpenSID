<script>
	$(function()
	{
		var keyword = <?= $keyword?> ;
		$( "#cari" ).autocomplete(
		{
			source: keyword,
			maxShowItems: 10,
		});
	});
</script>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengaturan Indikator - <?= $analisis_master['nama']?></h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('beranda')?>"><i class="fa fa-home"></i> Beranda</a></li>
			<li><a href="<?= site_url('analisis_master/clear')?>"> Master Analisis</a></li>
			<li><a href="<?= site_url('analisis_master/leave'); ?>"><?= $analisis_master['nama']; ?></a></li>
			<li class="active">Pengaturan Indikator</li>
		</ol>
	</section>
	</section>
	<section class="content" id="maincontent">
		<form id="mainform" name="mainform" method="post">
			<div class="row">
				<div class="col-md-4 col-lg-3">
					<?php $this->load->view('analisis_master/left', $data); ?>
				</div>
				<div class="col-md-8 col-lg-9">
					<div class="box box-info">
						<div class="box-header with-border">
							<?php if ($analisis_master['lock'] == 1): ?>
								<?php if (can('u')): ?>
									<a href="<?= site_url('analisis_indikator/form')?>" class="btn btn-social btn-flat btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block <?php if ($analisis_master['jenis'] == 1): ?>disabled<?php endif; ?>" title="Tambah Indikator Baru"><i class="fa fa-plus"></i> Tambah Indikator Baru</a>
								<?php endif; ?>
								<?php if (can('h')): ?>
									<a href="#confirm-delete" title="Hapus Data"
										<?php if ($analisis_master['jenis'] != 1): ?>
											onclick="deleteAllBox('mainform', '<?= site_url("analisis_indikator/delete_all/{$p}/{$o}")?>')"
										<?php endif; ?>
										class="btn btn-social btn-flat btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
								<?php endif; ?>
								<a href="<?= site_url('analisis_master/leave'); ?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left "></i> Kembali Ke <?= $analisis_master['nama']?></a>
							<?php endif; ?>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-sm-12">
									<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
										<form id="mainform" name="mainform" method="post">
											<div class="row">
												<div class="col-sm-8">
													<select class="form-control input-sm" name="tipe" onchange="formAction('mainform', '<?= site_url('analisis_indikator/tipe')?>')">
														<option value="">Tipe Pertanyaan</option>
														<?php foreach ($list_tipe as $data): ?>
															<option value="<?= $data['id']?>" <?php if ($tipe == $data['id']): ?>selected<?php endif ?>><?= $data['tipe']?></option>
														<?php endforeach; ?>
													</select>
													<select class="form-control input-sm" name="kategori" onchange="formAction('mainform', '<?= site_url('analisis_indikator/kategori')?>')">
														<option value="">Tipe Kategori</option>
														<?php foreach ($list_kategori as $data): ?>
															<option value="<?= $data['id']?>" <?php if ($kategori == $data['id']): ?>selected<?php endif ?>><?= $data['kategori']?></option>
														<?php endforeach; ?>
													</select>
													<select class="form-control input-sm" name="filter" onchange="formAction('mainform', '<?= site_url('analisis_indikator/filter')?>')">
														<option value="">Aksi Analisis</option>
														<option value="1" <?php if ($filter == 1): ?>selected<?php endif ?>>Ya</option>
														<option value="2" <?php if ($filter == 2): ?>selected<?php endif ?>>Tidak</option>
													</select>
												</div>
												<div class="col-sm-4">
													<div class="input-group input-group-sm pull-right">
														<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="<?=html_escape($cari)?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?= site_url('analisis_indikator/search')?>');$('#'+'mainform').submit();}">
														<div class="input-group-btn">
															<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?= site_url('analisis_indikator/search')?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-12">
													<div class="table-responsive">
														<table class="table table-bordered table-striped dataTable table-hover">
															<thead class="bg-gray disabled color-palette">
																<tr>
																	<?php if (can('u')): ?>
																		<th><input type="checkbox" id="checkall"/></th>
																	<?php endif; ?>
																	<th>No</th>
																	<th>Aksi</th>
																	<?php if ($o == 2): ?>
																		<th nowrap><a href="<?= site_url("analisis_indikator/index/{$p}/1")?>">Kode <i class='fa fa-sort-asc fa-sm'></i></a></th>
																	<?php elseif ($o == 1): ?>
																		<th nowrap><a href="<?= site_url("analisis_indikator/index/{$p}/2")?>">Kode <i class='fa fa-sort-desc fa-sm'></i></a></th>
																	<?php else: ?>
																		<th nowrap><a href="<?= site_url("analisis_indikator/index/{$p}/1")?>">Kode <i class='fa fa-sort fa-sm'></i></a></th>
																	<?php endif; ?>
																	<?php if ($o == 4): ?>
																		<th nowrap><a href="<?= site_url("analisis_indikator/index/{$p}/3")?>">Pertanyaan/Indikator <i class='fa fa-sort-asc fa-sm'></i></a></th>
																	<?php elseif ($o == 3): ?>
																		<th nowrap><a href="<?= site_url("analisis_indikator/index/{$p}/4")?>">Pertanyaan/Indikator <i class='fa fa-sort-desc fa-sm'></i></a></th>
																	<?php else: ?>
																		<th nowrap><a href="<?= site_url("analisis_indikator/index/{$p}/3")?>">Pertanyaan/Indikator <i class='fa fa-sort fa-sm'></i></a></th>
																	<?php endif; ?>
																	<?php if ($o == 6): ?>
																		<th nowrap><a href="<?= site_url("analisis_indikator/index/{$p}/5")?>">Tipe Pertanyaan <i class='fa fa-sort-asc fa-sm'></i></a></th>
																	<?php elseif ($o == 5): ?>
																		<th nowrap><a href="<?= site_url("analisis_indikator/index/{$p}/6")?>">Tipe Pertanyaan <i class='fa fa-sort-desc fa-sm'></i></a></th>
																	<?php else: ?>
																		<th nowrap><a href="<?= site_url("analisis_indikator/index/{$p}/5")?>">Tipe Pertanyaan <i class='fa fa-sort fa-sm'></i></a></th>
																	<?php endif; ?>
																	<?php if ($o == 6): ?>
																		<th nowrap><a href="<?= site_url("analisis_indikator/index/{$p}/5")?>">Kategori / Variabel <i class='fa fa-sort-asc fa-sm'></i></a></th>
																	<?php elseif ($o == 5): ?>
																		<th><a href="<?= site_url("analisis_indikator/index/{$p}/6")?>">Kategori / Variabel <i class='fa fa-sort-desc fa-sm'></i></a></th>
																	<?php else: ?>
																		<th nowrap><a href="<?= site_url("analisis_indikator/index/{$p}/5")?>">Kategori / Variabel <i class='fa fa-sort fa-sm'></i></a></th>
																	<?php endif; ?>
																	<?php if ($o == 2): ?>
																		<th nowrap><a href="<?= site_url("analisis_indikator/index/{$p}/1")?>">Bobot <i class='fa fa-sort-asc fa-sm'></i></a></th>
																	<?php elseif ($o == 1): ?>
																		<th nowrap><a href="<?= site_url("analisis_indikator/index/{$p}/2")?>">Bobot <i class='fa fa-sort-desc fa-sm'></i></a></th>
																	<?php else: ?>
																		<th nowrap><a href="<?= site_url("analisis_indikator/index/{$p}/1")?>">Bobot <i class='fa fa-sort fa-sm'></i></a></th>
																	<?php endif; ?>
																	<?php if ($o == 2): ?>
																		<th nowrap><a href="<?= site_url("analisis_indikator/index/{$p}/1")?>">Aksi Analisis <i class='fa fa-sort-asc fa-sm'></i></a></th>
																	<?php elseif ($o == 1): ?>
																		<th nowrap><a href="<?= site_url("analisis_indikator/index/{$p}/2")?>">Aksi Analisis <i class='fa fa-sort-desc fa-sm'></i></a></th>
																	<?php else: ?>
																		<th nowrap><a href="<?= site_url("analisis_indikator/index/{$p}/1")?>">Aksi Analisis <i class='fa fa-sort fa-sm'></i></a></th>
																	<?php endif; ?>
																</tr>
															</thead>
															<tbody>
																<?php foreach ($main as $data): ?>
																	<tr>
																		<?php if ($analisis_master['lock'] == 1 && can('u')): ?>
																			<td><input type="checkbox" name="id_cb[]" value="<?= $data['id']?>" /></td>
																		<?php endif; ?>
																		<td><?= $data['no']?></td>
																		<?php if ($analisis_master['lock'] == 1): ?>
																			<td nowrap>
																				<?php if ($data['id_tipe'] == 1 || $data['id_tipe'] == 2): ?>
																					<a href="<?= site_url("analisis_indikator/parameter/{$data['id']}")?>" class="btn bg-purple btn-flat btn-sm"  title="Jawaban"><i class='fa fa-list'></i></a>
																				<?php endif; ?>
																				<?php if (can('u')): ?>
																					<a href="<?= site_url("analisis_indikator/form/{$p}/{$o}/{$data['id']}")?>" class="btn bg-orange btn-flat btn-sm"  title="Ubah Data"><i class='fa fa-edit'></i></a>
																				<?php endif; ?>
																				<?php if ($analisis_master['jenis'] != 1 && can('h')): ?>
																					<a href="#" data-href="<?= site_url("analisis_indikator/delete/{$p}/{$o}/{$data['id']}")?>" class="btn bg-maroon btn-flat btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																				<?php endif; ?>
																			</td>
																		<?php endif; ?>
																		<td><label><?= $data['nomor']?></label></td>
																		<td><?= $data['pertanyaan']?></td>
																		<td><?= $data['tipe_indikator']?></td>
																		<td><?= $data['kategori']?></td>
																		<td><?= $data['bobot']?></td>
																		<td><?= $data['act_analisis']?></td>
																	</tr>
																<?php endforeach; ?>
															</tbody>
															</tbody>
														</table>
													</div>
												</div>
											</div>
										</form>
										<div class="row">
											<div class="col-sm-6">
												<div class="dataTables_length">
													<form id="paging" action="<?= site_url('analisis_indikator')?>" method="post" class="form-horizontal">
														<label>
															Tampilkan
															<select name="per_page" class="form-control input-sm" onchange="$('#paging').submit()">
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
											<div class="col-sm-6">
												<div class="dataTables_paginate paging_simple_numbers">
													<ul class="pagination">
														<?php if ($paging->start_link): ?>
															<li><a href="<?= site_url("analisis_indikator/index/{$paging->start_link}/{$o}")?>" aria-label="First"><span aria-hidden="true">Awal</span></a></li>
														<?php endif; ?>
														<?php if ($paging->prev): ?>
															<li><a href="<?= site_url("analisis_indikator/index/{$paging->prev}/{$o}")?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
														<?php endif; ?>
														<?php for ($i = $paging->start_link; $i <= $paging->end_link; $i++): ?>
															<li <?=jecho($p, $i, "class='active'")?>><a href="<?= site_url("analisis_indikator/index/{$i}/{$o}")?>"><?= $i?></a></li>
														<?php endfor; ?>
														<?php if ($paging->next): ?>
															<li><a href="<?= site_url("analisis_indikator/index/{$paging->next}/{$o}")?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
														<?php endif; ?>
														<?php if ($paging->end_link): ?>
															<li><a href="<?= site_url("analisis_indikator/index/{$paging->end_link}/{$o}")?>" aria-label="Last"><span aria-hidden="true">Akhir</span></a></li>
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
			</div>
		</form>
	</section>
</div>
<?php $this->load->view('global/confirm_delete'); ?>
