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
		<form id="mainform" name="mainform" method="post">
			<div class="row">
				<div class="col-md-3">
          <?php $this->load->view('kategori/menu_kiri.php')?>
				</div>
				<div class="col-md-9">
					<div class="box box-info">
            <div class="box-header with-border">
							<?php if ($this->CI->cek_hak_akses('u')): ?>
	              <a href="<?= site_url("kategori/ajax_add_sub_kategori/{$kategori}")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Tambah Sub Kategori"  class="btn btn-social btn-flat btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class='fa fa-plus'></i> Tambah Sub Kategori</a>
	            <?php endif; ?>
							<?php if ($this->CI->cek_hak_akses('h')): ?>
								<a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform', '<?= site_url("kategori/delete_all_sub_kategori/{$kategori}")?>')" class="btn btn-social btn-flat btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
							<?php endif; ?>
							<a href="<?= site_url('kategori')?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Tambah Artikel">
								<i class="fa fa-arrow-circle-left "></i>Kembali ke Daftar Kategori
            	</a>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-sm-12">
									<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
										<form id="mainform" name="mainform" method="post">
											<div class="row">
												<div class="col-sm-12">
													<div class="table-responsive">
														<table class="table table-bordered dataTable table-hover">
															<thead class="bg-gray disabled color-palette">
																<tr>
																	<?php if ($this->CI->cek_hak_akses('h')): ?>
																		<th><input type="checkbox" id="checkall"/></th>
																	<?php endif; ?>
																	<th class="padat">No</th>
																	<?php if ($this->CI->cek_hak_akses('u')): ?>
																		<th>Aksi</th>
																	<?php endif; ?>
                                  <th>Nama Sub Menu</th>
                                  <th>Aktif</th>
                                  <th>Link</th>
																</tr>
															</thead>
															<tbody>
																<?php foreach ($subkategori as $data): ?>
																	<tr>
																		<?php if ($this->CI->cek_hak_akses('h')): ?>
																			<td><input type="checkbox" name="id_cb[]" value="<?=$data['id']?>" /></td>
																		<?php endif; ?>
																		<td><?=$data['no']?></td>
																		<?php if ($this->CI->cek_hak_akses('u')): ?>
																			<td nowrap>
																				<?php if ($this->CI->cek_hak_akses('h')): ?>
																					<a href="<?= site_url("kategori/urut/{$data['id']}/1/{$kategori}")?>" class="btn bg-olive btn-flat btn-sm"  title="Pindah Posisi Ke Bawah"><i class="fa fa-arrow-down"></i></a>
																					<a href="<?= site_url("kategori/urut/{$data['id']}/2/{$kategori}")?>" class="btn bg-olive btn-flat btn-sm"  title="Pindah Posisi Ke Atas"><i class="fa fa-arrow-up"></i></a>
		                                      <a href="<?= site_url("kategori/ajax_add_sub_kategori/{$kategori}/{$data['id']}")?>" class="btn bg-orange btn-flat btn-sm" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ubah Data" title="Ubah Data"><i class="fa fa-edit"></i></a>
																					<?php if ($data['enabled'] == '2'): ?>
																						<a href="<?= site_url("kategori/kategori_lock_sub_kategori/{$kategori}/{$data['id']}")?>" class="btn bg-navy btn-flat btn-sm"  title="Aktifkan"><i class="fa fa-lock">&nbsp;</i></a>
																					<?php elseif ($data['enabled'] == '1'): ?>
																						<a href="<?= site_url("kategori/kategori_unlock_sub_kategori/{$kategori}/{$data['id']}")?>" class="btn bg-navy btn-flat btn-sm"  title="Non Aktifkan"><i class="fa fa-unlock"></i></a>
																					<?php endif; ?>
																				<?php endif; ?>
																				<?php if ($this->CI->cek_hak_akses('h')): ?>
																					<a href="#" data-href="<?= site_url("kategori/delete_sub_kategori/{$kategori}/{$data['id']}")?>" class="btn bg-maroon btn-flat btn-sm"  title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																				<?php endif; ?>
																		  </td>
																		<?php endif; ?>
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
<?php $this->load->view('global/confirm_delete'); ?>