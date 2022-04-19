<style type="text/css">
	.form-group a {
		color: #3c8dbc;
	}
</style>
<script>
	$('#file_browser2').click(function(e)
	{
			e.preventDefault();
			$('#file2').click();
	});

	$('#file2').change(function()
	{
			$('#file_path2').val($(this).val());
	});

	$('#file_path2').click(function()
	{
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
							<label for="file"  class="control-label">File Master Analisis :</label>
							<div class="input-group input-group-sm">
								<input type="text" class="form-control" id="file_path2" name="userfile">
								<input type="file" class="hidden" id="file2" name="userfile">
								<span class="input-group-btn">
									<button type="button" class="btn btn-info btn-flat"  id="file_browser2"><i class="fa fa-search"></i> Browse</button>
								</span>
							</div>
							<p class="help-block small">1. Data yang dibutuhkan untuk Impor dengan memenuhi aturan data sebagai berikut <a href="<?= base_url() ?>assets/import/analisis.xlsx">Aturan Data</a></p>
							<p class="help-block small">2. Contoh format upload Sensus dapat dilihat pada tautan berikut <a href="<?= base_url() ?>assets/import/ppls2.xlsx">Contoh</a></p>
						</div>
					</div>
					<div class="modal-footer">
						<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
						<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
