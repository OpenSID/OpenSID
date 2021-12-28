<?php if ($this->CI->cek_hak_akses('u')): ?>
<?php $this->load->view('global/validasi_form'); ?>
	<form action="<?= $form_action; ?>" method="post">
		<div class="modal-body">
			<div class="form-group">
				<select name="status" class="form-control input-sm" required="">
					<option value="">Pilih Status Pengaduan</option>
					<!-- <option value="1" <?= selected(1, $value['status']) ?>>Menunggu Diproses</option> -->
					<option value="2" <?= selected(2, $value['status']) ?>>Sedang Diproses</option>
					<option value="3" <?= selected(3, $value['status']) ?>>Selesai Diproses</option>
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