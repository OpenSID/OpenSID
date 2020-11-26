
<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengaturan Dokumen Persyaratan Surat</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('surat_mohon')?>"> Dokumen Persyaratan Surat</a></li>
			<li class="active">Pengaturan Dokumen Persyaratan Surat</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="card card-outline card-info">
					<div class="card-header with-border">
						<a href="<?=site_url("surat_mohon")?>" class="btn btn-flat btn-info btn-xs btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left">
							<i class="fa fa-arrow-circle-left "></i>Kembali ke Dokumen Persyaratan Surat
           	</a>
					</div>
					<div class="card-body">
						<form id="validasi" action="<?= $form_action?>" method="POST" enctype="multipart/form-data"  class="form-horizontal">
							<div class="card-body">
								<div class="row">
									<div class="col-sm-12">
										<div class="form-group">
											<label class="col-sm-3 control-label" >Nama Dokumen</label>
											<div class="col-sm-8">
													<input type="text" class="form-control form-control-sm required" id="ref_syarat_nama" name="ref_syarat_nama" placeholder="Nama Dokumen" value="<?= $ref_syarat_surat['ref_syarat_nama']?>"/>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="card-footer">
								<div class="col-xs-12">
									<button type="reset" class="btn btn-flat btn-danger btn-xs"><i class="fa fa-times"></i> Batal</button>
									<button type="submit" class="btn btn-flat btn-info btn-xs pull-right"><i class="fa fa-check"></i> Simpan</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
