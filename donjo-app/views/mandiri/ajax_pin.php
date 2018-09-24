<script type="text/javascript" src="<?= base_url()?>assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?= base_url()?>assets/js/validasi.js"></script>
<script>
	$(function ()
	{
		$('.select2').select2()
	});
</script>
<form action="<?=$form_action?>" method="post" id="validasi">
	<div class='modal-body'>
		<div class="row">
			<div class="col-sm-12">
				<div class="box box-danger">
					<div class="box-body">
						<div class="form-group">
							<label for="nik">NIK / Nama Penduduk</label>
							<select class="form-control input-sm select2 required"  id="nik" name="nik" style="width:100%;">
								<option option value="">-- Silakan Cari NIK / Nama Penduduk --</option>
								<?php foreach ($penduduk as $data): ?>
									<option value="<?=$data['id']?>">NIK :<?=$data['nik']." - ".$data['nama']?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="form-group">
							<label class="control-label" for="pin">PIN</label>
							<input id="pin" name="pin" class="form-control input-sm" type="text" placeholder="PIN Warga"></input>
							<p class="help-block">*) Jika PIN tidak di isi maka sistem akan menghasilkan PIN secara acak.</p>
							<p class="help-block">**) 6 (enam) digit Angka.</p>
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
