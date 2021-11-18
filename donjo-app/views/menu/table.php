<script type="text/javascript">
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
		<h1>Menu Statis</h1>
		<ol class="breadcrumb">
			<li><a href="<?=site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<?php if ($menu_utama): ?>
				<li><a href="<?=site_url('hom_sid')?>"><i class="fa fa-home"></i> Menu Statis</a></li>
				<li class="active"><?= $menu_utama['nama']; ?></li>
			<?php else: ?>
				<li class="active">Menu Statis</li>
			<?php endif; ?>
		</ol>
	</section>
	<?php $judul = ($menu_utama) ? 'Submenu' : 'Menu'; ?>
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
								<a href="<?= site_url('menu/ajax_menu')?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Tambah <?= $judul; ?>"  class="btn btn-social btn-flat btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class='fa fa-plus'></i> Tambah <?= $judul; ?></a>
							<?php endif; ?>
							<?php if ($this->CI->cek_hak_akses('h')): ?>
								<a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform', '<?=site_url('menu/delete_all')?>')" class="btn btn-social btn-flat btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
							<?php endif; ?>
							<?php if ($menu_utama): ?>
								<a href="<?= site_url('menu/clear')?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Kembali ke Daftar Menu">
									<i class="fa fa-arrow-circle-left "></i>Kembali ke Daftar Menu
								</a>
							<?php endif; ?>
						</div>
						<?php if ($menu_utama): ?>
							<div class="box-header with-border">
								<strong>MENU UTAMA / <?= strtoupper($menu_utama['nama']); ?></strong>
							</div>
						<?php endif; ?>
						<div class="box-body">
							<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
								<form id="mainform" name="mainform" method="post">
									<div class="row">
										<div class="col-sm-6">
											<select class="form-control input-sm " name="filter" onchange="formAction('mainform', '<?=site_url('menu/filter')?>')">
												<option value="">Semua</option>
												<option value="1" <?= selected($filter, 1); ?>>Aktif</option>
												<option value="2" <?= selected($filter, 2); ?>>Tidak Aktif</option>
											</select>
										</div>
										<div class="col-sm-6">
											<div class="box-tools">
												<div class="input-group input-group-sm pull-right">
													<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="<?=html_escape($cari)?>" onkeypress="if (event.keyCode == 13):$('#'+'mainform').attr('action', '<?=site_url('menu/search')?>');$('#'+'mainform').submit();endif">
													<div class="input-group-btn">
														<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?=site_url('menu/search')?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
													</div>
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
															<?php if ($this->CI->cek_hak_akses('h')): ?>
																<th><input type="checkbox" id="checkall"/></th>
															<?php endif; ?>
															<th>No</th>
															<th>Aksi</th>
															<?php if ($o == 2): ?>
																<th><a href="<?= site_url("menu/index/{$p}/1")?>">Nama <?= $judul; ?><i class='fa fa-sort-asc fa-sm'></i></a></th>
															<?php elseif ($o == 1): ?>
																<th><a href="<?= site_url("menu/index/{$p}/2")?>">Nama <?= $judul; ?><i class='fa fa-sort-desc fa-sm'></i></a></th>
															<?php else: ?>
																<th><a href="<?= site_url("menu/index/{$p}/1")?>">Nama <?= $judul; ?><i class='fa fa-sort fa-sm'></i></a></th>
															<?php endif; ?>
															<th>Link</th>
														</tr>
													</thead>
													<tbody>
														<?php if ($main): ?>
															<?php foreach ($main as $data): ?>
																<tr>
																	<?php if ($this->CI->cek_hak_akses('h')): ?>
																		<td class="padat"><input type="checkbox" name="id_cb[]" value="<?=$data['id']?>" /></td>
																	<?php endif; ?>
																	<td class="padat"><?=$data['no']?></td>
																	<td class="aksi">
																		<?php if ($this->CI->cek_hak_akses('u')): ?>
																			<a href="<?= site_url("menu/urut/{$data['id']}/1")?>" class="btn bg-olive btn-flat btn-sm"  title="Pindah Posisi Ke Bawah"><i class="fa fa-arrow-down"></i></a>
																			<a href="<?= site_url("menu/urut/{$data['id']}/2")?>" class="btn bg-olive btn-flat btn-sm"  title="Pindah Posisi Ke Atas"><i class="fa fa-arrow-up"></i></a>
																		<?php endif; ?>
																		<?php if (! $menu_utama): ?>
																			<a href="<?= site_url("menu/clear/{$data['id']}")?>" class="btn bg-purple btn-flat btn-sm"  title="Sub Menu"><i class="fa fa-bars"></i></a>
																		<?php endif; ?>
																		<?php if ($this->CI->cek_hak_akses('u')): ?>
																			<a href="<?=site_url("menu/ajax_menu/{$data['id']}")?>" class="btn bg-orange btn-flat btn-sm" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ubah <?= $judul; ?>" title="Ubah <?= $judul; ?>"><i class="fa fa-edit"></i></a>
																			<?php if ($data['enabled'] == '1'): ?>
																				<a href="<?= site_url("menu/menu_lock/{$data['id']}")?>" class="btn bg-navy btn-flat btn-sm"  title="Non Aktifkan"><i class="fa fa-unlock"></i></a>
																			<?php else: ?>
																				<a href="<?= site_url("menu/menu_unlock/{$data['id']}")?>" class="btn bg-navy btn-flat btn-sm"  title="Aktifkan"><i class="fa fa-lock">&nbsp;</i></a>
																			<?php endif; ?>
																		<?php endif; ?>
																		<?php if ($this->CI->cek_hak_akses('h')): ?>
																			<a href="#" data-href="<?= site_url("menu/delete/{$data['id']}")?>" class="btn bg-maroon btn-flat btn-sm"  title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																		<?php endif; ?>
																	</td>
																	<td nowrap width="40%"><?= $data['nama']?></td>
																	<td nowrap><a href="<?= $data['link']?>" target="_blank"><?= $data['link']?></a></td>
																</tr>
															<?php endforeach; ?>
														<?php else: ?>
															<tr>
																<td class="text-center" colspan="5">Data Tidak Tersedia</td>
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
