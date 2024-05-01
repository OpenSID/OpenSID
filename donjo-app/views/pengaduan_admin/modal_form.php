<?php if ($this->CI->cek_hak_akses('u')): ?>
<?php $this->load->view('global/validasi_form'); ?>
	<form action="<?= $form_action; ?>" method="post">
		<div class="modal-body">
			<div class="form-group">
				<?php if ($main->status == '3') : ?>
					<input type="hidden" name="status" value="<?= $main->status ?>">
				<?php endif ?>
				<select name="status" class="form-control input-sm" required <?= $main->status == '3' ? 'disabled' : '' ?>>
					<option value="">Pilih Status Pengaduan</option>
					<option value="2" <?= selected(2, $main->status) ?>>Sedang Diproses</option>
					<option value="3" <?= selected(3, $main->status) ?>>Selesai Diproses</option>
				</select>
			</div>
			<div class="form-group">
				<textarea name="isi" required="" class="form-control form-control input-sm" rows="5" placeholder="Isi"></textarea>
			</div>
		</div>
		<div class="modal-footer">
			<button type="reset" data-dismiss="modal" aria-hidden="true" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
			<button type="submit" class="btn btn-primary pull-right btn-flat btn-sm"><i class="fa fa-pencil"></i> Kirim</button>
		</div>
	</form>
<?php endif; ?>