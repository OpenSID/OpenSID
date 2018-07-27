<div class="content-wrapper">
	<section class="content-header">
		<h1>Surat Keterangan Pengantar Rujuk/Cerai</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_desa/about')?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="<?= site_url('surat')?>"> Cetak Surat</a></li>
			<li class="active">Surat Keterangan Pengantar Rujuk/Cerai</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<a href="<?=site_url("surat")?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Kembali Ke Daftar Wilayah">
							<i class="fa fa-arrow-circle-left "></i>Kembali ke Daftar Cetak Surat
           	</a>
					</div>
					<div class="box-body">
						<form action="" id="main" name="main" method="POST" class="form-horizontal">
							<div class="col-md-12">
								<div class="form-group">
									<label for="nik"  class="col-sm-3 control-label">NIK / Nama</label>
									<div class="col-sm-6">
										<select class="form-control  input-sm select2" id="nik" name="nik" style ="width:100%;" onchange="formAction('main')">
											<option value="">--  Cari NIK Penduduk--</option>
											<?php foreach ($penduduk as $data):?>
												<option value="<?= $data['id']?>" <?php if ($individu['nik']==$data['nik']):?>selected<?php endif;?>>NIK :<?= $data['nik']." - ".$data['nama']?></option>
											<?php endforeach;?>
										</select>
									</div>
								</div>
							</div>
						</form>
						<form id="validasi" action="<?= $form_action?>" method="POST" target="_blank" class="form-horizontal">
							<div class="col-md-12">
								<?php if ($individu):?>
									<?php include("donjo-app/views/surat/form/konfirmasi_pemohon.php"); ?>
								<?php	endif;?>
								<div class="form-group">
									<label for="nomor"  class="col-sm-3 control-label">Nomor Surat</label>
									<div class="col-sm-8">
										<input  id="nomor" class="form-control input-sm required" type="text" placeholder="Nomor Surat" name="nomor">
										<input type="hidden" name="nik" value="<?= $individu['id']?>">
										<p class="help-block text-red small">Terakhir: <strong><?= $surat_terakhir['no_surat'];?></strong> (tgl: <?= $surat_terakhir['tanggal']?>)</p>
									</div>
								</div>
								<div class="form-group bg-info" style="padding-top:10px;padding-bottom:5px">
									<label class="col-sm-3 text-right text-red"><strong>DATA PASANGAN :</strong></label>
								</div>
								<div class="form-group">
									<label for="nama_pasangan"  class="col-sm-3 control-label">Nama Lengkap</label>
									<div class="col-sm-8">
										<input type="text"  name="nama_pasangan" class="form-control input-sm required" placeholder="Nama Lengkap Pasangan"></input>
									</div>
								</div>
								<div class="form-group ibu_luar_desa">
									<label for="tempatlahir_pasangan"  class="col-sm-3 control-label">Tempat  / Tanggal Lahir </label>
									<div class="col-sm-5">
										<input class="form-control input-sm" type="text" name="tempatlahir_pasangan" id="tempatlahir_pasanganu" placeholder="Tempat Lahir Pasangan">
									</div>
									<div class="col-sm-3">
										<div class="input-group input-group-sm date">
											<div class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</div>
											<input title="Pilih Tanggal" class="form-control input-sm required" name="tanggallahir_pasangan" id="tgl_1" type="text" placeholder="Tgl. Lahir">
										</div>
									</div>
								</div>
								<div class="form-group">
									<label for="wn_pasangan"  class="col-sm-3 control-label">Warganegara</label>
									<div class="col-sm-8">
										<input type="text"  name="wn_pasangan" class="form-control input-sm required" placeholder="Warganegara Pasangan"></input>
									</div>
								</div>
								<div class="form-group">
									<label for="nama_ayah_pasangan"  class="col-sm-3 control-label">Nama Ayah</label>
									<div class="col-sm-8">
										<input type="text"  name="nama_ayah_pasangan" class="form-control input-sm required" placeholder="Nama Ayah Pasangan"></input>
									</div>
								</div>
								<div class="form-group">
									<label for="agama_pasangan"  class="col-sm-3 control-label">Agama</label>
									<div class="col-sm-8">
										<input type="text"  name="agama_pasangan" class="form-control input-sm required" placeholder="Agama Pasangan"></input>
									</div>
								</div>
								<div class="form-group">
									<label for="pekerjaan_pasangan"  class="col-sm-3 control-label">Pekerjaan</label>
									<div class="col-sm-8">
										<input type="text"  name="pekerjaan_pasangan" class="form-control input-sm required" placeholder="Pekerjaan Pasangan"></input>
									</div>
								</div>
								<div class="form-group">
									<label for="alamat_pasangan"  class="col-sm-3 control-label">Tempat Tinggal</label>
									<div class="col-sm-8">
										<input type="text" name="alamat_pasangan" class="form-control input-sm required" placeholder="Tempat Tinggal Pasangan"></input>
									</div>
								</div>
								<?php include("donjo-app/views/surat/form/_pamong.php"); ?>
							</div>
						</form>
					</div>
					<div class="box-footer">
						<div class="row">
							<div class="col-xs-12">
								<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
								<button type="button" onclick="$('#'+'validasi').attr('action','<?= $form_action?>');$('#'+'validasi').submit();" class="btn btn-social btn-flat btn-info btn-sm pull-right"><i class="fa fa-print"></i> Cetak</button>
								<?php if (SuratExport($url)):?>
									<button type="button" onclick="$('#'+'validasi').attr('action','<?= $form_action2?>');$('#'+'validasi').submit();" class="btn btn-social btn-flat btn-success btn-sm pull-right" style="margin-right: 5px;"><i class="fa fa-file-text"></i> Ekspor Dok</button>
								<?php endif;?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
