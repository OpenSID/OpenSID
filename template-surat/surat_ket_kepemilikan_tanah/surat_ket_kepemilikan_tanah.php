<!-- TODO: Pindahkan ke external css -->
<style>
	.error {
		color: #dd4b39;
	}
</style>
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
							<?php include("donjo-app/views/surat/form/_cari_nik.php"); ?>
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
							<?php include("donjo-app/views/surat/form/nomor_surat.php"); ?>

							<div class="form-group subtitle_head" id="detail_kendaraan">
								<label class="col-sm-3 control-label">DETAIL INFORMASI TANAH / LAHAN<br></label>
							</div>

							<div class="form-group">
								<div class="col-sm-3">
									<label for="jenis_tanah">Jenis Tanah</label>
									<select class="form-control input-sm required" name="jenis_tanah" onchange="$('input[name=jenis_tanah_nama]').val($(this).find(':selected').data('jenis_tanahnama'));">
										<option value=""> ->> Pilih Jenis Tanah/Lahan <<- </option>
										<?php foreach ($jenis_tanah as $pilihan => $nama): ?>
											<option value="<?= $pilihan?>" data-jenis_tanahnama="<?= $nama; ?>" <?php if ($pilihan==$_SESSION['post']['jenis_tanah']): ?>selected<?php endif; ?>><?= $nama; ?></option>
										<?php endforeach;?>
									</select>
								</div>
								<div class="col-sm-3">
									<label for="luas_tanah">Luas Tanah</label>
									<input name="luas_tanah" class="form-control input-sm required" placeholder="Luas Tanah (dalam M2)">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-3">
									<label for="bukti_kepemilikan">Bukti Kepemilikan</label>
									<select class="form-control input-sm required" name="bukti_kepemilikan" onchange="$('input[name=bukti_kepemilikan_nama]').val($(this).find(':selected').data('bukti_kepemilikannama'));">
										<option value=""> ->> Pilih Bukti Kepemilikan Tanah <<- </option>
										<?php foreach ($bukti_kepemilikan as $pilihan => $nama): ?>
											<option value="<?= $pilihan?>" data-bukti_kepemilikannama="<?= $nama; ?>" <?php if ($pilihan==$_SESSION['post']['bukti_kepemilikan']): ?>selected<?php endif; ?>><?= $nama; ?></option>
										<?php endforeach;?>
									</select>
								</div>
								<div class="col-sm-3">
									<label for="nomor_kepemilikan">Nomor Bukti Kepemilikan</label>
									<input name="nomor_kepemilikan" class="form-control input-sm required" placeholder="Nomor Bukti Kepemilikan">
								</div>
								<div class="col-sm-3">
									<label for="atas_nama">Atas Nama</label>
									<input name="atas_nama" class="form-control input-sm required" placeholder="Atas Nama">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-3">
									<label for="asal_tanah">Asal Kepemilikan Tanah</label>
									<select class="form-control input-sm required" name="asal_tanah" onchange="$('input[name=asal_tanah_nama]').val($(this).find(':selected').data('asal_tanahnama'));">
										<option value=""> ->> Pilih Asal Kepemilikan Tanah <<- </option>
										<?php foreach ($asal_tanah as $pilihan => $nama): ?>
											<option value="<?= $pilihan?>" data-asal_tanahnama="<?= $nama; ?>" <?php if ($pilihan==$_SESSION['post']['asal_tanah']): ?>selected<?php endif; ?>><?= $nama; ?></option>
										<?php endforeach;?>
									</select>
								</div>
								<div class="col-sm-3">
									<label for="bukti_pendukungg">Bukti Pendukung Kepemilikan</label>
									<input name="bukti_pendukung" class="form-control input-sm required" placeholder="Bukti Pendukung Kepemilikan">
								</div>
							</div>

							<div class="form-group">
								<div class="col-sm-3">
									<label for="utara">Batas Sebelah Utara</label>
									<input name="utara" class="form-control input-sm required" placeholder="Batas Sebelah Utara">
								</div>
								<div class="col-sm-3">
									<label for="timur">Batas Sebelah Timur</label>
									<input name="timur" class="form-control input-sm required" placeholder="Batas Sebelah Timur">
								</div>
								<div class="col-sm-3">
									<label for="selatan">Batas Sebelah Selatan</label>
									<input name="selatan" class="form-control input-sm required" placeholder="Batas Sebelah Selatan">
								</div>
								<div class="col-sm-3">
									<label for="barat">Batas Sebelah Barat</label>
									<input name="barat" class="form-control input-sm required" placeholder="Batas Sebelah Barat">
								</div>
							</div>

							<div class="form-group subtitle_head" id="detail_kendaraan">
								<label class="col-sm-3 control-label">PENANDATANGAN<br></label>
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
