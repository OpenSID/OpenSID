<script type="text/javascript" src="<?= base_url()?>assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?= base_url()?>assets/js/validasi.js"></script>
<script type="text/javascript" src="<?= base_url()?>assets/js/localization/messages_id.js"></script>
<script>
	$('#file_browser').click(function(e)
	{
			e.preventDefault();
			$('#file').click();
	});
	$('#file').change(function()
	{
			$('#file_path').val($(this).val());
	});
	$('#file_path').click(function()
	{
			$('#file_browser').click();
	});
</script>
<form action="<?= $form_action?>" method="POST" id="validasi" enctype="multipart/form-data">
	<div class='modal-body'>
		<div class="row">
			<div class="col-sm-12">
				<div class="box box-danger">
					<div class="box-body">
						<label class="control-label" for="nama">Unggah Template Format Surat</label>
						<div class="input-group input-group-sm">
							<input type="text" class="form-control input-sm required" id="file_path">
							<input type="file" class="hidden" id="file" name="foto">
							<span class="input-group-btn">
								<button type="button" class="btn btn-info btn-flat"  id="file_browser"><i class="fa fa-search"></i> Browse</button>
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
			<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
		</div>
	</div>
</form>
