<div class="content-wrapper">
	<section class="content-header">
		<h1>Hasilkan QRCode</h1>
		<ol class="breadcrumb">
			<li><a href="<?=site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Hasilkan QRCode</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<form action="<?=site_url("setting/qrcode_generate")?>" method="POST" id="validasi" enctype="multipart/form-data">
				<div class="col-md-12">
					<div class="box box-primary">
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-12 col-md-3" for="namaqr">Nama File :</label>
								<div class="input-group">
									<input class="form-control input-sm required" type="text" id="namaqr" name="namaqr" value="<?=$this->session->namaqr?>" style="width: 195px;">.png</input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-12 col-md-3" for="isiqr">Isi Kode :</label>
								<div class="input-group">
									<textarea class="form-control input-sm required" rows="3" id="isiqr" style="width: 220px;" name="isiqr" value="<?=$this->session->isiqr?>"></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-12 col-md-3" for="file" >Pilih Logo :</label>
								<div class="input-group">
									<input type="text" class="form-control input-sm" id="logoqr" name="logoqr" value="<?=$this->session->logoqr?>" style="width: 145px;">
									<span class="input-group-btn" style="width: 150px;">
										<button type="button" class="btn btn-info btn-flat btn-info btn-sm" id="file_browser1" data-toggle="modal" data-target="#myModal"><i class="fa fa-search"></i> Browse</button>
									</span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-12 col-md-3" for="sizeqr" >Ukuran : </label>
								<div class="input-group">
									<select class="form-control input-sm" id="sizeqr" name="sizeqr" style="width: 220px;" value="<?=$this->session->sizeqr?>">
										<option value="1">25 x 25px </option>
										<option value="2">50 x 50px</option>
										<option value="3">75 x 75px</option>
										<option value="4" selected="selected">100 x 100px</option>
										<option value="5">125 x 125px</option>
										<option value="6">150 x 150px</option>
										<option value="7">175 x 175px</option>
										<option value="8">200 x 200px</option>
										<option value="9">225 x 225px</option>
										<option value="10">250 x 250px</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-12 col-md-3" for="foreqr">Warna :</label>
								<div class="input-group my-colorpicker2">
									<div class="input-group-addon input-sm">
										<i></i>
									</div>
									<input type="text" id="foreqr" name="foreqr" class="form-control input-sm" style="width: 185px;" value="<?=$this->session->foreqr?>">
								</div>
							</div>
							<br/>
							<div class="form-group">
								<label class="col-sm-12 col-md-3" for="generate"></label>
								<div class="input-group">
									<button type='submit' id="generate" class='btn btn-social btn-flat btn-info btn-sm' style="width: 220px;"><i class='fa fa-check'></i> <center>Buat QRCODE</center></button>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-12 col-md-3" for="qrcode"></label>
								<div class="input-group">
									<img class="img-thumbnail" src="<?php echo base_url().'desa/upload/media/'.$this->session->qrcode.'.png';?>">
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
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
