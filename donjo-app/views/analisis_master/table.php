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
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0 text-dark">
						Master Analisis Data Potensi/Sumber Daya
					</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?= site_url('hom_sid'); ?>"><i class="fa fa-home"></i> Home</a></li>
						<li class="breadcrumb-item active">Master Analisis</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
	<section class="content" id="maincontent">
		<div class="card card-outline card-info">
			<div class="card-header with-border">
				<a href="<?= site_url('analisis_master/form')?>" class="btn btn-flat btn-success btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left" title="Tambah Analisis Baru"><i class="fa fa-plus"></i> Tambah Analisis Baru</a>
				<a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform','<?= site_url("analisis_master/delete_all/$p/$o")?>')" class="btn btn-flat	btn-danger btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
				<a href="<?= site_url('analisis_master/import_analisis')?>" class="btn btn-flat bg-purple btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left" title="Impor Analisis" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Impor Analisis"><i class="fa fa-upload"></i> Impor Analisis</a>
				<a href="<?= site_url("{$this->controller}/clear") ?>" class="btn btn-flat bg-purple btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left"><i class="fa fa-refresh"></i>Bersihkan Filter</a>
			</div>
			<div class="card-body">
				<div class="dataTables_wrapper dt-bootstrap no-footer">
					<form class="form-inline" id="mainform" name="mainform" action="" method="post">
						<div class="container-fluid">
							<div class="row mb-2">
								<div class="col-sm-6">
									<select class="form-control form-control-sm " name="filter" onchange="formAction('mainform','<?= site_url('analisis_master/filter')?>')">
										<option value="">Pilih Subjek</option>
										<?php foreach ($list_subjek AS $data): ?>
											<option value="<?= $data['id']?>" <?php if ($filter == $data['id']): ?>selected<?php endif ?>><?= $data['subjek']?></option>
										<?php endforeach;?>
									</select>
									<select class="form-control form-control-sm " name="state" onchange="formAction('mainform', '<?= site_url('analisis_master/state')?>')">
										<option value="">Pilih Status</option>
										<option value="1" <?php if ($state == 1): ?>selected<?php endif ?>>Aktif</option>
										<option value="2" <?php if ($state == 2): ?>selected<?php endif ?>>Tidak Aktif</option>
									</select>
								</div>
								<div class="col-sm-6">
									<div class="card-tools">
										<div class="input-group input-group-sm pull-right">
											<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="<?=html_escape($cari)?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action','<?= site_url('analisis_master/search')?>');$('#'+'mainform').submit();};">
											<div class="input-group-btn">
												<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action','<?= site_url("analisis_master/search")?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="table-responsive">
							<table class="table table-bordered table-striped dataTable table-hover">
								<thead class="bg-gray disabled color-palette">
									<tr>
										<th><input type="checkbox" id="checkall"/></th>
										<th>No</th>
										<th >Aksi</th>
										<?php if ($o==4): ?>
											<th><a href="<?= site_url("analisis_master/index/$p/3")?>">Nama <i class='fa fa-sort-asc fa-sm'></i></a></th>
										<?php elseif ($o==3): ?>
											<th><a href="<?= site_url("analisis_master/index/$p/4")?>">Nama <i class='fa fa-sort-desc fa-sm'></i></a></th>
										<?php else: ?>
											<th><a href="<?= site_url("analisis_master/index/$p/3")?>">Nama <i class='fa fa-sort fa-sm'></i></a></th>
										<?php endif; ?>
										<?php if ($o==6): ?>
											<th nowrap><a href="<?= site_url("analisis_master/index/$p/5")?>">Subjek/Unit Analisis <i class='fa fa-sort-asc fa-sm'></i></a></th>
										<?php elseif ($o==5): ?>
											<th nowrap><a href="<?= site_url("analisis_master/index/$p/6")?>">Subjek/Unit Analisis <i class='fa fa-sort-desc fa-sm'></i></a></th>
										<?php else: ?>
											<th nowrap><a href="<?= site_url("analisis_master/index/$p/5")?>">Subjek/Unit Analisis <i class='fa fa-sort fa-sm'></i></a></th>
										<?php endif; ?>
										<?php if ($o==2): ?>
											<th nowrap><a href="<?= site_url("analisis_master/index/$p/1")?>">Status <i class='fa fa-sort-asc fa-sm'></i></a></th>
										<?php elseif ($o==1): ?>
											<th nowrap><a href="<?= site_url("analisis_master/index/$p/2")?>">Status <i class='fa fa-sort-desc fa-sm'></i></a></th>
										<?php else: ?>
											<th nowrap><a href="<?= site_url("analisis_master/index/$p/1")?>">Status <i class='fa fa-sort fa-sm'></i></a></th>
										<?php endif; ?>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($main as $data): ?>
										<tr>
											<td>
												<?php if ($data['jenis']!=1): ?>
													<input type="checkbox" name="id_cb[]" value="<?= $data['id']?>" />
												<?php endif; ?>
											</td>
											<td><?= $data['no']?></td>
											<td nowrap>
												<a href="<?= site_url("analisis_master/menu/$data[id]")?>" class="btn bg-purple btn-flat btn-xs"  title="Rincian Analisis"><i class="fa fa-list-ol"></i></a>
												<a href="<?= site_url("analisis_master/form/$p/$o/$data[id]")?>" class="btn bg-orange btn-flat btn-xs"  title="Ubah Data"><i class='fa fa-edit'></i></a>
												<?php if ($data['jenis']!=1): ?>
													<a href="#" data-href="<?= site_url("analisis_master/delete/$p/$o/$data[id]")?>" class="btn bg-maroon btn-flat btn-xs"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
												<?php endif; ?>
											</td>
											<td width="60%"><?= $data['nama']?></td>
											<td nowrap><?= $data['subjek']?></td>
											<td><?= $data['lock']?></td>
										</tr>
									<?php endforeach;?>
								</tbody>
							</table>
						</div>
					</form>
					<div class="row text-sm">
						<div class="col-sm-6">
							<div class="dataTables_length">
								<form id="paging" action="<?= site_url("analisis_master")?>" method="post" class="form-horizontal">
									<label>
										Tampilkan
										<select name="per_page" class="form-control form-control-sm" onchange="$('#paging').submit()">
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
										<li class="page-item disabled"><a class="page-link" href="<?= site_url("analisis_master/index/$paging->start_link/$o")?>" aria-label="First"><span aria-hidden="true">Awal</span></a></li>
									<?php endif; ?>
									<?php if ($paging->prev): ?>
										<li class="page-item"><a class="page-link" href="<?= site_url("analisis_master/index/$paging->prev/$o")?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
									<?php endif; ?>
									<?php for ($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
										<li <?=jecho($p, $i, "class='page-item active'")?>><a class="page-link" href="<?= site_url("analisis_master/index/$i/$o")?>"><?= $i?></a></li>
									<?php endfor; ?>
									<?php if ($paging->next): ?>
										<li class="page-item"><a class="page-link" href="<?= site_url("analisis_master/index/$paging->next/$o")?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
									<?php endif; ?>
									<?php if ($paging->end_link): ?>
										<li class="page-item disabled"><a class="page-link" href="<?= site_url("analisis_master/index/$paging->end_link/$o")?>" aria-label="Last"><span aria-hidden="true">Akhir</span></a></li>
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
