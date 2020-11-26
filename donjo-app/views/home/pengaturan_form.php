<form action="<?= $form_action?>" method="post" id="validasi">
	<input type="hidden" name="rt" value="">
	<div class='modal-body'>
		<div class="row">
			<div class="col-sm-12">
				<div class="card card-outline card-danger">
					<div class="card-body">
						<div class="form-group">
							<label>Program Bantuan Untuk Ditampilkan</label>
							<select name="program_bantuan" class="form-control select2 input-sm required" style="width: 100%;">
								<option value="">Pilih Program Bantuan</option>
								<?php foreach ($list_program_bantuan AS $data): ?>
									<option value="<?=$data['id']?>" <?php selected($this->setting->dashboard_program_bantuan, $data['id']) ?>><?=$data['nama'].' - ['.$sasaran[$data['sasaran']].']'?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="reset" class="btn btn-flat btn-danger btn-xs" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
			<button type="submit" class="btn btn-flat btn-info btn-xs" id="ok"><i class='fa fa-check'></i> Simpan</button>
		</div>
	</div>
</form>
<script src="<?= base_url()?>assets/js/script.js"></script>
