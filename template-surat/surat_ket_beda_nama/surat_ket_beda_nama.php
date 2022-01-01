<div class="content-wrapper">
	<?php $this->load->view("surat/form/breadcrumb.php"); ?>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border tdk-permohonan tdk-periksa">
						<a href="<?= site_url("surat") ?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar Wilayah">
							<i class="fa fa-arrow-circle-left "></i>Kembali Ke Daftar Cetak Surat
						</a>
					</div>
					<div class="box-body">
						<form id="main" name="main" method="POST" class="form-horizontal">
							<div class="col-md-12">
								<?php include("donjo-app/views/surat/form/_cari_nik.php"); ?>
							</div>
						</form>
						<form id="validasi" action="<?= $form_action ?>" method="POST" target="_blank" class="form-surat form-horizontal">
							<input type="hidden" id="url_surat" name="url_surat" value="<?= $url ?>">
							<input type="hidden" id="url_remote" name="url_remote" value="<?= site_url('surat/nomor_surat_duplikat') ?>">
							<div class="col-md-12">
								<div class="row jar_form">
									<label for="nomor" class="col-sm-3"></label>
									<div class="col-sm-8">
										<input class="required" type="hidden" name="nik" value="<?= $individu['id'] ?>">
									</div>
								</div>
								<?php if ($individu) : ?>
									<?php include("donjo-app/views/surat/form/konfirmasi_pemohon.php"); ?>
								<?php endif; ?>
								<?php include("donjo-app/views/surat/form/nomor_surat.php"); ?>
								<div class="form-group subtitle_head">
									<label class="col-sm-3 text-right"><strong>IDENTITAS KEDUA</strong></label>
								</div>
								<div class="form-group">
									<label for="kartu" class="col-sm-3 control-label">Identitas dalam (nama kartu)</label>
									<div class="col-sm-8">
										<input type="text" name="kartu" class="form-control input-sm required <?= jecho($cek_anjungan['keyboard'] == 1, TRUE, 'kbvtext'); ?>" placeholder="Nama Kartu Identitas"></input>
									</div>
								</div>
								<div class="form-group">
									<label for="identitas" class="col-sm-3 control-label">Nomor Identitas</label>
									<div class="col-sm-4">
										<input id="identitas" class="form-control input-sm required <?= jecho($cek_anjungan['keyboard'] == 1, TRUE, 'kbvnumber'); ?>" type="text" placeholder="Nomor Identitas" name="identitas">
									</div>
								</div>
								<div class="form-group">
									<label for="nama" class="col-sm-3 control-label">Nama</label>
									<div class="col-sm-8">
										<input type="text" name="nama" class="form-control input-sm required <?= jecho($cek_anjungan['keyboard'] == 1, TRUE, 'kbvtext'); ?>" placeholder="Nama"></input>
									</div>
								</div>
								<div class="form-group">
									<label for="ttl" class="col-sm-3 control-label">Tempat Tanggal Lahir</label>
									<div class="col-sm-4">
										<input id="tempatlahir" class="form-control input-sm required <?= jecho($cek_anjungan['keyboard'] == 1, TRUE, 'kbvtext'); ?>" type="text" placeholder="Tempat Lahir" name="tempatlahir">
									</div>
									<div class="col-sm-4 col-lg-2">
										<div class="input-group input-group-sm date">
											<div class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</div>
											<input title="Pilih Tanggal" title="Pilih Tanggal" class="form-control input-sm datepicker required" name="tanggallahir" type="text" />
										</div>
									</div>
								</div>
								<div class="form-group">
									<label for="sex" class="col-sm-3 control-label">Jenis Kelamin</label>
									<div class="col-sm-8">
										<input type="text" name="sex" class="form-control input-sm required <?= jecho($cek_anjungan['keyboard'] == 1, TRUE, 'kbvtext'); ?>" placeholder="Jenis Kelamin"></input>
									</div>
								</div>
								<div class="form-group">
									<label for="alamat" class="col-sm-3 control-label">Alamat</label>
									<div class="col-sm-8">
										<textarea name="alamat" class="form-control input-sm required <?= jecho($cek_anjungan['keyboard'] == 1, TRUE, 'kbvtext'); ?>" placeholder="Alamat"></textarea>
									</div>
								</div>
								<div class="form-group">
									<label for="agama" class="col-sm-3 control-label">Agama</label>
									<div class="col-sm-8">
										<input type="text" name="agama" class="form-control input-sm required <?= jecho($cek_anjungan['keyboard'] == 1, TRUE, 'kbvtext'); ?>" placeholder="Agama"></input>
									</div>
								</div>
								<div class="form-group">
									<label for="pekerjaan" class="col-sm-3 control-label">Pekerjaan</label>
									<div class="col-sm-8">
										<input type="text" name="pekerjaan" class="form-control input-sm required <?= jecho($cek_anjungan['keyboard'] == 1, TRUE, 'kbvtext'); ?>" placeholder="Pekerjaan"></input>
									</div>
								</div>
								<div class="form-group">
									<label for="keterangan" class="col-sm-3 control-label">Keterangan</label>
									<div class="col-sm-8">
										<textarea name="keterangan" class="form-control input-sm required <?= jecho($cek_anjungan['keyboard'] == 1, TRUE, 'kbvtext'); ?>" placeholder="Keterangan"></textarea>
									</div>
								</div>
								<div class="form-group">
									<label for="perbedaan" class="col-sm-3 control-label">Perbedaan</label>
									<div class="col-sm-8">
										<input type="text" name="perbedaan" class="form-control input-sm required <?= jecho($cek_anjungan['keyboard'] == 1, TRUE, 'kbvtext'); ?>" placeholder="Perbedaan"></input>
									</div>
								</div>
								<div class="form-group subtitle_head tdk-permohonan tdk-periksa">
									<label class="col-sm-3 text-right"><strong>PENANDA TANGAN</strong></label>
								</div>
								<?php include("donjo-app/views/surat/form/_pamong.php"); ?>
							</div>
						</form>
					</div>
					<?php include("donjo-app/views/surat/form/tombol_cetak.php"); ?>
				</div>
			</div>
		</div>
	</section>
</div>