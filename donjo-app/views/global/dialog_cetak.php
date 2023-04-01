<form action="<?= $form_action; ?>" method="post" id="validasi" target="_blank">
	<div class="modal-body">
		<!-- Isi Dialog Cetak Disini -->
		<?php $this->load->view($isi); ?>
	</div>
	<div class="modal-footer">
		<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm pull-left" data-dismiss="modal"><i class="fa fa-times"></i> Tutup</button>
		<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-check'></i> <?= ucwords($aksi); ?></button>
	</div>
</form>
<?php $this->load->view('global/validasi_form'); ?>
<script type="text/javascript">
	$('document').ready(function() {
		$('#validasi').submit(function() {
			if ($('#validasi').valid())
				$('#modalBox').modal('hide');
		});
	});
</script>

