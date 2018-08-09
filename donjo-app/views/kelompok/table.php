<!-- jQuery 3 -->
<script src="<?= base_url()?>assets/bootstrap/js/jquery.min.js"></script>
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
		<h1>Pengelolaan Kelompok</h1>
		<ol class="breadcrumb">
			<li><a href="<?=site_url('hom_desa')?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Pengelolaan Kelompok</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="mainform" name="mainform" action="" method="post">
			<div class="row">
				<div class="col-md-3">
					<div id="bantuan" class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">Kategori Kelompok</h3>
							<div class="box-tools">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
							</div>
						</div>
						<div class="box-body no-padding">
							<ul class="nav nav-pills nav-stacked">
								<?php foreach ($list_master AS $data): ?>
									<li <?php if ($filter == $data['id']):?>class="active"<?php endif;?>>
										<a href="<?= site_url("kelompok/to_master/$data[id]")?>"><?= $data['kelompok']; ?></a>
									</li>
								<?php endforeach; ?>
								<li>
									<a href="<?= site_url("kelompok_master/clear")?>"><i class="fa fa-plus"></i> Kelola Kategori Kelompok</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<div class="col-md-9">
					<div class="box box-info">
            <div class="box-header with-border">
							<a href="<?= site_url('kelompok/form')?>" title="Tambah kelompok Baru" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-plus"></i> Tambah Kelompok Baru</a>
							<a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform','<?= site_url("kelompok/delete_all/$p/$o")?>')" class="btn btn-social btn-flat	btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
							<a href="<?= site_url("kelompok/cetak")?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  target="_blank"><i class="fa fa-print "></i> Cetak</a>
							<a href="<?= site_url("kelompok/excel")?>" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  target="_blank"><i class="fa fa-download"></i> Unduh</a>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-sm-12">
									<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
										<form id="mainform" name="mainform" action="" method="post">
											<div class="row">
												<div class="col-sm-9">
													<select class="form-control input-sm" name="filter" onchange="formAction('mainform', '<?=site_url('kelompok/filter')?>')">
														<option value="">Kategori Kelompok</option>
														<?php foreach ($list_master AS $data):?>
															<option value="<?= $data['id']?>" <?php if ($filter == $data['id']):?>selected<?php endif?>><?= $data['kelompok']?></option>
														<?php endforeach;?>
													</select>
												</div>
												<div class="col-sm-3">
													<div class="input-group input-group-sm pull-right">
														<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="<?=$cari?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?=site_url("kelompok/search")?>');$('#'+'mainform').submit();}">
														<div class="input-group-btn">
															<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?=site_url("kelompok/search")?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-12">
													<div class="table-responsive">
														<table class="table table-bordered dataTable table-hover nowrap">
															<thead class="bg-gray disabled color-palette">
																<tr>
																	<th><input type="checkbox" id="checkall"/></th>
																	<th>No</th>
																	<th >Aksi</th>
																	<?php if ($o==4): ?>
																		<th><a href="<?= site_url("kelompok/index/$p/3")?>">Nama Kelompok <i class='fa fa-sort-asc fa-sm'></i></a></th>
																	<?php elseif ($o==3): ?>
																		<th><a href="<?= site_url("kelompok/index/$p/4")?>">Nama Kelompok <i class='fa fa-sort-desc fa-sm'></i></a></th>
																	<?php else: ?>
																		<th><a href="<?= site_url("kelompok/index/$p/3")?>">Nama Kelompok <i class='fa fa-sort fa-sm'></i></a></th>
																	<?php endif; ?>
																	<th>Ketua Kelompok</th>
																	<?php if ($o==6): ?>
																		<th nowrap><a href="<?= site_url("kelompok/index/$p/5")?>">Kategori Kelompok <i class='fa fa-sort-asc fa-sm'></i></a></th>
																	<?php elseif ($o==5): ?>
																		<th nowrap><a href="<?= site_url("kelompok/index/$p/6")?>">Kategori Kelompok <i class='fa fa-sort-desc fa-sm'></i></a></th>
																	<?php else: ?>
																		<th nowrap><a href="<?= site_url("kelompok/index/$p/5")?>">Kategori Kelompok <i class='fa fa-sort fa-sm'></i></a></th>
																	<?php endif; ?>
																	<th>Jumlah Anggota</th>
																</tr>
															</thead>
															<tbody>
																<?php  foreach ($main as $data): ?>
																	<tr>
																		<td><input type="checkbox" name="id_cb[]" value="<?= $data['id']?>" /></td>
																		<td><?= $data['no']?></td>
																		<td>
																			<a href="<?= site_url("kelompok/anggota/$data[id]")?>" class="btn bg-purple btn-flat btn-sm"  title="Rincian Kelompok"><i class="fa fa-list-ol"></i></a>
																			<a href="<?= site_url("kelompok/form/$p/$o/$data[id]")?>" class="btn bg-orange btn-flat btn-sm"  title="Ubah Data Kelompok"><i class='fa fa-edit'></i></a>
																			<a href="#" data-href="<?= site_url("kelompok/delete/$p/$o/$data[id]")?>" class="btn bg-maroon btn-flat btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																		</td>
																		<td width="50%" nowrap><?= $data['nama']?></td>
																		<td nowrap><?= $data['ketua']?></td>
																		<td><?= $data['master']?></td>
																		<td><?= $data['jml_anggota']?></td>
																	</tr>
																<?php  endforeach; ?>
															</tbody>
															</tbody>
														</table>
													</div>
												</div>
											</div>
										</form>
										<div class="row">
											<div class="col-sm-6">
												<div class="dataTables_length">
													<form id="paging" action="<?= site_url("kelompok")?>" method="post" class="form-horizontal">
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
															<li><a href="<?=site_url("kelompok/index/$paging->start_link/$o")?>" aria-label="First"><span aria-hidden="true">Awal</span></a></li>
														<?php endif; ?>
														<?php if ($paging->prev): ?>
															<li><a href="<?=site_url("kelompok/index/$paging->prev/$o")?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
														<?php endif; ?>
														<?php for ($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
															<li <?=jecho($p, $i, "class='active'")?>><a href="<?= site_url("kelompok/index/$i/$o")?>"><?= $i?></a></li>
														<?php endfor; ?>
														<?php if ($paging->next): ?>
															<li><a href="<?=site_url("kelompok/index/$paging->next/$o")?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
														<?php endif; ?>
														<?php if ($paging->end_link): ?>
															<li><a href="<?=site_url("kelompok/index/$paging->end_link/$o")?>" aria-label="Last"><span aria-hidden="true">Akhir</span></a></li>
														<?php endif; ?>
													</ul>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class='modal fade' id='confirm-delete' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
								<div class='modal-dialog'>
									<div class='modal-content'>
										<div class='modal-header'>
											<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
											<h4 class='modal-title' id='myModalLabel'><i class='fa fa-text-width text-yellow'></i> Konfirmasi</h4>
										</div>
										<div class='modal-body btn-info'>
											Apakah Anda yakin ingin menghapus data ini?
										</div>
										<div class='modal-footer'>
											<button type="button" class="btn btn-social btn-flat btn-warning btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
											<a class='btn-ok'>
												<button type="button" class="btn btn-social btn-flat btn-danger btn-sm" id="ok-delete"><i class='fa fa-trash-o'></i> Hapus</button>
											</a>
										</div>
									</div>
								</div>
							</div>
							<div class='modal fade' id='confirm-status' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
								<div class='modal-dialog'>
									<div class='modal-content'>
										<div class='modal-header'>
											<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
											<h4 class='modal-title' id='myModalLabel'><i class='fa fa-text-width text-yellow'></i> Konfirmasi</h4>
										</div>
										<div class='modal-body btn-info'>
											Apakah Anda yakin ingin mengembalikan status data penduduk ini?
										</div>
										<div class='modal-footer'>
											<button type="button" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
											<a class='btn-ok'>
												<button type="button" class="btn btn-social btn-flat btn-info btn-sm" id="ok-status"><i class='fa fa-check'></i> Ya</button>
											</a>
										</div>
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

