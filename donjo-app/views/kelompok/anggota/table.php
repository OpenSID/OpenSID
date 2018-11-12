<div class="content-wrapper">
	<section class="content-header">
		<h1>Data Anggota Kelompok</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('kelompok')?>"> Daftar Kelompok</a></li>
			<li class="active">Data Anggota Kelompok</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="mainform" name="mainform" action="" method="post">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-info">
            <div class="box-header with-border">
						<a href="<?= site_url("kelompok/form_anggota/$kel")?>" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Tambah Anggota Kelompok"><i class="fa fa-plus"></i> Tambah Anggota Kelompok</a>
							<a href="<?= site_url("kelompok/cetak_a/$kel")?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  target="_blank"><i class="fa fa-print "></i> Cetak</a>
							<a href="<?= site_url("kelompok/excel_a/$kel")?>" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  target="_blank"><i class="fa fa-download"></i> Unduh</a>
							<a href="<?= site_url()?>kelompok" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left "></i> Kembali Ke Daftar Kelompok</a>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-sm-12">
									<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
										<form id="mainform" name="mainform" action="" method="post">
											<div class="row">
												<div class="col-sm-12">
													<div class="table-responsive">
														<table class="table table-bordered table-striped table-hover">
															<tbody>
															<tr >
																<td style="padding-top : 10px;padding-bottom : 10px; width:20%;" >Nama Kelompok</td>
																<td> : <?= $kelompok['nama']?></td>
															</tr>
															<tr>
																<td style="padding-top : 10px;padding-bottom : 10px;" >Keterangan</td>
																<td> : <?= $kelompok['keterangan']?></td>
															</tr>
															</tbody>
														</table>
													</div>
													<div class="table-responsive">
														<table class="table table-bordered dataTable table-hover nowrap">
															<thead class="bg-gray disabled color-palette">
																<tr>
																	<th><input type="checkbox" id="checkall"/></th>
																	<th>No</th>
																	<th >Aksi</th>
																	<th width="100">NIK</th>
																	<th width="100">Nomor Anggota</th>
																	<th>Nama</th>
																	<th>Alamat</th>
																	<th>Umur (Tahun)</th>
																	<th>Jenis Kelamin</th>
																</tr>
															</thead>
															<tbody>
																<?php foreach ($main as $data): ?>
																<tr>
																	<td><input type="checkbox" name="id_cb[]" value="<?= $data['id']?>" /></td>
																	<td><?= $data['no']?></td>
																	<td nowrap>
																		<a href="<?= site_url("kelompok/form_anggota/$kel/$data[id_penduduk]")?>" class="btn bg-orange btn-flat btn-sm"  title="Ubah Anggota" ><i class="fa fa-edit"></i></a>
																		<a href="#" data-href="<?= site_url("kelompok/delete_a/$kel/$data[id]")?>" class="btn bg-maroon btn-flat btn-sm"  title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																	</td>
																	<td><?= $data['nik']?></td>
																	<td><?= $data['no_anggota']?></td>
																	<td nowrap><?= $data['nama']?></td>
																	<td width="50%"><?= $data['alamat']?></td>
																	<td><?= $data['umur']?></td>
																	<td><?php if ($data['sex']==1): ?>Laki-laki <?php else: ?>Perempuan <?php endif; ?></td>
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
							<div class='modal fade' id='confirm-delete' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
								<div class='modal-dialog'>
									<div class='modal-content'>
										<div class='modal-header'>
											<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
											<h4 class='modal-title' id='myModalLabel'><i class='fa fa-exclamation-triangle text-red'></i> Konfirmasi</h4>
										</div>
										<div class='modal-body btn-info'>
											Apakah Anda yakin ingin menghapus data ini?
										</div>
										<div class='modal-footer'>
											<button type="button" class="btn btn-social btn-flat btn-warning btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
											<a class='btn-ok'>
												<button type="button" class="btn btn-social btn-flat btn-danger btn-sm" id="ok-delete"><i class='fa fa-trash-o'></i> Hapus</button>
											</a>
										</div>
									</div>
								</div>
							</div>
							<div class='modal fade' id='confirm-status' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
								<div class='modal-dialog'>
									<div class='modal-content'>
										<div class='modal-header'>
											<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
											<h4 class='modal-title' id='myModalLabel'><i class='fa fa-exclamation-triangle text-red'></i> Konfirmasi</h4>
										</div>
										<div class='modal-body btn-info'>
											Apakah Anda yakin ingin mengembalikan status data penduduk ini?
										</div>
										<div class='modal-footer'>
											<button type="button" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
											<a class='btn-ok'>
												<button type="button" class="btn btn-social btn-flat btn-info btn-sm" id="ok-status"><i class='fa fa-check'></i> Ya</button>
											</a>
										</div>
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

