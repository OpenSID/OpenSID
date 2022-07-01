<form id="main_setting" action="<?= site_url('setting/update'); ?>" method="POST" class="form-horizontal">
	<div class="modal-body">
		<?php include 'donjo-app/views/setting/form.php'; ?>
	</div>
	<div class="modal-footer">
		<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm pull-left"><i class="fa fa-times"></i> Batal</button>
		<button type="submit" class="btn btn-social btn-flat btn-info btn-sm"><i class="fa fa-check"></i> Simpan</button>
	</div>
</form>
<script>
	$(document).ready(function() {
		$("#main_setting").validate();
	})
</script>
