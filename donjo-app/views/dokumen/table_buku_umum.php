<script type="text/javascript">
	$(function() {
		var keyword = <?= $keyword ?>;
		$("#cari").autocomplete({
			source: keyword,
			maxShowItems: 10,
		});
	});
</script>
<div class="box box-info">
	<div class="box-header with-border">
		<?php if ($this->CI->cek_hak_akses('u')) : ?>
			<a href="<?= site_url("{$this->controller}/form/{$kat}") ?>" class="btn btn-social btn-flat btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Tambah Menu Baru"><i class="fa fa-plus"></i>Tambah <?= $kat_nama ?> Baru</a>
		<?php endif; ?>
		<?php if ($this->CI->cek_hak_akses('h')) : ?>
			<a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform', '<?= site_url("{$this->controller}/delete_all/{$kat}/{$p}/{$o}") ?>')" class="btn btn-social btn-flat btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
		<?php endif; ?>
		<a href="<?= site_url("{$this->controller}/dialog_cetak/{$kat}") ?>" class="btn btn-social btn-flat bg-purple btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Dokumen" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Cetak Laporan"><i class="fa fa-print"></i>Cetak</a>
		<a href="<?= site_url("{$this->controller}/dialog_excel/{$kat}") ?>" class="btn btn-social btn-flat bg-navy btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh Dokumen" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Unduh Laporan"><i class="fa fa-download"></i>Unduh</a>
		<?php if ($kat == 1) : ?>
			<a href="<?= site_url('informasi_publik/ekspor') ?>" class="btn btn-social btn-flat bg-blue btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Ekspor Data" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ekspor Data Informasi Publik"><i class="fa fa-download"></i>Ekspor</a>
		<?php endif; ?>
	</div>
	<div class="box-body">
		<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
			<form id="mainform" name="mainform" method="post">
				<input name="kategori" type="hidden" value="<?= $kat ?>">
				<div class="row">
					<div class="col-sm-9">
						<select class="form-control input-sm " name="filter" onchange="formAction('mainform','<?= site_url($this->controller . '/filter') ?>')">
							<option value="">Status</option>
							<option value="1" <?php selected($this->session->filter, 1); ?>>Aktif</option>
							<option value="2" <?php selected($this->session->filter, 2); ?>>Tidak Aktif</option>
						</select>
						<?php if ($kat == 3) : ?>
							<select class="form-control input-sm " name="jenis_peraturan" onchange="formAction('mainform','<?= site_url($this->controller . '/filter/jenis_peraturan') ?>')">
								<option value="">Jenis Peraturan</option>
								<?php foreach ($jenis_peraturan as $jenis) : ?>
									<option value="<?= $jenis ?>" <?php selected($this->session->jenis_peraturan, $jenis) ?>><?= $jenis ?></option>
								<?php endforeach; ?>
							</select>
						<?php endif; ?>
						<div class="form-group">
							<select class="form-control input-sm " name="tahun" onchange="formAction('mainform','<?= site_url($this->controller . '/filter/tahun') ?>')">
								<option value="">Tahun</option>
								<?php foreach ($list_tahun as $thn) : ?>
									<option value="<?= $thn['tahun'] ?>" <?php selected($tahun, $thn['tahun']) ?>><?= $thn['tahun'] ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="box-tools">
							<div class="input-group input-group-sm pull-right">
								<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="<?= html_escape($cari) ?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?= site_url("{$this->controller}/search") ?>');$('#'+'mainform').submit();}">
								<div class="input-group-btn">
									<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?= site_url("{$this->controller}/search") ?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<div class="table-responsive">
							<table class="table table-bordered table-striped dataTable table-hover">
								<thead class="bg-gray color-palette">
									<tr>
										<th><input type="checkbox" id="checkall" /></th>
										<th>No</th>
										<th>Aksi</th>
										<?php if ($o == 2) : ?>
											<th><a href="<?= site_url("{$this->controller}/index/{$kat}/{$p}/1") ?>">Judul <i class='fa fa-sort-asc fa-sm'></i></a></th>
										<?php elseif ($o == 1) : ?>
											<th><a href="<?= site_url("{$this->controller}/index/{$kat}/{$p}/2") ?>">Judul <i class='fa fa-sort-desc fa-sm'></i></a></th>
										<?php else : ?>
											<th><a href="<?= site_url("{$this->controller}/index/{$kat}/{$p}/1") ?>">Judul <i class='fa fa-sort fa-sm'></i></a></th>
										<?php endif; ?>
										<?php if ($kat == 1) : ?>
											<th>Kategori Info Publik</th>
											<th>Tahun</th>
										<?php elseif ($kat == 2) : ?>
											<th nowrap>No./Tgl Keputusan</th>
											<th nowrap>Uraian Singkat</th>
										<?php elseif ($kat == 3) : ?>
											<th>Jenis Peraturan</th>
											<th>No./Tgl Ditetapkan</th>
											<th>Uraian Singkat</th>
										<?php endif; ?>
										<?php if ($o == 4) : ?>
											<th nowrap><a href="<?= site_url("{$this->controller}/index/{$kat}/{$p}/3") ?>">Aktif <i class='fa fa-sort-asc fa-sm'></i></a></th>
										<?php elseif ($o == 3) : ?>
											<th nowrap><a href="<?= site_url("{$this->controller}/index/{$kat}/{$p}/4") ?>">Aktif <i class='fa fa-sort-desc fa-sm'></i></a></th>
										<?php else : ?>
											<th nowrap><a href="<?= site_url("{$this->controller}/index/{$kat}/{$p}/3") ?>">Aktif <i class='fa fa-sort fa-sm'></i></a></th>
										<?php endif; ?>
										<?php if ($o == 6) : ?>
											<th nowrap><a href="<?= site_url("{$this->controller}/index/{$kat}/{$p}/5") ?>">Dimuat Pada <i class='fa fa-sort-asc fa-sm'></i></a></th>
										<?php elseif ($o == 5) : ?>
											<th nowrap><a href="<?= site_url("{$this->controller}/index/{$kat}/{$p}/6") ?>">Dimuat Pada <i class='fa fa-sort-desc fa-sm'></i></a></th>
										<?php else : ?>
											<th nowrap><a href="<?= site_url("{$this->controller}/index/{$kat}/{$p}/5") ?>">Dimuat Pada <i class='fa fa-sort fa-sm'></i></a></th>
										<?php endif; ?>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($main as $data) : ?>
										<tr>
											<td><input type="checkbox" name="id_cb[]" value="<?= $data['id'] ?>" /></td>
											<td><?= $data['no'] ?></td>
											<td nowrap>
												<?php if ($this->CI->cek_hak_akses('u')) : ?>
													<a href="<?= site_url("{$this->controller}/form/{$kat}/{$p}/{$o}/{$data['id']}") ?>" class="btn btn-warning btn-flat btn-sm" title="Ubah"><i class="fa fa-edit"></i></a>
													<?php if ($data['enabled'] == '2') : ?>
														<a href="<?= site_url($this->controller . '/dokumen_lock/' . $kat . '/' . $data['id']) ?>" class="btn bg-navy btn-flat btn-sm" title="Aktifkan"><i class="fa fa-lock">&nbsp;</i></a>
													<?php elseif ($data['enabled'] == '1') : ?>
														<a href="<?= site_url($this->controller . '/dokumen_unlock/' . $kat . '/' . $data['id']) ?>" class="btn bg-navy btn-flat btn-sm" title="Non Aktifkan"><i class="fa fa-unlock"></i></a>
													<?php endif ?>
												<?php endif; ?>
												<?php if (! empty($data['satuan'])) : ?>
													<a href='<?= site_url("{$this->controller}/berkas/{$data['id']}/{$kat}/0") ?>' class="btn bg-purple btn-flat btn-sm" title="Unduh"><i class="fa fa-download"></i></a>
												<?php else : ?>
													<a class="btn bg-purple btn-flat btn-sm" disabled title="Unduh"><i class="fa fa-download"></i></a>
												<?php endif; ?>
												<?php if ($this->CI->cek_hak_akses('h')) : ?>
													<a href="#" data-href="<?= site_url("{$this->controller}/delete/{$kat}/{$p}/{$o}/{$data['id']}") ?>" class="btn bg-maroon btn-flat btn-sm" title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
												<?php endif; ?>
												<a href="<?= site_url("{$this->controller}/berkas/{$data['id']}/{$kat}/1") ?>" target="_blank" class="btn btn-info btn-flat btn-sm" title="Lihat Dokumen"><i class="fa fa-eye"></i></a>
											</td>
											<td width="20%"><?= $data['nama'] ?></td>
											<?php if ($kat == 1) : ?>
												<td><?= $data['kategori_info_publik'] ?></td>
												<td><?= $data['tahun'] ?></td>
											<?php elseif ($kat == 2) : ?>
												<td><?= $data['attr']['no_kep_kades'] . ' / ' . $data['attr']['tgl_kep_kades'] ?></td>
												<td><?= $data['attr']['uraian'] ?></td>
											<?php elseif ($kat == 3) : ?>
												<td><?= $data['attr']['jenis_peraturan'] ?></td>
												<td><?= strip_kosong($data['attr']['no_ditetapkan']) . ' / ' . $data['attr']['tgl_ditetapkan'] ?></td>
												<td width="20%"><?= $data['attr']['uraian'] ?></td>
											<?php endif; ?>
											<td><?= $data['aktif'] ?></td>
											<td nowrap><?= tgl_indo2($data['tgl_upload']) ?></td>
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
<?php $this->load->view('global/confirm_delete'); ?>