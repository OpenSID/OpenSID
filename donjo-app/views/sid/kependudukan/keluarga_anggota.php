<style>
.input-sm {
	padding: 4px 4px;
}

.tabel-info, td {
	height: 30px;
	padding: 5px;
	word-wrap: break-word;
}

</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Daftar Anggota Keluarga</h1>
		<ol class="breadcrumb">
			<li><a href="<?=site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('keluarga/clear')?>"> Daftar Keluarga</a></li>
			<li class="active">Daftar Anggota Keluarga</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<a href="<?= site_url("keluarga/ajax_add_anggota/$p/$o/$kk")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Tambah Anggota Keluarga" title="Tambah Anggota Dari Penduduk Yang Sudah Ada" class="btn btn-social btn-flat btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class='fa fa-plus'></i> Tambah Anggota</a>
						<a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform','<?= site_url("keluarga/delete_all_anggota/$p/$o/$kk")?>')" class="btn btn-social btn-flat	btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
						<a href="<?= site_url("keluarga/kartu_keluarga/$p/$o/$kk")?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-book"></i> Kartu Keluarga</a>
						<a href="<?=site_url("keluarga/index/$p/$o")?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar Keluarga">
							<i class="fa fa-arrow-circle-left "></i>Kembali Ke Daftar Keluarga
						</a>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="table-responsive">
									<table class="table table-bordered table-striped table-hover tabel-info">
										<tbody>
											<tr>
												<td width="15%">Nomor Kartu Keluarga (KK)</td>
												<td> : <?= $kepala_kk['no_kk']?></td>
											</tr>
											<tr>
												<td>Kepala Keluarga</td>
												<td> : <?= $kepala_kk['nama']?></td>
											</tr>
											<tr>
												<td>Alamat</td>
												<td> : <?= $kepala_kk['alamat_wilayah']?></td>
											</tr>
											<tr>
												<td>
													<?php if($program['programkerja']): ?>
														<a href="<?= site_url("program_bantuan/peserta/2/$kepala_kk[no_kk]")?>" target="_blank">Program Bantuan</a>
													<?php else: ?>
														Program Bantuan
													<?php endif; ?>
												</td>
												<td> :
													<?php if($program['programkerja']): ?>
														<?php foreach ($program['programkerja'] as $item): ?>
															<a href="<?= site_url("program_bantuan/data_peserta/$item[peserta_id]")?>" target="_blank"><span class="label label-success"><?= $item['nama']?></span>&nbsp;</a>
														<?php endforeach; ?>
													<?php else: ?>
														-
													<?php endif; ?>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
								<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
									<form id="mainform" name="mainform" action="" method="post">
										<div class="row">
											<div class="col-sm-12">
												<div class="table-responsive">
													<table id="tabel2" class="table table-bordered dataTable table-hover nowrap">
														<thead class="bg-gray disabled color-palette">
															<tr>
																<th width="1%"><input type="checkbox" class="checkall"/></th>
																<th width="1%">No</th>
																<th width="5%">Aksi</th>
																<th>NIK</th>
																<th>Nama</th>
																<th>Tanggal Lahir</th>
																<th>Jenis Kelamin</th>
																<th>Hubungan</th>
															</tr>
														</thead>
														<tbody>
															<?php foreach ($main as $key => $data): ?>
																<tr>
																	<td><input type="checkbox" name="id_cb[]" value="<?= $data['id']?>" /></td>
																	<td class="text-center"><?= $key+1;?></td>
																	<td class="text-center" nowrap>
																		<a href="<?= site_url("penduduk/form/$p/$o/$data[id]")?>" class="btn bg-orange btn-flat btn-sm" title="Ubah Biodata Penduduk"><i class="fa fa-edit"></i></a>
																		<a href="#" data-href="<?= site_url("keluarga/delete_anggota/$p/$o/$kk/$data[id]")?>" class="btn bg-purple btn-flat btn-sm" title="Pecah KK" data-toggle="modal" data-target="#confirm-status"><i class="fa fa-cut"></i></a>
																		<?php if ($data['kk_level']!=0): ?>
																			<a href="<?= site_url("keluarga/edit_anggota/$p/$o/$kk/$data[id]")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ubah Hubungan Keluarga" title="Ubah Hubungan Keluarga" class="btn bg-navy btn-flat btn-sm"><i class='fa fa-link'></i></a>
																		<?php endif; ?>
																	</td>
																	<td><?= $data['nik']?></td>
																	<td nowrap width="45%"><?= strtoupper($data['nama'])?></td>
																	<td nowrap><?= tgl_indo($data['tanggallahir'])?></td>
																	<td><?= $data['sex']?></td>
																	<td nowrap><?= $data['hubungan']?></td>
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
						<div class='modal fade' id='confirm-status' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
							<div class='modal-dialog'>
								<div class='modal-content'>
									<div class='modal-header'>
										<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
										<h4 class='modal-title' id='myModalLabel'><i class='fa fa-exclamation-triangle text-red'></i> Konfirmasi</h4>
									</div>
									<div class='modal-body btn-info'>
										Apakah Anda yakin ingin memecah Data Keluarga ini?
									</div>
									<div class='modal-footer'>
										<button type="button" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
										<a class='btn-ok'>
											<button type="button" class="btn btn-social btn-flat btn-info btn-sm" id="ok-delete"><i class='fa fa-check'></i> Simpan</button>
										</a>
									</div>
								</div>
							</div>
						</div>
						<div class="modal fade" id="modalBox" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<div class='modal-dialog'>
								<div class='modal-content'>
									<div class='modal-header'>
										<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
										<h4 class='modal-title' id='myModalLabel'></h4>
									</div>
									<div class="fetched-data"></div>
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
