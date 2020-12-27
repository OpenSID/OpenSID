<div class="box box-solid">
	<div class="box-header with-border bg-navy">
		<h4 class="box-title">Ganti PIN</h4>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-3"></div>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<form action="<?= $form_action; ?>" method="POST" id="validasi">
					<div class="box-body">
						<?php $gagal = $data = $this->session->flashdata('notif'); ?>
						<?php if ($data['status'] == -1): ?>
							<div class="callout callout-danger">
								<?= $gagal['pesan']; ?>
							</div>
						<?php endif; ?>

						<div class="form-group">
							<label for="pin_lama">PIN Lama</label>
							<input type="password" class="form-control input-md bilangan required" name="pin_lama" id="pin_lama" placeholder="Masukkan PIN Lama" minlength="6" maxlength="6">
						</div>
						<div class="form-group">
							<label for="pin_baru1">PIN Baru</label>
							<input type="password" class="form-control input-md bilangan required" name="pin_baru1" id="pin_baru1" placeholder="Masukkan PIN Baru" minlength="6" maxlength="6">
						</div>
						<div class="form-group">
							<label for="pin_baru2">Ulangi PIN Baru</label>
							<input type="password" class="form-control input-md bilangan required" name="pin_baru2" id="pin_baru2" placeholder="Masukkan PIN Baru" minlength="6" maxlength="6">
						</div>
					</div>
					<div class="box-footer">
						<button type="reset" class="btn bg-red">Batal</button>
						<button type="submit" class="btn bg-green pull-right">Simpan</button>
					</div>
				</form>
			</div>
			<div class="col-md-3"></div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		setTimeout(function() {
			$('#pin_baru2').rules('add', {
				equalTo: '#pin_baru1'
			});
		}, 500);

		window.setTimeout(function() {
			$(".callout").fadeTo(500, 0).slideUp(500, function(){
				$(this).remove();
			});
		}, 5000);
	});
</script>
