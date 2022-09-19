<?php if ($this->CI->cek_hak_akses('u')): ?>
<?php $this->load->view('global/validasi_form'); ?>
	<form action="<?= $form_action?>" method="post" id="validasi">
		<div class='modal-body'>
			<div class="row">
				<div class="col-sm-12">
					<div class="box box-danger">
						<div class="box-body">
							<div class="form-group">
								<label for="rtm_level">Hubungan</label>
								<select name="rtm_level" class="form-control input-sm required">
									<?php foreach ($hubungan as $data): ?>
										<option value="<?= $data['id'] ?>" <?= selected($data['id'], $main['rtm_level']) ?>><?= $data['hubungan'] ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
			<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
		</div>
	</form>
<?php endif; ?>