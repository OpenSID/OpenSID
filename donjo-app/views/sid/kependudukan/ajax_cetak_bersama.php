<script type="text/javascript" src="<?= base_url()?>assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?= base_url()?>assets/js/validasi.js"></script>
<script type="text/javascript" src="<?= base_url()?>assets/js/localization/messages_id.js"></script>
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
<style>
	.form-group {
		margin-bottom: 10px;
	}
</style>
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
	<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
	<a href="#" class="btn btn-social btn-flat btn-info btn-sm" title="Simpan" target="_blank" onclick="cetak()" id="cetak_data" ><i class="fa fa-check"></i> <?= ucwords($aksi) ?></a>
</div>