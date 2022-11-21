<div class="content-wrapper">
	<section class="content-header">
		<h1>Daftar Program Bantuan</h1>
		<ol class="breadcrumb">
			<li><a href="<?=site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Daftar Program Bantuan</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<?php if ($this->CI->cek_hak_akses('u')): ?>
							<a href="<?=site_url('program_bantuan/create')?>" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Tambah Program Bantuan"><i class="fa fa-plus"></i> Tambah</a>
							<a href="<?=site_url('program_bantuan/impor')?>" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Impor Program Bantuan" data-target="#impor" data-remote="false" data-toggle="modal" data-backdrop="false" data-keyboard="false"><i class="fa fa-upload"></i> Impor</a>
						<?php endif; ?>
						<a href="<?=site_url('program_bantuan/panduan')?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Panduan"><i class="fa fa-question-circle"></i> Panduan</a>
						<a href="#" data-href="<?= site_url('program_bantuan/bersihkan_data')?>" class="btn btn-social btn-flat btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Bersihkan Data Peserta Tidak Valid" data-remote="false"  data-toggle="modal" data-target="#confirm-status" data-body="<?= $penjelasan_pembersihan; ?>"><i class="fa fa-wrench"></i>Bersihkan Data Peserta Tidak Valid</a>
						<?php if ($tampil != 0): ?>
							<a href="<?=site_url('program_bantuan')?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar Program Bantuan"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Daftar Program Bantuan</a>
						<?php endif; ?>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
									<div class="row">
										<div class="col-sm-9">
											<form id="mainform" name="mainform" method="post">
												<select class="form-control input-sm" name="sasaran" onchange="formAction('mainform', '<?=site_url('program_bantuan/filter/sasaran')?>')">
													<option value="">Pilih Sasaran</option>
													<?php foreach ($list_sasaran as $key => $value): ?>
														<option value="<?= $key; ?>" <?= selected($set_sasaran, $key); ?>><?= $value?></option>
													<?php endforeach; ?>
												</select>
											</form>
										</div>
										<div class="col-sm-12">
											<div class="table-responsive">
												<table class="table table-bordered table-striped dataTable table-hover tabel-daftar" id="table-program">
													<thead class="bg-gray disabled color-palette">
														<tr>
															<th>No</th>
															<th>Aksi</th>
															<th>Nama Program</th>
															<th>Asal Dana</th>
															<th>Jumlah Peserta</th>
															<th>Masa Berlaku</th>
															<th>Sasaran</th>
															<th>Status</th>
														</tr>
													</thead>
													<tbody>
														<?php $nomer = $paging->offset; ?>
														<?php foreach ($program as $item): ?>
															<?php $nomer++; ?>
															<tr>
																<td class="padat"><?= $nomer?></td>
																<td class="aksi">
																	<a href="<?= site_url("program_bantuan/detail/{$item['id']}")?>" class="btn bg-purple btn-flat btn-sm" title="Rincian"><i class="fa fa-list"></i></a>
																	<?php if ($this->CI->cek_hak_akses('u')): ?>
																		<a href="<?= site_url("program_bantuan/edit/{$item['id']}")?>" class="btn bg-orange btn-flat btn-sm" title="Ubah"><i class="fa fa-edit"></i></a>
																	<?php endif ?>
																	<?php if ($item['jml_peserta'] != 0): ?>
																		<a href="<?= site_url("program_bantuan/expor/{$item['id']}"); ?>" class="btn bg-navy btn-flat btn-sm" title="Expor"><i class="fa fa-download"></i></a>
																	<?php endif ?>
																	<?php if ($this->CI->cek_hak_akses('h')): ?>
																		<?php if ($item['jml_peserta'] != 0): ?>
																			<a class="btn bg-maroon btn-flat btn-sm disabled" title="Hapus"><i class="fa fa-trash-o"></i></a>
																		<?php else: ?>
																			<a href="#" data-href="<?= site_url("program_bantuan/hapus/{$item['id']}")?>" class="btn bg-maroon btn-flat btn-sm" title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																		<?php endif ?>
																	<?php endif ?>
																</td>
																<td ><a href="<?= site_url("program_bantuan/detail_clear/{$item['id']}")?>"><?= $item['nama'] ?></a></td>
																<td class="padat"><?= $item['asaldana']?></td>
																<td class="padat"><?= $item['jml_peserta']?></td>
																<td class="padat"><?= fTampilTgl($item['sdate'], $item['edate']); ?></td>
																<td class="padat"><?= $sasaran[$item['sasaran']]?></td>
																<td class="padat"><?= $item['status'] ?></td>
															</tr>
														<?php endforeach; ?>
													</tbody>
												</table>
											</div>
										</div>
									</div>
									<?php $this->load->view('global/paging'); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<?php $this->load->view('global/confirm_delete'); ?>
<?php $this->load->view('global/konfirmasi'); ?>

<?php include 'donjo-app/views/program_bantuan/impor.php'; ?>
