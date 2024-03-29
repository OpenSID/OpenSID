<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengaturan Teks Berjalan</h1>
		<ol class="breadcrumb">
			<li><a href="<?=site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Pengaturan Teks Berjalan</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="mainform" name="mainform" method="post">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-info">
						<div class="box-header with-border">
							<?php if (can('u')): ?>
								<a href="<?=site_url('teks_berjalan/form')?>" class="btn btn-social btn-flat btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Tambah Artikel">
									<i class="fa fa-plus"></i> Tambah Teks
								</a>
							<?php endif; ?>
							<?php if (can('h')): ?>
								<a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform', '<?=site_url('teks_berjalan/delete_all')?>')" class="btn btn-social btn-flat btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
							<?php endif; ?>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-sm-12">
									<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
										<form id="mainform" name="mainform" method="post">
											<div class="row">
												<div class="col-sm-12">
													<div class="table-responsive">
														<table class="table table-bordered table-striped dataTable table-hover tabel-daftar">
															<thead class="bg-gray disabled color-palette">
																<tr>
																	<?php if (can('h')): ?>
																		<th><input type="checkbox" id="checkall"/></th>
																	<?php endif; ?>
																	<th>No.</th>
																	<?php if (can('u')):
																	    ?>
																		<th>Aksi</th>
																	<?php endif; ?>
																	<th>Isi Teks Berjalan</th>
																	<th>Tautan ke Artikel</th>
																	<th>Tampil Di</th>
																</tr>
															</thead>
															<tbody>
																<?php
                                                                // $main = false;
                                                                if($main) : ?>
																<?php foreach ($main as $data): ?>
																	<tr>
																		<?php if (can('h')): ?>
																			<td class="padat"><input type="checkbox" name="id_cb[]" value="<?=$data['id']?>" /></td>
																		<?php endif; ?>
																		<td class="padat"><?=$data['no']?></td>
																		<?php if (can('u')): ?>
																			<td class="aksi">
																				<?php if (can('u')): ?>
																					<a href="<?=site_url("teks_berjalan/urut/{$data['id']}/1")?>" class="btn bg-olive btn-flat btn-sm"  title="Pindah Posisi Ke Bawah"><i class="fa fa-arrow-down"></i></a>
																					<a href="<?=site_url("teks_berjalan/urut/{$data['id']}/2")?>" class="btn bg-olive btn-flat btn-sm"  title="Pindah Posisi Ke Atas"><i class="fa fa-arrow-up"></i></a>
																					<a href="<?=site_url("teks_berjalan/form/{$data['id']}")?>" class="btn bg-orange btn-flat btn-sm"  title="Ubah"><i class="fa fa-edit"></i></a>
																					<?php if ($data['status'] == '2'): ?>
																						<a href="<?=site_url("teks_berjalan/lock/{$data['id']}/1")?>" class="btn bg-navy btn-flat btn-sm"  title="Aktifkan"><i class="fa fa-lock">&nbsp;</i></a>
																					<?php else: ?>
																						<a href="<?=site_url("teks_berjalan/lock/{$data['id']}/2")?>" class="btn bg-navy btn-flat btn-sm"  title="Non Aktifkan"><i class="fa fa-unlock"></i></a>
																					<?php endif; ?>
																				<?php endif; ?>
																				<?php if (can('h')): ?>
																					<a href="#" data-href="<?=site_url("teks_berjalan/delete/{$data['id']}")?>" class="btn bg-maroon btn-flat btn-sm"  title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																				<?php endif; ?>
																			</td>
																		<?php endif; ?>
																		<td><?=$data['teks']?> <a href="<?=$data['tautan']?>" target="_blank"><?=$data['judul_tautan']?></a></td>
																		<td width="10%" nowrap>
																			<a href="<?=$data['tautan']?>" target="_blank"><?=tgl_indo($data['tgl_upload']) . ' <br> ' . $data['judul']?></a>
																		</td>
																		<td class="padat"><?= $data['tampilkan'] ?></td>
																	</tr>
																<?php endforeach; ?>
																<?php else: tidak_ada_data(6); endif; ?>
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
