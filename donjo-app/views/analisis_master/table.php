<script>
	$(function() {
		var keyword = <?= $keyword ?>;
		$("#cari").autocomplete({
			source: keyword,
			maxShowItems: 10,
		});
	});
</script>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Master Analisis Data Potensi/Sumber Daya </h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('beranda') ?>"><i class="fa fa-home"></i> Beranda</a></li>
			<li class="active">Master Analisis</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<input id="mode-form" type="hidden" value="<?= $_SESSION['success'] ?>">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<?php if (can('u')): ?>
							<div class="btn-group btn-group-vertical">
								<a class="btn btn-social btn-flat btn-success btn-sm" data-toggle="dropdown"><i class='fa fa-plus'></i> Tambah Analisis Baru</a>
								<ul class="dropdown-menu" role="menu">
									<li>
										<a href="<?= site_url('analisis_master/form') ?>" class="btn btn-social btn-flat btn-block btn-sm" title="Analisis Baru"><i class="fa fa-plus"></i> Analisis Baru</a>
									</li>
									<li>
										<a href="<?= site_url('analisis_master/import_gform') ?>" class="btn btn-social btn-flat btn-block btn-sm" title="Impor dari Google Form" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Impor Google Form"><i class="fa fa-plus"></i> Impor dari Google Form</a>
									</li>
								</ul>
							</div>
						<?php endif; ?>
						<?php if (can('h')): ?>
							<a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform','<?= site_url("analisis_master/delete_all/{$p}/{$o}") ?>')" class="btn btn-social btn-flat	btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
						<?php endif; ?>
						<?php if (can('u')): ?>
							<a href="<?= site_url('analisis_master/import_analisis') ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Impor Analisis" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Impor Analisis"><i class="fa fa-upload"></i> Impor Analisis</a>
						<?php endif; ?>
						<a href="<?= site_url("{$this->controller}/clear") ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i>Bersihkan</a>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
									<form id="mainform" name="mainform" method="post">
										<div class="row">
											<div class="col-sm-6">
												<select class="form-control input-sm " name="filter" onchange="formAction('mainform','<?= site_url('analisis_master/filter') ?>')">
													<option value="">Pilih Subjek</option>
													<?php foreach ($list_subjek as $data) : ?>
														<option value="<?= $data['id'] ?>" <?php if ($filter == $data['id']) : ?>selected<?php endif ?>><?= $data['subjek'] ?></option>
													<?php endforeach; ?>
												</select>
												<select class="form-control input-sm " name="state" onchange="formAction('mainform', '<?= site_url('analisis_master/state') ?>')">
													<option value="">Pilih Status</option>
													<option value="1" <?php if ($state == 1) : ?>selected<?php endif ?>>Aktif</option>
													<option value="2" <?php if ($state == 2) : ?>selected<?php endif ?>>Tidak Aktif</option>
												</select>
											</div>
											<div class="col-sm-6">
												<div class="box-tools">
													<div class="input-group input-group-sm pull-right">
														<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="<?= html_escape($cari) ?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action','<?= site_url('analisis_master/search') ?>');$('#'+'mainform').submit();};">
														<div class="input-group-btn">
															<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action','<?= site_url('analisis_master/search') ?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
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
																<?php if (can('u')): ?>
																	<th><input type="checkbox" id="checkall" /></th>
																<?php endif; ?>
																<th>No</th>
																<th>Aksi</th>
																<?php if ($o == 4) : ?>
																	<th><a href="<?= site_url("analisis_master/index/{$p}/3") ?>">Nama <i class='fa fa-sort-asc fa-sm'></i></a></th>
																<?php elseif ($o == 3) : ?>
																	<th><a href="<?= site_url("analisis_master/index/{$p}/4") ?>">Nama <i class='fa fa-sort-desc fa-sm'></i></a></th>
																<?php else : ?>
																	<th><a href="<?= site_url("analisis_master/index/{$p}/3") ?>">Nama <i class='fa fa-sort fa-sm'></i></a></th>
																<?php endif; ?>
																<?php if ($o == 6) : ?>
																	<th nowrap><a href="<?= site_url("analisis_master/index/{$p}/5") ?>">Subjek/Unit Analisis <i class='fa fa-sort-asc fa-sm'></i></a></th>
																<?php elseif ($o == 5) : ?>
																	<th nowrap><a href="<?= site_url("analisis_master/index/{$p}/6") ?>">Subjek/Unit Analisis <i class='fa fa-sort-desc fa-sm'></i></a></th>
																<?php else : ?>
																	<th nowrap><a href="<?= site_url("analisis_master/index/{$p}/5") ?>">Subjek/Unit Analisis <i class='fa fa-sort fa-sm'></i></a></th>
																<?php endif; ?>
																<?php if ($o == 2) : ?>
																	<th nowrap><a href="<?= site_url("analisis_master/index/{$p}/1") ?>">Status <i class='fa fa-sort-asc fa-sm'></i></a></th>
																<?php elseif ($o == 1) : ?>
																	<th nowrap><a href="<?= site_url("analisis_master/index/{$p}/2") ?>">Status <i class='fa fa-sort-desc fa-sm'></i></a></th>
																<?php else : ?>
																	<th nowrap><a href="<?= site_url("analisis_master/index/{$p}/1") ?>">Status <i class='fa fa-sort fa-sm'></i></a></th>
																<?php endif; ?>
																<th>ID Google Form</th>
																<th>Sinkronasi Google Form</th>
															</tr>
														</thead>
														<tbody>
															<?php foreach ($main as $data) : ?>
																<tr>
																	<?php if (can('u')): ?>
																		<td>
																			<?php if ($data['jenis'] != 1) : ?>
																				<input type="checkbox" name="id_cb[]" value="<?= $data['id'] ?>" />
																			<?php endif; ?>
																		</td>
																	<?php endif; ?>
																	<td><?= $data['no'] ?></td>
																	<td nowrap>
																		<a href="<?= site_url("analisis_master/menu/{$data['id']}") ?>" class="btn bg-purple btn-flat btn-sm" title="Rincian Analisis"><i class="fa fa-list-ol"></i></a>
																		<a href="<?= site_url("analisis_master/ekspor/{$data['id']}") ?>" class="btn bg-navy btn-flat btn-sm" title="Ekspor Analisis"><i class="fa fa-download"></i></a>
																		<?php if (can('u')): ?>
																			<a href="<?= site_url("analisis_master/form/{$p}/{$o}/{$data['id']}") ?>" class="btn bg-orange btn-flat btn-sm" title="Ubah Data"><i class='fa fa-edit'></i></a>
																		<?php endif; ?>
																		<?php if ($data['jenis'] != 1 && can('u')) : ?>
																			<a href="#" data-href="<?= site_url("analisis_master/delete/{$p}/{$o}/{$data['id']}") ?>" class="btn bg-maroon btn-flat btn-sm" title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																		<?php endif; ?>
																		<?php if ($data['gform_id'] != null && $data['gform_id'] != '' && can('u')) : ?>
																			<a href="<?= site_url("analisis_master/update_gform/{$data['id']}") ?>" class="btn bg-navy btn-flat btn-sm" title="Update Data Google Form"><i class='fa fa-refresh'></i></a>
																		<?php endif; ?>
																	</td>
																	<td width="30%"><?= $data['nama'] ?></td>
																	<td nowrap><?= $data['subjek'] ?></td>
																	<td><?= $data['lock'] ?></td>
																	<td><?= $data['gform_id'] ?? '-' ?></td>
																	<td><?= tgl_indo($data['gform_last_sync']) ?></td>
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
	</section>
</div>
<?php $this->load->view('global/confirm_delete'); ?>
<?php $this->load->view('analisis_master/modal_pertanyaan', $data); ?>
<?php $this->load->view('analisis_master/modal_jawaban_pilihan', $data); ?>
<?php $this->load->view('analisis_master/modal_hasil_import', $data); ?>
<script type="text/javascript" src="<?= asset('js/custom-analisis.js') ?>"></script>