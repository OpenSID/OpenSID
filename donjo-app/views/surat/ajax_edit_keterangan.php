<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/validasi.js"></script>
<script type="text/javascript" src="<?= base_url()?>assets/js/localization/messages_id.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/script.js"></script>
<style type="text/css">
	.horizontal {
		padding-left: 0px;
		width: auto;
		padding-right: 30px;
	}
</style>
<form action="<?= $form_action ?>" method="post" id="validasi">
	<div class='modal-body'>
		<div class="row">
			<div class="col-sm-12">
				<div class="box box-danger">
					<div class="box-body">
						<div class="form-group">
							<label for="no_kk">Keterangan</label>
							<textarea class="form-control input-sm required" placeholder="Keterangan" name="keterangan"><?= $data['keterangan'] ?></textarea>
							<input name="id" type="hidden" value="<?= $data['id']; ?>">
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