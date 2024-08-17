<?php if (can('u')): ?>
<?php $this->load->view('global/validasi_form'); ?>
	<form action="<?= $form_action?>" method="post" id="validasi">
		<div class='modal-body'>
			<div class="form-group">
				<label for="rtm_level">Hubungan</label>
				<select name="rtm_level" class="form-control input-sm required">
					<?php foreach ($hubungan as $data): ?>
						<option value="<?= $data['id'] ?>" <?= selected($data['id'], $main['rtm_level']) ?>><?= $data['hubungan'] ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<div class="modal-footer">
			<?= batal() ?>
			<button type="submit" class="btn btn-social btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
		</div>
	</form>
<?php endif; ?>