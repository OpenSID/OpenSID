<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengaturan Teks Berjalan</h1>
		<ol class="breadcrumb">
			<li><a href="<?=site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Pengaturan Teks Berjalan</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form class="form-inline" id="mainform" name="mainform" action="" method="post">
			<div class="row">
				<div class="col-md-12">
					<div class="card card-outline card-info">
						<div class="card-header with-border">
							<a href="<?=site_url("teks_berjalan/form")?>" class="btn btn-flat btn-success btn-xs btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left"  title="Tambah Artikel">
								<i class="fa fa-plus"></i> Tambah Teks
							</a>
							<?php if ($this->CI->cek_hak_akses('h')): ?>
								<a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform', '<?=site_url("teks_berjalan/delete_all")?>')" class="btn btn-flat btn-danger btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
							<?php endif; ?>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-sm-12">
									<div class="dataTables_wrapper dt-bootstrap no-footer">
										<form class="form-inline" id="mainform" name="mainform" action="" method="post">
											<div class="row">
												<div class="col-sm-12">
													<div class="table-responsive">
														<table class="table table-bordered table-striped dataTable table-hover">
															<thead class="bg-gray disabled color-palette">
																<tr>
																	<th><input type="checkbox" id="checkall"/></th>
																	<th>No</th>
																	<th>Aksi</th>
																	<th>Isi Teks Berjalan</th>
																	<th>Tautan ke Artikel</th>
																</tr>
															</thead>
															<tbody>
																<?php foreach ($main as $data): ?>
																	<tr>
																		<td width="1%"><input type="checkbox" name="id_cb[]" value="<?=$data['id']?>" /></td>
																		<td width="1%"><?=$data['no']?></td>
																		<td width="5%" nowrap>
																			<a href="<?=site_url("teks_berjalan/urut/$data[id]/1")?>" class="btn bg-olive btn-flat btn-xs"  title="Pindah Posisi Ke Bawah"><i class="fa fa-arrow-down"></i></a>
																			<a href="<?=site_url("teks_berjalan/urut/$data[id]/2")?>" class="btn bg-olive btn-flat btn-xs"  title="Pindah Posisi Ke Atas"><i class="fa fa-arrow-up"></i></a>
																			<a href="<?=site_url("teks_berjalan/form/$data[id]")?>" class="btn bg-orange btn-flat btn-xs"  title="Ubah"><i class="fa fa-edit"></i></a>
																			<?php if ($data['status'] == '2'): ?>
																				<a href="<?=site_url("teks_berjalan/lock/$data[id]/1")?>" class="btn bg-navy btn-flat btn-xs"  title="Aktifkan"><i class="fa fa-lock">&nbsp;</i></a>
																			<?php else: ?>
																				<a href="<?=site_url("teks_berjalan/lock/$data[id]/2")?>" class="btn bg-navy btn-flat btn-xs"  title="Non Aktifkan"><i class="fa fa-unlock"></i></a>
																			<?php endif; ?>
																			<?php if ($this->CI->cek_hak_akses('h')): ?>
																				<a href="#" data-href="<?=site_url("teks_berjalan/delete/$data[id]")?>" class="btn bg-maroon btn-flat btn-xs"  title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																			<?php endif; ?>
																		</td>
																		<td><?=$data['teks']?> <a href="<?=$data['tautan']?>" target="_blank"><?=$data['judul_tautan']?></a></td>
																		<td width="10%" nowrap>
																			<a href="<?=$data['tautan']?>" target="_blank"><?=tgl_indo($data['tgl_upload']).' <br> '.$data['judul']?></a>
																		</td>
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
