<script>
	$(function() {
		var keyword = <?= $keyword?> ;
		$( "#cari" ).autocomplete( {
			source: keyword,
			maxShowItems: 10,
		});
	});
</script>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Artikel <?= $kategori['kategori']; ?></h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Artikel <?= $kategori['kategori']; ?></li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="mainform" name="mainform" method="post">
			<div class="row">
				<div class="col-md-3">
					<div class="box box-info">
						<div class="box-body no-padding">
							<!-- <h3 class="box-title"> -->
								<ul class="nav nav-pills nav-stacked">
									<li class="<?= jecho($cat, -1, 'active'); ?>">
										<a href='<?= site_url('web/tab/-1')?>'>
											Semua Artikel Dinamis
										</a>
									</li>
								</ul>
							<!-- </h3> -->
						</div>
					</div>
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">Kategori Artikel</h3>
							<div class="box-tools">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							</div>
						</div>
						<div class="box-body no-padding">
							<ul class="nav nav-pills nav-stacked">
								<?php foreach ($list_kategori as $data): ?>
									<li class="<?= jecho($cat, $data['id'], 'active'); ?>">
										<a href='<?= site_url("web/tab/{$data['id']}")?>'>
											<?= $data['kategori']; ?>
										</a>
									</li>
									<?php foreach ($data['submenu'] as $submenu): ?>
										<li class="<?= jecho($cat, $submenu['id'], 'active'); ?>">
											<a href='<?= site_url("web/tab/{$submenu['id']}")?>'>
												&emsp;<?= $submenu['kategori']; ?>
											</a>
										</li>
									<?php endforeach; ?>
								<?php endforeach; ?>
							</ul>
						</div>
					</div>
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">Artikel Statis</h3>
							<div class="box-tools">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							</div>
						</div>
						<div class="box-body no-padding">
							<ul class="nav nav-pills nav-stacked">
								<li class="<?= jecho($cat, 999, 'active'); ?>"><a href="<?= site_url('web/tab/999')?>">Halaman Statis</a></li>
								<li class="<?= jecho($cat, 1000, 'active'); ?>"><a href="<?= site_url('web/tab/1000')?>">Agenda</a></li>
								<li class="<?= jecho($cat, 1001, 'active'); ?>"><a href="<?= site_url('web/tab/1001')?>">Keuangan</a></li>
							</ul>
						</div>
					</div>
				</div>
				<div class="col-md-9">
					<div class="box box-info">
						<div class="box-header with-border">
							<?php if ($this->CI->cek_hak_akses('u') && $cat > 0): ?>
								<a href="<?= site_url('web/form')?>" class="btn btn-social btn-flat btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Tambah Artikel">
									<i class="fa fa-plus"></i>Tambah
									<?php if ($kategori): ?>
										<?= $kategori['kategori']; ?>
									<?php elseif ($cat == 1000): ?>
										Agenda
									<?php elseif ($cat == 1001): ?>
										Artikel Keuangan
									<?php else: ?>
										Artikel Statis
									<?php endif; ?> Baru
								</a>
							<?php endif; ?>
							<?php if ($this->CI->cek_hak_akses('h')): ?>
								<a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform', '<?= site_url('web/delete_all')?>')" class="btn btn-social btn-flat btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
								<?php if ($cat > 0 && $cat < 999): ?>
									<a href="#confirm-delete" title="Hapus Kategori <?=$kategori['kategori']?>" onclick="deleteAllBox('mainform', '<?= site_url('web/hapus')?>')" class="btn btn-social btn-flat btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class='fa fa-trash-o'></i> Hapus Kategori <?=$kategori['kategori']?></a>
								<?php endif; ?>
							<?php endif; ?>
							<?php if ($cat == 999): ?>
								<a href="<?= site_url('web/reset')?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Reset Hit" data-toggle="modal" data-target="#reset-hit" data-remote="false"><i class="fa fa-spinner"></i> Reset Hit</a>
							<?php endif; ?>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-sm-12">
									<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
										<form id="mainform" name="mainform" method="post">
											<div class="row">
												<div class="col-sm-6">
													<select class="form-control input-sm" name="status" onchange="formAction('mainform', '<?= site_url("web/filter/status/{$cat}")?>')">
														<option value="">Semua</option>
														<option value="1" <?php selected($status, 1); ?>>Aktif</option>
														<option value="2" <?php selected($status, 2); ?>>Tidak Aktif</option>
													</select>
												</div>
												<div class="col-sm-6">
													<div class="box-tools">
														<div class="input-group input-group-sm pull-right">
															<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="<?=html_escape($cari)?>" onkeypress="if (event.keyCode == 13):$('#'+'mainform').attr('action', '<?= site_url('web/filter/cari/$cat')?>');$('#'+'mainform').submit();endif">
															<div class="input-group-btn">
																<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?= site_url("web/filter/cari/{$cat}")?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-12">
													<div class="table-responsive">
														<table class="table table-bordered dataTable table-striped table-hover tabel-daftar">
															<thead class="bg-gray disabled color-palette">
																<tr>
																	<?php if ($this->CI->cek_hak_akses('h')): ?>
																		<th><input type="checkbox" id="checkall"/></th>
																	<?php endif; ?>
																	<th>No</th>
																	<th>Aksi</th>
																	<?php if ($o == 2): ?>
																		<th width="50%"><a href="<?= site_url("web/index/{$p}/1")?>">Judul <i class='fa fa-sort-asc fa-sm'></i></a></th>
																	<?php elseif ($o == 1): ?>
																		<th width="50%"><a href="<?= site_url("web/index/{$p}/2")?>">Judul <i class='fa fa-sort-desc fa-sm'></i></a></th>
																	<?php else: ?>
																		<th width="50%"><a href="<?= site_url("web/index/{$p}/1")?>">Judul <i class='fa fa-sort fa-sm'></i></a></th>
																	<?php endif; ?>
																	<?php if ($o == 4): ?>
																		<th nowrap><a href="<?= site_url("web/index/{$p}/3")?>">Hit <i class='fa fa-sort-asc fa-sm'></i></a></th>
																	<?php elseif ($o == 3): ?>
																		<th nowrap><a href="<?= site_url("web/index/{$p}/4")?>">Hit <i class='fa fa-sort-desc fa-sm'></i></a></th>
																	<?php else: ?>
																		<th nowrap><a href="<?= site_url("web/index/{$p}/3")?>">Hit <i class='fa fa-sort fa-sm'></i></a></th>
																	<?php endif; ?>
																	<?php if ($o == 6): ?>
																		<th nowrap><a href="<?= site_url("web/index/{$p}/5")?>">Diposting Pada <i class='fa fa-sort-asc fa-sm'></i></a></th>
																	<?php elseif ($o == 5): ?>
																		<th nowrap><a href="<?= site_url("web/index/{$p}/6")?>">Diposting Pada <i class='fa fa-sort-desc fa-sm'></i></a></th>
																	<?php else: ?>
																		<th nowrap><a href="<?= site_url("web/index/{$p}/5")?>">Diposting Pada <i class='fa fa-sort fa-sm'></i></a></th>
																	<?php endif; ?>
																</tr>
															</thead>
															<tbody>
																<?php foreach ($main as $data): ?>
																	<tr>
																		<?php if ($this->CI->cek_hak_akses('h')): ?>
																			<td class="padat"><input type="checkbox" name="id_cb[]" value="<?=$data['id']?>" <?php $data['boleh_ubah'] || print 'disabled'?> /></td>
																		<?php endif; ?>
																		<td class="padat"><?=$data['no']?></td>
																		<td class="aksi">
																			<?php if ($data['boleh_ubah'] && $this->CI->cek_hak_akses('u')): ?>
																				<a href="<?= site_url("web/form/{$data['id']}")?>" class="btn bg-orange btn-flat btn-sm" title="Ubah Data"><i class="fa fa-edit"></i></a>
																				<?php if ($this->CI->cek_hak_akses('h')): ?>
																					<a href="#" data-href="<?= site_url("web/delete/{$data['id']}")?>" class="btn bg-maroon btn-flat btn-sm" title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																				<?php endif; ?>
																				<a href="<?= site_url("web/ubah_kategori_form/{$data['id']}")?>" class="btn bg-purple btn-flat btn-sm" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ubah Kategori" title="Ubah Kategori"><i class="fa fa-folder-open"></i></a>
																				<?php if ($data['boleh_komentar'] == 1): ?>
																					<a href="<?= site_url("web/komentar_lock/{$data['id']}/2")?>" class="btn bg-info btn-flat btn-sm" title="Tutup Komentar Artikel"><i class="fa fa-comment-o"></i></a>
																				<?php else: ?>
																					<a href="<?= site_url("web/komentar_lock/{$data['id']}/1")?>" class="btn bg-info btn-flat btn-sm" title="Buka Komentar Artikel"><i class="fa fa-comment"></i></a>
																				<?php endif ?>
																				<?php if ($data['enabled'] == '1'): ?>
																					<a href="<?= site_url("web/artikel_lock/{$data['id']}/2"); ?>" class="btn bg-navy btn-flat btn-sm" title="Non Aktifkan Artikel"><i class="fa fa-unlock"></i></a>
																					<a href="<?= site_url("web/headline/{$data['id']}")?>" class="btn bg-teal btn-flat btn-sm" title="Jadikan Headline">
																						<i class="<?= ($data['headline'] == 1) ? 'fa fa-star-o' : 'fa fa-star' ?>"></i>
																					</a>
																					<a href="<?= site_url("web/slide/{$data['id']}"); ?>" class="btn bg-gray btn-flat btn-sm" title="<?= ($data['headline'] == 3) ? 'Keluarkan dari slide' : 'Masukkan ke dalam slide' ?>">
																						<i class="<?= ($data['headline'] == 3) ? 'fa fa-pause' : 'fa fa-play' ?>"></i>
																					</a>
																				<?php else: ?>
																					<a href="<?= site_url("web/artikel_lock/{$data['id']}/1"); ?>" class="btn bg-navy btn-flat btn-sm" title="Aktifkan Artikel"><i class="fa fa-lock"></i></a>
																				<?php endif ?>
																			<?php endif; ?>
																			<a href="<?= site_url('artikel/' . buat_slug($data)); ?>" target="_blank" class="btn bg-green btn-flat btn-sm" title="Lihat Artikel"><i class="fa fa-eye"></i></a>
																		</td>
																		<td><?= $data['judul']?></td>
																		<td nowrap><?= hit($data['hit'])?></td>
																		<td nowrap><?= tgl_indo2($data['tgl_upload'])?></td>
																	</tr>
																<?php endforeach; ?>
															</tbody>
														</table>
													</div>
												</div>
											</div>
										</form>
										<?php $this->load->view('global/paging'); ?>
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

<form action="<?= site_url('web/reset')?>" method="post">
	<div class='modal fade' id='reset-hit' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
		<div class='modal-dialog'>
			<div class='modal-content'>
				<div class='modal-header'>
					<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
					<h4 class='modal-title' id='myModalLabel'></i> Reset Hit</h4>
				</div>
				<div class='modal-body'>
					<div class="form-group">
						<code>Lakukan hapus hit ini jika artikel statis di menu atas website anda terkena kunjungan tak terduga, seperti robot(crawler), yang berlebihan. </code><br><br>
						<label for="hit">Reset Hit</label>
						<select class="form-control input-sm" required name="hit" width="100%">
							<option value="">Pilih persen hit yang akan dihapus</option>
							<?php for ($i = 1; $i <= 10; $i++): ?>
								<option value="<?=($i * 10)?>"><?=($i * 10) . '%'?></option>
							<?php endfor; ?>
						</select>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
					<button type="submit" class="btn btn-social btn-flat btn-info btn-sm"><i class='fa fa-check'></i> Simpan</button>
				</div>
			</div>
		</div>
	</div>
</form>
