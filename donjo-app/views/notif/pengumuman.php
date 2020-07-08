<div class="modal fade" id="pengumuman" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class='modal-dialog'>
		<div class='modal-content'>
			<div class='modal-header btn-info'>
				<h4 class='modal-title' id='myModalLabel'><i class='fa fa-file-text-o text-black'></i> &nbsp;&nbsp;<?= $judul ?></h4>
			</div>
			<div class='modal-body'>
				<?= $isi_pengumuman; ?>
				<center>
					<i id="indikator" class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
				</center>
			</div>
			<div class='modal-footer'>
				<button id="btnTidak" class="btn btn-social btn-flat btn-danger btn-sm"><i class='fa fa-sign-out'></i> Tidak</button>
				<button id="btnSetuju" type="button" class="btn btn-social btn-flat btn-warning btn-sm"><i class='fa fa-check'></i> Setuju</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('document').ready(function() {
		$('#pengumuman').modal({backdrop: 'static', keyboard: false});
		$('#indikator').hide();
	});

	$('#btnSetuju').on('click', function() {
		$('#indikator').show();
		$('#btnSetuju').prop('disabled', true);
		$('#btnTidak').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: SITE_URL + "notif/update_pengumuman",
			success: function() {
				$('#indikator').hide();
				$('#pengumuman').modal('hide');
			}
		});
		return false;
	});

	$('#btnTidak').on('click', function() {
		location.href = SITE_URL + "siteman";
	});
</script>
