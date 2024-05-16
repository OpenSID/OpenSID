<script type="text/javascript" src="<?= asset('js/jquery.validate.min.js') ?>"></script>
<script type="text/javascript" src="<?= asset('js/validasi.js') ?>"></script>
<script type="text/javascript" src="<?= asset('js/localization/messages_id.js') ?>"></script>
<script>
	function cetak() {
		const privasi_nik = $('#privasi_nik:checked').val();
		if (privasi_nik == "on")
		{
			$("#cetak_data").attr("href", "<?= $form_action_privasi ?>");
		}
		else
		{
			$("#cetak_data").attr("href", "<?= $form_action ?>");
		}
		$('#modalBox').modal('hide');
	}
</script>
<form>
	<div class='modal-body'>
		<div class="box box-danger">
			<div class="box-body">
				<div class="row">
					<div class="col-sm-12">
						<label for="nama">Centang kotak berikut apabila NIK/No. KK ingin disensor</label>
					</div>
					<div class="col-sm-12">
						<div class="col-sm-6">
							<div class="form-group">
								<div class="form-check">
									<input type="checkbox" class="form-check-input" id="privasi_nik">
									<label class="form-check-label" for="cetak_privasi_nik">Sensor NIK/No. KK</label>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<?= batal() ?>
		<a href="#" class="btn btn-social btn-info btn-sm" title="Simpan" target="_blank" onclick="cetak()" id="cetak_data" ><i class="fa fa-check"></i> <?= ucwords($aksi) ?></a>
	</div>
</form>