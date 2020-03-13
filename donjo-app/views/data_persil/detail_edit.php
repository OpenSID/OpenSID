
<?= $no= 1; ?>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Rincian Data C-DESA <?=ucwords($this->setting->sebutan_desa)?> <?= $desa["nama_desa"];?></h1>
		<ol class="breadcrumb">
			<li><a href="<?=site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?=site_url('data_persil/clear')?>"> Daftar C-DESA</a></li>
			<li class="active">Rincian Data C-DESA</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-3">
				<?php $this->load->view('data_persil/menu_kiri.php')?>
			</div>
			<div class="col-md-9">
				<div class="box box-info">
					<div class="box-header with-border">
						<a href="<?= site_url("data_persil/clear") ?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar Penduduk"><i class="fa fa-arrow-circle-left"></i>Kembali</a>
						<?php if ($c_desa_detail['jenis_pemilik'] == '2'): ?>
							<a href="<?= site_url("data_persil/create_ext/add/".$c_desa_detail['id'])?>" class="btn btn-social btn-flat btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Tambah Data"><i class="fa fa-plus"></i> Persil</a>
						<?php else: ?>
							<a href="<?= site_url("data_persil/create/add/".$c_desa_detail['id'])?>" class="btn btn-social btn-flat btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Tambah Data"><i class="fa fa-plus"></i> Persil</a>
						<?php endif; ?>
						<a href="<?= site_url("data_persil/form_c_desa/".$c_desa_detail['id'])?>" class="btn btn-social btn-flat bg-purple btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Data" target="_blank"><i class="fa fa-print"></i>Cetak</a>
					</div>

					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="box-body">
									<div class="row">
										<div class="col-md-12">
											<div class="box-header with-border">
												<h3 class="box-title">Pengelolaan Data C-DESA <?= $desa['nama_desa'];?></h3>
											</div>
										</div>
										<div class="col-md-12">
											<h4 colspan="3" class="bg-aqua"><strong>DATA PEMILIK</strong></h4>
											
										<form name='mainform' action="" method="POST"  id="validasi" class="form-horizontal">
											<div class="box-body">
												<?php if ($c_desa_detail['jenis_pemilik'] != '2'): ?>
													<?php if($c_desa_detail["c_desa"]): ?>
														<input  id="id_c_desa" name="id_c_desa" type="hidden" value="<?= strtoupper($c_desa_detail["id"])?>" >
													<?php else : ?>
														<input  id="id_pend" name="id_pend" type="hidden" value="<?= strtoupper($c_desa_detail["id_pend"])?>" >
													<?php endif ?>
													<div class="form-group">
														<label class="col-sm-3 control-label">Nama Penduduk</label>
														<div class="col-sm-8">
															<input  class="form-control input-sm" type="text" placeholder="Nama Pemilik" value="<?= strtoupper($c_desa_detail["namapemilik"])?>" disabled >
										
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-3 control-label">NIK Pemilik</label>
														<div class="col-sm-8">
															<input  class="form-control input-sm" type="text" placeholder="NIK Pemilik" value="<?= $c_desa_detail["nik"]?>" disabled >
														</div>
													</div>
													<div class="form-group">
														<label for="alamat"  class="col-sm-3 control-label">Alamat Pemilik</label>
														<div class="col-sm-8">
															<textarea  class="form-control input-sm" placeholder="Alamat Pemilik" disabled> RT: <?= $c_desa_detail["rt"]?> RW: <?= $c_desa_detail["rw"]?> Dusun <?= strtoupper($c_desa_detail["dusun"])?></textarea>
														</div>
													</div>

												<?php else : ?>
													<?php if($c_desa_detail["c_desa"]): ?>
														<input  id="id_c_desa" name="id_c_desa" type="hidden" value="<?= strtoupper($c_desa_detail["id"])?>" >
													<?php else : ?>
														<input  type="hidden" id="id_persil" name="id_persil"  value="<?= strtoupper($c_desa_detail["id"])?>" >
													<?php endif ?>
													<div class="form-group">

														<label class="col-sm-3 control-label">Nama Pemilik</label>
														<div class="col-sm-8">
															<input  class="form-control input-sm" type="text" placeholder="Nama Pemilik" value="<?= strtoupper($c_desa_detail["namapemilik"])?>" disabled >
										
														</div>
													</div>
													<div class="form-group">
														<label for="alamat"  class="col-sm-3 control-label">Alamat Pemilik</label>
														<div class="col-sm-8">
															<textarea  class="form-control input-sm" placeholder="Alamat Pemilik" disabled> RT: <?= $c_desa_detail["rt"]?> RW: <?= $c_desa_detail["rw"]?> Dusun <?= strtoupper($c_desa_detail["dusun"])?></textarea>
														</div>
													</div>


												<?php endif; ?>
												<div class="form-group">
														<label for="c_desa"  class="col-sm-3 control-label">C-DESA</label>
														<div class="col-sm-8">
															<input  id="c_desa" name="c_desa" class="form-control input-sm angka" type="text" placeholder="Nomor C-DESA" value="<?= sprintf("%04s", $c_desa_detail["c_desa"])?>" >
										
														</div>
												</div>
											</div>
											<div class="box-footer">
												<div class="col-xs-12">
													<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
													<button type="submit" onclick="$('#'+'validasi').attr('action','<?= site_url('data_persil/simpan_c_desa')?>');$('#'+'validasi').submit();" class="btn btn-social btn-flat btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
												</div>
											</div>
										</form>
										<div class="box-body">
											<div class="table-responsive">
												<table class="table table-bordered table-striped dataTable table-hover">
													<thead class="bg-gray disabled color-palette">
														<tr>
															<th>No</th>
															<th>Aksi</th>
															<th>Nomor Persil</th>
															<th>Tipe Persil</th>
															<th>Kelas Desa</th>
															<th>Luas Tanah</th>
															<th>Pajak</th>
															<th>Keterangan</th>
															<th>Peruntukan</th>
															<th>No. SPPT</th>
															<th>Lokasi</th>
														</tr>
													</thead>
													<tbody>
														<?php foreach ($persil_detail as $item): ?>
															<tr>
																<td><?= $no++ ?></td>
																<td nowrap><a href="<?= site_url("data_persil/detail_persil/".$item["id"])?>" class="btn bg-purple btn-flat btn-sm"  title="Rincian"><i class="fa fa-bars"></i></a>
																	<?php if ($item['jenis_pemilik'] == '2'): ?>
																		<a href="<?= site_url("data_persil/create_ext/edit/".$item["id"])?>" class="btn bg-orange btn-flat btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a>
																		<?php else: ?>
																			<a href="<?= site_url("data_persil/create/edit/".$item["id"])?>" class="btn bg-orange btn-flat btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a>
																		<?php endif; ?>
																		<a href="#" data-href="<?= site_url("data_persil/hapus_persil/".$item["id"])?>" class="btn bg-maroon btn-flat btn-sm"  title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																	</td><td><?= $item["nopersil"] ?></td>
																	<td><?= $persil_jenis[$item["persil_jenis_id"]]['nama']?></td>
																	<td><?= $persil_kelas[$item["kelas"]]['kode']?></td>
																	<td><?= $item["luas"] ?></td>
																	<td><?= $item["pajak"] ?></td>
																	<td><?= $item["keterangan"] ?></td>
																	<td><?= $persil_peruntukan[$item["persil_peruntukan_id"]][0]?><br/><?= $persil_peruntukan[$item["persil_peruntukan_id"]][1]?></td>
																	<td><?= $item["no_sppt_pbb"] ?></td>
																	<td><?= ($item["lokasi"])?: "RT: ".$item["rt"]." RW: ".$item["rw"]." Dusun ".strtoupper($item["dusun"]) ?></td>
																</tr>
															<?php endforeach; ?>
														</tbody>
													</table>
												</div>
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
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

