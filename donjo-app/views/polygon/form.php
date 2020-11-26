<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengaturan Tipe Area</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('polygon')?>"><i class="fa fa-dashboard"></i> Daftar Tipe Area</a></li>
			<li class="active">Pengaturan Tipe Area</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
	<form id="validasi" action="<?= $form_action?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
			<div class="row">
				<div class="col-md-3">
          <?php $this->load->view('plan/nav.php')?>
				</div>
				<div class="col-md-9">
					<div class="card card-outline card-info">
            <div class="card-header with-border">
							<a href="<?= site_url("polygon")?>" class="btn btn-flat btn-info btn-xs btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left">
								<i class="fa fa-arrow-circle-left "></i>Kembali ke Daftar Tipe Area
            	</a>
						</div>
						<div class="card-body">
							<div class="form-group">
								<label class="control-label col-sm-3">Nama Jenis Area</label>
								<div class="col-sm-7">
									<input name="nama" class="form-control form-control-sm nomor_sk required" maxlength="100" type="text" value="<?=$polygon['nama']?>"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-3">Warna</label>
								<div class="col-sm-4">
									<div class="input-group my-colorpicker2">
										<input type="text" id="color" name="color" class="form-control form-control-sm required" placeholder="#FFFFFF" value="<?= $polygon['color']?>">
										<div class="input-group-addon input-sm">
											<i></i>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class='card-footer'>
							<div class='col-xs-12'>
								<button type='reset' class='btn btn-flat btn-danger btn-xs'><i class='fa fa-times'></i> Batal</button>
								<button type='submit' class='btn btn-flat btn-info btn-xs pull-right confirm'><i class='fa fa-check'></i> Simpan</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>
