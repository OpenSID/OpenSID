<div class="content-wrapper">
	<section class="content-header">
		<h1>Dokumen / Kelengkapan Penduduk</h1>
		<ol class="breadcrumb">
			<li><a href="<?=site_url('hom_desa')?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="<?= site_url('penduduk/clear')?>"> Daftar Penduduk</a></li>
			<li class="active">Dokumen / Kelengkapan Penduduk</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<a href="<?= site_url("penduduk/dokumen_form/$penduduk[id]")?>" title="Tambah Dokumen" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Tambah Dokumen" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class='fa fa-plus'></i>Tambah Dokumen</a>
						<a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform','<?= site_url("penduduk/delete_all_dokumen/$penduduk[id]")?>')" class="btn btn-social btn-flat	btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
						<a href="<?= site_url('penduduk/clear')?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa  fa-backward"></i> Kembali Ke Daftar Penduduk</a>
					</div>
					<div class="box-body ">
						<div class="table-responsive">
							<table class="table table-bordered table-striped table-hover">
								<tbody>
									<tr>
										<td style="padding-top : 10px;padding-bottom : 10px; width:30%;" >Nama Penduduk</td><td> : <?= $penduduk['nama']?></td>
									</tr>
									<tr>
										<td style="padding-top : 10px;padding-bottom : 10px;" >NIK</td><td> :  <?= $penduduk['nik']?></td>
									</tr>
									<tr>
										<td style="padding-top : 10px;padding-bottom : 10px;" >Alamat</td><td> : <?= $penduduk['alamat']?> RT/RW :  <?= $penduduk['rt']?>/<?= $penduduk['rw']?> <?= strtoupper($this->setting->sebutan_dusun)?> :  <?= $penduduk['dusun']?> </td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
									<form id="mainform" name="mainform" action="" method="post">
										<div class="row">
											<div class="col-sm-12">
												<div class="table-responsive">
													<table class="table table-bordered table-hover ">
														<thead class="bg-gray disabled color-palette">
															<tr>
																<th><input type="checkbox" id="checkall"></th>
																<th>No</th>
																<th >Aksi</th>
																<th>NIK</th>
																<th>Nama</th>
																<th>Tanggal Lahir</th>
																<th>Jenis Kelamin</th>
																<th>Hubungan</th>
															</tr>
														</thead>
														<tbody>
															<?php  foreach($main as $key => $data): ?>
																<tr>
																	<td><input type="checkbox" name="id_cb[]" value="<?php echo $data['id']?>" ></td>
																	<td><?= $key+1?></td>
																	<td>
																		<a href="<?php echo site_url("penduduk/detail/$p/$o/$data[id]")?>" class="btn btn-info btn-flat btn-sm"  title="Lihat Biodata Penduduk"><i class="fa fa-eye"></i></a>
																		<a href="<?php echo site_url("penduduk/form/$p/$o/$data[id]")?>" class="btn bg-orange btn-flat btn-sm"  title="Ubah Biodata Penduduk"><i class="fa fa-edit"></i></a>
																		<a href="#" data-href="<?php echo site_url("keluarga/delete_anggota/$p/$o/$kk/$data[id]")?>" class="btn bg-purple btn-flat btn-sm"  title="Pecah KK" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-cut"></i></a>
																		<?php if($data['kk_level']!=0){?>
																			<a href="<?php echo site_url("keluarga/edit_anggota/$p/$o/$kk/$data[id]")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ubah Hubungan Keluarga" title="Ubah Hubungan Keluarga" class="btn bg-navy btn-flat btn-sm"><i class='fa fa-exchange'></i></a>
																		<?php }?>
																	</td>
																	<td><?php echo $data['nik']?></td>
																	<td><?php echo strtoupper(unpenetration($data['nama']))?></td>
																	<td><?php echo tgl_indo_out($data['tanggallahir'])?></td>
																	<td><?php echo $data['sex']?></td>
																	<td><?php echo $data['hubungan']?></td>
																</tr>
																<?php  endforeach; ?>
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
										<h4 class='modal-title' id='myModalLabel'><i class='fa fa-text-width text-yellow'></i> Konfirmasi</h4>
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
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

