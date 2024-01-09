<div class="content-wrapper">
	<?php $detail = $program[0]; ?>
	<section class="content-header">
		<h1>Peserta Program Bantuan</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('beranda') ?>"><i class="fa fa-home"></i> Beranda</a></li>
			<li><a href="<?= site_url('peserta_bantuan') ?>"> Daftar Program Bantuan</a></li>
			<li><a href="<?= site_url("peserta_bantuan/detail/{$detail['id']}") ?>"> Rincian Program Bantuan</a></li>
			<li class="active">Peserta Program Bantuan</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<a href="<?= site_url('program_bantuan') ?>" class="btn btn-social btn-flat btn-primary btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar Program Bantuan"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Daftar Program Bantuan</a>
						<a href="<?= site_url("peserta_bantuan/detail/{$detail['id']}") ?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Rincian Program Bantuan"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Rincian Program Bantuan</a>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<?php include 'donjo-app/views/program_bantuan/rincian.php'; ?>

								<h5><b>Tambah Peserta Program</b></h5>
								<hr>
								<form id="main" name="main" method="POST" class="form-horizontal">
									<div class="form-group">
										<label class="col-sm-4 col-lg-3 control-label <?php if ($detail['sasaran'] != 1) {
                                            echo 'no-padding-top';
                                        } ?>" for="nik">Cari <?= $detail['judul_cari_peserta'] ?></label>
										<div class="col-sm-9">
											<select class="form-control input-sm required" id="nik_bantuan" name="nik" onchange="formAction('main')" data-bantuan="<?= $program[0]['id'] ?>" data-sasaran="<?= $program[0]['sasaran'] ?>" style="width:100%">
												<option value="">-- Silakan Masukan <?= $detail['judul_cari_peserta'] ?> --</option>
												<?php if ($individu['nik']) : ?>
													<option value="<?= $individu['id'] ?>" selected><?= 'NIK: ' . $individu['nik'] . ' - ' . $individu['nama'] . ' - ' . $individu['alamat_wilayah'] ?></option>
												<?php endif; ?>
											</select>
										</div>
									</div>
									<hr>
									<?php if ($individu['nik']) : ?>
										<div class="row">
											<div class="col-sm-6">
												<div class="box box-info box-solid">
													<div class="box-header with-border">
														<i class="fa fa-user"></i>
														<h3 class="box-title">Konfirmasi Peserta</h3>
													</div>
													<div class="box-body">
														<?php include 'donjo-app/views/program_bantuan/konfirmasi_peserta.php'; ?>
													</div>
												</div>
											</div>
								</form>
								<div class="col-sm-6">
									<div class="box box-success box-solid">
										<div class="box-header with-border">
											<i class="fa fa-credit-card"></i>
											<h3 class="box-title">Identitas Pada Kartu Peserta</h3>
										</div>
										<form id="validasi" action="<?= $form_action ?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
											<div class="box-body">
												<input name="peserta" type="hidden" value="<?= $individu['id_peserta'] ?>">
												<input name="kartu_id_pend" type="hidden" value="<?= $individu['id'] ?>">
												<div class="form-group">
													<label for="no_id_kartu" class="col-sm-4 col-lg-4 control-label">Nomor Kartu Peserta</label>
													<div class="col-sm-8">
														<input id="no_id_kartu" class="form-control input-sm nama_terbatas required" type="text" placeholder="Nomor Kartu Peserta" name="no_id_kartu" maxlength="36">
													</div>
												</div>
												<div class="form-group">
													<label for="jenis_keramaian" class="col-sm-4 col-lg-4 control-label">Gambar Kartu Peserta</label>
													<div class="col-sm-8">
														<div class="input-group input-group-sm ">
															<input type="text" class="form-control" id="file_path">
															<input type="file" class="hidden" id="file" name="file" accept=".jpg,.jpeg,.png">
															<span class="input-group-btn">
																<button type="button" class="btn btn-info btn-flat" id="file_browser"><i class="fa fa-search"></i> Browse</button>
															</span>
														</div>
														<span class="help-block"><code> Kosongkan jika tidak ingin mengunggah gambar</code></span>
													</div>
												</div>
												<div class="form-group">
													<label for="kartu_nik" class="col-sm-4 col-lg-4 control-label">NIK</label>
													<div class="col-sm-8">
														<input id="kartu_nik" class="form-control input-sm required nik" type="text" placeholder="Nomor NIK Peserta" name="kartu_nik" value="<?= $individu['kartu_nik'] ?>">
													</div>
												</div>
												<div class="form-group">
													<label for="kartu_nama" class="col-sm-4 col-lg-4 control-label">Nama</label>
													<div class="col-sm-8">
														<input id="kartu_nama" class="form-control input-sm required nama" type="text" maxlength="100" placeholder="Nama Peserta" name="kartu_nama" value="<?= $individu['nama'] ?>">
													</div>
												</div>
												<div class="form-group">
													<label for="kartu_tempat_lahir" class="col-sm-4 col-lg-4 control-label">Tempat Lahir</label>
													<div class="col-sm-8">
														<input id="kartu_tempat_lahir" class="form-control input-sm alamat required" type="text" placeholder="Tempat Lahir" name="kartu_tempat_lahir" maxlength="100" value="<?= $individu['tempatlahir'] ?>">
													</div>
												</div>
												<div class="form-group">
													<label for="kartu_tanggal_lahir" class="col-sm-4 col-lg-4 control-label">Tanggal Lahir</label>
													<div class="col-sm-8">
														<div class="input-group input-group-sm date">
															<div class="input-group-addon">
																<i class="fa fa-calendar"></i>
															</div>
															<input class="form-control input-sm pull-right required" id="tgl_1" name="kartu_tanggal_lahir" placeholder="Tgl. Lahir" type="text" value="<?= tgl_indo_out($individu['tanggallahir']) ?>">
														</div>
													</div>
												</div>
												<div class="form-group">
													<label for="kartu_alamat" class="col-sm-4 col-lg-4 control-label">Alamat</label>
													<div class="col-sm-8">
														<input id="kartu_alamat" class="form-control input-sm alamat required" type="text" placeholder="Alamat" name="kartu_alamat" maxlength="200" value="<?= $individu['alamat_wilayah'] ?>">
													</div>
												</div>
											</div>
											<div class="box-footer">
												<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
												<button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
											</div>
										</form>
									</div>
								</div>
							</div>
						<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
</div>
</section>
</div>