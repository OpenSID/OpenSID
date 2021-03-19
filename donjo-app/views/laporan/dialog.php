<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/validasi.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/localization/messages_id.js"></script>
<form action="<?php echo site_url( "{$this->controller}/$aksi" ) ?>" method="GET" id="validasi" target="_blank">
	<input type="hidden" name="tahun" value="<?= $tahun ?>">
	<input type="hidden" name="bulan" value="<?= $bulan ?>">
	<div class="modal-body">
		<div class="row">
			<div class="col-sm-12">
				<div class="box box-danger">
					<div class="box-body">
						<div class="form-group">
							<label for="pamong_ttd">Laporan Ditandatangani</label>
              <select class="form-control input-sm required" name="pamong_ttd" width="100%">
				<?php foreach ( $pamong as $data ): ?>
					<option value="<?php echo $data['pamong_id'] ?>" <?php selected( $data['pamong_ttd'], 1 );?>><?php echo $data['nama'] ?> (<?php echo $data['jabatan'] ?>)</option>
				<?php endforeach;?>
              </select>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
		<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok" data-dismiss="modal" onclick="$('#validasi').submit();"><i class='fa fa-check'></i> <?php echo htmlentities( ucwords( $aksi ) ) ?></button>
	</div>
</form>

<!-- Diperlukan karena di hosting yg lambat form belum lengkap sebelum $('#modalBox').on('show.bs.modal' dijalankan di script.js, sehingga csrf field belum ditambahkan -->
<script type="text/javascript">
  $(document).ready(function () {
      addCsrfField($('#validasi')[0]);
  });
</script>
