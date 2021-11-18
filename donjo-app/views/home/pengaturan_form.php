<?php $this->load->view('global/validasi_form'); ?>
<form action="<?= $form_action; ?>" method="post" id="validasi">
	<div class="modal-body">
		<div class="form-group">
			<label>Program Bantuan Untuk Ditampilkan</label>
			<select name="dashboard_program_bantuan" class="form-control select2 input-sm required">
				<option value="">Pilih Program Bantuan</option>
				<?php foreach ($list_program_bantuan as $data): ?>
					<option value="<?=$data['id']?>" <?= selected($this->setting->dashboard_program_bantuan, $data['id']) ?>><?=$data['nama'] . ' - [' . $sasaran[$data['sasaran']] . ']'?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>
	<div class="modal-footer">
		<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm"><i class='fa fa-times'></i> Batal</button>
		<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
	</div>
</form>
