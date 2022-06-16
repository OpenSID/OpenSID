<script type="text/javascript">
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
		<h1>Pengaturan Komentar</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Pengaturan Komentar</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="mainform" name="mainform" method="post">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-info">
						<div class="box-header with-border">
							<?php if ($this->CI->cek_hak_akses('h')): ?>
								<a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform', '<?=site_url("komentar/delete_all/{$p}/{$o}")?>')" class="btn btn-social btn-flat btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
							<?php endif; ?>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-sm-12">
									<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
										<form id="mainform" name="mainform" method="post">
											<div class="row">
												<div class="col-sm-6">
													<select class="form-control input-sm " name="filter" onchange="formAction('mainform', '<?=site_url('komentar/filter')?>')">
														<option value="">Semua</option>
														<option value="1" <?php if ($filter_status == 1): ?>selected<?php endif ?>>Aktif</option>
														<option value="2" <?php if ($filter_status == 2): ?>selected<?php endif ?>>Tidak Aktif</option>
													</select>
												</div>
												<div class="col-sm-6">
													<div class="box-tools">
														<div class="input-group input-group-sm pull-right">
															<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="<?=html_escape($cari)?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?= site_url("{$this->controller}/search")?>');$('#'+'mainform').submit();}">
															<div class="input-group-btn">
																<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?= site_url("{$this->controller}/search")?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
															</div>
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
																	<?php if ($this->CI->cek_hak_akses('h')): ?>
																		<th><input type="checkbox" id="checkall"/></th>
																	<?php endif; ?>
																	<th>No</th>
																	<?php if ($this->CI->cek_hak_akses('u')): ?>
																		<th>Aksi</th>
																	<?php endif; ?>
																	<th>Pengirim</th>
																	<?php if ($o == 6): ?>
																		<th><a href="<?= site_url("komentar/index/{$p}/5")?>">Isi Komentar <i class='fa fa-sort-asc fa-sm'></i></a></th>
																	<?php elseif ($o == 5): ?>
																		<th><a href="<?= site_url("komentar/index/{$p}/6")?>">Isi Komentar <i class='fa fa-sort-desc fa-sm'></i></a></th>
																	<?php else: ?>
																		<th><a href="<?= site_url("komentar/index/{$p}/5")?>">Isi Komentar <i class='fa fa-sort fa-sm'></i></a></th>
																	<?php endif; ?>
																	<th>No. HP Pengirim</th>
																	<th>Email Pengirim</th>
																	<th>Judul Artikel</th>
																	<?php if ($o == 8): ?>
																		<th nowrap><a href="<?= site_url("komentar/index/{$p}/7")?>">Aktif <i class='fa fa-sort-asc fa-sm'></i></a></th>
																	<?php elseif ($o == 7): ?>
																		<th nowrap><a href="<?= site_url("komentar/index/{$p}/8")?>">Aktif <i class='fa fa-sort-desc fa-sm'></i></a></th>
																	<?php else: ?>
																		<th nowrap><a href="<?= site_url("komentar/index/{$p}/7")?>">Aktif <i class='fa fa-sort fa-sm'></i></a></th>
																	<?php endif; ?>
																	<?php if ($o == 10): ?>
																		<th nowrap><a href="<?= site_url("komentar/index/{$p}/9")?>">Dimuat Pada <i class='fa fa-sort-asc fa-sm'></i></a></th>
																	<?php elseif ($o == 9): ?>
																		<th nowrap><a href="<?= site_url("komentar/index/{$p}/10")?>">Dimuat Pada <i class='fa fa-sort-desc fa-sm'></i></a></th>
																	<?php else: ?>
																		<th nowrap><a href="<?= site_url("komentar/index/{$p}/9")?>">Dimuat Pada <i class='fa fa-sort fa-sm'></i></a></th>
																	<?php endif; ?>
																</tr>
															</thead>
															<tbody>
																<?php foreach ($main as $data): ?>
																	<tr>
																		<?php if ($this->CI->cek_hak_akses('h')): ?>
																			<td><input type="checkbox" name="id_cb[]" value="<?=$data['id']?>" /></td>
																		<?php endif; ?>
																		<td><?=$data['no']?></td>
																		<?php if ($this->CI->cek_hak_akses('u')): ?>
																			<td nowrap>
																				<?php if ($this->CI->cek_hak_akses('u')): ?>
																					<a href="<?= site_url("komentar/form/{$p}/{$o}/{$data['id']}")?>" class="btn btn-warning btn-flat btn-sm" title="Ubah"><i class="fa fa-edit"></i></a>
																					<?php if ($data['status'] == '2'): ?>
																					 <a href="<?= site_url('komentar/komentar_lock/' . $data['id'])?>" class="btn bg-navy btn-flat btn-sm" title="Aktifkan Komentar"><i class="fa fa-lock">&nbsp;</i></a>
																					<?php elseif ($data['status'] == '1'): ?>
																					 <a href="<?= site_url('komentar/komentar_unlock/' . $data['id'])?>" class="btn bg-navy btn-flat btn-sm" title="Non Aktifkan Komentar"><i class="fa fa-unlock"></i></a>
																					<?php endif ?>
																				<?php endif; ?>
																				<?php if ($this->CI->cek_hak_akses('h')): ?>
																					<a href="#" data-href="<?= site_url("komentar/delete/{$p}/{$o}/{$data['id']}")?>" class="btn bg-maroon btn-flat btn-sm" title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																				<?php endif; ?>
																			</td>
																		<?php endif; ?>
																		<td nowrap><?= $data['owner']?></td>
																		<td><?= $data['komentar']?></td>
																		<td><?= $data['no_hp']?></td>
																		<td><?= $data['email']?></td>
																		<td>
																			<a href="<?= site_url('artikel/' . buat_slug($data))?>" target="_blank"><?= $data['artikel']?></a>
																		</td>
																		<td><?= $data['aktif']?></td>
																		<td nowrap><?= tgl_indo2($data['tgl_upload'])?></td>
																	</tr>
																<?php endforeach; ?>
															</tbody>
														</table>
													</div>
												</div>
											</div>
										</form>
										<div class="row">
											<div class="col-sm-6">
												<div class="dataTables_length">
													<form id="paging" action="<?= site_url('komentar')?>" method="post" class="form-horizontal">
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
															<li><a href="<?=site_url("komentar/index/{$paging->start_link}/{$o}")?>" aria-label="First"><span aria-hidden="true">Awal</span></a></li>
														<?php endif; ?>
														<?php if ($paging->prev): ?>
															<li><a href="<?=site_url("komentar/index//{$paging->prev}/{$o}")?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
														<?php endif; ?>
														<?php for ($i = $paging->start_link; $i <= $paging->end_link; $i++): ?>
															<li <?=jecho($p, $i, "class='active'")?>><a href="<?= site_url("komentar/index/{$i}/{$o}")?>"><?= $i?></a></li>
														<?php endfor; ?>
														<?php if ($paging->next): ?>
															<li><a href="<?=site_url("komentar/index/{$paging->next}/{$o}")?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
														<?php endif; ?>
														<?php if ($paging->end_link): ?>
															<li><a href="<?=site_url("komentar/index/{$paging->end_link}/{$o}")?>" aria-label="Last"><span aria-hidden="true">Akhir</span></a></li>
														<?php endif; ?>
													</ul>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class='modal fade' id='confirm-delete' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
								<div class='modal-dialog'>
									<div class='modal-content'>
										<div class='modal-header'>
											<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
											<h4 class='modal-title' id='myModalLabel'><i class='fa fa-exclamation-triangle text-red'></i> Konfirmasi</h4>
										</div>
										<div class='modal-body btn-info'>
											Apakah Anda yakin ingin mengarsipkan data ini?
										</div>
										<div class='modal-footer'>
											<button type="button" class="btn btn-social btn-flat btn-warning btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
											<a class='btn-ok'>
												<button type="button" class="btn btn-social btn-flat btn-danger btn-sm" id="ok-delete"><i class='fa fa-file-archive-o'></i> Arsipkan</button>
											</a>
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

