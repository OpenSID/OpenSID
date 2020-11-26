<script type="text/javascript" src="<?= base_url()?>assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?= base_url()?>assets/js/validasi.js"></script>
<script type="text/javascript" src="<?= base_url()?>assets/js/localization/messages_id.js"></script>
<script>
 	$('.my-colorpicker2').colorpicker();
</script>
<form id="validasi" action="<?= $form_action?>" method="POST" enctype="multipart/form-data">
	<div class='modal-body'>
		<div class="row">
			<div class="col-sm-12">
				<div class="card card-outline card-danger">
					<div class="card-body">
						<div class="form-group">
							<label class="control-label">Nama Kategori Area</label>
							<input name="nama" class="form-control form-control-sm nomor_sk required" maxlength="100" type="text" value="<?=$polygon['nama']?>"></input>
						</div>
						<div class="form-group">
							<label class="control-label">Warna</label>
							<div class="input-group my-colorpicker2">
								<div class="input-group-addon input-sm">
									<i></i>
								</div>
								<input type="text" id="color" name="color" class="form-control form-control-sm" placeholder="#FFFFFF" value="<?= $polygon['color']?>">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="reset" class="btn btn-flat btn-danger btn-xs" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
			<button type="submit" class="btn btn-flat btn-info btn-xs" id="ok"><i class='fa fa-check'></i> Simpan</button>
		</div>
	</div>
</form>