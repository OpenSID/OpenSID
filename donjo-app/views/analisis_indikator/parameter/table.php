<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengaturan Ukuran/Nilai Indikator Analisis</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid') ?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('analisis_master/clear') ?>"> Master Analisis</a></li>
			<li><a href="<?= site_url('analisis_master/leave'); ?>"><?= $analisis_master['nama']; ?></a></li>
			<li><a href="<?= site_url() ?>analisis_indikator">Pengaturan Indikator Analisis</a></li>
			<li class="active">Pengaturan Nilai</li>
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
							<?php if ($this->CI->cek_hak_akses('u') && ! $analisis_indikator['referensi']): ?>
								<a href="<?= site_url("analisis_indikator/form_parameter/{$analisis_indikator['id']}") ?>" class="btn btn-social btn-flat btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Tambah Ukuran Ukuran/Nilai Baru"  data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Tambah Data Parameter"><i class="fa fa-plus"></i> Tambah Ukuran Ukuran/Nilai Baru</a>
							<?php endif; ?>
							<?php if ($this->CI->cek_hak_akses('h') && ! $analisis_indikator['referensi']): ?>
								<a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform', '<?= site_url("analisis_indikator/p_delete_all/{$analisis_indikator['id']}") ?>')" class="btn btn-social btn-flat btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
							<?php endif; ?>
							<a href="<?= site_url() ?>analisis_indikator" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left "></i> Kembali Ke Indikator Analisis</a>
						</div>
						<div class="box-body">
							<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
								<form id="mainform" name="mainform" method="post">
									<div class="table-responsive">
										<table class="table table-bordered dataTable table-striped table-hover tabel-daftar">
											<thead class="bg-gray disabled color-palette">
												<tr>
													<?php if ($analisis_master['lock'] == 1 && $this->CI->cek_hak_akses('u') && ! $analisis_indikator['referensi']): ?>
														<th><input type="checkbox" id="checkall"/></th>
													<?php endif; ?>
													<th>No</th>
													<?php if ($analisis_master['lock'] == 1 && $this->CI->cek_hak_akses('u')): ?>
														<th>Aksi</th>
													<?php endif; ?>
													<th>Kode</th>
													<th>Jawaban</th>
													<th>Nilai / Ukuran</th>
												</tr>
											</thead>
											<tbody>
												<?php if ($main): ?>
													<?php foreach ($main as $key => $data): ?>
														<tr>
															<?php if ($analisis_master['lock'] == 1 && $this->CI->cek_hak_akses('u') && ! $analisis_indikator['referensi']): ?>
																<td class="padat"><input type="checkbox" name="id_cb[]" value="<?= $data['id']?>" /></td>
															<?php endif; ?>
															<td class="padat"><?= ($key + 1); ?></td>
															<?php if ($analisis_master['lock'] == 1 && $this->CI->cek_hak_akses('u')): ?>
																<td class="aksi">
																	<a href="<?= site_url("analisis_indikator/form_parameter/{$analisis_indikator['id']}/{$data['id']}") ?>" class="btn bg-orange btn-flat btn-sm" title="Ubah Data"  data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ubah Data Parameter"><i class='fa fa-edit'></i></a>
																	<?php if ($analisis_master['jenis'] != 1 && ! $analisis_indikator['referensi']): ?>
																		<a href="#" data-href="<?= site_url("analisis_indikator/p_delete/{$analisis_indikator['id']}/{$data['id']}") ?>" class="btn bg-maroon btn-flat btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																	<?php endif; ?>
																</td>
															<?php endif; ?>
															<td class="padat"><?= $data['kode_jawaban']?></td>
															<td><?= $data['jawaban']?></td>
															<td class="padat"><?= $data['nilai']?></td>
														</tr>
													<?php endforeach; ?>
												<?php else: ?>
													<tr>
														<td class="text-center" colspan="6">Data Tidak Tersedia</td>
													</tr>
												<?php endif; ?>
											</tbody>
										</table>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>
<?php $this->load->view('global/confirm_delete'); ?>

