<div class="content-wrapper">
	<section class="content-header">
		<h1>Surat Keterangan Lahir Mati</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_desa/about')?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="<?= site_url('surat')?>"> Cetak Surat</a></li>
			<li class="active">Surat Keterangan Lahir Mati</li>
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
									<div class="col-sm-5">
										<select class="form-control  input-sm select2" id="nik" name="nik" style ="width:100%;" onchange="formAction('main')">
											<option value="">--  Cari NIK Penduduk--</option>
											<?php foreach ($penduduk as $data):?>
												<option value="<?= $data['id']?>" <?php if ($individu['nik']==$data['nik']):?>selected<?php endif;?>>NIK :<?= $data['nik']." - ".$data['nama']?></option>
											<?php endforeach;?>
										</select>
									</div>
								</div>
							</form>
							<form id="validasi" action="<?= $form_action?>" method="POST" target="_blank" class="form-horizontal">
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
								<div class="form-group">
									<label for="ttl"  class="col-sm-3 control-label">Hari / Tanggal Mati</label>
									<div class="col-sm-3">
										<input  id="hari"  class="form-control input-sm" type="text" placeholder="Hari Mati" name="hari">
									</div>
									<div class="col-sm-2">
										<div class="input-group input-group-sm date">
											<div class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</div>
											<input title="Pilih Tanggal" class="form-control input-sm required" name="tanggal_mati" id="tgl_1" type="text"/>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label for="tempat_mati"  class="col-sm-3 control-label">Tempat Mati</label>
									<div class="col-sm-8">
										<input type="text" name="tempat_mati" class="form-control input-sm required" placeholder="Tempat Mati"></input>
									</div>
								</div>
								<div class="form-group">
									<label for="kandungan"  class="col-sm-3 control-label">Lama di Kandungan</label>
									<div class="col-sm-3">
										<div class="input-group input-group-sm">
											<input id="input_group" type="text" name="lama_kandungan" class="form-control input-sm required" placeholder="Lama di Kandungan"></input>
											<div class="input-group-addon">
												Bulan
											</div>
										</div>
									</div>
								</div>
								<div class="form-group bg-info" style="padding-top:10px;padding-bottom:5px">
									<label class="col-sm-3 text-right text-red"><strong>IDENTITAS PELAPOR :</strong></label>
								</div>
								<div class="form-group">
									<label for="nama_pelapor"  class="col-sm-3 control-label">Nama Pelapor</label>
									<div class="col-sm-8">
										<input type="text"  name="pelapor" class="form-control input-sm required" placeholder="Nama Pelapor"></input>
									</div>
								</div>
								<div class="form-group">
									<label for="hubungan"  class="col-sm-3 control-label">Hubungan dengan yang Lahir Mati</label>
									<div class="col-sm-8">
										<input type="text"  name="hubungan" class="form-control input-sm required" placeholder="Hubungan dengan yang Lahir Mati"></input>
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
