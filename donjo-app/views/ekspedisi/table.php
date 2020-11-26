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
<div class="card card-outline card-info">
  <div class="card-header with-border">
		<a href="<?= site_url("{$this->controller}/dialog/cetak/$o")?>" class="btn btn-flat bg-purple btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left" title="Cetak Buku Ekspedisi" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Cetak Buku Ekspedisi"><i class="fa fa-print "></i> Cetak</a>
		<a href="<?= site_url("{$this->controller}/dialog/unduh/$o")?>" title="Unduh Buku Ekspedisi" class="btn btn-flat bg-navy btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left" title="Unduh Buku Ekspedisi" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Unduh Buku Ekspedisi"><i class="fa fa-download"></i> Unduh</a>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-sm-12">
				<div class="dataTables_wrapper dt-bootstrap no-footer">
					<form class="form-inline" id="mainform" name="mainform" action="" method="post">
						<div class="container-fluid">
							<div class="row mb-2">
								<div class="col-sm-6">
									<select class="form-control form-control-sm " name="filter" onchange="formAction('mainform','<?= site_url($this->controller.'/filter')?>')">
										<option value="">Tahun</option>
										<?php foreach ($tahun_surat as $tahun): ?>
											<option value="<?= $tahun['tahun']?>" <?php selected($filter, $tahun['tahun']) ?>><?= $tahun['tahun']?></option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="col-sm-6">
									<div class="card-tools">
										<div class="input-group input-group-sm pull-right">
											<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="<?=html_escape($cari)?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?=site_url("{$this->controller}/search")?>');$('#'+'mainform').submit();}">
											<div class="input-group-btn">
												<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?=site_url("{$this->controller}/search")?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="table-responsive">
							<table class="table table-bordered dataTable table-hover">
								<thead class="bg-gray color-palette">
									<tr>
										<th class="nostretch">No.</th>
										<th class="nostretch">Aksi</th>
										<?php if ($o==8): ?>
											<th><a href="<?= site_url("{$this->controller}/index/$p/7")?>">Tgl Pengiriman <i class='fa fa-sort-asc fa-sm'></i></a></th>
										<?php elseif ($o==7): ?>
											<th><a href="<?= site_url("{$this->controller}/index/$p/8")?>">Tgl Pengiriman <i class='fa fa-sort-desc fa-sm'></i></a></th>
										<?php else: ?>
											<th><a href="<?= site_url("{$this->controller}/index/$p/7")?>">Tgl Pengiriman <i class='fa fa-sort fa-sm'></i></a></th>
										<?php endif; ?>
										<th class="nostretch">No. Surat</th>
										<th>Tgl Surat</th>
										<th>Isi Singkat</th>
										<?php if ($o==6): ?>
											<th nowrap><a href="<?= site_url("{$this->controller}/index/$p/5")?>">Ditujukan Kepada <i class='fa fa-sort-asc fa-sm'></i></a></th>
										<?php elseif ($o==5): ?>
											<th nowrap><a href="<?= site_url("{$this->controller}/index/$p/6")?>">Ditujukan Kepada <i class='fa fa-sort-desc fa-sm'></i></a></th>
										<?php else: ?>
											<th nowrap><a href="<?= site_url("{$this->controller}/index/$p/5")?>">Ditujukan Kepada <i class='fa fa-sort fa-sm'></i></a></th>
										<?php endif; ?>
										<th>Keterangan</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($main as $indeks => $data): ?>
										<tr>
											<td class="nostretch"><?= $indeks + 1?></td>
											<td class="nostretch">
												<a href="<?= site_url("{$this->controller}/form/$p/$o/$data[id]")?>" class="btn bg-orange btn-flat btn-xs"  title="Ubah Data"><i class="fa fa-edit"></i></a>
												<?php if ($data['tanda_terima']): ?>
													<a href='<?= site_url("{$this->controller}/unduh_tanda_terima/$data[id]")?>' class="btn bg-purple btn-flat btn-xs" title="Unduh Tanda Terima" target="_blank"><i class="fa fa-download"></i></a>
												<?php endif; ?>
												<a href="<?= site_url("{$this->controller}/bukan_ekspedisi/$p/$o/$data[id]")?>" class="btn bg-olive btn-flat btn-xs" title="Keluarkan dari Buku Ekspedisi"><i class="fa fa-undo"></i></a>
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
					</form>
					<div class="row text-sm">
						<div class="col-sm-6">
							<div class="dataTables_length">
								<form id="paging" action="<?= site_url("ekspedisi")?>" method="post" class="form-horizontal">
									<label>
										Tampilkan
										<select name="per_page" class="form-control form-control-sm" onchange="$('#paging').submit()">
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
										<li class="page-item disabled"><a class="page-link" href="<?=site_url("{$this->controller}/index/$paging->start_link/$o")?>" aria-label="First"><span aria-hidden="true">Awal</span></a></li>
									<?php endif; ?>
									<?php if ($paging->prev): ?>
										<li class="page-item"><a class="page-link" href="<?=site_url("{$this->controller}/index/$paging->prev/$o")?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
									<?php endif; ?>
									<?php for ($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
										<li <?=jecho($p, $i, "class='page-item active'")?>><a class="page-link" href="<?= site_url("{$this->controller}/index/$i/$o")?>"><?= $i?></a></li>
									<?php endfor; ?>
									<?php if ($paging->next): ?>
										<li class="page-item"><a class="page-link" href="<?=site_url("{$this->controller}/index/$paging->next/$o")?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
									<?php endif; ?>
									<?php if ($paging->end_link): ?>
										<li class="page-item disabled"><a class="page-link" href="<?=site_url("{$this->controller}/index/$paging->end_link/$o")?>" aria-label="Last"><span aria-hidden="true">Akhir</span></a></li>
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
