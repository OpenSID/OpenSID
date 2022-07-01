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
		<h1>Pengaturan Periode - <?= $analisis_master['nama']?></h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('analisis_master/clear')?>"> Master Analisis</a></li>
			<li><a href="<?= site_url('analisis_master/leave'); ?>"><?= $analisis_master['nama']; ?></a></li>
			<li class="active">Pengaturan Periode</li>
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
							<?php if ($this->CI->cek_hak_akses('u')): ?>
								<a href="<?= site_url('analisis_periode/form')?>" class="btn btn-social btn-flat btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Tambah Priode Baru"><i class="fa fa-plus"></i> Tambah Periode Baru</a>
							<?php endif; ?>
							<?php if ($this->CI->cek_hak_akses('h')): ?>
								<a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform', '<?= site_url("analisis_periode/delete_all/{$p}/{$o}")?>')" class="btn btn-social btn-flat btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
							<?php endif; ?>
							<a href="<?= site_url('analisis_master/leave'); ?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left "></i> Kembali Ke <?= $analisis_master['nama']?></a>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-sm-12">
									<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
										<form id="mainform" name="mainform" method="post">
											<div class="row">
												<div class="col-sm-6">
													<select class="form-control input-sm " name="state" onchange="formAction('mainform','<?= site_url('analisis_periode/state')?>')">
														<option value="">-- Status Pendataan --</option>
														<?php foreach ($list_state as $data): ?>
															<option value="<?= $data['id']?>" <?php if ($state == $data['id']): ?>selected<?php endif ?>><?= $data['nama']?></option>
														<?php endforeach; ?>
													</select>
												</div>
												<div class="col-sm-6">
													<div class="input-group input-group-sm pull-right">
														<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="<?=html_escape($cari)?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?= site_url('analisis_periode/search')?>');$('#'+'mainform').submit();}">
														<div class="input-group-btn">
															<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?= site_url('analisis_periode/search')?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-12">
													<div class="table-responsive">
														<table class="table table-bordered table-striped dataTable table-hover nowrap">
															<thead class="bg-gray disabled color-palette">
																<tr>
																	<?php if ($this->CI->cek_hak_akses('u')): ?>
																		<th><input type="checkbox" id="checkall"/></th>
																	<?php endif; ?>
																	<th>No</th>
																	<?php if ($this->CI->cek_hak_akses('u')): ?>
																		<th>Aksi</th>
																	<?php endif; ?>
																	<?php if ($o == 4): ?>
																		<th><a href="<?= site_url("analisis_periode/index/{$p}/3")?>">Periode <i class='fa fa-sort-asc fa-sm'></i></a></th>
																	<?php elseif ($o == 3): ?>
																		<th><a href="<?= site_url("analisis_periode/index/{$p}/4")?>">Periode <i class='fa fa-sort-desc fa-sm'></i></a></th>
																	<?php else: ?>
																		<th><a href="<?= site_url("analisis_periode/index/{$p}/3")?>">Periode <i class='fa fa-sort fa-sm'></i></a></th>
																	<?php endif; ?>
																	<?php if ($o == 2): ?>
																		<th><a href="<?= site_url("analisis_periode/index/{$p}/1")?>">Tahun Pelaksanaan <i class='fa fa-sort-asc fa-sm'></i></a></th>
																	<?php elseif ($o == 1): ?>
																		<th><a href="<?= site_url("analisis_periode/index/{$p}/2")?>">Tahun Pelaksanaan <i class='fa fa-sort-desc fa-sm'></i></a></th>
																	<?php else: ?>
																		<th><a href="<?= site_url("analisis_periode/index/{$p}/1")?>">Tahun Pelaksanaan <i class='fa fa-sort fa-sm'></i></a></th>
																	<?php endif; ?>
																	<th>Tahap Pendataan</th>
																	<th>Keterangan</th>
																	<th>Aktif</th>
																</tr>
															</thead>
															<tbody>
																<?php foreach ($main as $data): ?>
																	<tr>
																		<?php if ($this->CI->cek_hak_akses('u')): ?>
																			<td><input type="checkbox" name="id_cb[]" value="<?= $data['id']?>" /></td>
																		<?php endif; ?>
																		<td><?= $data['no']?></td>
																		<?php if ($this->CI->cek_hak_akses('u')): ?>
																			<td nowrap>
																				<a href="<?= site_url("analisis_periode/form/{$p}/{$o}/{$data['id']}")?>" class="btn bg-orange btn-flat btn-sm"  title="Ubah Data Priode" ><i class='fa fa-edit'></i></a>
																				<?php if ($this->CI->cek_hak_akses('h')): ?>
																					<a href="#" data-href="<?= site_url("analisis_periode/delete/{$p}/{$o}/{$data['id']}")?>" class="btn bg-maroon btn-flat btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																				<?php endif; ?>
																			</td>
																		<?php endif; ?>
																		<td><?= $data['nama']?></td>
																		<td><?= $data['tahun_pelaksanaan']?></td>
																		<td><?= $data['status']?></td>
																		<td><?= $data['keterangan']?></td>
																		<td><?= $data['aktif']?></td>
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
													<form id="paging" action="<?= site_url('analisis_periode')?>" method="post" class="form-horizontal">
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
															<li><a href="<?= site_url("analisis_periode/index/{$paging->start_link}/{$o}")?>" aria-label="First"><span aria-hidden="true">Awal</span></a></li>
														<?php endif; ?>
														<?php if ($paging->prev): ?>
															<li><a href="<?= site_url("analisis_periode/index/{$paging->prev}/{$o}")?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
														<?php endif; ?>
														<?php for ($i = $paging->start_link; $i <= $paging->end_link; $i++): ?>
															<li <?=jecho($p, $i, "class='active'")?>><a href="<?= site_url("analisis_periode/index/{$i}/{$o}")?>"><?= $i?></a></li>
														<?php endfor; ?>
														<?php if ($paging->next): ?>
															<li><a href="<?= site_url("analisis_periode/index/{$paging->next}/{$o}")?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
														<?php endif; ?>
														<?php if ($paging->end_link): ?>
															<li><a href="<?= site_url("analisis_periode/index/{$paging->end_link}/{$o}")?>" aria-label="Last"><span aria-hidden="true">Akhir</span></a></li>
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