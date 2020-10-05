<script type="text/javascript" src="<?= base_url()?>assets/js/script.js"></script>
<script type="text/javascript" src="<?= base_url()?>assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?= base_url()?>assets/js/validasi.js"></script>
<script type="text/javascript" src="<?= base_url()?>assets/js/localization/messages_id.js"></script>
<script>
	$('document').ready(function()
	{
		$('#status_dasar').change(function()
		{
			if ($(this).val() == '3')
			{
				$('.pindah').show();
				$("select[name='ref_pindah']").addClass('required');
			}
			else
			{
				$('.pindah').hide();
				$("select[name='ref_pindah']").removeClass('required');
			}
		});
		$('#status_dasar').trigger('change');
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
							<label for="status_dasar">Status Dasar Baru</label>
							<select id="status_dasar" name="status_dasar" class="form-control select2 input-sm required">
								<option value="">Pilih Status Dasar</option>
								<?php foreach ($list_status_dasar AS $data): ?>
									<option value="<?=$data['id']?>" <?php selected($data['id'], $nik['status_dasar_id'])?>><?=$data['nama']?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="form-group pindah">
							<label for="ref_pindah">Tujuan Pindah</label>
							<select  name="ref_pindah" class="form-control select2 input-sm required">
								<option value="">Pilih Tujuan Pindah</option>
								<?php foreach ($list_ref_pindah AS $data): ?>
									<option value="<?=$data['id']?>" <?php selected($data['id'], $nik['ref_pindah'])?>><?=$data['nama']?></option>
								<?php endforeach; ?>
							</select>
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
