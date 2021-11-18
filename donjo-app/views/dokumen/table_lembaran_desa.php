<script type="text/javascript">
	var baseURL = "<?= base_url(); ?>";
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
<div class="box box-info">
	<div class="box-header with-border">
		<a href="<?= site_url("{$this->controller}/dialog_daftar/cetak")?>" class="btn btn-social btn-flat bg-purple btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Cetak Laporan" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Cetak Laporan">
			<i class="fa fa-print"></i>Cetak
		</a>
		<a href="<?= site_url("{$this->controller}/dialog_daftar/unduh")?>" class="btn btn-social btn-flat bg-navy btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Unduh Laporan" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Unduh Laporan">
			<i class="fa fa-download"></i>Unduh
		</a>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-12">
				<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
					<form id="mainform" name="mainform" method="post">
						<div class="row">
							<div class="col-sm-9">
								<select class="form-control input-sm " name="filter" onchange="formAction('mainform','<?= site_url($this->controller . '/filter')?>')">
									<option value="">Status</option>
									<option value="1" <?php selected($this->session->filter, 1); ?>>Aktif</option>
									<option value="2" <?php selected($this->session->filter, 2); ?>>Tidak Aktif</option>
								</select>
								<select class="form-control input-sm " name="jenis_peraturan" onchange="formAction('mainform','<?= site_url($this->controller . '/filter/jenis_peraturan')?>')">
									<option value="">Jenis Peraturan</option>
									<?php foreach ($jenis_peraturan as $jenis): ?>
										<option value="<?= $jenis?>" <?php selected($this->session->jenis_peraturan, $jenis) ?>><?= $jenis?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-sm-3">
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
										<thead class="bg-gray color-palette">
											<tr>
												<th>No</th>
												<th>Aksi</th>
												<?php if ($o == 2): ?>
													<th><a href="<?= site_url("{$this->controller}/index/{$p}/1")?>">Judul <i class='fa fa-sort-asc fa-sm'></i></a></th>
												<?php elseif ($o == 1): ?>
													<th><a href="<?= site_url("{$this->controller}/index/{$p}/2")?>">Judul <i class='fa fa-sort-desc fa-sm'></i></a></th>
												<?php else: ?>
													<th><a href="<?= site_url("{$this->controller}/index/{$p}/1")?>">Judul <i class='fa fa-sort fa-sm'></i></a></th>
												<?php endif; ?>
												<th>Jenis Peraturan</th>
												<th>No./Tgl Ditetapkan</th>
												<th>Uraian Singkat</th>
												<?php if ($o == 4): ?>
													<th nowrap><a href="<?= site_url("{$this->controller}/index/{$p}/3")?>">Aktif <i class='fa fa-sort-asc fa-sm'></i></a></th>
												<?php elseif ($o == 3): ?>
													<th nowrap><a href="<?= site_url("{$this->controller}/index/{$p}/4")?>">Aktif <i class='fa fa-sort-desc fa-sm'></i></a></th>
												<?php else: ?>
													<th nowrap><a href="<?= site_url("{$this->controller}/index/{$p}/3")?>">Aktif <i class='fa fa-sort fa-sm'></i></a></th>
												<?php endif; ?>
												<?php if ($o == 6): ?>
													<th nowrap><a href="<?= site_url("{$this->controller}/index/{$p}/5")?>">Dimuat Pada <i class='fa fa-sort-asc fa-sm'></i></a></th>
												<?php elseif ($o == 5): ?>
													<th nowrap><a href="<?= site_url("{$this->controller}/index/{$p}/6")?>">Dimuat Pada <i class='fa fa-sort-desc fa-sm'></i></a></th>
												<?php else: ?>
													<th nowrap><a href="<?= site_url("{$this->controller}/index/{$p}/5")?>">Dimuat Pada <i class='fa fa-sort fa-sm'></i></a></th>
												<?php endif; ?>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($main as $data): ?>
												<tr>
													<td><?=$data['no']?></td>
													<td nowrap>
														<?php if ($this->CI->cek_hak_akses('u')): ?>
															<a href="<?= site_url("{$this->controller}/form/{$p}/{$o}/{$data['id']}")?>" class="btn btn-warning btn-flat btn-sm"  title="Ubah"><i class="fa fa-edit"></i></a>
														<?php endif; ?>
														<?php if ($data['enabled'] == '2'): ?>
															<a href="<?= site_url("{$this->controller}/lock/{$data['id']}/1"); ?>" class="btn bg-navy btn-flat btn-sm"  title="Aktifkan"><i class="fa fa-lock">&nbsp;</i></a>
														<?php elseif ($data['enabled'] == '1'): ?>
															<a href="<?= site_url("{$this->controller}/lock/{$data['id']}/2"); ?>" class="btn bg-navy btn-flat btn-sm"  title="Non Aktifkan"><i class="fa fa-unlock"></i></a>
														<?php endif ?>
														<?php if (! empty($data['satuan'])): ?>
															<a href='<?= site_url("{$this->controller}/unduh_berkas/{$data['id']}") ?>' class="btn bg-purple btn-flat btn-sm" title="Unduh"><i class="fa fa-download"></i></a>
														<?php else: ?>
															<a class="btn bg-purple btn-flat btn-sm" disabled title="Unduh"><i class="fa fa-download"></i></a>
														<?php endif; ?>
													</td>
													<td width="20%"><?= $data['nama']?></td>
													<td><?= $data['attr']['jenis_peraturan']?></td>
													<td><?= strip_kosong($data['attr']['no_ditetapkan']) . ' / ' . $data['attr']['tgl_ditetapkan']?></td>
													<td width="20%"><?= $data['attr']['uraian']?></td>
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
								<form id="paging" action="<?= site_url($this->controller . '/index/')?>" method="post" class="form-horizontal">
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
										<li><a href="<?= site_url("{$this->controller}/index/{$paging->start_link}/{$o}")?>" aria-label="First"><span aria-hidden="true">Awal</span></a></li>
									<?php endif; ?>
									<?php if ($paging->prev): ?>
										<li><a href="<?= site_url("{$this->controller}/index/{$paging->prev}/{$o}")?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
									<?php endif; ?>
									<?php for ($i = $paging->start_link; $i <= $paging->end_link; $i++): ?>
										<li <?=jecho($p, $i, "class='active'")?>><a href="<?= site_url("{$this->controller}/index/{$i}/{$o}")?>"><?= $i?></a></li>
									<?php endfor; ?>
									<?php if ($paging->next): ?>
										<li><a href="<?= site_url("{$this->controller}/index/{$paging->next}/{$o}")?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
									<?php endif; ?>
									<?php if ($paging->end_link): ?>
										<li><a href="<?= site_url("{$this->controller}/index/{$paging->end_link}/{$o}")?>" aria-label="Last"><span aria-hidden="true">Akhir</span></a></li>
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
