<div class="content-wrapper">
	<?php $this->load->view("surat/form/breadcrumb.php"); ?>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<a href="<?=site_url("surat")?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Kembali Ke Daftar Wilayah">
							<i class="fa fa-arrow-circle-left "></i>Kembali Ke Daftar Cetak Surat
						</a>
					</div>
					<div class="box-body">
						<form id="main" name="main" method="POST" class="form-horizontal">
							<div class="form-group">
								<label for="nik"  class="col-sm-3 control-label">NIK / Nama</label>
								<div class="col-sm-6 col-lg-4">
									<select class="form-control required input-sm select2" id="nik" name="nik" style ="width:100%;" onchange="formAction('main')">
										<option value="">--  Cari NIK / Nama Penduduk --</option>
										<?php foreach ($penduduk as $data): ?>
											<option value="<?= $data['id']?>" <?php if ($individu['nik']==$data['nik']): ?>selected<?php endif; ?>>NIK : <?= $data['nik']." - ".$data['nama']?></option>
										<?php endforeach;?>
									</select>
								</div>
							</div>
						</form>
						<form id="validasi" action="<?= $form_action?>" method="POST" target="_blank" class="form-surat form-horizontal">
							<input type="hidden" id="url_surat" name="url_surat" value="<?= $url ?>">
							<input type="hidden" id="url_remote" name="url_remote" value="<?= site_url('surat/nomor_surat_duplikat')?>">
							<?php if ($individu): ?>
								<?php include("donjo-app/views/surat/form/konfirmasi_pemohon.php"); ?>
							<?php	endif; ?>
							<div class="row jar_form">
								<label for="nomor" class="col-sm-3"></label>
								<div class="col-sm-8">
									<input class="required" type="hidden" name="nik" value="<?= $individu['id']?>">
								</div>
							</div>
							<div class="form-group">
								<label for="nomor"  class="col-sm-3 control-label">Nomor Surat</label>
								<div class="col-sm-8">
									<input  id="nomor" class="form-control input-sm required" type="text" placeholder="Nomor Surat" name="nomor" value="<?= $surat_terakhir['no_surat_berikutnya'];?>">
									<p class="help-block text-red small"><?= $surat_terakhir['ket_nomor']?><strong><?= $surat_terakhir['no_surat'];?></strong> (tgl: <?= $surat_terakhir['tanggal']?>)</p>
								</div>
							</div>
							<div class="form-group">
								<label for="pangkat_gol"  class="col-sm-3 control-label">Pangkat dan Golongan</label>
								<div class="col-sm-8">
									<input name="pangkat_gol" class="form-control input-sm required" placeholder="Pangkat dan Golongan"></input>
								</div>
							</div>
							<div class="form-group">
								<label for="jabatan1"  class="col-sm-3 control-label">Jabatan/Instansi</label>
								<div class="col-sm-8">
									<input name="jabatan1" class="form-control input-sm required" placeholder="Jabatan/Instansi"></input>
								</div>
							</div>
							<div class="form-group">
								<label for="biaya"  class="col-sm-3 control-label">Tingkat Biaya Perjalanan</label>
								<div class="col-sm-8">
									<input name="biaya" class="form-control input-sm required" placeholder="Tingkat Biaya Perjalanan"></input>
								</div>
							</div>
							<div class="form-group">
								<label for="keperluan"  class="col-sm-3 control-label">Maksud Perjalanan Dinas</label>
								<div class="col-sm-8">
									<textarea name="keperluan" class="form-control input-sm required" placeholder="Maksud Perjalanan Dinas"></textarea>
								</div>
							</div>
							<div class="form-group">
								<label for="tujuan"  class="col-sm-3 control-label">Tempat Tujuan</label>
								<div class="col-sm-8">
									<input name="tujuan" class="form-control input-sm required" placeholder="Tempat Tujuan"></input>
								</div>
							</div>
							<div class="form-group">
								<label for="alat_angkut"  class="col-sm-3 control-label">Alat Angkut Yang Digunakan</label>
								<div class="col-sm-8">
									<input id="alat_angkut" class="form-control input-sm required" placeholder="Alat Angkut Yang Digunakan" name="alat_angkut"></input>
								</div>
							</div>
							<div class="form-group">
								<label for="nama_pengikut"  class="col-sm-3 control-label">Nama Pengikut</label>
								<div class="col-sm-4">
									<input  id="anggota_i" name="anggota_i" class="form-control input-sm " type="text" placeholder="Nama Pengikut I ">
								</div>
								<div class="col-sm-4">
									<input  id="anggota_ii" name="anggota_ii" class="form-control input-sm " type="text" placeholder="Nama Pengikut II ">
								</div>
							</div>
							<div class="form-group">
								<label for="nama_pengikut"  class="col-sm-3 control-label"></label>
								<div class="col-sm-4">
									<input  id="anggota_iii" name="anggota_iii" class="form-control input-sm " type="text" placeholder="Nama Pengikut III ">
								</div>
								<div class="col-sm-4">
									<input  id="anggota_iv" name="anggota_iv" class="form-control input-sm " type="text" placeholder="Nama Pengikut IV ">
								</div>
							</div>
							<div class="form-group">
								<label for="nama_pengikut"  class="col-sm-3 control-label"></label>
								<div class="col-sm-4">
									<input  id="anggota_v" name="anggota_v" class="form-control input-sm " type="text" placeholder="Nama Pengikut V ">
								</div>
								<div class="col-sm-4">
									<input  id="anggota_vi" name="anggota_vi" class="form-control input-sm " type="text" placeholder="Nama Pengikut VI ">
								</div>
							</div>
							<div class="form-group">
								<label for="ket_lain"  class="col-sm-3 control-label">Keterangan Lain</label>
								<div class="col-sm-8">
									<textarea  id="ket_lain" class="form-control input-sm required" placeholder="Keterangan Lain" name="ket_lain"></textarea>
								</div>
							</div>
							<div class="form-group">
								<label for="berlaku_dari"  class="col-sm-3 control-label">Tanggal Berangkat</label>
								<div class="col-sm-3 col-lg-2">
									<div class="input-group input-group-sm date">
										<div class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</div>
										<input title="Pilih Tanggal" id="tgl_mulai" class="form-control input-sm required" name="berlaku_dari" type="text"/>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="berlaku_sampai"class="col-sm-3 control-label">Tanggal Kembali</label>
								<div class="col-sm-3 col-lg-2">
									<div class="input-group input-group-sm date">
										<div class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</div>
										<input title="Pilih Tanggal" id="tgl_akhir" class="form-control input-sm required" name="berlaku_sampai" type="text"/>
									</div>
								</div>
							</div>
							<?php include("donjo-app/views/surat/form/_pamong.php"); ?>
						</form>
					</div>
					<?php include("donjo-app/views/surat/form/tombol_cetak.php"); ?>
				</div>
			</div>
		</div>
	</section>
</div>
