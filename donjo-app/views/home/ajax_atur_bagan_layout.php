<form id="validasi" action="<?= site_url('setting/update'); ?>" method="POST" class="form-horizontal">
	<div class="modal-body">
		<?php include("donjo-app/views/setting/form.php"); ?>
	</div>
	<div class="modal-footer">
		<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm"><i class="fa fa-times"></i> Tutup</button>
		<button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
	</div>
</form>
