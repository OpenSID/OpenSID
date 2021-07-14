<script type="text/javascript">
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
		<h1>Pengaturan Menu Statis</h1>
		<ol class="breadcrumb">
			<li><a href="<?=site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Pengaturan Menu Statis</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="mainform" name="mainform" method="post">
			<div class="row">
				<div class="col-md-3">
					<?php $this->load->view('kategori/menu_kiri.php')?>
				</div>
				<div class="col-md-9">
					<div class="box box-info">
						<div class="box-header with-border">
							<?php if ($this->CI->cek_hak_akses('u')): ?>
								<a href="<?=site_url("menu/form")?>" class="btn btn-social btn-flat btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Tambah Menu Baru">
									<i class="fa fa-plus"></i>Tambah Menu Baru
								</a>
							<?php endif; ?>
							<?php if ($this->CI->cek_hak_akses('h')): ?>
								<a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform', '<?=site_url("menu/delete_all")?>')" class="btn btn-social btn-flat btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
							<?php endif; ?>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-sm-12">
									<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
										<form id="mainform" name="mainform" method="post">
											<div class="row">
												<div class="col-sm-6">
													<select class="form-control input-sm " name="filter" onchange="formAction('mainform', '<?=site_url('menu/filter')?>')">
														<option value="">Semua</option>
														<option value="1" <?php if ($filter==1): ?>selected<?php endif ?>>Aktif</option>
														<option value="2" <?php if ($filter==2): ?>selected<?php endif ?>>Tidak Aktif</option>
													</select>
												</div>
												<div class="col-sm-6">
													<div class="box-tools">
														<div class="input-group input-group-sm pull-right">
															<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="<?=html_escape($cari)?>" onkeypress="if (event.keyCode == 13):$('#'+'mainform').attr('action', '<?=site_url("menu/search")?>');$('#'+'mainform').submit();endif">
															<div class="input-group-btn">
																<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?=site_url("menu/search")?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-12">
													<div class="table-responsive">
														<table class="table table-bordered table-striped dataTable table-hover">
															<thead class="bg-gray disabled color-palette">
																<tr>
																	<?php if ($this->CI->cek_hak_akses('h')): ?>
																		<th><input type="checkbox" id="checkall"/></th>
																	<?php endif; ?>
																	<th>No</th>
																	<th>Aksi</th>
																	<?php if ($o==2): ?>
																		<th><a href="<?= site_url("menu/index/$p/1")?>">Nama Menu<i class='fa fa-sort-asc fa-sm'></i></a></th>
																	<?php elseif ($o==1): ?>
																		<th><a href="<?= site_url("menu/index/$p/2")?>">Nama Menu<i class='fa fa-sort-desc fa-sm'></i></a></th>
																	<?php else: ?>
																		<th><a href="<?= site_url("menu/index/$p/1")?>">Nama Menu<i class='fa fa-sort fa-sm'></i></a></th>
																	<?php endif; ?>
																	<th>Link</th>
																</tr>
															</thead>
															<tbody>
																<?php foreach ($main as $data): ?>
																	<tr>
																		<?php if ($this->CI->cek_hak_akses('h')): ?>
																			<td><input type="checkbox" name="id_cb[]" value="<?=$data['id']?>" /></td>
																		<?php endif; ?>
																		<td><?=$data['no']?></td>
																		<td nowrap>
																			<?php if ($this->CI->cek_hak_akses('u')): ?>
																				<a href="<?= site_url("menu/urut/$data[id]/1")?>" class="btn bg-olive btn-flat btn-sm"  title="Pindah Posisi Ke Bawah"><i class="fa fa-arrow-down"></i></a>
																				<a href="<?= site_url("menu/urut/$data[id]/2")?>" class="btn bg-olive btn-flat btn-sm"  title="Pindah Posisi Ke Atas"><i class="fa fa-arrow-up"></i></a>
																			<?php endif; ?>
																			<a href="<?= site_url("menu/sub_menu/$data[id]")?>" class="btn bg-purple btn-flat btn-sm"  title="Sub Menu"><i class="fa fa-bars"></i></a>
																			<?php if ($this->CI->cek_hak_akses('u')): ?>
																				<a href="<?= site_url("menu/form/$data[id]")?>" class="btn btn-warning btn-flat btn-sm"  title="Ubah"><i class="fa fa-edit"></i></a>
																				<?php if ($data['enabled'] == '2'): ?>
																					<a href="<?= site_url("menu/menu_lock/".$data['id'])?>" class="btn bg-navy btn-flat btn-sm"  title="Aktifkan"><i class="fa fa-lock">&nbsp;</i></a>
																				<?php elseif ($data['enabled'] == '1'): ?>
																					<a href="<?= site_url("menu/menu_unlock/".$data['id'])?>" class="btn bg-navy btn-flat btn-sm"  title="Non Aktifkan"><i class="fa fa-unlock"></i></a>
																					<a href="<?=site_url("menu/ajax_add_sub_menu/$data[id]")?>" class="btn bg-olive btn-flat btn-sm" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Tambah Sub Menu" title="Tambah Sub Menu"><i class="fa fa-plus"></i></a>
																				<?php endif ?>
																			<?php endif; ?>
																			<?php if ($this->CI->cek_hak_akses('h')): ?>
																				<a href="#" data-href="<?= site_url("menu/delete/$data[id]")?>" class="btn bg-maroon btn-flat btn-sm"  title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																			<?php endif; ?>
																		</td>
																		<td nowrap width="40%"><?= $data['nama']?></td>
																		<td nowrap><a href="<?= $data['link']?>" target="_blank"><?= $data['link']?></a></td>
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
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>
<?php $this->load->view('global/confirm_delete');?>
