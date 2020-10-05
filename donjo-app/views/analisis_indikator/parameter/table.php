<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengaturan Ukuran/Nilai Indikator Analisis</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid') ?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('analisis_master') ?>"> Master Analisis</a></li>
			<li><a href="<?= site_url() ?>analisis_indikator/leave"><?= $analisis_master['nama']?></a></li>
			<li><a href="<?= site_url() ?>analisis_indikator">Pengaturan Indikator Analisis</a></li>
			<li class="active">Pengaturan Nilai</li>
		</ol>
	</section>
	</section>
	<section class="content" id="maincontent">
		<form id="mainform" name="mainform" action="" method="post">
			<div class="row">
				<div class="col-md-4 col-lg-3">
					<?php $this->load->view('analisis_master/left', $data);?>
				</div>
				<div class="col-md-8 col-lg-9">
					<div class="box box-info">
            <div class="box-header with-border">
							<a href="<?= site_url("analisis_indikator/form_parameter/$analisis_indikator[id]") ?>" class="btn btn-social btn-flat btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Tambah Ukuran Ukuran/Nilai Baru"  data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Tambah Data Parameter"><i class="fa fa-plus"></i> Tambah Ukuran Ukuran/Nilai Baru</a>
							<a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform', '<?= site_url("analisis_indikator/p_delete_all/$analisis_indikator[id]") ?>')" class="btn btn-social btn-flat btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
							<a href="<?= site_url() ?>analisis_indikator" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left "></i> Kembali Ke Indikator Analisis</a>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-sm-12">
									<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
										<form id="mainform" name="mainform" action="" method="post">
											<div class="row">
												<div class="col-sm-12">
													<div class="table-responsive">
														<table class="table table-bordered dataTable table-hover nowrap">
															<thead class="bg-gray disabled color-palette">
																<tr>
																	<?php if ($analisis_master['lock']==1): ?>
																		<th><input type="checkbox" id="checkall"/></th>
																	<?php endif; ?>
																	<th>No</th>
																	<?php if ($analisis_master['lock']==1): ?>
																		<th>Aksi</th>
																	<?php endif; ?>
																	<th>Kode</th>
																	<th>Jawaban</th>
																	<th>Nilai/Ukuran</th>
																</tr>
															</thead>
															<tbody>
																<?php foreach ($main as $data): ?>
																	<tr>
																		<?php if ($analisis_master['lock']==1): ?>
																			<td><input type="checkbox" name="id_cb[]" value="<?= $data['id']?>" /></td>
																		<?php endif; ?>
																		<td><?= $data['no']?></td>
																		<?php if ($analisis_master['lock']==1): ?>
																			<td nowrap>
																				<a href="<?= site_url("analisis_indikator/form_parameter/$analisis_indikator[id]/$data[id]") ?>" class="btn bg-orange btn-flat btn-sm" title="Ubah Data"  data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ubah Data Parameter"><i class='fa fa-edit'></i></a>
																				<?php if ($analisis_master['jenis']!=1): ?>
																					<a href="#" data-href="<?= site_url("analisis_indikator/p_delete/$analisis_indikator[id]/$data[id]") ?>" class="btn bg-maroon btn-flat btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																				<?php endif; ?>
																			</td>
																		<?php endif; ?>
																		<td><?= $data['kode_jawaban']?></td>
																		<td width="70%"><?= $data['jawaban']?></td>
																		<td><?= $data['nilai']?></td>
																	</tr>
																<?php endforeach; ?>
															</tbody>
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

