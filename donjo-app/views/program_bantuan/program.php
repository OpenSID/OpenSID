<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0 text-dark">
						Daftar Program Bantuan
					</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?= site_url('hom_sid'); ?>"><i class="fa fa-home"></i> Home</a></li>
						<li class="breadcrumb-item active">Daftar Program Bantuan</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="card card-outline card-info">
					<div class="card-header with-border">
						<a href="<?=site_url('program_bantuan/create')?>" class="btn btn-flat bg-olive btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left" title="Tambah Program Bantuan Baru"><i class="fa fa-plus"></i> Tambah Program Bantuan</a>
						<a href="<?=site_url('program_bantuan/panduan')?>" class="btn btn-flat btn-info btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left" title="Tambah Program Bantuan Baru"><i class="fa fa-question-circle"></i> Panduan</a>
						<?php if ($tampil != 0): ?>
							<a href="<?=site_url('program_bantuan')?>" class="btn btn-flat btn-info btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left" title="Kembali Ke Daftar Program Bantuan"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Daftar Program Bantuan</a>
						<?php endif; ?>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="dataTables_wrapper dt-bootstrap no-footer">
									<div class="row">
										<div class="col-sm-9">
											<form class="form-inline" id="mainform" name="mainform" action="" method="post">
												<select class="form-control form-control-sm" name="sasaran" onchange="formAction('mainform', '<?=site_url('program_bantuan/filter/sasaran')?>')">
													<option value="">Pilih Sasaran</option>
													<?php foreach ($list_sasaran AS $key => $value): ?>
														<option value="<?= $key; ?>" <?= selected($set_sasaran, $key); ?>><?= $value?></option>
													<?php endforeach; ?>
												</select>
											</form>
										</div>
										<div class="col-sm-12">
											<div class="table-responsive">
												<table class="table table-bordered table-striped dataTable table-hover" id="table-program">
													<thead class="bg-gray disabled color-palette">
														<tr>
															<th width="1%">No</th>
															<th width="5%">Aksi</th>
															<th nowrap>Nama Program</th>
															<th>Asal Dana</th>
															<th>Jumlah Peserta</th>
															<th nowrap>Masa Berlaku</th>
															<th>Sasaran</th>
															<th>Status</th>
														</tr>
													</thead>
													<tbody>
														<?php $nomer = $paging->offset; ?>
														<?php foreach ($program as $item): ?>
															<?php $nomer++; ?>
															<tr>
																<td class="text-center"><?= $nomer?></td>
																<td nowrap>
																	<a href="<?= site_url("program_bantuan/detail/$item[id]")?>" class="btn bg-purple btn-flat btn-xs"  title="Rincian"><i class="fa fa-list"></i></a>
																	<a href="<?= site_url("program_bantuan/edit/$item[id]")?>" class="btn bg-orange btn-flat btn-xs"  title="Ubah"><i class="fa fa-edit"></i></a>
																	<?php if ($item['jml_peserta'] != 0): ?>
																		<a href="#" class="btn bg-maroon btn-flat btn-xs disabled"  title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																	<?php endif ?>
																	<?php if ($item['jml_peserta'] == 0): ?>
																		<a href="#" data-href="<?= site_url("program_bantuan/hapus/$item[id]")?>" class="btn bg-maroon btn-flat btn-xs"  title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																	<?php endif ?>
																</td>
																<td nowrap><a href="<?= site_url("program_bantuan/detail/$item[id]")?>"><?= $item["nama"] ?></a></td>
																<td><?= $item['asaldana']?></td>
																<td><?= $item['jml_peserta']?></td>
																<td nowrap><?= fTampilTgl($item["sdate"],$item["edate"]);?></td>
																<td nowrap><?= $sasaran[$item["sasaran"]]?></td>
																<td><?= $item['status'] ?></td>
															</tr>
														<?php endforeach; ?>
													</tbody>
												</table>
											</div>
										</div>
									</div>
									<?php $this->load->view('global/paging');?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<?php $this->load->view('global/confirm_delete');?>
