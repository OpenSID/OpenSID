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
<div class="box box-info">
  <div class="box-header with-border">
		<?php if ($this->CI->cek_hak_akses('u')): ?>
			<a href='<?= site_url("{$this->controller}/form")?>' title="Tambah Surat Keluar Baru" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-plus"></i> Tambah Surat Keluar Baru</a>
		<?php endif; ?>
		<?php if ($this->CI->cek_hak_akses('h')): ?>
			<a href="#confirm-delete" title="Hapus Data" title="Hapus Data Terpilih" onclick="deleteAllBox('mainform','<?= site_url("{$this->controller}/delete_all/{$p}/{$o}")?>')" class="btn btn-social btn-flat	btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
		<?php endif; ?>
		<a href="<?= site_url("{$this->controller}/dialog_cetak/{$o}")?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Agenda Surat Keluar" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Cetak Agenda Surat Keluar"><i class="fa fa-print "></i> Cetak</a>
		<a href="<?= site_url("{$this->controller}/dialog_unduh/{$o}")?>" title="Unduh Agenda Surat Keluar" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh Agenda Surat Keluar" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Unduh Agenda Surat Keluar"><i class="fa fa-download"></i> Unduh</a>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-12">
				<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
					<form id="mainform" name="mainform" method="post">
						<div class="row">
							<div class="col-sm-6">
								<select class="form-control input-sm " name="filter" onchange="formAction('mainform','<?= site_url($this->controller . '/filter')?>')">
									<option value="">Tahun</option>
									<?php foreach ($tahun_surat as $tahun): ?>
										<option value="<?= $tahun['tahun']?>" <?php selected($filter, $tahun['tahun']) ?>><?= $tahun['tahun']?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-sm-6">
								<div class="box-tools">
									<div class="input-group input-group-sm pull-right">
										<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="<?=html_escape($cari)?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?=site_url("{$this->controller}/search")?>');$('#'+'mainform').submit();}">
										<div class="input-group-btn">
											<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?=site_url("{$this->controller}/search")?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<div class="table-responsive">
									<table class="table table-bordered dataTable table-hover">
										<thead class="bg-gray color-palette">
											<tr>
												<th class="nostretch"><input type="checkbox" id="checkall"/></th>
												<?php if ($o == 2): ?>
													<th class="nostretch"><a href="<?= site_url("{$this->controller}/index/{$p}/1")?>">No. Urut <i class='fa fa-sort-asc fa-sm'></i></a></th>
												<?php elseif ($o == 1): ?>
													<th class="nostretch"><a href="<?= site_url("{$this->controller}/index/{$p}/2")?>">No. Urut <i class='fa fa-sort-desc fa-sm'></i></a></th>
												<?php else: ?>
													<th class="nostretch"><a href="<?= site_url("{$this->controller}/index/{$p}/1")?>">No. Urut <i class='fa fa-sort fa-sm'></i></a></th>
												<?php endif; ?>
												<th class="nostretch">Aksi</th>
												<th class="nostretch">Nomor Surat</th>
												<?php if ($o == 4): ?>
													<th><a href="<?= site_url("{$this->controller}/index/{$p}/3")?>">Tanggal Surat <i class='fa fa-sort-asc fa-sm'></i></a></th>
												<?php elseif ($o == 3): ?>
													<th><a href="<?= site_url("{$this->controller}/index/{$p}/4")?>">Tanggal Surat <i class='fa fa-sort-desc fa-sm'></i></a></th>
												<?php else: ?>
													<th><a href="<?= site_url("{$this->controller}/index/{$p}/3")?>">Tanggal Surat <i class='fa fa-sort fa-sm'></i></a></th>
												<?php endif; ?>
												<?php if ($o == 6): ?>
													<th nowrap><a href="<?= site_url("{$this->controller}/index/{$p}/5")?>">Ditujukan Kepada <i class='fa fa-sort-asc fa-sm'></i></a></th>
												<?php elseif ($o == 5): ?>
													<th nowrap><a href="<?= site_url("{$this->controller}/index/{$p}/6")?>">Ditujukan Kepada <i class='fa fa-sort-desc fa-sm'></i></a></th>
												<?php else: ?>
													<th nowrap><a href="<?= site_url("{$this->controller}/index/{$p}/5")?>">Ditujukan Kepada <i class='fa fa-sort fa-sm'></i></a></th>
												<?php endif; ?>
												<th width="30%">Isi Singkat</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($main as $data): ?>
												<tr>
													<td><input type="checkbox" name="id_cb[]" value="<?= $data['id']?>" /></td>
													<td class="nostretch"><?= $data['nomor_urut']?></td>
													<td class="nostretch">
														<?php if ($this->CI->cek_hak_akses('u')): ?>
															<a href="<?= site_url("{$this->controller}/form/{$p}/{$o}/{$data['id']}")?>" class="btn bg-orange btn-flat btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a>
														<?php endif; ?>
														<?php if ($data['berkas_scan']): ?>
															<a href='<?= site_url("{$this->controller}/berkas/{$data['id']}/0")?>' class="btn bg-purple btn-flat btn-sm" title="Unduh Berkas Surat" target="_blank"><i class="fa fa-download"></i></a>
														<?php endif; ?>
														<?php if ($this->CI->cek_hak_akses('u')): ?>
															<?php if ($data['ekspedisi']): ?>
																<a href='<?= site_url('ekspedisi/index/')?>' class="btn bg-info btn-flat btn-sm" title="Buku Ekspedisi"><i class="fa fa-envelope-open"></i></a>
															<?php else: ?>
																<a href='<?= site_url("{$this->controller}/untuk_ekspedisi/{$p}/{$o}/{$data['id']}")?>' class="btn bg-blue btn-flat btn-sm" title="Tambahkan ke Buku Ekspedisi"><i class="fa fa-envelope-open"></i></a>
															<?php endif; ?>
														<?php endif; ?>
														<?php if ($this->CI->cek_hak_akses('h')): ?>
															<a href="#" data-href="<?= site_url("{$this->controller}/delete/{$p}/{$o}/{$data['id']}")?>" class="btn bg-maroon btn-flat btn-sm" title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
														<?php endif; ?>
														<a href="<?= site_url("{$this->controller}/berkas/{$data['id']}/1")?>" target="_blank" class="btn btn-info btn-flat btn-sm"  title="Lihat Berkas Surat"><i class="fa fa-eye"></i></a>
													</td>
													<td class="nostretch"><?= $data['nomor_surat']?></td>
													<td nowrap><?= tgl_indo_out($data['tanggal_surat'])?></td>
													<td nowrap><?= $data['tujuan']?></td>
													<td><?= $data['isi_singkat']?></td>
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
							<form id="paging" action="<?= site_url('surat_keluar')?>" method="post" class="form-horizontal">
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
								<li><a href="<?=site_url("{$this->controller}/index/{$paging->start_link}/{$o}")?>" aria-label="First"><span aria-hidden="true">Awal</span></a></li>
							  <?php endif; ?>
							  <?php if ($paging->prev): ?>
								<li><a href="<?=site_url("{$this->controller}/index/{$paging->prev}/{$o}")?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
							  <?php endif; ?>
							  <?php for ($i = $paging->start_link; $i <= $paging->end_link; $i++): ?>
								<li <?=jecho($p, $i, "class='active'")?>><a href="<?= site_url("{$this->controller}/index/{$i}/{$o}")?>"><?= $i?></a></li>
							  <?php endfor; ?>
							  <?php if ($paging->next): ?>
								<li><a href="<?=site_url("{$this->controller}/index/{$paging->next}/{$o}")?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
							  <?php endif; ?>
							  <?php if ($paging->end_link): ?>
								<li><a href="<?=site_url("{$this->controller}/index/{$paging->end_link}/{$o}")?>" aria-label="Last"><span aria-hidden="true">Akhir</span></a></li>
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
<?php $this->load->view('global/confirm_delete'); ?>