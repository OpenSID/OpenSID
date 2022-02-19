<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengaturan Area</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid') ?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Pengaturan Area</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="mainform" name="mainform" method="post">
			<div class="row">
				<div class="col-md-3">
					<?php $this->load->view('plan/nav.php') ?>
				</div>
				<div class="col-md-9">
					<div class="box box-info">
						<div class="box-header with-border">
							<?php if ($this->CI->cek_hak_akses('u')) : ?>
								<a href="<?= site_url('area/form') ?>" class="btn btn-social btn-flat btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Tambah Data Baru">
									<i class="fa fa-plus"></i>Tambah Data Baru
								</a>
							<?php endif; ?>
							<?php if ($this->CI->cek_hak_akses('h')) : ?>
								<a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform', '<?= site_url("area/delete_all/{$p}/{$o}") ?>')" class="btn btn-social btn-flat btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
							<?php endif; ?>
							<a href="<?= site_url("{$this->controller}/clear") ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i>Bersihkan</a>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-sm-12">
									<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
										<form id="mainform" name="mainform" method="post">
											<div class="row">
												<div class="col-sm-7">
													<select class="form-control input-sm" name="filter" onchange="formAction('mainform', '<?= site_url('area/filter') ?>')">
														<option value="">Semua</option>
														<option value="1" <?php if ($filter == 1) : ?>selected<?php endif ?>>Aktif</option>
														<option value="2" <?php if ($filter == 2) : ?>selected<?php endif ?>>Tidak Aktif</option>
													</select>
													<select class="form-control input-sm" name="subpolygon" onchange="formAction('mainform', '<?= site_url('area/subpolygon') ?>')">
														<option value="">Jenis</option>
														<?php foreach ($list_subpolygon as $data) : ?>
															<option value="<?= $data['id'] ?>" <?php if ($subpolygon == $data['id']) : ?>selected<?php endif ?>><?= $data['nama'] ?></option>
														<?php endforeach; ?>
													</select>
													<?php if ($subpolygon) : ?>
														<select class="form-control input-sm" name="polygon" onchange="formAction('mainform', '<?= site_url('area/polygon') ?>')">
															<option value="">Kategori</option>
															<?php foreach ($list_polygon as $data) : ?>
																<option value="<?= $data['id'] ?>" <?php if ($polygon == $data['id']) : ?>selected<?php endif ?>><?= $data['nama'] ?></option>
															<?php endforeach; ?>
														</select>
													<?php endif; ?>
												</div>
												<div class="col-sm-5">
													<div class="box-tools">
														<div class="input-group input-group-sm pull-right">
															<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="<?= html_escape($cari) ?>" onkeypress="if (event.keyCode == 13):$('#'+'mainform').attr('action', '<?= site_url('area/search') ?>');$('#'+'mainform').submit();endif">
															<div class="input-group-btn">
																<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?= site_url('area/search') ?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-12">
													<div class="table-responsive">
														<table class="table table-bordered dataTable table-striped dataTable table-hover">
															<thead class="bg-gray disabled color-palette">
																<tr>
																	<?php if ($this->CI->cek_hak_akses('u')) : ?>
																		<th><input type="checkbox" id="checkall" /></th>
																	<?php endif; ?>
																	<th>No</th>
																	<th>Aksi</th>
																	<?php if ($o == 2) : ?>
																		<th><a href="<?= site_url("area/index/{$p}/1") ?>">Area <i class='fa fa-sort-asc fa-sm'></i></a></th>
																	<?php elseif ($o == 1) : ?>
																		<th><a href="<?= site_url("area/index/{$p}/2") ?>">Area <i class='fa fa-sort-desc fa-sm'></i></a></th>
																	<?php else : ?>
																		<th><a href="<?= site_url("area/index/{$p}/1") ?>">Area <i class='fa fa-sort fa-sm'></i></a></th>
																	<?php endif; ?>
																	<?php if ($o == 4) : ?>
																		<th nowrap><a href="<?= site_url("area/index/{$p}/3") ?>">Aktif <i class='fa fa-sort-asc fa-sm'></i></a></th>
																	<?php elseif ($o == 3) : ?>
																		<th nowrap><a href="<?= site_url("area/index/{$p}/4") ?>">Aktif <i class='fa fa-sort-desc fa-sm'></i></a></th>
																	<?php else : ?>
																		<th nowrap><a href="<?= site_url("area/index/{$p}/3") ?>">Aktif <i class='fa fa-sort fa-sm'></i></a></th>
																	<?php endif; ?>
																	<th>Jenis</th>
																	<th>Kategori</th>
																</tr>
															</thead>
															<tbody>
																<?php foreach ($main as $data) : ?>
																	<tr>
																		<?php if ($this->CI->cek_hak_akses('u')) : ?>
																			<td><input type="checkbox" name="id_cb[]" value="<?= $data['id'] ?>" /></td>
																		<?php endif; ?>
																		<td><?= $data['no'] ?></td>
																		<td nowrap>
																			<?php if ($this->CI->cek_hak_akses('u')) : ?>
																				<a href="<?= site_url("area/form/{$p}/{$o}/{$data['id']}") ?>" class="btn btn-warning btn-flat btn-sm" title="Ubah"><i class="fa fa-edit"></i></a>
																			<?php endif; ?>
																			<a href="<?= site_url("area/ajax_area_maps/{$p}/{$o}/{$data['id']}") ?>" class="btn bg-olive btn-flat btn-sm" title="Lokasi <?= $data['nama'] ?>"><i class="fa fa-map"></i></a>
																			<?php if ($this->CI->cek_hak_akses('u')) : ?>
																				<?php if ($data['enabled'] == '2') : ?>
																					<a href="<?= site_url('area/area_lock/' . $data['id']) ?>" class="btn bg-navy btn-flat btn-sm" title="Aktifkan"><i class="fa fa-lock">&nbsp;</i></a>
																				<?php elseif ($data['enabled'] == '1') : ?>
																					<a href="<?= site_url('area/area_unlock/' . $data['id']) ?>" class="btn bg-navy btn-flat btn-sm" title="Non Aktifkan"><i class="fa fa-unlock"></i></a>
																				<?php endif ?>
																			<?php endif; ?>
																			<?php if ($this->CI->cek_hak_akses('h')) : ?>
																				<a href="#" data-href="<?= site_url("area/delete/{$p}/{$o}/{$data['id']}") ?>" class="btn bg-maroon btn-flat btn-sm" title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																			<?php endif; ?>
																		</td>
																		<td width="40%"><?= $data['nama'] ?></td>
																		<td><?= $data['aktif'] ?></td>
																		<td><?= $data['jenis'] ?></td>
																		<td nowrap><?= $data['kategori'] ?></td>
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
<script type="text/javascript">
	var baseURL = "<?= base_url(); ?>";
	$(function() {
		var keyword = <?= $keyword ?>;
		$("#cari").autocomplete({
			source: keyword,
			maxShowItems: 10,
		});
	});
</script>