<script>
	$(function() {
		var keyword = <?= $keyword?> ;
		$( "#cari" ).autocomplete({
			source: keyword,
			maxShowItems: 10,
		});
	});
</script>
<div class="box box-info">
  <div class="box-header with-border">
		<a href="<?= site_url("{$this->controller}/dialog/cetak/{$o}")?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Buku Ekspedisi" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Cetak Buku Ekspedisi"><i class="fa fa-print "></i> Cetak</a>
		<a href="<?= site_url("{$this->controller}/dialog/unduh/{$o}")?>" title="Unduh Buku Ekspedisi" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh Buku Ekspedisi" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Unduh Buku Ekspedisi"><i class="fa fa-download"></i> Unduh</a>
	</div>
	<div class="box-body">
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
										<th class="nostretch">No.</th>
										<th class="nostretch">Aksi</th>
										<?php if ($o == 8): ?>
											<th><a href="<?= site_url("{$this->controller}/index/{$p}/7")?>">Tgl Pengiriman <i class='fa fa-sort-asc fa-sm'></i></a></th>
										<?php elseif ($o == 7): ?>
											<th><a href="<?= site_url("{$this->controller}/index/{$p}/8")?>">Tgl Pengiriman <i class='fa fa-sort-desc fa-sm'></i></a></th>
										<?php else: ?>
											<th><a href="<?= site_url("{$this->controller}/index/{$p}/7")?>">Tgl Pengiriman <i class='fa fa-sort fa-sm'></i></a></th>
										<?php endif; ?>
										<th class="nostretch">No. Surat</th>
										<th>Tgl Surat</th>
										<th>Isi Singkat</th>
										<?php if ($o == 6): ?>
											<th nowrap><a href="<?= site_url("{$this->controller}/index/{$p}/5")?>">Ditujukan Kepada <i class='fa fa-sort-asc fa-sm'></i></a></th>
										<?php elseif ($o == 5): ?>
											<th nowrap><a href="<?= site_url("{$this->controller}/index/{$p}/6")?>">Ditujukan Kepada <i class='fa fa-sort-desc fa-sm'></i></a></th>
										<?php else: ?>
											<th nowrap><a href="<?= site_url("{$this->controller}/index/{$p}/5")?>">Ditujukan Kepada <i class='fa fa-sort fa-sm'></i></a></th>
										<?php endif; ?>
										<th>Keterangan</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($main as $indeks => $data): ?>
										<tr>
											<td class="nostretch"><?= $indeks + 1?></td>
											<td class="nostretch">
												<?php if ($this->CI->cek_hak_akses('u')): ?>
													<a href="<?= site_url("{$this->controller}/form/{$p}/{$o}/{$data['id']}")?>" class="btn bg-orange btn-flat btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a>
												<?php endif; ?>
												<?php if ($data['tanda_terima']): ?>
													<a href='<?= site_url("{$this->controller}/unduh_tanda_terima/{$data['id']}")?>' class="btn bg-purple btn-flat btn-sm" title="Unduh Tanda Terima" target="_blank"><i class="fa fa-download"></i></a>
												<?php endif; ?>
												<a href="<?= site_url("{$this->controller}/bukan_ekspedisi/{$p}/{$o}/{$data['id']}")?>" class="btn bg-olive btn-flat btn-sm" title="Keluarkan dari Buku Ekspedisi"><i class="fa fa-undo"></i></a>
											</td>
											<td><?= tgl_indo_out($data['tanggal_pengiriman'])?></td>
											<td class="nostretch"><?= $data['nomor_surat']?></td>
											<td nowrap><?= tgl_indo_out($data['tanggal_surat'])?></td>
											<td><?= $data['isi_singkat']?></td>
											<td nowrap><?= $data['tujuan']?></td>
											<td><?= $data['keterangan']?></td>
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
						<form id="paging" action="<?= site_url('ekspedisi')?>" method="post" class="form-horizontal">
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
