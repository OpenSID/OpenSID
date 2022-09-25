<?php if ($this->CI->cek_hak_akses('u')): ?>
<?php $this->load->view('global/validasi_form'); ?>
	<form action="<?= $form_action; ?>" method="post" id="validasi">
		<div class="modal-body">
			<div class="form-group">
				<label for="rtm_nomor">Nomor Rumah Tangga</label>
				<input id="no_kk" name="no_kk" class="form-control input-sm digits required" type="text" placeholder="Nomor Rumah Tangga" value="<?= $kk['no_kk']; ?>" maxlength="30"/>
			</div>
			<div class="form-group">
				<label for="bdt">BDT</label>
				<input class="form-control input-sm angka" type="text" placeholder="BDT" name="bdt" value="<?= $kk['bdt']; ?>" minlength="16" maxlength="16"/>
			</div>
		</div>
		<div class="modal-footer">
			<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm pull-left"><i class="fa fa-times"></i> Batal</button>
			<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class="fa fa-check"></i> Simpan</button>
		</div>
	</form>
<?php endif; ?>