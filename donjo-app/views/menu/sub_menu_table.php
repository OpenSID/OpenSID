<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengaturan Sub Menu Statis</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('menu')?>"><i class="fa fa-dashboard"></i> Daftar Menu</a></li>
			<li class="active">Pengaturan Sub Menu Statis</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="mainform" name="mainform" action="" method="post">
			<div class="row">
				<div class="col-md-3">
					<?php $this->load->view('kategori/menu_kiri.php')?>
				</div>
				<div class="col-md-9">
					<div class="box box-info">
						<div class="box-header with-border">
							<a href="<?= site_url("menu/ajax_add_sub_menu/$tip/$menu")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Tambah Sub Menu"  class="btn btn-social btn-flat btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class='fa fa-plus'></i> Tambah Sub Menu</a>
							<a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform', '<?=site_url("menu/delete_all_sub_menu/$tip/$menu")?>')" class="btn btn-social btn-flat btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
							<a href="<?= site_url("menu")?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Tambah Artikel">
								<i class="fa fa-arrow-circle-left "></i>Kembali ke Daftar Menu
							</a>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-sm-12">
									<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
										<form id="mainform" name="mainform" action="" method="post">
											<div class="row">
												<div class="col-sm-12">
													<div class="table-responsive">
														<table class="table table-bordered table-striped dataTable table-hover">
															<thead class="bg-gray disabled color-palette">
																<tr>
																	<th><input type="checkbox" id="checkall"/></th>
																	<th>No</th>
																	<th>Aksi</th>
																	<th>Nama Sub Menu</th>
																	<th>Link</th>
																</tr>
															</thead>
															<tbody>
																<?php foreach ($submenu as $data): ?>
																	<tr>
																		<td><input type="checkbox" name="id_cb[]" value="<?=$data['id']?>" /></td>
																		<td><?=$data['no']?></td>
																		<td nowrap>
																			<a href="<?= site_url("menu/urut/$tip/$data[id]/1/$menu")?>" class="btn bg-olive btn-flat btn-sm"  title="Pindah Posisi Ke Bawah"><i class="fa fa-arrow-down"></i></a>
																			<a href="<?= site_url("menu/urut/$tip/$data[id]/2/$menu")?>" class="btn bg-olive btn-flat btn-sm"  title="Pindah Posisi Ke Atas"><i class="fa fa-arrow-up"></i></a>
																			<a href="<?=site_url("menu/ajax_add_sub_menu/$tip/$menu/$data[id]")?>" class="btn bg-orange btn-flat btn-sm" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ubah Data" title="Ubah Data"><i class="fa fa-edit"></i></a>
																			<?php if ($data['enabled'] == '2'): ?>
																				<a href="<?= site_url("menu/menu_lock_sub_menu/$tip/$menu/$data[id]")?>" class="btn bg-navy btn-flat btn-sm"  title="Aktifkan"><i class="fa fa-lock">&nbsp;</i></a>
																			<?php elseif ($data['enabled'] == '1'): ?>
																				<a href="<?= site_url("menu/menu_unlock_sub_menu/$tip/$menu/$data[id]")?>" class="btn bg-navy btn-flat btn-sm"  title="Non Aktifkan"><i class="fa fa-unlock"></i></a>
																			<?php endif ?>
																			<a href="#" data-href="<?= site_url("menu/delete_sub_menu/$tip/$menu/$data[id]")?>" class="btn bg-maroon btn-flat btn-sm"  title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
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
