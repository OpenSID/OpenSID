<?= form_open(route('setting.update'), 'class="form-group" id="main_setting"') ?>
	<div class="modal-body">
		<?php include 'donjo-app/views/setting/modal_form.php'; ?>
	</div>
	<div class="modal-footer">
		<button type="reset" class="btn btn-social btn-danger btn-sm pull-left"><i class="fa fa-times"></i> Batal</button>
		<button type="submit" class="btn btn-social btn-info btn-sm"><i class="fa fa-check"></i> Simpan</button>
	</div>
</form>
<script>
	$(document).ready(function() {
		$("#main_setting").validate();
	})
</script>