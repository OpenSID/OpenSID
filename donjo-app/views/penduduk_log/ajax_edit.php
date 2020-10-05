<script type="text/javascript" src="<?= base_url()?>assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?= base_url()?>assets/js/validasi.js"></script>
<script type="text/javascript" src="<?= base_url()?>assets/js/localization/messages_id.js"></script>
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
							<label>Status dasar penduduk</label>
							<label>: <?= $log_status_dasar['status'] ?></label>
						</div>
						<?php if ($log_status_dasar['status_id'] == 3): ?>
							<div class="form-group pindah">
								<label for="ref_pindah">Tujuan Pindah</label>
								<select  name="ref_pindah" class="form-control select2 input-sm required">
									<option value="">Pilih Tujuan Pindah</option>
									<?php foreach ($list_ref_pindah AS $data): ?>
										<option value="<?=$data['id']?>" <?php selected($data['id'], $log_status_dasar['ref_pindah'])?>><?=$data['nama']?></option>
									<?php endforeach; ?>
								</select>
							</div>
						<?php endif; ?>
						<div class="form-group">
							<label for="tgl_peristiwa">Tanggal Peristiwa</label>
							<div class="input-group input-group-sm date">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input class="form-control input-sm pull-right" id="tgl_1" name="tgl_peristiwa" type="text" value="<?= $log_status_dasar['tgl_peristiwa'];?>">
							</div>
						</div>
						<div class="form-group">
							<label for="catatan">Catatan Peristiwa</label>
							<textarea id="catatan" name="catatan" class="form-control input-sm" placeholder="Catatan" rows="5" style="resize:none;"><?= $log_status_dasar['catatan'] ?></textarea>
							<span class="help-block"><code>*mati/hilang terangkan penyebabnya, pindah tuliskan alamat pindah</code></span>
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
