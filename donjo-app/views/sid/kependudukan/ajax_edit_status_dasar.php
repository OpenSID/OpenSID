<script type="text/javascript" src="<?= base_url()?>assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?= base_url()?>assets/js/validasi.js"></script>
<script>
	$('#tgl_1').datetimepicker(
	{
		format: 'DD-MM-YYYY'
	});
</script>
<?php
	if ($log_status_dasar['tgl_peristiwa']!=''):
		$sekarang = $log_status_dasar['tgl_peristiwa'];
	else:
		$sekarang = date("d-m-Y");
	endif;
?>
<form action="<?=$form_action?>" method="post" id="validasi">
	<div class='modal-body'>
		<div class="row">
			<div class="col-sm-12">
				<div class="box box-danger">
					<div class="box-body">
						<div class="form-group">
              <div class="radio">
                <label>
								  <input name="status_dasar" id="sd1" value="1" <?php if ($nik['status_dasar_id'] == '1'): ?>checked<?php endif; ?> type="radio">
                  Hidup
                </label>
								<label>
								  <input name="status_dasar" id="sd2" value="4" <?php if ($nik['status_dasar_id'] == '4'): ?>checked<?php endif; ?> type="radio">
                  Hilang
                </label>
								<label>
								  <input name="status_dasar" id="sd3" value="3" <?php if ($nik['status_dasar_id'] == '3'): ?>checked<?php endif; ?> type="radio">
                  Pindah Ke Luar Desa
                </label>
								<label>
								  <input name="status_dasar" id="sd4" value="2" <?php if ($nik['status_dasar_id'] == '2'): ?>checked<?php endif; ?> type="radio">
                  Mati
                </label>
              </div>
						</div>
						<div class="form-group">
							<label for="tgl_peristiwa">Tanggal Peristiwa</label>
							<div class="input-group input-group-sm date">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input class="form-control input-sm pull-right" id="tgl_1" name="tgl_peristiwa" type="text" value="<?= $sekarang;?>">
							</div>
						</div>
						<div class="form-group">
							<label for="catatan">Catatan Peristiwa</label>
							<textarea id="catatan" name="catatan" class="form-control input-sm" placeholder="Catatan" style="height: 50px;"><?= $log_status_dasar['catatan'];?></textarea>
							<p class="help-block">*mati/hilang terangkan penyebabnya, pindah tuliskan alamat pindah</p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
			<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
		</div>
	</div>
</form>
