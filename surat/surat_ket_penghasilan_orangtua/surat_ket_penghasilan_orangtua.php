<style>
	.error {
		color: #dd4b39;
	}
</style>

<div class="content-wrapper">
	<section class="content-header">
		<h1>Surat Keterangan Penghasilan Orang Tua</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_desa/about')?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="<?= site_url('surat')?>"> Daftar Cetak Surat</a></li>
			<li class="active">Surat Keterangan Penghasilan Orang Tua</li>
		</ol>
	</section>
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
								<label for="keperluan"  class="col-sm-3 control-label">Nomor Induk Siswa/Mahasiswa</label>
								<div class="col-sm-8">
									<textarea name="nomor_induk" class="form-control input-sm required" placeholder="Nomor Induk Siswa/Mahasiswa"></textarea>
								</div>
							</div>
							<div class="form-group">
								<label for="keterangan"  class="col-sm-3 control-label">Sekolah/Perguruan Tinggi</label>
								<div class="col-sm-8">
									<textarea  id="sekolah_pt" class="form-control input-sm required" placeholder="Sekolah/Perguruan Tinggi" name="sekolah_pt"></textarea>
								</div>
							</div>
							<div class="form-group">
								<label for="keperluan"  class="col-sm-3 control-label">Kelas/Semester</label>
								<div class="col-sm-8">
									<textarea name="kelas_semester" class="form-control input-sm required" placeholder="Kelas/Semester"></textarea>
								</div>
							</div>
							<div class="form-group">
								<label for="jurussan"  class="col-sm-3 control-label">Jurusan/Fakultas/Prodi</label>
								<div class="col-sm-8">
									<textarea name="jurusan" class="form-control input-sm required" placeholder="Jurusan/Fakultas/Prodi"></textarea>
								</div>
							</div>

							<div class="form-group">
								<label for="keperluan"  class="col-sm-3 control-label">Jumlah Penghasilan Ayah</label>
								<div class="col-sm-8">
									<input type="text" id="rupiah_ayah" name="hasil_ayah" class="rupiah form-control input-sm required" placeholder="Penghasilan Ayah dalam Rupiah tanpa sen"></input>
								</div>
							</div>
							<div class="form-group">
								<label for="keperluan"  class="col-sm-3 control-label">Jumlah Penghasilan Ibu</label>
								<div class="col-sm-8">
									<input type="text" id="rupiah_ibu" name="hasil_ibu" class="rupiah form-control input-sm required" placeholder="Penghasilan Ibu dalam Rupiah tanpa sen"></input>
								</div>
							</div>

							<div class="form-group">
								<label for="berlaku_dari"  class="col-sm-3 control-label">Berlaku Dari - Sampai</label>
								<div class="col-sm-3 col-lg-2">
									<div class="input-group input-group-sm date">
										<div class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</div>
										<input title="Pilih Tanggal" id="tgl_mulai" class="form-control input-sm required" name="berlaku_dari" type="text"/>
									</div>
								</div>
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
