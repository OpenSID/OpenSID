<?php $this->load->view('global/validasi_form'); ?>
<form action="<?= $form_action ?>" method="post" id="validasi">
	<div class="modal-body">
		<div class="form-group">
			<label for="keterangan">Keterangan</label>
			<textarea class="form-control input-sm required" placeholder="Keterangan" name="keterangan" rows="5"><?= $main->keterangan; ?></textarea>
		</div>
	</div>
	<div class="modal-footer">
		<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm pull-left"><i class="fa fa-times"></i> Batal</button>
		<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class="fa fa-check"></i> Simpan</button>
	</div>
</form>