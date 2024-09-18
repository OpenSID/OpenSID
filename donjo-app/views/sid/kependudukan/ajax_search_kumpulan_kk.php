<script type="text/javascript" src="<?= asset('js/jquery.validate.min.js') ?>"></script>
<script type="text/javascript" src="<?= asset('js/validasi.js') ?>"></script>
<script type="text/javascript" src="<?= asset('js/localization/messages_id.js') ?>"></script>
<script type="text/javascript" src="<?= asset('js/select2.min.js') ?>"></script>
<script type="text/javascript" src="<?= asset('js/custom-select2.js') ?>"></script>
<!-- TODO: Pindahkan ke external css -->
<style>
	.form-group {
		margin-bottom: 10px;
	}
</style>
<form method="post" action="<?= $form_action?>" id="validasi">
	<div class='modal-body'>
		<div class="box box-danger">
			<div class="box-body">
				<div class="row">
					<div class="col-sm-12">
						<label for="nama">Kumpulan KK</label>
					</div>
					<div class="col-sm-12">
						<div class="form-group">
								<select
									autofocus
									name="kumpulan_kk[]"
									id="kumpulan_kk"
									class="form-control input-sm select2 select2-kk-ajax"
									data-placeholder="-- Cari No KK --"
									multiple
									data-url="<?= site_url('keluarga/list_kk_ajax') ?>"
									>
									<?php if (! empty($kumpulan_kk)) : ?>
										<?php foreach ($kumpulan_kk as $kk) : ?>
											<option selected value="<?= $kk ?>"><?= $kk ?></option>
										<?php endforeach; ?>
									<?php endif; ?>
								</select>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i
				class='fa fa-sign-out'></i> Tutup</button>
		<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-check'></i>
			Simpan</button>
	</div>
</form>