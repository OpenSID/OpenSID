<style type="text/css">
	.tetap {
		resize: none;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>QR Code</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid'); ?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">QR Code</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-6">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Buat QR Code</h3>
						<div class="pull-right box-tools">
							<a href="<?= site_url("setting/qrcode/clear"); ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-spinner"></i> Baru</a>
						</div>
					</div>
					<form action="<?= $form_action; ?>" method="post" id="validasi" enctype="multipart/form-data">
						<div class="box-body">
							<div class="form-group">
								<label for="namaqr">Nama File :</label>
								<input class="form-control input-sm nama_terbatas required" type="text" id="namaqr" name="namaqr" maxlength="15" value="<?= $qrcode['namaqr']; ?>"></input>
							</div>
							<div class="form-group">
								<label for="isiqr">Isi Kode :</label>
								<textarea class="form-control input-sm tetap required" rows="3" id="isiqr" name="isiqr" maxlength="100"><?= $qrcode['isiqr']; ?></textarea>
							</div>
							<div class="form-group">
								<label for="file">Sisipkan Logo :</label>
								<div class="input-group">
									<input type="text" class="form-control input-sm" id="logoqr" name="logoqr" value="<?= $qrcode['logoqr']; ?>">
									<span class="input-group-btn">
										<button type="button" class="btn btn-info btn-flat btn-info btn-sm" id="file_browser1" data-toggle="modal" data-target="#myModal"><i class="fa fa-search"></i> Browse</button>
									</span>
								</div>
							</div>
							<div class="row">
								<div class="form-group col-md-6">
									<label for="sizeqr" >Ukuran :</label>
									<select class="form-control input-sm" id="sizeqr" name="sizeqr">
										<?php foreach ($list_sizeqr as $key => $list): ?>
											<option value="<?= $key + 1; ?>" <?= selected($qrcode['sizeqr'], $key + 1); ?>><?= $list.' x '.$list.'px'; ?></option>
										<?php endforeach;?>
									</select>
								</div>
								<div class="form-group col-md-6">
									<label for="foreqr">Warna :</label>
									<div class="input-group my-colorpicker2">
										<div class="input-group-addon input-sm">
											<i></i>
										</div>
										<input type="text" id="foreqr" name="foreqr" class="form-control input-sm" value="<?= $qrcode['foreqr'] ?: $qrcode['backqr']; ?>">
									</div>
								</div>
							</div>
						</div>
						<div class="box-footer">
							<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
							<button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
						</div>
					</div>
				</form>
			</div>
			<div class="col-md-6">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Scan QR Code</h3>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="pathqr"></label>
									<center>
										<img class="img-thumbnail" src="<?= base_url($qrcode['pathqr']); ?>">
									</center>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<!-- File Manager -->
<div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class='modal-header'>
				<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
				<h4 class='modal-title' id='myModalLabel'>Pilih Logo</h4>
			</div>
			<div class="modal-body" style="padding:0px; margin:0px; width: 600px;">
				<iframe width="600" height="400" src="../../assets/filemanager/dialog.php?type=2&field_id=logoqr'&fldr='&akey=<?= config_item('file_manager')?>" frameborder="0" style="overflow: scroll; overflow-x: hidden; overflow-y: scroll; "></iframe>
			</div>
		</div>
	</div>
</div>

<script src="<?= base_url()?>assets/bootstrap/js/jquery.min.js"></script>
<script src="<?= base_url()?>assets/js/jquery.validate.min.js"></script>
<script src="<?= base_url()?>assets/js/validasi.js"></script>
