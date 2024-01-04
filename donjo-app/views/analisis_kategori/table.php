<script>
	$(function() {
		var keyword = <?= $keyword; ?> ;
		$( "#cari" ).autocomplete({
			source: keyword,
			maxShowItems: 10,
		});
	});
</script>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengaturan Kategori - <?= $analisis_master['nama']; ?></h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('beranda')?>"><i class="fa fa-home"></i> Beranda</a></li>
			<li><a href="<?= site_url('analisis_master/clear')?>"> Master Analisis</a></li>
			<li><a href="<?= site_url('analisis_master/leave'); ?>"><?= $analisis_master['nama']; ?></a></li>
			<li class="active">Pengaturan Kategori</li>
		</ol>
	</section>
	</section>
	<section class="content" id="maincontent">
		<form id="mainform" name="mainform" method="post">
			<div class="row">
				<div class="col-md-4 col-lg-3">
					<?php $this->load->view('analisis_master/left', $data); ?>
				</div>
				<div class="col-md-8 col-lg-9">
					<div class="box box-info">
            <div class="box-header with-border">
							<?php if (can('u')): ?>
								<a href="<?= site_url('analisis_kategori/form')?>" class="btn btn-social btn-flat btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Tambah Kategori / Variabel Baru" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Tambah Data Kategori Indikator"><i class="fa fa-plus"></i> Tambah Kategori / Variabel Baru</a>
							<?php endif; ?>
							<?php if (can('h')): ?>
								<a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform', '<?= site_url("analisis_kategori/delete_all/{$p}/{$o}")?>')" class="btn btn-social btn-flat btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
							<?php endif; ?>
							<a href="<?= site_url('analisis_master/leave'); ?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left "></i> Kembali Ke <?= $analisis_master['nama']?></a>
						</div>
						<div class="box-body">
							<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
								<form id="mainform" name="mainform" method="post">
									<div class="row">
										<div class="col-sm-12">
											<div class="input-group input-group-sm pull-right">
												<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="<?=html_escape($cari)?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?= site_url('analisis_kategori/search')?>');$('#'+'mainform').submit();}">
												<div class="input-group-btn">
													<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?= site_url('analisis_kategori/search')?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12">
											<div class="table-responsive">
												<table class="table table-bordered dataTable table-striped table-hover tabel-daftar">
													<thead class="bg-gray disabled color-palette">
														<tr>
															<?php if (can('u')): ?>
																<th><input type="checkbox" id="checkall"/></th>
															<?php endif; ?>
															<th>No</th>
															<?php if (can('u')): ?>
																<th>Aksi</th>
															<?php endif; ?>
															<?php if ($o == 4): ?>
																<th><a href="<?= site_url("analisis_kategori/index/{$p}/3")?>">Kategori / Variabel <i class='fa fa-sort-asc fa-sm'></i></a></th>
															<?php elseif ($o == 3): ?>
																<th><a href="<?= site_url("analisis_kategori/index/{$p}/4")?>">Kategori / Variabel <i class='fa fa-sort-desc fa-sm'></i></a></th>
															<?php else: ?>
																<th><a href="<?= site_url("analisis_kategori/index/{$p}/4")?>">Kategori / Variabel <i class='fa fa-sort fa-sm'></i></a></th>
															<?php endif; ?>
														</tr>
													</thead>
													<tbody>
														<?php if ($main): ?>
															<?php foreach ($main as $data): ?>
																<tr>
																	<?php if (can('u')): ?>
																		<td class="padat"><input type="checkbox" name="id_cb[]" value="<?= $data['id']?>" /></td>
																	<?php endif; ?>
																	<td class="padat"><?= $data['no']; ?></td>
																	<?php if (can('u')): ?>
																		<td class="aksi">
																			<a href="<?= site_url("analisis_kategori/form/{$p}/{$o}/{$data['id']}")?>" class="btn bg-orange btn-flat btn-sm"  title="Ubah Data Kategori Indikator"  data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ubah Data Kategori Indikator"><i class='fa fa-edit'></i></a>
																			<?php if (can('h')): ?>
																				<a href="#" data-href="<?= site_url("analisis_kategori/delete/{$p}/{$o}/{$data['id']}")?>" class="btn bg-maroon btn-flat btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																			<?php endif; ?>
																		</td>
																	<?php endif; ?>
																	<td><?= $data['kategori']; ?></td>
																</tr>
															<?php endforeach; ?>
														<?php else: ?>
															<tr>
																<td class="text-center" colspan="4">Data Tidak Tersedia</td>
															</tr>
														<?php endif; ?>
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
		</form>
	</section>
</div>
<?php $this->load->view('global/confirm_delete'); ?>
