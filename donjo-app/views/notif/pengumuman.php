<style type="text/css">
	#form-pengumuman .modal-body
	{
		height: auto;
	}
	.checkbox label
	{
		font-weight: bolder;
		margin-top: 10px;
	}
</style>
<form id="form-pengumuman" method="POST">
	<input type="hidden" name="notifikasi" value="1">
	<input type="hidden" name="kode" value="<?=$kode?>">
	<input type="hidden" id="jenis" value="<?=$jenis?>">
	<div class="modal fade" id="pengumuman" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class='modal-dialog'>
			<div class='modal-content'>
				<div class='modal-header btn-info'>
					<h4 class='modal-title' id='myModalLabel'><?= $judul ?></h4>
				</div>
				<div class='modal-body'>
					<div id="isi">
						<?= $isi_pengumuman; ?>
						<?php if ($jenis == 'pengumuman'): ?>
							<div class="checkbox">
								<label>
									<input type="checkbox" name="non_aktifkan" value="non_aktifkan"></input>&nbsp;Jangan tampilkan lagi
								</label>
							</div>
						<?php endif ?>
					</div>
					<center>
						<div id="indikator" class='text-center'>
							<img src='<?= base_url()?>assets/images/background/loading.gif'>
						</div>
					</center>
				</div>
				<div class='modal-footer' id='m_footer'>
					<?php if ($jenis == 'pengumuman'): ?>
						<button type="reset" data-dismiss="modal" id="btnSetuju" class="btn btn-social btn-flat btn-warning btn-sm"><i class='fa fa-sign-out'></i> OK</button>
					<?php else: ?>
						<button type="reset" data-dismiss="modal" id="btnTidak" class="btn btn-social btn-flat btn-danger btn-sm"><i class='fa fa-sign-out'></i> Tidak</button>
						<button  type="submit" id="btnSetuju" class="btn btn-social btn-flat btn-warning btn-sm"><i class='fa fa-check'></i> Setuju</button>
					<?php endif; ?>
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

	// Persetujuan langsung redirect ke aksi_tidak
	$('#btnTidak').on('click', function() {
		if ($('#jenis').val() == 'persetujuan') location.href = SITE_URL + "<?= $aksi_tidak; ?>";

		$('#isi').hide();
		$('#m_footer').hide();
		$('#indikator').show();
		$('#btnSetuju').prop('disabled', true);
		$('#btnTidak').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: SITE_URL + "<?= $aksi_tidak; ?>",
			data: $('#form-pengumuman').serialize(),
			success: function() {
				$('#indikator').hide();
				$('#pengumuman').modal('hide');
			}
		});
		return false;
	});
</script>
