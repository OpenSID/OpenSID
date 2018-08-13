
<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengaturan Format Surat Desa</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_desa')?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="<?= site_url('surat_master')?>"> Format Surat Desa</a></li>
			<li class="active">Pengaturan Format Surat</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<a href="<?=site_url("surat_master")?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Kembali Ke Daftar Wilayah">
							<i class="fa fa-arrow-circle-left "></i>Kembali ke Daftar Format Surat
           	</a>
					</div>
					<div class="box-body">
						<form id="validasi" action="<?= $form_action?>" method="POST" enctype="multipart/form-data"  class="form-horizontal">
							<div class="box-body">
								<div class="row">
									<div class="col-sm-12">
										<div class="form-group">
											<label class="col-sm-3 control-label" for="kode_surat">Kode/Klasifikasi Surat</label>
											<div class="col-sm-7">
												<input  id="kode_surat" name="kode_surat" class="form-control input-sm required" type="text" placeholder="Kode/Klasifikasi Surat" value="<?= $surat_master['kode_surat']?>">
											</div>
										</div>
									</div>
									<div class="col-sm-12">
										<div class="form-group">
											<label class="col-sm-3 control-label" >Nama Layanan</label>
											<div class="col-sm-7">
												<div class="input-group">
													<span class="input-group-addon input-sm">Surat</span>
													<input type="text" class="form-control input-sm required" id="nama" name="nama" placeholder="Nama Layanan" value="<?= $surat_master['nama']?>"/>
												</div>
											</div>
										</div>
									</div>
									<?php if (strpos($form_action, 'insert') !== false): ?>
										<div class="col-sm-12">
											<div class="form-group">
												<label class="col-sm-3 control-label" for="nama">Pemohon Surat</label>
												<div class="col-sm-3">
													<select class="form-control input-sm" id="pemohon_surat" name="pemohon_surat">
														<option value="warga" selected>Warga</option>
														<option value="non_warga">Bukan Warga</option>
													</select>
												</div>
											</div>
										</div>
									<?php endif; ?>
								</div>
							</div>
							<div class="box-footer">
								<div class="col-xs-12">
									<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
									<button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
