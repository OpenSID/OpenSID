<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengaturan Sub Menu Dinamis / Kategori</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('kategori')?>"> Daftar Kategori</a></li>
			<li class="active">Pengaturan Sub Menu Dinamis</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form class="form-inline" id="mainform" name="mainform" action="" method="post">
			<div class="row">
				<div class="col-md-3">
          <?php $this->load->view('kategori/menu_kiri.php')?>
				</div>
				<div class="col-md-9">
					<div class="card card-outline card-info">
            <div class="card-header with-border">
              <a href="<?= site_url("kategori/ajax_add_sub_kategori/$kategori")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Tambah Sub Kategori"  class="btn btn-flat btn-success btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left"><i class='fa fa-plus'></i> Tambah Sub Kategori</a>
							<a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform', '<?= site_url("kategori/delete_all_sub_kategori/$kategori")?>')" class="btn btn-flat btn-danger btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
							<a href="<?= site_url("kategori")?>" class="btn btn-flat btn-info btn-xs btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left"  title="Tambah Artikel">
								<i class="fa fa-arrow-circle-left "></i>Kembali ke Daftar Kategori
            	</a>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-sm-12">
									<div class="dataTables_wrapper dt-bootstrap no-footer">
										<form class="form-inline" id="mainform" name="mainform" action="" method="post">
											<div class="row">
												<div class="col-sm-12">
													<div class="table-responsive">
														<table class="table table-bordered dataTable table-hover">
															<thead class="bg-gray disabled color-palette">
																<tr>
																	<th><input type="checkbox" id="checkall"/></th>
																	<th>No</th>
																	<th>Aksi</th>
                                  <th>Nama Sub Menu</th>
                                  <th>Aktif</th>
                                  <th>Link</th>
																</tr>
															</thead>
															<tbody>
																<?php foreach ($subkategori as $data): ?>
																	<tr>
																		<td><input type="checkbox" name="id_cb[]" value="<?=$data['id']?>" /></td>
																		<td><?=$data['no']?></td>
																		<td nowrap>
																			<a href="<?= site_url("kategori/urut/$data[id]/1/$kategori")?>" class="btn bg-olive btn-flat btn-xs"  title="Pindah Posisi Ke Bawah"><i class="fa fa-arrow-down"></i></a>
																			<a href="<?= site_url("kategori/urut/$data[id]/2/$kategori")?>" class="btn bg-olive btn-flat btn-xs"  title="Pindah Posisi Ke Atas"><i class="fa fa-arrow-up"></i></a>
                                      <a href="<?= site_url("kategori/ajax_add_sub_kategori/$kategori/$data[id]")?>" class="btn bg-orange btn-flat btn-xs" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ubah Data" title="Ubah Data"><i class="fa fa-edit"></i></a>
																			<?php if ($data['enabled'] == '2'): ?>
																				<a href="<?= site_url("kategori/kategori_lock_sub_kategori/$kategori/$data[id]")?>" class="btn bg-navy btn-flat btn-xs"  title="Aktifkan"><i class="fa fa-lock">&nbsp;</i></a>
																			<?php elseif ($data['enabled'] == '1'): ?>
																				<a href="<?= site_url("kategori/kategori_unlock_sub_kategori/$kategori/$data[id]")?>" class="btn bg-navy btn-flat btn-xs"  title="Non Aktifkan"><i class="fa fa-unlock"></i></a>
																			<?php endif; ?>
																			<a href="#" data-href="<?= site_url("kategori/delete_sub_kategori/$kategori/$data[id]")?>" class="btn bg-maroon btn-flat btn-xs"  title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																	  </td>
                                    <td width="50%"><?= $data['kategori']?></td>
                                    <td><?= $data['aktif']?></td>
                                    <td>-</td>
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