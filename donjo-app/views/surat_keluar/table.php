<script>
	$(function() {
		var keyword = <?= $keyword?> ;
		$( "#cari" ).autocomplete({
			source: keyword,
			maxShowItems: 10,
		});
	});
</script>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Surat Keluar</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid'); ?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Surat Keluar</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="box box-info">
			<div class="box-header with-border">
				<a href='<?= site_url("{$this->controller}/form")?>' title="Tambah Surat Keluar Baru" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-plus"></i> Tambah Surat Keluar Baru</a>
				<a href="#confirm-delete" title="Hapus Data" title="Hapus Data Terpilih" onclick="deleteAllBox('mainform','<?= site_url("{$this->controller}/delete_all/$p/$o")?>')" class="btn btn-social btn-flat	btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
				<a href="<?= site_url("{$this->controller}/dialog_cetak/$o")?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Agenda Surat Keluar" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Cetak Agenda Surat Keluar"><i class="fa fa-print "></i> Cetak</a>
				<a href="<?= site_url("{$this->controller}/dialog_unduh/$o")?>" title="Unduh Agenda Surat Keluar" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh Agenda Surat Keluar" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Unduh Agenda Surat Keluar"><i class="fa fa-download"></i> Unduh</a>
			</div>
			<div class="box-body">
				<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
					<form id="mainform" name="mainform" action="" method="post">
						<div class="row">
							<div class="col-sm-6">
								<select class="form-control input-sm " name="filter" onchange="formAction('mainform','<?= site_url($this->controller.'/filter')?>')">
									<option value="">Tahun</option>
									<?php foreach ($tahun_surat as $tahun): ?>
										<option value="<?= $tahun['tahun']?>" <?= selected($filter, $tahun['tahun']) ?>><?= $tahun['tahun']?></option>
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
						<div class="table-responsive">
							<table class="table table-bordered table-striped dataTable table-hover tabel-daftar">
								<thead class="bg-gray disabled color-palette">
									<tr>
										<th><input type="checkbox" id="checkall"/></th>
										<?php if ($o==2): ?>
											<th><a href="<?= site_url("{$this->controller}/index/$p/1")?>">No. Urut <i class='fa fa-sort-asc fa-sm'></i></a></th>
										<?php elseif ($o==1): ?>
											<th><a href="<?= site_url("{$this->controller}/index/$p/2")?>">No. Urut <i class='fa fa-sort-desc fa-sm'></i></a></th>
										<?php else: ?>
											<th><a href="<?= site_url("{$this->controller}/index/$p/1")?>">No. Urut <i class='fa fa-sort fa-sm'></i></a></th>
										<?php endif; ?>
										<th>Aksi</th>
										<th>Nomor Surat</th>
										<?php if ($o==4): ?>
											<th><a href="<?= site_url("{$this->controller}/index/$p/3")?>">Tanggal Surat <i class='fa fa-sort-asc fa-sm'></i></a></th>
										<?php elseif ($o==3): ?>
											<th><a href="<?= site_url("{$this->controller}/index/$p/4")?>">Tanggal Surat <i class='fa fa-sort-desc fa-sm'></i></a></th>
										<?php else: ?>
											<th><a href="<?= site_url("{$this->controller}/index/$p/3")?>">Tanggal Surat <i class='fa fa-sort fa-sm'></i></a></th>
										<?php endif; ?>
										<?php if ($o==6): ?>
											<th><a href="<?= site_url("{$this->controller}/index/$p/5")?>">Ditujukan Kepada <i class='fa fa-sort-asc fa-sm'></i></a></th>
										<?php elseif ($o==5): ?>
											<th><a href="<?= site_url("{$this->controller}/index/$p/6")?>">Ditujukan Kepada <i class='fa fa-sort-desc fa-sm'></i></a></th>
										<?php else: ?>
											<th><a href="<?= site_url("{$this->controller}/index/$p/5")?>">Ditujukan Kepada <i class='fa fa-sort fa-sm'></i></a></th>
										<?php endif; ?>
										<th>Isi Singkat</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($main as $data): ?>
										<tr>
											<td class="padat"><input type="checkbox" name="id_cb[]" value="<?= $data['id']?>" /></td>
											<td class="padat"><?= $data['nomor_urut']?></td>
											<td class="aksi">
												<a href="<?= site_url("{$this->controller}/form/$p/$o/$data[id]")?>" class="btn bg-orange btn-flat btn-sm" title="Ubah Data"><i class="fa fa-edit"></i></a>
												<?php if ($data['berkas_scan']): ?>
													<a href='<?= site_url("{$this->controller}/unduh_berkas_scan/$data[id]")?>' class="btn bg-purple btn-flat btn-sm" title="Unduh Berkas Surat" target="_blank"><i class="fa fa-download"></i></a>
												<?php endif; ?>
												<a href="#" data-href="<?= site_url("{$this->controller}/delete/$p/$o/$data[id]")?>" class="btn bg-maroon btn-flat btn-sm" title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
											</td>
											<td class="padat"><?= $data['nomor_surat']?></td>
											<td nowrap><?= tgl_indo_out($data['tanggal_surat'])?></td>
											<td nowrap><?= $data['tujuan']?></td>
											<td width="30%"><?= $data['isi_singkat']?></td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					</form>
					<div class="row">
						<div class="col-sm-6">
							<div class="dataTables_length">
								<form id="paging" action="<?= site_url("surat_keluar")?>" method="post" class="form-horizontal">
								<label>
									Tampilkan
									<select name="per_page" class="form-control input-sm" onchange="$('#paging').submit()">
										<option value="20" <?= selected($per_page, 20); ?>>20</option>
										<option value="50" <?= selected($per_page, 50); ?>>50</option>
										<option value="100" <?= selected($per_page, 100); ?>>100</option>
									</select>
									Dari
									<strong><?= $paging->num_rows?></strong>
									Total Data
								</label>
							</form>
						</div>
						<div class="col-sm-6">
							<div class="dataTables_paginate paging_simple_numbers">
								<ul class="pagination">
									<?php if ($paging->start_link): ?>
										<li><a href="<?=site_url("{$this->controller}/index/$paging->start_link/$o")?>" aria-label="First"><span aria-hidden="true">Awal</span></a></li>
									<?php endif; ?>
									<?php if ($paging->prev): ?>
										<li><a href="<?=site_url("{$this->controller}/index/$paging->prev/$o")?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
									<?php endif; ?>
									<?php for ($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
										<li <?=jecho($p, $i, "class='active'")?>><a href="<?= site_url("{$this->controller}/index/$i/$o")?>"><?= $i?></a></li>
									<?php endfor; ?>
									<?php if ($paging->next): ?>
										<li><a href="<?=site_url("{$this->controller}/index/$paging->next/$o")?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
									<?php endif; ?>
									<?php if ($paging->end_link): ?>
										<li><a href="<?=site_url("{$this->controller}/index/$paging->end_link/$o")?>" aria-label="Last"><span aria-hidden="true">Akhir</span></a></li>
									<?php endif; ?>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<?php $this->load->view('global/confirm_delete');?>
