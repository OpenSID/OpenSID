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
<form id="validasi" action="<?= $form_action?>" method="POST" enctype="multipart/form-data">
	<div class='modal-body'>
		<div class="row">
			<div class="col-sm-12">
				<div class="box box-danger">
					<div class="box-body">
						<p>Impor Data BDT 2015 menggunakan format data yang diperoleh dari TNP2K. Contoh format data ada di tautan berikut <a href="<?= site_url('analisis_respon/unduh_form_bdt')?>" class="uibutton confirm" target="_blank"> Form Data BDT 2015 </a></p>
						<div class="form-group">
							<label for="file"  class="control-label">Pilih Berkas Data BDT 2015 :</label>
							<div class="input-group input-group-sm">
								<input type="text" class="form-control" id="file_path2">
								<input type="file" class="hidden" id="file2" name="bdt">
								<span class="input-group-btn">
									<button type="button" class="btn btn-info btn-flat"  id="file_browser2"><i class="fa fa-search"></i> Browse</button>
								</span>
							</div>
							<p class="help-block small">Pastikan format berkas telah sesuai <?= $jml ?></p>
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
