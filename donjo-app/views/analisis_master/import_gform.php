<script>
	$('#file_browser2').click(function(e) {
		e.preventDefault();
		$('#file2').click();
	});

	$('#file2').change(function() {
		$('#file_path2').val($(this).val());
	});

	$('#file_path2').click(function() {
		$('#file_browser2').click();
	});
</script>
<form id="validasi" action="<?= $form_action ?>" method="POST" enctype="multipart/form-data">
	<div class='modal-body'>
		<div class="row">
			<div class="col-sm-12">
				<div class="box box-danger">
					<div class="box-body">
						<div class="form-group">
							<label for="file" class="control-label">ID Google Form (Panduan mendapatkan ID Google Form dapat Anda akses <span><a href="https://github.com/OpenSID/OpenSID/wiki/Analisis#impor-data-survei-google-form-ke-master-analisis" target="_blank" style="color: blue;">[disini]</a></span>).</label>
							<input type="text" class="form-control input-sm" id="input-form-id" name="input-form-id">
						</div>
					</div>
					<div class="modal-footer">
						<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
						<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Impor</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>