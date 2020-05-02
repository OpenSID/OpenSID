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
<div class="content-wrapper">
	<section class="content-header">
		<h1>Format Surat Desa</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Format Surat Desa</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<a href="<?= site_url('surat_master/form')?>" title="Tambah Format Surat" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-plus"></i> Tambah Format Surat</a>
						<a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform','<?= site_url("surat_master/delete_all/$p/$o")?>')" class="btn btn-social btn-flat	btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
									<form id="mainform" name="mainform" action="" method="post">
										<div class="row">
											<div class="col-sm-6">
												<select class="form-control input-sm " name="filter" onchange="formAction('mainform','<?= site_url('surat_master/filter')?>')">
													<option value="">Semua</option>
													<option value="1" <?php if ($filter==1 ): ?>selected<?php endif; ?>>Surat Sistem</option>
													<option value="2" <?php if ($filter==2 ): ?>selected<?php endif; ?>>Surat Desa</option>
												</select>
											</div>
											<div class="col-sm-6">
												<div class="box-tools">
													<div class="input-group input-group-sm pull-right">
														<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="<?=html_escape($cari)?>" onkeypress="if (event.keyCode == 13)):$('#'+'mainform').attr('action','<?=site_url('surat_master/search')?>');$('#'+'mainform').submit();};">
														<div class="input-group-btn">
															<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action','<?=site_url("surat_master/search")?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12">
												<div class="table-responsive">
													<table class="table table-bordered dataTable table-striped table-hover">
														<thead class="bg-gray disabled color-palette">
															<tr>
																<th><input type="checkbox" id="checkall"/></th>
																<th>No</th>
																<th >Aksi</th>
																<?php if ($o==4): ?>
																	<th><a href="<?= site_url("surat_master/index/$p/3")?>">Nama Surat <i class='fa fa-sort-asc fa-sm'></i></a></th>
																<?php elseif ($o==3): ?>
																	<th><a href="<?= site_url("surat_master/index/$p/4")?>">Nama Surat <i class='fa fa-sort-desc fa-sm'></i></a></th>
																<?php else: ?>
																	<th><a href="<?= site_url("surat_master/index/$p/3")?>">Nama Surat <i class='fa fa-sort fa-sm'></i></a></th>
																<?php endif; ?>
																<?php if ($o==6): ?>
																	<th nowrap><a href="<?= site_url("surat_master/index/$p/5")?>">Kode/Klasifikasi <i class='fa fa-sort-asc fa-sm'></i></a></th>
																<?php elseif ($o==5): ?>
																	<th nowrap><a href="<?= site_url("surat_master/index/$p/6")?>">Kode/Klasifikasi <i class='fa fa-sort-desc fa-sm'></i></a></th>
																<?php else: ?>
																	<th nowrap><a href="<?= site_url("surat_master/index/$p/5")?>">Kode/Klasifikasi <i class='fa fa-sort fa-sm'></i></a></th>
																<?php endif; ?>
																<th>URL</th>
																<th>Lampiran</th>
																<th>Template Surat</th>
															</tr>
														</thead>
														<tbody>
															<?php foreach ($main as $data): ?>
																<tr <?php if ($data['jenis']!=1): ?>style='background-color:#f8deb5 !important;'<?php endif; ?>>
																	<td><input type="checkbox" name="id_cb[]" value="<?= $data['id']?>" /></td>
																	<td><?= $data['no']?></td>
																	<td nowrap>
																		<a href="<?= site_url("surat_master/form/$p/$o/$data[id]")?>" class="btn bg-orange btn-flat btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a>
																		<?php if ($data['kunci'] == '0'): ?>
																			<a href="<?= site_url("surat_master/lock/$data[id]/$data[kunci]")?>" class="btn bg-navy btn-flat btn-sm" title="Non-Aktifkan Surat" ><i class="fa fa-unlock"></i></a>
																			<?php if ($data['favorit']==1): ?>
																				<a href="<?= site_url("surat_master/favorit/$data[id]/$data[favorit]")?>" class="btn bg-purple btn-flat btn-sm" title="Keluarkan dari Daftar Favorit" ><i class="fa fa-star"></i></a>
																			<?php else: ?>
																				<a href="<?= site_url("surat_master/favorit/$data[id]/$data[favorit]")?>" class="btn bg-purple btn-flat btn-sm"  title="Tambahkan ke Daftar Favorit" ><i class="fa fa-star-o"></i></a>
																			<?php endif; ?>
																		<?php elseif ($data['kunci'] == '1'): ?>
																			<a href="<?= site_url("surat_master/lock/$data[id]/$data[kunci]")?>" class="btn bg-navy btn-flat btn-sm" title="Aktifkan Surat"><i class="fa fa-lock"></i></a>
																		<?php endif ?>
																		<?php if ($data['jenis']!=1): ?>
																			<a href="#" data-href="<?= site_url("surat_master/delete/$p/$o/$data[id]")?>" class="btn bg-maroon btn-flat btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																		<?php endif; ?>
																	</td>
																	<td><?= $data['nama']?></td>
																	<td><?= $data['kode_surat']?></td>
																	<td><?= $data['url_surat']?></td>
																	<td><?= $data['lampiran']?></td>
																	<td nowrap>
																		<a href="<?= site_url("surat_master/kode_isian/$p/$o/$data[id]")?>" class="btn btn-social btn-flat btn-info btn-sm"  title="Kode Isian"><i class="fa fa-code"></i>Kode Isian</a>
																		<a href="<?= site_url("surat_master/form_upload/$p/$o/$data[url_surat]")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" title="Unggah Template Format Surat" data-title="Unggah Template Format Surat" class="btn btn-social btn-flat bg-orange btn-sm"><i class='fa fa-upload'></i> Unggah</a>
																		<?php $surat = SuratExport($data[url_surat]); ?>
																		<?php if ($surat != ""): ?>
																			<a href="<?= base_url($surat)?>" class="btn btn-social btn-flat bg-purple btn-sm"  title="Unduh Template Format Surat"><i class="fa fa-download"></i>Unduh </a>
																		<?php endif; ?>
																	</td>

																</tr>
															<?php endforeach;?>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</form>
									<div class="row">
										<div class="col-sm-6">
											<div class="dataTables_length">
												<form id="paging" action="<?= site_url("surat_master")?>" method="post" class="form-horizontal">
													<label>
														Tampilkan
														<select name="per_page" class="form-control input-sm" onchange="$('#paging').submit()">
															<option value="20" <?php selected($per_page,20); ?> >20</option>
															<option value="50" <?php selected($per_page,50); ?> >50</option>
															<option value="100" <?php selected($per_page,100); ?> >100</option>
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
														<li><a href="<?=site_url("surat_master/index/$paging->start_link/$o")?>" aria-label="First"><span aria-hidden="true">Awal</span></a></li>
													<?php endif; ?>
													<?php if ($paging->prev): ?>
														<li><a href="<?=site_url("surat_master/index/$paging->prev/$o")?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
													<?php endif; ?>
													<?php for ($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
														<li <?=jecho($p, $i, "class='active'")?>><a href="<?= site_url("surat_master/index/$i/$o")?>"><?= $i?></a></li>
													<?php endfor; ?>
													<?php if ($paging->next): ?>
														<li><a href="<?=site_url("surat_master/index/$paging->next/$o")?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
													<?php endif; ?>
													<?php if ($paging->end_link): ?>
														<li><a href="<?=site_url("surat_master/index/$paging->end_link/$o")?>" aria-label="Last"><span aria-hidden="true">Akhir</span></a></li>
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
			</div>
		</div>
	</section>
</div>

<?php $this->load->view('global/confirm_delete');?>
