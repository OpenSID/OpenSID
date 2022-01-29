<?php $this->load->view('global/validasi_form'); ?>
<form id="validasi" action="<?= $form_action ?>" method="POST">
	<div class="modal-body">
		<div class="form-group" id="form_ubah_arsip">
			<label for="nama">Masukkan Lokasi Arsip</label>
			<input type="text" id="lokasi_arsip" name="lokasi_arsip" class="form-control input-sm nama_terbatas" maxlength="150" value="<?= $value ?>">
		</div>
	</div>
	<div class="modal-footer">
		<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm pull-left"><i class="fa fa-times"></i> Batal</button>
		<button type="submit" class="btn btn-social btn-flat btn-info btn-sm"><i class="fa fa-check"></i> Simpan</button>
	</div>
</form>
