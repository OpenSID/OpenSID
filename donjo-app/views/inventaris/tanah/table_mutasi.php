<div class="content-wrapper">
	<section class="content-header">
		<h1>Daftar Mutasi Inventaris Tanah</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Daftar Mutasi Inventaris Tanah</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="mainformexcel" name="mainformexcel" action="" method="post" class="form-horizontal">
			<div class="row">
				<div class="col-md-3">
          <?php	$this->load->view('inventaris/tanah/menu_kiri.php')?>
				</div>
				<div class="col-md-9">
					<div class="box box-info">
						<div class="box-body">
							<div class="row">
								<div class="col-sm-12">
									<div class="row">
										<div class="col-sm-12">
											<div class="table-responsive">
												<table id="tabel4" class="table table-bordered dataTable table-hover">
													<thead class="bg-gray">
														<tr>
															<th class="text-center" >No</th>
															<th class="text-center" >Aksi</th>
															<th class="text-center">Nama Barang</th>
															<th class="text-center">Kode Barang</th>
															<th class="text-center">Tahun Pengadaan</th>
															<th class="text-center">Tanggal Mutasi</th>
															<th class="text-center">Jenis Mutasi</th>
															<th class="text-center" width="300px">Keterangan</th>
														</tr>
													</thead>
													<tbody>
														<?php foreach ($main as $data): ?>
															<tr>
																<td></td>
																<td nowrap>
																	<?php if ($data->status == "0"): ?>
																		<a href="<?= base_url('index.php/inventaris_tanah/form_mutasi/'.$data->id); ?>" title="Mutasi Data" class="btn bg-olive btn-flat btn-sm"><i class="fa fa-external-link-square"></i></a>
																	<?php endif; ?>
																	<a href="<?= base_url('index.php/inventaris_tanah/view_mutasi/'.$data->id); ?>" title="Lihat Data" class="btn bg-info btn-flat btn-sm"><i class="fa fa-eye"></i></a>
																	<a href="<?= base_url('index.php/inventaris_tanah/edit_mutasi/'.$data->id); ?>" title="Edit Data"  class="btn bg-orange btn-flat btn-sm"><i class="fa fa-edit"></i> </a>
																	<a href="#" data-href="<?= site_url("api_inventaris_tanah/delete_mutasi/$data->id")?>" class="btn bg-maroon btn-flat btn-sm"  title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																</td>
																<td><?= $data->nama_barang;?></td>
																<td><?= $data->kode_barang;?></td>
																<td><?= $data->tahun_pengadaan;?></td>
																<td nowrap><?= date('d M Y',strtotime($data->tahun_mutasi));?></td>
																<td><?= $data->jenis_mutasi;?></td>
																<td><?= $data->keterangan;?></td>
															</tr>
														<?php endforeach; ?>
													</tbody>
												</table>
											</div>
										</div>
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
							<div id="unduhBox" class="modal fade" role="dialog" style="padding-top:30px;">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal">&times;</button>
											<h4 class="modal-title">Unduh Inventaris</h4>
										</div>
										<form action="" target="_blank" class="form-horizontal" method="get" >
											<div class="modal-body">
												<div class="form-group">
													<label class="col-sm-2 control-label required" style="text-align:left;" for="nama_barang">Tahun</label>
													<div class="col-sm-9">
														<select name="tahun" id="tahun" class="form-control select2 input-sm" style="width:100%;">
															<option value="1">Semua Tahun</option>
															<?php for ($i=date("Y"); $i>=date("Y")-30; $i--): ?>
																<option value="<?= $i ?>"><?= $i ?></option>
															<?php endfor; ?>
														</select>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-2 control-label required" style="text-align:left;" for="penandatangan">Penandatangan</label>
													<div class="col-sm-9">
														<select name="penandatangan" id="penandatangan" class="form-control input-sm">
															<?php foreach ($pamong AS $data): ?>
																<option value="<?= $data['pamong_id']?>" data-jabatan="<?= trim($data['jabatan'])?>"
																	<?= (strpos(strtolower($data['jabatan']),'Kepala Desa') !== false) ? 'selected' : '' ?>>
																	<?= $data['pamong_nama']?>(<?= $data['jabatan']?>)
																</option>
															<?php endforeach; ?>
														</select>
													</div>
												</div>
											</div>
											<div class="modal-footer">
												<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
												<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="form_download" name="form_download" data-dismiss="modal"><i class='fa fa-check'></i> Simpan</button>
											</div>

										</form>
									</div>
								</div>
							</div>
							<div id="cetakBox" class="modal fade" role="dialog" style="padding-top:30px;">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal">&times;</button>
											<h4 class="modal-title">Cetak Inventaris</h4>
										</div>
										<form action="" target="_blank" class="form-horizontal" method="get">
											<div class="modal-body">
												<div class="form-group">
													<label class="col-sm-2 control-label required" style="text-align:left;" for="tahun_pdf">Tahun</label>
													<div class="col-sm-9">
														<select name="tahun_pdf" id="tahun_pdf" class="form-control select2 input-sm" style="width:100%;">
															<option value="1">Semua Tahun</option>
															<?php for ($i = date("Y"); $i >= date("Y")-30; $i--): ?>
																<option value="<?= $i ?>"><?= $i ?></option>
															<?php endfor; ?>
														</select>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-2 control-label required" style="text-align:left;" for="penandatangan_pdf">Penandatangan</label>
													<div class="col-sm-9">
														<select name="penandatangan_pdf" id="penandatangan_pdf" class="form-control input-sm">
															<?php foreach ($pamong AS $data): ?>
																<option value="<?= $data['pamong_id']?>" data-jabatan="<?= trim($data['jabatan'])?>"
																	<?= (strpos(strtolower($data['jabatan']),'Kepala Desa') !== false) ? 'selected' : '' ?>>
																	<?= $data['pamong_nama']?>(<?= $data['jabatan']?>)
																</option>
															<?php endforeach; ?>
														</select>
													</div>
												</div>
											</div>
											<div class="modal-footer">
												<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
												<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="form_cetak" name="form_cetak"  data-dismiss="modal"><i class='fa fa-check'></i> Simpan</button>
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
<script>
</script>