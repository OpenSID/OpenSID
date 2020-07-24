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
						<form action="" id="main" name="main" method="POST" class="form-horizontal">
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
								<label class="col-sm-3 control-label">IDENTITAS / DETAIL KENDARAAN (Sesuai BPKP)<br></label>
							</div>

							<div class="form-group">
								<div class="col-sm-3">
									<label for="merk">Merk/Type</label>
									<input name="merk" class="form-control input-sm required" placeholder="Merk/Type Kendaraan">
								</div>
								<div class="col-sm-3">
									<label for="tahun_pembuatan">Tahun Pembuatan</label>
									<input name="tahun_pembuatan" class="form-control input-sm required" placeholder="Tahun Pembuatan">
								</div>
								<div class="col-sm-3">
									<label for="warna">Warna Kendaraan</label>
									<input name="warna" class="form-control input-sm required" placeholder="Warna Kendaraan">
								</div>
							</div>

							<div class="form-group">
								<div class="col-sm-3">
									<label for="nopol">Nomor Polisi</label>
									<input name="nopol" class="form-control input-sm required" placeholder="Nomor Polisi">
								</div>
								<div class="col-sm-3">
									<label for="nosin">Nomor Mesin</label>
									<input name="nosin" class="form-control input-sm required" placeholder="Nomor Mesin">
								</div>
								<div class="col-sm-3">
									<label for="noka">Nomor Rangka</label>
									<input name="noka" class="form-control input-sm required" placeholder="Nomor Rangka">
								</div>
							</div>

							<div class="form-group">
								<div class="col-sm-3">
									<label for="bpkb">Nomor BPKB</label>
									<input name="bpkb" class="form-control input-sm required" placeholder="Nomor BPKB">
								</div>
								<div class="col-sm-3">
									<label for="bahan_bakar">Bahan Bakar</label>
									<input name="bahan_bakar" class="form-control input-sm required" placeholder="Bahan Bakar">
								</div>
								<div class="col-sm-3">
									<label for="isi_silinder">Isi Silinder</label>
									<input name="isi_silinder" class="form-control input-sm required" placeholder="Isi Silinder (dalam CC)">
								</div>
							</div>

							<div class="form-group">
								<div class="col-sm-3">
									<label for="atas_nama">Atas Nama</label>
									<input name="atas_nama" class="form-control input-sm required" placeholder="Atas Nama">
								</div>
							</div>

							<div class="form-group">
								<div class="bg-info col-sm-12"><br></div>
							</div>

							<div class="form-group">
								<label for="peruntukan_surat"  class="col-sm-3 control-label">Keperluan Pembuatan Surat</label>
								<div class="col-sm-8">
									<textarea  id="peruntukan_surat" class="form-control input-sm required" placeholder="Untuk Keperluan" name="peruntukan_surat"></textarea>
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
