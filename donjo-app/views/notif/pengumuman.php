<form id="form-pengumuman" method="POST">
<div class="modal fade" id="pengumuman" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class='modal-dialog'>
		<div class='modal-content'>
			<div class='modal-header btn-info'>
				<h4 class='modal-title' id='myModalLabel'><?= $judul ?></h4>
			</div>
			<div class='modal-body'>
				<div id="isi">
					<?= $isi_pengumuman; ?>
					<div class="checkbox">
						<label>
							<input type="checkbox" name="cek_lagi" value="cek_lagi"></input>&nbsp;Jangan tampilkan lagi
						</label>
					</div>
				</div>
				<center>
					<i id="indikator" class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
				</center>
			</div>
			<div class='modal-footer' id='m_footer'>
				<button <?= ($kode=='tracking_off') ? 'type="submit" data-dismiss="modal"' : 'id="btnTidak"';?> class="btn btn-social btn-flat btn-danger btn-sm"><i class='fa fa-sign-out'></i> Tidak</button>
				<button  type="submit" id="btnSetuju" class="btn btn-social btn-flat btn-warning btn-sm"><i class='fa fa-check'></i> Setuju</button>
			</div>
		</div>
	</div>
</div>
</form>


<script type="text/javascript">
	$('document').ready(function() {
		$('#pengumuman').modal({backdrop: 'static', keyboard: false});
		$('#indikator').hide();
	});

	$('#btnSetuju').on('click', function() {
		$('#isi').hide();
		$('#m_footer').hide();
		$('#indikator').show();
		$('#btnSetuju').prop('disabled', true);
		$('#btnTidak').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: SITE_URL + "<?= $aksi_ya; ?>",
            data: $('#form-pengumuman').serialize(),
			success: function() {
				$('#indikator').hide();
				$('#pengumuman').modal('hide');
			}
		});
		return false;
	});

	$('#btnTidak').on('click', function() {
		location.href = SITE_URL + "<?= $aksi_tidak; ?>";
	});
	// TODO ketika button tidak diklik dan checkbox dicentang, maka pake ajax otherwise cancel biasa
</script>
