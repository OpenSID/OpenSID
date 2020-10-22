<script type="text/javascript" src="<?= base_url()?>assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?= base_url()?>assets/js/validasi.js"></script>
<script type="text/javascript" src="<?= base_url()?>assets/js/localization/messages_id.js"></script>
<script>
	$(function ()
	{
		$('.select2').select2()
	})
</script>
<form action="<?= $form_action?>" method="post" id="validasi">
	<div class="modal-body">
		<div class="row">
			<div class="col-sm-12">
				<div class="box box-danger">
					<div class="box-body">
						<div class="form-group">
							<label for="nik_kepala">Kepala Keluarga (dari penduduk yang tidak memiliki No. KK)</label>
							<?php if ($penduduk): ?>
								<select class="form-control input-sm required select2" id="nik_kepala" name="nik_kepala" style="width:100%;">
									<option value="">-- Silakan Cari NIK / Nama Kepala Keluarga --</option>
									<?php foreach ($penduduk as $data): ?>
										<option value="<?= $data['id']?>">NIK :<?= $data['nik']." - ".$data['nama']?></option>
									<?php endforeach; ?>
								</select>
							<?php else: ?>
								<div class="alert alert-danger" role="alert">
									<p>Tidak ada penduduk 'lepas' yang bukan anggota Keluarga. Silakan masukkan dulu data penduduk yang akan ditambahkan atau pecahkan dari KK yang ada. Pastikan penduduk tersebut tidak diisi data nomor KK-nya.</p>
								</div>
							<?php endif; ?>
						</div>
						<?php if ($penduduk): ?>
							<div class="form-group">
								<label for="nik_kepala">Nomor Kartu Keluarga (KK)</label>
								<input id="no_kk" class="form-control input-sm nik required" type="text" placeholder="Nomor KK" name="no_kk"></input>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
		<?php if ($penduduk): ?>
			<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
		<?php endif; ?>
	</div>
</form>
