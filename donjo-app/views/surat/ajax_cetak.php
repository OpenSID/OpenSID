<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/validasi.js"></script>
<script type="text/javascript" src="<?= base_url()?>assets/js/localization/messages_id.js"></script>
<script type="text/javascript">
	$('document').ready(function() {
		$('#validasi').submit(function() {
			if ($('#validasi').valid())
				$('#modalBox').modal('hide');
		});
	});
</script>
<form action="<?= $form_action ?>" method="post" id="validasi" target="_blank">
	<div class="modal-body">
		<div class="row">
			<div class="col-sm-12">
				<div class="card card-outline card-danger">
					<div class="card-body">
						<div class="form-group">
						</div>
						<div class="form-group">
							<label for="pamong_ttd">Laporan Ditandatangani</label>
							<select class="form-control form-control-sm required" name="pamong_ttd" width="100%">
								<option value="">Pilih Staf Pemerintah <?= ucwords($this->setting->sebutan_desa) ?></option>
								<?php foreach ($pamong as $data) : ?>
								<option value="<?= $data['pamong_id'] ?>"><?= $data['nama'] ?> (<?= $data['jabatan'] ?>)</option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="form-group">
							<label for="pamong_ketahui">Laporan Diketahui</label>
							<select class="form-control form-control-sm required" name="pamong_ketahui" width="100%">
								<option value="">Pilih Staf Pemerintah <?= ucwords($this->setting->sebutan_desa) ?></option>
								<?php foreach ($pamong as $data) : ?>
								<option value="<?= $data['pamong_id'] ?>"><?= $data['nama'] ?> (<?= $data['jabatan'] ?>)</option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="reset" class="btn btn-flat btn-danger btn-xs" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
		<button type="submit" class="btn btn-flat btn-info btn-xs" id="ok"><i class='fa fa-check'></i> <?= $aksi ?></button>
	</div>
</form>